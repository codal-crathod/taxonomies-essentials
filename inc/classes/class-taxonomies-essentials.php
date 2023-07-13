<?php
/**
 * Taxonomies Essentials class definition.
 *
 * @package taxonomies-essentials-opts
 */

namespace Taxonomies_Essentials\Inc;

use Taxonomies_Essentials\Inc\Traits\Singleton;

/**
 * Class Taxonomies_Essentials
 * Create admin page for save settings and options and used for the operation.
 * Enqueues necessary scripts on admin side.
 */
class Taxonomies_Essentials {

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
	private $option = 'taxonomies-essentials-opts';

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
		add_action('in_admin_header', array($this, 'validate_post_before_edit') );


		/**
		 * Filter
		 */
		add_filter( 'use_block_editor_for_post', array( $this, 'validate_post_before_save'), 10, 2 );

	}

	/**
	 * Admin submenu page under settings.
	 *
	 * @return void
	 */
	public function register_menu_page_tx_valid() {
		//echo $_SERVER["REQUEST_URI"] exit;
		add_submenu_page(
			'options-general.php', 'Taxonomies Essentials', 'Taxonomies Essentials', 'manage_options', 'taxonomies-essentials', array($this, 'submenu_page_tx_valid')
		);

		register_setting( 'tx_setting_group', 'txv_options' );

		add_settings_section(
			'tx_setting_section',
			__( 'Required Taxonomies and Terms & By Default Terms Selected' ), 
			array($this, 'tx_setting_section_callback'),
			'tx_setting_group'
		);
		
		add_settings_field( 
			'tx_fields_for_taxonomies', 
			'All Options',
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

		$setting = get_option( 'txv_options' ); 
		if( isset($_GET['dev']) ) pre($setting);

		$args = array(
			'public'   => true,
			'_builtin' => false
		);
 
		$output = 'names'; // 'names' or 'objects' (default: 'names')
		$operator = 'and'; // 'and' or 'or' (default: 'and')
 
		$post_types = get_post_types( $args, $output, $operator );
		array_unshift($post_types,'post');
		?>
		<p><?php _e('You have the flexibility to create your own taxonomy and tags according to your needs. Additionally, you can set any term to be automatically selected by default when creating a new post or editing an existing one. This functionality is compatible with all custom post types.','taxonomies-essentials') ?></p>
		<br>
		<div id="tabs">
			<ul>
				<li><a href="#default-required-options"><span class="dashicons dashicons-warning"></span>&nbsp;<?php _e("Required Terms"); ?></a></li>
				<li><a href="#default-selected-options"><span class="dashicons dashicons-yes-alt"></span>&nbsp;<?php _e("Default Selected Terms"); ?></a></li>
				<li><a href="#general-options"><span class="dashicons dashicons-admin-settings"></span>&nbsp;<?php _e("General Settings"); ?></a></li>
			</ul>
			<div class="tx_tab-wrapper">
				<div id="default-required-options">
					<div class="wrap">
						<p><?php _e('Whenever users create or edit a post, they will be required to select a taxonomy or terms based on the settings provided below.','taxonomies-essentials'); ?></p>
						<div class="tx-essentials-accordian">
							<?php foreach( $post_types as $post_type ) : ?>
								<?php $get_taxonomies = get_object_taxonomies( $post_type ); ?>
								<h3><?php echo esc_html($post_type); ?></h3>
								<div>
									<?php if( !empty($get_taxonomies) ) : ?>
										<?php foreach( $get_taxonomies as $taxonomy ) : ?>
											<?php 
											if( $taxonomy == 'post_format' ||
												$taxonomy == 'product_type' ||
												$taxonomy == 'product_visibility' ||
												$taxonomy == 'product_shipping_class' ||
												strpos($taxonomy,'pa_') === 0 )
											continue;
											?>
											<?php $tax_label = get_taxonomy($taxonomy);
											$tax_label = $tax_label ? $tax_label->label : '' ?>
											<h3 class="inner_title"><?php _e($tax_label); ?></h3>
											<div>
												<label class="selectit any">
													<input type="checkbox" name="txv_options[required][<?php echo esc_attr($post_type) ?>][<?php echo esc_attr($taxonomy) ?>][]" value="any"
														<?php echo $setting['required'][$post_type][$taxonomy][0] == 'any' ? 'checked' : '' ?> 
													>
													<?php echo esc_html('Any one term is required.') ?>
												</label>
												
												<?php
												$args = array("hide_empty" => 0, "taxonomy" => $taxonomy);
												$categories = get_categories($args); 


												$selected_cats = @$setting['required'][$post_type][$taxonomy]; //array( 45, 33, 118 );
												$list = wp_terms_checklist( 0, 
															array( 'taxonomy'=> $taxonomy,
																'selected_cats' => $selected_cats,
																'echo' => false,
																'checked_ontop' => false,
															) 
														);
												?>

												<?php if( $list ) : ?>

													<?php 
													$list = str_replace( 'post_category[]', 'txv_options[required]['.$post_type.']['.$taxonomy.'][]', $list );
													echo $setting['required'][$post_type][$taxonomy][0] == 'any' ? '<ul class="disabled">' : '<ul class="taxonomy_list">';
													echo str_replace( 'tax_input['.$taxonomy.']', 'txv_options[required]['.$post_type.']['.$taxonomy.']', $list );
													echo '</ul>';
													?>

												<?php else: ?>
													<ul><li><?php _e('No item found.','taxonomies-essentials'); ?></li></ul>
												<?php endif; ?>
											</div>
										<?php endforeach; ?>
									<?php else: ?>
										<ul><li><?php _e('No item found.','taxonomies-essentials'); ?></li></ul>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<div id="default-selected-options">
					<div class="wrap">
						<p><?php _e('When users create or edit a post, the taxonomy\'s terms will be automatically set as default based on the settings below.','taxonomies-essentials'); ?></p>
						<div class="tx-essentials-accordian">
							<?php foreach( $post_types as $post_type ) : ?>
								<?php $get_taxonomies = get_object_taxonomies( $post_type ); ?>
								<h3><?php echo esc_html($post_type); ?></h3>
								<div>
									<?php if( !empty($get_taxonomies) ) : ?>
										<?php foreach( $get_taxonomies as $taxonomy ) : ?>
											<?php 
											if( $taxonomy == 'post_format' ||
												$taxonomy == 'product_type' ||
												$taxonomy == 'product_visibility' ||
												$taxonomy == 'product_shipping_class' ||
												strpos($taxonomy,'pa_') === 0 )
											continue;
											?>
											<?php $tax_label = get_taxonomy($taxonomy);
											$tax_label = $tax_label ? $tax_label->label : '' ?>
											<h3 class="inner_title"><?php _e($tax_label); ?></h3>
											<ul class="taxonomy_list">
												<?php
												$args = array("hide_empty" => 0, "taxonomy" => $taxonomy);
												$categories = get_categories($args); 


												$selected_cats = @$setting['selected'][$post_type][$taxonomy]; //array( 45, 33, 118 );
												$list = wp_terms_checklist( 0, 
															array( 'taxonomy'=> $taxonomy,
																'selected_cats' => $selected_cats,
																'echo' => false,
																'checked_ontop' => false,
															) 
														);
												?>

												<?php if( $list ) : ?>

													<?php 
													$list = str_replace( 'post_category[]', 'txv_options[selected]['.$post_type.']['.$taxonomy.'][]', $list );
													echo str_replace( 'tax_input['.$taxonomy.']', 'txv_options[selected]['.$post_type.']['.$taxonomy.']', $list );
													?>

												<?php else: ?>

													<ul><li><?php _e('No item found.','taxonomies-essentials'); ?></li></ul>

												<?php endif; ?>
												</ul>
										<?php endforeach; ?>
									<?php else: ?>
										<ul><li><?php _e('No item found.','taxonomies-essentials'); ?></li></ul>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
				<div id="general-options">
					<div class="wrap">
						<ol>
							<li>
								<label><?php _e('Error message for a required Taxonomy.','taxonomies-essentials') ?><br>
								<small><?php _e('Use {taxonomy-name} for the Taxonomy name.','taxonomies-essentials') ?></small> <br>
								<input type="text" name="txv_options[err_msg_taxonomy]" value="<?php echo isset( $setting['err_msg_taxonomy'] ) ? esc_attr($setting['err_msg_taxonomy']) : esc_attr('{taxonomy-name} is a required'); ?>" style="width: 100%; display: block;"></label>
							</li>
							<li>
								<label><?php _e('Error message for a required Terms.','taxonomies-essentials') ?><br>
								<small><?php _e('Use {term-list} for the Term name or list.','taxonomies-essentials') ?></small> <br>
								<input type="text" name="txv_options[err_msg_term]" value="<?php echo isset( $setting['err_msg_term'] ) ? esc_attr($setting['err_msg_term']) : esc_attr('{term-list} required {taxonomy-name}'); ?>" style="width: 100%; display: block;"></label>
							</li>
						</ol>
					</div>
				</div>
			</div>
		</div>

		<?php
	}

	/**
	 * Function to set term on save any post.
	 * @param Boole $use_block_editor is block editor or not
	 * @param Object $post object of post.
	 *
	 * @return void
	 */
	public function validate_post_before_save( $use_block_editor, $post ) {

		$setting = get_option( 'txv_options' );

		if ( !isset( $setting['selected'] ) )
			return;

		$post_type = get_post_type( $post->ID );
		$post_id   = $post->ID;

		$this->validate_set_terms( $setting, $post_type, $post_id );
		
		return $use_block_editor;
	}

	/**
	 * Function to set term on edit any post.
	 *
	 * @return void
	 */
	public function validate_post_before_edit() {

		$screen = get_current_screen();
		if ( isset($screen->post_type) && isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['post']) ) {

			$setting = get_option( 'txv_options' );

			if ( !isset( $setting['selected'] ) )
				return;

			$post_type = $screen->post_type;
			$post_id = $_GET['post'];

			$this->validate_set_terms( $setting, $post_type, $post_id );
		}
	}

	/**
	 * Function to set term on save any post.
	 *
	 * @param array $setting plugin options
	 * @param String $post_type Post type name.
	 * @param Integer $post_id Post ID.
	 *
	 * @return void
	 */
	public function validate_set_terms( $setting, $post_type, $post_id ) {

		if ( isset( $setting['selected'][$post_type] ) ) {

			/**
			 * @param string   $selected_taxonomy Taxonomy name.
			 * @return array $selected_terms Term ID's list as array.
			 */
			foreach ($setting['selected'][$post_type] as $selected_taxonomy => $selected_terms) {

				if (is_taxonomy_hierarchical($selected_taxonomy) ) {

					$existing_term = get_the_terms( $post_id, $selected_taxonomy );
					foreach ($existing_term as $k => $term) {
						$selected_terms[] = $term->term_id;
					}

					/**
					 * Filters whether or not to skip/update some terms.
					 *
					 * @since 1.0
					 *
					 * @param array  $selected_terms_slug   Taxonomy terms slug list.
					 * @param bool   $is_hierarchidal       Current post's taxonomy hierarchidal or not.
					 * @param int    $post_id               Current post ID.
					 */
					$selected_terms = apply_filters( 'taxonomies_essentials_selected_terms', $selected_terms, true, $post_id );

					// set terms for hierarchidal taxonomy
					wp_set_post_terms($post_id, $selected_terms, $selected_taxonomy);
					

				}else{

					// set terms for non-hierarchidal taxonomy
					$selected_terms_slug = [];

					$existing_term = get_the_terms( $post_id, $selected_taxonomy );
					foreach ($existing_term as $k => $term) {
						$selected_terms_slug[] = htmlentities($term->slug);
					}

					foreach ($selected_terms as $k => $term_id) {
						$term = get_term( $term_id , $selected_taxonomy );
						$selected_terms_slug[] = htmlentities($term->slug);
					}

					/**
					 * Filters whether or not to skip/update some terms.
					 *
					 * @since 1.0
					 *
					 * @param array  $selected_terms_slug   Taxonomy terms slug list.
					 * @param bool   $is_hierarchidal       Current post's taxonomy hierarchidal or not.
					 * @param int    $post_id               Current post ID.
					 */
					$slug_list = apply_filters( 'taxonomies_essentials_selected_terms', $selected_terms_slug, false, $post_id );
					
					wp_set_post_terms($post_id, $slug_list, $selected_taxonomy);
				}
			}

		}
	}
}