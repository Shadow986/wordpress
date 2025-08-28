<?php

if (!defined('ABSPATH')) {
    exit;
}

class NewsManagerWidget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'news_manager';
    }
    
    public function get_title() {
        return 'News Manager';
    }
    
    public function get_icon() {
        return 'eicon-edit';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    protected function register_controls() {
        
        $this->start_controls_section(
            'access_section',
            [
                'label' => 'Access Control',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'allowed_roles',
            [
                'label' => 'Allowed User Roles',
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => [
                    'administrator' => 'Administrator',
                    'editor' => 'Editor',
                    'author' => 'Author',
                    'contributor' => 'Contributor',
                ],
                'default' => ['administrator', 'editor'],
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section(
            'form_style_section',
            [
                'label' => 'Form Style',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'form_background',
            [
                'label' => 'Form Background',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#f8f9fa',
            ]
        );
        
        $this->add_control(
            'button_color',
            [
                'label' => 'Button Color',
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#007cba',
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (!is_user_logged_in()) {
            echo '<p>Please log in to manage news items.</p>';
            return;
        }
        
        $user = wp_get_current_user();
        $user_roles = $user->roles;
        $allowed_roles = $settings['allowed_roles'];
        
        if (!array_intersect($user_roles, $allowed_roles)) {
            echo '<p>You do not have permission to manage news items.</p>';
            return;
        }
        
        $widget_id = 'news-manager-' . $this->get_id();
        ?>
        
        <div class="news-manager-widget" id="<?php echo $widget_id; ?>">
            <div class="news-form-container">
                <h3>Add News Item</h3>
                <form class="news-form">
                    <div class="form-group">
                        <label>Title *</label>
                        <input type="text" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Content *</label>
                        <textarea name="content" rows="5" required></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Image URL</label>
                        <input type="url" name="image_url" placeholder="https://example.com/image.jpg">
                    </div>
                    
                    <div class="form-group">
                        <label>Source URL</label>
                        <input type="url" name="source_url" placeholder="https://example.com/article">
                    </div>
                    
                    <div class="form-group">
                        <label>Category</label>
                        <input type="text" name="category" placeholder="e.g., Sports, Politics, Technology">
                    </div>
                    
                    <button type="submit" class="submit-btn">Add News Item</button>
                </form>
            </div>
            
            <div class="existing-news-container">
                <h3>Existing News Items</h3>
                <div class="news-list"></div>
            </div>
        </div>
        
        <style>
            #<?php echo $widget_id; ?> .news-form-container {
                background: <?php echo esc_attr($settings['form_background']); ?>;
                padding: 20px;
                border-radius: 8px;
                margin-bottom: 30px;
            }
            #<?php echo $widget_id; ?> .submit-btn {
                background: <?php echo esc_attr($settings['button_color']); ?>;
                color: white;
                border: none;
                padding: 12px 24px;
                border-radius: 4px;
                cursor: pointer;
            }
            #<?php echo $widget_id; ?> .form-group {
                margin-bottom: 15px;
            }
            #<?php echo $widget_id; ?> .form-group label {
                display: block;
                margin-bottom: 5px;
                font-weight: bold;
            }
            #<?php echo $widget_id; ?> .form-group input,
            #<?php echo $widget_id; ?> .form-group textarea {
                width: 100%;
                padding: 8px;
                border: 1px solid #ddd;
                border-radius: 4px;
            }
            #<?php echo $widget_id; ?> .news-item-admin {
                background: white;
                padding: 15px;
                margin-bottom: 10px;
                border-radius: 4px;
                border: 1px solid #ddd;
            }
            #<?php echo $widget_id; ?> .delete-btn {
                background: #dc3545;
                color: white;
                border: none;
                padding: 5px 10px;
                border-radius: 3px;
                cursor: pointer;
                font-size: 12px;
            }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            const widgetId = '<?php echo $widget_id; ?>';
            
            // Handle form submission
            $('#' + widgetId + ' .news-form').on('submit', function(e) {
                e.preventDefault();
                
                const formData = {
                    action: 'save_news_item',
                    nonce: newsEventsAjax.nonce,
                    title: $(this).find('[name="title"]').val(),
                    content: $(this).find('[name="content"]').val(),
                    image_url: $(this).find('[name="image_url"]').val(),
                    source_url: $(this).find('[name="source_url"]').val(),
                    category: $(this).find('[name="category"]').val()
                };
                
                $.post(newsEventsAjax.ajaxurl, formData, function(response) {
                    if (response.success) {
                        alert('News item saved successfully!');
                        $('#' + widgetId + ' .news-form')[0].reset();
                        loadExistingNews(widgetId);
                    } else {
                        alert('Error: ' + response.data);
                    }
                });
            });
            
            // Load existing news items
            loadExistingNews(widgetId);
        });
        
        function loadExistingNews(widgetId) {
            jQuery.get(newsEventsAjax.ajaxurl, {
                action: 'get_news_items',
                limit: 20
            }, function(response) {
                if (response.success) {
                    let html = '';
                    response.data.forEach(function(item) {
                        html += `
                            <div class="news-item-admin">
                                <h4>${item.title}</h4>
                                <p>${item.content.substring(0, 100)}...</p>
                                <small>Category: ${item.category || 'None'} | Published: ${item.published_date}</small>
                                <br><br>
                                <button class="delete-btn" onclick="deleteNewsItem(${item.id}, '${widgetId}')">Delete</button>
                            </div>
                        `;
                    });
                    jQuery('#' + widgetId + ' .news-list').html(html);
                }
            });
        }
        
        function deleteNewsItem(id, widgetId) {
            if (confirm('Are you sure you want to delete this news item?')) {
                jQuery.post(newsEventsAjax.ajaxurl, {
                    action: 'delete_news_item',
                    nonce: newsEventsAjax.nonce,
                    id: id
                }, function(response) {
                    if (response.success) {
                        loadExistingNews(widgetId);
                    } else {
                        alert('Error deleting item');
                    }
                });
            }
        }
        </script>
        
        <?php
    }
}
