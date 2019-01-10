<?php
define('USER_LEVEL_GUEST', 0);
define('USER_LEVEL_USER', 10);
define('USER_LEVEL_CLIENT_SERVICES', 11);
define('USER_LEVEL_SECURITY', 12);
define('USER_LEVEL_CREDIT_COMMITTEE', 13);
define('USER_LEVEL_CREDIT_DEPARTMENT_MANAGER', 14);
define('USER_LEVEL_CREDIT_OPERATOR', 15);
define('USER_LEVEL_ADMIN', 100);
define('USER_LEVEL_SUPERVISOR', 150);
define('USER_LEVEL_MANAGER', 160);
define('USER_LEVEL_ARHITECTOR', 255);

/*
			2 => 'Проверена специалистом по работе с клиентами (этап 2)',
			3 => 'Одобрена сотрудником службы безопасности (этап 3)',
			4 => 'Одобрена членом кредитного комитета (этап 4)',
			5 => 'Утверждена начальником кредитного отдела (этап 5)',
			6 => 'Операционист выдал деньги (этап 6)',
 */

class User extends Model
{
	public $UserData;

    const SALT = 'hrwf6';
    const SALT_ADD_TASK = 'sfdsdf';
	
	function __construct()
	{
		parent::__construct(
			'User',
			'wf_me_members',
			'CREATE TABLE wf_me_members (
				id				int AUTO_INCREMENT not null,
				id_level			int not null,
				password			varchar(64) not null,
				restored_password		varchar(64),
				login				varchar(64) not null,
				title				varchar(256) not null,
				email				varchar(256) not null,
				phone				varchar(256) not null,
				address			varchar(256) not null,
				uri				text,
				status				int not null default 0,
				primary key			(id, id_level, status),
				unique index 			(id, status),
				unique index 			(id),
				index 				(status)
			) ENGINE='.DB_ENGINE.' DEFAULT CHARSET='.DB_CHARSET.';'
		);
		$this->checkAuthorization();
	}

	function updateAuthorization($id, &$arr)
	{
		if(!isset($id) or !isset($this->UserData) or !isset($arr) or $id != InCookie('logined_user_id', null))
		{
			return false;
		}
		if(!isset($arr['password']) or empty($arr['password']))		
		{
			$res = $this->DataBase->selectSql('wf_me_members', array('id' => $id));
			if($res->rowCount())
			{
				$res = $res->fetch();
				$arr['password'] = $res['password'];
				return $this->setAuthorization($arr['login'], $arr['password'], true, true, null);				
			}
			else 
			{
				return false;
			}
		}
		else 
		{
			return $this->setAuthorization($arr['login'], md5($arr['password']), true, true, null);
		}
	}

	function setAuthorization($login, $password, $set_cookies =false, $store =true, $sql_ans =null)
	{
		if(is_array($sql_ans)) {
			$sql_ans_row = $sql_ans;
		}
		elseif(is_null($sql_ans)) {
			$sql_ans = $this->Core->DataBase->selectSql($this->dbName, array('status' =>2, 'login' =>$login, 'restored_password' =>$password));
			if($sql_ans->rowCount()) { // по восстановлению идут, ок
				$sql_ans_row = $sql_ans->fetch();
				$this->DataBase->updateSql($this->dbName, array('password' => $sql_ans_row['restored_password'], 'status' => 1, 'restored_password'=>''), array('id' => $sql_ans_row['id']));
			}
			else {
				$sql_ans = $this->Core->DataBase->selectSql($this->dbName, array('status' => array('<>', 0), 'login' =>$login, 'password' =>$password));
				if($sql_ans->rowCount()) { // есть такой юзер
					$sql_ans_row = $sql_ans->fetch();
					$this->DataBase->updateSql($this->dbName, array('status' => 1, 'restored_password'=>''), array('id' => $sql_ans_row['id']));
				}
			}
		}
		
		if(isset($sql_ans_row)) {
			$this->UserData['id'] = $sql_ans_row['id'];
			list($login, $password) = array($sql_ans_row['login'], $sql_ans_row['password']);
			foreach($sql_ans_row as $key => $value) {
				if(strtolower($key) != 'password')
				{
					$this->UserData[$key] =$value;
				}
			}
		}
		else {
			return $this->resetAuthorization();
		}

		if($set_cookies)
		{
			// set tv
			$this->setLoggedVariables($this->Core->tplVars);

			// get data for cookies
			$hash	= md5(implode(':', array($login, $password)));
			$ip	= md5($this->get_real_ip());
			$id	= $this->UserData['id'];
			$time = time()+24*60*60*30;

            $login_hash = md5($this->UserData['id'] . self::SALT);
            
			@setcookie('logined_user_hash_string', $login_hash, $time, '/', Config::$siteUrl);
			@setcookie('logined_user_id', $id, $time, '/', Config::$siteUrl);
			SetCacheVar('logined_user_hash', $hash, 'users');
			SetCacheVar('logined_user_up', $ip, 'users');
		}
		
		return true;
	}

	function resetAuthorization()
	{
		$this->UserData =array();

		@setcookie('logined_user_id', -1, time(), '/', Config::$siteUrl);
        @setcookie('logined_user_hash_string', null, time(), '/', Config::$siteUrl);
		SetCacheVar('logined_user_hash', '', 'users');
		SetCacheVar('logined_user_up', '', 'users');

		return false;
	}

	function get_real_ip()
	{
		foreach(array(
			'HTTP_X_FORWARDED_FOR',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'HTTP_VIA',
			'HTTP_X_COMING_FROM',
			'HTTP_COMING_FROM',
			'REMOTE_ADDR') as $key)
		{
			if(getenv($key))
			{
				return getenv($key);
			}
		}
	}

	function checkAuthorization()
	{
		if (isset($_SESSION['UserData']))
		{
			$_SESSION['UserData']	=array();
		}
		
		$this->UserData 		=& $_SESSION['UserData'];
		$uid					=(InCookie('logined_user_id', null) !=null)? (integer) InCookie('logined_user_id', null): -1;
		$logined_user_hash_string = (InCookie('logined_user_hash_string', null) !=null)? InCookie('logined_user_hash_string', null): -1;
		$this->UserData['id']	=$uid;

		if($uid <0)
		{
			return false;
		}

		if(! ($ans =$this->Core->DataBase->selectSql($this->dbName, array('status' =>1,  'id' =>$uid))) or !$ans->rowCount())
		{
			return $this->resetAuthorization();
		}
		$ans_row = $ans->fetch();

        $ans_hash = md5($ans_row['id'] . self::SALT);
        if($logined_user_hash_string != $ans_hash) {
			return $this->resetAuthorization();
        }

		return $this->setAuthorization(null, null, false, false, $ans_row);
	}

	function setLoggedVariables(&$tplVars)
	{
		if(!$this->isLogged())
		{
			$tplVars['is_logged']			=false;
			$tplVars['logged_user_id']		=-1;
			return false;
		}
			
		$tplVars['is_logged']				=true;
		
		foreach($this->UserData as $key =>$value)
		{
			$tplVars['logged_user_'.$key]	=$value;
		}

		$tplVars['is_global_admin']			=($this->UserData['id_level'] == USER_LEVEL_ARHITECTOR);
	}
		
	function isLogged($id_level =null)
	{
		return (
					isset($this->UserData['id']) 
				and 
					$this->UserData['id'] > 0 
				and 
					(
						!isset($id_level) 
					or 
						(
							isset($id_level) 
						and 
							$this->UserData['id_level'] == $id_level
						)
					)
				);
	}

	// login action
	function login($login =null, $password =null, $store =true)
	{
		$this->lastError ='';
		
		if(! $this->setAuthorization($login, md5($password), true, $store))
		{
			$this->lastError = $this->Core->Localizer->getString('login_no_such_user');
			return false;
		}

		return true;
	}

	function logout()
	{
		return !$this->resetAuthorization();
	}

	function loginFormCheck()
	{
		Form::addForm('login_form');
		Form::addField('login_form', new FormFieldRequired('login_form_name', array('minLength' => 4, 'maxLength' => 64)));
		Form::addField('login_form', new FormFieldRequired('login_form_password', array('minLength' => 4, 'maxLength' => 64), 'password'));
		Form::addField('login_form', new FormField('login_form_store', array('default' => 0), 'hidden'));
		if (Form::isSubmited('login_form'))
		{
			if (Form::validate('login_form'))
			{
				$this->Core->currentLang = InPost('admin_area_id_lang', 1);
				SetCacheVar('admin_area_id_lang', $this->Core->currentLang);
				
				$l = $this->tplVars['login_form_name'];
				$p = $this->tplVars['login_form_password'];
				$s = (intval($this->tplVars['login_form_store']) != 0);
				//$s = 0;
				
				if (!$this->login($l, $p, $s))
				{
					$this->logout();
					
					Loader::loadBlock('ArrayOutput', 'Helper');
					BlocksRegistry::getInstance()->registerBlock('ArrayOutput', new ArrayOutput());
					
					$this->tplVars['error'][] = $this->getLastError();
					
					return false;
				}
				else
				{
					if (InGetPost('post_login_action', '') != '')
					{
						Core::redirect(InGetPost('post_login_url', ''));
					}
					else
					{
						Core::redirect($this->tplVars['HTTP'].'admin/');
					}
				}
			}
			else
			{
				Loader::loadBlock('ArrayOutput', 'Helper');
				BlocksRegistry::getInstance()->registerBlock('ArrayOutput', new ArrayOutput());
				
				$this->tplVars['login_form_errors'] = Form::getFormErrors('login_form');
			}
		}
		return true;
	}

	function logoutFormCheck()
	{
		if (Form::isSubmited('logout_form'))
		{
			$this->logout();
			$this->tplVars['is_not_first'] = true;
			
			$_SESSION = array();
			if(!isset($_POST['logout_form_no_redirect'])) {
				Core::redirect($this->tplVars['HTTP']. $this->tplVars['current_page_lang_str']);
			}
		}
		return false;
	}
	
	function isAllowed($page_name, $id =null)
	{
		if(!isset($id) or (integer)$id ==0)
		{
			$id	= $this->UserData['id'];
			if($this->UserData['id_level'] >= USER_LEVEL_USER)
			{
				return true;
			}
		}

		$ans = $this->Core->DataBase->selectSql('wf_me_members_pages', array('allowed_page' =>$page_name, 'id_member' =>$id), 0, array('id'));
		
		return $ans->rowCount();
	}

	function addPermission($page_name, $id =null)
	{
		if(!isset($id) or (integer)$id ==0)
		{
			$id =$this->UserData['id'];
		}
		
		$ans =$this->Core->DataBase->selectSql('wf_me_members_pages', array('allowed_page' =>$page_name, 'id_member' =>$id), 0, array('id'));

		if (!$ans->rowCount()) 
		{
			return $this->Core->DataBase->insertSql('wf_me_members_pages', array('id_member' =>$id, 'allowed_page' =>$page_name));
		}
	}

	function removePermission($page_name, $id = null)
	{
		if(isset($id) and $id >0)
		{
			$this->Core->DataBase->deleteSql('wf_me_members_pages', array('id_member' => $id, 'allowed_page' =>$page_name));
		}
	}

	function onAdd(&$arr)
	{
		foreach(array('login', 'email') as $variable)
		{
			$ans = $this->Core->DataBase->selectSql($this->dbName, array($variable => $arr[$variable]), 0);
			if($ans->rowCount())
			{
				$this->lastError = $this->Core->Localizer->getString('error_'.$variable.'_exist');
				return false;
			}
		}
		
   		if ($arr['password'] !=$arr['cpassword']){
			$this->lastError	=$this->Core->Localizer->getString('error_pass_confirm');
			return false;
		}
		
		$arr['password']		=md5((! isset($arr['password']))? randomizePassord(): $arr['password']);
		unset($arr['cpassword']);
		
		if(! isset($arr['id_level'])){
			$arr['id_level']	=USER_LEVEL_USER;
		}

		$this->updateAuthorization(null, $arr);
	
		return true;
	}

	function onUpdate($item_id, &$arr)
	{
		foreach(array('login', 'email') as $variable)
		{
			$ans = $this->Core->DataBase->selectSql($this->dbName, array($variable =>$arr[$variable], $this->dbId =>array('<>', $item_id)), 0);

			if($ans->rowCount())
			{
				$this->lastError	=$this->Core->Localizer->getString('error_'.$variable.'_exist');
				return false;
			}
		}

		if($arr['password'] != '' and $arr['password'] !=$arr['cpassword'])
		{
			$this->lastError	=$this->Core->Localizer->getString('error_pass_confirm');
			return false;
		}
		
		if(isset($arr['password']) and !empty($arr['password']))
		{
			$arr['password']	=md5($arr['password']);	
		}
		else
		{
			unset($arr['password']);
		}
		
		if (!( ($_SESSION['UserData']['id_level'] >=USER_LEVEL_ADMIN) and ($_SESSION['UserData']['id'] !=$item_id))) 
		{
			unset($arr['id_level']);
			unset($arr['status']);
		}		

		unset($arr['cpassword']);

		$this->updateAuthorization($item_id, $arr);

		return true;
	}
	
	function onDelete($id)
	{
		$null =null;
		$this->updateAuthorization($id, $null);

		return true;
	}
	
	function updateProfile($id, $arr)
	{
		$ans =$this->Core->DataBase->selectSql($this->dbName, array('login' =>$arr['login'], $this->dbId =>array('<>', $id)), 0);

		if($ans->rowCount())
		{
			$this->lastError	=$this->Core->Localizer->getString('error_login_exist');
			return false;
		}

		if($arr['password'] != '' and $arr['password'] !=$arr['cpassword'])
		{
			$this->lastError	=$this->Core->Localizer->getString('error_pass_confirm');
			return false;
		}

		$update_array =array('login' =>$arr['login']);
		
		if($arr['email'] !='')
		{
			$update_array['email']		=$arr['email'];
		}

		if($arr['password'] !='')
		{
			$update_array['password']	=md5($arr['password']);
		}

		$return =$this->DataBase->updateSql($this->dbName, $update_array, array($this->dbId => $id));
		
		$this->updateAuthorization($id, $arr);

		return $return;
	}
		
	function install()
	{
		$this->DataBase->exec('DROP TABLE IF EXISTS '.$this->dbName);
		$this->DataBase->exec($this->dbSql);

		$user_arr = array(
			'id_level' => USER_LEVEL_ARHITECTOR,
			'status' => 1,
			'login' => 'system',
			'password' => 'ghbvfhb',
			'cpassword' => 'ghbvfhb',
			'email' => 'golubev@golubev.eu',
		);

		$this->addItem($user_arr);

		$user_arr = array(
			'id_level' => USER_LEVEL_SUPERVISOR,
			'status'   => 1,
			'login'    => 'supervisor',
			'password' => 'supervisor',
   			'cpassword' => 'supervisor',
	        		'email' => 'supervisor@supervisor.com'
		);
		
		$this->addItem($user_arr);
				
		$this->DataBase->exec('DROP TABLE IF EXISTS wf_me_members_pages');
		
		$this->DataBase->exec('CREATE TABLE wf_me_members_pages (
			id				int AUTO_INCREMENT not null,
			id_member		int not null,
			allowed_page		varchar(256) not null,
			primary key		(id),
			unique index 		(id)
		)');
				
		return true;
	}
}

function randomizePassord($size = 10, $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
	list($usec,$sec) = explode(' ', microtime());
    $srandval = ((float)$sec+(float)$usec*100000);
    mt_srand($srandval);

	$result = '';
  	$chars_len = strlen($chars)-1;
  	for ($i = 0; $i < $size; $i++)
		$result .= $chars{mt_rand(0, $chars_len)};
  	return $result;
}