/**
 * @saveCmd plugin.
 */

(function()
{
	var saveCmd =
	{
		modes : { wysiwyg:1, source:1 },

		exec : function( editor )
		{
			var oHTML=CKEDITOR.instances.CKfulleditor.getData();
			//opener.wysiwygUpdateContent(openerArea, oHTML);
			eval('opener.CKEDITOR.instances.'+openerArea+'.setData(oHTML);');
			window.close();
			return false;
		}
	};

	var pluginName = 'saveform';

	CKEDITOR.plugins.add( pluginName,
	{
		init : function( editor )
		{
			var command = editor.addCommand( pluginName, saveCmd );
			command.modes = { wysiwyg : !!( editor.element.$.form ) };

			editor.ui.addButton( 'Save',
				{
					label : editor.lang.save,
					command : pluginName
				});
		}
	});
})();
