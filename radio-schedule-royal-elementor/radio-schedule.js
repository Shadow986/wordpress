/**
 * HIGHWAY RADIO SCHEDULE SYSTEM - ROYAL ELEMENTOR VERSION
 * JavaScript for auto-updating radio schedule
 * 
 * UPLOAD TO: /wp-content/themes/YOUR-THEME-NAME/js/radio-schedule.js
 */

class HighwayRadioSchedule {
    constructor() {
        this.updateInterval = 60000; // Update every 60 seconds
        this.isLoading = false;
        this.retryCount = 0;
        this.maxRetries = 3;
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.init());
        } else {
            this.init();
        }
    }
    
    init() {
        console.log('🎵 Highway Radio Schedule: Starting...');
        
        if (!this.checkRequiredElements()) {
            console.log('📻 Highway Radio: Waiting for elements...');
            setTimeout(() => this.init(), 2000);
            return;
        }
        
        this.loadShows();
        this.startAutoRefresh();
        
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopAutoRefresh();
            } else {
                this.startAutoRefresh();
                this.loadShows();
            }
        });
        
        console.log('✅ Highway Radio Schedule: Ready!');
    }
    
    checkRequiredElements() {
        return document.getElementById('current-shows') && document.getElementById('upcoming-shows');
    }
    
    startAutoRefresh() {
        this.stopAutoRefresh();
        this.refreshInterval = setInterval(() => this.loadShows(), this.updateInterval);
    }
    
    stopAutoRefresh() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
            this.refreshInterval = null;
        }
    }
    
    async loadShows() {
        if (this.isLoading) return;
        
        this.isLoading = true;
        this.showLoadingState();
        
        try {
            const response = await fetch(highway_radio_ajax.ajax_url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'highway_radio_get_shows',
                    nonce: highway_radio_ajax.nonce
                })
            });
            
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const result = await response.json();
            
            if (result.success) {
                this.renderCurrentShows(result.data.current);
                this.renderUpcomingShows(result.data.upcoming);
                this.updateTimeDisplay(result.data.current_time);
                this.retryCount = 0;
            } else {
                throw new Error('Server error: ' + (result.data || 'Unknown error'));
            }
            
        } catch (error) {
            console.error('🚨 Highway Radio Error:', error);
            this.handleError(error);
        } finally {
            this.isLoading = false;
            this.hideLoadingState();
        }
    }
    
    handleError(error) {
        this.retryCount++;
        if (this.retryCount <= this.maxRetries) {
            console.log(`🔄 Retrying... (${this.retryCount}/${this.maxRetries})`);
            setTimeout(() => this.loadShows(), 5000);
        } else {
            this.showErrorState();
        }
    }
    
    showLoadingState() {
        ['current-shows', 'upcoming-shows'].forEach(id => {
            const container = document.getElementById(id);
            if (container) container.style.opacity = '0.7';
        });
    }
    
    hideLoadingState() {
        ['current-shows', 'upcoming-shows'].forEach(id => {
            const container = document.getElementById(id);
            if (container) container.style.opacity = '1';
        });
    }
    
    showErrorState() {
        const currentContainer = document.getElementById('current-shows');
        const upcomingContainer = document.getElementById('upcoming-shows');
        
        if (currentContainer) {
            currentContainer.innerHTML = `
                <div class="error-state">
                    <p>⚠️ Unable to load current shows</p>
                    <button onclick="window.highwayRadio.loadShows()" class="retry-btn">Retry</button>
                </div>
            `;
        }
        
        if (upcomingContainer) {
            upcomingContainer.innerHTML = `
                <div class="error-state">
                    <p>⚠️ Unable to load upcoming shows</p>
                </div>
            `;
        }
    }
    
    renderCurrentShows(shows) {
        const container = document.getElementById('current-shows');
        if (!container) return;
        
        if (!shows || shows.length === 0) {
            container.innerHTML = `
                <div class="no-shows">
                    <div class="no-shows-icon">📻</div>
                    <h3>No Shows Currently On Air</h3>
                    <p>Check back soon for live programming!</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = shows.map(show => `
            <div class="show-card" data-show-id="${show.id}">
                <div class="show-image-container">
                    <img src="${show.image}" 
                         alt="${this.escapeHtml(show.title)}" 
                         class="show-image"
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA4MCA4MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjgwIiBoZWlnaHQ9IjgwIiBmaWxsPSIjMzMzIi8+Cjx0ZXh0IHg9IjQwIiB5PSI0NSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjEyIiBmaWxsPSIjZmZmIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5SQURJTzwvdGV4dD4KPC9zdmc+'">
                    <div class="live-badge">🔴 LIVE</div>
                </div>
                <div class="show-content">
                    <div class="show-title">${this.escapeHtml(show.title)}</div>
                    <div class="show-host">with ${this.escapeHtml(show.host)}</div>
                    <div class="show-time">${show.start_time} - ${show.end_time}</div>
                    ${show.description ? `<div class="show-description">${this.escapeHtml(show.description)}</div>` : ''}
                </div>
            </div>
        `).join('');
    }
    
    renderUpcomingShows(shows) {
        const container = document.getElementById('upcoming-shows');
        if (!container) return;
        
        if (!shows || shows.length === 0) {
            container.innerHTML = `
                <div class="no-upcoming">
                    <p>No upcoming shows scheduled for today</p>
                </div>
            `;
            return;
        }
        
        container.innerHTML = shows.map(show => `
            <div class="upcoming-card" data-show-id="${show.id}">
                <div class="upcoming-content">
                    <div class="show-title">${this.escapeHtml(show.title)}</div>
                    <div class="show-host">${this.escapeHtml(show.host)}</div>
                    <div class="show-time">
                        <span class="time-badge">${show.start_time}</span>
                    </div>
                </div>
            </div>
        `).join('');
    }
    
    updateTimeDisplay(currentTime) {
        const timeDisplay = document.querySelector('.current-time-display');
        if (timeDisplay) {
            timeDisplay.textContent = currentTime;
        }
        
        const currentShows = document.querySelectorAll('.show-card');
        if (currentShows.length > 0) {
            const firstShow = currentShows[0].querySelector('.show-title').textContent;
            document.title = `🔴 LIVE: ${firstShow} - Highway Radio`;
        }
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    refresh() {
        this.loadShows();
    }
    
    setUpdateInterval(milliseconds) {
        this.updateInterval = milliseconds;
        this.startAutoRefresh();
    }
}

// Initialize the system
window.highwayRadio = new HighwayRadioSchedule();

// Debug functions
window.highwayRadioDebug = {
    refresh: () => window.highwayRadio.refresh(),
    setInterval: (seconds) => window.highwayRadio.setUpdateInterval(seconds * 1000),
    getStatus: () => ({
        isLoading: window.highwayRadio.isLoading,
        retryCount: window.highwayRadio.retryCount,
        updateInterval: window.highwayRadio.updateInterval
    })
};

console.log('🎵 Highway Radio Schedule System loaded!');
console.log('🔧 Debug: highwayRadioDebug.refresh(), highwayRadioDebug.setInterval(30)');

/**
 * UPLOAD THIS FILE TO:
 * /wp-content/themes/YOUR-THEME-NAME/js/radio-schedule.js
 * 
 * NEXT STEPS:
 * 1. Add Royal Elementor Custom CSS widget
 * 2. Add HTML widget with schedule code
 * 3. Create radio shows in WordPress admin
 */
