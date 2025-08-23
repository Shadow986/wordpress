<?php
/**
 * Radio Show Manager Widget
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Radio_Show_Manager_Widget extends \Elementor\Widget_Base {

    /**
     * Get widget name.
     */
    public function get_name() {
        return 'radio_show_manager';
    }

    /**
     * Get widget title.
     */
    public function get_title() {
        return esc_html__('Radio Show Manager', 'radio-schedule-elementor');
    }

    /**
     * Get widget icon.
     */
    public function get_icon() {
        return 'eicon-edit';
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
        return ['radio', 'show', 'manager', 'admin', 'edit'];
    }

    /**
     * Register widget controls.
     */
    protected function register_controls() {

        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Manager Settings', 'radio-schedule-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'manager_title',
            [
                'label' => esc_html__('Manager Title', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Manage Radio Shows',
                'placeholder' => esc_html__('Manager Title', 'radio-schedule-elementor'),
            ]
        );

        $this->add_control(
            'show_add_form',
            [
                'label' => esc_html__('Show Add New Form', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'radio-schedule-elementor'),
                'label_off' => esc_html__('Hide', 'radio-schedule-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_existing_shows',
            [
                'label' => esc_html__('Show Existing Shows', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Show', 'radio-schedule-elementor'),
                'label_off' => esc_html__('Hide', 'radio-schedule-elementor'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'access_level',
            [
                'label' => esc_html__('Access Level', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'editor',
                'options' => [
                    'administrator' => esc_html__('Administrator Only', 'radio-schedule-elementor'),
                    'editor' => esc_html__('Editor and Above', 'radio-schedule-elementor'),
                    'author' => esc_html__('Author and Above', 'radio-schedule-elementor'),
                    'contributor' => esc_html__('Contributor and Above', 'radio-schedule-elementor'),
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
            'form_background',
            [
                'label' => esc_html__('Form Background', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );

        $this->add_control(
            'button_color',
            [
                'label' => esc_html__('Button Color', 'radio-schedule-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ff6b35',
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Render widget output on the frontend.
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // Check user permissions
        if (!$this->check_user_permission($settings['access_level'])) {
            echo '<p>You do not have permission to manage radio shows.</p>';
            return;
        }

        $manager_title = $settings['manager_title'] ?: 'Manage Radio Shows';
        $show_add_form = $settings['show_add_form'] === 'yes';
        $show_existing_shows = $settings['show_existing_shows'] === 'yes';
        $form_bg = $settings['form_background'] ?: '#ffffff';
        $button_color = $settings['button_color'] ?: '#ff6b35';

        ?>
        <div class="radio-show-manager" style="--form-bg: <?php echo esc_attr($form_bg); ?>; --button-color: <?php echo esc_attr($button_color); ?>;">
            <h2><?php echo esc_html($manager_title); ?></h2>
            
            <?php if ($show_add_form): ?>
            <div class="add-show-form">
                <h3>Add New Radio Show</h3>
                <form id="radio-show-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="show-title">Show Title *</label>
                            <input type="text" id="show-title" name="title" required>
                        </div>
                        <div class="form-group">
                            <label for="show-host">Host/DJ Name</label>
                            <input type="text" id="show-host" name="host">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="start-time">Start Time *</label>
                            <input type="time" id="start-time" name="start_time" required>
                        </div>
                        <div class="form-group">
                            <label for="end-time">End Time *</label>
                            <input type="time" id="end-time" name="end_time" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Show Days *</label>
                        <div class="days-checkboxes">
                            <label><input type="checkbox" name="days[]" value="monday"> Monday</label>
                            <label><input type="checkbox" name="days[]" value="tuesday"> Tuesday</label>
                            <label><input type="checkbox" name="days[]" value="wednesday"> Wednesday</label>
                            <label><input type="checkbox" name="days[]" value="thursday"> Thursday</label>
                            <label><input type="checkbox" name="days[]" value="friday"> Friday</label>
                            <label><input type="checkbox" name="days[]" value="saturday"> Saturday</label>
                            <label><input type="checkbox" name="days[]" value="sunday"> Sunday</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="show-description">Show Description</label>
                        <textarea id="show-description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="show-image">Show Image URL</label>
                        <input type="url" id="show-image" name="image_url" placeholder="https://example.com/image.jpg">
                        <small>Enter a direct link to an image, or upload to your media library and copy the URL</small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Add Show</button>
                        <button type="button" class="btn-secondary" onclick="this.closest('form').reset()">Clear Form</button>
                    </div>
                    
                    <input type="hidden" name="show_id" id="show-id">
                </form>
            </div>
            <?php endif; ?>
            
            <?php if ($show_existing_shows): ?>
            <div class="existing-shows">
                <h3>Existing Shows</h3>
                <div id="shows-list">
                    <div class="loading-state">Loading shows...</div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <style>
        .radio-show-manager {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .add-show-form {
            background: var(--form-bg);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--button-color);
        }
        
        .days-checkboxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }
        
        .days-checkboxes label {
            display: flex;
            align-items: center;
            font-weight: normal;
            cursor: pointer;
        }
        
        .days-checkboxes input {
            width: auto;
            margin-right: 8px;
        }
        
        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .btn-primary,
        .btn-secondary {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: var(--button-color);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .btn-secondary {
            background: #f8f9fa;
            color: #6c757d;
            border: 2px solid #e1e5e9;
        }
        
        .existing-shows {
            background: var(--form-bg);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .show-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border: 2px solid #e1e5e9;
            border-radius: 10px;
            margin-bottom: 15px;
        }
        
        .show-info h4 {
            margin: 0 0 5px 0;
            color: #333;
        }
        
        .show-info p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        
        .show-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-edit,
        .btn-delete {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-edit {
            background: #17a2b8;
            color: white;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
        }
        
        .loading-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
            
            .days-checkboxes {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .show-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
        </style>
        <?php
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
