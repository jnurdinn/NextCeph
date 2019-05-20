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

			function get($url, $login, $pass){
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
				return $data;
			}

			$url = 'https://'.$_[0].':'.$_[1].'/osd';
			$login = $_[2];
			$pass = $_[3];
			$data = get($url,$login,$pass);

			echo('<div id="container"><main>');
			echo '<pre><H1>Object Storage Daemons</H1><br>';
			echo '<a class="button" href="#addOSD">Add New OSD</a><br><br>';
			echo '<table><tr>';
			echo '<td><b>OSD ID</b></td>';
			echo '<td><b>Hostname</b></td>';
			echo '<td><b>Cluster Address</b></td>';
			echo '<td><b>Public Address</b></td>';
			echo '<td><b>State</b></td>';
			echo '<td><b>UUID</b></td>';
			echo '<td><b>OSD Reweight</b></td>';
			echo '<td><b>Details</b></td>';
			echo '<td><b>Del</b></td></tr>';
      foreach ($data as $data) {
	      echo '<tr>';
	      echo '<td>'.$data->osd.'</td>';
				echo '<td>'.$data->server.'</td>';
				echo '<td>'.$data->cluster_addr.'</td>';
				echo '<td>'.$data->public_addr.'</td><td>';
				foreach ($data->state as $state){
					echo $state.'<br>';
				}
				echo '<td>'.$data->uuid.'</td><td>';
				echo (boolval($data->weight) ? 'true' : 'false');
				echo '</td><td><form action="#viewOSD" method="get"><input name="id" type="hidden" value="'.$data->osd.'"></input><input type="submit" class="icon-menu" value=""></input></form></td>';
				echo '</td><td><form action="#delOSD" method="get"><input name="id" type="hidden" value="'.$data->osd.'"></input><input type="submit" class="icon-delete" value=""></input></form></td></tr>';
			}
			echo '</table></pre></main></div>';
			?>
		</div>
		<div id="addOSD" class="overlay">
			<div class="popup">
				<h2>Add New OSD</h2>
				<a class="close" href="#">&times;</a>
				<div class="content">
					<form action="apply" method="POST">
						<input name="type" type="hidden" value="genOSD">
						UUID <input name="uuid" value="<?php echo($_GET["weight"]) ?>">
						ID <input name="id" value="<?php echo($_GET["up"]) ?>">
						<input type="submit" value="Submit">
					</form>
				</div>
			</div>
		</div>
		<div id="delOSD" class="overlay">
			<div class="popup">
				<h2>Delete OSD</h2>
				<a class="close" href="#">&times;</a>
				<div class="content">
					<form action="apply" method="POST">
						<input name="type" type="hidden" value="delOSD">
						<input name="id" type="hidden" value="<?php echo($_GET["id"]) ?>">
						Are you sure to delete OSD <b><?php echo($_GET["id"])?></b>?<br>
						<center><input type="submit" value="OK"></input><a class="button" href="#">Cancel</a></center>
					</form>
				</div>
			</div>
		</div>
		<div id="viewOSD" class="overlay">
			<div class="popup">
				<h2>OSD <b><?php echo($_GET["id"])?></b> Details</h2>
				<a class="close" href="#">&times;</a>
				<div class="content">
					<?php
						$osdurl = 'https://'.$_[0].':'.$_[1].'/osd/'.$_GET["id"];
						$osddata = get($osdurl,$_[2],$_[3]);
						echo '<div style="height:500px;width:530px;font:16px/26px ;overflow:auto;">';
						echo '<table><tr>';
						echo '<td><b>KEY</b></td>';
						echo '<td><b>VAL</b></td></tr>';
						foreach ($osddata as $key=>$val) {
							echo '<td>'.$key.'</td>';
							if (is_Array($val)){
								echo '<td>';
								foreach ($val as $val) {
									echo $val.' ';
								}
								echo '</td></tr>';
							} else {
								echo '<td>'.$val.'</td></tr>';
							}
						}
						echo '</div>';
					?>
				</div>
			</div>
		</div>
	</div>
</div>
