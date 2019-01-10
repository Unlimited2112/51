<?php

try
{
	require_once ('Classes/initializer.php');
	
	Loader::loadORM('StructureHandler', 'CMS/Handlers');
	
	StructureHandler::getInstance()->initialize();
} 
catch (Exception $e) 
{   
	Core::coreDie($e);
}