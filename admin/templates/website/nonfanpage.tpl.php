<?php
	$this->extend('nonfanpage_layout');
?>
<?php
    /* Set layout slots */
?>
<?php $this->set('title', $websiteTitlePrefix) ?>

<?php if (!empty($websiteTitlePrefix)): ?>
<?php $this->set('websiteTitle', $websiteTitlePrefix) ?>
<?php endif; ?>

<?php if (!empty($page->title)): ?>
<?php $this->set('pageTitle', $page->title) ?>
<?php endif; ?>

<?php if ($googleAnalyticsEnabled == 'true'): ?>
<?php $this->set('googleAnalytics', $this->render('_google_analytics', array('account' => $googleAnalyticsAccount))) ?>
<?php endif;?>

<?php if (!empty($navigation)): ?>
<?php $this->start('navigation') ?>
    <?php foreach($navigation as $path => $prop): ?>
    <li <?php
    echo ' class="';
    	if (isset($prop["page"]) && $prop["page"] != "")  echo $prop['page'];
    	if (isset($prop["class"]) && $prop["class"] != "")  echo ' ' . $prop['class'];
    	if (isset($prop["parent"]) && $prop["parent"] > 0 )  echo ' child';
    echo '" ';
    ?> <?php
    	if(isset($prop["style"]) && $prop["style"] != "") echo " style=\"$prop[style]\"";
    ?> ><a href="<?php
    	echo $this->assets->getUrl($path)
    ?>" <?php
    	if($prop["rel"]!="") echo " rel=\"$prop[rel]\"";
    ?> <?php
    	if(($prop["target"]!="_self")&&($prop["target"]!=""))  echo " target=\"$prop[target]\"";
    ?>><?php
    	echo $prop["title"] ?></a></li>
    <?php endforeach; ?>
<?php $this->stop(); ?>
<?php endif; ?>

<?php if (isset($pageData)) echo $pageData ?>
    
<?php 
if (file_exists(MOTO_ROOT_DIR . '/xml/nonfanpage.html'))
    echo file_get_contents(MOTO_ROOT_DIR . '/xml/nonfanpage.html');
?>

<?php if ($this->has('content')): ?>
<?php $this->output('content') ?>
<?php endif; ?>