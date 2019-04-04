<div id="app-settings">
	<div id="app-settings-header">
		<button class="settings-button"
				data-apps-slide-toggle="#app-settings-content"
		></button>
	</div>
	<div id="app-settings-content">
		<!-- Your settings in here -->
		<li> Ceph MGR API Settings :
			<form action="apply" method="post">
				<input name="type" type="hidden" value="applySetting">
				<?php
				echo ('IP Address 	: <input type="text" name="mgrip" value='.$_[0].'><br>');
				echo ('Port 				: <input type="text" name="mgrport" value='.$_[1].'><br>');
				echo ('Username	: <input type="text" name="username" value='.$_[2].'><br>');
				echo ('Password	:<input type="password" name="password" value='.$_[3].'><br>');
				?>
				<input type="submit" name="submit" value="Apply" >
			</form>
		</li>
		<li><a href="#">App Info</a></li>
	</div>
</div>
