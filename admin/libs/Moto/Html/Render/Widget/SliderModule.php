<?php
 class Moto_Html_Render_Widget_SliderModule extends Moto_Html_Render_Widget_Abstract { protected $_renderTemp = array(); function proccess($obj, $parent = null) { $this->_module = $obj; $this->_parent = $parent; $this->_renderTemp['widget.properties'] = Moto_Html_Util::implodeArray( $obj->properties ); $html = $this->_proccess($obj, $parent); return $html; } function _proccess($obj) { $html = ''; $content = ''; $obj->type = ucfirst($obj->type); if ($obj->data !== null) $content = $this->renderContent($obj->data->items); $controlsPadding = $this->_parsePadding($this->_module->properties['controlsPadding']['style']); $imagePadding = $this->_parsePadding($this->_module->properties['imagePadding']['style']); $data = array( 'id' => 'widget_' . $obj->id, 'class' => $this->getCssClass($obj), 'style' => $this->getStyle($obj), 'content' => $content, 'widget' => Moto_Html_Util::implodeArraySimple( $this->_module ), 'widget.properties' => $this->_renderTemp['widget.properties'], 'widget.parameters' => Moto_Html_Util::implodeArray( $this->_module->parameters ), 'controlsPadding.top' => $controlsPadding['top'], 'controlsPadding.right' => $controlsPadding['right'], 'controlsPadding.bottom' => $controlsPadding['bottom'], 'controlsPadding.left' => $controlsPadding['left'], 'imagePadding.top' => $imagePadding['top'], 'imagePadding.right' => $imagePadding['right'], 'imagePadding.bottom' => $imagePadding['bottom'], 'imagePadding.left' => $imagePadding['left'] ); $template = $obj->getTemplate('main'); if ($template == '') $template = $this->getTemplate('main'); $html = $this->_render($template, $data, false); return $html; } function renderContent($items, $parent = 0) { $html = ''; $data = array( 'widget.properties' => $this->_renderTemp['widget.properties'], 'controlButtons' => $this->renderControlButtons(), 'descriptionBlock' => $this->renderDescriptionBlock(), 'pagination' => $this->renderPagination($items), 'contentItems' => $this->renderContentItems($items), ); $template = $this->_module->getTemplate('content'); if ($template == '') $template = '<div>{%content%}</div>'; $html = $this->_render($template, $data, false); return $html; } function renderContentItems($items) { $html = ''; $content = ''; $data = array( 'content' => '', ); $template = $this->_module->getTemplate('contentItem'); foreach($items as $item) { $data['content'] .= $this->_render($template, array( 'item' => $item )); } $template = $this->_module->getTemplate('contentItems'); $html = $this->_render($template, $data, false); return $html; } function renderDescriptionBlock() { $html = ''; $template = $this->_module->getTemplate('descriptionBlock'); $props = $this->_module->properties; $descriptionBlockMargin = $this->_parsePadding($this->_module->properties['descriptionBlockMargin']['style']); $data = array( 'id' => 'widget_' . $this->_module->id, 'descriptionBlockMargin.top' => $descriptionBlockMargin['top'], 'descriptionBlockMargin.right' => $descriptionBlockMargin['right'], 'descriptionBlockMargin.bottom' => $descriptionBlockMargin['bottom'], 'descriptionBlockMargin.left' => $descriptionBlockMargin['left'], ); $data['fitWidth'] = $this->_module->properties['descriptionBlockJustify']['value'] == 'true' ? 'width:100%;' : ''; $data['fitHeight'] = ''; if ($props['descriptionBlockFitHeight']['value'] == 'true') { $data['fitHeight'] = 'height: 100%;'; $this->_module->properties['descriptionBlockVerticalAlign']['value'] = 'top'; } $html = $this->_render($template, $data, false); return $html; } function renderControlButtons() { $html = ''; $props = $this->_module->properties; if ($props['showButtons']['value'] == 'false') return ''; $data = array( 'id' => 'widget_' . $this->_module->id, 'prevStyle' => '', 'nextStyle' => '', 'span_open' => '', 'span_close' => '', 'prev.width' => $props['prevIcon']['image']->parameters['width'], 'prev.height' => $props['prevIcon']['image']->parameters['height'], 'next.width' => $props['nextIcon']['image']->parameters['width'], 'next.height' => $props['nextIcon']['image']->parameters['height'], 'prevOn.width' => $props['prevIconOn']['image']->parameters['width'], 'prevOn.height' => $props['prevIconOn']['image']->parameters['height'], 'nextOn.width' => $props['nextIconOn']['image']->parameters['width'], 'nextOn.height' => $props['nextIconOn']['image']->parameters['height'] ); $template = $this->_module->getTemplate('controlButtons'); $orient = $props['controlButtonsOrientation']['value']; $data['break'] = $orient == 'vertical' ? '<br />' : ''; if ($props['controlButtonsJustify']['value'] == 'true') { if ($orient == 'horizontal') { $data['prevStyle'] .= 'float:left;'; $data['nextStyle'] .= 'float:right;'; } else { $props['controlButtonsVerticalAlign']['value'] = 'top'; $data['span_open'] .= '<span class="mjs-forceToBottom">'; $data['span_close'] .= '</span>'; } } $data['widget.properties'] = Moto_Html_Util::implodeArray( $props ); $html = $this->_render($template, $data, false); return $html; } function renderPagination($items) { $props = $this->_module->properties; if ($props['showPagination']['value'] == 'false') return ''; $horizontal = $props['paginationOrientation']['value'] == 'horizontal'; $paginationMargin = $this->_parsePadding($this->_module->properties['paginationMargin']['style']); $data = array( 'id' => 'widget_' . $this->_module->id, 'content' => '', 'paginationMargin.top' => $paginationMargin['top'], 'paginationMargin.right' => $paginationMargin['right'], 'paginationMargin.bottom' => $paginationMargin['bottom'], 'paginationMargin.left' => $paginationMargin['left'] ); if (isset($props['paginationIcon']['image'])) { $data['pagerWidth'] = $props['paginationIcon']['image']->parameters['width']; $data['pagerHeight'] = $props['paginationIcon']['image']->parameters['height']; if (!isset($props['paginationIconHover']['image'])) { $props['paginationIconHover'] = $props['paginationIcon']; } } if (isset($props['paginationIconHover']['image'])) { $data['pagerHoverWidth'] = $props['paginationIconHover']['image']->parameters['width']; $data['pagerHoverHeight'] = $props['paginationIconHover']['image']->parameters['height']; } $itemTemplate = $this->_module->getTemplate('paginationItem'); if (!$itemTemplate) $itemTemplate = '<li>{%id%}</li>'; foreach ($items as $item) { $data['content'] .= $this->_render($itemTemplate, array( 'url' => $item->properties['preview']['value'], 'style' => $horizontal ? 'float:left;' : '', )); } $template = $this->_module->getTemplate('pagination'); if (!$template) $template = '<div><ul>{%content%}</ul></div>'; $html = ''; $html = $this->_render($template, $data); return $html; } protected function _parsePadding($style) { return array( 'top' => $style->paddingTop, 'right' => $style->paddingRight, 'bottom' => $style->paddingBottom, 'left' => $style->paddingLeft, ); } } 