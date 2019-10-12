<?php
class HTMLWidgetModuleService { public static $dom = null; protected $_template = 'template.xml'; protected $_newXmlPrefix = 'html_'; protected $_languageXml = 'language.xml'; protected $_xmlLocation = 'xml/modules/'; public function __construct() { $this->_xmlLocation = MotoConfig::get('websiteContentFolder', '') . '/' . $this->_xmlLocation; $this->_xmlLocation = ltrim($this->_xmlLocation, '/'); } public function getLanguages() { $responseVO = new ResponseVO(); try { $query = './language'; $config = MOTO_ADMIN_DIR . '/' . CONTROL_PANEL_CONFIGURATION; if (file_exists($config)) { $config_xml = new MotoXML($config); $lang = MotoXML::findOneByXPath('.//item[@name="language"]', $config_xml); if (!is_null($lang)) { $query = './language[@id="eng" or @id="' . $lang->nodeValue . '"]'; } } $dom = new MotoXML($this->_languageXml); $lanugages = array(); $lanugagesXML = MotoXML::findByXPath($query, $dom); foreach ($lanugagesXML as $languageXML) { array_push($lanugages, new LanguageVO($languageXML)); } $responseVO->result = $lanugages; $responseVO->status->status = StatusEnum::SUCCESS; } catch (Exception $e) { $responseVO->result = null; $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; $responseVO->status->message = $e->getMessage(); } return $responseVO; } private function getXMLPath($module, $fullPath = true) { $dom = new DOMDocument(); $dom->loadXML($module); $node = $dom->getElementsByTagName("file"); if ($node) { $file = $node->item(0)->nodeValue; if (strlen($file) > 0) return ($fullPath ? MOTO_ROOT_DIR . '/' : '') . $file; } return ""; } public function getModuleData($module) { $responseVO = new ResponseVO(); $xmlPath = self::getXMLPath($module); try { $responseVO->result = new ModuleDataVO(new MotoXML($xmlPath)); $responseVO->status->status = StatusEnum::SUCCESS; } catch (Exception $e) { $responseVO->result = null; $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; $responseVO->status->message = $e->getMessage(); } return $responseVO; } public function saveModuleData($module, $moduleData) { $responseVO = new ResponseVO(); if (DEMO_MODE === 'true') { $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; return $responseVO; } $xmlPath = self::getXMLPath($module); try { $dom = new MotoXML($xmlPath); $moduleData->saveDomElement(MotoXML::findOneByXPath('.', $dom), $xmlPath); MotoXML::putXML($dom, $xmlPath); $responseVO->result = null; $responseVO->status->status = StatusEnum::SUCCESS; } catch (Exception $e) { $responseVO->result = null; $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; $responseVO->status->message = $e->getMessage(); } return $responseVO; } public function checkRequiredFiles($module) { $responseVO = new ResponseVO(); if (DEMO_MODE === 'true') { return $responseVO; } $responseVO->result = null; $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; try { $dom = new MotoXML(); $dom->loadXML($module); $templateNode = MotoXML::findOneByXPath('./template', $dom); if ($templateNode && strlen($templateNode->nodeValue) > 0) { $this->_template = $templateNode->nodeValue; } $file = $this->getXMLPath($module); if (strlen($file) == 0) { MotoUtil::createDir( MOTO_ROOT_DIR . '/' . $this->_xmlLocation ); $path = $this->_xmlLocation . $this->_newXmlPrefix . uniqid() . '.xml'; $dest = MOTO_ROOT_DIR . '/' . $path; if (file_exists($this->_template) && copy($this->_template, $dest)) { $responseVO->result = $path; $responseVO->status->status = StatusEnum::SUCCESS; } } else { if (!is_file($file)) { if (file_exists($this->_template) && copy($this->_template, $file)) { $responseVO->result = str_replace(MOTO_ROOT_DIR . '/', '', $file); $responseVO->status->status = StatusEnum::SUCCESS; } } else $responseVO->status->status = StatusEnum::SUCCESS; } } catch (Exception $e) { $responseVO->status->message = $e->getMessage(); } return $responseVO; } public function createModuleData($module) { $responseVO = new ResponseVO(); if (DEMO_MODE === 'true') { $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; return $responseVO; } $responseVO->result = null; $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; try { $dom = new MotoXML(); $dom->loadXML($module); $templateNode = MotoXML::findOneByXPath('./template', $dom); if ($templateNode && strlen($templateNode->nodeValue) > 0) { $this->_template = $templateNode->nodeValue; } MotoUtil::createDir( MOTO_ROOT_DIR . '/' . $this->_xmlLocation ); $file = MOTO_ROOT_DIR . '/' . $this->_xmlLocation . $this->_newXmlPrefix . uniqid() . '.xml'; if (file_exists($this->_template) && copy($this->_template, $file)) { $responseVO->result = str_replace(MOTO_ROOT_DIR . '/', '', $file); $responseVO->status->status = StatusEnum::SUCCESS; } } catch (Exception $e) { $responseVO->status->message = $e->getMessage(); } return $responseVO; } public function duplicateModuleData(ModuleDataProviderVO $dpVO) { $responseVO = new ResponseVO(); if (DEMO_MODE === 'true') { $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; return $responseVO; } $responseVO->result = null; $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; try { if ($dpVO->sources != null && count($dpVO->sources) > 0) { foreach($dpVO->sources as $source) { if ($source == '' || !file_exists(MOTO_ROOT_DIR . '/' . $source->value)) throw new Exception('File ' . $source->value . ' not found'); } foreach($dpVO->sources as &$source) { $newFile = $this->_xmlLocation . $this->_newXmlPrefix . uniqid() . '.xml'; if (!copy(MOTO_ROOT_DIR . '/' . $source->value, MOTO_ROOT_DIR . '/' . $newFile)) { throw new Exception('File ' . $source->value . ' can not be duplicated'); break; } $source->value = $newFile; } $responseVO->result = $dpVO; } $responseVO->status->status = StatusEnum::SUCCESS; } catch (Exception $e) { $responseVO->status->message = $e->getMessage(); } return $responseVO; } public function removeModuleData(ModuleDataProviderVO $dpVO) { if (DEMO_MODE === 'true') { return false; } if (isset($dpVO->sources) && $dpVO->sources != null && count($dpVO->sources) > 0) { foreach($dpVO->sources as $source) { if(is_file(MOTO_ROOT_DIR . '/' . $source->value)) @unlink(MOTO_ROOT_DIR . '/' . $source->value); $htmlFile = preg_replace('/\.xml$/i', '.html', $source->value); if(is_file(MOTO_ROOT_DIR . '/' . $htmlFile)) @unlink(MOTO_ROOT_DIR . '/' . $htmlFile); } } } function getModule($module) { try { $moduleVO = new stdClass(); $moduleVO->dataProvider = self::getXMLPath($module, false); if ($moduleVO->dataProvider == '') { return null; } $dom = new MotoXML(MOTO_ROOT_DIR . '/' . $moduleVO->dataProvider); $moduleVO->configuration = null; $moduleVO->structure = null; $moduleVO->item = new ModuleDataVO( $dom ); if (isset($moduleVO->item->options) && is_array($moduleVO->item->options)) { $options = array(); for($i = 0, $icount = count($moduleVO->item->options); $i < $icount; $i++) { $options[$moduleVO->item->options[$i]->id] = $moduleVO->item->options[$i]->value; } $moduleVO->item->options = $options; } } catch (Exception $e) { $moduleVO = null; } return $moduleVO; } }