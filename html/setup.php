<?php
require_once __DIR__.'/config.php';
require_once '_header.php';
?>
<div class="page-header">
	<h1>IPLocker Setup</h1>
</div>


<div class="page-header">
	<h3>If You Do Not Have A Twilio Account</h3>
	<p>Go to the <a href="https://www.twilio.com/try-twilio?g=" target="_blank">Twilio Website</a> and sign up for an account. As a developer you get $20.00 in free credits. 
Once you have a Twilio account setup, follow the instructions below.</p>
</div>

<div class="page-header">
	<h3>If You Have A Twilio Account</h3>
	<p>Follow the steps below to setup your account and phone number with Twilio.</p>
	<ul>
		<li>Go to 'Numbers'</li>
		<li>If you do not have a number
			<ul>
				<li>Click on 'Buy a number'</li>
			</ul>
		</li>
		<li>Click on your number</li>
		<li>Set the 'SMS Request URL' to '<strong>http://<?=$_SERVER['HTTP_HOST']?><?=preg_replace('/setup\.php$/', '', $_SERVER['REQUEST_URI'])?>iplocker.php</strong>'</li>
		<li>The SMS Request type should be '<strong>POST</strong>'</li>
		<li>Save your changes</li>
	</ul>
</div>


<div class="page-header">
	<h3>Getting Started</h3>
	<p>If your phone number is in the access list, you can send the following text message command to <strong><?=\IPLocker\Helpers::prettyNumber($twilio['number'])?></strong></p>
	<pre>+ IP <?=\IPLocker\Helpers::realIP()?></pre>
	<p>If you would like to add other administrators</p>
	<pre>+ admin 5551231234 John Doe</pre>
</div>

<?php
require_once '_footer.php';
