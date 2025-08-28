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
        
        // Direct Content Input
        $this->start_controls_section(
            'direct_content_section',
            [
                'label' => 'Your Content',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'content_items',
            [
                'label' => 'Add News/Events',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'item_type',
                        'label' => 'Type',
                        'type' => \Elementor\Controls_Manager::SELECT,
                        'default' => 'news',
                        'options' => [
                            'news' => 'News',
                            'event' => 'Event',
                        ],
                    ],
                    [
                        'name' => 'title',
                        'label' => 'Title',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => 'Enter title here',
                    ],
                    [
                        'name' => 'content',
                        'label' => 'Content',
                        'type' => \Elementor\Controls_Manager::TEXTAREA,
                        'default' => 'Enter your content here...',
                        'rows' => 4,
                    ],
                    [
                        'name' => 'image',
                        'label' => 'Image',
                        'type' => \Elementor\Controls_Manager::MEDIA,
                    ],
                    [
                        'name' => 'author',
                        'label' => 'Author',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => 'Author Name',
                    ],
                    [
                        'name' => 'date',
                        'label' => 'Date',
                        'type' => \Elementor\Controls_Manager::DATE_TIME,
                    ],
                    [
                        'name' => 'schedule_days',
                        'label' => 'Show on Days',
                        'type' => \Elementor\Controls_Manager::SELECT2,
                        'multiple' => true,
                        'options' => [
                            'monday' => 'Monday',
                            'tuesday' => 'Tuesday',
                            'wednesday' => 'Wednesday',
                            'thursday' => 'Thursday',
                            'friday' => 'Friday',
                            'saturday' => 'Saturday',
                            'sunday' => 'Sunday',
                        ],
                        'default' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'],
                    ],
                    [
                        'name' => 'schedule_time_start',
                        'label' => 'Show From Time',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => '09:00',
                        'description' => 'Format: HH:MM (24-hour)',
                    ],
                    [
                        'name' => 'schedule_time_end',
                        'label' => 'Show Until Time',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'placeholder' => '17:00',
                        'description' => 'Format: HH:MM (24-hour)',
                    ],
                    [
                        'name' => 'category',
                        'label' => 'Category',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => 'General',
                    ],
                ],
                'default' => [
                    [
                        'title' => 'Sample News Item',
                        'content' => 'This is sample content. Edit this in the widget settings.',
                        'author' => 'Assend Creative',
                        'category' => 'News',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );
        
        $this->end_controls_section();
        
        // Sidebar Content
        $this->start_controls_section(
            'sidebar_content_section',
            [
                'label' => 'Trending Stories (Sidebar)',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_sidebar',
            [
                'label' => 'Show Sidebar',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'sidebar_title',
            [
                'label' => 'Sidebar Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Trending Stories',
                'condition' => [
                    'show_sidebar' => 'yes',
                ],
            ]
        );
        
        $this->add_control(
            'sidebar_items',
            [
                'label' => 'Sidebar Content',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'title',
                        'label' => 'Title',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => 'Trending Story',
                    ],
                    [
                        'name' => 'content',
                        'label' => 'Content',
                        'type' => \Elementor\Controls_Manager::TEXTAREA,
                        'default' => 'Trending story content...',
                        'rows' => 3,
                    ],
                    [
                        'name' => 'image',
                        'label' => 'Image',
                        'type' => \Elementor\Controls_Manager::MEDIA,
                    ],
                ],
                'default' => [
                    [
                        'title' => 'Sample Trending Story',
                        'content' => 'This is a sample trending story that appears in the sidebar.',
                    ],
                ],
                'title_field' => '{{{ title }}}',
                'condition' => [
                    'show_sidebar' => 'yes',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Schedule Settings
        $this->start_controls_section(
            'schedule_section',
            [
                'label' => 'Schedule Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'show_day_filter',
            [
                'label' => 'Show Day Filter',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
                'description' => 'Show day buttons (Mon, Tue, Wed, etc.)',
            ]
        );
        
        $this->add_control(
            'current_day_only',
            [
                'label' => 'Show Current Day Only',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
                'description' => 'Only show content scheduled for today',
            ]
        );
        
        $this->end_controls_section();
        
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
            
            <?php if ($settings['show_day_filter'] === 'yes'): ?>
                <div class="day-filter">
                    <button class="day-btn active" data-day="all">All</button>
                    <button class="day-btn" data-day="monday">Mon</button>
                    <button class="day-btn" data-day="tuesday">Tue</button>
                    <button class="day-btn" data-day="wednesday">Wed</button>
                    <button class="day-btn" data-day="thursday">Thu</button>
                    <button class="day-btn" data-day="friday">Fri</button>
                    <button class="day-btn" data-day="saturday">Sat</button>
                    <button class="day-btn" data-day="sunday">Sun</button>
                </div>
            <?php endif; ?>
            
            <div class="content-layout <?php echo $settings['show_sidebar'] === 'yes' ? 'with-sidebar' : 'no-sidebar'; ?>">
                <div class="main-content">
                    <?php $this->render_content_items($settings); ?>
                </div>
                
                <?php if ($settings['show_sidebar'] === 'yes'): ?>
                    <div class="sidebar-content">
                        <h3 class="sidebar-title"><?php echo esc_html($settings['sidebar_title']); ?></h3>
                        <?php $this->render_sidebar_items($settings); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            // Day filter functionality
            $('.day-btn').click(function() {
                $('.day-btn').removeClass('active');
                $(this).addClass('active');
                
                const selectedDay = $(this).data('day');
                filterByDay(selectedDay);
            });
            
            // Initial filter based on current day if enabled
            <?php if ($settings['current_day_only'] === 'yes'): ?>
                const today = new Date().toLocaleLowerCase().substring(0, 3);
                filterByDay(today + 'day');
            <?php endif; ?>
        });
        
        function filterByDay(day) {
            $('.news-item, .event-item').each(function() {
                const itemDays = $(this).data('days');
                if (day === 'all' || !itemDays || itemDays.includes(day)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
        </script>
        <?php
    }
    
    private function render_content_items($settings) {
        if (empty($settings['content_items'])) {
            echo '<div class="no-content">No content added yet. Add content in the widget settings.</div>';
            return;
        }
        
        $columns = $settings['show_sidebar'] === 'yes' ? 2 : ($settings['columns'] ?? 3);
        
        echo '<div class="template-grid" data-columns="' . esc_attr($columns) . '">';
        echo '<div class="grid-items">';
        
        foreach ($settings['content_items'] as $item) {
            if ($this->should_show_item($item)) {
                $this->render_single_item($item, $settings);
            }
        }
        
        echo '</div></div>';
    }
    
    private function should_show_item($item) {
        // Check time schedule
        if (!empty($item['schedule_time_start']) && !empty($item['schedule_time_end'])) {
            $current_time = current_time('H:i');
            if ($current_time < $item['schedule_time_start'] || $current_time > $item['schedule_time_end']) {
                return false;
            }
        }
        
        // Check day schedule
        if (!empty($item['schedule_days'])) {
            $current_day = strtolower(current_time('l'));
            if (!in_array($current_day, $item['schedule_days'])) {
                return false;
            }
        }
        
        return true;
    }
    
    private function render_single_item($item, $settings) {
        $item_class = $item['item_type'] === 'event' ? 'event-item' : 'news-item';
        $schedule_days = !empty($item['schedule_days']) ? implode(',', $item['schedule_days']) : '';
        
        ?>
        <div class="<?php echo esc_attr($item_class); ?>" data-days="<?php echo esc_attr($schedule_days); ?>">
            
            <?php if ($settings['show_image'] !== 'no' && !empty($item['image']['url'])): ?>
                <div class="item-image">
                    <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
                </div>
            <?php endif; ?>
            
            <div class="item-content">
                <h3 class="item-title"><?php echo esc_html($item['title']); ?></h3>
                
                <?php if ($settings['show_author'] === 'yes' || $settings['show_date'] === 'yes'): ?>
                    <div class="item-meta">
                        <?php if ($settings['show_author'] === 'yes' && !empty($item['author'])): ?>
                            <span class="item-author">👤 <?php echo esc_html($item['author']); ?></span>
                        <?php endif; ?>
                        
                        <?php if ($settings['show_date'] === 'yes' && !empty($item['date'])): ?>
                            <span class="item-date">📅 <?php echo date('M j, Y', strtotime($item['date'])); ?></span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <div class="item-excerpt"><?php echo wp_kses_post($item['content']); ?></div>
                
                <?php if (!empty($item['category'])): ?>
                    <span class="item-category"><?php echo esc_html($item['category']); ?></span>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
    
    private function render_sidebar_items($settings) {
        if (empty($settings['sidebar_items'])) {
            echo '<div class="no-sidebar-content">No trending stories added yet.</div>';
            return;
        }
        
        echo '<div class="sidebar-items">';
        
        foreach ($settings['sidebar_items'] as $item) {
            ?>
            <div class="sidebar-item">
                <?php if (!empty($item['image']['url'])): ?>
                    <div class="sidebar-image">
                        <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
                    </div>
                <?php endif; ?>
                
                <div class="sidebar-content">
                    <h4 class="sidebar-item-title"><?php echo esc_html($item['title']); ?></h4>
                    <div class="sidebar-item-content"><?php echo wp_kses_post($item['content']); ?></div>
                </div>
            </div>
            <?php
        }
        
        echo '</div>';
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
