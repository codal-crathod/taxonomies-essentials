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
		add_action('admin_menu', array($this, 'register_menu_page_tx_valid'));


		/**
		 * Filter
		 */
		//add_filter( 'wp_nav_menu_objects', array( $this, 'wp_nav_menu_objects' ), 10, 2 );
		//add_filter( 'walker_nav_menu_start_el', array( $this, 'walker_nav_menu_start_el' ), 10, 4 );

	}

	/**
	 * Admin submenu page under settings.
	 *
	 * @return void
	 */
	public function register_menu_page_tx_valid() {
		add_submenu_page(
			'options-general.php', 'Taxonomies Validation', 'Taxonomies Validation', 'manage_options', 'taxonomies-validation', array($this, 'submenu_page_tx_valid')
		);

		register_setting( 'tx_setting_group', 'tx_options' );

		add_settings_section(
			'tx_setting_section',
			__( 'The Matrix has you.' ), 
			array($this, 'tx_setting_section_callback'),
			'tx_setting_group'
		);
		
		add_settings_field( 
			'tx_fields_for_taxonomies', 
			'Taxonomies Options',
			array($this, 'tx_fields_for_taxonomies_callback'), 
			'tx_setting_group', 
			'tx_setting_section'
		);
	}

	/**
	 * Admin submenu page render HTML.
	 *
	 * @return void
	 */
	public function submenu_page_tx_valid() {
		if( !current_user_can('manage_options') ) {
			wp_die(__('You dont have enough permissions to view this page.'));
		}

		if( file_exists( TX_VALID_PATH . '/inc/page-content.php' ) ) {
			include TX_VALID_PATH . '/inc/page-content.php';
		}
	}


	/**
	 * Custom fields Section.
	 *
	 * @return void
	 */
	public function tx_setting_section_callback() {
		// https://developer.wordpress.org/plugins/settings/custom-settings-page/
	}

	/**
	 * Custom fields HTML.
	 *
	 * @return void
	 */
	public function tx_fields_for_taxonomies_callback() {
		$setting = get_option( 'tx_options' );
		pre($setting);	
		
		$i = 0; // increment

		// Get all existing taxonomies which are using in the post and custom post type only.
		$args = array(
			'public'   => true,
			'_builtin' => true
		); 
		$get_taxonomies = get_taxonomies($args,'objects','and');
		unset($get_taxonomies['post_format']);
		?>
		<!-- <input type="text" name="tx_options[title]" value="<?php //echo isset( $setting ) ? esc_attr( $setting['title'] ) : ''; ?>"> -->
		<div class="tx-valid-accordian">

			<?php foreach( $get_taxonomies as $taxonomy ) : ?>
			<h3><?php _e($taxonomy->label); ?></h3>
			<div>
				<?php
				$args = array("hide_empty" => 0, "taxonomy" => $taxonomy->name);
				$categories = get_categories($args); 


				$selected_cats = $setting[$taxonomy->name]; //array( 45, 33, 118 );
				$list = wp_terms_checklist( 0, array( 'taxonomy'=> $taxonomy->name, 'selected_cats' => $selected_cats ) );
				?>

				<?php if( !$list ) : ?>

					<p><?php _e('No item found.','taxonomies-validation'); ?></p>

				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<?php
	}

	/**
	 * Function to set transient data.
	 *
	 * @param int   $opts_key Save All Options here.
	 * @param array $data Data to be stored in transient.
	 *
	 * @return void
	 */
	private function cache_tx_valid_data( $opts_key, $data ) {
		set_transient( $this->meta_key . '-' . $item_id, $data, DAY_IN_SECONDS );
	}
}