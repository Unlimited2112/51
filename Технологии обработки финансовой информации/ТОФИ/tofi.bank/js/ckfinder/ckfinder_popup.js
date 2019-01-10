function BrowseServer( resourceType, startupPath, functionData )
{
	var finder = new CKFinder() ;
	finder.BasePath = '/js/ckfinder/' ;
	finder.StartupPath = startupPath ;
	finder.ResourceType = resourceType ;
	finder.StartupFolderExpanded = true ;
	finder.DisableThumbnailSelection = true ;
	finder.SelectFunction = SetFileField ;
	finder.SelectFunctionData = functionData ;
	finder.Popup() ;
}

function SetFileField( fileUrl, data )
{
	document.getElementById( data["selectFunctionData"] ).value = fileUrl ;
	document.getElementById( data["selectFunctionData"] + 'div' ).innerHTML = fileUrl ;
}