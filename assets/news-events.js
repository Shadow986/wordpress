// News & Events Frontend JavaScript

function loadNewsItems(widgetId, options) {
    const container = jQuery('#' + widgetId);
    const loadingEl = container.find('.news-loading');
    const itemsEl = container.find('.news-items');
    
    loadingEl.show();
    
    jQuery.get(newsEventsAjax.ajaxurl, {
        action: 'get_news_items',
        limit: options.limit || 6,
        category: options.category || ''
    }, function(response) {
        loadingEl.hide();
        
        if (response.success && response.data.length > 0) {
            let html = '';
            
            response.data.forEach(function(item) {
                const imageHtml = item.image_url ? 
                    `<div class="news-image"><img src="${item.image_url}" alt="${item.title}"></div>` : '';
                
                const sourceHtml = item.source_url ? 
                    `<a href="${item.source_url}" target="_blank" class="source-link">Read Full Article</a>` : '';
                
                const categoryHtml = item.category ? 
                    `<span class="news-category">${item.category}</span>` : '';
                
                const publishedDate = new Date(item.published_date).toLocaleDateString();
                
                if (options.layout === 'list') {
                    html += `
                        <div class="news-item news-item-list">
                            ${imageHtml}
                            <div class="news-content">
                                ${categoryHtml}
                                <h3>${item.title}</h3>
                                <div class="content">${item.content}</div>
                                <div class="news-meta">
                                    <span class="date">${publishedDate}</span>
                                    ${sourceHtml}
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="news-item news-item-card">
                            ${imageHtml}
                            <div class="news-content">
                                ${categoryHtml}
                                <h3>${item.title}</h3>
                                <div class="content">${item.content.substring(0, 150)}...</div>
                                <div class="news-meta">
                                    <span class="date">${publishedDate}</span>
                                    ${sourceHtml}
                                </div>
                            </div>
                        </div>
                    `;
                }
            });
            
            itemsEl.html(html);
            
            if (options.layout === 'carousel') {
                initNewsCarousel(widgetId);
            }
            
        } else {
            itemsEl.html('<p class="no-news">No news items found.</p>');
        }
    }).fail(function() {
        loadingEl.hide();
        itemsEl.html('<p class="error">Error loading news items.</p>');
    });
}

function loadEventItems(widgetId, options) {
    const container = jQuery('#' + widgetId);
    const loadingEl = container.find('.events-loading');
    const itemsEl = container.find('.events-items');
    
    loadingEl.show();
    
    jQuery.get(newsEventsAjax.ajaxurl, {
        action: 'get_event_items',
        limit: options.limit || 6,
        category: options.category || '',
        upcoming: options.upcoming || false
    }, function(response) {
        loadingEl.hide();
        
        if (response.success && response.data.length > 0) {
            let html = '';
            
            response.data.forEach(function(item) {
                const eventDate = new Date(item.event_date);
                const endDate = item.end_date ? new Date(item.end_date) : null;
                const now = new Date();
                const isUpcoming = eventDate > now;
                
                const imageHtml = item.image_url ? 
                    `<div class="event-image"><img src="${item.image_url}" alt="${item.title}"></div>` : '';
                
                const categoryHtml = item.category ? 
                    `<span class="event-category">${item.category}</span>` : '';
                
                const locationHtml = item.location ? 
                    `<div class="event-location"><i class="location-icon"></i> ${item.location}</div>` : '';
                
                const endDateHtml = endDate ? 
                    ` - ${endDate.toLocaleDateString()} ${endDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}` : '';
                
                const statusClass = isUpcoming ? 'upcoming' : 'past';
                
                if (options.layout === 'timeline') {
                    html += `
                        <div class="event-item event-item-timeline ${statusClass}">
                            <div class="event-date">
                                <div class="date-day">${eventDate.getDate()}</div>
                                <div class="date-month">${eventDate.toLocaleDateString('en', {month: 'short'})}</div>
                            </div>
                            <div class="event-content">
                                ${categoryHtml}
                                <h3>${item.title}</h3>
                                <div class="description">${item.description}</div>
                                <div class="event-time">${eventDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}${endDateHtml}</div>
                                ${locationHtml}
                            </div>
                        </div>
                    `;
                } else if (options.layout === 'list') {
                    html += `
                        <div class="event-item event-item-list ${statusClass}">
                            ${imageHtml}
                            <div class="event-content">
                                ${categoryHtml}
                                <h3>${item.title}</h3>
                                <div class="description">${item.description}</div>
                                <div class="event-meta">
                                    <div class="event-time">${eventDate.toLocaleDateString()} ${eventDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}${endDateHtml}</div>
                                    ${locationHtml}
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    html += `
                        <div class="event-item event-item-card ${statusClass}">
                            ${imageHtml}
                            <div class="event-date">
                                <div class="date-day">${eventDate.getDate()}</div>
                                <div class="date-month">${eventDate.toLocaleDateString('en', {month: 'short'})}</div>
                            </div>
                            <div class="event-content">
                                ${categoryHtml}
                                <h3>${item.title}</h3>
                                <div class="description">${item.description.substring(0, 100)}...</div>
                                <div class="event-time">${eventDate.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}${endDateHtml}</div>
                                ${locationHtml}
                            </div>
                        </div>
                    `;
                }
            });
            
            itemsEl.html(html);
            
        } else {
            itemsEl.html('<p class="no-events">No events found.</p>');
        }
    }).fail(function() {
        loadingEl.hide();
        itemsEl.html('<p class="error">Error loading events.</p>');
    });
}

function initNewsCarousel(widgetId) {
    const container = jQuery('#' + widgetId + ' .news-items');
    
    // Simple carousel implementation
    container.addClass('news-carousel');
    
    let currentIndex = 0;
    const items = container.find('.news-item');
    const itemCount = items.length;
    
    if (itemCount <= 1) return;
    
    // Add navigation buttons
    container.append(`
        <button class="carousel-prev" onclick="moveCarousel('${widgetId}', -1)">‹</button>
        <button class="carousel-next" onclick="moveCarousel('${widgetId}', 1)">›</button>
    `);
    
    // Auto-rotate every 5 seconds
    setInterval(function() {
        moveCarousel(widgetId, 1);
    }, 5000);
}

function moveCarousel(widgetId, direction) {
    const container = jQuery('#' + widgetId + ' .news-items');
    const items = container.find('.news-item');
    const itemCount = items.length;
    
    let currentIndex = parseInt(container.data('current-index') || 0);
    currentIndex = (currentIndex + direction + itemCount) % itemCount;
    
    items.hide();
    items.eq(currentIndex).show();
    
    container.data('current-index', currentIndex);
}

// Auto-refresh news and events every 5 minutes
jQuery(document).ready(function($) {
    setInterval(function() {
        $('.news-display-widget').each(function() {
            const widgetId = $(this).attr('id');
            if (widgetId && widgetId.includes('news-display')) {
                // Reload news items
                loadNewsItems(widgetId, {
                    limit: 6,
                    layout: 'grid'
                });
            }
        });
        
        $('.events-display-widget').each(function() {
            const widgetId = $(this).attr('id');
            if (widgetId && widgetId.includes('events-display')) {
                // Reload event items
                loadEventItems(widgetId, {
                    limit: 6,
                    upcoming: true,
                    layout: 'grid'
                });
            }
        });
    }, 300000); // 5 minutes
});
