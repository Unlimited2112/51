/**
 * @saveCmd plugin.
 */

(function()
{
	var openFull =
	{
		modes : { wysiwyg:1, source:1 },

		exec : function( editor )
		{
			var name = "ss_ext_wysiwyg";
			var path = "/js/ckeditor/?id="+editor.name;
			var width = "800";
			var height = "550";
			var left=(window.screen.availWidth/2)-(width/2);
			var top=(window.screen.availHeight/2)-(height/2);
			var param = "scrollbars=yes,status=no,resizable=1,width=" + width + ",height=" + height + ",left=" + left + ",top=" + top;
	   		eval(name+'=window.open("'+path+'", "'+name+'", "'+param+'");');
			eval(name+'.focus();');
		}
	};
	
	var pluginName = 'openfull';

	CKEDITOR.plugins.add( pluginName,
	{
		init : function( editor )
		{
			var command = editor.addCommand(pluginName, openFull);
			command.modes = { wysiwyg : !!( editor.element.$.form ) };

			editor.ui.addButton( 'OpenFull',
			{
				label : 'Открыть большой редактор',
				command : pluginName
			});
		}
	});
})();
