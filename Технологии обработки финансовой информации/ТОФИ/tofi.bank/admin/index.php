<?php

try {
	include ('../Classes/initializer.php');
	Loader::loadORM('AdminStructureHandler', 'CMS/Handlers');
	AdminStructureHandler::getInstance()->initialize();
} catch (Exception $e) {
	Core::coreDie($e);
}