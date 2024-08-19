<?php

/**
 * Current_Prayers_Venture_Plugin_Menu class for the admin page
 *
 * @class       Current_Prayers_Venture_Plugin_Menu
 * @version     0.1.0
 * @since       0.1.0
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Initialize menu class
 */
Current_Prayers_Venture_Plugin_Menu::instance();

/**
 * Class Current_Prayers_Venture_Plugin_Menu
 */
class Current_Prayers_Venture_Plugin_Menu {
    public $token = 'dt_dashboard_plugin';

    private static $_instance = null;

    /**
     * Current_Prayers_Venture_Plugin_Menu Instance
     *
     * Ensures only one instance of Current_Prayers_Venture_Plugin_Menu is loaded or can be loaded.
     *
     * @return Current_Prayers_Venture_Plugin_Menu instance
     * @since 0.1.0
     * @static
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()


    /**
     * Constructor function.
     * @access  public
     * @since   0.1.0
     */
    public function __construct() {

        add_action('admin_menu', [$this, 'register_menu']);
        add_action('admin_enqueue_scripts', function () {
            $this->scripts();
        }, 1);
    } // End __construct()


    /**
     * Loads the subnav page
     * @since 0.1
     */
    public function register_menu() {
        /*
        add_submenu_page(
            'dt_extensions',
            __(
                'Current Prayers Venture',
                'current_prayers_venture'
            ),
            __(
                'Current Prayers Venture',
                'current_prayers_venture'
            ),
            'manage_dt',
            $this->token,
            [$this, 'content']
        );
        */
    }

    /**
     * Menu stub. Replaced when Disciple.Tools Theme fully loads.
     */
    public function extensions_menu() {
    }

    public function scripts() {
        wp_localize_script('wp-api', 'dashboardWPApiShare', [
            'nonce' => wp_create_nonce('wp_rest'),
            'root'  => esc_url_raw(rest_url()) . 'dt-dashboard'
        ]);
        wp_enqueue_script('jquery');
        wp_register_script('jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.min.js', ['jquery'], '1.12.1');
        wp_enqueue_script('jquery-ui');
        /*
        wp_enqueue_script('dt-admin', DT_Dashboard_Plugin::path() . 'includes/admin.js', [
            'wp-api',
            'jquery',
            'jquery-ui',
        ], filemtime(DT_Dashboard_Plugin::dir() . 'includes/admin.js'), true);
        */
    }

    /**
     * Builds page contents
     * @since 0.1
     */

    public function content() {

        if (!current_user_can('manage_dt')) { // manage dt is a permission that is specific to Disciple.Tools and allows admins, strategists and dispatchers into the wp-admin
            wp_die(esc_attr__('You do not have sufficient permissions to access this page.'));
        }

        status_header(200);

        include Current_Prayers_Venture_Plugin::includes_dir() . 'template-admin.php';
    }
}
