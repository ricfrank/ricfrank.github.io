<?php
	$this->extend('page');
?>

<?php if ($googleWebmasterToolsEnabled == 'true'): ?>
<?php $this->set('meta-google-webmaster-tools', $googleWebmasterToolsMetaTag) ?>
<?php endif; ?>