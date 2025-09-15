@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Payments</h1>
                <p class="text-gray-600">Manage event payments and transactions</p>
            </div>
            
            @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('payments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Create Payment
                </a>
            @endif
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <i class="fas fa-dollar-sign text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm opacity-90">Total Revenue</p>
                        <p class="text-xl font-bold">${{ number_format($payments->sum('amount'), 2) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <i class="fas fa-credit-card text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm opacity-90">Total Payments</p>
                        <p class="text-xl font-bold">{{ $payments->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <i class="fas fa-clock text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm opacity-90">Pending</p>
                        <p class="text-xl font-bold">{{ $payments->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-4 text-white">
                <div class="flex items-center">
                    <i class="fas fa-times-circle text-2xl mr-3"></i>
                    <div>
                        <p class="text-sm opacity-90">Failed</p>
                        <p class="text-xl font-bold">{{ $payments->where('status', 'failed')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payments Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendee</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $payment->transaction_id ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $payment->attendee->full_name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $payment->attendee->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $payment->attendee->event->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $payment->attendee->event->start_date?->format('M d, Y') ?? 'No date' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $payment->currency ?? 'USD' }} {{ number_format($payment->amount, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ ucfirst($payment->payment_method ?? 'N/A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($payment->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Completed
                                    </span>
                                @elseif($payment->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @elseif($payment->status === 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>Failed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-question mr-1"></i>{{ ucfirst($payment->status ?? 'Unknown') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $payment->payment_date?->format('M d, Y') ?? $payment->created_at->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $payment->payment_date?->format('H:i') ?? $payment->created_at->format('H:i') }}</div>
                            </td>
                            @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('payments.show', $payment) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                        @if(auth()->user()->role === 'super_admin')
                                            <a href="{{ route('payments.edit', $payment) }}" class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <form method="POST" action="{{ route('payments.destroy', $payment) }}" class="inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this payment?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash mr-1"></i>Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                @if(auth()->user()->role === 'super_admin')
                                    No payments found. <a href="{{ route('payments.create') }}" class="text-blue-600 hover:text-blue-800">Create the first payment</a>.
                                @else
                                    No payments found for your company events.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($payments->hasPages())
            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
