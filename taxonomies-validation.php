<?php
/**
 * Plugin Name: Taxonomies validation used for, Required / By default selected, any taxonomies terms
 * Description: Before saving any post you can set any categories/taxonomies as required, so you can't save the post without selecting the required taxonomy. And also you can set by default any taxonomy selected whenever creating a new post.
 * Plugin URI:  https://wordpress.org/plugins/taxonomies-validation/
 * Author:      chiragrathod103
 * Author URI:  https://www.linkedin.com/in/chirag-r-6a8b77121
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version:     1.0
 * Text Domain: taxonomies-validation
 *
 * @package wp-menu-custom-fields
 */

define( 'TAXONOMIES_VALIDATION_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'TAXONOMIES_VALIDATION_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

// phpcs:disable WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once TAXONOMIES_VALIDATION_PATH . '/inc/helpers/autoloader.php';
// phpcs:enable WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

/**
 * To load plugin manifest class.
 *
 * @return void
 */
function taxonomies_validation_plugin_loader() {
	\Taxonomies_Validation\Inc\Plugin::get_instance();
}
taxonomies_validation_plugin_loader();
