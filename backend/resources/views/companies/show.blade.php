@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $company->name }}</h1>
                    <p class="text-gray-600 mt-2">Company details and associated events</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('companies.edit', $company->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-edit mr-2"></i>Edit Company
                    </a>
                    <a href="{{ route('companies.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Companies
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="max-w-6xl mx-auto px-4 pt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Company Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-building mr-2 text-blue-600"></i>Company Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Company Name</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $company->name }}</p>
                            </div>
                            @if($company->email)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <p class="text-gray-900">{{ $company->email }}</p>
                            </div>
                            @endif
                            @if($company->phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <p class="text-gray-900">{{ $company->phone }}</p>
                            </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Created Date</label>
                                <p class="text-gray-900">{{ $company->created_at->format('F j, Y g:i A') }}</p>
                            </div>
                        </div>
                        @if($company->address)
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <p class="text-gray-900">{{ $company->address }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Company Events -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-calendar-alt mr-2 text-green-600"></i>Company Events
                            </h2>
                            <a href="{{ route('events.create') }}?company_id={{ $company->id }}" class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm transition duration-200">
                                <i class="fas fa-plus mr-1"></i>Add Event
                            </a>
                        </div>
                    </div>
                    @if($company->events->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($company->events as $event)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $event->name }}</div>
                                                @if($event->location)
                                                    <div class="text-sm text-gray-500">{{ $event->location }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ $event->event_date->format('M j, Y') }}</div>
                                            @if($event->start_time)
                                                <div class="text-sm text-gray-500">{{ $event->start_time->format('g:i A') }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($event->type === 'conference') bg-blue-100 text-blue-800
                                                @elseif($event->type === 'workshop') bg-green-100 text-green-800
                                                @elseif($event->type === 'seminar') bg-purple-100 text-purple-800
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($event->type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('events.show', $event->id) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('events.edit', $event->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="p-6 text-center">
                            <i class="fas fa-calendar-plus text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 mb-4">No events created yet for this company.</p>
                            <a href="{{ route('events.create') }}?company_id={{ $company->id }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200">
                                Create First Event
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Company Users -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-users mr-2 text-purple-600"></i>Company Users
                            </h2>
                            <a href="{{ route('admin.CreateUsers.index') }}?company_id={{ $company->id }}" class="px-3 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded-md text-sm transition duration-200">
                                <i class="fas fa-plus mr-1"></i>Add User
                            </a>
                        </div>
                    </div>
                    @if($company->users->count() > 0)
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($company->users as $user)
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                        <span class="text-white font-medium text-sm">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                    </div>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($user->role ?? 'User') }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="p-6 text-center">
                            <i class="fas fa-user-plus text-4xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 mb-4">No users assigned to this company yet.</p>
                            <a href="{{ route('admin.CreateUsers.index') }}?company_id={{ $company->id }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition duration-200">
                                Add First User
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column - Stats & Actions -->
            <div class="space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Stats</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Events</span>
                            <span class="font-semibold text-gray-900">{{ $company->events->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Users</span>
                            <span class="font-semibold text-gray-900">{{ $company->users->count() }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Company ID</span>
                            <span class="font-mono text-sm text-gray-900">#{{ $company->id }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Created</span>
                            <span class="text-sm text-gray-900">{{ $company->created_at->format('M j, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('companies.edit', $company->id) }}" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200 text-center block">
                            <i class="fas fa-edit mr-2"></i>Edit Company
                        </a>
                        <a href="{{ route('events.create') }}?company_id={{ $company->id }}" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200 text-center block">
                            <i class="fas fa-plus mr-2"></i>Add Event
                        </a>
                        <a href="{{ route('admin.CreateUsers.index') }}?company_id={{ $company->id }}" class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition duration-200 text-center block">
                            <i class="fas fa-user-plus mr-2"></i>Add User
                        </a>
                        <form action="{{ route('companies.destroy', $company->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this company? This will also delete all associated events and users.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition duration-200">
                                <i class="fas fa-trash mr-2"></i>Delete Company
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
