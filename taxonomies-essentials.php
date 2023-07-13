<?php
/**
 * Plugin Name: Taxonomies essentials used for, Required and By default selected, any taxonomies terms
 * Description: Before saving any post you can set any categories/taxonomies as required, so you can't save the post without selecting the required taxonomy. And also you can set by default any taxonomy selected whenever creating a new post and updating post.
 * Plugin URI:  https://wordpress.org/plugins/taxonomies-essentials/
 * Author:      chiragrathod103
 * Author URI:  https://www.linkedin.com/in/chirag-r-6a8b77121
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Version:     1.0
 * Text Domain: taxonomies-essentials
 *
 * @package taxonomies-essentials
 */

/*
- include post type : only that post type will work in general settings
- Exceprt require
- featured image require
*/

define( 'TX_VALID_VERSION', '1.0' );
define( 'TX_VALID_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'TX_VALID_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );

// phpcs:disable WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant
require_once TX_VALID_PATH . '/inc/helpers/autoloader.php';
// phpcs:enable WordPressVIPMinimum.Files.IncludingFile.UsingCustomConstant

/**
 * To load plugin manifest class.
 *
 * @return void
 */
function taxonomies_essentials_plugin_loader() {
	\Taxonomies_Essentials\Inc\Plugin::get_instance();
}
taxonomies_essentials_plugin_loader();

/**
 * Display the Print_r data
  * @return void
 */
function pre( $args ) {
    echo '<pre>'; print_r($args); echo '</pre>';
}