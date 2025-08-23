<?php
/**
 * Radio Schedule Display Widget
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Radio_Schedule_Display_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'radio_schedule_display';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return esc_html__('Radio Schedule Display', 'radio-schedule-elementor');
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
        return ['radio', 'schedule', 'shows', 'broadcast'];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {

        // Content Section
        $this->start_controls_section(
            'content_section',
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
        $max_shows = $settings['max_shows'] ?: 6;

        // Get gradient
        $gradient = $this->get_gradient($settings['background_gradient'], $settings['custom_gradient']);
        $border_radius = $settings['border_radius']['size'] . $settings['border_radius']['unit'];

        ?>
        <div class="radio-schedule-container" 
             style="background: <?php echo esc_attr($gradient); ?>; border-radius: <?php echo esc_attr($border_radius); ?>;"
             data-max-shows="<?php echo esc_attr($max_shows); ?>">
            
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
        </div>
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
}
?>
