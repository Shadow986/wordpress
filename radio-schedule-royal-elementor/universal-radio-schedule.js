/**
 * UNIVERSAL RADIO SCHEDULE - JAVASCRIPT
 * Works with any radio station - fully responsive
 * 
 * Features:
 * - Real-time updates
 * - Day navigation
 * - Responsive design
 * - WordPress integration
 * - Live show detection
 */

(function($) {
    'use strict';
    
    class UniversalRadioSchedule {
        constructor() {
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
            // Day navigation buttons
            $(document).on('click', '.day-btn', (e) => {
                const day = $(e.target).data('day');
                this.switchDay(day);
            });
            
            // Retry button
            $(document).on('click', '.retry-btn', () => {
                this.loadShows(this.currentDay);
            });
            
            // Auto-refresh on window focus
            $(window).on('focus', () => {
                this.loadShows(this.currentDay);
            });
        }
        
        getCurrentDay() {
            const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            return days[new Date().getDay()];
        }
        
        setActiveDay() {
            $('.day-btn').removeClass('active');
            $(`.day-btn[data-day="${this.currentDay}"]`).addClass('active');
        }
        
        switchDay(day) {
            this.currentDay = day;
            this.setActiveDay();
            this.loadShows(day);
        }
        
        loadShows(day = null) {
            if (!day) day = this.currentDay;
            
            this.showLoading();
            
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
                        this.showError('Failed to load shows. Please try again.');
                    }
                },
                error: (xhr, status, error) => {
                    console.error('AJAX Error:', error);
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
            this.updateStationName(data.station_name);
        }
        
        renderCurrentShows(shows) {
            const container = $('#current-shows');
            
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
            const container = $('#upcoming-shows');
            
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
            const typeClass = show.type ? `show-type-${show.type}` : '';
            const typeBadge = show.type ? `<span class="show-type-badge ${typeClass}">${this.capitalizeFirst(show.type)}</span>` : '';
            
            return `
                <div class="show-card ${isLive ? 'live-show' : ''}">
                    <div class="show-image-container">
                        <img src="${show.image}" alt="${show.title}" class="show-image" loading="lazy" 
                             onerror="this.src='${this.getDefaultImage(show.type)}'">
                        ${liveBadge}
                    </div>
                    <div class="show-content">
                        <h4 class="show-title">${show.title}</h4>
                        <p class="show-host">with ${show.host}</p>
                        <div class="show-time">${show.formatted_time}</div>
                        ${typeBadge}
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
            $('#current-shows').html(`
                <div class="loading-state">
                    <div class="loading-spinner"></div>
                    <p>Loading shows...</p>
                </div>
            `);
            
            $('#upcoming-shows').html(`
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
                    <button class="retry-btn">Try Again</button>
                </div>
            `;
            
            $('#current-shows').html(errorHTML);
            $('#upcoming-shows').html('');
        }
        
        updateLiveIndicator(hasLiveShows) {
            const indicator = $('.live-indicator');
            if (hasLiveShows) {
                indicator.show();
            } else {
                indicator.hide();
            }
        }
        
        updateStationName(stationName) {
            if (stationName) {
                $('.schedule-header h2').text(`${stationName} Schedule`);
            }
        }
        
        startAutoUpdate() {
            // Update every 2 minutes
            this.updateInterval = setInterval(() => {
                // Only auto-update if we're viewing today's schedule
                if (this.currentDay === this.getCurrentDay()) {
                    this.loadShows(this.currentDay);
                }
            }, 120000);
        }
        
        stopAutoUpdate() {
            if (this.updateInterval) {
                clearInterval(this.updateInterval);
                this.updateInterval = null;
            }
        }
        
        getDefaultImage(type) {
            const defaultImages = {
                music: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiNmZjZiMzUiLz48dGV4dCB4PSI1MCIgeT0iNTUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMiIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiPk1VU0lDPC90ZXh0Pjwvc3ZnPg==',
                talk: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiMyMTk2RjMiLz48dGV4dCB4PSI1MCIgeT0iNTUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMiIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiPlRBTEs8L3RleHQ+PC9zdmc+',
                news: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiNmNDQzMzYiLz48dGV4dCB4PSI1MCIgeT0iNTUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMiIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiPk5FV1M8L3RleHQ+PC9zdmc+',
                sports: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiM0Q0FGNTAI+PHRleHQgeD0iNTAiIHk9IjU1IiBmb250LWZhbWlseT0iQXJpYWwiIGZvbnQtc2l6ZT0iMTIiIGZpbGw9IndoaXRlIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5TUE9SVFM8L3RleHQ+PC9zdmc+',
                default: 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiM5QzI3QjAiLz48dGV4dCB4PSI1MCIgeT0iNTUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMiIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiPlJBRElPPC90ZXh0Pjwvc3ZnPg=='
            };
            
            return defaultImages[type] || defaultImages.default;
        }
        
        capitalizeFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
        
        // Public methods for external control
        refresh() {
            this.loadShows(this.currentDay);
        }
        
        destroy() {
            this.stopAutoUpdate();
            $(document).off('click', '.day-btn');
            $(document).off('click', '.retry-btn');
            $(window).off('focus');
        }
    }
    
    // Initialize when document is ready
    $(document).ready(function() {
        // Check if radio schedule container exists
        if ($('.radio-schedule-container').length > 0) {
            window.radioSchedule = new UniversalRadioSchedule();
        }
    });
    
    // Expose for external use
    window.UniversalRadioSchedule = UniversalRadioSchedule;
    
})(jQuery);

/**
 * ADDITIONAL FEATURES FOR ELEMENTOR INTEGRATION
 */

// Elementor frontend compatibility
$(window).on('elementor/frontend/init', function() {
    if (typeof elementorFrontend !== 'undefined') {
        elementorFrontend.hooks.addAction('frontend/element_ready/widget', function($scope) {
            if ($scope.find('.radio-schedule-container').length > 0) {
                // Reinitialize if needed
                if (window.radioSchedule) {
                    window.radioSchedule.destroy();
                }
                window.radioSchedule = new UniversalRadioSchedule();
            }
        });
    }
});

// Auto-refresh when page becomes visible (for mobile browsers)
document.addEventListener('visibilitychange', function() {
    if (!document.hidden && window.radioSchedule) {
        window.radioSchedule.refresh();
    }
});

// Touch/swipe support for mobile day navigation
let touchStartX = 0;
let touchEndX = 0;

$(document).on('touchstart', '.schedule-controls', function(e) {
    touchStartX = e.changedTouches[0].screenX;
});

$(document).on('touchend', '.schedule-controls', function(e) {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;
    
    if (Math.abs(diff) > swipeThreshold) {
        const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        const currentIndex = days.indexOf(window.radioSchedule.currentDay);
        
        if (diff > 0 && currentIndex < days.length - 1) {
            // Swipe left - next day
            window.radioSchedule.switchDay(days[currentIndex + 1]);
        } else if (diff < 0 && currentIndex > 0) {
            // Swipe right - previous day
            window.radioSchedule.switchDay(days[currentIndex - 1]);
        }
    }
}

// Keyboard navigation support
$(document).on('keydown', function(e) {
    if (!window.radioSchedule) return;
    
    const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    const currentIndex = days.indexOf(window.radioSchedule.currentDay);
    
    switch(e.key) {
        case 'ArrowLeft':
            if (currentIndex > 0) {
                window.radioSchedule.switchDay(days[currentIndex - 1]);
            }
            break;
        case 'ArrowRight':
            if (currentIndex < days.length - 1) {
                window.radioSchedule.switchDay(days[currentIndex + 1]);
            }
            break;
        case 'r':
        case 'R':
            if (e.ctrlKey || e.metaKey) {
                e.preventDefault();
                window.radioSchedule.refresh();
            }
            break;
    }
});

/**
 * USAGE EXAMPLES:
 * 
 * // Refresh schedule manually
 * window.radioSchedule.refresh();
 * 
 * // Switch to specific day
 * window.radioSchedule.switchDay('friday');
 * 
 * // Get current day
 * console.log(window.radioSchedule.currentDay);
 * 
 * // Destroy instance
 * window.radioSchedule.destroy();
 */
