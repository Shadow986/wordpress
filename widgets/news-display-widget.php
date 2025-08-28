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
        
        // Layout Settings
        $this->start_controls_section(
            'layout_section',
            [
                'label' => 'Layout & Grid',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'columns',
            [
                'label' => 'Columns',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1' => '1 Column',
                    '2' => '2 Columns',
                    '3' => '3 Columns',
                    '4' => '4 Columns',
                    '6' => '6 Columns',
                ],
            ]
        );
        
        $this->add_control(
            'rows',
            [
                'label' => 'Rows per Page',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 2,
                'min' => 1,
                'max' => 10,
            ]
        );
        
        $this->add_control(
            'sidebar_position',
            [
                'label' => 'Sidebar Position',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'none' => 'No Sidebar',
                    'left' => 'Left Sidebar',
                    'right' => 'Right Sidebar',
                ],
                'condition' => [
                    'template_style' => ['template_1', 'template_3'],
                ],
            ]
        );
        
        $this->add_control(
            'sidebar_width',
            [
                'label' => 'Sidebar Width (%)',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    '%' => [
                        'min' => 20,
                        'max' => 50,
                    ],
                ],
                'default' => [
                    'size' => 30,
                ],
                'condition' => [
                    'sidebar_position!' => 'none',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Image Settings
        $this->start_controls_section(
            'image_section',
            [
                'label' => 'Image Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'image_size',
            [
                'label' => 'Image Size',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'medium',
                'options' => [
                    'thumbnail' => 'Thumbnail (150x150)',
                    'medium' => 'Medium (300x300)',
                    'large' => 'Large (1024x1024)',
                    'full' => 'Full Size',
                    'custom' => 'Custom Size',
                ],
            ]
        );
        
        $this->add_control(
            'custom_image_width',
            [
                'label' => 'Custom Width',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 300,
                'condition' => [
                    'image_size' => 'custom',
                ],
            ]
        );
        
        $this->add_control(
            'custom_image_height',
            [
                'label' => 'Custom Height',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 200,
                'condition' => [
                    'image_size' => 'custom',
                ],
            ]
        );
        
        $this->add_control(
            'image_position',
            [
                'label' => 'Image Position',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'top',
                'options' => [
                    'top' => 'Top',
                    'left' => 'Left',
                    'right' => 'Right',
                    'background' => 'Background',
                ],
            ]
        );
        
        $this->add_control(
            'default_image',
            [
                'label' => 'Default Image URL',
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com/default-image.jpg',
                'description' => 'Used when no image is provided',
            ]
        );
        
        $this->end_controls_section();
        
        // Pagination Settings
        $this->start_controls_section(
            'pagination_section',
            [
                'label' => 'Pagination',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'enable_pagination',
            [
                'label' => 'Enable Pagination',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'pagination_type',
            [
                'label' => 'Pagination Type',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'numbers',
                'options' => [
                    'numbers' => 'Page Numbers',
                    'prev_next' => 'Previous/Next',
                    'load_more' => 'Load More Button',
                    'infinite' => 'Infinite Scroll',
                ],
                'condition' => [
                    'enable_pagination' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'items_per_page',
            [
                'label' => 'Items per Page',
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 50,
                'condition' => [
                    'enable_pagination' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Heading/Title Settings
        $this->start_controls_section(
            'heading_section',
            [
                'label' => 'Heading & Title',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_main_heading',
            [
                'label' => 'Show Main Heading',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'main_heading_text',
            [
                'label' => 'Main Heading Text',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Latest News & Events',
                'condition' => [
                    'show_main_heading' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'heading_tag',
            [
                'label' => 'Heading HTML Tag',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => 'H1',
                    'h2' => 'H2',
                    'h3' => 'H3',
                    'h4' => 'H4',
                    'h5' => 'H5',
                    'h6' => 'H6',
                ],
                'condition' => [
                    'show_main_heading' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'show_section_titles',
            [
                'label' => 'Show Section Titles',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => 'Show "News" and "Events" section titles',
            ]
        );
        
        $this->end_controls_section();
        
        // Quick Add Content Section
        $this->start_controls_section(
            'quick_content_section',
            [
                'label' => 'Add Content',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'quick_add_type',
            [
                'label' => 'Add New',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'news',
                'options' => [
                    'news' => 'News Item',
                    'event' => 'Event',
                ],
            ]
        );
        
        $this->add_control(
            'quick_title',
            [
                'label' => 'Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'Enter title...',
            ]
        );
        
        $this->add_control(
            'quick_content',
            [
                'label' => 'Content/Description',
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 4,
                'placeholder' => 'Enter content...',
            ]
        );
        
        $this->add_control(
            'quick_image',
            [
                'label' => 'Image',
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => '',
                ],
            ]
        );
        
        $this->add_control(
            'quick_author',
            [
                'label' => 'Author',
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'Author name...',
            ]
        );
        
        $this->add_control(
            'quick_category',
            [
                'label' => 'Category',
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'breaking' => 'Breaking News',
                    'sports' => 'Sports',
                    'politics' => 'Politics',
                    'entertainment' => 'Entertainment',
                    'technology' => 'Technology',
                    'health' => 'Health',
                    'business' => 'Business',
                    'conference' => 'Conference',
                    'workshop' => 'Workshop',
                    'webinar' => 'Webinar',
                    'meetup' => 'Meetup',
                ],
            ]
        );
        
        $this->add_control(
            'quick_date',
            [
                'label' => 'Date/Event Date',
                'type' => \Elementor\Controls_Manager::DATE_TIME,
                'condition' => [
                    'quick_add_type' => 'event',
                ],
            ]
        );
        
        $this->add_control(
            'quick_location',
            [
                'label' => 'Location',
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'Event location...',
                'condition' => [
                    'quick_add_type' => 'event',
                ],
            ]
        );
        
        $this->add_control(
            'add_content_button',
            [
                'label' => 'Add Content',
                'type' => \Elementor\Controls_Manager::BUTTON,
                'text' => 'Add Item',
                'event' => 'add_content_item',
            ]
        );
        
        $this->end_controls_section();
        
        // Management Section
        $this->start_controls_section(
            'management_section',
            [
                'label' => 'Advanced Management',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_management',
            [
                'label' => 'Show Full Management Panel',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'description' => 'Enable advanced content management interface',
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
        
        // Image Style Section
        $this->start_controls_section(
            'image_style_section',
            [
                'label' => 'Image Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'image_border_radius',
            [
                'label' => 'Image Border Radius',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .item-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'image_shadow',
                'label' => 'Image Shadow',
                'selector' => '{{WRAPPER}} .item-image img',
            ]
        );
        
        $this->add_control(
            'image_hover_effect',
            [
                'label' => 'Hover Effect',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'zoom',
                'options' => [
                    'none' => 'None',
                    'zoom' => 'Zoom In',
                    'zoom_out' => 'Zoom Out',
                    'fade' => 'Fade',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Pagination Style Section
        $this->start_controls_section(
            'pagination_style_section',
            [
                'label' => 'Pagination Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'enable_pagination' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'pagination_alignment',
            [
                'label' => 'Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => 'Left',
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => 'Center',
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => 'Right',
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .pagination-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'pagination_button_color',
            [
                'label' => 'Button Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pagination-btn' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'pagination_button_bg',
            [
                'label' => 'Button Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pagination-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'pagination_active_color',
            [
                'label' => 'Active Button Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .pagination-btn.active' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Heading Style Section
        $this->start_controls_section(
            'heading_style_section',
            [
                'label' => 'Heading Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_main_heading' => 'yes',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'label' => 'Heading Typography',
                'selector' => '{{WRAPPER}} .main-heading',
            ]
        );
        
        $this->add_control(
            'heading_color',
            [
                'label' => 'Heading Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .main-heading' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'heading_alignment',
            [
                'label' => 'Heading Alignment',
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => 'Left',
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => 'Center',
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => 'Right',
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .main-heading' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Meta Information Style
        $this->start_controls_section(
            'meta_style_section',
            [
                'label' => 'Meta Info Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typography',
                'label' => 'Meta Typography',
                'selector' => '{{WRAPPER}} .item-meta',
            ]
        );
        
        $this->add_control(
            'meta_color',
            [
                'label' => 'Meta Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .item-meta' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'author_color',
            [
                'label' => 'Author Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .item-author' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_author' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'date_color',
            [
                'label' => 'Date Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .item-date' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_date' => 'yes',
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
            
            <?php if ($settings['show_main_heading'] === 'yes'): ?>
                <<?php echo esc_attr($settings['heading_tag']); ?> class="main-heading">
                    <?php echo esc_html($settings['main_heading_text']); ?>
                </<?php echo esc_attr($settings['heading_tag']); ?>>
            <?php endif; ?>
            
            <!-- Quick Add Content Form -->
            <?php if (is_user_logged_in() && current_user_can('edit_posts')): ?>
                <div class="quick-add-panel">
                    <h3>Add New Content</h3>
                    <form class="quick-add-form" data-widget-id="<?php echo $widget_id; ?>">
                        <div class="form-row">
                            <select name="content_type" required>
                                <option value="news">News Item</option>
                                <option value="event">Event</option>
                            </select>
                            <input type="text" name="title" placeholder="Title" required>
                        </div>
                        <textarea name="content" placeholder="Content/Description" rows="3" required></textarea>
                        <div class="form-row">
                            <input type="text" name="author" placeholder="Author">
                            <input type="url" name="image_url" placeholder="Image URL">
                        </div>
                        <div class="form-row event-fields" style="display:none;">
                            <input type="datetime-local" name="event_date">
                            <input type="text" name="location" placeholder="Location">
                        </div>
                        <div class="form-row">
                            <select name="category">
                                <option value="">Select Category</option>
                                <option value="breaking">Breaking News</option>
                                <option value="sports">Sports</option>
                                <option value="technology">Technology</option>
                                <option value="business">Business</option>
                                <option value="conference">Conference</option>
                                <option value="workshop">Workshop</option>
                            </select>
                            <button type="submit" class="add-btn">Add Content</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
            
            <?php if ($settings['show_management'] === 'yes' && $this->can_manage_content($settings['allowed_roles'])): ?>
                <div class="management-panel">
                    <?php $this->render_management_panel($settings); ?>
                </div>
            <?php endif; ?>
            
            <div class="content-display" 
                 data-columns="<?php echo esc_attr($settings['columns']); ?>"
                 data-rows="<?php echo esc_attr($settings['rows']); ?>"
                 data-sidebar="<?php echo esc_attr($settings['sidebar_position'] ?? 'none'); ?>">
                <?php $this->render_template($settings, $widget_id); ?>
            </div>
            
            <?php if ($settings['enable_pagination'] === 'yes'): ?>
                <div class="pagination-container" data-type="<?php echo esc_attr($settings['pagination_type']); ?>">
                    <div class="pagination-wrapper"></div>
                </div>
            <?php endif; ?>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            loadContentForWidget('<?php echo $widget_id; ?>', <?php echo json_encode($settings); ?>);
            
            // Show/hide event fields
            $('select[name="content_type"]').change(function() {
                if ($(this).val() === 'event') {
                    $('.event-fields').show();
                } else {
                    $('.event-fields').hide();
                }
            });
            
            // Quick add form submission
            $('.quick-add-form').submit(function(e) {
                e.preventDefault();
                submitQuickAdd($(this));
            });
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
        <div class="form-section">
            <h3>Add/Edit News</h3>
            <form class="news-form">
                <div class="form-row">
                    <input type="text" name="title" placeholder="News Title" required>
                    <input type="text" name="author" placeholder="Author Name">
                </div>
                <div class="form-row">
                    <select name="category">
                        <option value="">Select Category</option>
                        <option value="breaking">Breaking News</option>
                        <option value="sports">Sports</option>
                        <option value="politics">Politics</option>
                        <option value="entertainment">Entertainment</option>
                        <option value="technology">Technology</option>
                        <option value="health">Health</option>
                        <option value="business">Business</option>
                    </select>
                    <input type="datetime-local" name="publish_date">
                </div>
                <textarea name="content" placeholder="News Content" rows="4" required></textarea>
                <div class="image-upload-section">
                    <label>News Image:</label>
                    <div class="image-input-group">
                        <input type="url" name="image_url" placeholder="Image URL">
                        <button type="button" class="upload-btn">Upload Image</button>
                        <input type="file" name="image_file" accept="image/*" style="display:none;">
                    </div>
                    <div class="image-preview"></div>
                </div>
                <div class="form-row">
                    <input type="url" name="source_url" placeholder="Source URL (optional)">
                    <select name="status">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="featured">Featured</option>
                    </select>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Add News</button>
                    <button type="button" class="btn-secondary" onclick="clearForm(this)">Clear</button>
                </div>
            </form>
        </div>
        <div class="existing-items">
            <h3>Manage Existing News</h3>
            <div class="items-list news-list">
                <div class="loading">Loading news items...</div>
            </div>
        </div>
        <?php
    }
    
    private function render_events_form() {
        ?>
        <div class="form-section">
            <h3>Add/Edit Events</h3>
            <form class="events-form">
                <div class="form-row">
                    <input type="text" name="title" placeholder="Event Title" required>
                    <input type="text" name="location" placeholder="Event Location">
                </div>
                <div class="form-row">
                    <input type="datetime-local" name="event_date" required>
                    <input type="datetime-local" name="end_date" placeholder="End Date (optional)">
                </div>
                <textarea name="description" placeholder="Event Description" rows="4" required></textarea>
                <div class="image-upload-section">
                    <label>Event Image:</label>
                    <div class="image-input-group">
                        <input type="url" name="image_url" placeholder="Image URL">
                        <button type="button" class="upload-btn">Upload Image</button>
                        <input type="file" name="image_file" accept="image/*" style="display:none;">
                    </div>
                    <div class="image-preview"></div>
                </div>
                <div class="form-row">
                    <select name="category">
                        <option value="">Select Category</option>
                        <option value="conference">Conference</option>
                        <option value="workshop">Workshop</option>
                        <option value="webinar">Webinar</option>
                        <option value="meetup">Meetup</option>
                        <option value="concert">Concert</option>
                        <option value="festival">Festival</option>
                        <option value="sports">Sports Event</option>
                    </select>
                    <input type="text" name="organizer" placeholder="Organizer">
                </div>
                <div class="form-row">
                    <input type="url" name="registration_url" placeholder="Registration URL">
                    <input type="number" name="price" placeholder="Price (optional)" step="0.01">
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Add Event</button>
                    <button type="button" class="btn-secondary" onclick="clearForm(this)">Clear</button>
                </div>
            </form>
        </div>
        <div class="existing-items">
            <h3>Manage Existing Events</h3>
            <div class="items-list events-list">
                <div class="loading">Loading events...</div>
            </div>
        </div>
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
