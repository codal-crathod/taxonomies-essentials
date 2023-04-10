<?php 
/**
 * Template used for the taxonomies validation page settings.
 */
?>
<div class="tx_main">
	<h1 id="settings_tx_title"><?php _e('Taxonomies Validation'); ?></h1>
	<div id="tabs">
		<ul>
			<li><a href="#all-options-1"><span class="dashicons dashicons-admin-generic"></span>&nbsp;<?php _e('All Options'); ?></a></li>
			<li><a href="#general-2"><span class="dashicons dashicons-admin-tools"></span>&nbsp;<?php _e('General Settings'); ?></a></li>
		</ul>
		<div class="tx_tab-wrapper">
			<div id="all-options-1">
				All Options
				<div class="wrap">
					<form action="options.php" method="post">
						<?php
						// output security fields for the registered setting "wporg"
						settings_fields( 'tx_setting_group' );
						do_settings_sections( 'tx_setting_group' );
						submit_button( 'Save Settings' );
						?>
					</form>
				</div>

			</div>
			<div id="general-2">
				General Settings
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	$( "#tabs" ).tabs();
});
</script>