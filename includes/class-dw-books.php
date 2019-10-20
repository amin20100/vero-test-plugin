<?php
/**
 * Main plugin class
 *
 * @author Am!n <amin.nz>
 * @since 1.0
 */

final class DW_Books
{
	/**
	 * Plugin version.
	 *
	 * @var string
	 */
    public $version = '1.0';


	/**
	 * The single instance of the class.
	 *
	 * @var DW_Books
	 */
	protected static $_instance = null;


	/**
	 * Main DW_Books Instance.

	 * Ensures only one instance of DW_Books is loaded or can be loaded.
	 *
	 * @static
	 * @return DW_Books - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
        }

		return self::$_instance;
    }

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
        $this->init_hooks();
    }

    /**
     * Define constants
     */
    public function define_constants() {
		$this->define( 'DW_ABSPATH', dirname( DW_PLUGIN_FILE ) . '/' );
		$this->define( 'DW_PLUGIN_BASENAME', plugin_basename( DW_PLUGIN_FILE ) );
		$this->define( 'DW_BOOKING_VERSION', $this->version );
		$this->define( 'DW_PLUGIN_URL', $this->plugin_url() );
    }

    /**
     * Include required files and dependancies
     */
    public function includes() {
        include_once DW_ABSPATH . 'includes/functions-helper.php';

        /**
         * psr-4 Class autoloader
         */
        include_once DW_ABSPATH . 'includes/class-dw-autoloader.php';
    }

    /**
     * INIT hooks
     */
    public function init_hooks() {
        register_activation_hook( DW_PLUGIN_FILE, array( 'DW_Install', 'install' ) );
        add_action('plugins_loaded', array('DW_Post_Types', 'init'));
        add_action( 'admin_menu', [$this, 'admin_menu'], 9 );
    }

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
    }

	/**
	 * Get the plugin url.
	 *
	 * @return string
	 */
	public function plugin_url() {
		return untrailingslashit( plugins_url( '/', DW_PLUGIN_FILE ) );
    }

    public function admin_menu() {
        $hook = add_submenu_page('edit.php?post_type=book', __('Book informations', 'dw-books'), __('Book info', 'dw-books'), 'manage_options', 'book-info', [$this,'book_info_page']);
        add_action( "load-$hook", [ $this, 'screen_option' ] );
    }

    public function book_info_page() {
        include DW_ABSPATH . '/includes/views/book-info.php';
    }

    public function screen_option() {
        $option = 'per_page';
		$args   = [
			'label'   => 'Customers',
			'default' => 5,
			'option'  => 'customers_per_page'
		];

		add_screen_option( $option, $args );

		$this->books_info_object = new Books_Info_List();
    }
}
