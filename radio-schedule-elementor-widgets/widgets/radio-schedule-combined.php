<?php
/**
 * Combined Radio Schedule Widget
 * Display + Management in one widget
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Radio_Schedule_Combined_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'radio_schedule_combined';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return esc_html__('Radio Schedule', 'radio-schedule-elementor');
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-calendar';
    }

    /**
     * Get widget categories.
     */
    public function get_categories() {
        return ['general'];
    }

    /**
     * Get widget keywords.
     */
    public function get_keywords() {
        return ['radio', 'schedule', 'shows', 'broadcast', 'manager'];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {

        // Schedule Settings Section
        $this->start_controls_section(
            'schedule_section',
            [
                'label' => esc_html__('Schedule Settings', 'radio-schedule-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'station_name',
            [
                'label' => esc_html__('Station Name', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => get_bloginfo('name'),
                'placeholder' => esc_html__('Your Radio Station', 'radio-schedule-elementor'),
            ]
        );

        $this->add_control(
            'show_header',
            [
                'label' => esc_html__('Show Header', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'radio-schedule-elementor'),
                'label_off' => esc_html__('Hide', 'radio-schedule-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_day_navigation',
            [
                'label' => esc_html__('Show Day Navigation', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'radio-schedule-elementor'),
                'label_off' => esc_html__('Hide', 'radio-schedule-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'show_header' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_upcoming',
            [
                'label' => esc_html__('Show Upcoming Shows', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'radio-schedule-elementor'),
                'label_off' => esc_html__('Hide', 'radio-schedule-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'max_shows',
            [
                'label' => esc_html__('Max Upcoming Shows', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 6,
                'condition' => [
                    'show_upcoming' => 'yes',
                ],
            ]
        );

        // Show Management Controls
        $this->add_control(
            'management_divider',
            [
                'type' => \Elementor\Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'show_management',
            [
                'label' => esc_html__('Show Management Panel', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'radio-schedule-elementor'),
                'label_off' => esc_html__('Hide', 'radio-schedule-elementor'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => esc_html__('Enable this to add/edit shows directly in Elementor', 'radio-schedule-elementor'),
            ]
        );

        $this->add_control(
            'management_access',
            [
                'label' => esc_html__('Management Access Level', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'editor',
                'options' => [
                    'administrator' => esc_html__('Administrator Only', 'radio-schedule-elementor'),
                    'editor' => esc_html__('Editor and Above', 'radio-schedule-elementor'),
                    'author' => esc_html__('Author and Above', 'radio-schedule-elementor'),
                    'contributor' => esc_html__('Contributor and Above', 'radio-schedule-elementor'),
                ],
                'condition' => [
                    'show_management' => 'yes',
                ],
            ]
        );

        // Radio Shows Repeater
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            'show_title',
            [
                'label' => esc_html__('Show Title', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('Morning Show', 'radio-schedule-elementor'),
                'placeholder' => esc_html__('Enter show title', 'radio-schedule-elementor'),
            ]
        );

        $repeater->add_control(
            'show_host',
            [
                'label' => esc_html__('Host/DJ Name', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_html__('DJ Mike', 'radio-schedule-elementor'),
                'placeholder' => esc_html__('Enter host name', 'radio-schedule-elementor'),
            ]
        );

        $repeater->add_control(
            'show_image',
            [
                'label' => esc_html__('Show Image', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
            ]
        );

        $repeater->add_control(
            'start_time',
            [
                'label' => esc_html__('Start Time', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '06:00',
                'placeholder' => '06:00',
                'description' => esc_html__('24-hour format (e.g., 06:00, 14:30)', 'radio-schedule-elementor'),
            ]
        );

        $repeater->add_control(
            'end_time',
            [
                'label' => esc_html__('End Time', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '10:00',
                'placeholder' => '10:00',
                'description' => esc_html__('24-hour format (e.g., 10:00, 18:00)', 'radio-schedule-elementor'),
            ]
        );

        $repeater->add_control(
            'show_days',
            [
                'label' => esc_html__('Show Days', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'monday' => esc_html__('Monday', 'radio-schedule-elementor'),
                    'tuesday' => esc_html__('Tuesday', 'radio-schedule-elementor'),
                    'wednesday' => esc_html__('Wednesday', 'radio-schedule-elementor'),
                    'thursday' => esc_html__('Thursday', 'radio-schedule-elementor'),
                    'friday' => esc_html__('Friday', 'radio-schedule-elementor'),
                    'saturday' => esc_html__('Saturday', 'radio-schedule-elementor'),
                    'sunday' => esc_html__('Sunday', 'radio-schedule-elementor'),
                ],
                'default' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            ]
        );

        $repeater->add_control(
            'show_description',
            [
                'label' => esc_html__('Show Description', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => esc_html__('Start your day with the best music and local news.', 'radio-schedule-elementor'),
                'placeholder' => esc_html__('Enter show description', 'radio-schedule-elementor'),
            ]
        );

        $repeater->add_control(
            'is_live',
            [
                'label' => esc_html__('Live Show', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Live', 'radio-schedule-elementor'),
                'label_off' => esc_html__('Recorded', 'radio-schedule-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'radio_shows',
            [
                'label' => esc_html__('Radio Shows', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'show_title' => esc_html__('Morning Drive Show', 'radio-schedule-elementor'),
                        'show_host' => esc_html__('DJ Sarah', 'radio-schedule-elementor'),
                        'start_time' => '06:00',
                        'end_time' => '10:00',
                        'show_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                        'show_description' => esc_html__('Start your day with the best music and local news.', 'radio-schedule-elementor'),
                        'is_live' => 'yes',
                    ],
                    [
                        'show_title' => esc_html__('Lunch Hour Hits', 'radio-schedule-elementor'),
                        'show_host' => esc_html__('DJ Mike', 'radio-schedule-elementor'),
                        'start_time' => '12:00',
                        'end_time' => '13:00',
                        'show_days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                        'show_description' => esc_html__('The best hits to get you through your lunch break.', 'radio-schedule-elementor'),
                        'is_live' => 'yes',
                    ],
                ],
                'title_field' => '{{{ show_title }}} - {{{ start_time }}} to {{{ end_time }}}',
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'radio-schedule-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'background_gradient',
            [
                'label' => esc_html__('Background Gradient', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'blue_purple',
                'options' => [
                    'blue_purple' => esc_html__('Blue to Purple', 'radio-schedule-elementor'),
                    'orange_red' => esc_html__('Orange to Red', 'radio-schedule-elementor'),
                    'green_blue' => esc_html__('Green to Blue', 'radio-schedule-elementor'),
                    'purple_pink' => esc_html__('Purple to Pink', 'radio-schedule-elementor'),
                    'custom' => esc_html__('Custom', 'radio-schedule-elementor'),
                ],
            ]
        );

        $this->add_control(
            'custom_gradient',
            [
                'label' => esc_html__('Custom Gradient', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'placeholder' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                'condition' => [
                    'background_gradient' => 'custom',
                ],
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => esc_html__('Border Radius', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 25,
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $station_name = $settings['station_name'] ?: get_bloginfo('name');
        $show_header = $settings['show_header'] === 'yes';
        $show_day_navigation = $settings['show_day_navigation'] === 'yes';
        $show_upcoming = $settings['show_upcoming'] === 'yes';
        $show_management = $settings['show_management'] === 'yes';
        $max_shows = $settings['max_shows'] ?: 6;

        // Check management permissions
        $can_manage = $this->check_user_permission($settings['management_access']);

        // Get gradient
        $gradient = $this->get_gradient($settings['background_gradient'], $settings['custom_gradient']);
        $border_radius = $settings['border_radius']['size'] . $settings['border_radius']['unit'];

        // Prepare shows data for JavaScript
        $shows_data = [];
        if (!empty($settings['radio_shows'])) {
            foreach ($settings['radio_shows'] as $show) {
                $shows_data[] = [
                    'title' => $show['show_title'],
                    'host' => $show['show_host'],
                    'start_time' => $show['start_time'],
                    'end_time' => $show['end_time'],
                    'days' => is_array($show['show_days']) ? implode(',', $show['show_days']) : $show['show_days'],
                    'description' => $show['show_description'],
                    'image' => $show['show_image']['url'] ?? '',
                    'is_live' => $show['is_live'] === 'yes',
                    'formatted_time' => $this->format_time_range($show['start_time'], $show['end_time'])
                ];
            }
        }

        ?>
        <div class="radio-schedule-container" 
             style="background: <?php echo esc_attr($gradient); ?>; border-radius: <?php echo esc_attr($border_radius); ?>;"
             data-max-shows="<?php echo esc_attr($max_shows); ?>"
             data-shows='<?php echo esc_attr(json_encode($shows_data)); ?>'>
            
            <?php if ($show_header): ?>
            <div class="schedule-header">
                <h2><?php echo esc_html($station_name); ?> Schedule</h2>
                
                <?php if ($show_day_navigation): ?>
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
                <?php endif; ?>
                
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
            
            <?php if ($show_upcoming): ?>
            <div class="upcoming-section">
                <h3 class="section-title">Coming Up</h3>
                <div class="upcoming-shows" id="upcoming-shows">
                    <!-- Upcoming shows will be loaded here -->
                </div>
            </div>
            <?php endif; ?>

            <?php if ($show_management && $can_manage): ?>
            <div class="management-panel">
                <h3 class="section-title">Manage Shows</h3>
                <div class="management-notice">
                    <p>💡 <strong>Tip:</strong> To add or edit shows, use the Elementor editor panel on the left. Look for the "Radio Shows" section under "Schedule Settings".</p>
                    <p>📝 You can add multiple shows, set their times, select days, upload images, and more - all directly in Elementor!</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <style>
        .management-panel {
            margin-top: 40px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            border: 2px dashed rgba(255, 255, 255, 0.3);
            text-align: center;
        }
        
        .management-notice {
            color: rgba(255, 255, 255, 0.9);
        }
        
        .management-notice p {
            margin: 15px 0;
            font-size: 1.1em;
            line-height: 1.6;
        }
        
        .management-notice strong {
            color: #ff6b35;
        }
        </style>
        <?php
    }

    /**
     * Get gradient based on selection
     */
    private function get_gradient($type, $custom = '') {
        $gradients = [
            'blue_purple' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
            'orange_red' => 'linear-gradient(135deg, #ff6b35 0%, #f7931e 100%)',
            'green_blue' => 'linear-gradient(135deg, #4CAF50 0%, #2196F3 100%)',
            'purple_pink' => 'linear-gradient(135deg, #9C27B0 0%, #E91E63 100%)',
            'custom' => $custom ?: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'
        ];

        return $gradients[$type] ?? $gradients['blue_purple'];
    }

    /**
     * Format time range
     */
    private function format_time_range($start, $end) {
        if (!$start || !$end) return '';
        
        $start_formatted = date('g:i A', strtotime($start));
        $end_formatted = date('g:i A', strtotime($end));
        
        return $start_formatted . ' - ' . $end_formatted;
    }

    /**
     * Check user permission
     */
    private function check_user_permission($level) {
        switch ($level) {
            case 'administrator':
                return current_user_can('manage_options');
            case 'editor':
                return current_user_can('edit_pages');
            case 'author':
                return current_user_can('publish_posts');
            case 'contributor':
                return current_user_can('edit_posts');
            default:
                return current_user_can('edit_posts');
        }
    }
}
?>
