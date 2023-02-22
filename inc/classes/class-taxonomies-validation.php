<?php
/**
 * Taxonomies Validation class definition.
 *
 * @package taxonomies-validation-opts
 */

namespace Taxonomies_Validation\Inc;

use Taxonomies_Validation\Inc\Traits\Singleton;

/**
 * Class Taxonomies_Validation
 * Create admin page for save settings and options and used for the operation.
 * Enqueues necessary scripts on admin side.
 */
class Taxonomies_Validation {

	use Singleton;

	/**
	 * Holds options and it's required fields.
	 *
	 * @var array
	 */
	private $options = array(
		'all_options' => array(
			'shortcode',
			'shortcode-caption',
		),
		'general'     => array(
			'media-id',
			'media-type',
			'media-link',
			'media-caption',
		),
	);

	/**
	 * Holds options.
	 *
	 * @var string
	 */
	private $option = 'taxonomies-validation-opts';

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
		//add_action( 'wp_nav_menu_item_custom_fields', array( $this, 'wp_nav_menu_item_custom_fields' ), 10, 4 );
		//add_action( 'wp_update_nav_menu_item', array( $this, 'wp_update_nav_menu_item' ), 10, 2 );

		/**
		 * Filter
		 */
		//add_filter( 'wp_nav_menu_objects', array( $this, 'wp_nav_menu_objects' ), 10, 2 );
		//add_filter( 'walker_nav_menu_start_el', array( $this, 'walker_nav_menu_start_el' ), 10, 4 );

	}

	/**
	 * Function to set transient data.
	 *
	 * @param int   $opts_key Save All Options here.
	 * @param array $data Data to be stored in transient.
	 *
	 * @return void
	 */
	private function cache_nav_menu_meta_data( $opts_key, $data ) {
		set_transient( $this->meta_key . '-' . $item_id, $data, DAY_IN_SECONDS );
	}
}