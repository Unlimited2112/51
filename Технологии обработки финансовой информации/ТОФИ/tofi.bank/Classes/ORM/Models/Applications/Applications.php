<?php

class Applications extends Model
{
	public function __construct()
	{
		parent::__construct('Applications', 'wf_applications');
		
		$this->uri = false;
		$this->savetime = false;
	}

    /**
     * Custom On Add Validation
     *
     * @param array $arr
     * @return bool
     */
    public function onAdd(&$arr) {
        $arr = $this->updateData($arr);
        $arr['user_id'] = $this->Core->User->UserData['id'];
        $arr['cdate'] = date('Y-m-d H:i:s');
        $arr['udate'] = date('Y-m-d H:i:s');
        return true;
    }

    public function onUpdate($item_id, &$arr) {

        $statuses = array(
            0 => 'Черновик',
            1 => 'Отправлена (этап 1)',
            2 => 'Проверена специалистом по работе с клиентами (этап 2)',
            3 => 'Одобрена сотрудником службы безопасности (этап 3)',
            4 => 'Одобрена членом кредитного комитета (этап 4)',
            5 => 'Утверждена начальником кредитного отдела (этап 5)',
            6 => 'Операционист выдал деньги (этап 6)',
            100 => 'Отказано',
        );

        $errorText = 'Вы можете только отклонить или одобрить заявку';

        if ($this->Core->User->UserData['id_level'] == USER_LEVEL_USER ) {
            $res = $this->getByID($item_id);
            if ($res['status'] != 0) {
                $this->lastError = 'Нельзя менять отправленные заявки';
                return false;
            } elseif ($arr['status'] != 1 && $arr['status'] != 0) {
                $this->lastError = 'Пользователи могут только отправлять заявки или редактировать черновики';
                return false;
            }
        } elseif ($this->Core->User->UserData['id_level'] == USER_LEVEL_CLIENT_SERVICES ) {
            if (!in_array($arr['status'], array(1, 2, 100))) {
                $this->lastError = $errorText;
                return false;
            }
        } elseif ($this->Core->User->UserData['id_level'] == USER_LEVEL_SECURITY ) {
            if (!in_array($arr['status'], array(2, 3, 100))) {
                $this->lastError = $errorText;
                return false;
            }
        } elseif ($this->Core->User->UserData['id_level'] == USER_LEVEL_CREDIT_COMMITTEE ) {
            if (!in_array($arr['status'], array(3, 4, 100))) {
                $this->lastError = $errorText;
                return false;
            }
        } elseif ($this->Core->User->UserData['id_level'] == USER_LEVEL_CREDIT_DEPARTMENT_MANAGER ) {
            if (!in_array($arr['status'], array(4, 5, 100))) {
                $this->lastError = $errorText;
                return false;
            }
        } elseif ($this->Core->User->UserData['id_level'] == USER_LEVEL_CREDIT_OPERATOR ) {
            if (!in_array($arr['status'], array(5, 6, 100))) {
                $this->lastError = $errorText;
                return false;
            }
        }

        $arr = $this->updateData($arr);
        $arr['udate'] = date('Y-m-d H:i:s');

        if ($arr['status'] == 6) {
            $this->createCredit($item_id, $arr);
        }

        return true;
    }

    protected function updateData($arr) {
        $arr['percent'] = $arr['percent'] ?: $this->Core->Settings->getSetting('percent_year');
        $arr['title'] = 'Кредит на ' . $arr['term_months'] . ' месяцев под ' . $arr['percent'] . '%';
        return $arr;
    }

    protected function createCredit($item_id, $application)
    {
        $credits = Core::getInstance()->getModel('Credits'); /**@var Credits $credits*/
        if (!$credits->getCount(array('application_id' => $application['id']))) {

            $creditInfo = $credits->getCreditInfo(
                $application['term_months'],
                $application['amount'],
                $application['percent']
            );

            $arr = array();
            $arr['date_start'] = $creditInfo['date_start'];
            $arr['date_end'] = $creditInfo['date_end'];
            $arr['application_id'] = $item_id;
            $arr['title'] = $application['title'];
            $arr['fio'] = $application['fio'];
            $arr['amount'] = $creditInfo['amount'];
            $arr['user_id'] = $this->Core->User->UserData['id'];
            $creditId = $credits->addItem($arr);

            $CreditsPlan = Core::getInstance()->getModel('CreditsPlan');
            foreach ($creditInfo['plans'] as $plan) {
                $payArr = array();
                $payArr['amount'] = $plan['amount'];
                $payArr['date'] = $plan['date'];
                $payArr['title'] = $payArr['date'] . '. Оплата ' . $payArr['amount'] . ' рублей';
                $payArr['credit_id'] = $creditId;
                $CreditsPlan->addItem($payArr);
            }
        }
    }
}