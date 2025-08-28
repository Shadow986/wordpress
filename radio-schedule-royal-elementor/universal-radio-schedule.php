<?php
/**
 * UNIVERSAL RADIO SCHEDULE SYSTEM - WORDPRESS ELEMENTOR VERSION
 * Works with ANY radio station - fully customizable
 * 
 * INSTALLATION:
 * 1. Add this code to your theme's functions.php file
 * 2. Upload the CSS and JS files to your theme
 * 3. Create radio shows in WordPress admin
 * 4. Use shortcode [radio_schedule] anywhere
 */

// ========================================
// RADIO SCHEDULE SETTINGS
// ========================================
class UniversalRadioSchedule {
    
    public function __construct() {
        add_action('init', array($this, 'create_radio_shows_post_type'));
        add_action('add_meta_boxes', array($this, 'add_show_meta_boxes'));
        add_action('save_post', array($this, 'save_show_meta'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_get_radio_shows', array($this, 'get_radio_shows_ajax'));
        add_action('wp_ajax_nopriv_get_radio_shows', array($this, 'get_radio_shows_ajax'));
        add_shortcode('radio_schedule', array($this, 'radio_schedule_shortcode'));
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_filter('manage_radio_shows_posts_columns', array($this, 'custom_columns'));
        add_action('manage_radio_shows_posts_custom_column', array($this, 'custom_column_content'), 10, 2);
    }
    
    // ========================================
    // CREATE RADIO SHOWS POST TYPE
    // ========================================
    public function create_radio_shows_post_type() {
        register_post_type('radio_shows', array(
            'labels' => array(
                'name' => 'Radio Shows',
                'singular_name' => 'Radio Show',
                'menu_name' => 'Radio Shows',
                'add_new' => 'Add New Show',
                'edit_item' => 'Edit Show',
                'all_items' => 'All Shows',
                'view_item' => 'View Show',
                'search_items' => 'Search Shows',
                'not_found' => 'No shows found',
                'not_found_in_trash' => 'No shows found in trash'
            ),
            'public' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'menu_icon' => 'dashicons-microphone',
            'menu_position' => 20,
            'show_in_rest' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'radio-shows'),
            'capability_type' => 'post'
        ));
    }
    
    // ========================================
    // ADD SHOW SCHEDULE FIELDS
    // ========================================
    public function add_show_meta_boxes() {
        add_meta_box(
            'show_schedule_meta',
            'Show Schedule Information',
            array($this, 'show_schedule_callback'),
            'radio_shows',
            'normal',
            'high'
        );
    }
    
    public function show_schedule_callback($post) {
        wp_nonce_field('radio_save_meta', 'radio_meta_nonce');
        
        $days = get_post_meta($post->ID, '_show_days', true);
        $start_time = get_post_meta($post->ID, '_start_time', true);
        $end_time = get_post_meta($post->ID, '_end_time', true);
        $host_name = get_post_meta($post->ID, '_host_name', true);
        $show_type = get_post_meta($post->ID, '_show_type', true);
        $is_live = get_post_meta($post->ID, '_is_live', true);
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="show_days">Show Days</label></th>
                <td>
                    <?php
                    $all_days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                    $selected_days = $days ? explode(',', strtolower($days)) : array();
                    
                    foreach ($all_days as $day) {
                        $checked = in_array($day, $selected_days) ? 'checked' : '';
                        echo '<label style="margin-right: 15px;">';
                        echo '<input type="checkbox" name="show_days[]" value="' . $day . '" ' . $checked . '> ';
                        echo ucfirst($day);
                        echo '</label>';
                    }
                    ?>
                    <p class="description">Select the days this show airs</p>
                </td>
            </tr>
            
            <tr>
                <th><label for="start_time">Start Time</label></th>
                <td>
                    <input type="time" id="start_time" name="start_time" value="<?php echo esc_attr($start_time); ?>" />
                    <p class="description">24-hour format (e.g., 06:00, 14:30)</p>
                </td>
            </tr>
            
            <tr>
                <th><label for="end_time">End Time</label></th>
                <td>
                    <input type="time" id="end_time" name="end_time" value="<?php echo esc_attr($end_time); ?>" />
                    <p class="description">24-hour format (e.g., 10:00, 18:00)</p>
                </td>
            </tr>
            
            <tr>
                <th><label for="host_name">Host/DJ Name</label></th>
                <td>
                    <input type="text" id="host_name" name="host_name" value="<?php echo esc_attr($host_name); ?>" style="width: 100%;" />
                    <p class="description">DJ or host name for this show</p>
                </td>
            </tr>
            
            <tr>
                <th><label for="show_type">Show Type</label></th>
                <td>
                    <select id="show_type" name="show_type">
                        <option value="music" <?php selected($show_type, 'music'); ?>>Music Show</option>
                        <option value="talk" <?php selected($show_type, 'talk'); ?>>Talk Show</option>
                        <option value="news" <?php selected($show_type, 'news'); ?>>News</option>
                        <option value="sports" <?php selected($show_type, 'sports'); ?>>Sports</option>
                        <option value="religious" <?php selected($show_type, 'religious'); ?>>Religious</option>
                        <option value="educational" <?php selected($show_type, 'educational'); ?>>Educational</option>
                        <option value="entertainment" <?php selected($show_type, 'entertainment'); ?>>Entertainment</option>
                    </select>
                    <p class="description">Category of the show</p>
                </td>
            </tr>
            
            <tr>
                <th><label for="is_live">Live Show</label></th>
                <td>
                    <input type="checkbox" id="is_live" name="is_live" value="1" <?php checked($is_live, '1'); ?> />
                    <label for="is_live">This is a live show (not pre-recorded)</label>
                </td>
            </tr>
        </table>
        <?php
    }
    
    // ========================================
    // SAVE SHOW DATA
    // ========================================
    public function save_show_meta($post_id) {
        if (!isset($_POST['radio_meta_nonce']) || !wp_verify_nonce($_POST['radio_meta_nonce'], 'radio_save_meta')) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Save show days
        if (isset($_POST['show_days']) && is_array($_POST['show_days'])) {
            $days = implode(',', array_map('sanitize_text_field', $_POST['show_days']));
            update_post_meta($post_id, '_show_days', $days);
        } else {
            delete_post_meta($post_id, '_show_days');
        }
        
        // Save other fields
        $fields = array('start_time', 'end_time', 'host_name', 'show_type');
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
            }
        }
        
        // Save live checkbox
        if (isset($_POST['is_live'])) {
            update_post_meta($post_id, '_is_live', '1');
        } else {
            delete_post_meta($post_id, '_is_live');
        }
    }
    
    // ========================================
    // AJAX ENDPOINT FOR GETTING SHOWS
    // ========================================
    public function get_radio_shows_ajax() {
        $day = isset($_POST['day']) ? sanitize_text_field($_POST['day']) : strtolower(current_time('l'));
        $current_time = current_time('H:i');
        
        $shows = get_posts(array(
            'post_type' => 'radio_shows',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_show_days',
                    'value' => $day,
                    'compare' => 'LIKE'
                )
            )
        ));
        
        $current_shows = array();
        $upcoming_shows = array();
        $all_shows = array();
        
        foreach ($shows as $show) {
            $show_days = get_post_meta($show->ID, '_show_days', true);
            $start_time = get_post_meta($show->ID, '_start_time', true);
            $end_time = get_post_meta($show->ID, '_end_time', true);
            $host_name = get_post_meta($show->ID, '_host_name', true);
            $show_type = get_post_meta($show->ID, '_show_type', true);
            $is_live = get_post_meta($show->ID, '_is_live', true);
            
            // Get featured image
            $image_url = get_the_post_thumbnail_url($show->ID, 'medium');
            if (!$image_url) {
                $image_url = $this->get_default_image($show_type);
            }
            
            $show_data = array(
                'id' => $show->ID,
                'title' => $show->post_title,
                'host' => $host_name ? $host_name : get_bloginfo('name'),
                'start_time' => $start_time,
                'end_time' => $end_time,
                'image' => $image_url,
                'description' => $show->post_excerpt ? $show->post_excerpt : wp_trim_words($show->post_content, 20, '...'),
                'type' => $show_type,
                'is_live' => $is_live,
                'is_current' => false,
                'formatted_time' => $this->format_time_range($start_time, $end_time)
            );
            
            // Check if show is currently on
            if ($day === strtolower(current_time('l')) && $start_time && $end_time) {
                if ($current_time >= $start_time && $current_time <= $end_time) {
                    $show_data['is_current'] = true;
                    $current_shows[] = $show_data;
                } elseif ($start_time > $current_time) {
                    $upcoming_shows[] = $show_data;
                }
            }
            
            $all_shows[] = $show_data;
        }
        
        // Sort shows by start time
        usort($all_shows, function($a, $b) {
            return strcmp($a['start_time'], $b['start_time']);
        });
        
        usort($upcoming_shows, function($a, $b) {
            return strcmp($a['start_time'], $b['start_time']);
        });
        
        wp_send_json_success(array(
            'current' => $current_shows,
            'upcoming' => array_slice($upcoming_shows, 0, 6),
            'all_shows' => $all_shows,
            'current_time' => $current_time,
            'current_day' => $day,
            'station_name' => get_bloginfo('name')
        ));
    }
    
    // ========================================
    // SHORTCODE
    // ========================================
    public function radio_schedule_shortcode($atts) {
        $atts = shortcode_atts(array(
            'view' => 'current', // current, today, week
            'show_header' => 'true',
            'show_upcoming' => 'true',
            'max_shows' => '6'
        ), $atts);
        
        ob_start();
        ?>
        <div id="radio-schedule" class="radio-schedule-container" data-view="<?php echo esc_attr($atts['view']); ?>" data-max-shows="<?php echo esc_attr($atts['max_shows']); ?>">
            <?php if ($atts['show_header'] === 'true'): ?>
            <div class="schedule-header">
                <h2><?php echo get_bloginfo('name'); ?> Schedule</h2>
                <div class="schedule-controls">
                    <button class="day-btn active" data-day="<?php echo strtolower(current_time('l')); ?>">Today</button>
                    <button class="day-btn" data-day="monday">Mon</button>
                    <button class="day-btn" data-day="tuesday">Tue</button>
                    <button class="day-btn" data-day="wednesday">Wed</button>
                    <button class="day-btn" data-day="thursday">Thu</button>
                    <button class="day-btn" data-day="friday">Fri</button>
                    <button class="day-btn" data-day="saturday">Sat</button>
                    <button class="day-btn" data-day="sunday">Sun</button>
                </div>
                <div class="live-indicator" style="display: none;">
                    🔴 LIVE NOW
                </div>
            </div>
            <?php endif; ?>
            
            <div class="current-shows-section">
                <h3 class="section-title">Now Playing</h3>
                <div class="current-shows-grid" id="current-shows">
                    <div class="loading-state">
                        <div class="loading-spinner"></div>
                        <p>Loading shows...</p>
                    </div>
                </div>
            </div>
            
            <?php if ($atts['show_upcoming'] === 'true'): ?>
            <div class="upcoming-section">
                <h3 class="section-title">Coming Up</h3>
                <div class="upcoming-shows" id="upcoming-shows">
                    <!-- Upcoming shows will be loaded here -->
                </div>
            </div>
            <?php endif; ?>
            
            <div class="all-shows-section" style="display: none;">
                <h3 class="section-title">Full Schedule</h3>
                <div class="all-shows-grid" id="all-shows">
                    <!-- All shows will be loaded here -->
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    // ========================================
    // HELPER FUNCTIONS
    // ========================================
    private function get_default_image($show_type) {
        $default_images = array(
            'music' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100" fill="#ff6b35"/><text x="50" y="55" font-family="Arial" font-size="12" fill="white" text-anchor="middle">MUSIC</text></svg>'),
            'talk' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100" fill="#2196F3"/><text x="50" y="55" font-family="Arial" font-size="12" fill="white" text-anchor="middle">TALK</text></svg>'),
            'news' => 'data:image/svg+xml;base64=' . base64_encode('<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100" fill="#f44336"/><text x="50" y="55" font-family="Arial" font-size="12" fill="white" text-anchor="middle">NEWS</text></svg>'),
            'sports' => 'data:image/svg+xml;base64=' . base64_encode('<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100" fill="#4CAF50"/><text x="50" y="55" font-family="Arial" font-size="12" fill="white" text-anchor="middle">SPORTS</text></svg>'),
            'default' => 'data:image/svg+xml;base64,' . base64_encode('<svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="100" height="100" fill="#9C27B0"/><text x="50" y="55" font-family="Arial" font-size="12" fill="white" text-anchor="middle">RADIO</text></svg>')
        );
        
        return isset($default_images[$show_type]) ? $default_images[$show_type] : $default_images['default'];
    }
    
    private function format_time_range($start, $end) {
        if (!$start || !$end) return '';
        
        $start_formatted = date('g:i A', strtotime($start));
        $end_formatted = date('g:i A', strtotime($end));
        
        return $start_formatted . ' - ' . $end_formatted;
    }
    
    // ========================================
    // ENQUEUE SCRIPTS
    // ========================================
    public function enqueue_scripts() {
        wp_enqueue_script(
            'universal-radio-schedule',
            get_template_directory_uri() . '/js/universal-radio-schedule.js',
            array('jquery'),
            '2.0.0',
            true
        );
        
        wp_enqueue_style(
            'universal-radio-schedule',
            get_template_directory_uri() . '/css/universal-radio-schedule.css',
            array(),
            '2.0.0'
        );
        
        wp_localize_script('universal-radio-schedule', 'radio_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('radio_schedule_nonce')
        ));
    }
    
    // ========================================
    // ADMIN COLUMNS
    // ========================================
    public function custom_columns($columns) {
        $new_columns = array();
        $new_columns['cb'] = $columns['cb'];
        $new_columns['title'] = $columns['title'];
        $new_columns['thumbnail'] = 'Image';
        $new_columns['host'] = 'Host';
        $new_columns['schedule'] = 'Time';
        $new_columns['days'] = 'Days';
        $new_columns['type'] = 'Type';
        $new_columns['live'] = 'Live';
        $new_columns['date'] = $columns['date'];
        return $new_columns;
    }
    
    public function custom_column_content($column, $post_id) {
        switch ($column) {
            case 'thumbnail':
                if (has_post_thumbnail($post_id)) {
                    echo get_the_post_thumbnail($post_id, array(50, 50), array('style' => 'border-radius: 50%;'));
                } else {
                    echo '<div style="width:50px;height:50px;background:#ddd;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;">No Image</div>';
                }
                break;
            case 'host':
                $host = get_post_meta($post_id, '_host_name', true);
                echo $host ? esc_html($host) : '<em>Not set</em>';
                break;
            case 'schedule':
                $start = get_post_meta($post_id, '_start_time', true);
                $end = get_post_meta($post_id, '_end_time', true);
                if ($start && $end) {
                    echo '<strong>' . date('g:i A', strtotime($start)) . ' - ' . date('g:i A', strtotime($end)) . '</strong>';
                } else {
                    echo '<em>Not set</em>';
                }
                break;
            case 'days':
                $days = get_post_meta($post_id, '_show_days', true);
                if ($days) {
                    $day_array = explode(',', $days);
                    $formatted_days = array_map('ucfirst', $day_array);
                    echo implode(', ', $formatted_days);
                } else {
                    echo '<em>Not set</em>';
                }
                break;
            case 'type':
                $type = get_post_meta($post_id, '_show_type', true);
                echo $type ? '<span class="show-type-' . esc_attr($type) . '">' . ucfirst($type) . '</span>' : '<em>Not set</em>';
                break;
            case 'live':
                $is_live = get_post_meta($post_id, '_is_live', true);
                echo $is_live ? '<span style="color: #f00;">● Live</span>' : '<span style="color: #999;">○ Recorded</span>';
                break;
        }
    }
    
    // ========================================
    // ADMIN MENU
    // ========================================
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=radio_shows',
            'Schedule Settings',
            'Settings',
            'manage_options',
            'radio-schedule-settings',
            array($this, 'admin_settings_page')
        );
    }
    
    public function admin_settings_page() {
        ?>
        <div class="wrap">
            <h1>Radio Schedule Settings</h1>
            <div class="card">
                <h2>Usage Instructions</h2>
                <p><strong>Shortcode:</strong> Use <code>[radio_schedule]</code> to display the schedule anywhere.</p>
                <p><strong>Parameters:</strong></p>
                <ul>
                    <li><code>view="current"</code> - Show current/upcoming shows (default)</li>
                    <li><code>view="today"</code> - Show today's full schedule</li>
                    <li><code>view="week"</code> - Show weekly view</li>
                    <li><code>show_header="false"</code> - Hide the header</li>
                    <li><code>show_upcoming="false"</code> - Hide upcoming shows</li>
                    <li><code>max_shows="10"</code> - Maximum shows to display</li>
                </ul>
                <p><strong>Example:</strong> <code>[radio_schedule view="today" max_shows="8"]</code></p>
            </div>
            
            <div class="card">
                <h2>File Requirements</h2>
                <p>Make sure these files are in your theme:</p>
                <ul>
                    <li><code>/js/universal-radio-schedule.js</code></li>
                    <li><code>/css/universal-radio-schedule.css</code></li>
                </ul>
            </div>
        </div>
        <?php
    }
}

// Initialize the plugin
new UniversalRadioSchedule();

/**
 * INSTALLATION COMPLETE!
 * 
 * NEXT STEPS:
 * 1. Upload universal-radio-schedule.js to your theme's js/ folder
 * 2. Upload universal-radio-schedule.css to your theme's css/ folder
 * 3. Create radio shows in WordPress admin
 * 4. Use [radio_schedule] shortcode in Elementor or anywhere
 * 5. Customize the CSS to match your brand colors
 */
?>
