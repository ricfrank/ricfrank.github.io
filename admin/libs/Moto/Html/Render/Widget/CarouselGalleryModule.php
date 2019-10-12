<?php
 class Moto_Html_Render_Widget_CarouselGalleryModule extends Moto_Html_Render_Widget_SimpleGalleryModule { function renderItem($item, $parent = 0) { $props = $this->_module->properties; $html = ''; $html = ''; $basePath = MotoConfig::get('websiteBasePath'); $data = array( 'label' => '', 'href' => '', 'class' => '', 'content' => '<span class="mjs-gallery-item-stylevo"><img  src="' . $item->properties['preview']['value'] .'"/></span>', 'url' => '', 'action' => '', 'id' => $item->id, 'order' => $item->order, 'item' => $item, 'widget.options' => $this->_module->data->configuration->options, 'widget.properties' => $this->_module->properties ); if ($item->properties['clickAction']['action'] == '') { $item->properties['clickAction']['class'] = ''; $template = $this->_module->getTemplate('itemWithoutA'); } else { $template = $this->_module->getTemplate('item'); } if ($template == ''){ $template = '<li class="{%class%}"><a href="{%href%}" data-url="{%url%}" data-action="{%action%}" target="{%target%}">{%label%}{%content%}</a></li>'; } $row = $this->_render($template, $data); $row = str_replace(array(' alt=""', ' data-url=""', 'target=""', ' rel=""', ' title=""', '<img src="' . $basePath . '" />'), '', $row); $row = str_replace(array(' href=""'), '', $row); $html .= $row; return $html; } }