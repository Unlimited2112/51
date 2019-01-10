function hideInfo() 
{
	$('#info').hide();
}

function gotoURL(url)
{
	if (!url) url = "/";
	if (window.event)
	{
		var src = window.event.srcElement;
		if((src.tagName != 'A') && ((src.tagName != 'IMG') || (src.parentElement.tagName != 'A'))){
			if (window.event.shiftKey) window.open(url);
			else document.location = url;
		}
	} 
	else 
	{
		document.location = url;
	}
}

function chbCheckAll(formObj, checkName, checkVal)
{
	var el = formObj.elements;
	for (count = 0; count < el.length; count++)
		if (el[count].name == checkName + '[]')
			if (!el[count].disabled) el[count].checked = checkVal;
}

function chbExamAll(formObj, checkName, resName)
{
	var checkCount = 0;
	var boxCount = 0;
	var el = formObj.elements;

	for (count = 0; count < el.length; count++)
		if (el[count].name == checkName + '[]')
		{
			boxCount++;
			if (el[count].checked || el[count].disabled) checkCount++;
		}
	formObj.elements[resName].checked = (checkCount == boxCount);
}

var Contracts = {
    send: function(type, id, lang) {
        if(confirm('Точно отправить?')) {
            var url = '/ajax/mpdf/index.php?id='+id+'&type='+type+'&lang='+lang+'&do=send';

            $.get(url, {}, function(jsonResult){
                if(!jsonResult.success) {
                    alert('Произошла ошибка. ' + jsonResult.message);
                }
                else {
                    alert(jsonResult.message);
                }
                return;
            }, 'json');
        }
    }
}