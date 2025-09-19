@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Add New User</h1>
                    <p class="text-gray-900 text-2xl mt-2">For {{ $company->name }}</p>
                    <p class="text-gray-600 mt-2">Create a new user to manage events and tickets</p>
                </div>
                <a href="{{ route('companies.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>  
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">User Information</h2>
                <p class="text-gray-600 mt-1">Please fill out all required fields to create the user</p>
            </div>

            <form action="{{ route('admin.CreateUsers.post') }}" method="POST">
                @csrf
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @if ($errors->has('name'))
                                <p class="text-red-500 text-sm mt-1">{{ $errors->first('name') }}</p>
                            
                            @endif
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @if ($errors->has('email'))
                                <p class="text-red-500 text-sm mt-1">{{ $errors->first('email') }}</p>
                            @endif
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <input type="password" name="password" id="password" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                          @if ($errors->has('password'))
                                <p class="text-red-500 text-sm mt-1">{{ $errors->first('password') }}</p>
                          
                          @endif
                        </div>
                        
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" id="role" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Select a role</option>
                                <option value="organizer">Organizer</option>
                            </select>
                            @if ($errors->has('role'))
                                <p class="text-red-500 text-sm mt-1">{{ $errors->first('role') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Create User</button>
                </div>
            </form>

        </div>

        <!-- Info Box -->
        
    </div>
@endsection
