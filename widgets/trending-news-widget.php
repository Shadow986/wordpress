<?php

if (!defined('ABSPATH')) {
    exit;
}

class TrendingNewsWidget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'trending_news';
    }
    
    public function get_title() {
        return 'Trending News';
    }
    
    public function get_icon() {
        return 'eicon-posts-ticker';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    protected function register_controls() {
        
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Trending Content',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'widget_title',
            [
                'label' => 'Widget Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Trending News',
            ]
        );
        
        $this->add_control(
            'trending_items',
            [
                'label' => 'Trending Items',
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => [
                    [
                        'name' => 'title',
                        'label' => 'Title',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => 'Trending Story Title',
                    ],
                    [
                        'name' => 'content',
                        'label' => 'Content',
                        'type' => \Elementor\Controls_Manager::TEXTAREA,
                        'default' => 'Brief description of the trending story...',
                        'rows' => 3,
                    ],
                    [
                        'name' => 'image',
                        'label' => 'Image',
                        'type' => \Elementor\Controls_Manager::MEDIA,
                        'default' => [
                            'url' => '',
                        ],
                    ],
                    [
                        'name' => 'link',
                        'label' => 'Link',
                        'type' => \Elementor\Controls_Manager::URL,
                        'placeholder' => 'https://example.com',
                    ],
                    [
                        'name' => 'category',
                        'label' => 'Category',
                        'type' => \Elementor\Controls_Manager::TEXT,
                        'default' => 'Trending',
                    ],
                ],
                'default' => [
                    [
                        'title' => 'Breaking: Major News Story',
                        'content' => 'This is a sample trending news story that captures attention.',
                        'category' => 'Breaking',
                    ],
                    [
                        'title' => 'Popular: Viral Content',
                        'content' => 'Another trending story that everyone is talking about.',
                        'category' => 'Viral',
                    ],
                ],
                'title_field' => '{{{ title }}}',
            ]
        );
        
        $this->end_controls_section();
        
        // Layout Settings
        $this->start_controls_section(
            'layout_section',
            [
                'label' => 'Layout Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'layout_style',
            [
                'label' => 'Layout Style',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'vertical',
                'options' => [
                    'vertical' => 'Vertical List',
                    'horizontal' => 'Horizontal Cards',
                    'grid' => 'Grid Layout',
                ],
            ]
        );
        
        $this->add_control(
            'columns',
            [
                'label' => 'Columns',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '1',
                'options' => [
                    '1' => '1 Column',
                    '2' => '2 Columns',
                    '3' => '3 Columns',
                ],
                'condition' => [
                    'layout_style' => 'grid',
                ],
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
        
        $this->add_control(
            'show_category',
            [
                'label' => 'Show Category',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
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
                'label' => 'Background',
                'types' => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .trending-container',
            ]
        );
        
        $this->add_control(
            'container_padding',
            [
                'label' => 'Padding',
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .trending-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .trending-container' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Image Style
        $this->start_controls_section(
            'image_style_section',
            [
                'label' => 'Image Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'image_width',
            [
                'label' => 'Image Width',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 300,
                    ],
                ],
                'default' => [
                    'size' => 80,
                ],
                'selectors' => [
                    '{{WRAPPER}} .trending-image' => 'width: {{SIZE}}{{UNIT}}; flex-shrink: 0;',
                ],
            ]
        );
        
        $this->add_control(
            'image_height',
            [
                'label' => 'Image Height',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 50,
                        'max' => 200,
                    ],
                ],
                'default' => [
                    'size' => 80,
                ],
                'selectors' => [
                    '{{WRAPPER}} .trending-image img' => 'height: {{SIZE}}{{UNIT}}; object-fit: cover;',
                ],
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
                    '{{WRAPPER}} .trending-image img' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        // Typography
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
                'selector' => '{{WRAPPER}} .trending-title',
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .trending-title' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'content_typography',
                'label' => 'Content Typography',
                'selector' => '{{WRAPPER}} .trending-content',
            ]
        );
        
        $this->add_control(
            'content_color',
            [
                'label' => 'Content Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .trending-content' => 'color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        ?>
        <div class="trending-container layout-<?php echo esc_attr($settings['layout_style']); ?>" data-columns="<?php echo esc_attr($settings['columns']); ?>">
            
            <?php if (!empty($settings['widget_title'])): ?>
                <h3 class="trending-widget-title"><?php echo esc_html($settings['widget_title']); ?></h3>
            <?php endif; ?>
            
            <div class="trending-items">
                <?php foreach ($settings['trending_items'] as $item): ?>
                    <div class="trending-item">
                        
                        <?php if ($settings['show_image'] === 'yes' && !empty($item['image']['url'])): ?>
                            <div class="trending-image">
                                <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
                            </div>
                        <?php endif; ?>
                        
                        <div class="trending-text">
                            <?php if ($settings['show_category'] === 'yes' && !empty($item['category'])): ?>
                                <span class="trending-category"><?php echo esc_html($item['category']); ?></span>
                            <?php endif; ?>
                            
                            <h4 class="trending-title">
                                <?php if (!empty($item['link']['url'])): ?>
                                    <a href="<?php echo esc_url($item['link']['url']); ?>" <?php echo $item['link']['is_external'] ? 'target="_blank"' : ''; ?>>
                                        <?php echo esc_html($item['title']); ?>
                                    </a>
                                <?php else: ?>
                                    <?php echo esc_html($item['title']); ?>
                                <?php endif; ?>
                            </h4>
                            
                            <div class="trending-content"><?php echo wp_kses_post($item['content']); ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}
