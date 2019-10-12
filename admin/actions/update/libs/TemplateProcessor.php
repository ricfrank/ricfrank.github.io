<?php
 class TemplateProcessor { private $templatesDirsList; private $pathToTemplates; const TEMPLATE_TYPE_HTML = 1; const TEMPLATE_TYPE_FLASH = 2; public function __construct($pathToTemplates = null) { if (null !== $pathToTemplates) { $this->setPathToTemplates($pathToTemplates); } } public function setPathToTemplates($pathToTemplates) { if (!file_exists($pathToTemplates) || !is_dir($pathToTemplates)) { throw new Exception("Folder with templates doesn't exist"); } $this->pathToTemplates = $pathToTemplates; $this->templatesDirsList = DirectoryScanner::getTemplatesDirList($pathToTemplates); } public function processTemplates($templateType, $dom) { foreach ($this->templatesDirsList as $templateDir) { $this->processTemplate($templateDir, $templateType, $dom); } } private function replaceMapModule($xmlDirectoryPath , $module, $templateType, $googleMapsCounter, $isRichContent = false, $dom) { $text = $dom->createCDATASection('HTML Widget ' . $module->getAttribute('id')); $module->getElementsByTagName("name")->item(0)->nodeValue = ""; $module->getElementsByTagName("name")->item(0)->appendChild($text); $module->getElementsByTagName("properties")->item(0)->nodeValue = ''; $dataProvider = $module->getElementsByTagName("data")->item(0)->getElementsByTagName('dataProvider')->item(0); $explodedPath = explode('/', $dataProvider->nodeValue); $dataProviderFile = $explodedPath[count($explodedPath) - 1]; $dataProviderDom = simplexml_load_file($xmlDirectoryPath . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $dataProviderFile); if ($dataProviderDom){ $providerItems = $dataProviderDom->xpath('//googlemap/data/item'); } else { return; } if (!$isRichContent) { $resultMapContent = MAPS_HTML_CONTENT2; $googleMapJsArray = '['; $googleMapCenter = ''; $coordsArray = ''; $propertiesCounter = 0; foreach ($providerItems as $item) { $items = $item->xpath('properties/item'); foreach ($items as $item) { if ($item->attributes()->propertyType == 2) { $coordsArray .= '[' . $item . ','; if ($propertiesCounter === 0) { $resultMapContent = str_replace('#CENTER_LAT#', $item, $resultMapContent); } } if ($item->attributes()->propertyType == 3) { $coordsArray .= $item . '],'; if ($propertiesCounter === 0) { $resultMapContent = str_replace('#CENTER_LNG#', $item, $resultMapContent); } } } if ($propertiesCounter === 0) { $googleMapCenter .= $coordsArray; } $propertiesCounter++; } if ($propertiesCounter === 0) { $resultMapContent = str_replace(array('#CENTER_LAT#', '#CENTER_LNG#'), array(DEFAULT_CENTER_LAT, DEFAULT_CENTER_LNG), $resultMapContent); } $googleMapJsArray .= $coordsArray . ']'; $resultMapHtmlContent = str_replace('#LOCATIONS#', $googleMapJsArray, $resultMapContent); $resultMapHtmlContent = str_replace('#MAP_ID#', $module->getAttribute("id"), $resultMapHtmlContent); $resultMapHtmlContent = str_replace('#WIDTH#', $module->getAttribute('width'), $resultMapHtmlContent); $resultMapHtmlContent = str_replace('#HEIGHT#', $module->getAttribute('height'), $resultMapHtmlContent); if ($googleMapsCounter < 1 || $templateType === self::TEMPLATE_TYPE_FLASH ) { $resultMapHtmlContent = str_replace('#GET_API_SCRIPT#', 'true', $resultMapHtmlContent); } else { $resultMapHtmlContent = str_replace('#GET_API_SCRIPT#', 'false', $resultMapHtmlContent); } } else { $resultMapHtmlContent = MAPS_RICH_HTML_CONTENT; } $resultMapHtmlContent = $this->replaceEncodedEntities($resultMapHtmlContent); $id = uniqid(); $htmlContentFileName = 'html_' . $id . '.xml'; $htmlContentFilePath = $xmlDirectoryPath . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $htmlContentFileName; file_put_contents($htmlContentFilePath, str_replace('#HTML_CONTENT#', $resultMapHtmlContent, MAPS_HTML_WIDGET_CONTENT)); $newNode = $dom->createDocumentFragment(); $htmlFileName = 'html_' . $id . '.html'; $newNode->appendXML('<file isDataProvider="true">' . 'xml/modules/' . $htmlContentFileName . '</file>'); $module->getElementsByTagName("data")->item(0)->appendChild($newNode); $htmlFilePath = $xmlDirectoryPath . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . $htmlFileName; file_put_contents($htmlFilePath, $resultMapHtmlContent); if ($templateType === self::TEMPLATE_TYPE_FLASH) { $htmlModuleTypeId = '16'; } else if ($templateType === self::TEMPLATE_TYPE_HTML) { $htmlModuleTypeId = '6'; } $module->setAttribute('moduleType', $htmlModuleTypeId); $resultArray['contentFile'] = 'successfully replaced'; } public function processTemplate($templateDir, $templateType, $dom) { $resultArray = array('templateName' => $templateDir); $fullTemplateDirPath = $templateDir; $xmlDirectoryPath = $fullTemplateDirPath . '/' . 'xml'; if ($templateType === self::TEMPLATE_TYPE_FLASH) { $googleMapSWFDir = 'modules'; } else if ($templateType === self::TEMPLATE_TYPE_HTML) { $googleMapSWFDir = 'widgets'; } $googleMapSWFFilePath = $fullTemplateDirPath . DIRECTORY_SEPARATOR . $googleMapSWFDir . DIRECTORY_SEPARATOR . 'gmapModule.swf'; $contextXmlPath = $xmlDirectoryPath . '/' . 'content.xml'; $structureXmlFile = $xmlDirectoryPath . DIRECTORY_SEPARATOR . 'structure.xml'; $adminMapModulePath = $fullTemplateDirPath . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'googleMapModule'; if (file_exists($contextXmlPath)) { $contentDom = $dom; if(!$contentDom || $dom == NULL || $contentDom == false){ return false; } $query = './/module'; if ($contentDom && $contentDom !==true){ $modules = MotoXML::findByXPath($query, $contentDom); } else { return; } $googleMapsCounter = 0; foreach ($modules as $module) { $moduleName = strtolower(trim($module->getElementsByTagName('name')->item(0)->nodeValue)); if (strpos($moduleName, 'google maps') !== false) { $this->replaceMapModule($xmlDirectoryPath, $module, $templateType, $googleMapsCounter, false, $dom); $googleMapsCounter++; } else if(strpos($moduleName, 'rich content') !== false) { $richContentModules = MotoXML::findByXPath('.//data/modules/module', $dom); foreach ($richContentModules as $richContentModule) { $richModuleName = strtolower(trim($richContentModule->getElementsByTagName('name')->item(0)->nodeValue)); if (strpos($richModuleName, 'google map') !== false) { $this->replaceMapModule($xmlDirectoryPath, $richContentModule, $templateType, $googleMapsCounter, true, $dom); $googleMapsCounter++; } } } } } else { $resultArray['contentFile'] = "content.xml doesn't exist"; } if (file_exists($structureXmlFile)) { $structureDom = simplexml_load_file($structureXmlFile); $modules = $structureDom->xpath('//motoStructure/modules/module'); foreach ($modules as $module) { if ((strtolower($module->attributes()->type) === 'googlemapmodule' && $templateType === self::TEMPLATE_TYPE_HTML) || (strtolower($module->attributes()->type) === 'mapmodule' && $templateType === self::TEMPLATE_TYPE_FLASH) ) { unset($module[0]); file_put_contents($structureXmlFile, $structureDom->asXML()); $resultArray['structureFile'] = 'successfully replaced'; break; } } } else { $resultArray['structureFile'] = 'structure.xml doesn\'t exist'; } if (file_exists($googleMapSWFFilePath)) { unlink($googleMapSWFFilePath); $resultArray['swfModule'] = 'gmapModule.swf deleted successfully'; } else { $resultArray['swfModule'] = 'gmapModule.swf doesn\'t exist'; } if (file_exists($adminMapModulePath) && is_dir($adminMapModulePath)) { $this->deleteDir($adminMapModulePath); $resultArray['adminMapModule'] = 'admin google map module deleted successfully'; } else { $resultArray['adminMapModule'] = 'admin google map module doesn\'t exist'; } return $resultArray; } private function replaceEncodedEntities($replacement) { $replaceResult = str_replace( array( '&gt;', '&lt;' ), array( '>', '<' ), $replacement ); return $replaceResult; } public function deleteDir($dirPath) { if (!is_dir($dirPath)) { throw new InvalidArgumentException("$dirPath must be a directory"); } if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') { $dirPath .= '/'; } $files = glob($dirPath . '*', GLOB_MARK); foreach ($files as $file) { if (is_dir($file)) { $this->deleteDir($file); } else { unlink($file); } } rmdir($dirPath); } }