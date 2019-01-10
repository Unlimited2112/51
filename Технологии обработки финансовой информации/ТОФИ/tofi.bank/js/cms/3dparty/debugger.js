function trim(str)
{
	str = '' + str;
	var re = /^ */;
	var res = str.replace(re, '');
	re = / *$/;
	return(res.replace(re, ''));
}

function HideDebugger(id) 
{
	setCookie("debug_status_"+id, "hided", new Date(2299,1,1), "/");
	if ( document.getElementById('debug_'+id) ) 
	{
		document.getElementById('debug_'+id).style.display 			= "none";
		document.getElementById('debug_hide_butt_'+id).style.display 	= "none";
		document.getElementById('debug_show_butt_'+id).style.display 	= "";
	}
}

function ShowDebugger(id) 
{
	setCookie("debug_status_"+id, "showed", new Date(2299,1,1), "/");
	if ( document.getElementById('debug_'+id) ) 
	{
		document.getElementById('debug_'+id).style.display 			= "";
		document.getElementById('debug_hide_butt_'+id).style.display 	= "";
		document.getElementById('debug_show_butt_'+id).style.display 	= "none";
	}
}

function DebuggerCheck(id)
{
	if(getCookie("debug_status_"+id) == "hided") HideDebugger(id);
}

function setCookie(name, value, expires, path, domain, secure)
{
	var curCookie = name + "=" + escape(value) +
                ((expires) ? "; expires=" + expires.toGMTString() : "") +
                ((path) ? "; path=" + path : "") +
                ((domain) ? "; domain=" + domain : "") +
                ((secure) ? "; secure" : "");

	if ( (name + "=" + escape(value)).length <= 4000)
		document.cookie = curCookie
}

function getCookie(name)
{
        var prefix = name + "=";
        var cookieStartIndex = document.cookie.indexOf(prefix);

        if (cookieStartIndex == -1)
                return null;

        var cookieEndIndex = document.cookie.indexOf(";", cookieStartIndex + prefix.length);

        if (cookieEndIndex == -1)
                cookieEndIndex = document.cookie.length;

        return unescape(document.cookie.substring(cookieStartIndex + prefix.length, cookieEndIndex));
}

function deleteCookie(name, path, domain)
{
	if (getCookie(name))
	{
		document.cookie = name+"="+
                ((path) ? "; path=" + path : "") +
                ((domain) ? "; domain=" + domain : "") +
                "; expires=Thu, 01-Jan-70 00:00:01 GMT"
	}
}