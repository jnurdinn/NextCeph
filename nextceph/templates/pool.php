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

			function post($url,$login,$pass,$jsondata){
				$jsondata = json_encode($jsondata);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $jsondata);
				$result = curl_exec($ch);
				$obj = json_decode($result);
				curl_close($ch);
				return $obj;
			}

			function get($url,$login,$pass){
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, "$login:$pass");
				$result = curl_exec($ch);
				$data = json_decode($result);
				return $data;
			}

			$url = 'https://'.$_[0].':'.$_[1].'/pool';
			$login = $_[2];
			$pass = $_[3];

			$data = get($url,$login,$pass);

			echo('<div id="container"><main>');
			echo "<pre><H1>Pools</H1>\n";
			echo "<table><tr>";
			echo "<td><b>Pool ID</b></td>";
			echo "<td><b>Name</b></td>";
			echo "<td><b>Type</b></td>";
			echo "<td><b>Application</b></td>";
			echo "<td><b>Placement Group</b></td>";
			echo "<td><b>Replica Size</b></td>";
			echo "<td><b>Last Change</b></td>";
			echo "<td><b>Erasure Code Profile</b></td>";
			echo "<td><b>Edit</b></td>";
			echo "<td><b>Del</b></td></tr>";
      foreach ($data as $data) {
	      echo "<tr>";
				echo "<td>$data->pool</td>";
	      echo "<td>$data->pool_name</td>";
				echo "<td>$data->type</td><td>";
				foreach ($data->application_metadata as $key=>$val){
					echo "$key";
				}
				echo "</td><td>$data->pg_num</td>";
				echo "<td>$data->size</td>";
				echo "<td>$data->last_change</td>";
				echo "<td>$data->erasure_code_profile</td>";
				echo '<td><input type="submit" name="edit" class="icon-edit" value=""></td>';
				echo '<td><form action="#delPool" method="get"><input name="name" type="hidden" value="'.$data->pool_name.'"><input name="id" type="hidden" value="'.$data->pool.'"></input><input type="submit" class="icon-delete" value=""></input></form></td></tr>';
			}
			echo '</table>';
			echo '<br><a class="button" href="#addPool">Add New Pool</a><br>';
			echo('</pre></main></div>');
			?>

			<div id="addPool" class="overlay">
				<div class="popup">
					<h2>Generate New Pool</h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
			      <form action="apply" method="POST">
							<input name="type" type="hidden" value="genPool">
			        Pool Name <input type="text" name="poolName" <br>
			        PG Value <input type="number" name="poolPG" value=0 <br>
			        <input type="submit" value="Submit">
			      </form>
					</div>
				</div>
			</div>
			<div id="delPool" class="overlay">
				<div class="popup">
					<h2>Delete Pool</h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
			      <form action="apply" method="POST">
							<input name="type" type="hidden" value="delPool">
							<input name="id" type="hidden" value="<?php echo($_GET["id"]) ?>">
			        Are you sure to delete pool <b><?php echo($_GET["name"])?></b>?<br>
			        <c><input type="submit" value="OK"></input><a class="button" href="#">Cancel</a></c>
			      </form>
					</div>
				</div>
			</div>
			<div id="editPool" class="overlay">
				<div class="popup">
					<h2>Generate New Pool</h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
			      <form action="apply" method="POST">
							<input name="type" type="hidden" value="genPool">
			        Pool Name <input type="text" name="poolName" <br>
			        PG Value <input type="number" name="poolPG" value=0 <br>
			        <input type="submit" value="Submit">
			      </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
