<?php

date_default_timezone_set('Europe/Minsk');

require_once('Config/Config.php');
require_once('Core/Loader.php');
Loader::loadCore('Core');

@session_start();

Core::getInstance()->setModels(array(
	'LocalizerManagement' => 'CMS',
	'AdminStructure' => 'CMS',
	'Structure' => 'CMS',
	'StructureTemplates' => 'CMS',
	'StructureAttachables' => 'CMS',
	'StructureContent' => 'CMS',
	'StructureContentValues' => 'CMS',
	'Settings' => 'CMS',
	'User' => 'CMS',

	'HomepageAnnonces' => 'Attachables',
	'StructureImages' => 'Attachables',
    'StructureFiles' => 'Attachables',

	'Projects' => 'Models',
	'ProjectsImages' => 'Models',

	'Products' => 'Models',
	'ProductsImages' => 'Models',
	'Reviews' => 'Models',
	)
);

Core::outputBufferStart();

Core::getInstance()->DataBase = new DataBase('mysql:dbname='.DB_DATABASE.';host='.DB_HOST.';charset=utf8', DB_USER, DB_PASSWORD, array(
	PDO::ATTR_PERSISTENT => true,
));
Core::getInstance()->DataBase->exec('SET NAMES utf8');
Core::getInstance()->User = Core::getInstance()->getModel('User');
Core::getInstance()->Settings = Core::getInstance()->getModel('Settings');
Core::getInstance()->Localizer = new Localizer();