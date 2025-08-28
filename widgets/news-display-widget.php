<?php

class NewsDisplayWidget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'news_display';
    }
    
    public function get_title() {
        return 'News Display';
    }
    
    public function get_icon() {
        return 'eicon-posts-grid';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    protected function _register_controls() {
        
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Content Settings',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'title',
            [
                'label' => 'Section Title',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Latest News',
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
            ]
        );
        
        $this->add_control(
            'category_filter',
            [
                'label' => 'Category Filter',
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => 'Leave empty for all categories',
            ]
        );
        
        $this->add_control(
            'layout_style',
            [
                'label' => 'Layout Style',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => 'Grid',
                    'list' => 'List',
                    'carousel' => 'Carousel',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'style_section',
            [
                'label' => 'Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'card_background',
            [
                'label' => 'Card Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
            ]
        );
        
        $this->add_control(
            'title_color',
            [
                'label' => 'Title Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#333333',
            ]
        );
        
        $this->add_control(
            'content_color',
            [
                'label' => 'Content Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#666666',
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
                'default' => [
                    'size' => 10,
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $widget_id = 'news-display-' . $this->get_id();
        ?>
        
        <div class="news-display-widget" id="<?php echo $widget_id; ?>">
            <?php if ($settings['title']): ?>
                <h2 class="news-section-title"><?php echo esc_html($settings['title']); ?></h2>
            <?php endif; ?>
            
            <div class="news-container <?php echo esc_attr($settings['layout_style']); ?>">
                <div class="news-loading">Loading news...</div>
                <div class="news-items"></div>
            </div>
        </div>
        
        <style>
            #<?php echo $widget_id; ?> .news-item {
                background: <?php echo esc_attr($settings['card_background']); ?>;
                border-radius: <?php echo esc_attr($settings['border_radius']['size']); ?>px;
            }
            #<?php echo $widget_id; ?> .news-item h3 {
                color: <?php echo esc_attr($settings['title_color']); ?>;
            }
            #<?php echo $widget_id; ?> .news-item .content {
                color: <?php echo esc_attr($settings['content_color']); ?>;
            }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            loadNewsItems('<?php echo $widget_id; ?>', {
                limit: <?php echo intval($settings['news_count']); ?>,
                category: '<?php echo esc_js($settings['category_filter']); ?>',
                layout: '<?php echo esc_js($settings['layout_style']); ?>'
            });
        });
        </script>
        
        <?php
    }
}
