<?php
script('nextceph', 'script');
style('nextceph', 'style');
?>

<div id='app'>
	<div id='app-navigation'>
		<?php print_unescaped($this->inc('navigation/index')); ?>
		<?php print_unescaped($this->inc('settings/index')); ?>
	</div>

	<div id='app-content'>
		<div id='app-content-wrapper'>
			<?php
			include 'config/config.php';
			$url = 'https://'.$nc_config['mgr_host'].':'.$nc_config['mgr_port'].'/osd';
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
			echo "<pre><H1>Object Storage Daemons</H1>\n";
			echo "<table>";
			echo "<tr>";
			echo "<td>OSD ID</td>";
			echo "<td>Hostname</td>";
			echo "<td>Cluster Address</td>";
			echo "<td>Public Address</td>";
			echo "</tr>";
      // Cycle through the array
      foreach ($data as $idx => $stand) {
	      // Output a row
	      echo "<tr>";
	      echo "<td>$stand->osd</td>";
				echo "<td>$stand->server</td>";
				echo "<td>$stand->cluster_addr</td>";
				echo "<td>$stand->public_addr</td>";
	      echo "</tr>";

			// Close the table
			}
			echo '</table>';
			echo('</pre>');
			?>
		</div>
	</div>
</div>
