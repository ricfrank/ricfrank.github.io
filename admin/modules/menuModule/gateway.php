<?php
 require_once './../../config/ProjectConfig.php'; ProjectConfig::setup(); set_include_path( 'services' . PATH_SEPARATOR . 'services/vo' . PATH_SEPARATOR . get_include_path() ); $server = new Zend_Amf_Server(); $server->addDirectory('/services/'); $server->setClassMap("StatusVO", "StatusVO"); $server->setClassMap("ResponseVO", "ResponseVO"); $server->setClassMap("LanguageVO", "LanguageVO"); $server->setClassMap("MenuModuleItemVO", "MenuModuleItemVO"); $server->setClassMap("MenuModuleItemTypeVO", "MenuModuleItemTypeVO"); $server->setClassMap("PropertyTypeVO", "PropertyTypeVO"); $server->setClassMap("PropertyVO", "PropertyVO"); $server->setClassMap("ModuleConfigurationVO", "ModuleConfigurationVO"); $server->setClassMap("ModuleOptionVO", "ModuleOptionVO"); $server->setClassMap("ModuleDataProviderVO", "ModuleDataProviderVO"); $server->setProduction(false); $response = $server->handle(); echo $response;