<?php

class Candidates extends Model
{
	public function __construct()
	{
		parent::__construct('Candidates', 'wf_candidates');
		
		$this->uri = false;
		$this->savetime = false;
	}

    public function afterAdd($item_id, $arr) {
        $this->sendMail($item_id);
    }

    public function afterUpdate($item_id, $arr) {
        $this->sendMail($item_id);
    }

    public function getLinks($itemId, $params = array('session' => false, 'ip' => false, 'skype' => true, 'email' => true, 'title' => true)) {

        $item_res = $this->getByID($itemId);

        $result = array();
        foreach($params as $key => $full_content_search) {
            $res = $this->getByField($key, $item_res[$key], $itemId, $full_content_search);
            if($res !== false) {
                foreach($res as $row) {
                    if(!isset($result[$row['id']])) {
                        $linked_fields = $key;
                    }
                    else {
                        $linked_fields = $result[$row['id']]['linked_fields'] . ', ' . $key;
                    }
                    $result[$row['id']] = $row;
                    $result[$row['id']]['linked_fields'] = $linked_fields;
                }
            }
        }

        return $result;
    }

    public function sendMail($candidate_id) {
        $res = $this->getByID($candidate_id);
        $email_from = Core::getInstance()->Settings->getSetting('administrator_email');
        Loader::loadLib('Mail', 'Other');
        
        if($res['status'] == 4) { // отклонен
            if(!$res['notified']) {
                $subject = "К сожалению, Ваше предложение нам не подошло. " . Core::getInstance()->Settings->getSetting('captcha_key');
                $message = nl2br(trim('
Здравствуйте, ' . $res['title'] . '!
К сожалению, Ваше предложение нам не подошло. Для объективности интервью мы не можем разглашать информацию о причинах.
Большое спасибо за проявленный интерес и за Ваше время.
                '));
        		@Mail::sendEmail(array($email_from, Core::getInstance()->Settings->getSetting('captcha_key')), $res['email'], $subject, $message);

                $this->updateItem($candidate_id, array('notified' => 1));
            }
        }
        elseif($res['status'] == 2) { // кандидат
            if(!$res['notified']) {
                $subject = "Ваша кандидатура утверждена. " . Core::getInstance()->Settings->getSetting('captcha_key');
                $message = nl2br(trim('
Здравствуйте, ' . $res['title'] . '!
Спешим Вам сообщить, что Ваша анкета прошла ручную проверку, а это значит, что нам все подходит и, как только появится первая задача для Вас, мы сразу Вам напишем в скайп.
Обычно такая пробная задача появляется в течение недели-двух, иногда бывает и сразу. Как бы ни было, Вы нам очень интересны.
До скорого контакта.
                '));
        		@Mail::sendEmail(array($email_from, Core::getInstance()->Settings->getSetting('captcha_key')), $res['email'], $subject, $message);

                $this->updateItem($candidate_id, array('notified' => 1));
            }
        }
    }

    protected function getByField($key, $value, $currentId, $search_in_full_content=false) {

        if(!$value) {
            return false;
        }

        $add = '';
        if($search_in_full_content) {
            $add = " OR full_content like '%$value%'";
        }
        $query = "
            SELECT * FROM wf_candidates WHERE (`$key`=".$this->DataBase->quote($value)." $add ) AND id<>$currentId
        ";

//        echo $query . '<br />';

        $res = $this->DataBase->selectCustomSql($query)->fetchAll();

        return $res;
    }
}