<?php

function compare_arrays($items, $input_items) {
	foreach($items as $i => $item) {
		$item_amount = (float) str_replace(',', '.', $item[0]);
		$item_currency = $item[1];
		$item_date = $item[3];

		foreach($input_items as $j => $input_item) {
			$input_item_amount = $input_item[0];
			$input_item_currency = $input_item[2];
			$input_item_date = $input_item[1];

			if(
				$item_amount == $input_item_amount &&
				$item_currency == $input_item_currency &&
				$item_date == $input_item_date
			) {
				unset($items[$i]);
				unset($input_items[$j]);
				break;
			}
		}
	}

	go_from_handmade($items, $input_items);

	echo "Лишные платежи в кудире: <br/>";
	foreach($items as $item) {
		echo $item[3], ' ', $item[0] . ' ' . $item[1] . '<br/>';
	}
	echo "Неучтенные платежи: <br/>";
	foreach($input_items as $item) {
		echo $item[1], ' ', $item[0] . ' ' . $item[2] . '<br/>';
	}
}

function go_from_handmade(&$items, &$input_items) {
	foreach($items as $i => $item) {

		// платежи до 2013 не учитываем
		if($item[3] == '02.10.2012' || $item[3] == '28.12.2012') {
			unset($items[$i]);
		}

		// 2 платежа разбиты в учете, но пришли одним платежом на WMZ
		if($item[3] == '10.09.2013' && $item[0] == 42 && $item[1] == 'WMZ') {
			unset($items[$i]);
		}
		if($item[3] == '10.09.2013' && $item[0] == '190,48' && $item[1] == 'WMZ') {
			unset($items[$i]);
		}
	}

	foreach($input_items as $i => $item) {

		// 2 платежа разбиты в учете, но пришли одним платежом на WMZ
		if($item[1] == '10.09.2013' && $item[0] == 232.48 && $item[2] == 'WMZ') {
			unset($input_items[$i]);
		}
		// проверка перевода, по платежу был оформлен возврат
		if($item[1] == '02.07.2013' && $item[0] == 50 && $item[2] == 'WMR') {
			unset($input_items[$i]);
		}
	}
}

function get_from_bank_account_current_for_saldo($filename) {
	$items = array();
	$data_array = get_file_content_foreign($filename);

	foreach($data_array as $item) {
		$parsed_item = get_parsed_item_foreign($item, false);
		if(strpos($parsed_item[0], mb_convert_encoding('Исходящее', 'cp1251', 'utf8')) !== false) {
		    if(strpos($parsed_item[0], mb_convert_encoding('сальдо', 'cp1251', 'utf8')) !== false) {
                $items[$parsed_item[1]] = (float) str_replace(',', '', $parsed_item[4]);
            }
		}
	}

	return $items;
}

function get_from_bank_account_current($filename) {
	$items = array();
	$data_array = get_prepared_file_content_foreign($filename);
    
	foreach($data_array as $item) {
		$parsed_item = get_parsed_item_foreign($item);
//        var_dump($parsed_item);
		if($parsed_item[5] !== '0.00') {

            $params = array();
            $params['amount'] = (float) str_replace(',', '', $parsed_item[5]);
            $params['currency'] = $parsed_item[3];

            $params['date'] = implode('-', array_reverse(explode('.', $parsed_item[0])));
            $params['type'] = 1; // bank
            $params['comment'] = del_double_spaces($parsed_item[8]);
            $params['swift'] = $parsed_item[1];

			$items[] = $params;
		}
	}

	return $items;
}

function get_from_bank_account($filename) {
	$items = array();
	$data_array = get_prepared_file_content_foreign($filename);

	foreach($data_array as $item) {
		$parsed_item = get_parsed_item_foreign($item);
		if($parsed_item[6] !== '0.00') {

            $params = array();
            $params['amount'] = (float) str_replace(',', '', $parsed_item[6]);
            $params['currency'] = $parsed_item[3];

            $params['date'] = implode('-', array_reverse(explode('.', $parsed_item[0])));
            $params['type'] = 1; // bank
            $params['comment'] = del_double_spaces($parsed_item[8]);
            $params['swift'] = $parsed_item[1];

			$items[] = $params;
		}
	}

	return $items;
}

function del_double_spaces($string) {
    while(strpos($string, '  ') !== false) {
        $string = str_replace('  ', ' ', $string);
    }
    return $string;
}

/*
CREATE TABLE `wf_reports_contracts_payments_kudir` (
  `id` int(9) unsigned NOT NULL AUTO_INCREMENT,
  `contract_payment_id` int(9) DEFAULT NULL,
  `amount` float(9,2) DEFAULT NULL,
  `currency` varchar(255) DEFAULT NULL,
  `swift` varchar(255) DEFAULT NULL,
  `datetime` datetime DEFAULT NULL,
  `date` date DEFAULT NULL,
  `type` int(9) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8
 */

function get_from_webmoney($file, $currency) {
	$items = array();
	$fp = fopen($file, 'r');
	fgets($fp); // проматываем
	while($row_cp1251 = fgetcsv($fp, null, ';')) {
		$row = row_to_utf($row_cp1251);
		if($row[1]) {
            $params = array();
            $params['amount'] = (float) str_replace(',', '.', $row[1]);
            $params['currency'] = $currency;

            $date_time = str_replace('.', '-', $row[0]);
            $params['datetime'] = $date_time;
            $params['date'] = date('Y-m-d', strtotime($date_time));
            $params['type'] = 2; // webmoney
            $params['comment'] = $row[6];

            $items[] = $params;
		}
	}
	fclose($fp);
	return $items;
}

function check_currencies($items) {
	foreach($items as $item) {
		foreach($item['currencies'] as $date => $currency) {
			$res = check_currency($item[1], $date, $currency);
			if(is_array($res)) {
				echo 'Неверный курс ' . $res[0] . ' верный курс ' . $res[1], ' ', $item[1], ' ', $date, ', сумма ', $item[0], ', ' . $item[2] . '<br />';
			}
		}
	}
}

function check_currency($currency_type, $date, $currency) {
	$currency_type = get_canonical_currency($currency_type);

	$currency = str_replace(' ', '', $currency);
	$currency = (float) str_replace(',', '.', $currency);
	$nbrb_currency = get_nbrb_currency($date, $currency_type);

	if($currency != $nbrb_currency) {
		return array($currency, $nbrb_currency);
	}
	return true;
}

function get_canonical_currency($currency) {
	$arr = array(
		'WMR' => 'RUB',
		'RUR' => 'RUB',
		'RUB' => 'RUB',
		'WME' => 'EUR',
		'EUR' => 'EUR',
		'WMZ' => 'USD',
	);
	return $arr[$currency];
}

function parse_post_payments($post_payments_file) {
	$fp = fopen($post_payments_file, 'r');
	$items = array();
	while($row_cp1251 = fgetcsv($fp, null, ';')) {
		$row = row_to_utf($row_cp1251);
		$item = array(
			$row[8],
			$row[7],
			'currencies' => array(
				$row[0] => $row[1],
			),
			'постоплата',
			$row[0],
		);

		foreach($row as $cell) {
			if(strpos($cell, 'Акт') !== false) {
				$date = get_date($cell);
				$item['currencies'][$date] = $row[6];
			}
		}
		$items[] = $item;
	}
	fclose($fp);
	return $items;
}

function parse_pre_payments($pre_payments_file) {
	$fp = fopen($pre_payments_file, 'r');
	$items = array();
	while($row_cp1251 = fgetcsv($fp, null, ';')) {
		$row = row_to_utf($row_cp1251);
		$item = array(
			$row[8],
			$row[7],
			'currencies' => array(
				$row[0] => $row[6],
			),
			'предоплата',
			$row[0],
		);

		foreach($row as $cell) {
			if(strpos($cell, 'Акт') !== false) {
				$date = get_date($cell);
				$item['currencies'][$date] = $row[1];
			}
		}
		$items[] = $item;
	}
	fclose($fp);
	return $items;
}

function get_date($cell) {
	$regExp = '~от (\d{2}\.\d{2}\.\d{4})~s';
	$matches = array();
	preg_match_all($regExp, $cell, $matches, PREG_SET_ORDER);
	if(!sizeof($matches) || !sizeof($matches[0])) {
		die('невозможно определить дату ' . $cell);
	}
	if(isset($matches[1][1])) {
		return $matches[1][1];
	}
	return $matches[0][1];
}

function row_to_utf($row) {
	$row_utf = array();
	foreach($row as $k => $v) {
		$row_utf[$k] = mb_convert_encoding($v, 'utf8', 'cp1251');
	}
	return $row_utf;
}

function get_file_content_foreign($filepath) {
	$content = file_get_contents($filepath);

	$pos = strpos($content, mb_convert_encoding('Входящее сальдо', 'cp1251', 'utf8'));
	$content = substr($content, $pos);

	$separator = '+-------+-----+-----+----+-----------+----------+----------+------------+------------+-+';

	$pos = strpos($content, $separator) + strlen($separator);
	$content = ltrim(substr($content, $pos));

    $content = str_replace(
        '+-------------------+----+-----------+----------+----------+------------+------------+-+',
        $separator, $content);

//	$pos = strpos($content, mb_convert_encoding('|Обороты', 'cp1251', 'utf8'));
//	$content = rtrim(substr($content, 0, $pos - strlen($separator) - 3));

	$data_array = explode($separator, $content);
	return $data_array;
}


function get_prepared_file_content_foreign($filepath) {
	$content = file_get_contents($filepath);

	$pos = strpos($content, mb_convert_encoding('Входящее сальдо', 'cp1251', 'utf8'));
	$content = substr($content, $pos);

	$separator = '+-------+-----+-----+----+-----------+----------+----------+------------+------------+-+';

	$pos = strpos($content, $separator) + strlen($separator);
	$content = ltrim(substr($content, $pos));

	$pos = strpos($content, mb_convert_encoding('|Обороты', 'cp1251', 'utf8'));
	$content = rtrim(substr($content, 0, $pos - strlen($separator) - 3));

	$data_array = explode($separator, $content);
	return $data_array;
}

function get_prepared_file_content($filepath) {
	$content = file_get_contents($filepath);

	$pos = strpos($content, mb_convert_encoding('Входящее сальдо', 'cp1251', 'utf8'));
	$content = substr($content, $pos);

	$separator = '+--------+-------+-----+-----+-------------+-----------------+------------------+';

	$pos = strpos($content, $separator) + strlen($separator);
	$content = ltrim(substr($content, $pos));

	$pos = strpos($content, mb_convert_encoding('|Обороты', 'cp1251', 'utf8'));
	$content = rtrim(substr($content, 0, $pos - strlen($separator) - 3));

	$data_array = explode($separator, $content);
	return $data_array;
}

function get_prepared_file_content_by_saldo($filepath) {
	$content = file_get_contents($filepath);

	$pos = strpos($content, mb_convert_encoding('Входящее сальдо', 'cp1251', 'utf8'));
	$content = substr($content, $pos);

	$separator = '+--------+-------+-----+-----+-------------+-----------------+------------------+';

	$pos = strpos($content, $separator) + strlen($separator);
	$content = ltrim(substr($content, $pos));

    $content = str_replace('+------------------------------------------+-----------------+------------------+', $separator, $content);

//	$pos = strpos($content, mb_convert_encoding('|Обороты', 'cp1251', 'utf8'));
//	$content = rtrim(substr($content, 0, $pos - strlen($separator) - 3));

	$data_array = explode($separator, $content);
	return $data_array;
}

function get_parsed_item_foreign($item, $echo_error=true) {
	$result = array_fill(0, 9, '');
	$lines = explode("\n", trim($item));
	foreach($lines as $line) {
		$positions = explode('|', ' ' . $line);
		array_shift($positions);
		array_pop($positions);
		if(!isset($positions[0])) {
            if($echo_error) {
			    var_dump($line);
            }
		}
		for($i=0;$i<9;$i++) {
			$result[$i] .= ' ' . (isset($positions[$i]) ? $positions[$i] : null);
		}
	}

	/* обработка */
	$result[0] = trim(str_replace(' ', '', $result[0])); // дата
	$result[1] = trim(str_replace(' ', '', $result[1])); // номер пп
	$result[2] = trim(str_replace(' ', '', $result[2])); // код операции
	$result[3] = trim(str_replace(' ', '', $result[3])); // код операции

	while(strpos($result[4], '  ') !== false) {
		$result[4] = str_replace('  ', ' ', $result[4]);
	}
	while(strpos($result[5], '  ') !== false) {
		$result[5] = str_replace('  ', ' ', $result[5]);
	}
	while(strpos($result[6], '  ') !== false) {
		$result[6] = str_replace('  ', ' ', $result[6]);
	}

	for($i=0;$i<9;$i++) {
		$result[$i] = trim($result[$i]);
	}

	return $result;
}


function get_parsed_item($item) {
	$result = array_fill(0, 7, '');
	$lines = explode("\n", trim($item));
	foreach($lines as $line) {
		$positions = explode(' |', ' ' . $line);
		array_shift($positions);
		array_pop($positions);
		if(!isset($positions[0])) {
			var_dump($line);
		}
		for($i=0;$i<7;$i++) {
			$result[$i] .= ' ' . $positions[$i];
		}
	}

	/* обработка */
	$result[0] = trim(str_replace(' ', '', $result[0])); // дата
	$result[1] = trim(str_replace(' ', '', $result[1])); // номер пп
	$result[2] = trim(str_replace(' ', '', $result[2])); // код операции
	$result[3] = trim(str_replace(' ', '', $result[3])); // код операции

	while(strpos($result[4], '  ') !== false) {
		$result[4] = str_replace('  ', ' ', $result[4]);
	}
	while(strpos($result[5], '  ') !== false) {
		$result[5] = str_replace('  ', ' ', $result[5]);
	}
	while(strpos($result[6], '  ') !== false) {
		$result[6] = str_replace('  ', ' ', $result[6]);
	}

	for($i=0;$i<7;$i++) {
		$result[$i] = trim($result[$i]);
	}

	return $result;
}

function get_nbrb_currency($date, $type) {
	$date = explode('.', $date);
	$year = $date[2];
	$month = $date[1];
	$day = $date[0];

	$date = $year . '-' . $month . '-' . $day;
	$url = 'http://nbrb.by/statistics/rates/ratesDaily.asp?date=' . $date;

	$page = @file_get_contents($url);
	if(!$page) {
		sleep(1);
		$page = @file_get_contents($url);
		if(!$page) {
			die('нет страницы по адресу ' . $url);
		}
	}

	$regExp = '~\<td class="titlecol"\>'.$type.'\</td\>\<td\>(.*?)\</td\>\<td class="textcol"\>.*?\</td\>\<td\>([^<]+?)</td>~s';

	$matches = array();
	preg_match_all($regExp, $page, $matches);

	if(!sizeof($matches) || !sizeof($matches[0])) {
		echo $regExp;
		echo $page;
		var_dump($matches);
		var_dump($type);
		die('ошибка получения валюты' . $url);
	}

	$count = (int) trim($matches[1][0]);
	$currency = $matches[2][0];
	$currency = preg_replace('~[^\d,]~s', '', $currency);
	$currency = str_replace(',', '.', $currency);
	$currency = (float) $currency;
	$currency /= $count;

	$ym = (int) ($year . $month);
	if ($ym >= 201607) {
		$currency *= 10000;
	}
	return $currency;
}


function get_from_by_bank_file_spent($filename) {
	$data_array = get_prepared_file_content($filename);

    $items = array();
	foreach($data_array as $item) {
		$parsed_item = get_parsed_item($item);
		if(is_debet_operation($parsed_item)) {
            $parsed_item = row_to_utf($parsed_item);

            $amount = array_shift(explode(' ', $parsed_item[5]));
            $params = array();
            $params['amount'] = (float) str_replace(',', '', $amount);

			$date = explode('.', $parsed_item[0]);
			$year = $date[2];
			$month = $date[1];
			$day = $date[0];
			$ym = (int) ($year . $month);
			if ($ym >= 201607) {
				$params['amount'] *= 10000;
				$params['amount'] = (string) $params['amount'];
			}

            $params['currency'] = 'BYR';

            $params['date'] = implode('-', array_reverse(explode('.', $parsed_item[0])));
            $params['type'] = 1; // банк

            $customer_comment = explode(' ', $parsed_item[5]);
            array_shift($customer_comment);
            $customer_comment = del_double_spaces(implode(' ', $customer_comment));

            $pay_comment = explode(' ', $parsed_item[6]);
            array_shift($pay_comment);
            $pay_comment = del_double_spaces(implode(' ', $pay_comment));

            $params['comment'] = $customer_comment . "\n" . $pay_comment;
            $params['swift'] = $parsed_item[1];

            if(strpos($params['comment'], 'ПЕРЕЧИСЛЕНИЕ ЛИЧНОГО ДОХОДА') === false) {
                continue;
            }

			$items[] = $params;
		}
	}

    return $items;
}


function get_from_by_bank_file($filename) {
	$data_array = get_prepared_file_content($filename);

    $items = array();
	foreach($data_array as $item) {
		$parsed_item = get_parsed_item($item);
		if(is_debet_operation($parsed_item)) {
			continue; // не обрабатываем платежи с моей стороны
		}
		if(is_change_operation($parsed_item)) {
            continue;
		}
		elseif(is_change_operation_by_bank($parsed_item)) {
            continue;
		}
		elseif(is_percent_operation($parsed_item)) {
            continue;
		}
		else {

            $parsed_item = row_to_utf($parsed_item);

            $amount = array_shift(explode(' ', $parsed_item[6]));
            $params = array();
            $params['amount'] = (float) str_replace(',', '', $amount);

			$date = explode('.', $parsed_item[0]);
			$year = $date[2];
			$month = $date[1];
			$day = $date[0];
			$ym = (int) ($year . $month);
			if ($ym >= 201607) {
				$params['amount'] *= 10000;
			}

            $params['currency'] = 'BYR';

            $params['date'] = implode('-', array_reverse(explode('.', $parsed_item[0])));
            $params['type'] = 1; // банк

            $customer_comment = explode(' ', $parsed_item[5]);
            array_shift($customer_comment);
            $customer_comment = del_double_spaces(implode(' ', $customer_comment));

            $pay_comment = explode(' ', $parsed_item[6]);
            array_shift($pay_comment);
            $pay_comment = del_double_spaces(implode(' ', $pay_comment));

            $params['comment'] = $customer_comment . "\n" . $pay_comment;
            $params['swift'] = $parsed_item[1];

			$items[] = $params;
		}
	}

    return $items;
}


function get_from_by_bank_file_for_saldo($filename) {
	$data_array = get_prepared_file_content_by_saldo($filename);

	$flag_phrase = iconv('utf-8', 'windows-1251', "Счет клиента 3013076050004 BYN Пассивный");
	$is_byn = (bool) strpos(file_get_contents($filename), $flag_phrase);

    $items = array();
	foreach($data_array as $item) {
        if(strpos($item, mb_convert_encoding('Исходящее сальдо', 'cp1251', 'utf8')) !== false) {

            $parsed_item = get_parsed_item_foreign($item, false);
            $parsed_item = row_to_utf($parsed_item);

            $amount = array_shift(explode(' ', $parsed_item[2]));
            $params = array();
            $params['amount'] = (float) str_replace(',', '', $amount);
            $params['currency'] = 'BYR';

            $params['date'] = implode('-', array_reverse(explode('.', $parsed_item[0])));
            $params['type'] = 1; // банк

            $customer_comment = explode(' ', $parsed_item[5]);
            array_shift($customer_comment);
            $customer_comment = del_double_spaces(implode(' ', $customer_comment));

            $pay_comment = explode(' ', $parsed_item[6]);
            array_shift($pay_comment);
            $pay_comment = del_double_spaces(implode(' ', $pay_comment));

            $params['comment'] = $customer_comment . "\n" . $pay_comment;
            $params['swift'] = $parsed_item[1];

			if ($is_byn) {
				$params['amount'] *= 10000;
			}
			$items[$params['currency']] = $params['amount'];
        }
	}

    return $items;
}


function is_change_operation_by_bank_with_course($parsed_item) {
	$pos = strpos($parsed_item[6], mb_convert_encoding('Курс продажи', 'cp1251', 'utf8'));
	return ($pos !== false);
}
function is_change_operation_by_bank($parsed_item) {
	$pos = strpos($parsed_item[5], mb_convert_encoding('Рублевый эквивалент валютной позиции по операциям Головного банка', 'cp1251', 'utf8'));
	return ($pos !== false);
}
function is_percent_operation($parsed_item) {
	$pos = strpos($parsed_item[6], mb_convert_encoding('Уплата процентов по остаткам', 'cp1251', 'utf8'));
	if($pos !== false) {
        return true;
    }
	$pos = strpos($parsed_item[6], mb_convert_encoding('Доначисление процентов по остаткам', 'cp1251', 'utf8'));
	return ($pos !== false);
}
function is_change_operation($parsed_item) {
	$pos = strpos($parsed_item[6], mb_convert_encoding('Рубл. эквивалент от продажи на ОАО БВФБ', 'cp1251', 'utf8'));
	return ($pos !== false);
}

function is_debet_operation($parsed_item) {
	$arr = explode(' ', $parsed_item[5]);
	return $arr[0] != "0.00";
}