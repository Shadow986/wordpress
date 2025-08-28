<?php

if (!defined('ABSPATH')) {
    exit;
}

class NewsDisplayWidget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'news_display';
    }
    
    public function get_title() {
        return 'News & Events Hub';
    }
    
    public function get_icon() {
        return 'eicon-posts-grid';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    protected function register_controls() {
        
        // Template Selection
        $this->start_controls_section(
            'template_section',
            [
                'label' => 'Template',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'template_style',
            [
                'label' => 'Choose Template',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'template_1',
                'options' => [
                    'template_1' => 'Left Carousel + Right Info',
                    'template_2' => 'Grid Layout',
                    'template_3' => 'List with Sidebar',
                    'template_4' => 'Magazine Style',
                    'template_5' => 'Card Stack',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Content Settings
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Content Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'content_type',
            [
                'label' => 'Content Type',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'news',
                'options' => [
                    'news' => 'News Only',
                    'events' => 'Events Only',
                    'both' => 'News & Events',
                ],
            ]
        );
        
        $this->add_control(
            'news_count',
            [
                'label' => 'Number of News Items',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 20,
                'condition' => [
                    'content_type!' => 'events',
                ],
            ]
        );
        
        $this->add_control(
            'events_count',
            [
                'label' => 'Number of Events',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 4,
                'min' => 1,
                'max' => 20,
                'condition' => [
                    'content_type!' => 'news',
                ],
            ]
        );
        
        $this->add_control(
            'show_author',
            [
                'label' => 'Show Author',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_date',
            [
                'label' => 'Show Date/Time',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_comments',
            [
                'label' => 'Show Comments Count',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_image',
            [
                'label' => 'Show Images',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->end_controls_section();
        
        // Management Section
        $this->start_controls_section(
            'management_section',
            [
                'label' => 'Content Management',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_management',
            [
                'label' => 'Show Management Panel',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'description' => 'Enable to add/edit content directly in this widget',
            ]
        );
        
        $this->add_control(
            'allowed_roles',
            [
                'label' => 'Who Can Manage Content',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'administrator' => 'Administrator',
                    'editor' => 'Editor',
                    'author' => 'Author',
                    'contributor' => 'Contributor',
                ],
                'default' => ['administrator', 'editor'],
                'condition' => [
                    'show_management' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => 'Container Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'label' => 'Container Background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .news-events-container',
            ]
        );
        
        $this->add_control(
            'container_padding',
            [
                'label' => 'Container Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .news-events-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_control(
            'border_radius',
            [
                'label' => 'Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .news-events-container' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Card Style Section
        $this->start_controls_section(
            'card_style_section',
            [
                'label' => 'Card Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'card_background',
                'label' => 'Card Background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .news-item, {{WRAPPER}} .event-item',
            ]
        );
        
        $this->add_control(
            'card_border_radius',
            [
                'label' => 'Card Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 30,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .news-item, {{WRAPPER}} .event-item' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'card_shadow',
                'label' => 'Card Shadow',
                'selector' => '{{WRAPPER}} .news-item, {{WRAPPER}} .event-item',
            ]
        );
        
        $this->end_controls_section();
        
        // Typography Section
        $this->start_controls_section(
            'typography_section',
            [
                'label' => 'Typography',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => 'Title Typography',
                'selector' => '{{WRAPPER}} .item-title',
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .item-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => 'Content Typography',
                'selector' => '{{WRAPPER}} .item-content',
            ]
        );
        
        $this->add_control(
            'content_color',
            [
                'label' => 'Content Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .item-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_id = 'news-events-' . $this->get_id();
        
        ?>
        <div class="news-events-container template-<?php echo esc_attr($settings['template_style']); ?>" id="<?php echo $widget_id; ?>">
            
            <?php if ($settings['show_management'] === 'yes' && $this->can_manage_content($settings['allowed_roles'])): ?>
                <div class="management-panel">
                    <?php $this->render_management_panel($settings); ?>
                </div>
            <?php endif; ?>
            
            <div class="content-display">
                <?php $this->render_template($settings, $widget_id); ?>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            loadContentForWidget('<?php echo $widget_id; ?>', <?php echo json_encode($settings); ?>);
        });
        </script>
        <?php
    }
    
    private function can_manage_content($allowed_roles) {
        if (!is_user_logged_in()) {
            return false;
        }
        
        $user = wp_get_current_user();
        return array_intersect($user->roles, $allowed_roles);
    }
    
    private function render_management_panel($settings) {
        ?>
        <div class="management-tabs">
            <?php if ($settings['content_type'] !== 'events'): ?>
                <button class="tab-btn active" data-tab="news">Manage News</button>
            <?php endif; ?>
            <?php if ($settings['content_type'] !== 'news'): ?>
                <button class="tab-btn" data-tab="events">Manage Events</button>
            <?php endif; ?>
        </div>
        
        <?php if ($settings['content_type'] !== 'events'): ?>
            <div class="tab-content" id="news-tab">
                <?php $this->render_news_form(); ?>
            </div>
        <?php endif; ?>
        
        <?php if ($settings['content_type'] !== 'news'): ?>
            <div class="tab-content" id="events-tab">
                <?php $this->render_events_form(); ?>
            </div>
        <?php endif; ?>
        <?php
    }
    
    private function render_news_form() {
        ?>
        <form class="news-form">
            <div class="form-row">
                <input type="text" name="title" placeholder="News Title" required>
                <input type="text" name="author" placeholder="Author Name">
            </div>
            <textarea name="content" placeholder="News Content" required></textarea>
            <div class="form-row">
                <input type="url" name="image_url" placeholder="Image URL">
                <input type="url" name="source_url" placeholder="Source URL">
            </div>
            <div class="form-row">
                <input type="text" name="category" placeholder="Category">
                <button type="submit">Add News</button>
            </div>
        </form>
        <div class="existing-items news-list"></div>
        <?php
    }
    
    private function render_events_form() {
        ?>
        <form class="events-form">
            <div class="form-row">
                <input type="text" name="title" placeholder="Event Title" required>
                <input type="text" name="location" placeholder="Location">
            </div>
            <textarea name="description" placeholder="Event Description" required></textarea>
            <div class="form-row">
                <input type="datetime-local" name="event_date" required>
                <input type="datetime-local" name="end_date">
            </div>
            <div class="form-row">
                <input type="url" name="image_url" placeholder="Image URL">
                <input type="text" name="category" placeholder="Category">
            </div>
            <button type="submit">Add Event</button>
        </form>
        <div class="existing-items events-list"></div>
        <?php
    }
    
    private function render_template($settings, $widget_id) {
        switch ($settings['template_style']) {
            case 'template_1':
                $this->render_carousel_sidebar_template($settings, $widget_id);
                break;
            case 'template_2':
                $this->render_grid_template($settings, $widget_id);
                break;
            case 'template_3':
                $this->render_list_sidebar_template($settings, $widget_id);
                break;
            case 'template_4':
                $this->render_magazine_template($settings, $widget_id);
                break;
            case 'template_5':
                $this->render_card_stack_template($settings, $widget_id);
                break;
        }
    }
    
    private function render_carousel_sidebar_template($settings, $widget_id) {
        ?>
        <div class="template-carousel-sidebar">
            <div class="left-carousel">
                <div class="carousel-container">
                    <div class="carousel-items"></div>
                    <div class="carousel-controls">
                        <button class="carousel-prev">‹</button>
                        <button class="carousel-next">›</button>
                    </div>
                </div>
            </div>
            <div class="right-sidebar">
                <div class="sidebar-content">
                    <div class="featured-item"></div>
                    <div class="additional-info">
                        <h3>Latest Updates</h3>
                        <div class="mini-items"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    private function render_grid_template($settings, $widget_id) {
        ?>
        <div class="template-grid">
            <div class="grid-items"></div>
        </div>
        <?php
    }
    
    private function render_list_sidebar_template($settings, $widget_id) {
        ?>
        <div class="template-list-sidebar">
            <div class="main-list">
                <div class="list-items"></div>
            </div>
            <div class="sidebar">
                <div class="sidebar-widget">
                    <h3>Quick Info</h3>
                    <div class="quick-items"></div>
                </div>
            </div>
        </div>
        <?php
    }
    
    private function render_magazine_template($settings, $widget_id) {
        ?>
        <div class="template-magazine">
            <div class="featured-section">
                <div class="main-feature"></div>
                <div class="sub-features"></div>
            </div>
            <div class="regular-items"></div>
        </div>
        <?php
    }
    
    private function render_card_stack_template($settings, $widget_id) {
        ?>
        <div class="template-card-stack">
            <div class="stacked-cards"></div>
        </div>
        <?php
    }
}
