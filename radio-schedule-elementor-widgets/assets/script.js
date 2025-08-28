/**
 * Radio Schedule Elementor Widgets - JavaScript
 * Updated for combined widget with Elementor data
 */

(function($) {
    'use strict';
    
    // Radio Schedule Display functionality
    class RadioScheduleDisplay {
        constructor(container) {
            this.container = $(container);
            this.currentDay = this.getCurrentDay();
            this.updateInterval = null;
            this.showsData = [];
            
            this.init();
        }
        
        init() {
            // Get shows data from the widget
            const showsDataAttr = this.container.attr('data-shows');
            if (showsDataAttr) {
                try {
                    this.showsData = JSON.parse(showsDataAttr);
                    console.log('✅ Shows data loaded:', this.showsData.length, 'shows');
                } catch (e) {
                    console.error('❌ Error parsing shows data:', e);
                    this.showsData = [];
                }
            } else {
                console.warn('⚠️ No shows data found in widget');
            }
            
            console.log('📅 Current day detected as:', this.currentDay);
            
            this.bindEvents();
            this.loadShows(this.currentDay);
            this.startAutoUpdate();
            this.setActiveDay();
        }
        
        bindEvents() {
            this.container.on('click', '.day-btn', (e) => {
                const day = $(e.target).data('day');
                if (day === this.getCurrentDay()) {
                    // If clicking "Today", use the actual current day
                    this.switchDay(this.getCurrentDay());
                } else {
                    this.switchDay(day);
                }
            });
        }
        
        getCurrentDay() {
            const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
            return days[new Date().getDay()];
        }
        
        setActiveDay() {
            this.container.find('.day-btn').removeClass('active');
            // Handle "Today" button specially
            if (this.currentDay === this.getCurrentDay()) {
                this.container.find('.day-btn[data-day="' + this.getCurrentDay() + '"]').addClass('active');
            } else {
                this.container.find('.day-btn[data-day="' + this.currentDay + '"]').addClass('active');
            }
        }
        
        switchDay(day) {
            this.currentDay = day;
            this.setActiveDay();
            this.loadShows(day);
        }
        
        loadShows(day = null) {
            if (!day) day = this.currentDay;
            
            this.showLoading();
            
            // Process shows data from Elementor
            const currentTime = this.getCurrentTime();
            const currentShows = [];
            const upcomingShows = [];
            const allDayShows = [];
            
            console.log('Loading shows for day:', day);
            console.log('Shows data:', this.showsData);
            
            this.showsData.forEach(show => {
                // Check if show airs on this day
                let showDays = [];
                if (typeof show.days === 'string') {
                    showDays = show.days.toLowerCase().split(',').map(d => d.trim());
                } else if (Array.isArray(show.days)) {
                    showDays = show.days.map(d => d.toLowerCase().trim());
                }
                
                console.log('Show:', show.title, 'Days:', showDays, 'Looking for:', day);
                
                if (!showDays.includes(day.toLowerCase())) {
                    return;
                }
                
                const showData = {
                    title: show.title,
                    host: show.host,
                    start_time: show.start_time,
                    end_time: show.end_time,
                    image: show.image || this.getDefaultImage(),
                    description: show.description,
                    formatted_time: show.formatted_time,
                    is_live: show.is_live
                };
                
                // Add to all day shows first
                allDayShows.push(showData);
                
                // Check if show is currently on (only for today)
                if (day === this.getCurrentDay() && show.start_time && show.end_time) {
                    const startTime = this.timeToMinutes(show.start_time);
                    const endTime = this.timeToMinutes(show.end_time);
                    const currentMinutes = this.timeToMinutes(currentTime);
                    
                    if (currentMinutes >= startTime && currentMinutes < endTime) {
                        currentShows.push(showData);
                    } else if (startTime > currentMinutes) {
                        upcomingShows.push(showData);
                    }
                } else if (day !== this.getCurrentDay()) {
                    // For other days, show all scheduled shows as "upcoming"
                    upcomingShows.push(showData);
                }
            });
            
            // Sort upcoming shows by start time
            upcomingShows.sort((a, b) => {
                const aTime = this.timeToMinutes(a.start_time);
                const bTime = this.timeToMinutes(b.start_time);
                return aTime - bTime;
            });
            
            console.log('Current shows:', currentShows);
            console.log('Upcoming shows:', upcomingShows);
            
            // Render shows
            setTimeout(() => {
                this.renderCurrentShows(currentShows);
                this.renderUpcomingShows(upcomingShows);
                this.updateLiveIndicator(currentShows.length > 0);
            }, 500);
        }
        
        getCurrentTime() {
            const now = new Date();
            return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
        }
        
        timeToMinutes(timeString) {
            if (!timeString) return 0;
            const [hours, minutes] = timeString.split(':').map(Number);
            return hours * 60 + (minutes || 0);
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
            
            const maxShows = parseInt(this.container.attr('data-max-shows')) || 6;
            const limitedShows = shows.slice(0, maxShows);
            
            let html = '';
            limitedShows.forEach(show => {
                html += this.getUpcomingCardHTML(show);
            });
            
            container.html(html);
        }
        
        getShowCardHTML(show, isLive = false) {
            const liveBadge = isLive ? '<div class="live-badge">🔴 LIVE</div>' : '';
            const showImage = show.image && show.image !== '' ? show.image : this.getDefaultImage();
            
            // Build links HTML
            let linksHTML = '';
            if (show.listen_link || show.show_link) {
                linksHTML = '<div class="show-links">';
                if (show.listen_link) {
                    linksHTML += `<a href="${show.listen_link}" target="_blank" class="listen-btn">🎧 Listen Live</a>`;
                }
                if (show.show_link) {
                    linksHTML += `<a href="${show.show_link}" target="_blank" class="info-btn">ℹ️ More Info</a>`;
                }
                linksHTML += '</div>';
            }
            
            return `
                <div class="show-card ${isLive ? 'live-show' : ''}">
                    <div class="show-image-container">
                        <img src="${showImage}" alt="${show.title}" class="show-image" loading="lazy" onerror="this.src='${this.getDefaultImage()}'">
                        ${liveBadge}
                    </div>
                    <div class="show-content">
                        <h4 class="show-title">${show.title}</h4>
                        <p class="show-host">with ${show.host}</p>
                        <div class="show-time">${show.formatted_time}</div>
                        ${show.description ? `<p class="show-description">${show.description}</p>` : ''}
                        ${linksHTML}
                    </div>
                </div>
            `;
        }
        
        getUpcomingCardHTML(show) {
            const showImage = show.image && show.image !== '' ? show.image : this.getDefaultImage();
            
            // Build links HTML
            let linksHTML = '';
            if (show.listen_link || show.show_link) {
                linksHTML = '<div class="show-links compact">';
                if (show.listen_link) {
                    linksHTML += `<a href="${show.listen_link}" target="_blank" class="listen-btn-small">🎧</a>`;
                }
                if (show.show_link) {
                    linksHTML += `<a href="${show.show_link}" target="_blank" class="info-btn-small">ℹ️</a>`;
                }
                linksHTML += '</div>';
            }
            
            return `
                <div class="upcoming-card">
                    <div class="upcoming-image">
                        <img src="${showImage}" alt="${show.title}" class="upcoming-show-image" loading="lazy" onerror="this.src='${this.getDefaultImage()}'">
                    </div>
                    <div class="upcoming-content">
                        <h4 class="show-title">${show.title}</h4>
                        <p class="show-host">with ${show.host}</p>
                        <span class="time-badge">${show.formatted_time}</span>
                        ${show.description ? `<p class="show-description">${show.description}</p>` : ''}
                        ${linksHTML}
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
        
        updateLiveIndicator(hasLiveShows) {
            const indicator = this.container.find('.live-indicator');
            if (hasLiveShows) {
                indicator.show();
            } else {
                indicator.hide();
            }
        }
        
        getDefaultImage() {
            return 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgdmlld0JveD0iMCAwIDEwMCAxMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHJlY3Qgd2lkdGg9IjEwMCIgaGVpZ2h0PSIxMDAiIGZpbGw9IiM5QzI3QjAiLz48dGV4dCB4PSI1MCIgeT0iNTUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxMiIgZmlsbD0id2hpdGUiIHRleHQtYW5jaG9yPSJtaWRkbGUiPlJBRElPPC90ZXh0Pjwvc3ZnPg==';
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
    
    // Initialize widgets when document is ready
    $(document).ready(function() {
        // Initialize Radio Schedule widgets
        $('.radio-schedule-container').each(function() {
            new RadioScheduleDisplay(this);
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
            });
        }
    });
    
})(jQuery);
