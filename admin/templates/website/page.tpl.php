<?php
	$this->extend('layout');
?>
<?php
    /* Set layout slots */
?>
<?php $this->set('title', $websiteTitlePrefix . " " .$page->title) ?>

<?php if (!empty($websiteTitlePrefix)): ?>
<?php $this->set('websiteTitle', $websiteTitlePrefix) ?>
<?php endif; ?>

<?php if (!empty($page->title)): ?>
<?php $this->set('pageTitle', $page->title) ?>
<?php endif; ?>

<?php if (!empty($page->description)): ?>
<?php $this->set('meta-description', $page->description) ?>
<?php endif; ?>

<?php if (!empty($page->keywords)): ?>
<?php $this->set('meta-keywords', $page->keywords) ?>
<?php endif; ?>

<?php if ($page->noIndex) : ?>
<?php $robots[] = 'noindex' ?>
<?php endif; ?>

<?php if ($page->noFollow) : ?>
<?php $robots[] = 'nofollow' ?>
<?php endif; ?>

<?php if (!empty($robots)): ?>
<?php $this->set('meta-robots', implode(',', $robots)) ?>
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

<?php if ($this->has('content')): ?>
<?php $this->output('content') ?>
<?php endif; ?>