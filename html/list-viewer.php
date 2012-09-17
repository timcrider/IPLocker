<?php
require_once __DIR__.'/config.php';
require_once '_header.php';
?>
<div class="page-header">
	<h1>IPLocker List Viewer</h1>
	<p>This is where a brief introduction to the list managers and setup will be. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla dignissim aliquam libero a dapibus. Integer volutpat auctor tortor, non porttitor velit adipiscing in. In vitae ligula tellus, ac iaculis ante. Cras posuere pulvinar nulla sed viverra. Duis aliquet sem sed odio ultricies quis semper ligula vestibulum. Cras semper consequat molestie. Morbi cursus dignissim urna, eget commodo nibh vehicula sit amet. Suspendisse rhoncus eros et urna lacinia ultricies. Praesent ac diam risus. Nullam urna purus, fringilla nec interdum in, dictum vitae risus. Donec vitae elit metus, at feugiat felis. Morbi in arcu nisi. Sed adipiscing congue fermentum. Nullam scelerisque iaculis lacinia. Quisque turpis nisl, pretium eget vulputate ultricies, semper eu odio. Suspendisse mattis, tellus vel dignissim volutpat, neque leo aliquet velit, a pretium nisl nisl id tortor.</p>
	<p>Cras faucibus odio et ipsum luctus consectetur. Suspendisse ultrices sapien ante, eu consequat metus. Pellentesque justo ipsum, sagittis eget lobortis aliquet, euismod sit amet dui. Proin egestas accumsan porttitor. Sed dapibus, lorem ac fermentum elementum, erat purus lacinia odio, id mattis quam lectus sed lorem. In hac habitasse platea dictumst. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris porta purus eget quam hendrerit pulvinar. Cras tristique dolor vel dui faucibus a congue sapien mollis. Duis dapibus accumsan quam. Integer et lorem eu neque dapibus hendrerit ac sit amet erat. Aenean arcu nisi, vehicula at mattis eu, venenatis sed nulla. Sed mollis ipsum sit amet diam vestibulum imperdiet. </p>
</div>

<div class="page-header">
	<h1>Current Administrators
	<small>People who can add/remove both users and ip access</small></h1>

	<table class="table">
		<thead>
			<tr>
				<th>Administrator</th>
				<th>Phone Number</th>
			</tr>
		</thead>
		<tbody>
<?php
$admins = $admin->fetchAll();
asort($admins);
foreach ($admins AS $number=>$admin) {
$pretty = \IPLocker\Helpers::prettyNumber($number);
print <<< END_ROW
			<tr>
				<td>{$admin}</td>
				<td>{$pretty}</td>
			</tr>
END_ROW;
}

?>
		</tbody>
	</table>
</div>

<div class="page-header">
	<h1>Allowed IPs</h1>

	<table class="table">
		<thead>
			<tr>
				<th>IP Address</th>
			</tr>
		</thead>
		<tbody>
<?php
$ips = $iplist->fetchAll();
sort($ips);
foreach ($ips AS $ip) {
print <<< END_ROW
			<tr>
				<td>{$ip}</td>
			</tr>
END_ROW;
}

?>
		</tbody>
	</table>
</div>




<?php
require_once '_footer.php';
