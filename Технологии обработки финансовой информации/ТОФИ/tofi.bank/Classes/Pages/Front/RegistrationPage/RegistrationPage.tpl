<div class="wrap">
	<h2>ТОФИ Банк</h2>
	<div>Выгодные кредиты всего <?=$percent;?>% в год. Зарегистрируйтесь для создания заявки на кредит или <a href="/admin/">войдите в систему</a>.</div>
	<br />
	<h3 style="text-decoration: underline; cursor: pointer;" onclick="$('.register-block').toggle();">Регистрация</h3>

	<style>
		p.err {
			color: red;
			margin: 3px 0 3px 0;
			padding-bottom: 0px !important;
		}
		.register-block label, .calculator-block label {
			width: 200px;
			display: block;
			margin-bottom: 3px;
			margin-top: 3px;
		}

		.register-block label.error, .calculator-block label.error {
			width: auto;
			color: red;
			display: inline;
			margin-left: 5px;
		}

		.register-block button, .calculator-block button {
			margin-top: 3px;
		}
	</style>
	<div class="register-block" style="display:none;">
	<?=get_block('FormGenerator', array(
					'name' => 'registration_form',
	'action' => '/',
	'submit_title' => 'button_register',
		'form_type' => 'RegisterForm',
	))?>
	</div>

	<h3 style="text-decoration: underline; cursor: pointer;">Кредитный калькулятор</h3>
	<div class="calculator-block">
		<form name="credit_form" id="credit_form" method="get" action="/">
			<div>
				<div>
					<label for="credit_amount">Сумма кредита (рублей):</label>
					<input autocomplete="off" type="text" value="<?=(isset($_GET['credit_amount']) ? $_GET['credit_amount'] : '')?>" name="credit_amount" id="credit_amount" class="i-text" />
				</div>
				<div>
					<label for="credit_term">Срок кредита (месяцев):</label>
					<input autocomplete="off" type="text" value="<?=(isset($_GET['credit_term']) ? $_GET['credit_term'] : '')?>" name="credit_term" id="credit_term" class="i-text" />
				</div>
				<button type="submit" onclick=""><span>Расчитать выплаты</span></button>
				<div class="clr"></div>
			</div>
			<?php if ($calculator_show) : ?>
			<div>
				<h3>Выплаты по кредиту</h3>
				<table>
					<tr>
						<th>Дата</th>
						<th>Сумма, руб.</th>
						<th>Выплата по кредиту, руб.</th>
						<th>Выплата процентов, руб.</th>
					</tr>
				<?php foreach ($creditInfo['plans'] as $payment) :?>
					<tr>
						<td><?=date('d.m.Y', strtotime($payment['date']))?></td>
						<td><?=$payment['amount']?></td>
						<td><?=$payment['credit_month_amount']?></td>
						<td><?=$payment['percent_month_amount']?></td>
					</tr>
				<?php endforeach; ?>
				</table>
			</div>
			<?php endif; ?>
		</form>
	</div>
</div>