@extends('layouts.app')

@section('title', 'Dashboard')

@push('head-scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
    <!-- Main Dashboard Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, {{ auth()->user()->name ?? 'Administrator' }}!</h2>
            <p class="text-gray-600">Here's what's happening with your events today.</p>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Events -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Events</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalEvents }}</p>
                        <p class="text-sm text-green-600 flex items-center mt-2">
                            <i class="fas fa-arrow-up mr-1"></i>
                            Active Events
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Tickets Sold -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Attendees</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalAttendees }}</p>
                        <p class="text-sm text-green-600 flex items-center mt-2">
                            <i class="fas fa-arrow-up mr-1"></i>
                            Registered
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-3xl font-bold text-gray-900">${{ number_format($totalRevenue, 2) }}</p>
                        <p class="text-sm text-green-600 flex items-center mt-2">
                            <i class="fas fa-arrow-up mr-1"></i>
                            From completed payments
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Active Coupons -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Zones</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $totalZones }}</p>
                        <p class="text-sm text-orange-600 flex items-center mt-2">
                            <i class="fas fa-clock mr-1"></i>
                            Across all events
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-tags text-orange-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Revenue Chart -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Revenue Overview</h3>
                    <select class="text-sm border border-gray-300 rounded-md px-3 py-1">
                        <option>Last 7 days</option>
                        <option>Last 30 days</option>
                        <option>Last 90 days</option>
                    </select>
                </div>
                <canvas id="revenueChart" style="max-height: 400px;"></canvas>
            </div>

            <!-- Event Performance -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Top Performing Events</h3>
                    <a href="{{ route('events.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                </div>
                <div class="space-y-4">
                    @forelse($topEvents as $event)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $event->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $event->attendees_count }} attendees</p>
                                </div>
                            </div>
                            <span class="text-green-600 font-semibold">${{ number_format($event->total_revenue, 2) }}</span>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-times text-gray-400 text-3xl mb-3"></i>
                            <p class="text-gray-500">No events found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions & Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('events.create') }}" class="w-full flex items-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-200">
                        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-plus text-white text-sm"></i>
                        </div>
                        <span class="font-medium text-blue-900">Create New Event</span>
                    </a>
                    
                    <a href="{{ route('tickets.create') }}" class="w-full flex items-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition duration-200">
                        <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-ticket-alt text-white text-sm"></i>
                        </div>
                        <span class="font-medium text-green-900">Add Ticket Type</span>
                    </a>
                    
                    <a href="{{ route('coupons.create') }}" class="w-full flex items-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition duration-200">
                        <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-tags text-white text-sm"></i>
                        </div>
                        <span class="font-medium text-purple-900">Create Coupon</span>
                    </a>
                    
                    <a href="{{ route('eventZones.create') }}" class="w-full flex items-center p-3 bg-orange-50 hover:bg-orange-100 rounded-lg transition duration-200">
                        <div class="w-8 h-8 bg-orange-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-map-marker-alt text-white text-sm"></i>
                        </div>
                        <span class="font-medium text-orange-900">Setup Event Zone</span>
                    </a>
                    
                    <a href="{{ route('currencies.create') }}" class="w-full flex items-center p-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition duration-200">
                        <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-globe text-white text-sm"></i>
                        </div>
                        <span class="font-medium text-indigo-900">Add Currency</span>
                    </a>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                    <span class="text-sm text-gray-500">Last 24 hours</span>
                </div>
                <div class="space-y-4">
                    <!-- Recent Payments -->
                    @foreach($recentPayments as $payment)
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-ticket-alt text-green-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">
                                    <span class="font-medium">Payment received</span> for {{ $payment->attendee->event->name }}
                                </p>
                                <p class="text-xs text-gray-500">{{ $payment->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-sm font-medium text-green-600">${{ number_format($payment->amount, 2) }}</span>
                        </div>
                    @endforeach
                    
                    <!-- Recent New Events -->
                    @foreach($recentNewEvents as $event)
                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-calendar-plus text-blue-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">New event <span class="font-medium">"{{ $event->name }}"</span> created</p>
                                <p class="text-xs text-gray-500">{{ $event->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($recentPayments->isEmpty() && $recentNewEvents->isEmpty())
                        <div class="text-center py-8">
                            <i class="fas fa-clock text-gray-400 text-3xl mb-3"></i>
                            <p class="text-gray-500">No recent activity</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Upcoming Events</h3>
                <a href="{{ route('events.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All Events</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($recentEvents as $event)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                        <div class="flex items-center justify-between mb-3">
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">Event</span>
                            <span class="text-sm text-gray-500">{{ $event->event_date ? \Carbon\Carbon::parse($event->event_date)->format('M d, Y') : 'TBA' }}</span>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-2">{{ $event->name }}</h4>
                        <p class="text-sm text-gray-600 mb-3">{{ Str::limit($event->description ?? 'No description available', 80) }}</p>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-gray-500">{{ $event->attendees_count ?? 0 }} attendees</span>
                            <span class="text-sm font-medium text-green-600">${{ number_format($event->total_revenue ?? 0, 2) }}</span>
                        </div>
                        <!-- Share Registration Link -->
                        <div class="pt-3 border-t border-gray-100">
                            <button onclick="copyRegistrationLink('{{ route('public.tickets', $event->id) }}')" 
                                    class="w-full flex items-center justify-center px-3 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-sm font-medium rounded-lg transition duration-200">
                                <i class="fas fa-share-alt mr-2"></i>
                                Copy Registration Link
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <i class="fas fa-calendar-times text-gray-400 text-3xl mb-3"></i>
                        <p class="text-gray-500">No events found</p>
                        <a href="{{ route('events.create') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Create your first event</a>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <!-- Copy Registration Link Script -->
    <script>
        function copyRegistrationLink(url) {
            // Create a temporary textarea element
            const tempTextarea = document.createElement('textarea');
            tempTextarea.value = url;
            document.body.appendChild(tempTextarea);
            
            // Select and copy the text
            tempTextarea.select();
            tempTextarea.setSelectionRange(0, 99999); // For mobile devices
            
            try {
                document.execCommand('copy');
                
                // Show success message
                showCopyNotification('Registration link copied to clipboard!', 'success');
            } catch (err) {
                console.error('Failed to copy: ', err);
                showCopyNotification('Failed to copy link. Please try again.', 'error');
            }
            
            // Remove the temporary element
            document.body.removeChild(tempTextarea);
        }
        
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
    </script>

    <!-- Chart.js Script -->
    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const canvas = document.getElementById('revenueChart');
            if (canvas) {
                const ctx = canvas.getContext('2d');
                
                // Destroy existing chart if it exists
                if (window.revenueChart instanceof Chart) {
                    window.revenueChart.destroy();
                }
                
                window.revenueChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Revenue ($)',
                            data: [1200, 1900, 3000, 2500, 2200, 3000, 2800],
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
            } else {
                console.error('Revenue chart canvas not found');
            }
        });
    </script>
@endpush