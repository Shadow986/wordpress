// Enhanced News & Events Frontend JavaScript

function loadContentForWidget(widgetId, settings) {
    const container = jQuery('#' + widgetId);
    
    // Initialize management panel if enabled
    if (settings.show_management === 'yes') {
        initManagementPanel(widgetId);
    }
    
    // Load content based on type
    if (settings.content_type === 'news' || settings.content_type === 'both') {
        loadNewsContent(widgetId, settings);
    }
    
    if (settings.content_type === 'events' || settings.content_type === 'both') {
        loadEventsContent(widgetId, settings);
    }
    
    // Initialize template-specific functionality
    initTemplate(widgetId, settings.template_style);
}

function initManagementPanel(widgetId) {
    const container = jQuery('#' + widgetId);
    
    // Tab switching
    container.find('.tab-btn').on('click', function() {
        const tabName = jQuery(this).data('tab');
        
        container.find('.tab-btn').removeClass('active');
        jQuery(this).addClass('active');
        
        container.find('.tab-content').removeClass('active');
        container.find('#' + tabName + '-tab').addClass('active');
    });
    
    // News form submission
    container.find('.news-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            action: 'save_news_item',
            nonce: newsEventsAjax.nonce,
            title: jQuery(this).find('[name="title"]').val(),
            content: jQuery(this).find('[name="content"]').val(),
            author: jQuery(this).find('[name="author"]').val(),
            image_url: jQuery(this).find('[name="image_url"]').val(),
            source_url: jQuery(this).find('[name="source_url"]').val(),
            category: jQuery(this).find('[name="category"]').val()
        };
        
        jQuery.post(newsEventsAjax.ajaxurl, formData, function(response) {
            if (response.success) {
                alert('News item saved successfully!');
                jQuery(this).find('.news-form')[0].reset();
                loadNewsContent(widgetId, {});
            } else {
                alert('Error: ' + response.data);
            }
        });
    });
    
    // Events form submission
    container.find('.events-form').on('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            action: 'save_event_item',
            nonce: newsEventsAjax.nonce,
            title: jQuery(this).find('[name="title"]').val(),
            description: jQuery(this).find('[name="description"]').val(),
            event_date: jQuery(this).find('[name="event_date"]').val(),
            end_date: jQuery(this).find('[name="end_date"]').val(),
            location: jQuery(this).find('[name="location"]').val(),
            image_url: jQuery(this).find('[name="image_url"]').val(),
            category: jQuery(this).find('[name="category"]').val()
        };
        
        jQuery.post(newsEventsAjax.ajaxurl, formData, function(response) {
            if (response.success) {
                alert('Event saved successfully!');
                jQuery(this).find('.events-form')[0].reset();
                loadEventsContent(widgetId, {});
            } else {
                alert('Error: ' + response.data);
            }
        });
    });
}

function loadNewsContent(widgetId, settings) {
    jQuery.get(newsEventsAjax.ajaxurl, {
        action: 'get_news_items',
        limit: settings.news_count || 6,
        category: settings.category_filter || ''
    }, function(response) {
        if (response.success) {
            renderNewsItems(widgetId, response.data, settings);
        }
    });
}

function loadEventsContent(widgetId, settings) {
    jQuery.get(newsEventsAjax.ajaxurl, {
        action: 'get_event_items',
        limit: settings.events_count || 4,
        upcoming: true
    }, function(response) {
        if (response.success) {
            renderEventItems(widgetId, response.data, settings);
        }
    });
}

function renderNewsItems(widgetId, items, settings) {
    const container = jQuery('#' + widgetId);
    const template = settings.template_style || 'template_1';
    
    let html = '';
    
    items.forEach(function(item, index) {
        const imageHtml = (settings.show_image !== 'no' && item.image_url) ? 
            `<img src="${item.image_url}" alt="${item.title}" class="item-image">` : '';
        
        const authorHtml = settings.show_author === 'yes' ? 
            `<span class="meta-author">${item.author || 'Admin'}</span>` : '';
        
        const dateHtml = settings.show_date === 'yes' ? 
            `<span class="meta-date">${new Date(item.published_date).toLocaleDateString()}</span>` : '';
        
        const commentsHtml = settings.show_comments === 'yes' ? 
            `<span class="meta-comments">${Math.floor(Math.random() * 20)} comments</span>` : '';
        
        const metaHtml = `<div class="item-meta">${authorHtml}${dateHtml}${commentsHtml}</div>`;
        
        switch (template) {
            case 'template_1':
                if (index === 0) {
                    html += `
                        <div class="carousel-item">
                            ${imageHtml}
                            <h3 class="item-title">${item.title}</h3>
                            <div class="item-content">${item.content}</div>
                            ${metaHtml}
                        </div>
                    `;
                }
                break;
                
            case 'template_2':
                html += `
                    <div class="grid-item news-item">
                        ${imageHtml}
                        <div class="item-body">
                            <h3 class="item-title">${item.title}</h3>
                            <div class="item-content">${item.content.substring(0, 150)}...</div>
                            ${metaHtml}
                        </div>
                    </div>
                `;
                break;
                
            case 'template_3':
                html += `
                    <div class="list-item news-item">
                        ${imageHtml}
                        <div class="item-body">
                            <h3 class="item-title">${item.title}</h3>
                            <div class="item-content">${item.content.substring(0, 200)}...</div>
                            ${metaHtml}
                        </div>
                    </div>
                `;
                break;
                
            case 'template_4':
                if (index === 0) {
                    html += `
                        <div class="main-feature">
                            <div class="gradient-overlay"></div>
                            <h2 class="item-title">${item.title}</h2>
                            <div class="item-content">${item.content.substring(0, 200)}...</div>
                            ${metaHtml}
                        </div>
                    `;
                } else if (index <= 2) {
                    html += `
                        <div class="sub-feature">
                            <h3 class="item-title">${item.title}</h3>
                            <div class="item-content">${item.content.substring(0, 100)}...</div>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="regular-item news-item">
                            ${imageHtml}
                            <h3 class="item-title">${item.title}</h3>
                            <div class="item-content">${item.content.substring(0, 120)}...</div>
                            ${metaHtml}
                        </div>
                    `;
                }
                break;
                
            case 'template_5':
                html += `
                    <div class="stacked-card news-item">
                        ${imageHtml}
                        <h3 class="item-title">${item.title}</h3>
                        <div class="item-content">${item.content}</div>
                        ${metaHtml}
                    </div>
                `;
                break;
        }
    });
    
    // Insert content into appropriate containers
    switch (template) {
        case 'template_1':
            container.find('.carousel-items').html(html);
            container.find('.featured-item').html(items[0] ? `
                <h3>${items[0].title}</h3>
                <p>${items[0].content.substring(0, 100)}...</p>
            ` : '');
            break;
        case 'template_2':
            container.find('.grid-items').html(html);
            break;
        case 'template_3':
            container.find('.list-items').html(html);
            break;
        case 'template_4':
            container.find('.main-feature').html(html.split('</div>')[0] + '</div>');
            container.find('.sub-features').html(html.split('sub-feature').slice(1, 3).join('sub-feature'));
            container.find('.regular-items').html(html.split('regular-item').slice(1).join('regular-item'));
            break;
        case 'template_5':
            container.find('.stacked-cards').html(html);
            break;
    }
}

function renderEventItems(widgetId, items, settings) {
    const container = jQuery('#' + widgetId);
    // Similar rendering logic for events
    // Implementation would follow same pattern as news items
}

function initTemplate(widgetId, template) {
    const container = jQuery('#' + widgetId);
    
    switch (template) {
        case 'template_1':
            initCarousel(widgetId);
            break;
        case 'template_5':
            initCardStack(widgetId);
            break;
    }
}

function initCarousel(widgetId) {
    const container = jQuery('#' + widgetId);
    let currentIndex = 0;
    
    container.find('.carousel-next').on('click', function() {
        const items = container.find('.carousel-item');
        currentIndex = (currentIndex + 1) % items.length;
        updateCarousel(container, currentIndex);
    });
    
    container.find('.carousel-prev').on('click', function() {
        const items = container.find('.carousel-item');
        currentIndex = (currentIndex - 1 + items.length) % items.length;
        updateCarousel(container, currentIndex);
    });
    
    // Auto-advance carousel
    setInterval(function() {
        const items = container.find('.carousel-item');
        if (items.length > 1) {
            currentIndex = (currentIndex + 1) % items.length;
            updateCarousel(container, currentIndex);
        }
    }, 5000);
}

function updateCarousel(container, index) {
    const items = container.find('.carousel-items');
    items.css('transform', `translateX(-${index * 100}%)`);
}

function initCardStack(widgetId) {
    const container = jQuery('#' + widgetId);
    
    container.find('.stacked-card').on('mouseenter', function() {
        jQuery(this).css('z-index', 100);
    }).on('mouseleave', function() {
        jQuery(this).css('z-index', 'auto');
    });
}

// Auto-refresh content every 5 minutes
jQuery(document).ready(function($) {
    setInterval(function() {
        $('.news-events-container').each(function() {
            const widgetId = $(this).attr('id');
            if (widgetId) {
                // Reload content for each widget
                loadContentForWidget(widgetId, {
                    content_type: 'both',
                    news_count: 6,
                    events_count: 4
                });
            }
        });
    }, 300000); // 5 minutes
});
