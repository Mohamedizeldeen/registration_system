@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Company</h1>
                    <p class="text-gray-600 mt-2">Update company information for {{ $company->name }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('companies.show', $company->id) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-eye mr-2"></i>View Details
                    </a>
                    <a href="{{ route('companies.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Companies
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Update Company Information</h2>
                <p class="text-gray-600 mt-1">Modify the company details below</p>
            </div>

            <form action="{{ route('companies.update', $company->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Company Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Company Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $company->name) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                           required placeholder="Enter company name">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Company Address -->
                <div>
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Address
                    </label>
                    <textarea name="address" id="address" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Enter company address">{{ old('address', $company->address) }}</textarea>
                    @error('address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $company->email) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="company@example.com">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $company->phone) }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="+1 (555) 123-4567">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('companies.show', $company->id) }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200">
                        <i class="fas fa-save mr-2"></i>Update Company
                    </button>
                </div>
            </form>
        </div>

        <!-- Current Stats -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6 border border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Current Company Statistics</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">{{ $company->events->count() }}</div>
                    <div class="text-sm text-gray-600">Events</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $company->users->count() }}</div>
                    <div class="text-sm text-gray-600">Users</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-purple-600">{{ $company->created_at->diffForHumans() }}</div>
                    <div class="text-sm text-gray-600">Company Age</div>
                </div>
            </div>
        </div>

        <!-- Warning Box -->
        <div class="mt-8 bg-yellow-50 rounded-lg p-6 border border-yellow-200">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Important Note</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <p>Changing company information will affect all associated events and users. Please ensure all details are correct before saving.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
