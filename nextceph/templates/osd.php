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
			$url = 'https://'.$_[0].':'.$_[1].'/osd';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$_[2]:$_[3]");
			$result = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($result);
			echo('<div id="container"><main>');
			echo "<pre><H1>Object Storage Daemons</H1>\n";
			echo "<table><tr>";
			echo "<td><b>OSD ID</b></td>";
			echo "<td><b>Hostname</b></td>";
			echo "<td><b>Cluster Address</b></td>";
			echo "<td><b>Public Address</b></td>";
			echo "<td><b>State</b></td>";
			echo "<td><b>UUID</b></td>";
			echo "<td><b>Weight</b></td></tr>";
      foreach ($data as $data) {
	      echo "<tr>";
	      echo "<td>$data->osd</td>";
				echo "<td>$data->server</td>";
				echo "<td>$data->cluster_addr</td>";
				echo "<td>$data->public_addr</td><td>";
				foreach ($data->state as $state){
					echo $state."<br>";
				}
				echo "<td>$data->uuid</td>";
				echo "<td>$data->weight</td></tr>";
			}
			echo '</table></pre></main></div>';
			?>
		</div>
	</div>
</div>
