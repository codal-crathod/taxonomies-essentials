<?php
/**
 * Assets class.
 *
 * @package wp-menu-custom-fields
 */

namespace Taxonomies_Essentials\Inc;

use Taxonomies_Essentials\Inc\Traits\Singleton;

/**
 * Class Assets
 */
class Assets {

	use Singleton;

	/**
	 * Construct method.
	 */
	protected function __construct() {
		$this->setup_hooks();
	}

	/**
	 * To setup action/filter.
	 *
	 * @return void
	 */
	protected function setup_hooks() {

		/**
		 * Action
		 */
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

	}


	/**
	 * To enqueue scripts and styles. in admin.
	 *
	 * @param string $hook_suffix Admin page name.
	 *
	 * @return void
	 */
	public function admin_enqueue_scripts( $hook_suffix ) {

		global $pagenow;
		if ( (isset( $_GET['page'] ) && $_GET['page'] == 'taxonomies-essentials') || 
			( ($pagenow == 'post.php' || $pagenow == 'post-new.php') && (get_post_type() != 'page')) ) {

			$file_path = TX_VALID_PATH . '/assets/js/admin.js';
			$time      = time();
			if ( file_exists( $file_path ) ) {
				$time = filemtime( $file_path );
			}

			wp_enqueue_script('jquery-ui-tabs',array( 'jquery' ), TX_VALID_VERSION, false);
			wp_enqueue_script('jquery-ui-accordion',array( 'jquery' ), TX_VALID_VERSION, false);
			wp_enqueue_script( 'tx-essentials-admin', TX_VALID_URL . '/assets/js/admin.js', array('jquery'), $$time, true );
			$txv_options = get_option( 'txv_options' );

			// taxonomy name in options array we will add if rest_base slug is different.
			foreach ($txv_options as $key => $obj) {
				foreach ($obj as $ptype => $taxonomies) {
					foreach ($taxonomies as $taxonomy => $terms) { 
						foreach ($terms as $k => $id) {
							$term = get_term( $id , $taxonomy );
							$txv_options[$id] = $term->name;
						}
					}
					$taxonomies = get_object_taxonomies($ptype, 'objects');
					foreach ($taxonomies as $taxonomy => $t_obj) {
						if( $t_obj->rest_base ) {
							$txv_options[$taxonomy] = $t_obj->rest_base;
						}
						$txv_options[$taxonomy.'-label'] = $t_obj->label;
					}
				}
			}
			$txv_options['ptype'] = get_post_type();
			wp_localize_script( 'tx-essentials-admin', 'txv_options', $txv_options );

			$file_path = TX_VALID_PATH . '/assets/css/admin.css';
			$time      = time();
			if ( file_exists( $file_path ) ) {
				$time = filemtime( $file_path );
			}

			wp_enqueue_style( 'tx-essentials-admin-style', TX_VALID_URL . '/assets/css/admin.css', array(), $time );
			wp_enqueue_style( 'dashicons' );

			$file_path = TX_VALID_PATH . '/assets/css/jquery-ui.min.css';
			$time      = time();
			if ( file_exists( $file_path ) ) {
				$time = filemtime( $file_path );
			}
			wp_enqueue_style( 'jquery-ui-tx-essentials', TX_VALID_URL . '/assets/css/jquery-ui.min.css', array(), $time );

			//wp_register_style( 'jquery-ui-tx-essentials', 'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css' );
			//wp_enqueue_style( 'jquery-ui-tx-essentials' );

		}
	}

}
