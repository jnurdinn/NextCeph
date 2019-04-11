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

			$c_url = 'https://'.$_[0].':'.$_[1].'/crush/rule';
			$crush = get($c_url,$login,$pass);

			echo('<div id="container"><main>');
			echo '<pre><H1>Pools</H1><br>';
			echo '<a class="button" href="#addPool">(+) Add New Pool</a><br><br>';
			echo '<table><tr>';
			echo '<td><b>Pool ID</b></td>';
			echo '<td><b>Name</b></td>';
			echo '<td><b>Type</b></td>';
			echo '<td><b>Application</b></td>';
			echo '<td><b>PG Value</b></td>';
			echo '<td><b>PGP Value</b></td>';
			echo '<td><b>Replica Size</b></td>';
			echo '<td><b>Last Change</b></td>';
			echo '<td><b>Erasure Code Profile</b></td>';
			echo '<td><b>Edit</b></td>';
			echo '<td><b>Del</b></td></tr>';
      foreach ($data as $data) {
	      echo '<tr>';
				echo '<td>'.$data->pool.'</td>';
	      echo '<td>'.$data->pool_name.'</td>';
				echo '<td>';
				if ($data->type == 1){
					echo 'replicated';
				} else {
					echo 'erasure';
				}
				echo '</td><td>';
				foreach ($data->application_metadata as $key=>$val){
					echo $key;
				}
				echo '</td><td>'.$data->pg_num.'</td>';
				echo '</td><td>'.$data->pgp_num.'</td>';
				echo '<td>'.$data->size.'</td>';
				echo '<td>'.$data->last_change.'</td>';
				echo '<td>'.$data->erasure_code_profile.'</td>';
				echo '<td><form action="#editPool" method="get"><input name="name" type="hidden" value="'.$data->pool_name.'"><input name="id" type="hidden" value="'.$data->pool.'"><input name="pg_num" type="hidden" value="'.$data->pg_num.'"></input><input name="pgp_num" type="hidden" value="'.$data->pgp_num.'"></input><input name="size" type="hidden" value="'.$data->size.'"></input><input type="submit" class="icon-edit" value=""></input></form></td>';
				echo '<td><form action="#delPool" method="get"><input name="name" type="hidden" value="'.$data->pool_name.'"><input name="id" type="hidden" value="'.$data->pool.'"></input><input type="submit" class="icon-delete" value=""></input></form></td></tr>';
			}
			echo '</table>';
			echo('</pre></main></div>');
			?>

			<div id="addPool" class="overlay">
				<div class="popup">
					<h2>Generate New Pool</h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
			      <form action="apply" method="POST">
							<input name="type" type="hidden" value="genPool">
			        Pool Name <input type="text" name="name"></input> <br>
			        PG Value <input type="number" name="pg_num" value=8> <br>
							PGP Value <input type="number" name="pgp_num" value=8></input> <br>
							Replica Size <input type="number" name="size" value=3></input> <br>
							Pool Type <br>
							<select name="pg_type" size="10"><option value="replicated">Replication</option><option value="erasure">Erasure Code</option></select> <br>
							CRUSH Rule Set <br>
							<select name="rule" size="10">
								<?php
								foreach ($crush as $crush) {
									echo ('<option value="'.$crush->rule_name.'">'.$crush->rule_name.'</option>');
								}
								?>
							</select><br>
							Erasure Code Profile (*EC Only) <input type="text" name="erasure_code_profile" value="default"></input> <br>
			        <center><input type="submit" value="Generate"></input><a class="button" href="#">Cancel</a></center>
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
			        <center><input type="submit" value="OK"></input><a class="button" href="#">Cancel</a></center>
			      </form>
					</div>
				</div>
			</div>
			<div id="editPool" class="overlay">
				<div class="popup">
					<h2>Edit <b><?php echo($_GET["name"])?></b></h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
			      <form action="apply" method="POST">
							<input name="type" type="hidden" value="editPool">
							<input name="id" type="hidden" value="<?php echo($_GET["id"]) ?>">
			        PG Value <input type="number" name="pg_num" value=<?php echo($_GET["pg_num"]) ?> <br>
							PGP Value <input type="number" name="pgp_num" value=<?php echo($_GET["pgp_num"]) ?> <br>
							Replica Size <input type="number" name="size" value=<?php echo($_GET["size"]) ?> <br>
			        <input type="submit" value="Submit">
			      </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
