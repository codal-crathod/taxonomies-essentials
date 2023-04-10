<?php
/**
 * Assets class.
 *
 * @package wp-menu-custom-fields
 */

namespace Taxonomies_Validation\Inc;

use Taxonomies_Validation\Inc\Traits\Singleton;

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

		if ( isset( $_GET['page'] ) && $_GET['page'] == 'taxonomies-validation' ) {

			$file_path = TX_VALID_PATH . '/assets/js/admin.js';
			$time      = time();
			if ( file_exists( $file_path ) ) {
				$time = filemtime( $file_path );
			}

			wp_enqueue_script('jquery-ui-tabs',array( 'jquery' ), TX_VALID_VERSION, false);
			wp_enqueue_script('jquery-ui-accordion',array( 'jquery' ), TX_VALID_VERSION, false);
			wp_enqueue_script( 'tx-valid-admin', TX_VALID_URL . '/assets/js/admin.js', array('jquery'), $time, true );

			$file_path = TX_VALID_PATH . '/assets/css/admin.css';
			$time      = time();
			if ( file_exists( $file_path ) ) {
				$time = filemtime( $file_path );
			}

			wp_enqueue_style( 'tx-valid-admin-style', TX_VALID_URL . '/assets/css/admin.css', array(), $time );
			wp_enqueue_style( 'dashicons' );

			$file_path = TX_VALID_PATH . '/assets/css/jquery-ui.min.css';
			$time      = time();
			if ( file_exists( $file_path ) ) {
				$time = filemtime( $file_path );
			}
			wp_enqueue_style( 'jquery-ui-tx-valid', TX_VALID_URL . '/assets/css/jquery-ui.min.css', array(), $time );

			//wp_register_style( 'jquery-ui-tx-valid', 'https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css' );
			//wp_enqueue_style( 'jquery-ui-tx-valid' );

		}
	}

}
