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
				<?php

				function append($service, $input){
					if ($service['total'] == 0) {
						$service['id'] = $input;
					} else {
						$service['id'] .= ", ".$input;
					}
					$service['total']++;
					return $service;
				}

				function notavail($service){
					if ($service['total'] == 0){
						$output = "N/A";
					} else {
						$output = $service['id'];
					}
					return $output;
				}

				$url = 'https://'.$_[0].':'.$_[1].'/server';
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
				curl_setopt($ch, CURLOPT_USERPWD, "$_[2]:$_[3]");
				$result = curl_exec($ch);
				curl_close($ch);
				$data = json_decode($result);
				echo('<div id="container"><main>');
				echo "<pre><H1>Hosts</H1>\n";
				echo "<table><tr>";
				echo "<td><b>Hostname</b></td>";
				echo "<td><b>Ceph Version</b></td>";
				echo "<td><b>OSD</b></td>";
				echo "<td><b>MON</b></td>";
				echo "<td><b>MDS</b></td>";
				echo "<td><b>MGR</b></td>";
				echo "<td><b>RGW</b></td>";
				echo "</tr>";
	      foreach ($data as $data) {
					$mon = array('id'=>'','total'=>0);
					$mds = array('id'=>'','total'=>0);
					$mgr = array('id'=>'','total'=>0);
					$osd = array('id'=>'','total'=>0);
					$rgw = array('id'=>'','total'=>0);
		      echo "<tr>";
		      echo "<td>$data->hostname</td>";
					echo "<td>$data->ceph_version</td>";
					foreach ($data->services as $services){
						switch ($services->type) {
							case "osd":
								$osd = append($osd,$services->id);
								break;
							case "mon":
								$mon = append($mon,$services->id);
								break;
							case "mds":
								$mds = append($mds,$services->id);
								break;
							case "mgr":
								$mgr = append($mgr,$services->id);
								break;
							case "rgw":
								$rgw = append($rgw,$services->id);
								break;
						}
					}
					echo "<td>".notavail($osd)."</td>";
					echo "<td>".notavail($mon)."</td>";
					echo "<td>".notavail($mds)."</td>";
					echo "<td>".notavail($mgr)."</td>";
					echo "<td>".notavail($rgw)."</td>";
		      echo "</tr>";
				}
				echo('</pre>');
				echo('</main></div>');
				?>
			</div>
		</div>
	</div>
</div>
