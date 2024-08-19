<?php

/**
 * Rest API example class
 */


// class DT_Dashboard_Plugin_Endpoints
class Current_Prayers_Venture_Endpoints {

    public $permissions = ['access_disciple_tools'];
    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    private $version = 1;
    private $context = 'dt-dashboard';
    private $namespace;

    public function __construct() {

        $this->namespace = $this->context . '/v' . intval($this->version);
        add_action('rest_api_init', [$this, 'add_api_routes']);

    }

    public function has_permission() {
        
        $pass = true;

        foreach ($this->permissions as $permission) {
            if (!current_user_can($permission)) {
                $pass = false;
            }
        }
        return $pass;
        
    }


    //See https://github.com/DiscipleTools/disciple-tools-theme/wiki/Site-to-Site-Link for outside of wordpress authentication
    public function add_api_routes() {

        // reference for future use
        /*
        register_rest_route(
            $this->namespace,
            '/stats',
            [
                'methods'             => 'GET',
                'callback'            => [$this, 'get_other_stats'],
                'permission_callback' => function (WP_REST_Request $request) {
                    return $this->has_permission();
                },
            ]
        );
        */

    } // e.o add_api_routes

    public static function translations() {
        return [
            'contacts_prayer_requests'                                              => __('Contacts Prayer Requests', 'current-prayers-venture'),
            'current_prayers'                                                       => __('Current Prayers', 'current-prayers-venture'),
            'from'                                                                  => __('From', 'current-prayers-venture'),
            'groups_prayer_requests'                                                => __('Groups Prayer Requests', 'current-prayers-venture'),
            'request'                                                               => __('Request', 'current-prayers-venture'),
            'the_system_does_not_have_current_prayers_field_for_the_followings'     => __('The system does not have current prayers field for the followings:', 'current-prayers-venture'),
            'update_needed'                                                         => __('Update Needed', 'current-prayers-venture'),
            'groups'  => __('Groups', 'current-prayers-venture'),
            'group'  => __('Group', 'current-prayers-venture'),
            'church'  => __('Church', 'current-prayers-venture'),
            'contacts'  => __('Contacts', 'current-prayers-venture'),
            // 'contact'  => __('Contacts', 'current-prayers-venture'),

        ];
    }

    public static function get_current_prayers() {
        global $wpdb;

        $contacts = $wpdb->get_results($wpdb->prepare(
            "
            SELECT pm.*, p.*
            FROM $wpdb->postmeta as pm
            INNER JOIN $wpdb->posts as p ON pm.post_id = p.ID
            WHERE pm.meta_key = %s AND p.post_type = %s",
            'current_prayer_requests',
            'contacts'
        ), ARRAY_A);

        $groups = $wpdb->get_results($wpdb->prepare(
            "
            SELECT pm.*, p.*, pmGroup.meta_value AS 'group_type'
            FROM $wpdb->postmeta as pm
            INNER JOIN $wpdb->posts as p ON pm.post_id = p.ID
            RIGHT JOIN $wpdb->postmeta as pmGroup ON pmGroup.post_id = p.ID
            WHERE pm.meta_key = %s 
            AND pmGroup.meta_key = %s
            AND p.post_type = %s 
            ",
            'current_prayer_requests',
            'group_type',
            'groups'
        ), ARRAY_A);

        return [
            'contacts' => $contacts,
            'groups' => $groups
        ];
    }

    public static function get_current_prayers_fields() {
        return [
            'contacts' => DT_Posts::get_post_field_settings('contacts', false, true),
            'groups'   => DT_Posts::get_post_field_settings('groups', false, true),
        ];
    }
}
