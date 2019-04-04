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
			$url = 'https://'.$_[0].':'.$_[1].'/crush/rule';
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,$url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, "$_[2]:$_[3]");
			$result = curl_exec($ch);
			$data = json_decode($result);
			echo('<div id="container"><main>');
			echo "<pre><H1>CRUSH Rules</H1>\n";
			//echo $result;
			echo "<table><tr><th><b>Rule Name</b></th>";
			echo "<th><b>Key</b></th>";
			echo "<th><b>Value</b></th>";
			echo "</tr>";
      foreach ($data as $data){
	      echo "<tr>";
				$name = $data->rule_name;
				foreach ($data as $key=>$val){
					if ($key == 'max_size') {
						echo "<td>".$name."</td>";
					} else if ($key != "rule_name"){
						echo "<td></td>";
					}
					if ($key != "rule_name"){
						echo "<td>".$key."</td>";
						echo "<td>".$val."</td>";
					}
					echo "</tr>";
				}
	    }
			/*
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
			*/
			echo '</table></pre></main></div>';
			?>
		</div>
	</div>
</div>
