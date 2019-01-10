<?php

class InterviewPage extends FrontPage
{
	protected function initialize()
	{
		parent::initialize();

		if(!isset($_SESSION['interview_data']) || !is_array($_SESSION['interview_data'])) {
			$_SESSION['interview_data'] = array('finished' => false);
		}

		if(!sizeof($_POST)) {
			$_SESSION['interview_data'] = array('finished' => false);
		}

		$blocks = array(
			'InterviewStart' => 'Начало',
			'InterviewUserInfo' => 'Знакомство',
			'InterviewSlicing' => 'Верстка',
			'InterviewExpYears' => 'Годы опыта',
			'InterviewHireExp' => 'Опыт поиска команды',
			'InterviewPriceTask' => 'Оценка первой задачи',
			'InterviewPriceTaskBig' => 'Оценка второй задачи',
//			'InterviewHourRate' => 'Ставка в час',
			'InterviewPhpTest' => 'Проверка',
			'InterviewExperience' => 'Что доводилось делать',
			'InterviewWorkMode' => 'Режим работы',
			'InterviewFreelance' => 'Отзывы и попутные проекты',
			'Interview3Questions' => '3 вопроса',
			'InterviewFinish' => 'Конец',
		);

		$this->tplVars['block_list'] = $blocks;

		$blocksList = array();

		foreach($blocks as $className => $name) {
			Loader::loadBlock($className, 'Front');
			$control = new $className($name); /**@var $control InterviewControl*/
			try {
				$control->checkFinish();
			}
			catch(RejectCandidate_Exception $e) {
				$_SESSION['interview_data']['finished'] = true;
				$_SESSION['interview_data']['reject_reason'] = $e->getMessage();
			}
			$blocksList[] = $control;
		}

		foreach($blocksList as $control) { /**@var $control InterviewControl*/
			if(!$control->isFinished()) {
				$control->onShow();
				BlocksRegistry::getInstance()->registerBlock('InterviewItem', $control);
				$this->tplVars['current_step'] = get_class($control);
				break;
			}
		}
	}
}