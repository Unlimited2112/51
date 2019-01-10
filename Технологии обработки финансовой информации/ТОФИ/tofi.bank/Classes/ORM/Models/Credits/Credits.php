<?php

class Credits extends Model
{
	public function __construct()
	{
		parent::__construct('Credits', 'wf_credits');
		
		$this->uri = false;
		$this->savetime = false;
	}

	public function getCreditInfo($term_months, $amount, $percent)
	{
		$arr = array();
		$arr['date_start'] = date('Y-m-d');
		$arr['date_end'] = date('Y-m-d', strtotime('+' . $term_months . ' months'));
		$arr['amount'] = 0;
		$creditMonthAmount = round($amount / $term_months, 2);
		$percentBase = $amount;
//		$arr['amount'] = round($amount + $amount * $percent / 100 / 12 * $term_months, 2); // забыл добавить сумму
		$arr['plans'] = array();
		for ($i = 1; $i <= $term_months; $i++) {
			$payTime = date('Y-m-d', strtotime("+$i months"));
			$payArr = array();
			$percentAmount = round($percentBase  * $percent / 100 / 12, 2);
			$payArr['amount'] = $creditMonthAmount + $percentAmount;
			$payArr['date'] = $payTime;
			$payArr['credit_month_amount'] = $creditMonthAmount;
			$payArr['percent_month_amount'] = $percentAmount;
			$arr['plans'][] = $payArr;
			$arr['amount'] += $payArr['amount'];
			$percentBase -= $creditMonthAmount;
		}

		return $arr;
	}
}