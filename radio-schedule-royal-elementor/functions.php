<?php
/**
 * HIGHWAY RADIO SCHEDULE SYSTEM - ROYAL ELEMENTOR VERSION
 * Add this code to your theme's functions.php file
 * 
 * INSTRUCTIONS:
 * 1. WordPress Admin → Appearance → Theme Editor
 * 2. Select functions.php
 * 3. Scroll to the BOTTOM of the file
 * 4. Copy and paste ALL the code below
 * 5. Click "Update File"
 */

// ========================================
// CREATE RADIO SHOWS POST TYPE
// ========================================
function highway_radio_create_shows_post_type() {
    register_post_type('radio_shows', array(
        'labels' => array(
            'name' => 'Radio Shows',
            'singular_name' => 'Radio Show',
            'menu_name' => 'Radio Shows',
            'add_new' => 'Add New Show',
            'edit_item' => 'Edit Show',
            'all_items' => 'All Shows'
        ),
        'public' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-microphone',
        'menu_position' => 20,
        'show_in_rest' => true,
        'has_archive' => true
    ));
}
add_action('init', 'highway_radio_create_shows_post_type');

// ========================================
// ADD SHOW SCHEDULE FIELDS
// ========================================
function highway_radio_add_show_meta_boxes() {
    add_meta_box(
        'show_schedule_meta',
        'Show Schedule Information',
        'highway_radio_show_schedule_callback',
        'radio_shows',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'highway_radio_add_show_meta_boxes');

function highway_radio_show_schedule_callback($post) {
    wp_nonce_field('highway_radio_save_meta', 'highway_radio_meta_nonce');
    
    $days = get_post_meta($post->ID, '_show_days', true);
    $start_time = get_post_meta($post->ID, '_start_time', true);
    $end_time = get_post_meta($post->ID, '_end_time', true);
    $host_name = get_post_meta($post->ID, '_host_name', true);
    
    echo '<table class="form-table">';
    
    echo '<tr>';
    echo '<th><label for="show_days">Show Days</label></th>';
    echo '<td>';
    echo '<input type="text" id="show_days" name="show_days" value="' . esc_attr($days) . '" style="width: 100%;" />';
    echo '<p class="description">Enter days separated by commas: <strong>monday,tuesday,wednesday,thursday,friday</strong></p>';
    echo '</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th><label for="start_time">Start Time</label></th>';
    echo '<td>';
    echo '<input type="time" id="start_time" name="start_time" value="' . esc_attr($start_time) . '" />';
    echo '<p class="description">24-hour format (e.g., 06:00, 14:30)</p>';
    echo '</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th><label for="end_time">End Time</label></th>';
    echo '<td>';
    echo '<input type="time" id="end_time" name="end_time" value="' . esc_attr($end_time) . '" />';
    echo '<p class="description">24-hour format (e.g., 10:00, 18:00)</p>';
    echo '</td>';
    echo '</tr>';
    
    echo '<tr>';
    echo '<th><label for="host_name">Host Name</label></th>';
    echo '<td>';
    echo '<input type="text" id="host_name" name="host_name" value="' . esc_attr($host_name) . '" style="width: 100%;" />';
    echo '<p class="description">DJ or host name for this show</p>';
    echo '</td>';
    echo '</tr>';
    
    echo '</table>';
}

// ========================================
// SAVE SHOW DATA
// ========================================
function highway_radio_save_show_meta($post_id) {
    if (!isset($_POST['highway_radio_meta_nonce']) || !wp_verify_nonce($_POST['highway_radio_meta_nonce'], 'highway_radio_save_meta')) {
        return;
    }
    
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    if (isset($_POST['show_days'])) {
        update_post_meta($post_id, '_show_days', sanitize_text_field($_POST['show_days']));
    }
    
    if (isset($_POST['start_time'])) {
        update_post_meta($post_id, '_start_time', sanitize_text_field($_POST['start_time']));
    }
    
    if (isset($_POST['end_time'])) {
        update_post_meta($post_id, '_end_time', sanitize_text_field($_POST['end_time']));
    }
    
    if (isset($_POST['host_name'])) {
        update_post_meta($post_id, '_host_name', sanitize_text_field($_POST['host_name']));
    }
}
add_action('save_post', 'highway_radio_save_show_meta');

// ========================================
// AJAX ENDPOINT FOR GETTING SHOWS
// ========================================
function highway_radio_get_current_shows() {
    $current_time = current_time('H:i');
    $current_day = strtolower(current_time('l'));
    
    $shows = get_posts(array(
        'post_type' => 'radio_shows',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ));
    
    $current_shows = array();
    $upcoming_shows = array();
    
    foreach ($shows as $show) {
        $show_days = get_post_meta($show->ID, '_show_days', true);
        $start_time = get_post_meta($show->ID, '_start_time', true);
        $end_time = get_post_meta($show->ID, '_end_time', true);
        $host_name = get_post_meta($show->ID, '_host_name', true);
        
        if (empty($show_days) || strpos(strtolower($show_days), $current_day) === false) {
            continue;
        }
        
        $image_url = get_the_post_thumbnail_url($show->ID, 'medium');
        if (!$image_url) {
            $image_url = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA4MCA4MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjMzMzIi8+Cjx0ZXh0IHg9IjQwIiB5PSI0NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjEyIiBmaWxsPSIjZmZmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5SQURJTzwvdGV4dD4KPC9zdmc+';
        }
        
        $show_data = array(
            'id' => $show->ID,
            'title' => $show->post_title,
            'host' => $host_name ? $host_name : 'Highway Radio',
            'start_time' => $start_time,
            'end_time' => $end_time,
            'image' => $image_url,
            'description' => wp_trim_words($show->post_content, 15, '...')
        );
        
        if ($start_time && $end_time && $current_time >= $start_time && $current_time <= $end_time) {
            $current_shows[] = $show_data;
        } elseif ($start_time && $start_time > $current_time) {
            $upcoming_shows[] = $show_data;
        }
    }
    
    usort($upcoming_shows, function($a, $b) {
        return strcmp($a['start_time'], $b['start_time']);
    });
    
    wp_send_json_success(array(
        'current' => array_slice($current_shows, 0, 3),
        'upcoming' => array_slice($upcoming_shows, 0, 6),
        'current_time' => $current_time,
        'current_day' => $current_day
    ));
}

add_action('wp_ajax_highway_radio_get_shows', 'highway_radio_get_current_shows');
add_action('wp_ajax_nopriv_highway_radio_get_shows', 'highway_radio_get_current_shows');

// ========================================
// ENQUEUE SCRIPTS
// ========================================
function highway_radio_enqueue_scripts() {
    if (is_page() || is_front_page() || is_home()) {
        wp_enqueue_script(
            'highway-radio-schedule',
            get_template_directory_uri() . '/js/radio-schedule.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        wp_localize_script('highway-radio-schedule', 'highway_radio_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('highway_radio_nonce')
        ));
    }
}
add_action('wp_enqueue_scripts', 'highway_radio_enqueue_scripts');

// ========================================
// ADMIN IMPROVEMENTS
// ========================================
function highway_radio_admin_notices() {
    $screen = get_current_screen();
    if ($screen->post_type === 'radio_shows') {
        echo '<div class="notice notice-info">';
        echo '<p><strong>Highway Radio:</strong> Use lowercase days (monday,tuesday,friday) and 24-hour time format (06:00, 14:30)</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'highway_radio_admin_notices');

function highway_radio_custom_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['host'] = 'Host';
    $new_columns['schedule'] = 'Time';
    $new_columns['days'] = 'Days';
    $new_columns['date'] = $columns['date'];
    return $new_columns;
}
add_filter('manage_radio_shows_posts_columns', 'highway_radio_custom_columns');

function highway_radio_custom_column_content($column, $post_id) {
    switch ($column) {
        case 'host':
            $host = get_post_meta($post_id, '_host_name', true);
            echo $host ? esc_html($host) : 'Not set';
            break;
        case 'schedule':
            $start = get_post_meta($post_id, '_start_time', true);
            $end = get_post_meta($post_id, '_end_time', true);
            echo ($start && $end) ? esc_html($start . ' - ' . $end) : 'Not set';
            break;
        case 'days':
            $days = get_post_meta($post_id, '_show_days', true);
            echo $days ? esc_html($days) : 'Not set';
            break;
    }
}
add_action('manage_radio_shows_posts_custom_column', 'highway_radio_custom_column_content', 10, 2);

/**
 * INSTALLATION COMPLETE!
 * 
 * NEXT STEPS:
 * 1. Upload radio-schedule.js to your theme's js/ folder
 * 2. Add Royal Elementor Custom CSS widget with selector
 * 3. Add HTML widget with schedule code
 * 4. Create your first radio show!
 */
?>
