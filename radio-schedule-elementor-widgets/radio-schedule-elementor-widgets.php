<?php
/**
 * Plugin Name: Radio Schedule Elementor Widgets
 * Plugin URI: https://github.com/Shadow986/wordpress
 * Description: Custom Elementor widgets for radio schedule management. Add shows, manage content, and display schedules directly in Elementor.
 * Version: 1.0.0
 * Author: Your Radio Station
 * License: GPL v2 or later
 * Text Domain: radio-schedule-elementor
 * Elementor tested up to: 3.18.0
 * Elementor Pro tested up to: 3.18.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

define('RADIO_SCHEDULE_ELEMENTOR_VERSION', '1.0.0');
define('RADIO_SCHEDULE_ELEMENTOR_MINIMUM_ELEMENTOR_VERSION', '3.0.0');
define('RADIO_SCHEDULE_ELEMENTOR_MINIMUM_PHP_VERSION', '7.0');

/**
 * Main Radio Schedule Elementor Class
 */
final class Radio_Schedule_Elementor {

    /**
     * Constructor
     */
    public function __construct() {
        // Initialize the plugin
        add_action('plugins_loaded', [$this, 'init']);
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        // Check if Elementor installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_main_plugin']);
            return;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, RADIO_SCHEDULE_ELEMENTOR_MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, RADIO_SCHEDULE_ELEMENTOR_MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        // Add Plugin actions
        add_action('elementor/widgets/widgets_registered', [$this, 'init_widgets']);
        add_action('elementor/controls/controls_registered', [$this, 'init_controls']);

        // Register Widget Styles
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'widget_styles']);
        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);

        // Create radio shows post type
        add_action('init', [$this, 'create_radio_shows_post_type']);
        
        // AJAX handlers
        add_action('wp_ajax_get_radio_shows', [$this, 'get_radio_shows_ajax']);
        add_action('wp_ajax_nopriv_get_radio_shows', [$this, 'get_radio_shows_ajax']);
        add_action('wp_ajax_save_radio_show', [$this, 'save_radio_show_ajax']);
        add_action('wp_ajax_delete_radio_show', [$this, 'delete_radio_show_ajax']);
    }

    /**
     * Admin notice
     * Warning when the site doesn't have Elementor installed or activated.
     */
    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'radio-schedule-elementor'),
            '<strong>' . esc_html__('Radio Schedule Elementor Widgets', 'radio-schedule-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'radio-schedule-elementor') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     * Warning when the site doesn't have a minimum required Elementor version.
     */
    public function admin_notice_minimum_elementor_version() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'radio-schedule-elementor'),
            '<strong>' . esc_html__('Radio Schedule Elementor Widgets', 'radio-schedule-elementor') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'radio-schedule-elementor') . '</strong>',
            RADIO_SCHEDULE_ELEMENTOR_MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice
     * Warning when the site doesn't have a minimum required PHP version.
     */
    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'radio-schedule-elementor'),
            '<strong>' . esc_html__('Radio Schedule Elementor Widgets', 'radio-schedule-elementor') . '</strong>',
            '<strong>' . esc_html__('PHP', 'radio-schedule-elementor') . '</strong>',
            RADIO_SCHEDULE_ELEMENTOR_MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Init Widgets
     */
    public function init_widgets() {
        // Include Widget files
        require_once(__DIR__ . '/widgets/radio-schedule-display.php');
        require_once(__DIR__ . '/widgets/radio-show-manager.php');

        // Register widget
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Radio_Schedule_Display_Widget());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Radio_Show_Manager_Widget());
    }

    /**
     * Init Controls
     */
    public function init_controls() {
        // Here we can add custom controls if needed
    }

    /**
     * Widget Styles
     */
    public function widget_styles() {
        wp_register_style('radio-schedule-elementor', plugins_url('assets/style.css', __FILE__), [], RADIO_SCHEDULE_ELEMENTOR_VERSION);
        wp_enqueue_style('radio-schedule-elementor');
    }

    /**
     * Widget Scripts
     */
    public function widget_scripts() {
        wp_register_script('radio-schedule-elementor', plugins_url('assets/script.js', __FILE__), ['jquery'], RADIO_SCHEDULE_ELEMENTOR_VERSION, true);
        wp_enqueue_script('radio-schedule-elementor');
        
        wp_localize_script('radio-schedule-elementor', 'radio_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('radio_schedule_nonce')
        ]);
    }

    /**
     * Create Radio Shows Post Type
     */
    public function create_radio_shows_post_type() {
        register_post_type('radio_shows', [
            'labels' => [
                'name' => 'Radio Shows',
                'singular_name' => 'Radio Show',
            ],
            'public' => false,
            'show_ui' => false,
            'supports' => ['title', 'editor', 'thumbnail'],
            'capability_type' => 'post',
        ]);
    }

    /**
     * AJAX: Get Radio Shows
     */
    public function get_radio_shows_ajax() {
        check_ajax_referer('radio_schedule_nonce', 'nonce');

        $day = isset($_POST['day']) ? sanitize_text_field($_POST['day']) : strtolower(current_time('l'));
        $current_time = current_time('H:i');

        $shows = get_posts([
            'post_type' => 'radio_shows',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => '_show_days',
                    'value' => $day,
                    'compare' => 'LIKE'
                ]
            ]
        ]);

        $current_shows = [];
        $upcoming_shows = [];

        foreach ($shows as $show) {
            $show_days = get_post_meta($show->ID, '_show_days', true);
            $start_time = get_post_meta($show->ID, '_start_time', true);
            $end_time = get_post_meta($show->ID, '_end_time', true);
            $host_name = get_post_meta($show->ID, '_host_name', true);

            if (!$show_days || strpos(strtolower($show_days), $day) === false) {
                continue;
            }

            $image_url = get_the_post_thumbnail_url($show->ID, 'medium');
            if (!$image_url) {
                $image_url = $this->get_default_image();
            }

            $show_data = [
                'id' => $show->ID,
                'title' => $show->post_title,
                'host' => $host_name ?: get_bloginfo('name'),
                'start_time' => $start_time,
                'end_time' => $end_time,
                'image' => $image_url,
                'description' => wp_trim_words($show->post_content, 20, '...'),
                'formatted_time' => $this->format_time_range($start_time, $end_time)
            ];

            if ($day === strtolower(current_time('l')) && $start_time && $end_time) {
                if ($current_time >= $start_time && $current_time <= $end_time) {
                    $current_shows[] = $show_data;
                } elseif ($start_time > $current_time) {
                    $upcoming_shows[] = $show_data;
                }
            }
        }

        wp_send_json_success([
            'current' => $current_shows,
            'upcoming' => array_slice($upcoming_shows, 0, 6),
            'station_name' => get_bloginfo('name')
        ]);
    }

    /**
     * AJAX: Save Radio Show
     */
    public function save_radio_show_ajax() {
        check_ajax_referer('radio_schedule_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Permission denied');
        }

        $show_data = [
            'post_title' => sanitize_text_field($_POST['title']),
            'post_content' => sanitize_textarea_field($_POST['description']),
            'post_status' => 'publish',
            'post_type' => 'radio_shows'
        ];

        if (isset($_POST['show_id']) && $_POST['show_id']) {
            $show_data['ID'] = intval($_POST['show_id']);
            $show_id = wp_update_post($show_data);
        } else {
            $show_id = wp_insert_post($show_data);
        }

        if ($show_id) {
            // Save meta data
            update_post_meta($show_id, '_show_days', sanitize_text_field($_POST['days']));
            update_post_meta($show_id, '_start_time', sanitize_text_field($_POST['start_time']));
            update_post_meta($show_id, '_end_time', sanitize_text_field($_POST['end_time']));
            update_post_meta($show_id, '_host_name', sanitize_text_field($_POST['host']));

            // Handle image upload
            if (isset($_POST['image_url']) && $_POST['image_url']) {
                $attachment_id = $this->handle_image_upload($_POST['image_url']);
                if ($attachment_id) {
                    set_post_thumbnail($show_id, $attachment_id);
                }
            }

            wp_send_json_success(['show_id' => $show_id, 'message' => 'Show saved successfully']);
        } else {
            wp_send_json_error('Failed to save show');
        }
    }

    /**
     * AJAX: Delete Radio Show
     */
    public function delete_radio_show_ajax() {
        check_ajax_referer('radio_schedule_nonce', 'nonce');

        if (!current_user_can('delete_posts')) {
            wp_send_json_error('Permission denied');
        }

        $show_id = intval($_POST['show_id']);
        if (wp_delete_post($show_id, true)) {
            wp_send_json_success('Show deleted successfully');
        } else {
            wp_send_json_error('Failed to delete show');
        }
    }

    /**
     * Handle Image Upload
     */
    private function handle_image_upload($image_url) {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $attachment_id = media_sideload_image($image_url, 0, null, 'id');
        return is_wp_error($attachment_id) ? false : $attachment_id;
    }

    /**
     * Get Default Image
     */
    private function get_default_image() {
        return 'data:image/svg+xml;base64,' . base64_encode('<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100" fill="#ff6b35"/><text x="50" y="55" font-family="Arial" font-size="12" fill="white" text-anchor="middle">RADIO</text></svg>');
    }

    /**
     * Format Time Range
     */
    private function format_time_range($start, $end) {
        if (!$start || !$end) return '';
        return date('g:i A', strtotime($start)) . ' - ' . date('g:i A', strtotime($end));
    }
}

// Initialize the plugin
new Radio_Schedule_Elementor();
?>
