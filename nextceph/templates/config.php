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
			include 'settings/settings.php';
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
			$data = json_decode($result);
			curl_close($ch);

			echo('<div id="container"><main>');
			echo("<pre><H1>Cluster Configuration</H1>\n");
			echo "<table><tr><th><b>Config Key</b></th>";
			echo "<th><b>Value</b></th>";
			echo "</tr>";
      // Cycle through the array
      foreach ($data as $key=>$value){
	      // Output a row
	      echo "<tr>";
	      echo "<td>$key</td>";
				echo "<td>$value</td>";
	      echo "</tr>";
	    }
			echo('</table></pre></main></div>');
			?>
		</div>
	</div>
</div>
