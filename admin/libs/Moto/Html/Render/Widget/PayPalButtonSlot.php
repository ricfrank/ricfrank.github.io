<?php
 class Moto_Html_Render_Widget_PayPalButtonSlot extends Moto_Html_Render_Widget_Abstract { function proccess($obj, $parent = null) { $this->_module = $obj; $this->_parent = $parent; $html = $this->_proccess($obj); return $html; } function _proccess($obj) { $html = ''; $content = ''; $obj->type = $obj->getType()->getExtraData('jsClass'); $data = array( 'id' => 'widget_' . $obj->id, 'class' => $this->getCssClass($obj), 'style' => $this->getStyle($obj), 'content' => $content, 'widget' => Moto_Html_Util::implodeArraySimple( $this->_module ), 'widget.properties' => Moto_Html_Util::implodeArray( $this->_module->properties ), 'widget.parameters' => Moto_Html_Util::implodeArray( $this->_module->parameters ), 'buttonIcon' => '', ); $data['widget.submit.image.src'] = $this->_getButtonImageUrl(); $template = $obj->getTemplate('main'); if ($template == '') $template = $this->getTemplate('main'); $html = $this->_render($template, $data); return $html; } protected function _getButtonImageUrl() { $imageSrc = ''; $buttonType = $this->getPropertyValue('buttonType', 'buynow'); if ($this->getPropertyValue('buttonImageType') == 'custom') { $imageSrc = $this->getPropertyValue('buttonCustomImage'); if ($imageSrc != '') $imageSrc = MotoConfig::get('websiteBasePath') . $imageSrc; } if ($imageSrc == '') { $imageSrc = 'https://www.paypal.com/en_US/i/btn/btn_'; switch($buttonType) { case 'services': $imageSrc .= $this->getPropertyValue('buttonPaypalText', 'buynow'); break; case 'donations': $imageSrc .= 'donate'; break; }; $imageSrc .= $this->getPropertyValue('buttonPaypalMode') . '.gif'; } return $imageSrc; } }