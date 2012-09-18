<?php
if (!defined('BASEDIR')) {
	header('Location: index.php');
}
?><!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>IPLocker Example site</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
    <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
	
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>

          <a class="brand" href="#">IPLocker</a>
          <div class="nav-collapse">
            <ul class="nav">
<!--
              <li class="active"><a href="index.php">Home</a></li> 
-->
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span2">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
			  <li class="nav-header">Topcis</li>
			  <li><a href="index.php">Introduction</a></li>
			  <li><a href="setup.php">Setup</a></li>
			  <li><a href="list-viewer.php">List Viewer</a></li>
			  <li><a href="examples.php">Examples</a></li>
			  <li><a href="coverage/" target="_blank">Unit Test Coverage</a></li>

              <li class="nav-header">Resources</li>
              <li><a href="http://github.com/" target="_blank">Github</a></li>
              <li><a href="http://twilio.com/" target="_blank">Twilio</a></li>
              <li><a href="http://php.net/" target="_blank">PHP</a></li>
              <li><a href="http://pear.php.net/" target="_blank">PEAR</a></li>
              <li><a href="http://github.com/timcrider/IPLocker/" target="_blank">IPLocker Repository</a></li>
              <li><a href="http://blog.timcrider.com/" target="_blank">Timothy M. Crider</a></li>
            </ul>
          </div><!--/.well -->
        </div><!--/span-->

        <div class="span10">
<?php if (defined('IPLOCK_AUTH') && IPLOCK_AUTH) : ?>
			<div class="hero-unit success">
			    <h1>Access Granted</h1>
			    <p>Your IP <strong><?=\IPLocker\Helpers::realIP()?></strong> has been granted access by IPLocker</p>
			</div>
<?php else : ?>
			<div class="hero-unit error">
			    <h1>Access Denied</h1>
			    <p>Your IP <strong><?=\IPLocker\Helpers::realIP()?></strong> has been denied access by IPLocker</p>
			</div>
<?php endif; ?>
<!-- BEGIN CONTENT -->
