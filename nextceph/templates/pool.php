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
			$url = 'https://'.$nc_config['mgr_host'].':'.$nc_config['mgr_port'].'/pool';
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
			echo('<div id="container"><main>');
			echo "<pre><H1>Pools</H1>\n";
			echo '<button type="button">Add New Pool</button>';
			echo "<table><tr>";
			echo "<td><b>Name</b></td>";
			echo "<td><b>Type</b></td>";
			echo "<td><b>Application</b></td>";
			echo "<td><b>Placement Group</b></td>";
			echo "<td><b>Replica Size</b></td>";
			echo "<td><b>Last Change</b></td>";
			echo "<td><b>Erasure Code Profile</b></td>";
			echo "<td><b>Edit Pool</b></td></tr>";
      foreach ($data as $data) {
	      echo "<tr>";
	      echo "<td>$data->pool_name</td>";
				echo "<td>$data->type</td>";
				foreach ($data->application_metadata as $key=>$val){
					echo "<td>$key</td>";
				}
				echo "<td>$data->pg_num</td>";
				echo "<td>$data->size</td>";
				echo "<td>$data->last_change</td>";
				echo "<td>$data->erasure_code_profile</td>";
				echo '<td><button class="icon-edit"></button><button class="icon-delete"></button></td></td></tr>';
			}
			echo '</table></pre></main></div>';
			?>
		</div>
	</div>
</div>
