<?php
 class Moto_Html_Render_Widget_BackgroundControlsModule extends Moto_Html_Render_Widget_Abstract { private $startItemNumber = 0; function proccess($obj, $parent = null) { $this->_module = $obj; $this->_parent = $parent; $html = $this->_proccess($obj, $parent); return $html; } function _proccess($obj) { $html = ''; $content = ''; $obj->type = ucfirst($obj->type); $content .= $this->renderControlButtons(); $content .= $this->renderPagination($obj->data->items); $data = array( 'id' => 'widget_' . $obj->id, 'class' => $this->getCssClass($obj), 'style' => $this->getStyle($obj), 'content' => $content, 'widget' => Moto_Html_Util::implodeArraySimple( $this->_module ), 'widget.properties' => Moto_Html_Util::implodeArray( $this->_module->properties ), 'widget.parameters' => Moto_Html_Util::implodeArray( $this->_module->parameters ) ); $template = $obj->getTemplate('main'); if ($template == '') $template = $this->getTemplate('main'); $html = $this->_render($template, $data, false); return $html; } function renderControlButtons() { $html = ''; $props = $this->_module->properties; if ($props['controlsSelector']['value'] != 'buttons') return ''; $controlsPadding = $this->_parsePadding($this->_module->properties['controlsPadding']['value']); $data = array( 'id' => 'widget_' . $this->_module->id, 'prevStyle' => '', 'nextStyle' => '', 'span_open' => '', 'span_close' => '', 'prev.width' => $props['controlsPrevButton']['image']->parameters['width'], 'prev.height' => $props['controlsPrevButton']['image']->parameters['height'], 'next.width' => $props['controlsNextButton']['image']->parameters['width'], 'next.height' => $props['controlsNextButton']['image']->parameters['height'], 'prevOn.width' => $props['controlsPrevButtonHover']['image']->parameters['width'], 'prevOn.height' => $props['controlsPrevButtonHover']['image']->parameters['height'], 'nextOn.width' => $props['controlsNextButtonHover']['image']->parameters['width'], 'nextOn.height' => $props['controlsNextButtonHover']['image']->parameters['height'], 'controlsPadding.top' => $controlsPadding['top'], 'controlsPadding.right' => $controlsPadding['right'], 'controlsPadding.bottom' => $controlsPadding['bottom'], 'controlsPadding.left' => $controlsPadding['left'], ); $template = $this->_module->getTemplate('controlButtons'); $orient = $props['controlsButtonsOrientation']['value']; $data['break'] = $orient == 'vertical' ? '<br />' : ''; if ($props['controlsButtonsJustify']['value'] == 'true') { if ($orient == 'horizontal') { $data['prevStyle'] .= 'float:left;'; $data['nextStyle'] .= 'float:right;'; } else { $props['controlsButtonsVerticalAlign']['value'] = 'top'; $data['span_open'] .= '<span class="mjs-forceToBottom">'; $data['span_close'] .= '</span>'; } } $data['widget.properties'] = Moto_Html_Util::implodeArray( $props ); $html = $this->_render($template, $data, false); return $html; } function renderPagination($items = array()) { $props = $this->_module->properties; if ($props['controlsSelector']['value'] != 'pagination') return ''; $horizontal = $props['paginationOrientation']['value'] == 'horizontal'; $controlsPadding = $this->_parsePadding($this->_module->properties['controlsPadding']['value']); $data = array( 'id' => 'widget_' . $this->_module->id, 'content' => '', 'controlsPadding.top' => $controlsPadding['top'], 'controlsPadding.right' => $controlsPadding['right'], 'controlsPadding.bottom' => $controlsPadding['bottom'], 'controlsPadding.left' => $controlsPadding['left'], ); if (isset($props['paginationIcon']['image'])) { $data['pagerWidth'] = $props['paginationIcon']['image']->parameters['width']; $data['pagerHeight'] = $props['paginationIcon']['image']->parameters['height']; if (!isset($props['paginationIconHover']['image'])) { $props['paginationIconHover'] = $props['paginationIcon']; } } if (isset($props['paginationIconHover']['image'])) { $data['pagerHoverWidth'] = $props['paginationIconHover']['image']->parameters['width']; $data['pagerHoverHeight'] = $props['paginationIconHover']['image']->parameters['height']; } $itemTemplate = $this->_module->getTemplate('paginationItem'); if (!$itemTemplate) $itemTemplate = '<li>{%id%}</li>'; foreach ($items as $item) { $data['content'] .= $this->_render($itemTemplate, array( 'url' => $item->properties['preview']['value'], 'style' => $horizontal ? 'float:left;' : '', )); } $template = $this->_module->getTemplate('pagination'); if (!$template) $template = '<div><ul>{%content%}</ul></div>'; $html = ''; $html = $this->_render($template, $data); return $html; } private function _parsePadding($str) { $str = trim(str_replace('padding:', '', $str)); $null_padding = array(0, 0, 0, 0); $padding = array_map(create_function('$a', 'return intval($a);'), explode(' ', $str)); $result = array_merge($padding, $null_padding); return array( 'top' => $result[0], 'right' => $result[1], 'bottom' => $result[2], 'left' => $result[3] ); } } 