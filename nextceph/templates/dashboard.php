<?php
script('nextceph', 'script');
script('nextceph', 'chart');
script('nextceph', 'chart.min');
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
			echo("Health Report : \n".$obj->finished[0]->outb."\n");

			//usage
			$obj = post($url,$login,$pass,array('prefix'=>'df','detail'=>'detail'));
			$obj = str_replace('\n', ' ', $obj->finished[0]->outb);
			$obj = preg_replace('!\s+!', ' ', $obj);
			$obj = explode(" ",$obj);
			echo ('Usage :');
			echo ('<br>Size : '.$obj[8].' '.$obj[9]);
			echo ('<br>Avail: '.$obj[10].' '.$obj[11]);
			echo ('<br>Raw  : '.$obj[12].' '.$obj[13]);
			echo ('<br>Usage: '.$obj[14].' %');
			echo ('<br>Obj  : '.$obj[15].'<br><br>');

			//Daemons
			$obj = post($url,$login,$pass,array('prefix'=>'node ls'));
			$json = json_decode($obj->finished[0]->outb, true);
			echo ("Daemons :<br>");
			//mon
			$mon = array('data'=>$json['mon'],'total'=>0);
			foreach ($mon['data'] as $key=>$value){
				$mon['total']++;
			}
			if($mon['total'] == 0){
				$mon['total'] = 'No monitor server found';
			}
			echo("MON : ".$mon['total']."<br>");

			//mgr
			$mgr = array('data'=>$json['mgr'],'total'=>0);
			foreach ($mgr['data'] as $key=>$value){
				$mgr['total']++;
				//echo($key."->".$value[0]."<br>");
			}
			if($mgr['total'] == 0){
				$mgr['total'] = 'No manager server found';
			}
			echo("MGR : ".$mgr['total']."<br>");

			//mds
			$mds = array('data'=>$json['mds'],'total'=>0);
			foreach ($mds['data'] as $key=>$value){
			$mds['total']++;
			//echo($key."->".$value[0]."<br>");
			}
			if($mds['total'] == 0){
			$mds['total'] = 'No metadata server found';
			}
			echo("MDS : ".$mds['total']."<br>");

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
			echo("OSD : ".$osd['total']."<br><br>");

			//log
			$obj = post($url,$login,$pass,array('prefix'=>'log last','num'=>100));
			echo("Cluster Log : \n".$obj->finished[0]->outb);
			echo('</pre></main></div>');
			?>
		</div>
	</div>
</div>
