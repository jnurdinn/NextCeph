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
			<div id="container">
				<main>
					<pre><H1>Ceph FS
					</H1><table><tr><th><b>FS Name</b></th>
					<th><b>MDS Node</b></th>
					<th><b>Metadata Pool</b></th>
					<th><b>Data Pools</b></th>
					<th><b>Add/Remove Data Pool</b></th>
					<th><b>Delete</b></th></tr>
						<?php
						function get($url){
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL,$url);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
							$result = curl_exec($ch);
							$data = json_decode($result);
							return $data;
						}
						$data = get('http://'.$_[4].':'.$_[5].'/cephfs');
						echo '<a class="button" href="#addCephFS">Add New Ceph FS</a><br><br>';
						echo '<tr>';
						echo '<td><b>'.$data->fsname.'</b></td>';
						echo '<td>'.$data->mdsnode.'</td>';
						echo '<td>'.$data->metadatapool.'</td>';
						echo '<td>';
						foreach ($data->datapools as $datas){
							echo $datas.'<br>';
						}
						echo '</td>';
						echo '<td>';
						echo '<form action="#addDataPool" method="get"><input name="fsname" type="hidden" value="'.$data->fsname.'"></input><input type="submit" class="icon-add" value=""></input></form>';
						echo '<form action="#delDataPool" method="get"><input name="fsname" type="hidden" value="'.$data->fsname.'"></input><input type="submit" class="icon-delete" value=""></input></form>';
						echo '</td>';
						echo '<td><form action="#delCephFS" method="get"><input name="fsname" type="hidden" value="'.$data->fsname.'"></input><input type="submit" class="icon-delete" value=""></input></form></td>';
						echo "</tr>";
						?>
			</table></pre></main></div>
			<div id="addCephFS" class="overlay">
				<div class="popup">
					<h2>Generate New CephFS</h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
						<form action="apply" method="POST">
							<input name="type" type="hidden" value="addCephFS"></input>
							CephFS Name :<input type="text" name="fsname"></input> <br>
							Metadata Pool :<input type="text" name="metapool"></input> <br>
							Data Pool : <input type="text" name="datapool"></input> <br>
							<center><input type="submit" value="Generate"></input><a class="button" href="#">Cancel</a></center>
						</form>
					</div>
				</div>
			</div>
			<div id="addDataPool" class="overlay">
				<div class="popup">
					<h2>Add CephFS Data Pool</h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
						<form action="apply" method="POST">
							<input name="type" type="hidden" value="addDataPool"></input>
							<input name="fsname" type="hidden" value="<?php echo $_GET["fsname"]?>"></input>
							Data Pool : <input type="text" name="datapool"></input> <br>
							<center><input type="submit" value="Generate"></input><a class="button" href="#">Cancel</a></center>
						</form>
					</div>
				</div>
			</div>
			<div id="delCephFS" class="overlay">
				<div class="popup">
					<h2>Delete <b><?php echo $_GET["fsname"]?></b></h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
			      <form action="apply" method="POST">
							<input name="type" type="hidden" value="delCephFS">
							<input name="fsname" type="hidden" value="<?php echo $_GET["fsname"]?>">
			        Are you sure to delete <b><?php echo $_GET["fsname"]?></b>?<br>
			        <center><input type="submit" value="OK"></input><a class="button" href="#">Cancel</a></center>
			      </form>
					</div>
				</div>
			</div>
			<div id="delDataPool" class="overlay">
				<div class="popup">
					<h2>Delete <b><?php echo $_GET["fsname"]?></b> Data Pool</h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
						<form action="apply" method="POST">
							<input name="type" type="hidden" value="delDataPool"></input>
							<input name="fsname" type="hidden" value="<?php echo $_GET["fsname"]?>"></input>
							Data Pool : <input type="text" name="datapool"></input> <br>
							<center><input type="submit" value="Generate"></input><a class="button" href="#">Cancel</a></center>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
