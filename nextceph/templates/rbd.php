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
			function get($url){
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				$result = curl_exec($ch);
				$data = json_decode($result);
				return $data;
			}
			function gets($url,$login,$pass){
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

			//list all pools correlated with rbd
			$url = 'https://'.$_[0].':'.$_[1].'/pool';
			$login = $_[2];
			$pass = $_[3];
			$datas = gets($url,$login,$pass);
			$listpools = Array();
			foreach ($datas as $datas) {
				foreach ($datas->application_metadata as $key=>$val){
					if ($key == 'rbd'){
						array_push($listpools,$datas->pool_name);
					}
				}
			}
			$url = 'http://'.$_[4].':'.$_[5].'/rbd';
			$data = Array(get($url));
			foreach ($listpools as $list){
				$url = 'http://'.$_[4].':'.$_[5].'/rbd/'.$list;
				$data[] = get($url);
			}
			echo('<div id="container"><main>');
			echo("<pre><H1>Rados Block Device</H1>\n");
			echo '<a class="button" href="#addImage">Add New RBD Image</a><br><br>';
			echo "<table><tr><th><b>Pool Name</b></th>";
			echo "<th><b>Image Name</b></th>";
			echo "<th><b>Feature</b></th>";
			echo "<th><b>Size</b></th>";
			echo "<th><b>Obj Size</b></th>";
			echo "<th><b>Num Object</b></th>";
			echo "<th><b>Order</b></th>";
			echo "<th><b>Block Name Prefix</b></th>";
			echo "<th><b>Parent Pool</b></th>";
			echo "<th><b>Del</b></th>";
			echo "</tr>";
			foreach ($data as $data){
				$count = 0;
				if ($data->images == null){
					echo '<tr><td><b>'.$data->poolname.'<b></td><td></td></td><td></td></td><td></td></td><td></td></td><td></td></td><td></td></td><td></td></td><td></td></td><td></td></tr>';
				} else {
					foreach ($data->images as $images){
						if ($count == 0){
							echo '<tr><td><b>'.$data->poolname.'<b></td>';
						} else {
							echo '<tr><td></td>';
						}
						echo '<td>'.$images->name.'</td>';
						echo '<td>'.$images->feature.'</td>';
						echo '<td>'.$images->size.'</td>';
						echo '<td>'.$images->objsize.'</td>';
						echo '<td>'.$images->numobjs.'</td>';
						echo '<td>'.$images->order.'</td>';
						echo '<td>'.$images->blocknameprefix.'</td>';
						echo '<td>'.$images->parentpool.'</td>';
						echo '<td><form action="#delImage" method="get"><input name="imagename" type="hidden" value="'.$images->name.'"><input name="poolname" type="hidden" value="'.$data->poolname.'"></input><input type="submit" class="icon-delete" value=""></input></form></td></tr>';
						echo '</tr>';
						$count++;
					}
				}
			}
			echo('</table></pre></main></div>');
			?>
			<div id="addImage" class="overlay">
				<div class="popup">
					<h2>Generate New Image</h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
			      <form action="apply" method="POST">
							<input name="type" type="hidden" value="genRBDImage"></input>
							Select Pool <br>
							<select name="poolname" size="10">
								<?php
								$url = 'http://10.10.2.103:8080/rbd';
								$data = Array(get($url));
								foreach ($listpools as $list){
									$url = 'http://10.10.2.103:8080/rbd/'.$list;
									$data[] = get($url);
								}
								foreach ($data as $data) {
									echo ('<option value="'.$data->poolname.'">'.$data->poolname.'</option>');
								}
								?>
							</select><br>
			        Image Name <input type="text" name="imagename"></input> <br>
							Image Size (E.g : 250M, 4G) <input type="text" name="imagesize"></input> <br>
							Feature <br>
							<select name="imagefeature" size="10">
								<option value="RbdFeaturesDefault">RbdFeaturesDefault</option>
								<option value="RbdFeatureLayering">RbdFeatureLayering</option>
								<option value="RbdFeatureStripingV2">RbdFeatureStripingV2</option>
								<option value="RbdFeatureExclusiveLock">RbdFeatureExclusiveLock</option>
								<option value="RbdFeatureObjectMap">RbdFeatureObjectMap</option>
								<option value="RbdFeatureFastDiff">RbdFeatureFastDiff</option>
								<option value="RbdFeatureDeepFlatten">RbdFeatureDeepFlatten</option>
								<option value="RbdFeatureJournaling">RbdFeatureJournaling</option>
								<option value="RbdFeatureDataPool">RbdFeatureDataPool</option>
							</select><br>
			        <center><input type="submit" value="Generate"></input><a class="button" href="#">Cancel</a></center>
			      </form>
					</div>
				</div>
			</div>
			<div id="delImage" class="overlay">
				<div class="popup">
					<h2>Delete Image</h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
			      <form action="apply" method="POST">
							<input name="type" type="hidden" value="delRBDImage">
							<input name="poolname" type="hidden" value="<?php echo($_GET["poolname"]) ?>">
							<input name="imagename" type="hidden" value="<?php echo($_GET["imagename"]) ?>">
			        Are you sure to delete image <b><?php echo($_GET["imagename"])?></b>?<br>
			        <center><input type="submit" value="OK"></input><a class="button" href="#">Cancel</a></center>
			      </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
