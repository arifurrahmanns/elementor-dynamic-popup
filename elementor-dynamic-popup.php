<?php
/**
 * Plugin Name:       Elementor Dynamic Popup
 * Plugin URI:        https://github.com/your-repo/elementor-dynamic-popup
 * Description:       Add Elementor popups to Loop Grid items and dynamic templates with content based on each listing item.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Your Name
 * Author URI:        https://example.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       elementor-dynamic-popup
 * Domain Path:       /languages
 *
 * @package Elementor_Dynamic_Popup
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EDP_VERSION', '1.0.0' );
define( 'EDP_FILE', __FILE__ );
define( 'EDP_PATH', plugin_dir_path( __FILE__ ) );
define( 'EDP_URL', plugin_dir_url( __FILE__ ) );

/**
 * Check if Elementor and Elementor Pro are active.
 */
function edp_check_dependencies() {
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'edp_elementor_missing_notice' );
		return false;
	}

	if ( ! class_exists( 'ElementorPro\Plugin' ) ) {
		add_action( 'admin_notices', 'edp_elementor_pro_missing_notice' );
		return false;
	}

	return true;
}

/**
 * Admin notice for missing Elementor.
 */
function edp_elementor_missing_notice() {
	?>
	<div class="notice notice-warning is-dismissible">
		<p><?php esc_html_e( 'Elementor Dynamic Popup requires Elementor to be installed and activated.', 'elementor-dynamic-popup' ); ?></p>
	</div>
	<?php
}

/**
 * Admin notice for missing Elementor Pro.
 */
function edp_elementor_pro_missing_notice() {
	?>
	<div class="notice notice-warning is-dismissible">
		<p><?php esc_html_e( 'Elementor Dynamic Popup requires Elementor Pro (for Popups) to be installed and activated.', 'elementor-dynamic-popup' ); ?></p>
	</div>
	<?php
}

/**
 * Main plugin class.
 */
final class Elementor_Dynamic_Popup {

	/**
	 * Instance.
	 *
	 * @var Elementor_Dynamic_Popup
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return Elementor_Dynamic_Popup
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_scripts' ) );
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'register_styles' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Register widgets.
	 *
	 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
	 */
	public function register_widgets( $widgets_manager ) {
		require_once EDP_PATH . 'includes/class-edp-popup-trigger-widget.php';
		$widgets_manager->register( new \EDP\Popup_Trigger_Widget() );
	}

	/**
	 * Register frontend scripts.
	 */
	public function register_scripts() {
		wp_register_script(
			'edp-frontend',
			EDP_URL . 'assets/js/frontend.js',
			array( 'jquery' ),
			EDP_VERSION,
			true
		);

		wp_localize_script(
			'edp-frontend',
			'elementorDynamicPopup',
			array(
				'i18n' => array(
					'close' => __( 'Close', 'elementor-dynamic-popup' ),
				),
			)
		);
	}

	/**
	 * Register frontend styles.
	 */
	public function register_styles() {
		wp_register_style(
			'edp-frontend',
			EDP_URL . 'assets/css/frontend.css',
			array(),
			EDP_VERSION
		);
	}

	/**
	 * Enqueue styles when widget is used.
	 */
	public function enqueue_styles() {
		if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			wp_enqueue_style( 'edp-frontend' );
		}
	}
}

/**
 * Initialize the plugin.
 */
function edp_init() {
	if ( ! edp_check_dependencies() ) {
		return;
	}

	Elementor_Dynamic_Popup::instance();
}

add_action( 'plugins_loaded', 'edp_init', 20 );
