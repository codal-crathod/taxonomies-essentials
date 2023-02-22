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

		if ( 'taxonomies-validation.php' === $hook_suffix ) {
			/*$file_path = TAXONOMIES_VALIDATION_PATH . '/assets/build/css/admin.css';
			$time      = time();
			if ( file_exists( $file_path ) ) {
				$time = filemtime( $file_path );
			}

			wp_enqueue_style( 'wp-menu-custom-fields-admin-style', TAXONOMIES_VALIDATION_URL . '/assets/build/css/admin.css', array(), $time );
			wp_enqueue_style( 'dashicons' );

			wp_enqueue_editor();
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'wp-tinymce' );
			wp_enqueue_media();

			$file_path = TAXONOMIES_VALIDATION_PATH . '/assets/build/js/admin.js';
			$time      = time();
			if ( file_exists( $file_path ) ) {
				$time = filemtime( $file_path );
			}
			wp_enqueue_script( 'wp-menu-custom-fields-admin-script', TAXONOMIES_VALIDATION_URL . '/assets/build/js/admin.js', array( 'jquery', 'wp-tinymce', 'media-editor', 'media-views' ), $time, true );

			wp_localize_script(
				'wp-menu-custom-fields-admin-script',
				'wpMenuCustomFields',
				array(
					'selectMediaText' => esc_html__( 'Select Image', 'wp-menu-custom-fields' ),
				)
			);*/
		}
	}

}
