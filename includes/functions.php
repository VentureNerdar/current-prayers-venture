<?php

/**
 * Functions class
 */


// class DT_Dashboard_Plugin_Functions {
class Current_Prayers_Venture_Functions {
    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    private $version = 1;
    private $context = 'current_prayers_venture';
    private $namespace;

    public function __construct() {
        $this->namespace = $this->context . '/v' . intval($this->version);
        add_filter('dt_front_page', [$this, 'front_page']);

        add_filter('desktop_navbar_menu_options', [$this, 'nav_menu'], 10, 1);
        add_filter('off_canvas_menu_options', [$this, 'nav_menu']);

        $url_path = dt_get_url_path();

        add_action('template_redirect', [$this, 'my_theme_redirect']);

        if (strpos($url_path, 'current-prayers') !== false) {
            add_action('wp_enqueue_scripts', [$this, 'scripts']);
        } else {
            add_action('wp_enqueue_scripts', [$this, 'scripts']);
        }
    }

    public function my_theme_redirect() {
        $url = dt_get_url_path();
        if (strpos($url, 'current-prayers') !== false) {
            $plugin_dir = dirname(__FILE__);
            $path = $plugin_dir . '/template-current-prayers.php';
            status_header(200);
            include($path);
            die();
        }
    }

    public function scripts() {
        wp_enqueue_style('current-prayers-css', plugin_dir_url(__FILE__) . '/style.css', [], filemtime(plugin_dir_path(__FILE__) . 'style.css'));

        wp_enqueue_script(
            'current-prayers-plugin',
            plugin_dir_url(__FILE__) . 'plugin.js',
            [
                'jquery',
            ],
            filemtime(plugin_dir_path(__FILE__) . '/plugin.js'),
            true
        );

        wp_localize_script(
            'current-prayers-plugin',
            'wpApiCurrentPrayers',
            [
                'root'                      => esc_url_raw(rest_url()),
                'site_url'                  => get_site_url(),
                'nonce'                     => wp_create_nonce('wp_rest'),
                'current_user_login'        => wp_get_current_user()->user_login,
                'current_user_id'           => get_current_user_id(),
                'template_dir'              => get_template_directory_uri(),

                'translations'              => Current_Prayers_Venture_Endpoints::instance()->translations(),

                'fields'                    => Current_Prayers_Venture_Endpoints::get_current_prayers_fields(),
                'prayers'                   => Current_Prayers_Venture_Endpoints::get_current_prayers(),
                'post_tiles'                => Current_Prayers_Venture_Endpoints::post_tiles(), // get_post_tiles
            ],
        );
    }

    public function front_page($page) {
        return site_url('/current-prayers/');
    }

    public function nav_menu($tabs) {
        $tabs['current-prayers'] = [
            'link'  => site_url('/current-prayers/'),
            'label' => __('Current Prayers', 'current-prayers-venture')
        ];
        return $tabs;
    }
}
