@extends('layouts.app')

@section('page-title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Notification Header -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800">All Notifications</h3>
        <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">Mark all as read</button>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow-md divide-y">
        <!-- Sample Notification 1 -->
        <div class="p-4 hover:bg-gray-50 cursor-pointer">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-gray-900">New Project Assigned</p>
                        <span class="text-xs text-gray-500">2 hours ago</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">You have been assigned as a guide for "AI-Based Recommendation System"</p>
                    <div class="mt-2">
                        <span class="inline-block w-2 h-2 bg-blue-500 rounded-full"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sample Notification 2 -->
        <div class="p-4 hover:bg-gray-50 cursor-pointer bg-blue-50">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-gray-900">Student Placement Confirmed</p>
                        <span class="text-xs text-gray-500">5 hours ago</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">John Doe has been placed at Google with package of 25 LPA</p>
                </div>
            </div>
        </div>

        <!-- Sample Notification 3 -->
        <div class="p-4 hover:bg-gray-50 cursor-pointer">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-gray-900">Evaluation Pending</p>
                        <span class="text-xs text-gray-500">1 day ago</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">3 project evaluations are pending for review</p>
                </div>
            </div>
        </div>

        <!-- Sample Notification 4 -->
        <div class="p-4 hover:bg-gray-50 cursor-pointer">
            <div class="flex items-start space-x-3">
                <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <p class="font-medium text-gray-900">New Company Added</p>
                        <span class="text-xs text-gray-500">2 days ago</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-1">Microsoft has been added to the companies list</p>
                </div>
            </div>
        </div>

        <!-- No More Notifications -->
        <div class="p-8 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <p class="mt-2 text-gray-500">No more notifications</p>
        </div>
    </div>
</div>
@endsection
