<?php 
/**
 * Template used for the taxonomies essentials page settings.
 */
?>
<div class="tx_main">
	<h1 id="settings_tx_title"><?php _e('Taxonomies Essentials'); ?></h1>
	<form action="options.php" method="post">
		<?php
		// output security fields for the registered setting "wporg"
		settings_fields( 'tx_setting_group' );
		do_settings_sections( 'tx_setting_group' );
		submit_button( 'Save Settings' );
		?>
	</form>
</div>
