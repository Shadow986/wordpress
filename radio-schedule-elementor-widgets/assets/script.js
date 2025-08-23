/**
 * Radio Schedule Elementor Widgets - JavaScript
 */

(function($) {
    'use strict';
    
    // Radio Schedule Display functionality
    class RadioScheduleDisplay {
        constructor(container) {
            this.container = $(container);
            this.currentDay = this.getCurrentDay();
            this.updateInterval = null;
            this.retryCount = 0;
            this.maxRetries = 3;
            
            this.init();
        }
        
        init() {
            this.bindEvents();
            this.loadShows(this.currentDay);
            this.startAutoUpdate();
            this.setActiveDay();
        }
        
        bindEvents() {
            this.container.on('click', '.day-btn', (e) => {
                const day = $(e.target).data('day');
                this.switchDay(day);
            });
        }
        
        getCurrentDay() {
            const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            return days[new Date().getDay()];
        }
        
        setActiveDay() {
            this.container.find('.day-btn').removeClass('active');
            this.container.find(`.day-btn[data-day="${this.currentDay}"]`).addClass('active');
        }
        
        switchDay(day) {
            this.currentDay = day;
            this.setActiveDay();
            this.loadShows(day);
        }
        
        loadShows(day = null) {
            if (!day) day = this.currentDay;
            
            this.showLoading();
            
            if (typeof radio_ajax === 'undefined') {
                console.error('radio_ajax is not defined');
                this.showError('Configuration error. Please check plugin setup.');
                return;
            }
            
            $.ajax({
                url: radio_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_radio_shows',
                    day: day,
                    nonce: radio_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.renderShows(response.data);
                        this.retryCount = 0;
                    } else {
                        console.error('AJAX Error:', response);
                        this.showError('Failed to load shows. Please try again.');
                    }
                },
                error: (xhr, status, error) => {
                    console.error('AJAX Error:', error, xhr);
                    if (this.retryCount < this.maxRetries) {
                        this.retryCount++;
                        setTimeout(() => this.loadShows(day), 2000);
                    } else {
                        this.showError('Unable to connect to server. Please check your internet connection.');
                    }
                }
            });
        }
        
        renderShows(data) {
            this.renderCurrentShows(data.current || []);
            this.renderUpcomingShows(data.upcoming || []);
            this.updateLiveIndicator(data.current && data.current.length > 0);
        }
        
        renderCurrentShows(shows) {
            const container = this.container.find('#current-shows');
            
            if (!shows || shows.length === 0) {
                container.html(this.getNoShowsHTML('current'));
                return;
            }
            
            let html = '';
            shows.forEach(show => {
                html += this.getShowCardHTML(show, true);
            });
            
            container.html(html);
        }
        
        renderUpcomingShows(shows) {
            const container = this.container.find('#upcoming-shows');
            
            if (!shows || shows.length === 0) {
                container.html(this.getNoShowsHTML('upcoming'));
                return;
            }
            
            let html = '';
            shows.forEach(show => {
                html += this.getUpcomingCardHTML(show);
            });
            
            container.html(html);
        }
        
        getShowCardHTML(show, isLive = false) {
            const liveBadge = isLive ? '<div class="live-badge">🔴 LIVE</div>' : '';
            
            return `
                <div class="show-card ${isLive ? 'live-show' : ''}">
                    <div class="show-image-container">
                        <img src="${show.image}" alt="${show.title}" class="show-image" loading="lazy">
                        ${liveBadge}
                    </div>
                    <div class="show-content">
                        <h4 class="show-title">${show.title}</h4>
                        <p class="show-host">with ${show.host}</p>
                        <div class="show-time">${show.formatted_time}</div>
                        ${show.description ? `<p class="show-description">${show.description}</p>` : ''}
                    </div>
                </div>
            `;
        }
        
        getUpcomingCardHTML(show) {
            return `
                <div class="upcoming-card">
                    <div class="upcoming-content">
                        <h4 class="show-title">${show.title}</h4>
                        <p class="show-host">with ${show.host}</p>
                        <span class="time-badge">${show.formatted_time}</span>
                        ${show.description ? `<p class="show-description">${show.description}</p>` : ''}
                    </div>
                </div>
            `;
        }
        
        getNoShowsHTML(type) {
            const messages = {
                current: {
                    icon: '🎵',
                    title: 'No Live Shows',
                    message: 'No shows are currently broadcasting. Check back later!'
                },
                upcoming: {
                    icon: '⏰',
                    title: 'No Upcoming Shows',
                    message: 'No more shows scheduled for today.'
                }
            };
            
            const msg = messages[type] || messages.current;
            
            return `
                <div class="no-shows">
                    <div class="no-shows-icon">${msg.icon}</div>
                    <h3>${msg.title}</h3>
                    <p>${msg.message}</p>
                </div>
            `;
        }
        
        showLoading() {
            this.container.find('#current-shows').html(`
                <div class="loading-state">
                    <div class="loading-spinner"></div>
                    <p>Loading shows...</p>
                </div>
            `);
            
            this.container.find('#upcoming-shows').html(`
                <div class="loading-state">
                    <div class="loading-spinner"></div>
                    <p>Loading upcoming shows...</p>
                </div>
            `);
        }
        
        showError(message) {
            const errorHTML = `
                <div class="error-state">
                    <h3>⚠️ Error Loading Shows</h3>
                    <p>${message}</p>
                </div>
            `;
            
            this.container.find('#current-shows').html(errorHTML);
            this.container.find('#upcoming-shows').html('');
        }
        
        updateLiveIndicator(hasLiveShows) {
            const indicator = this.container.find('.live-indicator');
            if (hasLiveShows) {
                indicator.show();
            } else {
                indicator.hide();
            }
        }
        
        startAutoUpdate() {
            this.updateInterval = setInterval(() => {
                if (this.currentDay === this.getCurrentDay()) {
                    this.loadShows(this.currentDay);
                }
            }, 120000); // 2 minutes
        }
        
        destroy() {
            if (this.updateInterval) {
                clearInterval(this.updateInterval);
            }
        }
    }
    
    // Radio Show Manager functionality
    class RadioShowManager {
        constructor(container) {
            this.container = $(container);
            this.currentEditId = null;
            
            this.init();
        }
        
        init() {
            this.bindEvents();
            this.loadExistingShows();
        }
        
        bindEvents() {
            this.container.on('submit', '#radio-show-form', (e) => {
                e.preventDefault();
                this.saveShow();
            });
            
            this.container.on('click', '.btn-edit', (e) => {
                const showId = $(e.target).data('show-id');
                this.editShow(showId);
            });
            
            this.container.on('click', '.btn-delete', (e) => {
                const showId = $(e.target).data('show-id');
                if (confirm('Are you sure you want to delete this show?')) {
                    this.deleteShow(showId);
                }
            });
        }
        
        saveShow() {
            const form = this.container.find('#radio-show-form');
            const formData = new FormData(form[0]);
            
            // Get selected days
            const selectedDays = [];
            form.find('input[name="days[]"]:checked').each(function() {
                selectedDays.push($(this).val());
            });
            
            const data = {
                action: 'save_radio_show',
                nonce: radio_ajax.nonce,
                title: formData.get('title'),
                host: formData.get('host'),
                start_time: formData.get('start_time'),
                end_time: formData.get('end_time'),
                days: selectedDays.join(','),
                description: formData.get('description'),
                image_url: formData.get('image_url'),
                show_id: this.currentEditId
            };
            
            $.ajax({
                url: radio_ajax.ajax_url,
                type: 'POST',
                data: data,
                success: (response) => {
                    if (response.success) {
                        alert('Show saved successfully!');
                        form[0].reset();
                        this.currentEditId = null;
                        this.loadExistingShows();
                    } else {
                        alert('Error saving show: ' + response.data);
                    }
                },
                error: (xhr, status, error) => {
                    alert('Error saving show. Please try again.');
                    console.error('Save error:', error);
                }
            });
        }
        
        editShow(showId) {
            // This would load show data into the form
            // For now, we'll just set the edit ID
            this.currentEditId = showId;
            alert('Edit functionality would load show data into the form');
        }
        
        deleteShow(showId) {
            $.ajax({
                url: radio_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'delete_radio_show',
                    nonce: radio_ajax.nonce,
                    show_id: showId
                },
                success: (response) => {
                    if (response.success) {
                        alert('Show deleted successfully!');
                        this.loadExistingShows();
                    } else {
                        alert('Error deleting show: ' + response.data);
                    }
                },
                error: (xhr, status, error) => {
                    alert('Error deleting show. Please try again.');
                    console.error('Delete error:', error);
                }
            });
        }
        
        loadExistingShows() {
            const container = this.container.find('#shows-list');
            
            $.ajax({
                url: radio_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'get_radio_shows',
                    day: 'all',
                    nonce: radio_ajax.nonce
                },
                success: (response) => {
                    if (response.success && response.data.all_shows) {
                        this.renderExistingShows(response.data.all_shows);
                    } else {
                        container.html('<p>No shows found.</p>');
                    }
                },
                error: (xhr, status, error) => {
                    container.html('<p>Error loading shows.</p>');
                    console.error('Load error:', error);
                }
            });
        }
        
        renderExistingShows(shows) {
            const container = this.container.find('#shows-list');
            
            if (!shows || shows.length === 0) {
                container.html('<p>No shows found. Create your first show above!</p>');
                return;
            }
            
            let html = '';
            shows.forEach(show => {
                html += `
                    <div class="show-item">
                        <div class="show-info">
                            <h4>${show.title}</h4>
                            <p><strong>Host:</strong> ${show.host}</p>
                            <p><strong>Time:</strong> ${show.formatted_time}</p>
                            <p><strong>Days:</strong> ${show.days || 'Not set'}</p>
                        </div>
                        <div class="show-actions">
                            <button class="btn-edit" data-show-id="${show.id}">Edit</button>
                            <button class="btn-delete" data-show-id="${show.id}">Delete</button>
                        </div>
                    </div>
                `;
            });
            
            container.html(html);
        }
    }
    
    // Initialize widgets when document is ready
    $(document).ready(function() {
        // Initialize Radio Schedule Display widgets
        $('.radio-schedule-container').each(function() {
            new RadioScheduleDisplay(this);
        });
        
        // Initialize Radio Show Manager widgets
        $('.radio-show-manager').each(function() {
            new RadioShowManager(this);
        });
    });
    
    // Elementor frontend compatibility
    $(window).on('elementor/frontend/init', function() {
        if (typeof elementorFrontend !== 'undefined') {
            elementorFrontend.hooks.addAction('frontend/element_ready/widget', function($scope) {
                // Reinitialize widgets when Elementor loads them
                $scope.find('.radio-schedule-container').each(function() {
                    new RadioScheduleDisplay(this);
                });
                
                $scope.find('.radio-show-manager').each(function() {
                    new RadioShowManager(this);
                });
            });
        }
    });
    
})(jQuery);
