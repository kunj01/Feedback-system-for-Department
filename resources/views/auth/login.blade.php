@extends('layouts.guest')

@section('title', 'Login - Training & Placement System')

@section('content')
<div class="w-full max-w-6xl">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <div class="flex flex-col lg:flex-row">
            <!-- Left Side - Branding -->
            <div class="lg:w-2/5 bg-gradient-to-br from-blue-600 to-indigo-700 p-12 text-white flex flex-col justify-center">
                <div class="mb-8">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-white rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 14l9-5-9-5-9 5 9 5z"></path>
                                <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold">Training & Placement</h1>
                            <p class="text-blue-200 text-sm">Tracking System</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-500 bg-opacity-30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold">Manage Projects</h3>
                            <p class="text-blue-200 text-sm">Track student training projects and evaluations</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-500 bg-opacity-30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold">Track Placements</h3>
                            <p class="text-blue-200 text-sm">Monitor student placement offers and confirmations</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-500 bg-opacity-30 rounded-lg flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold">Generate Reports</h3>
                            <p class="text-blue-200 text-sm">View analytics and export comprehensive reports</p>
                        </div>
                    </div>
                </div>

                <div class="mt-12 pt-8 border-t border-blue-500 border-opacity-30">
                    <p class="text-blue-200 text-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        Need help? Contact support@university.edu
                    </p>
                </div>
            </div>

            <!-- Right Side - Login Form -->
            <div class="lg:w-3/5 p-12">
                <div class="max-w-md mx-auto">
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back!</h2>
                        <p class="text-gray-600">Please sign in to your account to continue</p>
                    </div>

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-red-800">{{ $errors->first() }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg">
                            <p class="text-sm text-red-800">{{ session('error') }}</p>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg">
                            <p class="text-sm text-green-800">{{ session('success') }}</p>
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                        @csrf

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="label">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                                Email Address
                            </label>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="Enter your email"
                                class="input-field @error('email') border-red-500 @enderror"
                                required
                                autofocus
                            >
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="label">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Password
                            </label>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Enter your password"
                                class="input-field @error('password') border-red-500 @enderror"
                                required
                            >
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                >
                                <span class="ml-2 text-sm text-gray-700">Remember me</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                Forgot password?
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="w-full btn-primary py-3 text-lg font-semibold flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                            </svg>
                            Sign In
                        </button>

                        <!-- Quick Login Credentials -->
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <p class="text-xs font-semibold text-blue-800 mb-2">Demo Credentials:</p>
                            <div class="space-y-1 text-xs text-blue-700">
                                <p><span class="font-medium">Admin:</span> admin@system.com / admin123</p>
                                <p><span class="font-medium">TnP:</span> tnp@system.com / tnp123</p>
                                <p><span class="font-medium">Student:</span> student1@system.com / student123</p>
                            </div>
                        </div>
                    </form>

                    <!-- Footer Links -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-gray-600">
                            &copy; {{ date('Y') }} Training & Placement System. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
