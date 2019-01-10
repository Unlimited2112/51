<?php

require_once ('../Classes/initializer.php');

define('DEBUG_MODE_PROCESS_DAY', 1);

$currentDay = DEBUG_MODE_PROCESS_DAY ? Core::getInstance()->Settings->getSetting('debug_current_date') : date('Y-m-d');

$finePercentDay = Core::getInstance()->Settings->getSetting('fine') / 100 / 365;

$db = Core::getInstance()->DataBase;

$query = "
    SELECT c.*,
    (
        ifnull((select sum(cp.amount) from wf_credits_plan cp where cp.credit_id = c.id AND cp.date < '$currentDay'),0) + c.fine
        -
        ifnull((select sum(cf.amount) from wf_credits_fact cf where cf.credit_id = c.id AND cf.is_fine <> 1),0)
        ) dept
    FROM wf_credits c
    WHERE c.paid = 0 AND c.date_start <= '$currentDay'
    having dept > 0
";

$res = $db->selectCustomSql($query);

foreach ($res as $credit) {
    $credit['fine'] += round($finePercentDay * $credit['dept'], 2);
    Core::getInstance()->getModel('Credits')->updateItem($credit['id'], array('fine' => $credit['fine']));
}


if (DEBUG_MODE_PROCESS_DAY) {
    $nextDay = date('Y-m-d', strtotime('+1 day', strtotime($currentDay)));
    $db->updateSql('wf_settings', array('value' => $nextDay), array('system' => 'debug_current_date'));
}

echo 'Current Day: ' . $currentDay;