var Development = {
	clickLinkOpen: function(anchor) {
		$(anchor).hide();
		$('select', $(anchor).parent()).show();
	},
	clickLinkHide: function(select, id) {
		var status = $(select).val();
		var title = '';
		$('option', select).each(function(i, option) {
			if($(option).attr('selected')) {
				title = $(option).html();
			}
		});

		if(!confirm('Поменять статус на "' + title + '"?')) {

			$(select).hide();
			$('a', $(select).parent()).show();
			return false;
		}

		$('a', $(select).parent()).html(title);
		$(select).hide();
		$('a', $(select).parent()).show();

		var params = {
			operation: 'changeStatus',
			status: status,
			id: id
		};

		$.post('/ajax/development.php', params, function(jsonResult){
			if(!jsonResult.success) {
				alert(jsonResult.message);
				return;
			}
			return;
		}, 'json');

		return true;
	}
};