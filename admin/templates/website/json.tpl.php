<?php
ob_start();
$this->htmlHelpRender->dispatch($content, 'page');
$this->htmlHelpRender->dispatch($content, 'popup');
ob_get_clean();
echo json_encode($this->htmlHelpRender->getResponse());
