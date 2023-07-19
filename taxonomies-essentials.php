<?php
/**
 * Plugin Name: Taxonomies Essentials
 * Description: You have the flexibility to create your own mandatory rules for categories, taxonomies and tags according to your needs. Additionally, you can set any term to be automatically selected by default when creating a new post or editing an existing one. This functionality is compatible with all custom post types.
 * Plugin URI:        https://wordpress.org/plugins/taxonomies-essentials/
 * Author:            chiragrathod103
 * Author URI:        https://profiles.wordpress.org/chiragrathod103/
 * License:           GPL2
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      5.6
 * Text Domain:       taxonomies-essentials
 *
 * @package taxonomies-essentials
 */

define( 'TX_VALID_VERSION', '1.0' );
define( 'TX_VALID_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'TX_VALID_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

require_once TX_VALID_PATH . '/inc/helpers/autoloader.php';

/**
 * To load plugin manifest class.
 *
 * @return void
 */
function taxonomies_essentials_plugin_loader() {
	\Taxonomies_Essentials\Inc\Plugin::get_instance();
}
taxonomies_essentials_plugin_loader();
            