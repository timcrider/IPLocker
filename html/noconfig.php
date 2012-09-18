<?php
define('SKIP_AUTH', true);
require_once __DIR__.'/config.php';
$locker = new \IPLocker\Locker();
require_once '_header.php';
?>
<div class="page-header">
	<h1>Unable to find the site configuration.</h1>
	<p>Sorry, I could not find the configuration file: <?php print BASEDIR.'config/configuration.php';?>.</p>
	<p>Try copying <strong><?php print BASEDIR.'config/configuration-default.php';?></strong> and changing the appropriate values</p>
	<p>If you feel you received this screen in error, please <a href="https://github.com/timcrider/IPLocker/issues" target="_blank">report a bug</a>.</p>
</div>

<?php
require_once '_footer.php';
