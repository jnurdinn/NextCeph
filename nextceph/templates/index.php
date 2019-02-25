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
				$nowurl = "$_SERVER[REQUEST_URI]";
				if ($nowurl = "index.php/apps/nextceph/"){
					print_unescaped($this->inc('content/dashboard'));
				} else if ($nowurl = "index.php/apps/nextceph/osd") {
					print_unescaped($this->inc('content/osd'));
				}
			?>
		</div>
	</div>
</div>
