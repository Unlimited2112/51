<?php
class TextMatcher {
	static public function matchWords(&$words, $text) {
		# $words в формате array(<слово> => <вес>)

		$sum_weight = 0;

		$text = TextMatcher::formatText($text);

		foreach($words as $settings) {

			$weight = $settings['k'];
			foreach($settings['words'] as $val) {
				$word = $val;

				$test_key = trim(mb_strtolower($word, 'utf-8'), '*');
				
				if($word[strlen($word) - 1] != '*') {
					$test_key = $test_key . ' ';
				}
				if($word[0] != '*') {
					$test_key = ' ' . $test_key;
				}

				if($test_key === '') continue;

				if(strpos($text, $test_key) !== false) { // совпало стоп слово
					$sum_weight += $weight;
					break;
				}
			}
		}

		return $sum_weight;
	}

	static public function formatText(&$content) {
		$formatted_text = str_replace('<', ' <', $content); // пробел перед тегом, т.к. его вырежем
 		$formatted_text = strip_tags($formatted_text);
		$formatted_text = ' ' . str_replace(array('.', '?', '!', ':', ';', ',', '(', ')', "\n", "\r", '"', "'"), ' ', $formatted_text) . ' ';
		$formatted_text = mb_strtolower($formatted_text, 'utf-8');
		return $formatted_text;
	}
}