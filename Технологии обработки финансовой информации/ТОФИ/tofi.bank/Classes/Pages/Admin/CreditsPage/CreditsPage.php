<?php
class CreditsPage extends ModelTemplate
{
	public function __construct()
	{
		parent::__construct('Credits');
	}

	protected function initTabs()
	{

		$this->MainTabName = 'Кредит';
		$this->addTab('Выплаты. План', "CreditsPlanEdit", "creditplan");
		$this->addTab('Выплаты. Факт', "CreditsFactEdit", "creditfact");
	}
}