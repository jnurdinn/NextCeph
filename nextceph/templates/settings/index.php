<div id="app-settings">
	<div id="app-settings-header">
		<button class="settings-button"
				data-apps-slide-toggle="#app-settings-content"
		></button>
	</div>
	<div id="app-settings-content">
		<!-- Your settings in here -->
		<form action="apply" method="post">
		<input name="type" type="hidden" value="applySetting">
		<li> Ceph MGR API Settings :<br>
				IP Address 	: <input type="text" name="mgrip" value='<?php p($_[0])?>'>
				Port 				: <input type="text" name="mgrport" value='<?php p($_[1])?>'>
				Username	: <input type="text" name="username" value='<?php p($_[2])?>'>
				Password	:<input type="password" name="password" value='<?php p($_[3])?>'>
		</li>
		<li> Ceph NextRados API Settings :<br>
				IP Address 	: <input type="text" name="nrip" value='<?php p($_[4])?>'>
				Port 				: <input type="text" name="nrport" value='<?php p($_[5])?>'>
				<input type="submit" name="submit" value="Apply" >
		</li>
		</form>
		<li><a href="#">App Info</a></li>
	</div>
</div>
