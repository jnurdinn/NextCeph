<div id="app-settings">
	<div id="app-settings-header">
		<button class="settings-button"
				data-apps-slide-toggle="#app-settings-content"
		></button>
	</div>
	<div id="app-settings-content">
		<!-- Your settings in here -->
		<li> Ceph MGR API Settings :
			<form method="get">
				<?php
				include 'settings.php';
				echo ('IP Address 	: <input type="text" name="mgrip" value='.$nc_config['mgr_host'].'><br>');
				echo ('Port 				: <input type="text" name="mgrport" value='.$nc_config['mgr_port'].'><br>');
				echo ('Username	: <input type="text" name="user" value='.$nc_config['user'].'><br>');
				echo ('Password	: <input type="password" name="passwd" value='.$nc_config['psswd'].'><br>');
				if ($_SERVER["REQUEST_METHOD"] == "GET") {
					echo $_GET["mgr-ip"];
					echo $_GET["mgr-port"];
					echo $_GET["user"];
					echo $_GET["passwd"];

					$settings = array(
				    'user' => $_GET["user"],
				    'psswd' => $_GET["passwd"],
				    'mgr_host' => $_GET["mgr-ip"],
				    'mgr_port' => $_GET["mgr-port"],
				  );

					$dataNew = json_encode($settings);
					file_put_contents('settings.json', $dataNew);
				}
				?>
				<input type="submit" name="submit" value="Apply" >
			</form>
		</li>
		<li><a href="#">App Info</a></li>
	</div>
</div>
