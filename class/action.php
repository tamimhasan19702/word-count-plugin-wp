<?php


class Action extends Base
{

    /**
     * Action hooks init method
     * 
     * @return void
     */
    public static function action_init()
    {

        add_action('wp_login', array('Action', 'register_login'), 10, 2);
    }

    /**
     * Register user login on database
     * 
     * @static
     * @global $wpdb Object WordPress Database handler
     * @return void
     */
    public static function register_login($user_login, $user)
    {
        global $wpdb;

        if (!empty($user)) {
            // log init session.
            $table_name = $wpdb->prefix . parent::$sessions_table_name;
            $data = array(
                'user_id' => $user->data->ID,
                'user_name' => $user_login,
                'user_email' => $user->data->user_email,
                'user_role' => $user->roles[0],
                'last_session' => date('Y-m-d H:i:s'),
            );
            $wpdb->insert($table_name, $data);
        }
    }

    /**
     * Set user notification.
     * 
     * @param  String ( required ) $not_id Notification ID.
     * @return Array $not_data Contais id as key and text as value
     */
    public static function get_not_data($not_id)
    {
        $not_text = 'Something went wrong ! Please try again.';
        $type = 'error';
        switch ($not_id) {
            case 'invalid-nonce':
                $not_text = 'Please do not cheat with the forms';
                $type = 'error';
                break;
            case 'settings-updated':
                $not_text = 'Settings successfully updated';
                $type = 'success';
                break;
            default:
                $not_text = 'Something went wrong ! Please try again.';
                $type = 'error';
                break;
        }

        return array(
            'content' => $not_text,
            'type' => $type,
        );
    }


    public static function get_records($date = null, $limit = 20)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . self::$sessions_table_name;
        $data = array();
        $current_user_id = get_current_user_id();

        $query = "SELECT * FROM " . $table_name;

        // filter results by last session date.
        if (!is_null($date)) {
            $query .= " WHERE last_session LIKE '%" . sanitize_text_field($date) . "%' ";
        }

        $query .= " ORDER BY last_session DESC LIMIT " . sanitize_text_field($limit);
        $data = $wpdb->get_results($query);

        if (empty($data) || !$data) {
            return false;
        }
        return $data;
    }


    public static function get_user_data($user_id)
    {
        $user_data = new stdClass();

        $user_data->basic = get_userdata($user_id);
        $user_data->gravatar_url = get_gravatar_url($user_id, 200, 'default');

        return $user_data;
    }
}