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

			$url = 'https://'.$nc_config['mgr_host'].':'.$nc_config['mgr_port'].'/request?wait=1';
			$login = $nc_config['user'];
			$pass = $nc_config['psswd'];

			echo('<div id="container"><main>');
			echo("<pre><H1>Dashboard</H1>\n");
			//health report
			$obj = post($url,$login,$pass,array('prefix'=>'health'));
			echo('<div id="padding"><h2>Health Report :</h2>'.$obj->finished[0]->outb."</div>\n");

			//usage
			$obj = post($url,$login,$pass,array('prefix'=>'df','detail'=>'detail'));
			$obj = str_replace('\n', ' ', $obj->finished[0]->outb);
			$obj = preg_replace('!\s+!', ' ', $obj);
			$obj = explode(" ",$obj);
			echo ('<div id="padding"><h2>Usage :</h2>');
			echo ('Size : '.$obj[8].' '.$obj[9]);
			echo ('<br>Avail: '.$obj[10].' '.$obj[11]);
			echo ('<br>Raw  : '.$obj[12].' '.$obj[13]);
			echo ('<br>Obj  : '.$obj[15]);
			echo ('<br><br>Usage in Percentage: <progress value='.$obj[14].' max="100"></progress>'.$obj[14]."%	</div>\n");

			//Daemons
			$obj = post($url,$login,$pass,array('prefix'=>'node ls'));
			$json = json_decode($obj->finished[0]->outb, true);
			//mon
			$mon = array('data'=>$json['mon'],'total'=>0);
			foreach ($mon['data'] as $key=>$value){
				$mon['total']++;
			}
			if($mon['total'] == 0){
				$mon['total'] = 'No monitor server found';
			}

			//mgr
			$mgr = array('data'=>$json['mgr'],'total'=>0);
			foreach ($mgr['data'] as $key=>$value){
				$mgr['total']++;
			}
			if($mgr['total'] == 0){
				$mgr['total'] = 'No manager server found';
			}


			//mds
			$mds = array('data'=>$json['mds'],'total'=>0);
			foreach ($mds['data'] as $key=>$value){
			$mds['total']++;
			//echo($key."->".$value[0]."<br>");
			}
			if($mds['total'] == 0){
			$mds['total'] = 'No metadata server found';
			}


			//osd
			$osd = array('data'=>$json['osd'],'total'=>0);
			foreach ($osd['data'] as $key=>$value){
			foreach ($value as $value){
			$osd['total']++;
			//echo($key."->".$value."<br>");
				}
			}
			if($osd['total'] == 0){
			$osd['total'] = 'No OSD server found';
			}

			//print daemons
			echo('<div id="pad1"><mons><a href="mon">MON</a><br></mons>'.$mon['total'].'</div>');
			echo('<div id="pad2"><mons>MGR</mons><br>'.$mgr['total'].'</div>');
			echo('<div id="pad3"><mons>MDS<br></mons>'.$mds['total'].'</div>');
			echo('<div id="pad4"><mons><a href="osd">OSD</a><br></mons>'.$osd['total']."</div><br>");

			//log
			$obj = post($url,$login,$pass,array('prefix'=>'log last','num'=>100));
			echo("<h2>Cluster Log : </h2>");
			echo('<div style="height:350px;width:1500px;border:1px solid #ccc;font:16px/26px ;overflow:auto;">');
			echo($obj->finished[0]->outb);
			echo('</div></pre></main></div>');
			?>
		</div>
	</div>
</div>
