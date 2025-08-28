// News & Events Widget JavaScript
jQuery(document).ready(function($) {
    
    // Initialize all widgets on page load
    $('.news-events-container').each(function() {
        const widgetId = $(this).attr('id');
        if (widgetId) {
            initializeWidget(widgetId);
        }
    });
    
    // Pagination click handler
    $(document).on('click', '.pagination-btn', function(e) {
        e.preventDefault();
        const page = parseInt($(this).data('page'));
        const widget = $(this).closest('.news-events-container');
        loadPage(widget, page);
    });
    
    // Tab switching
    $(document).on('click', '.tab-btn', function() {
        const tab = $(this).data('tab');
        const container = $(this).closest('.management-panel');
        
        container.find('.tab-btn').removeClass('active');
        $(this).addClass('active');
        
        container.find('.tab-content').removeClass('active');
        container.find('#' + tab + '-tab').addClass('active');
    });
    
    // Image upload handling
    $(document).on('click', '.upload-btn', function() {
        $(this).siblings('input[type="file"]').click();
    });
    
    $(document).on('change', 'input[type="file"]', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = $(this).siblings('.image-preview');
                preview.html('<img src="' + e.target.result + '" alt="Preview">');
            }.bind(this);
            reader.readAsDataURL(file);
        }
    });
    
    // Form submissions
    $(document).on('submit', '.news-form, .events-form', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const formData = new FormData(this);
        const submitBtn = form.find('.submit-btn');
        
        // Add action and nonce
        formData.append('action', 'save_news_event_item');
        formData.append('nonce', newsEventsAjax.nonce);
        
        // Show loading state
        submitBtn.prop('disabled', true).text('Saving...');
        
        $.ajax({
            url: newsEventsAjax.ajaxurl,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showMessage('Item saved successfully!', 'success');
                    form[0].reset();
                    form.find('.image-preview').empty();
                    
                    // Refresh the display
                    const widget = form.closest('.news-events-container');
                    refreshWidget(widget);
                } else {
                    showMessage('Error: ' + response.data, 'error');
                }
            },
            error: function() {
                showMessage('Failed to save item. Please try again.', 'error');
            },
            complete: function() {
                submitBtn.prop('disabled', false).text('Save Item');
            }
        });
    });
    
    // Delete item
    $(document).on('click', '.delete-item', function(e) {
        e.preventDefault();
        
        if (!confirm('Are you sure you want to delete this item?')) {
            return;
        }
        
        const button = $(this);
        const itemId = button.data('id');
        const widget = button.closest('.news-events-container');
        
        $.ajax({
            url: newsEventsAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'delete_news_event_item',
                nonce: newsEventsAjax.nonce,
                item_id: itemId
            },
            success: function(response) {
                if (response.success) {
                    showMessage('Item deleted successfully!', 'success');
                    refreshWidget(widget);
                } else {
                    showMessage('Error: ' + response.data, 'error');
                }
            },
            error: function() {
                showMessage('Failed to delete item. Please try again.', 'error');
            }
        });
    });
    
    // Edit item
    $(document).on('click', '.edit-item', function(e) {
        e.preventDefault();
        
        const button = $(this);
        const itemData = button.data();
        const form = button.closest('.news-events-container').find('.news-form, .events-form').first();
        
        // Populate form with item data
        Object.keys(itemData).forEach(key => {
            const input = form.find('[name="' + key + '"]');
            if (input.length) {
                if (input.attr('type') === 'checkbox') {
                    input.prop('checked', itemData[key] === 'yes' || itemData[key] === true);
                } else {
                    input.val(itemData[key]);
                }
            }
        });
        
        // Show image preview if exists
        if (itemData.image) {
            form.find('.image-preview').html('<img src="' + itemData.image + '" alt="Preview">');
        }
        
        // Add item ID to form for updating
        form.find('[name="item_id"]').remove();
        form.append('<input type="hidden" name="item_id" value="' + itemData.id + '">');
        
        // Switch to appropriate tab
        const tabType = itemData.type === 'event' ? 'events' : 'news';
        form.closest('.management-panel').find('.tab-btn[data-tab="' + tabType + '"]').click();
    });
    
    function loadPage(widget, page) {
        const gridItems = widget.find('.grid-items');
        const allItems = gridItems.children();
        const settings = widget.data('settings') || {};
        const itemsPerPage = parseInt(settings.items_per_page) || 6;
        
        // Calculate pagination
        const totalItems = allItems.length;
        const totalPages = Math.ceil(totalItems / itemsPerPage);
        const offset = (page - 1) * itemsPerPage;
        
        // Hide all items
        allItems.hide();
        
        // Show items for current page
        allItems.slice(offset, offset + itemsPerPage).show();
        
        // Update pagination
        updatePagination(widget, page, totalPages);
        
        // Update widget data
        widget.data('current-page', page);
        
        // Scroll to widget
        $('html, body').animate({
            scrollTop: widget.offset().top - 100
        }, 300);
    }
    
    function updatePagination(widget, currentPage, totalPages) {
        const pagination = widget.find('.pagination-wrapper');
        let html = '';
        
        if (totalPages <= 1) {
            pagination.parent().hide();
            return;
        }
        
        // Previous button
        if (currentPage > 1) {
            html += '<a href="#" class="pagination-btn" data-page="' + (currentPage - 1) + '">‹ Previous</a>';
        }
        
        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            const activeClass = i === currentPage ? ' active' : '';
            html += '<a href="#" class="pagination-btn' + activeClass + '" data-page="' + i + '">' + i + '</a>';
        }
        
        // Next button
        if (currentPage < totalPages) {
            html += '<a href="#" class="pagination-btn" data-page="' + (currentPage + 1) + '">Next ›</a>';
        }
        
        pagination.html(html);
        pagination.parent().show();
    }
    
    function initializeWidget(widgetId) {
        const widget = $('#' + widgetId);
        const settings = widget.data('settings') || {};
        
        // Initialize pagination
        if (settings.enable_pagination === 'yes') {
            const itemsPerPage = parseInt(settings.items_per_page) || 6;
            const totalItems = widget.find('.grid-items').children().length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            
            if (totalPages > 1) {
                loadPage(widget, 1);
                
                // Initialize auto-scroll if enabled
                if (settings.enable_auto_scroll === 'yes') {
                    initAutoScroll(widget, settings);
                }
            }
        }
    }
    
    function initAutoScroll(widget, settings) {
        const interval = parseInt(settings.auto_scroll_interval) * 1000 || 5000;
        let autoScrollTimer;
        
        function startAutoScroll() {
            autoScrollTimer = setInterval(function() {
                const currentPage = parseInt(widget.data('current-page')) || 1;
                const totalPages = Math.ceil(widget.find('.grid-items').children().length / (parseInt(settings.items_per_page) || 6));
                const nextPage = currentPage >= totalPages ? 1 : currentPage + 1;
                
                loadPage(widget, nextPage);
            }, interval);
        }
        
        function stopAutoScroll() {
            if (autoScrollTimer) {
                clearInterval(autoScrollTimer);
            }
        }
        
        // Start auto-scroll
        startAutoScroll();
        
        // Pause on hover
        widget.on('mouseenter', stopAutoScroll);
        widget.on('mouseleave', startAutoScroll);
        
        // Stop on manual pagination click
        widget.on('click', '.pagination-btn', function() {
            stopAutoScroll();
            setTimeout(startAutoScroll, interval); // Restart after delay
        });
    }
    
    function refreshWidget(widget) {
        // Reload widget content
        location.reload();
    }
    
    function showMessage(message, type) {
        const messageDiv = $('<div class="message message-' + type + '">' + message + '</div>');
        $('body').append(messageDiv);
        
        setTimeout(function() {
            messageDiv.fadeOut(300, function() {
                $(this).remove();
            });
        }, 3000);
    }
});
        if (file) {
            const reader = new FileReader();
            const preview = $(this).closest('.image-upload-section').find('.image-preview');
            
            reader.onload = function(e) {
                preview.html('<img src="' + e.target.result + '" alt="Preview">');
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Image URL preview
    $(document).on('blur', 'input[name="image_url"]', function() {
        const url = $(this).val();
        const preview = $(this).closest('.image-upload-section').find('.image-preview');
        
        if (url) {
            preview.html('<img src="' + url + '" alt="Preview" onerror="this.style.display=\'none\'">');
        }
    });
    
    // Form submissions
    $(document).on('submit', '.news-form', function(e) {
        e.preventDefault();
        submitNewsForm($(this));
    });
    
    $(document).on('submit', '.events-form', function(e) {
        e.preventDefault();
        submitEventForm($(this));
    });
    
    // Pagination
    $(document).on('click', '.pagination-btn', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        const container = $(this).closest('.news-events-container');
        const widgetId = container.attr('id');
        
        loadPage(widgetId, page);
    });
    
    // Load more button
    $(document).on('click', '.load-more-btn', function() {
        const container = $(this).closest('.news-events-container');
        const widgetId = container.attr('id');
        const currentPage = parseInt($(this).data('page')) || 1;
        
        loadMoreContent(widgetId, currentPage + 1);
    });
    
    // Carousel controls
    $(document).on('click', '.carousel-prev', function() {
        const carousel = $(this).closest('.carousel-container');
        moveCarousel(carousel, -1);
    });
    
    $(document).on('click', '.carousel-next', function() {
        const carousel = $(this).closest('.carousel-container');
        moveCarousel(carousel, 1);
    });
    
    // Edit/Delete actions
    $(document).on('click', '.edit-btn', function() {
        const itemId = $(this).data('id');
        const type = $(this).data('type');
        editItem(itemId, type);
    });
    
    $(document).on('click', '.delete-btn', function() {
        const itemId = $(this).data('id');
        const type = $(this).data('type');
        
        if (confirm('Are you sure you want to delete this ' + type + '?')) {
            deleteItem(itemId, type);
        }
    });
    
    // Read More button functionality
    $(document).on('click', '.read-more-btn', function(e) {
        e.preventDefault();
        
        const itemIndex = $(this).data('item-index');
        const widgetId = $(this).data('widget-id');
        const button = $(this);
        
        button.text('Creating Page...').prop('disabled', true);
        
        $.ajax({
            url: newsEventsAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'create_read_more_page',
                item_index: itemIndex,
                widget_id: widgetId,
                nonce: newsEventsAjax.nonce
            },
            success: function(response) {
                if (response.success) {
                    window.open(response.data.page_url, '_blank');
                    button.text('Read More').prop('disabled', false);
                } else {
                    alert('Error creating page: ' + response.data);
                    button.text('Read More').prop('disabled', false);
                }
            },
            error: function() {
                alert('Failed to create page. Please try again.');
                button.text('Read More').prop('disabled', false);
            }
        });
    });
});

// Initialize widget functionality
function initializeWidget(widgetId) {
    const container = $('#' + widgetId);
    const settings = container.data('settings') || {};
    
    // Load initial content
    loadContentForWidget(widgetId, settings);
    
    // Setup infinite scroll if enabled
    if (settings.pagination_type === 'infinite') {
        setupInfiniteScroll(widgetId);
    }
    
    // Setup carousel auto-play if needed
    if (settings.template_style === 'template_1') {
        setupCarouselAutoPlay(container);
    }
}

// Load content for widget
function loadContentForWidget(widgetId, settings) {
    const container = $('#' + widgetId);
    const contentDisplay = container.find('.content-display');
    
    // Show loading state
    contentDisplay.html('<div class="loading">Loading content...</div>');
    
    // AJAX call to load content
    $.ajax({
        url: newsEventsAjax.ajaxurl,
        type: 'POST',
        data: {
            action: 'load_news_events_content',
            widget_id: widgetId,
            settings: settings,
            nonce: newsEventsAjax.nonce
        },
        success: function(response) {
            if (response.success) {
                renderContent(container, response.data, settings);
                setupPagination(container, response.data.pagination, settings);
            } else {
                contentDisplay.html('<div class="error">Error loading content: ' + response.data + '</div>');
            }
        },
        error: function() {
            contentDisplay.html('<div class="error">Failed to load content. Please try again.</div>');
        }
    });
}

// Render content based on template
function renderContent(container, data, settings) {
    const template = settings.template_style || 'template_2';
    const contentDisplay = container.find('.content-display');
    
    switch (template) {
        case 'template_1':
            renderCarouselSidebar(contentDisplay, data, settings);
            break;
        case 'template_2':
            renderGrid(contentDisplay, data, settings);
            break;
        case 'template_3':
            renderListSidebar(contentDisplay, data, settings);
            break;
        case 'template_4':
            renderMagazine(contentDisplay, data, settings);
            break;
        case 'template_5':
            renderCardStack(contentDisplay, data, settings);
            break;
        default:
            renderGrid(contentDisplay, data, settings);
    }
    
    // Apply image hover effects
    if (settings.image_hover_effect && settings.image_hover_effect !== 'none') {
        contentDisplay.addClass('image-hover-' + settings.image_hover_effect);
    }
}

// Render grid template
function renderGrid(container, data, settings) {
    const items = [...(data.news || []), ...(data.events || [])];
    const columns = settings.columns || 3;
    
    let html = '<div class="template-grid" data-columns="' + columns + '">';
    html += '<div class="grid-items">';
    
    items.forEach(item => {
        html += renderItem(item, settings);
    });
    
    html += '</div></div>';
    container.html(html);
}

// Render individual item
function renderItem(item, settings) {
    const isEvent = item.type === 'event';
    const itemClass = isEvent ? 'event-item' : 'news-item';
    
    let html = '<div class="' + itemClass + '">';
    
    // Image
    if (settings.show_image !== 'no' && item.image) {
        html += '<div class="item-image">';
        html += '<img src="' + item.image + '" alt="' + item.title + '">';
        html += '</div>';
    }
    
    html += '<div class="item-content">';
    
    // Title
    html += '<h3 class="item-title">' + item.title + '</h3>';
    
    // Meta information
    if (settings.show_author === 'yes' || settings.show_date === 'yes' || settings.show_comments === 'yes') {
        html += '<div class="item-meta">';
        
        if (settings.show_author === 'yes' && item.author) {
            html += '<span class="item-author">👤 ' + item.author + '</span>';
        }
        
        if (settings.show_date === 'yes' && item.date) {
            html += '<span class="item-date">📅 ' + formatDate(item.date) + '</span>';
        }
        
        if (settings.show_comments === 'yes' && item.comments_count) {
            html += '<span class="item-comments">💬 ' + item.comments_count + '</span>';
        }
        
        html += '</div>';
    }
    
    // Content/Description
    if (item.excerpt) {
        html += '<div class="item-excerpt">' + item.excerpt + '</div>';
    }
    
    // Category
    if (item.category) {
        html += '<span class="item-category">' + item.category + '</span>';
    }
    
    // Event specific info
    if (isEvent) {
        if (item.location) {
            html += '<div class="event-location">📍 ' + item.location + '</div>';
        }
        if (item.event_date) {
            html += '<div class="event-date">🗓️ ' + formatDate(item.event_date) + '</div>';
        }
    }
    
    html += '</div></div>';
    
    return html;
}

// Setup pagination
function setupPagination(container, paginationData, settings) {
    if (settings.enable_pagination !== 'yes' || !paginationData) return;
    
    const paginationContainer = container.find('.pagination-container');
    const wrapper = paginationContainer.find('.pagination-wrapper');
    
    if (paginationData.total_pages <= 1) {
        paginationContainer.hide();
        return;
    }
    
    let html = '';
    const currentPage = paginationData.current_page;
    const totalPages = paginationData.total_pages;
    const type = settings.pagination_type || 'numbers';
    
    switch (type) {
        case 'numbers':
            html = renderNumberPagination(currentPage, totalPages);
            break;
        case 'prev_next':
            html = renderPrevNextPagination(currentPage, totalPages);
            break;
        case 'load_more':
            if (currentPage < totalPages) {
                html = '<button class="load-more-btn" data-page="' + currentPage + '">Load More</button>';
            }
            break;
    }
    
    wrapper.html(html);
    paginationContainer.show();
}

// Render number pagination
function renderNumberPagination(current, total) {
    let html = '';
    
    // Previous button
    if (current > 1) {
        html += '<a href="#" class="pagination-btn" data-page="' + (current - 1) + '">‹ Previous</a>';
    }
    
    // Page numbers
    const start = Math.max(1, current - 2);
    const end = Math.min(total, current + 2);
    
    if (start > 1) {
        html += '<a href="#" class="pagination-btn" data-page="1">1</a>';
        if (start > 2) html += '<span class="pagination-dots">...</span>';
    }
    
    for (let i = start; i <= end; i++) {
        const activeClass = i === current ? ' active' : '';
        html += '<a href="#" class="pagination-btn' + activeClass + '" data-page="' + i + '">' + i + '</a>';
    }
    
    if (end < total) {
        if (end < total - 1) html += '<span class="pagination-dots">...</span>';
        html += '<a href="#" class="pagination-btn" data-page="' + total + '">' + total + '</a>';
    }
    
    // Next button
    if (current < total) {
        html += '<a href="#" class="pagination-btn" data-page="' + (current + 1) + '">Next ›</a>';
    }
    
    return html;
}

// Submit news form
function submitNewsForm(form) {
    const formData = new FormData(form[0]);
    formData.append('action', 'add_news_item');
    formData.append('nonce', newsEventsAjax.nonce);
    
    $.ajax({
        url: newsEventsAjax.ajaxurl,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                form[0].reset();
                form.find('.image-preview').empty();
                alert('News item added successfully!');
                // Reload the content
                const widgetId = form.closest('.news-events-container').attr('id');
                const settings = form.closest('.news-events-container').data('settings') || {};
                loadContentForWidget(widgetId, settings);
            } else {
                alert('Error: ' + response.data);
            }
        },
        error: function() {
            alert('Failed to add news item. Please try again.');
        }
    });
}

// Submit event form
function submitEventForm(form) {
    const formData = new FormData(form[0]);
    formData.append('action', 'add_event_item');
    formData.append('nonce', newsEventsAjax.nonce);
    
    $.ajax({
        url: newsEventsAjax.ajaxurl,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                form[0].reset();
                form.find('.image-preview').empty();
                alert('Event added successfully!');
                // Reload the content
                const widgetId = form.closest('.news-events-container').attr('id');
                const settings = form.closest('.news-events-container').data('settings') || {};
                loadContentForWidget(widgetId, settings);
            } else {
                alert('Error: ' + response.data);
            }
        },
        error: function() {
            alert('Failed to add event. Please try again.');
        }
    });
}

// Submit quick add form
function submitQuickAdd(form) {
    const formData = new FormData(form[0]);
    formData.append('action', 'quick_add_content');
    formData.append('nonce', newsEventsAjax.nonce);
    
    const submitBtn = form.find('.add-btn');
    const originalText = submitBtn.text();
    submitBtn.text('Adding...').prop('disabled', true);
    
    $.ajax({
        url: newsEventsAjax.ajaxurl,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                form[0].reset();
                $('.event-fields').hide();
                alert('Content added successfully!');
                
                // Reload the content
                const widgetId = form.data('widget-id');
                const container = $('#' + widgetId);
                const settings = container.data('settings') || {};
                loadContentForWidget(widgetId, settings);
            } else {
                alert('Error: ' + response.data);
            }
        },
        error: function() {
            alert('Failed to add content. Please try again.');
        },
        complete: function() {
            submitBtn.text(originalText).prop('disabled', false);
        }
    });
}

// Utility functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
}

function clearForm(button) {
    const form = $(button).closest('form')[0];
    form.reset();
    $(form).find('.image-preview').empty();
}

function moveCarousel(carousel, direction) {
    const items = carousel.find('.carousel-items');
    const itemWidth = carousel.width();
    const currentTransform = parseInt(items.css('transform').split(',')[4]) || 0;
    const newTransform = currentTransform + (direction * itemWidth);
    
    items.css('transform', 'translateX(' + newTransform + 'px)');
}

function setupInfiniteScroll(widgetId) {
    $(window).scroll(function() {
        const container = $('#' + widgetId);
        const containerBottom = container.offset().top + container.outerHeight();
        const windowBottom = $(window).scrollTop() + $(window).height();
        
        if (windowBottom > containerBottom - 100) {
            // Load more content
            const currentPage = container.data('current-page') || 1;
            loadMoreContent(widgetId, currentPage + 1);
        }
    });
}

function loadPage(widgetId, page) {
    const container = $('#' + widgetId);
    const settings = container.data('settings') || {};
    settings.page = page;
    
    loadContentForWidget(widgetId, settings);
}

function loadMoreContent(widgetId, page) {
    const container = $('#' + widgetId);
    const settings = container.data('settings') || {};
    
    $.ajax({
        url: newsEventsAjax.ajaxurl,
        type: 'POST',
        data: {
            action: 'load_more_content',
            widget_id: widgetId,
            page: page,
            settings: settings,
            nonce: newsEventsAjax.nonce
        },
        success: function(response) {
            if (response.success) {
                const contentDisplay = container.find('.content-display .grid-items, .content-display .list-items');
                response.data.items.forEach(item => {
                    contentDisplay.append(renderItem(item, settings));
                });
                
                container.data('current-page', page);
                
                if (page >= response.data.pagination.total_pages) {
                    container.find('.load-more-btn').hide();
                }
            }
        }
    });
}
