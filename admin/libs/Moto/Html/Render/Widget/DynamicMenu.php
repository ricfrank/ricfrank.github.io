<?php
class Moto_Html_Render_Widget_DynamicMenu extends Moto_Html_Render_Widget_Abstract { protected $_page = null; function proccess($obj, $parent = null) { $this->_module = $obj; $this->_parent = $parent; $this->_page = $this->_engine->get('page'); $html = $this->_proccess($obj); return $html; } function _proccess($obj) { $html = ''; $content = ''; $obj = $this->initModuleData($obj); $obj->type = ucfirst($obj->type); if ($obj->data !== null) $content = $this->renderItems($obj->data->items); $data = array( 'id' => 'widget_' . $obj->id, 'class' => $this->getCssClass($obj), 'style' => $this->getStyle($obj), 'content' => $content, 'widget' => Moto_Html_Util::implodeArraySimple( $this->_module ), 'widget.properties' => Moto_Html_Util::implodeArray( $this->_module->properties ), 'widget.parameters' => Moto_Html_Util::implodeArray( $this->_module->parameters ), ); $template = $obj->getTemplate('main'); if ($template == '') $template = $this->getTemplate('main'); $html = $this->_render($template, $data); return $html; } function renderItems($items, $parent = 0, $level = 0) { $html = ''; $data = array( 'content' => '', 'level' => $level, ); $template = $this->_module->getTemplate('items'); if ($template == '') $template = '<ul>{%content%}</ul>'; $_items = array(); foreach($items as &$item) { if ($item->parent == $parent) $_items[] = $item; } $params = array( 'level' => $level, ); $icount = count($_items); $params['count'] = $icount; $params['position'] = 'first'; for($i = 0; $i < $icount; $i++) { $params['num'] = $i; if ($i == ($icount-1)) $params['position'] = 'last'; $data['content'] .= $this->renderItem($_items[$i], $params); $params['position'] = ''; } $html .= $this->_render($template, $data); return $html; } function renderItem(&$item, $params = null) { if ($item == null) return ''; if ($params == null) $params = array( 'level' => 0, ); $html = ''; $data = array( 'label' => $item->label, 'href' => '#', 'class' => '', 'content' => '', 'url' => '', 'action' => '', 'id' => $item->id, 'order' => $item->order, 'item' => $item, 'widget.properties' => $this->_module->properties, 'position' => (isset($params['position']) ? $params['position'] : ''), ); if ( isset($item->click) && $item->click != null) { $click = $this->getClicker()->parse($item->click, array()); $data['href'] = $click->href; $data['url'] = $click->url; $data['action'] = $click->action; if ($this->_page != null && $this->_page->url == $click->url) { $data['class'] .= ($data['class'] != '' ? ' ' : '') . $this->_cssPref . 'active'; } } $template = $this->_module->getTemplate('item'); if ($template == '') $template = '<li class="{%class%}"><a href="{%href%}" data-url="{%url%}" data-action="{%action%}">{%label%}</a>{%content%}</li>'; if ($item->childExists()) { $data['content'] = $this->renderItems($item->getChilds(), $item->id, $params['level']+1); if (1==11) { $classSubItems = $this->_module->getTemplate('classSubItems'); if ($classSubItems == '') $classSubItems = 'sub'; $data['class'] .= ' ' .$classSubItems; } } $html .= $this->_render($template, $data); return $html; } function initModuleData($module) { $data = null; $xml = $module->data; $menusContent = $this->_loadMenuContent(); $module->content = null; $xml = $module->data; $dom = new MotoXML(); $dom->loadXML($xml); $node = MotoXML::findOneByXPath('./menu', $dom); $menuId = $node->nodeValue; if ( $menuId > 0 && (isset($menusContent->menus[$menuId])) ) { $data = $menusContent->menus[$menuId]; } $module->data = $data; $module->_data = $xml; $module = $this->_postInitModuleData($module); return $module; } function _loadMenuContent() { $result = null; try { $result = new stdClass(); $dom = MotoXML::create(MENUS_RESOURSE_PATH); $result->menusData = MenuVO::findAll($dom); $result->menusStructure = MenuTypeVO::findAll($dom); $result->menus = array(); foreach($result->menusData as $menu) { $result->menus[$menu->id] = $menu; } unset($result->menusData); } catch(Exception $e) { $result = null; } return $result; } protected function _postInitModuleData($module) { if ( $module->data == null ) return $module; if ( isset($module->data->configuration) && $module->data->configuration != null) { if ( isset($module->data->configuration->options) ) { $options = $module->data->configuration->options; $module->data->configuration->options = array(); foreach($options as $option) { $module->data->configuration->options[ $option->id ] = $option->value; } } } $typeById = null; $defaultProperties = null; if ( isset($module->data->structure) && $module->data->structure != null ) { $properiesTemplate = array(); $typeById = array(); $defaultProperties = array(); if (isset($module->data->structure->template) && isset($module->data->structure->template->properties)) foreach ( $module->data->structure->template->properties as $property) { $properiesTemplate[$property->propertyType] = $property; } foreach($module->data->structure->properties as $i => $property) { $typeById[$property->id] = $property; if (isset($properiesTemplate[ $property->id ])) { $value = array_merge($properiesTemplate[ $property->id ]->parameters, array('value' => $properiesTemplate[ $property->id ]->value)); } else { $value = array_merge($property->parameters, array('value' => (isset($property->defaultValue) ? $property->defaultValue : ''))); } $keyName = ( (isset($property->keyName) && $property->keyName != null) ? $property->keyName : $i); switch($property->type) { case 'color' : $value['value'] = str_replace ('0x', '', $value['value']); break; case 'htmlText' : $value['value'] = Moto_Html_Render_HtmlText::getInstance()->parse($value['value']); break; case 'plainText' : $value['value'] = strip_tags($value['value']); break; } $defaultProperties[$keyName] = $value; } } if ( isset($module->data->items) && $module->data->items != null && $typeById != null) { foreach ($module->data->items as &$item) { $item->properties = Moto_Html_Util::itemPropertiesFill($item->properties, $typeById, $defaultProperties); } } return $module; } protected function compareByOrder($a, $b) { if ($a->order == $b->order) return 0; return ($a->order < $b->order) ? -1 : 1; } }