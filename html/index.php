<?php
require_once __DIR__.'/config.php';
require_once '_header.php';
?>
<div class="page-header">
	<h1>Welcome to IP Locker
	<small>Mobile Authentication Solution</small></h1>
	<p>IPLocker was created to help simplify managing access to your website. Using the flexible Twilio API, access can be gratend/revoked with a simple text message.</p>
	<p>This demo site was created to show a few examples of how to use this library to toggle access based on the client IP. Please refer to <a href="setup.php">the setup page</a> for a quick guide to setting up IPLocker.</p>
</div>

<div class="page-header">
	<h1>Requirements</h1>
	<p>IPLocker requires the follwing to work:</p>
	<ul>
		<li>PHP 5.3.0 or greater</li>
		<li>An active Twilio account</li>
		<li>Json storage requires two files that are web writable. (these should not be in your web root)</li>
	</ul>
	
	<p>IPLocker uses:</p>
	<ul>
		<li>Zend Framework</li>
		<li>PEAR components 1.9.4</li>
		<li>PHPUnit 3.6.12</li>
		<li>Twitter Bootstrap</li>
	</ul>
</div>

<div>
<?php
if (!$admin->valid()) {
	$admStatus = array(
		'css' => 'error',
		'message' => implode($admin->fetchErrors(), "<br />"),
		'status'  => "Failed"
	);
} else {
	$admStatus = array(
		'css' => 'success',
		'message' => "&nbsp;",
		'status'  => "Success"
	);
}

if (!$iplist->valid()) {
	$ipStatus = array(
		'css' => 'error',
		'message' => implode($admin->fetchErrors(), "<br />"),
		'status'  => "Failed"
	);
} else {
	$ipStatus = array(
		'css' => 'success',
		'message' => "&nbsp;",
		'status'  => "Success"
	);
}

?>

	<table class="table">
		<thead>
			<tr>
				<th>Status</th>
				<th>Test Name</th>
				<th>Message</th>
			</tr>
		</thead>
		<tbody>
			<tr class="<?=$admStatus['css']?>">
				<td><?=$admStatus['status']?></td>
				<td>Administration Storage</td>
				<td><?=$admStatus['message']?></td>
			</tr>

			<tr class="<?=$ipStatus['css']?>">
				<td><?=$ipStatus['status']?></td>
				<td>IP Access Storage</td>
				<td><?=$ipStatus['message']?></td>
			</tr>
		</tbody>
	</table>
</div>

<?php
require_once '_footer.php';
