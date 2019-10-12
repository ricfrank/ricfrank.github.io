<?php
 if (!defined('WEBSITE_TEMPLATES_PATH')) { define('WEBSITE_TEMPLATES_PATH', MOTO_ADMIN_DIR . '/templates/website'); } class ConfigurationService { public function getConfiguration() { $responseVO = new ResponseVO(); $responseVO->status = new StatusVO(); try { $responseVO->result = ConfigurationVO::findAll(new MotoXML(CONFIG_RESOURSE_PATH)); foreach($responseVO->result as $i => &$item) { if ($item->name == 'facebookAppSecret' && $item->value != '') { $c = new ContentService(); $p = $c->getProductInfo(); $item->value = MotoUtil::decrypt($item->value, $p['product_id']); break; } } $responseVO->status->status = StatusEnum::SUCCESS; } catch (Exception $e) { $responseVO->result = null; $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; $responseVO->status->message = $e->getMessage(); } return $responseVO; } public function saveConfiguration($configurationItems, $websiteVO = null) { $responseVO = new ResponseVO(); if (DEMO_MODE === 'true') { $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; return $responseVO; } $responseVO->status = new StatusVO(); $responseVO->result = null; $responseVO->status->status = StatusEnum::SUCCESS; try { $dom = new MotoXML(CONFIG_RESOURSE_PATH); $container = MotoXML::findOneByXPath('//configuration', $dom); $oldVerFileName = ""; $newVerFileName = ""; $_config = array(); foreach ($configurationItems as $item) { $optionElement = MotoXML::findOneByXPath( ".//option[@name='{$item->name}']", $container); if ($item->name == "enableCompatibilityMode") { $oldMode = MotoUtil::toBoolean($optionElement->nodeValue); $newMode = MotoUtil::toBoolean($item->value); if ($newMode !== $oldMode) { try { $structureDom = MotoXML::create(STRUCTURE_RESOURSE_PATH); $node = MotoXML::findOneByXPath('./modules/module[@type="htmlWidget" and @librarySymbolLinkage="HTMLWidget"]', $structureDom); if ($node != null) { $node->setAttribute('enabled', MotoUtil::boolToString(!$newMode)); MotoXML::putXML($structureDom, STRUCTURE_RESOURSE_PATH); } } catch (Exception $e) { } } } if ($item->name == "facebookAppSecret") { $c = new ContentService(); $p = $c->getProductInfo(); $item->value = MotoUtil::encrypt($item->value, $p['product_id']); } if ($item->name == 'websiteProtectionKey' && strlen($item->value) < 24 && strlen($item->value) > 0) { $item->value = md5($item->value) . ':' . strlen($item->value); } if ( $item->name == "showCustomBlockForNonFans" || $item->name == "nonFansCustomBlockType" || $item->name == "nonFansCustomBlockCode" || $item->name == "facebookAppId" || $item->name == "facebookAppSecret" ) { $_config[$item->name]['old'] = $optionElement->nodeValue; $_config[$item->name]['new'] = $item; } if ($item->name == "googleWebmasterToolsFileName") { $oldVerFileName = trim($optionElement->nodeValue); $newVerFileName = trim($item->value); } $itemElement = new DOMElement('option'); if (is_null($optionElement)) { $container->appendChild($itemElement); } else { $container->replaceChild($itemElement, $optionElement); } $item->saveDomElement($itemElement); } if (count($_config) > 0) { if (isset($_config['showCustomBlockForNonFans']) && $_config['showCustomBlockForNonFans']['new']->value == 'true') { $type = $_config['nonFansCustomBlockType']['new']->value; $code = $_config['nonFansCustomBlockCode']['new']->value; $html = ''; if ($code != '') switch(strtolower($type)) { case 'image' : $html = '<img src="' . MOTO_ROOT_URL . '/' . $code . '" />'; break; case 'html' : default : $html = $code; break; } file_put_contents(MOTO_ROOT_DIR . '/xml/nonfanpage.html', $html); } } MotoXML::putXML($dom, CONFIG_RESOURSE_PATH); if ($oldVerFileName == $newVerFileName && !file_exists( MOTO_ROOT_DIR . "/" . $newVerFileName)) { if (!file_put_contents( MOTO_ROOT_DIR . "/" . $newVerFileName, $newVerFileName)) { $responseVO->status->status = StatusEnum::ERROR_WHILE_CREATING_VERIFICATION_FILE; } } else if ($oldVerFileName != $newVerFileName) { if (file_exists(MOTO_ROOT_DIR . "/" . $oldVerFileName)) { @unlink(MOTO_ROOT_DIR . "/" . $oldVerFileName); } if (isset($newVerFileName) && $newVerFileName != "") { if (!file_put_contents( MOTO_ROOT_DIR . "/" . $newVerFileName, $newVerFileName)) { $responseVO->status->status = StatusEnum::ERROR_WHILE_CREATING_VERIFICATION_FILE; } } } if (!is_null($websiteVO)) { $dom = new MotoXML(CONTENT_RESOURSE_PATH); $node = MotoXML::findOneByXPath('.//website', $dom); if ($node != null) { $newWebsiteVO = new WebsiteVO($node); $newWebsiteVO->width = $websiteVO->width; $newWebsiteVO->height = $websiteVO->height; $newWebsiteVO->properties = $websiteVO->properties; $newWebsiteVO->loginForm = $websiteVO->loginForm; $newWebsiteVO->style = $websiteVO->style; $newWebsiteVO->updateDomElement(MotoXML::findOneByXPath('//motoContent', $dom)); MotoXML::putXML($dom, CONTENT_RESOURSE_PATH); } } } catch (Exception $e) { $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; $responseVO->status->message = $e->getMessage(); } return $responseVO; } public function savePathConfiguration() { $responseVO = new ResponseVO(); $responseVO->status = new StatusVO(); try { $dom = new MotoXML(MOTO_ADMIN_DIR.'/config.xml'); $container = MotoXML::findOneByXPath('//configuration', $dom); $configurationItems = array(); $templateLocation = new ConfigurationVO(); $templateLocation->name = "TEMPLATE_LOCATION"; $templateLocation->value = str_replace("//", "/", MOTO_ROOT_URL . "/"); $websiteUrl = new ConfigurationVO(); $websiteUrl->name = "WEBSITE_URL"; $websiteUrl->value = $_SERVER['HTTP_HOST'].MOTO_ROOT_URL; array_push($configurationItems, $templateLocation); array_push($configurationItems, $websiteUrl); foreach ($configurationItems as $item) { $optionElement = MotoXML::findOneByXPath(".//item[@name='{$item->name}']", $container); $itemElement = new DOMElement('item'); if (is_null($optionElement)) { $container->appendChild($itemElement); } else { $container->replaceChild($itemElement, $optionElement); } $item->saveDomElement($itemElement); } $dom->save(MOTO_ADMIN_DIR . '/config.xml'); $responseVO->result = $configurationItems; $responseVO->status->status = StatusEnum::SUCCESS; } catch (Exception $e) { $responseVO->result = null; $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; $responseVO->status->message = $e->getMessage(); } return $responseVO; } public function getUnderConstructionTemplates() { $section = "coming_soon"; $responseVO = new ResponseVO(); $responseVO->status = new StatusVO(); $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; $result = null; try { $dir = MOTO_ADMIN_DIR . "/templates/sections/" . $section . "/"; if (!isset($dir)) { $responseVO->status->message = "Not exists template for " . $section; return $responseVO; } $list = MotoUtil::scanDir($dir); $result = array(); foreach($list as $i => $value) if (trim($value) != "" && preg_match('/\.tpl\.php$/', $value)) { $template = self::getTemplateInfo($dir . "/" . $value); if (isset($template["id"])) $result[] = $template; } $responseVO->status->status = StatusEnum::SUCCESS; } catch (Exception $e) { $responseVO->status->message = $e->getMessage(); } $responseVO->result = $result; return $responseVO; } private static function getTemplateInfo($filename) { $result = array( "name" => basename($filename), ); if (preg_match("/\/([^\/]+)\.tpl(.*)$/i", $filename, $match)) $result["id"] = $match[1]; if (!file_exists($filename)) return $result; if (preg_match("/\/([^\/]+)\.tpl(.*)$/i", $filename, $match)) { $names = explode("_", $match[1]); $name = ""; foreach($names as $i=>$word) { if ($name != "") $name .= " "; $name .= ucfirst(strtolower($word)); } $result["name"] = $name; } return $result; } static function changeKey($key, $value) { if (DEMO_MODE === 'true') { return false; } try { $dom = MotoXML::create(CONFIG_RESOURSE_PATH); $node = MotoXML::findOneByXPath('./configuration/option[@name="'. $key .'"]', $dom); $root = MotoXML::findOneByXPath('./configuration', $dom); if ($node != null && $node->nodeValue == $value) return true; if ($root == null) return false; if ($node == null) { $node = new DOMElement('option'); $root->appendChild($node); $node->setAttribute('name', $key); } $node->nodeValue = ''; $node->appendChild($root->ownerDocument->createCDATASection($value)); MotoXML::putXML($dom, CONFIG_RESOURSE_PATH); if ($key == 'loadEncodedData' && $value == 'false') MotoUtil::setLoadEncodedDataFalse(false); } catch(Exception $e) { return false; } return true; } }