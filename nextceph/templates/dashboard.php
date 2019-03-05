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
			echo($obj->finished[0]->outb);
			//mon
			$obj = post($url,$login,$pass,array('prefix'=>'node ls','type'=>'mon'));
			$mon = array('data'=>json_decode($obj->finished[0]->outb, true),'total'=>0);
			foreach ($mon['data'] as $key=>$value){
				$mon['total']++;
				echo($key."->".$value[0]."<br>");
			}
			echo("MON : \n".$mon['total']."\n");
			//mgr
			$obj = post($url,$login,$pass,array('prefix'=>'node ls','type'=>'mgr'));
			echo("MGR : \n".$obj->finished[0]->outb);
			//osd
			$obj = post($url,$login,$pass,array('prefix'=>'node ls','type'=>'osd'));
			echo("OSD : \n".$obj->finished[0]->outb);
			//mds
			$obj = post($url,$login,$pass,array('prefix'=>'node ls','type'=>'mds'));
			echo("MDS : \n".$obj->finished[0]->outb);
			//log
			$obj = post($url,$login,$pass,array('prefix'=>'log last','num'=>100));
			echo("Cluster Log : \n".$obj->finished[0]->outb);
			echo('</pre></main></div>');
			?>
		</div>
	</div>
</div>
