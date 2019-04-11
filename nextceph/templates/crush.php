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

			$url = 'https://'.$_[0].':'.$_[1].'/crush/rule';
			$crush = get($url, $_[2], $_[3]);

			echo '<div id="container"><main>';

			//crush rule
			echo '<div class="pad-crush"><pre><H1>CRUSH Rules</H1><br>';
			echo '<a class="button" href="#addCRUSH">Add CRUSH Rule</a><br><br>';
			echo '<table><tr><th><b>Rule Name</b></th>';
			echo '<th><b>Key</b></th>';
			echo '<th><b>Value</b></th>';
			echo '<th><b>Del</b></th>';
			echo '</tr>';
      foreach ($crush as $data){
				$count = 0;
	      echo "<tr>";
				foreach ($data as $key=>$val){
					if ($count == 0){
						echo "<td><b>".$data->rule_name."</b></td>";
					} else if ($key != "rule_name") {
						echo "<td></td>";
					}
					if ($key != "rule_name"){
						echo '<td>'.$key.'</td>';
						if ($key != 'steps'){
							echo '<td>'.$val.'</td>';
						} else {
							echo '<td>';
							foreach ($val as $step){
								  foreach ($step as $keys=>$vals){
										echo $keys.' : '.$vals;
										if ($vals != end($step) || $keys == 'num'){
											echo '<br>';
										}
									}
									if ($step != end($val)){
										echo '<hr>';
									}
							}
							echo '<br></td>';
						}
					}
					if ($count == 0){
						echo '<td><form action="#delCRUSH" method="get"><input name="name" type="hidden" value="'.$data->rule_name.'"></input><input type="submit" class="icon-delete" value=""></input></form></td>';
					} else if ($key != "rule_name")  {
						echo "<td></td>";
					}
					echo "</tr>";
					$count++;
				}
	    }
			echo '</table></div>';

			//erasure code
			$ec_url = 'https://'.$_[0].':'.$_[1].'/request?wait=1';
			$obj = post($ec_url,$_[2],$_[3],array('prefix'=>'osd erasure-code-profile ls'));
			$obj = explode("\n", $obj->finished[0]->outb);

			echo '<div class="pad-erasure"><pre><H1>Erasure Code Profile</H1><br>';
			echo '<a class="button" href="#addECP"> Add EC Profile</a><br><br>';
			echo '<table><tr><th><b>Profile Name</b></th>';
			echo '<th><b>Key</b></th>';
			echo '<th><b>Value</b></th>';
			echo '<th><b>Del</b></th></tr>';
			foreach ($obj as $data){
				if ($data != end($obj)){
					$obj2 = post($ec_url,$_[2],$_[3],array('prefix'=>'osd erasure-code-profile get','name'=>$data));
					$obj2 = explode("\n", $obj2->finished[0]->outb);
					$count = 0;
					foreach ($obj2 as $sep){
						if ($sep != end($obj2)){
							$sep = explode("=", $sep);
							$sep = Array($sep[0]=>$sep[1]);
							foreach ($sep as $s_key=>$s_val){
								echo '<tr>';
								if ($count==0){
									echo '<td><b>'.$data.'</b></td>';
								} else {
									echo '<td></td>';
								}
								echo '<td>'.$s_key.'</td>';
								echo '<td>'.$s_val.'</td>';
								if ($count==0){
									if ($data != 'default'){
											echo '<td><form action="#delECP" method="get"><input name="name" type="hidden" value="'.$data.'"></input><input type="submit" class="icon-delete" value=""></input></form></td>';
									} else {
											echo '<td></td>';
									}
									$count = 1;
								} else {
									echo '<td></td>';
								}
								echo '</tr>';
							}
						}
					}
				}
			}
			echo '</table>';
			echo '</div>';
			echo '</main></div>';
			?>
			<div id="addCRUSH" class="overlay">
				<div class="popup">
					<h2>Generate New Pool</h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
			      <form action="apply" method="POST">
							<input name="type" type="hidden" value="genCRUSH">
							Rule Name <input type="text" name="name"> <br>
							Root <input type="text" name="root" value="default"> <br>
							Type <input type="text" name="crush_type" value="osd"><br>
							Mode <br>
							<select name="mode" size="10"><option value="firstn">firstn</option><option value="indep">indep</option></select> <br>
			        <center><input type="submit" value="Generate"></input><a class="button" href="#">Cancel</a></center>
			      </form>
					</div>
				</div>
			</div>
			<div id="addECP" class="overlay">
				<div class="popup">
					<h2>Generate New EC Profile</h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
			      <form action="apply" method="POST">
							<input name="type" type="hidden" value="genECP">
							Profile Name <input type="text" name="name"> <br>
							Profile <input type="text" name="profile" value="k=5 m=2"> <br>
			        <center><input type="submit" value="Generate"></input><a class="button" href="#">Cancel</a></center>
			      </form>
					</div>
				</div>
			</div>
			<div id="delCRUSH" class="overlay">
				<div class="popup">
					<h2>Delete CRUSH Rule</h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
			      <form action="apply" method="POST">
							<input name="type" type="hidden" value="delCRUSH">
							<input name="name" type="hidden" value="<?php echo($_GET["name"]) ?>">
			        Are you sure to delete <b><?php echo($_GET["name"])?></b>?<br>
			        <center><input type="submit" value="OK"></input><a class="button" href="#">Cancel</a></center>
			      </form>
					</div>
				</div>
			</div>
			<div id="delECP" class="overlay">
				<div class="popup">
					<h2>Delete EC Profile</h2>
					<a class="close" href="#">&times;</a>
					<div class="content">
			      <form action="apply" method="POST">
							<input name="type" type="hidden" value="delECP">
							<input name="name" type="hidden" value="<?php echo($_GET["name"]) ?>">
			        Are you sure to delete <b><?php echo($_GET["name"])?></b>?<br>
			        <center><input type="submit" value="OK"></input><a class="button" href="#">Cancel</a></center>
			      </form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
