// Global variables
let dashboardData = null;
const API_BASE_URL = 'http://localhost:8000/api/SuperAdmin-dashboard';

// Initialize dashboard when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
});

// Load dashboard data from API
async function loadDashboardData() {
    try {
        showLoading();
        
        const response = await axios.get(API_BASE_URL, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });
        
        dashboardData = response.data;
        renderDashboard();
        hideLoading();
        
    } catch (error) {
        console.error('Error fetching dashboard data:', error);
        showError(error.response?.data?.message || error.message || 'Failed to fetch dashboard data');
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

    // Sample data - in real implementation, this would come from API
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
                <p class="text-gray-500">No events found</p>
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
                <p class="text-gray-500">No recent activity</p>
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
                <p class="text-gray-500">No events found</p>
                <span class="text-blue-600 text-sm font-medium">Create your first event</span>
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
        const eventDate = event.event_date ? 
            new Date(event.event_date).toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            }) : 'TBA';
        
        const description = event.description ? 
            (event.description.length > 80 ? event.description.substring(0, 80) + '...' : event.description) :
            'No description available';
            
        const registrationLink = `${window.location.origin}/register/${event.id}`;
        
        return `
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                <div class="flex items-center justify-between mb-3">
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Event</span>
                    <span class="text-sm text-gray-500">${eventDate}</span>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">${event.name || 'Untitled Event'}</h4>
                <p class="text-sm text-gray-600 mb-3">${description}</p>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-sm text-gray-500">${event.attendees_count || 0} attendees</span>
                    <span class="text-sm font-medium text-green-600">$${parseFloat(event.total_revenue || 0).toFixed(2)}</span>
                </div>
                <div class="pt-3 border-t border-gray-100">
                    <button onclick="copyRegistrationLink('${registrationLink}')" 
                            class="w-full flex items-center justify-center px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-medium rounded-lg transition duration-200">
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