<!DOCTYPE html>
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml" lang="en">

<head>
	<!-- Specify the page encoding -->
	<meta charset="UTF-8">
	<!-- Set the device width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Specify the site description for search engines -->
	<meta name="description" content="NewBlog is a CMS allowing a user to create their own blog">
	<script src="/js/styleDebug.js"></script>
	<!-- Define the page title -->
	<?php
	// By default, the blog name is not defined
	$blogName = false;

	// If the connection to the database could be established
	if (Model::getPdo() != null) {
		// Store the blog installation status in a variable
		$blogInstalled = BlogController::isInstalled();

		// If the blog is installed, try to get the blog name from the controller method
		if ($blogInstalled) {
			$blogName = BlogController::getBlogName();
		} else {
			// If the blog is not installed, destroy the session and its variables
			unset($_SESSION);
			session_destroy();
		}
	}
	// If the blog name is defined, set it as the title, otherwise set "NewBlog"
	echo $blogName ? '<title>' . $blogName . '</title>' : '<title>NewBlog</title>';
	// Set a site icon
	echo '<link rel="icon" href="' . BlogController::getLogoUrl() . '" />';
	?>
	<!-- Preload fonts -->
	<link rel="preload" href="/style/fonts/agencyfb.ttf" as="font" type="font/ttf" crossorigin="anonymous">
	<link rel="preload" href="/style/fonts/LCD.ttf" as="font" type="font/ttf" crossorigin="anonymous">
	<!-- Link the stylesheet with the page -->
	<link href="/style/styleGeneral.css" rel="stylesheet" onload="sheetLoaded('general')"
		onerror="sheetError('general')">
	<link href="/style/styleLight.css" rel="stylesheet" media="(prefers-color-scheme: light)"
		onload="sheetLoaded('light')" onerror="sheetError('light')">
	<link href="/style/styleDark.css" rel="stylesheet" media="(prefers-color-scheme: dark)"
		onload="sheetLoaded('dark')" onerror="sheetError('dark')">
	<link href="/style/styleMobile.css" rel="stylesheet" onload="sheetLoaded('mobile')"
		onerror="sheetError('mobile')">
	<link href="/style/stylePrint.css" rel="stylesheet" media="print" onload="sheetLoaded('print')"
		onerror="sheetError('print')">

</head>