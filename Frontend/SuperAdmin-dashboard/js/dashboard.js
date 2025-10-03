// Global variables
let dashboardData = {};
const API_BASE_URL = 'http://localhost:3001/super-admin/dashboard';

// Simple test to verify axios is loaded
console.log('Dashboard.js loaded, axios available:', typeof axios !== 'undefined');
console.log('API_BASE_URL set to:', API_BASE_URL);

// Initialize dashboard when page loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, initializing dashboard...');
    loadDashboardData();
});

// Load dashboard data from API
async function loadDashboardData() {
    try {
        showLoading();
        console.log('Attempting to fetch data from:', API_BASE_URL);
        
        // Get auth token from localStorage
        const token = localStorage.getItem('auth_token');
        console.log('Auth token found:', !!token);
        
        const headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        };
        
        // Add authorization header if token exists
        if (token) {
            headers.Authorization = `Bearer ${token}`;
        }
        
        const response = await axios.get(API_BASE_URL, {
            headers: headers,
            timeout: 10000 // 10 second timeout
        });
        
        console.log('API Response received:', response);
        console.log('Real data fetched from database:');
        console.log('- Events:', response.data.events?.length || 0);
        console.log('- Users:', response.data.users?.length || 0);
        console.log('- Attendees:', response.data.attendees?.length || 0);
        console.log('- Payments:', response.data.payments?.length || 0);
        console.log('- Companies:', response.data.companies?.length || 0);
        
        dashboardData = response.data;
        renderDashboard();
        hideLoading();
        
    } catch (error) {
        console.error('Detailed error information:', {
            message: error.message,
            code: error.code,
            response: error.response,
            config: error.config
        });
        
        let errorMessage = 'Failed to fetch dashboard data';
        
        if (error.response?.status === 401) {
            errorMessage = 'Authentication failed: Please login with super admin credentials to access this dashboard.';
            // Remove invalid token and suggest redirect to login
            localStorage.removeItem('auth_token');
        } else if (error.response?.status === 403) {
            errorMessage = 'Access denied: You do not have super admin privileges to access this dashboard.';
        } else if (error.code === 'NETWORK_ERROR' || error.message.includes('Network Error')) {
            errorMessage = `Network Error: Cannot connect to API server at ${API_BASE_URL}. Please ensure the backend server is running.`;
        } else if (error.response) {
            errorMessage = `API Error (${error.response.status}): ${error.response.data?.message || error.response.statusText}`;
        } else if (error.code === 'ECONNREFUSED') {
            errorMessage = 'Connection refused: Backend server is not running or not accessible.';
        }
        
        showError(errorMessage);
    }
}

// Show loading state
function showLoading() {
    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('error').classList.add('hidden');
    document.getElementById('dashboard').classList.add('hidden');
}

// Hide loading state
function hideLoading() {
    document.getElementById('loading').classList.add('hidden');
    document.getElementById('error').classList.add('hidden');
    document.getElementById('dashboard').classList.remove('hidden');
}

// Show error state
function showError(message) {
    document.getElementById('loading').classList.add('hidden');
    document.getElementById('dashboard').classList.add('hidden');
    document.getElementById('error').classList.remove('hidden');
    document.getElementById('error-message').textContent = message;
}

// Render the entire dashboard
function renderDashboard() {
    renderMetricsCards();
    renderRevenueChart();
    renderTopPerformingEvents();
    renderRecentActivity();
    renderUpcomingEvents();
}

// Render metrics cards (Key metrics in Laravel style)
function renderMetricsCards() {
    const metricsGrid = document.getElementById('metrics-grid');
    
    const totalRevenue = dashboardData.payments?.reduce((sum, payment) => sum + (parseFloat(payment.amount) || 0), 0) || 0;
    
    const metrics = [
        {
            title: 'Total Events',
            value: dashboardData.events?.length || 0,
            icon: 'fas fa-calendar-alt',
            iconBg: 'bg-blue-100',
            iconColor: 'text-blue-600',
            trend: 'Active Events',
            trendIcon: 'fas fa-arrow-up',
            trendColor: 'text-green-600'
        },
        {
            title: 'Total Attendees',
            value: dashboardData.attendees?.length || 0,
            icon: 'fas fa-ticket-alt',
            iconBg: 'bg-green-100',
            iconColor: 'text-green-600',
            trend: 'Registered',
            trendIcon: 'fas fa-arrow-up',
            trendColor: 'text-green-600'
        },
        {
            title: 'Total Revenue',
            value: `$${totalRevenue.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`,
            icon: 'fas fa-dollar-sign',
            iconBg: 'bg-purple-100',
            iconColor: 'text-purple-600',
            trend: 'From completed payments',
            trendIcon: 'fas fa-arrow-up',
            trendColor: 'text-green-600'
        },
        {
            title: 'Total Zones',
            value: dashboardData.eventZones?.length || 0,
            icon: 'fas fa-tags',
            iconBg: 'bg-orange-100',
            iconColor: 'text-orange-600',
            trend: 'Across all events',
            trendIcon: 'fas fa-clock',
            trendColor: 'text-orange-600'
        }
    ];

    metricsGrid.innerHTML = metrics.map(metric => `
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">${metric.title}</p>
                    <p class="text-3xl font-bold text-gray-900">${metric.value}</p>
                    <p class="text-sm ${metric.trendColor} flex items-center mt-2">
                        <i class="${metric.trendIcon} mr-1"></i>
                        ${metric.trend}
                    </p>
                </div>
                <div class="w-12 h-12 ${metric.iconBg} rounded-lg flex items-center justify-center">
                    <i class="${metric.icon} ${metric.iconColor} text-xl"></i>
                </div>
            </div>
        </div>
    `).join('');
}

// Render revenue chart
function renderRevenueChart() {
    const canvas = document.getElementById('revenueChart');
    if (!canvas) return;

    // Destroy existing chart if it exists
    if (window.revenueChart instanceof Chart) {
        window.revenueChart.destroy();
    }

    // Calculate revenue data from API response
    const revenueData = calculateRevenueData();
    
    const ctx = canvas.getContext('2d');
    window.revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: revenueData.labels,
            datasets: [{
                label: 'Revenue ($)',
                data: revenueData.data,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 2,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        },
                        font: {
                            size: 12
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: $' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

// Calculate revenue data from API data
function calculateRevenueData() {
    // Default to last 7 days
    const labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
    
    if (!dashboardData.payments || dashboardData.payments.length === 0) {
        return {
            labels: labels,
            data: [0, 0, 0, 0, 0, 0, 0]
        };
    }

    // Calculate daily revenue from payments
    const dailyRevenue = new Array(7).fill(0);
    const now = new Date();
    
    dashboardData.payments.forEach(payment => {
        if (payment.created_at && payment.amount) {
            const paymentDate = new Date(payment.created_at);
            const diffDays = Math.floor((now - paymentDate) / (1000 * 60 * 60 * 24));
            
            if (diffDays >= 0 && diffDays < 7) {
                dailyRevenue[6 - diffDays] += parseFloat(payment.amount);
            }
        }
    });

    return {
        labels: labels,
        data: dailyRevenue
    };
}

// Render top performing events
function renderTopPerformingEvents() {
    const container = document.getElementById('top-events-container');
    
    if (!dashboardData.events || dashboardData.events.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-calendar-times text-gray-400 text-3xl mb-3"></i>
                <p class="text-gray-500">No events in database</p>
                <p class="text-xs text-gray-400 mt-1">Real-time data from API</p>
            </div>
        `;
        return;
    }

    // Sort events by attendee count and revenue, take top 3
    const topEvents = dashboardData.events
        .filter(event => (event.attendees_count || 0) > 0)
        .sort((a, b) => (b.attendees_count || 0) - (a.attendees_count || 0))
        .slice(0, 3);

    if (topEvents.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-calendar-times text-gray-400 text-3xl mb-3"></i>
                <p class="text-gray-500">No events found</p>
            </div>
        `;
        return;
    }

    container.innerHTML = topEvents.map(event => `
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-calendar-alt text-blue-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">${event.name || 'Untitled Event'}</p>
                    <p class="text-sm text-gray-600">${event.attendees_count || 0} attendees</p>
                </div>
            </div>
            <span class="text-green-600 font-semibold">$${parseFloat(event.total_revenue || 0).toFixed(2)}</span>
        </div>
    `).join('');
}

// Render recent activity
function renderRecentActivity() {
    const container = document.getElementById('recent-activity-container');
    
    const activities = [];

    // Add recent payments
    if (dashboardData.payments && dashboardData.payments.length > 0) {
        dashboardData.payments.slice(0, 3).forEach(payment => {
            // Find the related event/attendee for the payment
            const attendee = dashboardData.attendees?.find(att => att.id === payment.attendee_id);
            const event = attendee ? dashboardData.events?.find(evt => evt.id === attendee.event_id) : null;
            
            activities.push({
                type: 'payment',
                title: 'Payment received',
                description: `for ${event?.name || 'an event'}`,
                time: formatTimeAgo(payment.created_at),
                amount: `$${parseFloat(payment.amount || 0).toFixed(2)}`,
                icon: 'fas fa-ticket-alt',
                iconBg: 'bg-green-100',
                iconColor: 'text-green-600'
            });
        });
    }

    // Add recent new events
    if (dashboardData.events && dashboardData.events.length > 0) {
        dashboardData.events
            .sort((a, b) => new Date(b.created_at || 0) - new Date(a.created_at || 0))
            .slice(0, 2)
            .forEach(event => {
                activities.push({
                    type: 'event',
                    title: 'New event',
                    description: `"${event.name || 'Untitled Event'}" created`,
                    time: formatTimeAgo(event.created_at),
                    amount: null,
                    icon: 'fas fa-calendar-plus',
                    iconBg: 'bg-blue-100',
                    iconColor: 'text-blue-600'
                });
            });
    }

    if (activities.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-clock text-gray-400 text-3xl mb-3"></i>
                <p class="text-gray-500">No recent activity in database</p>
                <p class="text-xs text-gray-400 mt-1">Live data from API</p>
            </div>
        `;
        return;
    }

    container.innerHTML = activities.map(activity => `
        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
            <div class="w-8 h-8 ${activity.iconBg} rounded-full flex items-center justify-center flex-shrink-0">
                <i class="${activity.icon} ${activity.iconColor} text-sm"></i>
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-900">
                    <span class="font-medium">${activity.title}</span> ${activity.description}
                </p>
                <p class="text-xs text-gray-500">${activity.time}</p>
            </div>
            ${activity.amount ? `<span class="text-sm font-medium text-green-600">${activity.amount}</span>` : ''}
        </div>
    `).join('');
}

// Render upcoming events
function renderUpcomingEvents() {
    const container = document.getElementById('upcoming-events-grid');
    
    if (!dashboardData.events || dashboardData.events.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-8">
                <i class="fas fa-calendar-times text-gray-400 text-3xl mb-3"></i>
                <p class="text-gray-500">No events in database</p>
                <p class="text-xs text-gray-400 mt-1">Real-time data from API</p>
                <span class="text-blue-600 text-sm font-medium block mt-2">Create your first event</span>
            </div>
        `;
        return;
    }

    // Filter upcoming events (future events or recent events)
    const upcomingEvents = dashboardData.events
        .filter(event => {
            if (!event.event_date) return true; // Include events without dates
            const eventDate = new Date(event.event_date);
            const today = new Date();
            return eventDate >= today || (today - eventDate) / (1000 * 60 * 60 * 24) <= 30; // Include events from last 30 days
        })
        .slice(0, 6); // Show up to 6 events

    if (upcomingEvents.length === 0) {
        container.innerHTML = `
            <div class="col-span-full text-center py-8">
                <i class="fas fa-calendar-times text-gray-400 text-3xl mb-3"></i>
                <p class="text-gray-500">No events found</p>
                <span class="text-blue-600 text-sm font-medium">Create your first event</span>
            </div>
        `;
        return;
    }

    container.innerHTML = upcomingEvents.map(event => {
        const now = new Date();
        let expired = false;
        let eventDateStr = 'TBA';
        let eventDateObj = null;
        if (event.event_date) {
            eventDateObj = new Date(event.event_date);
            eventDateStr = eventDateObj.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
            expired = eventDateObj < now;
        }

        const description = event.description ?
            (event.description.length > 80 ? event.description.substring(0, 80) + '...' : event.description) :
            'No description available';

        const registrationLink = `${window.location.origin}/register/${event.id}`;

        // Use event.logo and event.bannerURL if available
        const logoImg = event.logo ? `<div class='flex justify-center items-center w-full mb-2'><img src="${event.logo}" alt="Logo" class="w-20 h-20 object-contain rounded-xl shadow border border-gray-100 bg-white p-2" /></div>` : '';
        const bannerImg = event.bannerURL ? `<img src="${event.bannerURL}" alt="Banner" class="w-full h-32 object-cover rounded-xl mb-3 shadow-sm border border-gray-100" />` : '';

        // Expired badge and overlay
        const expiredBadge = expired ? `<span class="bg-red-100 text-red-700 text-xs font-semibold px-3 py-1 rounded-full ml-2 animate-pulse">Expired</span>` : '';
        const expiredOverlay = expired ? `<div class="absolute inset-0 bg-red-50 bg-opacity-70 rounded-2xl flex items-center justify-center z-10"><span class="text-red-700 font-bold text-lg"><i class='fas fa-exclamation-circle mr-2'></i>Event Expired</span></div>` : '';

        return `
            <div class="relative bg-white border border-gray-100 rounded-2xl p-5 shadow-lg hover:shadow-xl transition duration-200 flex flex-col justify-between min-h-[370px]">
                ${expiredOverlay}
                ${bannerImg}
                ${logoImg}
                <div class="flex items-center justify-between mb-2">
                    <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">Event</span>
                    <span class="text-sm text-gray-500">${eventDateStr}${expiredBadge}</span>
                </div>
                <h4 class="font-bold text-lg text-gray-900 mb-1 truncate">${event.name || 'Untitled Event'}</h4>
                <p class="text-sm text-gray-600 mb-2 line-clamp-2">${description}</p>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-gray-500"><i class="fas fa-users mr-1"></i>${event.attendees_count || 0} attendees</span>
                    <span class="text-xs font-semibold text-green-600"><i class="fas fa-dollar-sign mr-1"></i>${parseFloat(event.total_revenue || 0).toFixed(2)}</span>
                </div>
                <div class="pt-2 mt-auto">
                    <button onclick="copyRegistrationLink('${registrationLink}')" 
                            class="w-full flex items-center justify-center px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-xl transition duration-200 shadow">
                        <i class="fas fa-share-alt mr-2"></i>
                        Copy Registration Link
                    </button>
                </div>
            </div>
        `;
    }).join('');
}

// Copy registration link function
function copyRegistrationLink(link) {
    navigator.clipboard.writeText(link).then(() => {
        showCopyNotification('Registration link copied to clipboard!', 'success');
    }).catch(err => {
        console.error('Failed to copy link: ', err);
        showCopyNotification('Failed to copy link. Please try again.', 'error');
    });
}

// Show copy notification
function showCopyNotification(message, type) {
    // Remove existing notification if any
    const existingNotification = document.querySelector('.copy-notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `copy-notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} mr-2"></i>
            ${message}
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Utility functions
function formatValue(value) {
    if (value === null || value === undefined || value === '') return 'N/A';
    if (typeof value === 'string' && value.includes('T')) {
        // Likely a date string
        try {
            return new Date(value).toLocaleDateString();
        } catch (e) {
            return value;
        }
    }
    if (typeof value === 'number' && value > 1000000000000) {
        // Likely a timestamp
        return new Date(value).toLocaleDateString();
    }
    return value.toString();
}

// Helper function to format time ago
function formatTimeAgo(dateString) {
    if (!dateString) return 'Recently';
    
    try {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) return 'Just now';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
        if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)}d ago`;
        return date.toLocaleDateString();
    } catch (error) {
        return 'Recently';
    }
}

function capitalizeHeader(header) {
    return header.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
}