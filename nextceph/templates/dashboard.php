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

			function append($service, $input){
				if ($service == '') {
					$service = $input;
				} else {
					$service .= ", ".$input;
				}
				return $service;
			}

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

			$url = 'https://'.$_[0].':'.$_[1].'/request?wait=1';
			$login = $_[2];
			$pass = $_[3];

			echo('<div id="container"><main>');
			echo('<pre><H1>Dashboard</H1><br>');
			//health report
			$obj = post($url,$login,$pass,array('prefix'=>'health'));
			$obj = str_replace('; ', '<br>', $obj->finished[0]->outb);
			echo('<div id="healthpad"><mons>Health Report</mons><br><br><div style="height:180px;width:650px;font:16px/26px ;overflow:auto;">'.$obj.'</div></div><br>');

			//usage
			$obj = post($url,$login,$pass,array('prefix'=>'df','detail'=>'detail'));
			$obj = str_replace(' k', 'k', $obj->finished[0]->outb);
			$obj = preg_replace('!\s+!', ' ', $obj);
			$obj = explode(" ",$obj);
			echo ('<div id="usagepad"><mons>Usage</mons><br>');
			echo ('<br>Size : '.$obj[8].' '.$obj[9]);
			echo ('<br>Avail: '.$obj[10].' '.$obj[11]);
			echo ('<br>Raw  : '.$obj[12].' '.$obj[13]);
			echo ('<br>Obj  : '.$obj[15]);
			echo ('<br><br>Usage in Percentage: <progress value='.$obj[14].' max="100"></progress>'.$obj[14]."%	</div>\n");

			//print pool
			echo('<div id="pad5"><mons><a href="pool">Pools</a><br></mons><div style="height:180px;width:650px;font:16px/26px ;overflow:auto;">');
			echo "<table><tr>";
			echo "<td><b>Name</b></td>";
			echo "<td><b>ID</b></td>";
			echo "<td><b>Used (Bytes)</b></td>";
			echo "<td><b>Used (%)</b></td>";
			echo "<td><b>Objects</b></td>";
			echo "<td><b>Read</b></td>";
			echo "<td><b>Write</b></td><tr>";
			foreach ($obj as $obj){
				$i++;
				if ($i >= 34){
					$j = ($i - 34) % 17;
					if ($j == 0){
						echo "<td>$obj</td>";
					}
					if ($j == 1){
						echo "<td>$obj</td>";
					}
					if ($j == 4){
						echo "<td>$obj ";
					}
					if ($j == 5){
						echo "$obj </td>";
					}
					if ($j == 6){
						echo "<td>$obj %</td>";
					}
					if ($j == 9){
						echo "<td>$obj</td>";
					}
					if ($j == 11){
						echo "<td>$obj ";
					}
					if ($j == 12){
						echo "$obj</td>";
					}
					if ($j == 13){
						echo "<td>$obj ";
					}
					if ($j == 14){
						echo "$obj</td><tr>";
					}
				}
			}
			echo '</table></div></div>';

			//Daemons
			$obj = post($url,$login,$pass,array('prefix'=>'node ls'));
			$json = json_decode($obj->finished[0]->outb, true);
			//mon
			$mon = array('data'=>$json['mon'],'total'=>0);
			foreach ($mon['data'] as $val){
				$mon['list'] = append($mon['list'], $val[0]);
				$mon['total']++;
			}
			if($mon['total'] == 0){
				$mon['desc'] = 'No monitor server found';
			} else {
				$mon['desc'] = 'Active : '.$mon['total'].' ('.$mon['list'].')';
			}

			//mgr
			$mgr = array('data'=>$json['mgr'],'total'=>0);
			foreach ($mgr['data'] as $val){
				$mgr['list'] = append($mgr['list'], $val[0]);
				$mgr['total']++;
			}
			if($mgr['total'] == 0){
				$mgr['desc'] = 'No manager server found';
			} else {
				$mgr['desc'] = 'Active : '.$mgr['total'].' ('.$mgr['list'].')';
			}

			//mds
			$mds = array('data'=>$json['mds'],'total'=>0);
			foreach ($mds['data'] as $val){
				$mds['list'] = append($mds['list'], $val[0]);
				$mds['total']++;
			}
			if($mds['total'] == 0){
				$mds['desc'] = 'No manager server found';
			} else {
				$mds['desc'] = 'Active : '.$mds['total'].' ('.$mds['list'].')';
			}


			//osd
			$osd = array('data'=>$json['osd'],'total'=>0);
			foreach ($osd['data'] as $key=>$value){
				foreach ($value as $value){
					$osd['total']++;
				}
			}
			if($osd['total'] == 0){
				$osd['desc'] = 'No OSD server found';
			} else {
				$osd['desc'] = 'Active : '.$osd['total'];
			}

			//print daemons?>
			<div id="pad1"><mons><img src="<?php print_unescaped(image_path('nextceph', 'mon.png'));?>"/><a href="mon"> MON</a></mons><br><?php echo $mon['desc']
			?></div><div id="pad2"><mons><img src="<?php print_unescaped(image_path('nextceph', 'osd.png'));?>"/><a href="osd"> OSD</a></mons><br><?php echo $osd['desc']
			?></div><div id="pad3"><mons><img src="<?php print_unescaped(image_path('nextceph', 'mds.png'));?>"/> MDS</mons><br><?php echo $mds['desc']
			?></div><div id="pad4"><mons><img src="<?php print_unescaped(image_path('nextceph', 'mgr.png'));?>"/> MGR</mons><br><?php echo $mgr['desc']?></div><?php
			//log
			$obj = post($url,$login,$pass,array('prefix'=>'log last','num'=>100));
			echo('<div="logpad"><h2>Cluster Log : </h2>');
			echo('<div style="height:250px;width:1500px;border:1px solid #ccc;font:16px/26px ;overflow:auto;">');
			echo($obj->finished[0]->outb);
			echo('</div></pre></main></div></div>');
			?>
		</div>
	</div>
</div>
