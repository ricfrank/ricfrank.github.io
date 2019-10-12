<?php
 class MenusService { public function getMenus() { $responseVO = new ResponseVO(); $responseVO->status = new StatusVO(); try { $dom = new MotoXML(MENUS_RESOURSE_PATH); $menusData = MenuVO::findAll($dom); $menusStructure = MenuTypeVO::findAll($dom); $responseVO->result = array( 'menusData' => $menusData, 'menusStructure' => $menusStructure); $responseVO->status->status = StatusEnum::SUCCESS; } catch (Exception $e) { $responseVO->result = null; $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; $responseVO->status->message = $e->getMessage(); } return $responseVO; } public function saveMenus($menus, $modules = array()) { $responseVO = new ResponseVO(); if (DEMO_MODE === 'true') { $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; return $responseVO; } $responseVO->status = new StatusVO(); try { $domMenu = new MotoXML(MENUS_RESOURSE_PATH); $menusData = new DOMElement('menusData'); $motoMenus = MotoXML::findOneByXPath('/motoMenus', $domMenu); $motoMenus->replaceChild($menusData, MotoXML::findOneByXPath('//menusData', $domMenu)); foreach ($menus as $menu) { $menu->saveDomElement($menusData->appendChild(new DOMElement('menu'))); } if (is_array($modules) && count($modules) > 0) { $doms = array(md5(CONTENT_RESOURSE_PATH) => new MotoXML(CONTENT_RESOURSE_PATH)); $files = array(md5(CONTENT_RESOURSE_PATH) => CONTENT_RESOURSE_PATH); $files = MotoUtil::getDataProvidersFromXML($doms[md5(CONTENT_RESOURSE_PATH)], $files); foreach($files as $md5 => $file) { if (!isset($doms[$md5])) $doms[$md5] = new MotoXML($file); $dom = $doms[$md5]; foreach($modules as $moduleVO) if (isset($moduleVO->id) && $moduleVO->id>0) { $moduleNode = MotoXML::findOneByXPath('//module[@id=' . $moduleVO->id . ']', $dom); if (!is_null($moduleNode)) { $nodeModules = $moduleNode->parentNode; $nodeModules->removeChild($moduleNode); $moduleNode = new DOMElement("module"); $nodeModules->appendChild($moduleNode); $moduleVO->saveDomElement($moduleNode); $moduleVO->id = 0; } } MotoXML::putXML($dom, $file); MotoCache::getInstance()->clean(MotoCache::CLEANING_MODE_MATCHING_TAG, array('menu', 'menu_module', 'menu_module_' . $moduleVO->id), __FUNCTION__); } } MotoXML::putXML($domMenu, MENUS_RESOURSE_PATH); MotoCache::getInstance()->clean(MotoCache::CLEANING_MODE_MATCHING_TAG, array('menu', 'menus'), __FUNCTION__); $responseVO->result = $menus; $responseVO->status->status = StatusEnum::SUCCESS; } catch (Exception $e) { $responseVO->result = null; $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; $responseVO->status->message = $e->getMessage(); } return $responseVO; } public function updateMenuItemTemplate(MenuItemVO $menuItemVO, $menuTypeId) { $responseVO = new ResponseVO(); if (DEMO_MODE === 'true') { $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; return $responseVO; } $responseVO->status = new StatusVO(); $responseVO->result = null; $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE; try { $dom = new MotoXML(MENUS_RESOURSE_PATH); $menuTypeNode = MotoXML::findOneByXPath('.//menusStructure/menuType[@id=' . $menuTypeId . ']', $dom); if (!is_null($menuTypeNode)) { $menuTypeVO = new MenuTypeVO($menuTypeNode); $responseVO->result = $menuTypeVO->updateTemplate($menuTypeNode, $menuItemVO); MotoXML::putXML($dom, MENUS_RESOURSE_PATH); $responseVO->status->status = StatusEnum::SUCCESS; } } catch (Exception $e) { $responseVO->status->message = $e->getMessage(); } return $responseVO; } }