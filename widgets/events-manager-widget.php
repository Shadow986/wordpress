<?php

class EventsManagerWidget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'events_manager';
    }
    
    public function get_title() {
        return 'Events Manager';
    }
    
    public function get_icon() {
        return 'eicon-calendar';
    }
    
    public function get_categories() {
        return ['general'];
    }
    
    protected function _register_controls() {
        
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
                'default' => '#28a745',
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        if (!is_user_logged_in()) {
            echo '<p>Please log in to manage events.</p>';
            return;
        }
        
        $user = wp_get_current_user();
        $user_roles = $user->roles;
        $allowed_roles = $settings['allowed_roles'];
        
        if (!array_intersect($user_roles, $allowed_roles)) {
            echo '<p>You do not have permission to manage events.</p>';
            return;
        }
        
        $widget_id = 'events-manager-' . $this->get_id();
        ?>
        
        <div class="events-manager-widget" id="<?php echo $widget_id; ?>">
            <div class="events-form-container">
                <h3>Add Event</h3>
                <form class="events-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Event Title *</label>
                            <input type="text" name="title" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Category</label>
                            <input type="text" name="category" placeholder="e.g., Conference, Workshop, Concert">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Description *</label>
                        <textarea name="description" rows="4" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Start Date & Time *</label>
                            <input type="datetime-local" name="event_date" required>
                        </div>
                        
                        <div class="form-group">
                            <label>End Date & Time</label>
                            <input type="datetime-local" name="end_date">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Location</label>
                            <input type="text" name="location" placeholder="Event venue or online link">
                        </div>
                        
                        <div class="form-group">
                            <label>Image URL</label>
                            <input type="url" name="image_url" placeholder="https://example.com/event-image.jpg">
                        </div>
                    </div>
                    
                    <button type="submit" class="submit-btn">Add Event</button>
                </form>
            </div>
            
            <div class="existing-events-container">
                <h3>Existing Events</h3>
                <div class="events-list"></div>
            </div>
        </div>
        
        <style>
            #<?php echo $widget_id; ?> .events-form-container {
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
            #<?php echo $widget_id; ?> .form-row {
                display: flex;
                gap: 15px;
            }
            #<?php echo $widget_id; ?> .form-group {
                margin-bottom: 15px;
                flex: 1;
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
            #<?php echo $widget_id; ?> .event-item-admin {
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
            #<?php echo $widget_id; ?> .event-status {
                display: inline-block;
                padding: 2px 8px;
                border-radius: 12px;
                font-size: 11px;
                font-weight: bold;
            }
            #<?php echo $widget_id; ?> .status-upcoming {
                background: #d4edda;
                color: #155724;
            }
            #<?php echo $widget_id; ?> .status-past {
                background: #f8d7da;
                color: #721c24;
            }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            const widgetId = '<?php echo $widget_id; ?>';
            
            // Handle form submission
            $('#' + widgetId + ' .events-form').on('submit', function(e) {
                e.preventDefault();
                
                const formData = {
                    action: 'save_event_item',
                    nonce: newsEventsAjax.nonce,
                    title: $(this).find('[name="title"]').val(),
                    description: $(this).find('[name="description"]').val(),
                    event_date: $(this).find('[name="event_date"]').val(),
                    end_date: $(this).find('[name="end_date"]').val(),
                    location: $(this).find('[name="location"]').val(),
                    image_url: $(this).find('[name="image_url"]').val(),
                    category: $(this).find('[name="category"]').val()
                };
                
                $.post(newsEventsAjax.ajaxurl, formData, function(response) {
                    if (response.success) {
                        alert('Event saved successfully!');
                        $('#' + widgetId + ' .events-form')[0].reset();
                        loadExistingEvents(widgetId);
                    } else {
                        alert('Error: ' + response.data);
                    }
                });
            });
            
            // Load existing events
            loadExistingEvents(widgetId);
        });
        
        function loadExistingEvents(widgetId) {
            jQuery.get(newsEventsAjax.ajaxurl, {
                action: 'get_event_items',
                limit: 20,
                upcoming: false
            }, function(response) {
                if (response.success) {
                    let html = '';
                    response.data.forEach(function(item) {
                        const eventDate = new Date(item.event_date);
                        const now = new Date();
                        const isUpcoming = eventDate > now;
                        const statusClass = isUpcoming ? 'status-upcoming' : 'status-past';
                        const statusText = isUpcoming ? 'Upcoming' : 'Past';
                        
                        html += `
                            <div class="event-item-admin">
                                <h4>${item.title} <span class="event-status ${statusClass}">${statusText}</span></h4>
                                <p>${item.description.substring(0, 100)}...</p>
                                <small>
                                    <strong>Date:</strong> ${eventDate.toLocaleDateString()} ${eventDate.toLocaleTimeString()}<br>
                                    <strong>Location:</strong> ${item.location || 'Not specified'}<br>
                                    <strong>Category:</strong> ${item.category || 'None'}
                                </small>
                                <br><br>
                                <button class="delete-btn" onclick="deleteEventItem(${item.id}, '${widgetId}')">Delete</button>
                            </div>
                        `;
                    });
                    jQuery('#' + widgetId + ' .events-list').html(html);
                }
            });
        }
        
        function deleteEventItem(id, widgetId) {
            if (confirm('Are you sure you want to delete this event?')) {
                jQuery.post(newsEventsAjax.ajaxurl, {
                    action: 'delete_event_item',
                    nonce: newsEventsAjax.nonce,
                    id: id
                }, function(response) {
                    if (response.success) {
                        loadExistingEvents(widgetId);
                    } else {
                        alert('Error deleting event');
                    }
                });
            }
        }
        </script>
        
        <?php
    }
}
