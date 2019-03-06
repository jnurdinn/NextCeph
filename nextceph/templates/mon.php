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
			$url = 'https://'.$nc_config['mgr_host'].':'.$nc_config['mgr_port'].'/mon';
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
			$data = json_decode($result);
			echo('<div id="container"><main>');
			echo "<pre><H1>Monitor Daemons</H1>\n";
	    echo "<table>";
			echo "<tr>";
			echo "<th><b>Hostname</b></th>";
			echo "<th><b>Rank</b></th>";
			echo "<th><b>Quorum</b></th>";
			echo "<th><b>Leader</b></th>";
			echo "<th><b>Host Address</b></th>";
			echo "</tr>";
      // Cycle through the array
      foreach ($data as $idx => $stand) {
	      // Output a row
	      echo "<tr>";
	      echo "<td>$stand->server</td>";
				echo "<td>$stand->rank</td><td>";
				echo (boolval($stand->in_quorum) ? 'true' : 'false');
				echo "</td><td>";
				echo (boolval($stand->leader) ? 'true' : 'false');
				echo "</td><td>$stand->public_addr</td>";
	      echo "</tr>";

      // Close the table
	    }
			echo "</table>";
			echo('</pre>');
			echo('</main></div>');
			?>
		</div>
	</div>
</div>
