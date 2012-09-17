<?php
define('SKIP_AUTH', true);
require_once __DIR__.'/config.php';
require_once '_header.php';
?>
<div class="hero-unit">
	<h1>IPLocker Error</h1>
	<p>IPLocker has encountered an error</p>
</div>


<div class="page-header">
	<h1>What went wrong?</h1>
	<p><?=$e->getMessage()?></p>
</div>

<?php
require_once '_footer.php';
