<?php
script('nextceph', 'script');
style('nextceph', 'style');
?>

<div id="app">
	<div id="app-navigation">
		<?php print_unescaped($this->inc('navigation/index')); ?>
		<?php print_unescaped($this->inc('settings/index')); ?>
	</div>

	<div id="app-content">
		<div id="app-content-wrapper">
			<?php
			include 'config/config.php';
			$url = 'https://'.$nc_config['mgr_host'].':'.$nc_config['mgr_port'].'/config/cluster';
			$login = $nc_config['user'];
			$pass = $nc_config['psswd'];
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
			$result = curl_exec($ch);
			curl_close($ch);
			echo('<pre><H1>Cluster Configuration</H1>');
			echo($result);
			echo('</pre>');
			?>
		</div>
	</div>
</div>
