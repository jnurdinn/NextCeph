<h1>Welcome to Ceph Admin</h1>
<body>
	<?php
		$output = shell_exec('ceph -s');
		echo "<pre>$output</pre>";
	?>
</body>
