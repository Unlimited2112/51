<?php

class CreditsFact extends Model
{
	public function __construct()
	{
		parent::__construct('CreditsFact', 'wf_credits_fact');
		
		$this->uri = false;
		$this->savetime = false;
	}


	public function getFields()
	{
		$fields = array(
			'amount' => array(
				'type' => 'text' ,
				'validator' => array(
					'isRequired' => true,
					'minValue' => 1
				)
			),
			'date' => array(
				'type' => 'date' ,
				'validator' => array(
					'isRequired' => true,
					'isDate' => true,
				),
				'add' => date('Y-m-d')
			),
		);

		return $fields;
	}


	/**
	 * Custom On Add Validation
	 *
	 * @param array $arr
	 * @return bool
	 */
	public function onAdd(&$arr) {

		if (empty($arr['is_fine'])) {
			$creditId = $this->Core->currentView->page_params[1];
			$credit = (array) $this->Core->getModel('Credits')->getByID($creditId);

			if ($credit['fine'] > 0) {
				if ($credit['fine'] > $arr['amount']) { // целиком на пеню
					$arr['is_fine'] = 1;
					$credit['fine'] -= $arr['amount'];
					$this->Core->getModel('Credits')->updateItem($creditId, $credit);
				} else {
					$arrFine = $arr;
					$arrFine['amount'] = $credit['fine'];
					$arrFine['is_fine'] = 1;
					$this->addItem($arrFine);
					$credit['fine'] = 0;
					$this->Core->getModel('Credits')->updateItem($creditId, $credit);
					$arr['amount'] -= $arrFine['amount'];
				}
			}
		}

		$arr = $this->updateData($arr);
		return true;
	}

	public function onUpdate($item_id, &$arr) {
		$arr = $this->updateData($arr);
		return true;
	}

	protected function updateData($arr) {
		if ($arr['is_fine']) {
			$arr['title'] = $arr['date'] . '. Оплата пени ' . $arr['amount'] . ' рублей';
		} else {
			$arr['title'] = $arr['date'] . '. Оплата кредита ' . $arr['amount'] . ' рублей';
		}
		return $arr;
	}


	/**
	 * Custom After Add Validation
	 *
	 * @param array $arr
	 * @return bool
	 */
	public function afterAdd($item_id, &$arr) {

		$query = "
			SELECT
			(
				ifnull((select sum(cp.amount) from wf_credits_plan cp where cp.credit_id = c.id),0) + c.fine
				-
				ifnull((select sum(cf.amount) from wf_credits_fact cf where cf.credit_id = c.id AND cf.is_fine <> 1),0)
				) dept
			FROM wf_credits c
			WHERE c.id = ".(int) $arr['credit_id']."
		";

		$res = $this->DataBase->selectCustomSql($query)->fetch();

		if ($res['dept'] <= 0) {
			$this->Core->getModel('Credits')->updateItem($arr['credit_id'], array('paid' => 1));
		} else {
			$this->Core->getModel('Credits')->updateItem($arr['credit_id'], array('paid' => 0));
		}

		return true;
	}
}