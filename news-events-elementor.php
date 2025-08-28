<?php
/**
 * Plugin Name: News & Events Elementor Widgets
 * Description: Complete news and events management system with Elementor widgets for drag-and-drop functionality
 * Version: 1.0.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) {
    exit;
}

define('NEWS_EVENTS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('NEWS_EVENTS_PLUGIN_PATH', plugin_dir_path(__FILE__));

class NewsEventsElementor {
    
    public function __construct() {
        add_action('init', [$this, 'init']);
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('wp_ajax_save_news_item', [$this, 'save_news_item']);
        add_action('wp_ajax_save_event_item', [$this, 'save_event_item']);
        add_action('wp_ajax_delete_news_item', [$this, 'delete_news_item']);
        add_action('wp_ajax_delete_event_item', [$this, 'delete_event_item']);
        add_action('wp_ajax_get_news_items', [$this, 'get_news_items']);
        add_action('wp_ajax_get_event_items', [$this, 'get_event_items']);
        add_action('wp_ajax_nopriv_get_news_items', [$this, 'get_news_items']);
        add_action('wp_ajax_nopriv_get_event_items', [$this, 'get_event_items']);
    }
    
    public function init() {
        $this->create_tables();
        $this->register_post_types();
    }
    
    private function create_tables() {
        global $wpdb;
        
        $news_table = $wpdb->prefix . 'news_items';
        $events_table = $wpdb->prefix . 'event_items';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $news_sql = "CREATE TABLE $news_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            content text NOT NULL,
            image_url varchar(500),
            source_url varchar(500),
            category varchar(100),
            published_date datetime DEFAULT CURRENT_TIMESTAMP,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        $events_sql = "CREATE TABLE $events_table (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            title varchar(255) NOT NULL,
            description text NOT NULL,
            event_date datetime NOT NULL,
            end_date datetime,
            location varchar(255),
            image_url varchar(500),
            category varchar(100),
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($news_sql);
        dbDelta($events_sql);
    }
    
    private function register_post_types() {
        register_post_type('news_item', [
            'public' => false,
            'show_ui' => false,
        ]);
        
        register_post_type('event_item', [
            'public' => false,
            'show_ui' => false,
        ]);
    }
    
    public function register_widgets() {
        require_once NEWS_EVENTS_PLUGIN_PATH . 'widgets/news-display-widget.php';
        require_once NEWS_EVENTS_PLUGIN_PATH . 'widgets/news-manager-widget.php';
        require_once NEWS_EVENTS_PLUGIN_PATH . 'widgets/events-display-widget.php';
        require_once NEWS_EVENTS_PLUGIN_PATH . 'widgets/events-manager-widget.php';
        
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \NewsDisplayWidget());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \NewsManagerWidget());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \EventsDisplayWidget());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \EventsManagerWidget());
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script('news-events-js', NEWS_EVENTS_PLUGIN_URL . 'assets/news-events.js', ['jquery'], '1.0.0', true);
        wp_enqueue_style('news-events-css', NEWS_EVENTS_PLUGIN_URL . 'assets/news-events.css', [], '1.0.0');
        
        wp_localize_script('news-events-js', 'newsEventsAjax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('news_events_nonce')
        ]);
    }
    
    public function save_news_item() {
        check_ajax_referer('news_events_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'news_items';
        
        $title = sanitize_text_field($_POST['title']);
        $content = wp_kses_post($_POST['content']);
        $image_url = esc_url_raw($_POST['image_url']);
        $source_url = esc_url_raw($_POST['source_url']);
        $category = sanitize_text_field($_POST['category']);
        
        $result = $wpdb->insert($table, [
            'title' => $title,
            'content' => $content,
            'image_url' => $image_url,
            'source_url' => $source_url,
            'category' => $category
        ]);
        
        if ($result) {
            wp_send_json_success('News item saved successfully');
        } else {
            wp_send_json_error('Failed to save news item');
        }
    }
    
    public function save_event_item() {
        check_ajax_referer('news_events_nonce', 'nonce');
        
        if (!current_user_can('edit_posts')) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'event_items';
        
        $title = sanitize_text_field($_POST['title']);
        $description = wp_kses_post($_POST['description']);
        $event_date = sanitize_text_field($_POST['event_date']);
        $end_date = sanitize_text_field($_POST['end_date']);
        $location = sanitize_text_field($_POST['location']);
        $image_url = esc_url_raw($_POST['image_url']);
        $category = sanitize_text_field($_POST['category']);
        
        $result = $wpdb->insert($table, [
            'title' => $title,
            'description' => $description,
            'event_date' => $event_date,
            'end_date' => $end_date,
            'location' => $location,
            'image_url' => $image_url,
            'category' => $category
        ]);
        
        if ($result) {
            wp_send_json_success('Event saved successfully');
        } else {
            wp_send_json_error('Failed to save event');
        }
    }
    
    public function get_news_items() {
        global $wpdb;
        $table = $wpdb->prefix . 'news_items';
        
        $category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        
        $where = '';
        if ($category) {
            $where = $wpdb->prepare(" WHERE category = %s", $category);
        }
        
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table $where ORDER BY published_date DESC LIMIT %d", 
            $limit
        ));
        
        wp_send_json_success($results);
    }
    
    public function get_event_items() {
        global $wpdb;
        $table = $wpdb->prefix . 'event_items';
        
        $category = isset($_GET['category']) ? sanitize_text_field($_GET['category']) : '';
        $upcoming = isset($_GET['upcoming']) ? $_GET['upcoming'] === 'true' : false;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        
        $where = '';
        if ($category) {
            $where .= $wpdb->prepare(" AND category = %s", $category);
        }
        if ($upcoming) {
            $where .= " AND event_date >= NOW()";
        }
        
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE 1=1 $where ORDER BY event_date ASC LIMIT %d", 
            $limit
        ));
        
        wp_send_json_success($results);
    }
    
    public function delete_news_item() {
        check_ajax_referer('news_events_nonce', 'nonce');
        
        if (!current_user_can('delete_posts')) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'news_items';
        $id = intval($_POST['id']);
        
        $result = $wpdb->delete($table, ['id' => $id]);
        
        if ($result) {
            wp_send_json_success('News item deleted');
        } else {
            wp_send_json_error('Failed to delete news item');
        }
    }
    
    public function delete_event_item() {
        check_ajax_referer('news_events_nonce', 'nonce');
        
        if (!current_user_can('delete_posts')) {
            wp_die('Unauthorized');
        }
        
        global $wpdb;
        $table = $wpdb->prefix . 'event_items';
        $id = intval($_POST['id']);
        
        $result = $wpdb->delete($table, ['id' => $id]);
        
        if ($result) {
            wp_send_json_success('Event deleted');
        } else {
            wp_send_json_error('Failed to delete event');
        }
    }
}

new NewsEventsElementor();
