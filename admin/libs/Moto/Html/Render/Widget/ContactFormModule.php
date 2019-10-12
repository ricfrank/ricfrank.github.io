<?php
class Moto_Html_Render_Widget_ContactFormModule extends Moto_Html_Render_Widget_Abstract { function proccess($obj, $parent = null) { $this->_module = $obj; $this->_parent = $parent; $html = $this->_proccess($obj); return $html; } function _proccess($obj) { $html = ''; $content = ''; $obj = $this->initModuleData($obj); if ($obj->data !== null) $content = $this->renderItems($obj->data->items); $ieGradientStart = $this->_module->properties['inputBackgroundColorTop']['value']; $ieGradientEnd = $this->_module->properties['inputBackgroundColorBottom']['value']; $props = $this->_module->properties; $alpha = $props['inputBackgroundFillAlpha']['value']; $data = array( 'id' => 'widget_' . $obj->id, 'class' => $this->getCssClass($obj), 'style' => $this->getStyle($obj), 'content' => $content, 'widget' => Moto_Html_Util::implodeArraySimple( $this->_module ), 'widget.data.configurationDataProvider' => $this->_module->data->configurationDataProvider, 'widget.data.itemsDataProvider' => $this->_module->data->itemsDataProvider, 'widget.properties' => Moto_Html_Util::implodeArray( $this->_module->properties ), 'widget.parameters' => Moto_Html_Util::implodeArray( $this->_module->parameters ), 'widget.action' => $obj->data->configuration->serverProcessorFileName . '.' . $obj->data->configuration->serverProcessorType, 'item.fillOpacity' => round( $alpha / 100, 2), 'item.inputBackgroundGradientIE' => $this->_getIEGradientData($ieGradientStart, $ieGradientEnd, $alpha), ); $alpha = $props['buttonFillAlpha']['value']; $ieGradientStart = $props['buttonBackgroundColorTop']['value']; $ieGradientEnd = $props['buttonBackgroundColorBottom']['value']; $data['item.buttonFillOpacity'] = round($alpha / 100, 2); $data['item.buttonBackgroundGradientIE'] = $this->_getIEGradientData($ieGradientStart, $ieGradientEnd, $alpha); $ieGradientStart = $props['buttonHoverColorTop']['value']; $ieGradientEnd = $props['buttonHoverColorBottom']['value']; $data['item.buttonBackgroundGradientIEHover'] = $this->_getIEGradientData($ieGradientStart, $ieGradientEnd, $alpha); $data['widget.submitButtonStyle'] = $this->_module->properties['submitButtonLabel']['style']; $data['widget.resetButtonStyle'] = $this->_module->properties['resetButtonLabel']['style']; $data['widget.inputShadowStyle'] = ''; $data['widget.buttonShadowStyle'] = ''; $data['widget.buttonBGBorder'] = $props['buttonCornerRadius']['value'] - $props['buttonLineWidth']['value']; if ($this->_module->properties['inputShadowEnabled']['value'] == 'true') { $inset = ''; if ($this->_module->properties['inputShadowIsInner']['value'] == 'true') $inset = 'inset'; $data['widget.inputShadowStyle'] = '
	-webkit-box-shadow: ' . $inset . ' #{%widget.properties.inputShadowColor.value%} {%widget.properties.inputShadowDistance.value%}px {%widget.properties.inputShadowDistance.value%}px {%widget.properties.inputShadowBlur.value%}px {%widget.properties.inputShadowStrength.value%}px;
	-moz-box-shadow: ' . $inset . ' #{%widget.properties.inputShadowColor.value%} {%widget.properties.inputShadowDistance.value%}px {%widget.properties.inputShadowDistance.value%}px {%widget.properties.inputShadowBlur.value%}px {%widget.properties.inputShadowStrength.value%}px;
	box-shadow: ' . $inset . ' #{%widget.properties.inputShadowColor.value%} {%widget.properties.inputShadowDistance.value%}px {%widget.properties.inputShadowDistance.value%}px {%widget.properties.inputShadowBlur.value%}px {%widget.properties.inputShadowStrength.value%}px;
'; $data['widget.inputShadowStyle'] = $this->_render($data['widget.inputShadowStyle'], $data); } if ($this->_module->properties['buttonShadowEnabled']['value'] == 'true') { $inset = ''; if ($this->_module->properties['buttonShadowIsInner']['value'] == 'true') $inset = 'inset'; $data['widget.buttonShadowStyle'] = '
	-webkit-box-shadow: ' . $inset . ' #{%widget.properties.buttonShadowColor.value%} {%widget.properties.buttonShadowDistance.value%}px {%widget.properties.buttonShadowDistance.value%}px {%widget.properties.buttonShadowBlur.value%}px {%widget.properties.buttonShadowStrength.value%}px;
	-moz-box-shadow: ' . $inset . ' #{%widget.properties.buttonShadowColor.value%} {%widget.properties.buttonShadowDistance.value%}px {%widget.properties.buttonShadowDistance.value%}px {%widget.properties.buttonShadowBlur.value%}px {%widget.properties.buttonShadowStrength.value%}px;
	box-shadow: ' . $inset . ' #{%widget.properties.buttonShadowColor.value%} {%widget.properties.buttonShadowDistance.value%}px {%widget.properties.buttonShadowDistance.value%}px {%widget.properties.buttonShadowBlur.value%}px {%widget.properties.buttonShadowStrength.value%}px;
'; $data['widget.buttonShadowStyle'] = $this->_render($data['widget.buttonShadowStyle'], $data); } $template = $obj->getTemplate('main'); if ($template == '') $template = $this->getTemplate('main'); $html = $this->_render($template, $data); $obj->type = ucfirst($obj->type); return $html; } protected function _getIEGradientData($startColor, $endColor, $alpha) { $format = "filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#%s', endColorstr='#%s');"; $alpha = (string)dechex(intval(($alpha * 255) / 100)); if ($alpha === '0') { $alpha = '00'; } return sprintf($format, $alpha . $startColor, $alpha . $endColor); } function renderItems($items, $parent = 0) { $html = ''; $data = array( 'content' => '', 'style' => $this->generateFormStylesheet($this->_module->id), ); $template = $this->_module->getTemplate('items'); if ($template == '') $template = '<style type="text/css">{%style%}</style><form action="" name="{%id%}_name" method="POST">{%content%}</form>'; if (isset($items->formItems) && is_array($items->formItems)) foreach($items->formItems as &$item) { $data['content'] .= $this->_input($item); } if (isset($items->formControls) && is_array($items->formControls)) foreach($items->formControls as &$item) { $data['content'] .= $this->_button($item); } $data['widget.id'] = 'widget_' . $this->_module->id; $html .= $this->_render($template, $data); return $html; } function _buttonStatusMessage($item) { $data = array( 'item' => $item, 'item.style' => $this->buildStyle($item), ); $template = $this->_module->getTemplate('buttonStatusMessage'); if ($template == '') $template = '
			<div id="{%widget.id%}_{%item.type%}" class="' . $this->_cssPref . 'item ' . $this->_cssPref . 'item-statusMessage" style="{%item.style%}"></div>
			' . "\n"; $html = $this->_render($template, $data); return $html; } function _buttonResetButton($item) { $data = array( 'item' => $item, 'item.style' => $this->buildStyle($item), 'label' => strip_tags($this->_module->properties['resetButtonLabel']['value']) ); $template = $this->_module->getTemplate('buttonReset'); if ($template == '') $template = '
			<input id="{%widget.id%}_{%item.type%}" type="reset" class="' . $this->_cssPref . 'item ' . $this->_cssPref . 'item-{%item.type%}" style="{%item.style%}" value="{%label%}" />
			' . "\n"; $html = $this->_render($template, $data); return $html; } function _buttonSubmitButton($item) { $data = array( 'item' => $item, 'item.style' => $this->buildStyle($item), 'label' => strip_tags($this->_module->properties['submitButtonLabel']['value']) ); $template = $this->_module->getTemplate('buttonSubmit'); if ($template == '') $template = '
			<input id="{%widget.id%}_{%item.type%}" type="submit" class="' . $this->_cssPref . 'item" style="{%item.style%}" value="{%label%}" />
			' . "\n"; $html = $this->_render($template, $data); return $html; } function _button($item) { $method_name = '_button' . ucfirst($item->type); if (method_exists($this, $method_name)) { $html = $this->$method_name($item); return $html; } $data = array( 'item' => $item, 'style' => $this->getStyle($item), ); $template = '
		<div class="' . $this->_cssPref . 'item" style="{%style%}">
			<input value="{%item.type%}" name="data[{%item.label%}]" type="{%item.type%}" xxxvalue="{%item.textToShow%}" class="" style="{%style%}" maxlength="{%item.maxChars%}"/>
		</div>
			' . "\n"; $html = $this->_render($template, $data); return $html; } function _input($item) { $method_name = '_input' . ucfirst($item->type); $item->mailOrder = sprintf('%02d', $item->mailOrder); $item->tabOrder = sprintf('%d%02d', $this->_module->id, $item->tabOrder); if (method_exists($this, $method_name)) { $html = $this->$method_name($item); return $html; } $templateName = 'inputText'; $data = array( 'item' => $item, 'style' => $this->getStyle($item) ); if ($item->password) { $data['item.type'] = 'password'; } if ($item->multiline) { $templateName = 'inputTextarea'; unset($data['item.wraper.padding']); } $template = $this->_module->getTemplate($templateName); if ($template == '') { $template = '
			<div class="' . $this->_cssPref . 'item" style="{%style%}">
				<input
					value="{%item.type%}"
					name="data[{%item.label%}]"
					tabindex="{%item.tabOrder%}"
					type="{%item.type%}"
					class=""
					style="width:{%item.width%}px;height:{%item.height%}px;"
					maxlength="{%item.maxChars%}"
				/>
			</div>
				' . "\n"; if ($item->multiline) $template = '
				<div class="' . $this->_cssPref . 'item" style="{%style%}">
					<textarea
						name="data[{%item.label%}]"
						tabindex="{%item.tabOrder%}"
						class=""
						style="width:{%item.width%}px;height:{%item.height%}px;"
						maxlength="{%item.maxChars%}"
					>{%item.textToShow%}</textarea>
				</div>
					' . "\n"; } $html = $this->_render($template, $data); return $html; } function _inputSelect($item) { $data = array( 'item' => $item, 'style' => $this->getStyle($item), 'item.checked' => '', 'content' => '', ); if ($item->defaultValue == 'true') $data['item.checked'] = ' checked="checked"'; $options = $item->dataProvider; $options = urldecode($options); $options = str_replace("\r", "\n", $options); $options = explode("\n", $options); $data['item.options'] = ''; foreach($options as $option) { $selected = ''; if ($option == $item->defaultValue) $selected = ' selected="selected"'; $data['content'] .= '<option' . $selected . ' value="'.$option.'">' . $option . '</option>'; } $template = $this->_module->getTemplate('inputSelect'); if ($template == '') $template = '
			<div class="' . $this->_cssPref . 'item" style="{%style%}">
				<select name="data[{%item.label%}]" class="" style="width:{%item.width%}px;height:{%item.height%}px;" tabindex="{%item.tabOrder%}">
					{%content%}
				</select>
			</div>
				' . "\n"; $html = $this->_render($template, $data); return $html; } function _inputCheckBox($item) { $data = array( 'item' => $item, 'style' => $this->getStyle($item), 'item.class' => 'r-'.((isset($item->textPosition) && $item->textPosition == 'left') ? 'right' : 'left'), 'item.checked' => '', ); if ($item->defaultValue == 'true') $data['item.checked'] = ' checked="checked"'; $template = $this->_module->getTemplate('inputCheckBox'); if ($template == '') $template = '
			<div class="' . $this->_cssPref . 'item" style="{%style%}">
				<label><input
					name="data[{%item.label%}]"
					type="checkbox"
					value="{%item.textToShow%}"
					tabindex="{%item.tabOrder%}"
                    class="{%item.class%}"
					style=""
					{%item.checked%}
				/>
				<span>{%item.text%}</span></label>
			</div>
				' . "\n"; $html = $this->_render($template, $data); return $html; } function _inputRadioButton($item) { $data = array( 'item' => $item, 'style' => $this->getStyle($item), 'item.text' => '<span>' . $item->textToShow . '</span>', 'item.class' => 'r-' .((isset($item->textPosition) && $item->textPosition == 'left') ? 'right' : 'left'), 'item.checked' => '', ); if ($item->defaultValue == 'true') $data['item.checked'] = ' checked="checked"'; $template = $this->_module->getTemplate('inputRadioButton'); if ($template == '') $template = '
			<div class="' . $this->_cssPref . 'item" style="{%style%}">
				<label><input
					name="data[{%item.label%}]"
					type="radio"
					value="{%item.textToShow%}"
					tabindex="{%item.tabOrder%}"
                    class="{%item.class%}"
					style=""
					{%item.checked%}
				/>
                <span>{%item.text%}</span></label>
			</div>
				' . "\n"; $html = $this->_render($template, $data); return $html; } function _inputAttach($item) { $data = array( 'item' => $item, 'style' => $this->getStyle($item), 'item.textPosition.left' => ( isset($item->textPosition) && $item->textPosition == 'left' ? '<span>' . $item->textToShow . '</span>' : '' ), 'item.textPosition.right' => ( isset($item->textPosition) && $item->textPosition == 'right' ? '<span>' . $item->textToShow . '</span>' : '' ), 'item.checked' => '', ); $template = $this->_module->getTemplate('inputAttach'); if ($template == '') $template = '
			<div class="' . $this->_cssPref . 'item" style="{%style%}">
				<input
					name="data[{%item.label%}]"
					type="file"
					tabindex="{%item.tabOrder%}"
					class=""
					style="width:{%item.width%}px;height:{%item.height%}px;"
				/>
			</div>
				' . "\n"; $html = $this->_render($template, $data); return $html; } function renderItem(&$item, $parent = 0) { return $this->_input($item); } public function getProperty($property, $selector = 'value', $default = '') { if (isset($this->_module->properties[$property])) return $this->_module->properties[$property][$selector]; else return $default; } protected function generateFormStylesheet($id) { $id = '#' . $this->_cssPref . 'widget_' . $id . '_form'; return $this->generateItemStyle($id, 'button') . "\n" . $this->generateItemStyle($id, 'input'); } protected function generateItemStyle($id, $type) { switch ($type) { case 'input': $selector = "$id .mjs-input, $id .chzn-container-single .chzn-single"; break; case 'button': $selector = "$id input[type=submit].mjs-item, $id input[type=reset].mjs-item"; break; default: return ''; } return $selector . ' {' . preg_replace('/(\n)|(\s+)/', '', $this->getControlStyle($type)) . '}'; } protected function getControlStyle($type = 'input') { return implode(";\n", array_merge( array( 'border-width:' . $this->getProperty($type . 'LineWidth', 'value', 0) . 'px', ), $this->getStyleBorderColor( $this->getProperty($type . 'LineColor'), $this->getProperty($type . 'LineAlpha') ), $this->getStyleBorderRadius( $this->getProperty($type . 'CornerRadius') ), $this->getStyleBackgroundGradient( $this->getProperty($type . 'BackgroundColorTop'), $this->getProperty($type . 'BackgroundColorBottom') ) )); } protected function getStyleBorderRadius($radius) { return array( 'border-radius:' . $radius . 'px', '-webkit-border-radius:' . $radius . 'px', '-moz-border-radius:' . $radius . 'px', ); } protected function getStyleBackgroundGradient($top, $bot) { if (substr($top, 0, 1) != '#') $top = '#' . $top; if (substr($bot, 0, 1) != '#') $bot = '#' . $bot; $gradient = 'linear-gradient(top, ' . $top . ', ' . $bot . ');'; return array( 'background: ' . $top, "filter: progid:DXImageTransform.Microsoft.gradient(startColorstr = '" . $top . "', endColorstr = '" . $bot . "', GradientType = 0);", 'background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, color-stop(100%, ' . $top . '), color-stop(100%, ' . $bot . '));', 'background-image:' . $gradient, 'background-image: -webkit-' . $gradient, 'background-image: -moz-' . $gradient, 'background-image: -o-' . $gradient, 'background-image: -ms-' . $gradient, ); } protected function getStyleBorderColor($color, $alpha) { if ($alpha != 0) { $rgb = $this->toRGB($color); $alpha = 100/intval($alpha); return array( 'border-style: solid', 'border-color: #' . $color, "border-color: rgba($rgb[R], $rgb[G], $rgb[B], $alpha)", ); } return array(); } protected function toRGB($Hex) { if (substr($Hex, 0, 1) == "#") $Hex = substr($Hex, 1); $R = substr($Hex, 0, 2); $G = substr($Hex, 2, 2); $B = substr($Hex, 4, 2); $R = hexdec($R); $G = hexdec($G); $B = hexdec($B); $RGB['R'] = $R; $RGB['G'] = $G; $RGB['B'] = $B; return $RGB; } protected function buildStyle($item) { $style = ''; foreach ($this->getStyle($item) as $property => $value) if ($property != 'z-index') $style .= $property . ':' . $value . ";"; return $style; } } 