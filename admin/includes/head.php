<!DOCTYPE html>
<html lang="fr">
    <head>
	<?php 
	if (file_exists($_SERVER["DOCUMENT_ROOT"].'/admin/settings/name.txt'))
	{
	$blogname = file_get_contents($_SERVER["DOCUMENT_ROOT"].'/admin/settings/name.txt');
    ?>
	<title><?php echo $blogname; ?> - NewBlog CMS</title>
    <meta name="description" content="NewBlog CMS, <?php echo $blogname; ?>">
    <meta name="keyword" content="NewBlog CMS, <?php echo $blogname; ?>">
	<?php
	}
	else
	{
	?>
		<title>NewBlog CMS</title>
        <meta name="description" content="NewBlog CMS">
        <meta name="keyword" content="NewBlog CMS">
	<?php
	}
	?>        <link type="image/x-icon" rel="shortcut icon" href="/admin/css/newblog.png">
		<meta name="viewport" content="width=device-width, initial-scale=0.5">		<meta charset="utf-8" />		
        <!-- Style -->
        <link href="/admin/css/custom.css" rel="stylesheet">        <!-- 
		<link href="/admin/css/bootstrap.css" rel="stylesheet">        <link href="/admin/css/font-awesome.css" rel="stylesheet">
		-->
    </head>
	<?php
		
	if (file_exists($_SERVER["DOCUMENT_ROOT"].'/admin/settings/bg.txt') && file_get_contents($_SERVER["DOCUMENT_ROOT"].'/admin/settings/bg.txt') != "")
	{
	$background = file_get_contents($_SERVER["DOCUMENT_ROOT"].'/admin/settings/bg.txt');
?>
	<body style="background-image: url(<?php echo $background;?>); background-repeat: none; background-size: cover; background-position: center fixed;">
<?php
	}
	else
	{
	?>
	<body style="background-image: url(/admin/css/background.jpg)">
	<?php
	}
	?>