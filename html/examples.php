<?php
require_once __DIR__.'/config.php';
require_once '_header.php';
?>

<div class="page-header">
	<h1>Administrator Commands</h1>
<pre>
# To create a new admin, text one of the following (+, add)
+ admin 5551231234 John Doe
add admin 5551231234 John Doe

# To remove an existing admin, text one of the following (-, rm, rem, remove)
- admin 5551231234 John Doe
rm admin 5551231234 John Doe
rem admin 5551231234 John Doe
remove admin 5551231234 John Doe
</pre>
</div>

<div class="page-header">
	<h1>IP Commands</h1>
<pre>
# To create a new admin, text one of the following (+, add)
+ ip 1.1.1.1
add 1.1.1.1

# To remove an existing admin, text one of the following (-, rm, rem, remove)
- ip 1.1.1.1
rm ip 1.1.1.1
rem ip 1.1.1.1
remove ip 1.1.1.1
</pre>
</div>

<?php
require_once '_footer.php';
