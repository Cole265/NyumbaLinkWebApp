{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Khomolanu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50" x-data="{ 
    sidebarOpen: true, 
    currentTab: 'overview',
    logout() {
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
        window.location.href = '/login';
    }
}">
    
    <div class="flex h-screen overflow-hidden">
        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gradient-to-b from-blue-600 to-indigo-700 text-white transition-all duration-300 flex-shrink-0">
            <div class="p-6 flex flex-col h-full">
                <div class="flex items-center justify-between mb-8">
                    <a href="/" class="flex items-center space-x-2" x-show="sidebarOpen">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                            <span class="text-blue-600 font-bold text-xl">KL</span>
                        </div>
                        <span class="text-xl font-bold">Admin Panel</span>
                    </a>
                    <button @click="sidebarOpen = !sidebarOpen" class="text-white hover:bg-blue-700 p-2 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>

                <nav class="space-y-2 flex-1">
                    <button @click="currentTab = 'overview'" 
                            :class="currentTab === 'overview' ? 'bg-blue-700' : 'hover:bg-blue-700'"
                            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span x-show="sidebarOpen">Overview</span>
                    </button>

                    <button @click="currentTab = 'properties'" 
                            :class="currentTab === 'properties' ? 'bg-blue-700' : 'hover:bg-blue-700'"
                            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span x-show="sidebarOpen">Properties</span>
                    </button>

                    <button @click="currentTab = 'users'" 
                            :class="currentTab === 'users' ? 'bg-blue-700' : 'hover:bg-blue-700'"
                            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span x-show="sidebarOpen">Users</span>
                    </button>

                    <button @click="currentTab = 'verification'" 
                            :class="currentTab === 'verification' ? 'bg-blue-700' : 'hover:bg-blue-700'"
                            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span x-show="sidebarOpen">Verifications</span>
                    </button>

                    <button @click="currentTab = 'transactions'" 
                            :class="currentTab === 'transactions' ? 'bg-blue-700' : 'hover:bg-blue-700'"
                            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span x-show="sidebarOpen">Transactions</span>
                    </button>

                    <button @click="currentTab = 'contact-messages'" 
                            :class="currentTab === 'contact-messages' ? 'bg-blue-700' : 'hover:bg-blue-700'"
                            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span x-show="sidebarOpen">Contact Messages</span>
                    </button>

                    <button @click="currentTab = 'account'" 
                            :class="currentTab === 'account' ? 'bg-blue-700' : 'hover:bg-blue-700'"
                            class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span x-show="sidebarOpen">My Account</span>
                    </button>
                </nav>

                <div x-show="sidebarOpen" class="mt-auto">
                    <button @click="logout()" class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-blue-700 rounded-lg transition text-left">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span>Logout</span>
                    </button>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 overflow-y-auto">
            {{-- Header --}}
            <header class="bg-white shadow-sm sticky top-0 z-40">
                <div class="px-8 py-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Admin Dashboard</h1>
                            <p class="text-gray-600">Welcome back, Admin</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                                <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-8">
                {{-- Overview Tab --}}
                <div x-show="currentTab === 'overview'" x-cloak x-data="adminOverview()" x-init="init()">
                    {{-- Statistics Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium">Total Properties</p>
                                    <h3 class="text-3xl font-bold text-gray-900 mt-2" x-text="stats.properties?.total || 0"></h3>
                                    <p class="text-green-600 text-sm mt-2" x-text="`${stats.properties?.published || 0} published`"></p>
                                </div>
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-yellow-500">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium">Pending Review</p>
                                    <h3 class="text-3xl font-bold text-gray-900 mt-2" x-text="stats.properties?.pending_review || 0"></h3>
                                    <p class="text-yellow-600 text-sm mt-2">Requires attention</p>
                                </div>
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium">Active Users</p>
                                    <h3 class="text-3xl font-bold text-gray-900 mt-2" x-text="stats.users?.total || 0"></h3>
                                    <p class="text-green-600 text-sm mt-2" x-text="`${stats.users?.landlords || 0} landlords, ${stats.users?.tenants || 0} tenants`"></p>
                                </div>
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-600">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                                    <h3 class="text-3xl font-bold text-gray-900 mt-2">
                                        MWK <span x-text="formatCurrency(stats.revenue?.total || 0)"></span>
                                    </h3>
                                    <p class="text-green-600 text-sm mt-2" x-text="`This month: MWK ${formatCurrency(stats.revenue?.this_month || 0)}`"></p>
                                </div>
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="loading" class="text-center py-8">
                        <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-blue-600 border-t-transparent"></div>
                        <p class="mt-3 text-gray-600 text-sm font-medium">Loading overview data...</p>
                    </div>

                    {{-- Recent Activity --}}
                    <div x-show="!loading" class="grid grid-cols-1 lg:grid-cols-2 gap-6" x-cloak>
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900">Recent Properties</h3>
                                <button @click="$parent.currentTab = 'properties'" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">View All →</button>
                                        </div>
                            <div x-show="recentProperties.length === 0" class="text-center py-8 text-gray-500 text-sm">
                                No recent properties
                                    </div>
                            <div x-show="recentProperties.length > 0" class="space-y-4">
                                <template x-for="property in recentProperties" :key="property.id">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div class="flex items-center space-x-4">
                                            <img :src="property.primary_image_url || 'https://placehold.co/80x80/e2e8f0/64748b?text=No+Image'" class="w-16 h-16 rounded-lg object-cover">
                                        <div>
                                                <h4 class="font-semibold text-gray-900 line-clamp-1" x-text="property.title"></h4>
                                                <p class="text-sm text-gray-600" x-text="`${property.city || ''}${property.district ? ', ' + property.district : ''}`"></p>
                                        </div>
                                    </div>
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-medium capitalize"
                                            :class="{
                                                'bg-yellow-100 text-yellow-700': property.status === 'pending_review',
                                                'bg-green-100 text-green-700': property.status === 'published',
                                                'bg-gray-100 text-gray-700': property.status !== 'pending_review' && property.status !== 'published'
                                            }"
                                            x-text="property.status.replace('_', ' ')"
                                        ></span>
                                </div>
                                </template>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900">Pending Verifications</h3>
                                <button @click="$parent.currentTab = 'verification'" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">View All →</button>
                                    </div>
                            <div x-show="pendingVerifications.length === 0" class="text-center py-8 text-gray-500 text-sm">
                                No pending verifications
                                    </div>
                            <div x-show="pendingVerifications.length > 0" class="space-y-4">
                                <template x-for="verification in pendingVerifications" :key="verification.id">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <div>
                                            <h4 class="font-semibold text-gray-900" x-text="verification.landlord_name"></h4>
                                        <p class="text-sm text-gray-600">Landlord Verification</p>
                                            <p class="text-xs text-gray-500 mt-1" x-text="`Submitted: ${new Date(verification.created_at).toLocaleDateString()}`"></p>
                                    </div>
                                        <div class="flex space-x-2">
                                            <button @click="$parent.currentTab = 'verification'" class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Review</button>
                                        </div>
                                </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Properties Tab --}}
                <div x-show="currentTab === 'properties'" x-cloak x-data="adminProperties()" x-init="init()">
                    <div class="bg-white rounded-xl shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                                <div>
                                <h2 class="text-2xl font-bold text-gray-900">Property Management</h2>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Manage all published and pending properties on the platform.
                                    </p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <button
                                        @click="filterStatus = 'all'"
                                        :class="filterStatus === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                    >
                                        All
                                    </button>
                                    <button
                                        @click="filterStatus = 'pending_review'"
                                        :class="filterStatus === 'pending_review' ? 'bg-yellow-500 text-white' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200'"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                    >
                                        Pending
                                    </button>
                                    <button
                                        @click="filterStatus = 'published'"
                                        :class="filterStatus === 'published' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200'"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                    >
                                        Published
                                    </button>
                                </div>
                            </div>
                            <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                <div class="relative w-full md:w-64">
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input
                                        type="text"
                                        x-model="search"
                                        placeholder="Search by title, city, owner..."
                                        class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                </div>
                                <div class="text-xs text-gray-500">
                                    Showing <span class="font-semibold text-blue-600" x-text="filteredProperties.length"></span>
                                    of <span class="font-semibold text-blue-600" x-text="properties.length"></span> properties
                                </div>
                            </div>
                        </div>

                        <div x-show="loading" class="p-10 text-center">
                            <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-blue-600 border-t-transparent"></div>
                            <p class="mt-3 text-gray-600 text-sm font-medium">Loading properties...</p>
                        </div>

                        <div x-show="!loading && filteredProperties.length === 0" class="p-10 text-center" x-cloak>
                            <div class="text-5xl mb-3">🏠</div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">No properties found</h3>
                            <p class="text-sm text-gray-600">Try adjusting the filters or search term.</p>
                        </div>

                        <div x-show="!loading && filteredProperties.length > 0" class="overflow-x-auto" x-cloak>
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Property</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="property in filteredProperties" :key="property.id">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                    <img :src="property.primary_image_url || 'https://placehold.co/80x80/e2e8f0/64748b?text=No+Image'" class="w-12 h-12 rounded-lg object-cover">
                                                <div class="ml-4">
                                                        <div class="font-semibold text-gray-900 line-clamp-1" x-text="property.title"></div>
                                                        <div class="text-xs text-gray-600" x-text="`${property.city || ''}${property.district ? ', ' + property.district : ''}`"></div>
                                                </div>
                                            </div>
                                        </td>
                                            <td class="px-6 py-4 text-sm text-gray-900" x-text="property.landlord_name || '—'"></td>
                                            <td class="px-6 py-4 text-sm text-gray-900 capitalize" x-text="property.property_type"></td>
                                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                                MWK <span x-text="Number(property.price || 0).toLocaleString()"></span>
                                        </td>
                                        <td class="px-6 py-4">
                                                <span
                                                    class="px-3 py-1 rounded-full text-xs font-medium capitalize"
                                                    :class="{
                                                        'bg-yellow-100 text-yellow-700': property.status === 'pending_review',
                                                        'bg-green-100 text-green-700': property.status === 'published',
                                                        'bg-gray-100 text-gray-700': property.status !== 'pending_review' && property.status !== 'published'
                                                    }"
                                                    x-text="property.status.replace('_', ' ')"
                                                ></span>
                                        </td>
                                        <td class="px-6 py-4">
                                                <div class="flex flex-wrap gap-2">
                                                    <button
                                                        type="button"
                                                        @click="openPropertyViewModal(property.id)"
                                                        class="px-3 py-1 bg-blue-600 text-white rounded-lg text-xs hover:bg-blue-700"
                                                    >
                                                        View
                                                    </button>
                                                    <button
                                                        x-show="property.status === 'pending_review'"
                                                        @click="approve(property)"
                                                        class="px-3 py-1 bg-green-600 text-white rounded-lg text-xs hover:bg-green-700"
                                                    >
                                                        Approve
                                                    </button>
                                                    <button
                                                        x-show="property.status === 'pending_review'"
                                                        @click="reject(property)"
                                                        class="px-3 py-1 bg-red-600 text-white rounded-lg text-xs hover:bg-red-700"
                                                    >
                                                        Reject
                                                    </button>
                                            </div>
                                        </td>
                                    </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- Property View Modal --}}
                    @include('components.PropertyViewModal')
                </div>

                {{-- Users Tab --}}
                <div x-show="currentTab === 'users'" x-cloak x-data="adminUsers()" x-init="init()">
                    <div class="bg-white rounded-xl shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                                <div>
                            <h2 class="text-2xl font-bold text-gray-900">User Management</h2>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Manage all users registered on the platform.
                                    </p>
                        </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <button
                                        @click="filterRole = 'all'"
                                        :class="filterRole === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                    >
                                        All
                                    </button>
                                    <button
                                        @click="filterRole = 'landlord'"
                                        :class="filterRole === 'landlord' ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700 hover:bg-blue-200'"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                    >
                                        Landlords
                                    </button>
                                    <button
                                        @click="filterRole = 'tenant'"
                                        :class="filterRole === 'tenant' ? 'bg-purple-600 text-white' : 'bg-purple-100 text-purple-700 hover:bg-purple-200'"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                    >
                                        Tenants
                                    </button>
                                </div>
                            </div>
                            <div class="mt-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                <div class="relative w-full md:w-64">
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    <input
                                        type="text"
                                        x-model="search"
                                        placeholder="Search by name, email, phone..."
                                        class="w-full pl-9 pr-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                </div>
                                <div class="text-xs text-gray-500">
                                    Showing <span class="font-semibold text-blue-600" x-text="filteredUsers.length"></span>
                                    of <span class="font-semibold text-blue-600" x-text="users.length"></span> users
                                </div>
                            </div>
                        </div>

                        <div x-show="loading" class="p-10 text-center">
                            <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-blue-600 border-t-transparent"></div>
                            <p class="mt-3 text-gray-600 text-sm font-medium">Loading users...</p>
                        </div>

                        <div x-show="!loading && filteredUsers.length === 0" class="p-10 text-center" x-cloak>
                            <div class="text-5xl mb-3">👥</div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">No users found</h3>
                            <p class="text-sm text-gray-600">Try adjusting the filters or search term.</p>
                        </div>

                        <div x-show="!loading && filteredUsers.length > 0" class="overflow-x-auto" x-cloak>
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verification</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="user in filteredUsers" :key="user.id">
                                    <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="font-semibold text-gray-900" x-text="user.name"></div>
                                                <span x-show="user.is_suspended"
                                                      class="inline-block mt-1 px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-700">
                                                    Suspended
                                                </span>
                                        </td>
                                            <td class="px-6 py-4 text-sm text-gray-600" x-text="user.email"></td>
                                            <td class="px-6 py-4 text-sm text-gray-600" x-text="user.phone || '—'"></td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-3 py-1 rounded-full text-xs font-medium capitalize"
                                                    :class="{
                                                        'bg-blue-100 text-blue-700': user.role === 'landlord',
                                                        'bg-purple-100 text-purple-700': user.role === 'tenant',
                                                        'bg-gray-100 text-gray-700': user.role === 'admin'
                                                    }"
                                                    x-text="user.role"
                                                ></span>
                                        </td>
                                            <td class="px-6 py-4">
                                                <span
                                                    class="px-3 py-1 rounded-full text-xs font-medium"
                                                    :class="{
                                                        'bg-green-100 text-green-700': user.verification_status === 'approved' || (user.role === 'tenant' && user.is_verified),
                                                        'bg-yellow-100 text-yellow-700': user.verification_status === 'pending',
                                                        'bg-red-100 text-red-700': user.verification_status === 'rejected',
                                                        'bg-gray-100 text-gray-700': !user.verification_status && !user.is_verified
                                                    }"
                                                    x-text="user.role === 'landlord' ? (user.verification_status || 'pending') : (user.is_verified ? 'verified' : 'unverified')"
                                                ></span>
                                                <template x-if="user.role === 'landlord' && user.total_properties !== undefined">
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        <span x-text="user.total_properties"></span> properties
                                                    </div>
                                                </template>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600" x-text="new Date(user.created_at).toLocaleDateString()"></td>
                                            <td class="px-6 py-4">
                                                <div class="flex flex-wrap gap-2 items-center">
                                                    <span x-show="user.is_suspended" class="px-3 py-1 rounded-lg text-xs font-medium bg-red-100 text-red-700">
                                                        Suspended
                                                    </span>
                                                    <button
                                                        type="button"
                                                        @click="openEditUser(user)"
                                                        class="px-3 py-1 bg-gray-600 text-white rounded-lg text-xs hover:bg-gray-700"
                                                    >
                                                        Edit
                                                    </button>
                                                    <button
                                                        type="button"
                                                        @click="openResetPasswordModal(user)"
                                                        class="px-3 py-1 bg-blue-600 text-white rounded-lg text-xs hover:bg-blue-700"
                                                    >
                                                        Reset password
                                                    </button>
                                                    <button
                                                        x-show="user.role !== 'admin' && !user.is_suspended"
                                                        @click="suspendUser(user)"
                                                        class="px-3 py-1 bg-red-600 text-white rounded-lg text-xs hover:bg-red-700"
                                                    >
                                                        Suspend
                                                    </button>
                                                </div>
                                        </td>
                                    </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Edit user modal --}}
                    <div x-show="showEditUserModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" @click.self="showEditUserModal = false">
                        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6" @click.stop>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit user</h3>
                            <template x-if="editUser">
                                <form @submit.prevent="saveEditUser()" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                        <input type="text" x-model="editUserForm.name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                        <input type="email" x-model="editUserForm.email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                        <input type="text" x-model="editUserForm.phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <p x-show="editUserMessage" class="text-sm" :class="editUserError ? 'text-red-600' : 'text-green-600'" x-text="editUserMessage"></p>
                                    <div class="flex gap-2">
                                        <button type="button" @click="showEditUserModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                                        <button type="submit" :disabled="editUserSaving" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">Save</button>
                                    </div>
                                </form>
                            </template>
                        </div>
                    </div>

                    {{-- Reset password modal --}}
                    <div x-show="showResetPasswordModal" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4" @click.self="showResetPasswordModal = false">
                        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6" @click.stop>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Reset password</h3>
                            <p class="text-sm text-gray-600 mb-4" x-show="resetPasswordUser" x-text="`Set a new password for ${resetPasswordUser?.name || 'this user'}.`"></p>
                            <template x-if="resetPasswordUser">
                                <form @submit.prevent="saveResetPassword()" class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                                        <input type="password" x-model="resetPasswordForm.password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Min 8 characters" required minlength="8">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm password</label>
                                        <input type="password" x-model="resetPasswordForm.password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required minlength="8">
                                    </div>
                                    <p x-show="resetPasswordMessage" class="text-sm" :class="resetPasswordError ? 'text-red-600' : 'text-green-600'" x-text="resetPasswordMessage"></p>
                                    <div class="flex gap-2">
                                        <button type="button" @click="showResetPasswordModal = false" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                                        <button type="submit" :disabled="resetPasswordSaving" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">Reset password</button>
                                    </div>
                                </form>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Verification Tab --}}
                <div x-show="currentTab === 'verification'" x-cloak x-data="adminVerifications()" x-init="init()">
                    <div class="bg-white rounded-xl shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                                    <div>
                                    <h2 class="text-2xl font-bold text-gray-900">Verification Management</h2>
                                    <p class="text-sm text-gray-500 mt-1">
                                        Review and approve landlord verification requests.
                                    </p>
                                    </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <button
                                        @click="filterStatus = 'all'"
                                        :class="filterStatus === 'all' ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                    >
                                        All
                                    </button>
                                    <button
                                        @click="filterStatus = 'pending'"
                                        :class="filterStatus === 'pending' ? 'bg-yellow-500 text-white' : 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200'"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                    >
                                        Pending
                                    </button>
                                    <button
                                        @click="filterStatus = 'approved'"
                                        :class="filterStatus === 'approved' ? 'bg-green-600 text-white' : 'bg-green-100 text-green-700 hover:bg-green-200'"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                    >
                                        Approved
                                    </button>
                                    <button
                                        @click="filterStatus = 'rejected'"
                                        :class="filterStatus === 'rejected' ? 'bg-red-600 text-white' : 'bg-red-100 text-red-700 hover:bg-red-200'"
                                        class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                    >
                                        Rejected
                                    </button>
                                </div>
                                    </div>
                            <div class="text-xs text-gray-500">
                                Showing <span class="font-semibold text-blue-600" x-text="filteredVerifications.length"></span>
                                of <span class="font-semibold text-blue-600" x-text="verifications.length"></span> verifications
                                    </div>
                                </div>

                        <div x-show="loading" class="p-10 text-center">
                            <div class="inline-block animate-spin rounded-full h-10 w-10 border-4 border-blue-600 border-t-transparent"></div>
                            <p class="mt-3 text-gray-600 text-sm font-medium">Loading verifications...</p>
                                </div>

                        <div x-show="!loading && filteredVerifications.length === 0" class="p-10 text-center" x-cloak>
                            <div class="text-5xl mb-3">✅</div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">No verifications found</h3>
                            <p class="text-sm text-gray-600">Try adjusting the filter.</p>
                            </div>

                        <div x-show="!loading && filteredVerifications.length > 0" class="p-6 space-y-6" x-cloak>
                            <template x-for="verification in filteredVerifications" :key="verification.id">
                                <div class="border border-gray-200 rounded-lg p-6 hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                            <h3 class="text-lg font-semibold text-gray-900" x-text="verification.landlord_name"></h3>
                                            <p class="text-sm text-gray-600" x-text="`${verification.landlord_email || ''}${verification.landlord_phone ? ' • ' + verification.landlord_phone : ''}`"></p>
                                            <template x-if="verification.business_name">
                                                <p class="text-sm text-gray-500 mt-1" x-text="`Business: ${verification.business_name}`"></p>
                                            </template>
                                        <span class="inline-block mt-2 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Landlord</span>
                                    </div>
                                        <span
                                            class="px-3 py-1 rounded-full text-xs font-medium capitalize"
                                            :class="{
                                                'bg-yellow-100 text-yellow-700': verification.status === 'pending',
                                                'bg-green-100 text-green-700': verification.status === 'approved',
                                                'bg-red-100 text-red-700': verification.status === 'rejected'
                                            }"
                                            x-text="verification.status"
                                        ></span>
                                </div>
                                
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <p class="text-sm font-semibold text-gray-700 mb-2">National ID Document</p>
                                            <template x-if="verification.id_document_url">
                                                <div class="space-y-2">
                                                    <a
                                                        :href="verification.id_document_url"
                                                        target="_blank"
                                                        class="inline-flex items-center space-x-2 text-blue-600 hover:text-blue-800 text-sm font-medium"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        <span>View Document →</span>
                                                    </a>
                                                    <div class="text-xs text-gray-500">Click to view in new tab</div>
                                    </div>
                                            </template>
                                            <template x-if="!verification.id_document_url">
                                                <p class="text-sm text-gray-400">No document uploaded</p>
                                            </template>
                                        </div>
                                        <div class="border border-gray-200 rounded-lg p-4">
                                        <p class="text-sm font-semibold text-gray-700 mb-2">Proof of Ownership</p>
                                            <template x-if="verification.proof_url">
                                                <div class="space-y-2">
                                                    <a
                                                        :href="verification.proof_url"
                                                        target="_blank"
                                                        class="inline-flex items-center space-x-2 text-blue-600 hover:text-blue-800 text-sm font-medium"
                                                    >
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                        </svg>
                                                        <span>View Document →</span>
                                                    </a>
                                                    <div class="text-xs text-gray-500">Click to view in new tab</div>
                                                </div>
                                            </template>
                                            <template x-if="!verification.proof_url">
                                                <p class="text-sm text-gray-400">No document uploaded</p>
                                            </template>
                                    </div>
                                </div>

                                    <template x-if="verification.status === 'pending'">
                                        <div>
                                <div class="mb-4">
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Admin Notes (Optional)</label>
                                                <textarea
                                                    x-model="verification.admin_notes"
                                                    rows="3"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    placeholder="Add notes about this verification..."
                                                ></textarea>
                                </div>

                                <div class="flex space-x-3">
                                                <button
                                                    @click="approveVerification(verification)"
                                                    class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-semibold transition"
                                                >
                                        ✓ Approve Verification
                                    </button>
                                                <button
                                                    @click="rejectVerification(verification)"
                                                    class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold transition"
                                                >
                                        ✗ Reject Verification
                                    </button>
                                </div>
                            </div>
                                    </template>

                                    <template x-if="verification.status !== 'pending' && verification.admin_notes">
                                        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                            <p class="text-xs font-semibold text-gray-700 mb-1">Admin Notes:</p>
                                            <p class="text-sm text-gray-600" x-text="verification.admin_notes"></p>
                                        </div>
                                    </template>

                                    <div class="mt-4 text-xs text-gray-500">
                                        Submitted: <span x-text="new Date(verification.created_at).toLocaleString()"></span>
                                        <template x-if="verification.reviewed_at">
                                            <span> • Reviewed: <span x-text="new Date(verification.reviewed_at).toLocaleString()"></span></span>
                                        </template>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Transactions Tab --}}
                <div x-show="currentTab === 'transactions'" x-cloak>
                    <div class="bg-white rounded-xl shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-2xl font-bold text-gray-900">Recent Transactions</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Reference</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-mono text-gray-900">TXN-001234</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">John Banda</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">Listing Fee</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">MWK 5,000</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">Airtel Money</td>
                                        <td class="px-6 py-4"><span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Completed</span></td>
                                        <td class="px-6 py-4 text-sm text-gray-600">2025-01-20</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-mono text-gray-900">TXN-001235</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">Mary Phiri</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">Boost Fee</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">MWK 15,000</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">TNM Mpamba</td>
                                        <td class="px-6 py-4"><span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Completed</span></td>
                                        <td class="px-6 py-4 text-sm text-gray-600">2025-01-19</td>
                                    </tr>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 text-sm font-mono text-gray-900">TXN-001236</td>
                                        <td class="px-6 py-4 text-sm text-gray-900">Peter Mwale</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">Registration Fee</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">MWK 2,000</td>
                                        <td class="px-6 py-4 text-sm text-gray-600">Bank Transfer</td>
                                        <td class="px-6 py-4"><span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Pending</span></td>
                                        <td class="px-6 py-4 text-sm text-gray-600">2025-01-18</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Contact Messages Tab --}}
                <div x-show="currentTab === 'contact-messages'" x-cloak x-data="adminContactMessages()" x-init="init()">
                    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900">Contact Messages</h2>
                                <p class="text-sm text-gray-600">Messages sent from the public contact form.</p>
                            </div>
                        </div>

                        <div x-show="loading" class="py-10 text-center text-gray-500">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
                            <p class="text-sm">Loading messages...</p>
                        </div>

                        <div x-show="!loading && messages.length === 0" class="py-10 text-center text-gray-500">
                            <p class="text-sm">No contact messages have been received yet.</p>
                        </div>

                        <div x-show="!loading && messages.length > 0" class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Name</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Email</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Message</th>
                                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Received</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    <template x-for="message in messages" :key="message.id">
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 font-medium text-gray-900" x-text="message.name"></td>
                                            <td class="px-4 py-2 text-blue-600">
                                                <a :href="`mailto:${message.email}`" class="hover:underline" x-text="message.email"></a>
                                            </td>
                                            <td class="px-4 py-2 text-gray-700">
                                                <span x-text="message.message.length > 80 ? message.message.substring(0, 80) + '…' : message.message"></span>
                                            </td>
                                            <td class="px-4 py-2 text-gray-500" x-text="new Date(message.created_at).toLocaleString()"></td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- My Account Tab --}}
                <div x-show="currentTab === 'account'" x-cloak x-data="adminAccount()" x-init="loadProfile()">
                    <div class="max-w-2xl space-y-6">
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Personal information</h2>
                            <div x-show="profileLoading" class="py-4 text-center text-gray-500">
                                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                            </div>
                            <form x-show="!profileLoading && user" @submit.prevent="updateProfile()" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                    <input type="text" x-model="profileForm.name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Your name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" x-model="profileForm.email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="admin@example.com">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                    <input type="text" x-model="profileForm.phone" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="+265...">
                                </div>
                                <p x-show="profileMessage" class="text-sm" :class="profileError ? 'text-red-600' : 'text-green-600'" x-text="profileMessage"></p>
                                <button type="submit" :disabled="profileSaving" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 font-medium">
                                    <span x-show="!profileSaving">Save changes</span>
                                    <span x-show="profileSaving">Saving...</span>
                                </button>
                            </form>
                        </div>
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Change password</h2>
                            <form @submit.prevent="updatePassword()" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Current password</label>
                                    <input type="password" x-model="passwordForm.current_password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="••••••••" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">New password</label>
                                    <input type="password" x-model="passwordForm.password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="••••••••" required minlength="8">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm new password</label>
                                    <input type="password" x-model="passwordForm.password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="••••••••" required minlength="8">
                                </div>
                                <p x-show="passwordMessage" class="text-sm" :class="passwordError ? 'text-red-600' : 'text-green-600'" x-text="passwordMessage"></p>
                                <button type="submit" :disabled="passwordSaving" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 font-medium">
                                    <span x-show="!passwordSaving">Update password</span>
                                    <span x-show="passwordSaving">Updating...</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminAccount', () => ({
                user: null,
                profileLoading: true,
                profileForm: { name: '', email: '', phone: '' },
                profileSaving: false,
                profileMessage: '',
                profileError: false,
                passwordForm: { current_password: '', password: '', password_confirmation: '' },
                passwordSaving: false,
                passwordMessage: '',
                passwordError: false,

                async loadProfile() {
                    this.profileLoading = true;
                    const token = localStorage.getItem('auth_token');
                    if (!token) return;
                    try {
                        const response = await fetch('/api/v1/admin/profile', {
                            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                        });
                        const result = await response.json();
                        if (result.success && result.data && result.data.user) {
                            this.user = result.data.user;
                            this.profileForm = {
                                name: this.user.name || '',
                                email: this.user.email || '',
                                phone: this.user.phone || ''
                            };
                        }
                    } catch (e) {
                        console.error('Load profile error:', e);
                    } finally {
                        this.profileLoading = false;
                    }
                },

                async updateProfile() {
                    this.profileSaving = true;
                    this.profileMessage = '';
                    this.profileError = false;
                    const token = localStorage.getItem('auth_token');
                    try {
                        const response = await fetch('/api/v1/admin/profile', {
                            method: 'PUT',
                            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                            body: JSON.stringify(this.profileForm)
                        });
                        const result = await response.json();
                        if (response.ok && result.success) {
                            this.profileMessage = 'Profile updated successfully.';
                            this.user = result.data;
                        } else {
                            this.profileError = true;
                            this.profileMessage = result.message || 'Failed to update profile.';
                        }
                    } catch (e) {
                        this.profileError = true;
                        this.profileMessage = 'An error occurred. Please try again.';
                    } finally {
                        this.profileSaving = false;
                    }
                },

                async updatePassword() {
                    this.passwordSaving = true;
                    this.passwordMessage = '';
                    this.passwordError = false;
                    const token = localStorage.getItem('auth_token');
                    try {
                        const response = await fetch('/api/v1/admin/password', {
                            method: 'PUT',
                            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                            body: JSON.stringify(this.passwordForm)
                        });
                        const result = await response.json();
                        if (response.ok && result.success) {
                            this.passwordMessage = 'Password updated successfully.';
                            this.passwordForm = { current_password: '', password: '', password_confirmation: '' };
                        } else {
                            this.passwordError = true;
                            this.passwordMessage = (result.errors && (result.errors.current_password && result.errors.current_password[0])) || result.message || 'Failed to update password.';
                        }
                    } catch (e) {
                        this.passwordError = true;
                        this.passwordMessage = 'An error occurred. Please try again.';
                    } finally {
                        this.passwordSaving = false;
                    }
                }
            }));

            Alpine.data('adminProperties', () => ({
                loading: true,
                properties: [],
                filterStatus: 'all',
                search: '',
                showPropertyViewModal: false,
                viewModalProperty: null,
                viewModalLoading: false,
                viewModalError: null,
                viewModalCurrentImage: null,

                get filteredProperties() {
                    return this.properties.filter((p) => {
                        const matchesStatus =
                            this.filterStatus === 'all' || p.status === this.filterStatus;
                        const term = this.search.toLowerCase();
                        const matchesSearch =
                            !term ||
                            (p.title && p.title.toLowerCase().includes(term)) ||
                            (p.city && p.city.toLowerCase().includes(term)) ||
                            (p.district && p.district.toLowerCase().includes(term)) ||
                            (p.landlord_name && p.landlord_name.toLowerCase().includes(term));
                        return matchesStatus && matchesSearch;
                    });
                },

                async init() {
                    await this.loadProperties();
                },

                async loadProperties() {
                    this.loading = true;
                    const token = localStorage.getItem('auth_token');
                    try {
                        const [publishedRes, pendingRes] = await Promise.all([
                            fetch('/api/v1/properties?per_page=100', {
                                headers: { Accept: 'application/json' },
                            }),
                            fetch('/api/v1/admin/properties/pending', {
                                headers: {
                                    Accept: 'application/json',
                                    ...(token ? { Authorization: `Bearer ${token}` } : {}),
                                },
                            }),
                        ]);

                        const publishedJson = await publishedRes.json();
                        const pendingJson = await pendingRes.json();

                        const published = publishedJson.success && publishedJson.data
                            ? (publishedJson.data.data || [])
                            : [];

                        const pending = pendingJson.success && pendingJson.data
                            ? (pendingJson.data.data || [])
                            : [];

                        const normalizedPublished = published.map((p) => ({
                            id: p.id,
                            title: p.title,
                            city: p.city,
                            district: p.district,
                            price: p.price,
                            status: p.status || 'published',
                            property_type: p.property_type,
                            landlord_name: p.landlord_name || (p.landlord && p.landlord.user ? p.landlord.user.name : null),
                            primary_image_url:
                                p.primary_image_url ||
                                (p.primaryImage ? `/storage/${p.primaryImage.image_path}` : null),
                        }));

                        const normalizedPending = pending.map((p) => ({
                            id: p.id,
                            title: p.title,
                            city: p.city,
                            district: p.district,
                            price: p.price,
                            status: p.status || 'pending_review',
                            property_type: p.property_type,
                            landlord_name: p.landlord_name || (p.landlord && p.landlord.user ? p.landlord.user.name : null),
                            primary_image_url: p.primary_image_url || null,
                        }));

                        this.properties = [...normalizedPending, ...normalizedPublished];
                    } catch (error) {
                        console.error('Error loading admin properties:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                async approve(property) {
                    const token = localStorage.getItem('auth_token');
                    if (!token) {
                        alert('Please login as admin.');
                        window.location.href = '/login';
                        return;
                    }

                    if (!confirm('Approve this property for publishing?')) {
                        return;
                    }

                    try {
                        const response = await fetch(`/api/v1/admin/properties/${property.id}/approve`, {
                            method: 'POST',
                            headers: {
                                Accept: 'application/json',
                                Authorization: `Bearer ${token}`,
                            },
                        });

                        const data = await response.json();
                        if (response.ok && data.success) {
                            property.status = 'published';
                            alert('Property approved and published.');
                        } else {
                            alert(data.message || 'Failed to approve property.');
                        }
                    } catch (error) {
                        console.error('Error approving property:', error);
                        alert('An error occurred while approving the property.');
                    }
                },

                async reject(property) {
                    const token = localStorage.getItem('auth_token');
                    if (!token) {
                        alert('Please login as admin.');
                        window.location.href = '/login';
                        return;
                    }

                    const reason = prompt('Enter a reason for rejection:');
                    if (!reason) {
                        return;
                    }

                    try {
                        const response = await fetch(`/api/v1/admin/properties/${property.id}/reject`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                Accept: 'application/json',
                                Authorization: `Bearer ${token}`,
                            },
                            body: JSON.stringify({ reason }),
                        });

                        const data = await response.json();
                        if (response.ok && data.success) {
                            property.status = 'draft';
                            alert('Property rejected and set back to draft.');
                        } else {
                            alert(data.message || 'Failed to reject property.');
                        }
                    } catch (error) {
                        console.error('Error rejecting property:', error);
                        alert('An error occurred while rejecting the property.');
                    }
                },

                async openPropertyViewModal(propertyId) {
                    this.showPropertyViewModal = true;
                    this.viewModalProperty = null;
                    this.viewModalError = null;
                    this.viewModalCurrentImage = null;
                    this.viewModalLoading = true;
                    const token = localStorage.getItem('auth_token');
                    if (!token) {
                        this.viewModalError = 'Please log in to view property details.';
                        this.viewModalLoading = false;
                        return;
                    }
                    try {
                        const response = await fetch(`/api/v1/admin/properties/${propertyId}`, {
                            headers: { 'Accept': 'application/json', 'Authorization': `Bearer ${token}` }
                        });
                        const data = await response.json();
                        if (data.success && data.data) {
                            this.viewModalProperty = data.data;
                            const imgs = this.viewModalProperty.formatted_images;
                            if (imgs && imgs.length) this.viewModalCurrentImage = imgs.find(i => i.is_primary)?.url || imgs[0]?.url;
                        } else {
                            this.viewModalError = data.message || 'Failed to load property.';
                        }
                    } catch (e) {
                        this.viewModalError = 'Failed to load property.';
                    } finally {
                        this.viewModalLoading = false;
                    }
                },

                closePropertyViewModal() {
                    this.showPropertyViewModal = false;
                    this.viewModalProperty = null;
                    this.viewModalError = null;
                    this.viewModalCurrentImage = null;
                },
            }));
        });

        // Admin Users Component
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminUsers', () => ({
                users: [],
                loading: false,
                filterRole: 'all',
                search: '',
                showEditUserModal: false,
                editUser: null,
                editUserForm: { name: '', email: '', phone: '' },
                editUserSaving: false,
                editUserMessage: '',
                editUserError: false,
                showResetPasswordModal: false,
                resetPasswordUser: null,
                resetPasswordForm: { password: '', password_confirmation: '' },
                resetPasswordSaving: false,
                resetPasswordMessage: '',
                resetPasswordError: false,

                get filteredUsers() {
                    let filtered = this.users;

                    // Filter by role
                    if (this.filterRole !== 'all') {
                        filtered = filtered.filter((u) => u.role === this.filterRole);
                    }

                    // Filter by search
                    if (this.search.trim()) {
                        const query = this.search.toLowerCase().trim();
                        filtered = filtered.filter(
                            (u) =>
                                u.name?.toLowerCase().includes(query) ||
                                u.email?.toLowerCase().includes(query) ||
                                u.phone?.toLowerCase().includes(query)
                        );
                    }

                    return filtered;
                },

                async init() {
                    await this.loadUsers();
                },

                async loadUsers() {
                    this.loading = true;
                    const token = localStorage.getItem('auth_token');
                    if (!token) {
                        console.error('No auth token found');
                        this.loading = false;
                        return;
                    }

                    try {
                        // Load all users with pagination - we'll fetch multiple pages to get all users
                        let allUsers = [];
                        let currentPage = 1;
                        let hasMore = true;

                        while (hasMore) {
                            const response = await fetch(
                                `/api/v1/admin/users?page=${currentPage}&per_page=100`,
                                {
                                    headers: {
                                        Accept: 'application/json',
                                        ...(token ? { Authorization: `Bearer ${token}` } : {}),
                                    },
                                }
                            );

                            const data = await response.json();
                            if (data.success && data.data) {
                                const pageUsers = data.data.data || [];
                                allUsers = [...allUsers, ...pageUsers];

                                // Check if there are more pages
                                hasMore = data.data.current_page < data.data.last_page;
                                currentPage++;
                            } else {
                                hasMore = false;
                            }
                        }

                        // Normalize user data
                        this.users = allUsers.map((u) => ({
                            id: u.id,
                            name: u.name,
                            email: u.email,
                            phone: u.phone,
                            role: u.role,
                            is_verified: u.is_verified,
                            is_suspended: u.is_suspended || false,
                            suspension_reason: u.suspension_reason || null,
                            suspended_at: u.suspended_at || null,
                            verification_status: u.verification_status || (u.role === 'landlord' ? 'pending' : null),
                            total_properties: u.total_properties || 0,
                            avg_rating: u.avg_rating || 0,
                            created_at: u.created_at,
                        }));
                    } catch (error) {
                        console.error('Error loading admin users:', error);
                        alert('Failed to load users. Please refresh the page.');
                    } finally {
                        this.loading = false;
                    }
                },

                async suspendUser(user) {
                    const token = localStorage.getItem('auth_token');
                    if (!token) {
                        alert('Please login as admin.');
                        window.location.href = '/login';
                        return;
                    }

                    if (user.role === 'admin') {
                        alert('Cannot suspend admin users.');
                        return;
                    }

                    const reason = prompt('Enter a reason for suspension:');
                    if (!reason) {
                        return;
                    }

                    if (!confirm(`Suspend user "${user.name}"?`)) {
                        return;
                    }

                    try {
                        const response = await fetch(`/api/v1/admin/users/${user.id}/suspend`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                Accept: 'application/json',
                                Authorization: `Bearer ${token}`,
                            },
                            body: JSON.stringify({ reason }),
                        });

                        const data = await response.json();
                        if (response.ok && data.success) {
                            user.is_verified = false;
                            alert('User suspended successfully.');
                            // Reload users to reflect changes
                            await this.loadUsers();
                        } else {
                            alert(data.message || 'Failed to suspend user.');
                        }
                    } catch (error) {
                        console.error('Error suspending user:', error);
                        alert('An error occurred while suspending the user.');
                    }
                },

                openEditUser(user) {
                    this.editUser = user;
                    this.editUserForm = { name: user.name || '', email: user.email || '', phone: user.phone || '' };
                    this.editUserMessage = '';
                    this.editUserError = false;
                    this.showEditUserModal = true;
                },

                async saveEditUser() {
                    if (!this.editUser) return;
                    this.editUserSaving = true;
                    this.editUserMessage = '';
                    this.editUserError = false;
                    const token = localStorage.getItem('auth_token');
                    try {
                        const response = await fetch(`/api/v1/admin/users/${this.editUser.id}`, {
                            method: 'PUT',
                            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                            body: JSON.stringify(this.editUserForm)
                        });
                        const result = await response.json();
                        if (response.ok && result.success) {
                            this.editUserMessage = 'User updated successfully.';
                            Object.assign(this.editUser, this.editUserForm);
                            setTimeout(() => { this.showEditUserModal = false; }, 1500);
                        } else {
                            this.editUserError = true;
                            this.editUserMessage = result.message || 'Failed to update user.';
                        }
                    } catch (e) {
                        this.editUserError = true;
                        this.editUserMessage = 'An error occurred. Please try again.';
                    } finally {
                        this.editUserSaving = false;
                    }
                },

                openResetPasswordModal(user) {
                    this.resetPasswordUser = user;
                    this.resetPasswordForm = { password: '', password_confirmation: '' };
                    this.resetPasswordMessage = '';
                    this.resetPasswordError = false;
                    this.showResetPasswordModal = true;
                },

                async saveResetPassword() {
                    if (!this.resetPasswordUser || this.resetPasswordForm.password !== this.resetPasswordForm.password_confirmation) {
                        this.resetPasswordError = true;
                        this.resetPasswordMessage = 'Passwords do not match.';
                        return;
                    }
                    this.resetPasswordSaving = true;
                    this.resetPasswordMessage = '';
                    this.resetPasswordError = false;
                    const token = localStorage.getItem('auth_token');
                    try {
                        const response = await fetch(`/api/v1/admin/users/${this.resetPasswordUser.id}/password`, {
                            method: 'PUT',
                            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                            body: JSON.stringify({ password: this.resetPasswordForm.password, password_confirmation: this.resetPasswordForm.password_confirmation })
                        });
                        const result = await response.json();
                        if (response.ok && result.success) {
                            this.resetPasswordMessage = 'Password reset successfully.';
                            this.resetPasswordForm = { password: '', password_confirmation: '' };
                            setTimeout(() => { this.showResetPasswordModal = false; }, 1500);
                        } else {
                            this.resetPasswordError = true;
                            this.resetPasswordMessage = (result.errors && result.errors.password && result.errors.password[0]) || result.message || 'Failed to reset password.';
                        }
                    } catch (e) {
                        this.resetPasswordError = true;
                        this.resetPasswordMessage = 'An error occurred. Please try again.';
                    } finally {
                        this.resetPasswordSaving = false;
                    }
                },
            }));
        });

        // Admin Verifications Component
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminVerifications', () => ({
                verifications: [],
                loading: false,
                filterStatus: 'pending',

                get filteredVerifications() {
                    if (this.filterStatus === 'all') {
                        return this.verifications;
                    }
                    return this.verifications.filter((v) => v.status === this.filterStatus);
                },

                async init() {
                    await this.loadVerifications();
                },

                async loadVerifications() {
                    this.loading = true;
                    const token = localStorage.getItem('auth_token');
                    if (!token) {
                        console.error('No auth token found');
                        this.loading = false;
                        return;
                    }

                    try {
                        // Load all verifications with pagination
                        let allVerifications = [];
                        let currentPage = 1;
                        let hasMore = true;

                        while (hasMore) {
                            const response = await fetch(
                                `/api/v1/admin/verifications?status=all&page=${currentPage}&per_page=100`,
                                {
                                    headers: {
                                        Accept: 'application/json',
                                        ...(token ? { Authorization: `Bearer ${token}` } : {}),
                                    },
                                }
                            );

                            const data = await response.json();
                            if (data.success && data.data) {
                                const pageVerifications = data.data.data || [];
                                allVerifications = [...allVerifications, ...pageVerifications];

                                hasMore = data.data.current_page < data.data.last_page;
                                currentPage++;
                            } else {
                                hasMore = false;
                            }
                        }

                        // Normalize verification data
                        this.verifications = allVerifications.map((v) => ({
                            id: v.id,
                            landlord_name: v.landlord_name || v.user?.name,
                            landlord_email: v.landlord_email || v.user?.email,
                            landlord_phone: v.landlord_phone || v.user?.phone,
                            business_name: v.business_name,
                            id_document_url: v.id_document_url,
                            proof_url: v.proof_url,
                            status: v.status,
                            admin_notes: v.admin_notes || '',
                            created_at: v.created_at,
                            reviewed_at: v.reviewed_at,
                        }));
                    } catch (error) {
                        console.error('Error loading admin verifications:', error);
                        alert('Failed to load verifications. Please refresh the page.');
                    } finally {
                        this.loading = false;
                    }
                },

                async approveVerification(verification) {
                    const token = localStorage.getItem('auth_token');
                    if (!token) {
                        alert('Please login as admin.');
                        window.location.href = '/login';
                        return;
                    }

                    if (!confirm(`Approve verification for "${verification.landlord_name}"?`)) {
                        return;
                    }

                    try {
                        const response = await fetch(`/api/v1/admin/verifications/${verification.id}/approve`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                Accept: 'application/json',
                                Authorization: `Bearer ${token}`,
                            },
                            body: JSON.stringify({
                                admin_notes: verification.admin_notes || null,
                            }),
                        });

                        const data = await response.json();
                        if (response.ok && data.success) {
                            verification.status = 'approved';
                            verification.reviewed_at = new Date().toISOString();
                            alert('Verification approved successfully.');
                        } else {
                            alert(data.message || 'Failed to approve verification.');
                        }
                    } catch (error) {
                        console.error('Error approving verification:', error);
                        alert('An error occurred while approving the verification.');
                    }
                },

                async rejectVerification(verification) {
                    const token = localStorage.getItem('auth_token');
                    if (!token) {
                        alert('Please login as admin.');
                        window.location.href = '/login';
                        return;
                    }

                    const notes = verification.admin_notes || prompt('Enter a reason for rejection (required):');
                    if (!notes) {
                        alert('Rejection reason is required.');
                        return;
                    }

                    if (!confirm(`Reject verification for "${verification.landlord_name}"?`)) {
                        return;
                    }

                    try {
                        const response = await fetch(`/api/v1/admin/verifications/${verification.id}/reject`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                Accept: 'application/json',
                                Authorization: `Bearer ${token}`,
                            },
                            body: JSON.stringify({
                                admin_notes: notes,
                            }),
                        });

                        const data = await response.json();
                        if (response.ok && data.success) {
                            verification.status = 'rejected';
                            verification.admin_notes = notes;
                            verification.reviewed_at = new Date().toISOString();
                            alert('Verification rejected.');
                        } else {
                            alert(data.message || 'Failed to reject verification.');
                        }
                    } catch (error) {
                        console.error('Error rejecting verification:', error);
                        alert('An error occurred while rejecting the verification.');
                    }
                },
            }));
        });

        // Admin Components
        document.addEventListener('alpine:init', () => {
            Alpine.data('adminOverview', () => ({
                stats: {},
                recentProperties: [],
                pendingVerifications: [],
                loading: true,

                async init() {
                    await Promise.all([
                        this.loadStats(),
                        this.loadRecentProperties(),
                        this.loadPendingVerifications()
                    ]);
                },

                async loadStats() {
                    const token = localStorage.getItem('auth_token');
                    if (!token) {
                        this.loading = false;
                        return;
                    }

                    try {
                        const response = await fetch('/api/v1/admin/stats', {
                            headers: {
                                Accept: 'application/json',
                                Authorization: `Bearer ${token}`,
                            },
                        });

                        const data = await response.json();
                        if (data.success && data.data) {
                            this.stats = data.data;
                        }
                    } catch (error) {
                        console.error('Error loading admin stats:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                async loadRecentProperties() {
                    const token = localStorage.getItem('auth_token');
                    if (!token) return;

                    try {
                        // Load recent published and pending properties
                        const [publishedRes, pendingRes] = await Promise.all([
                            fetch('/api/v1/properties?per_page=5&sort=latest', {
                                headers: {
                                    Accept: 'application/json',
                                },
                            }),
                            fetch('/api/v1/admin/properties/pending?per_page=5', {
                                headers: {
                                    Accept: 'application/json',
                                    ...(token ? { Authorization: `Bearer ${token}` } : {}),
                                },
                            }),
                        ]);

                        const publishedJson = await publishedRes.json();
                        const pendingJson = await pendingRes.json();

                        const published = publishedJson.success && publishedJson.data
                            ? (publishedJson.data.data || []).slice(0, 3)
                            : [];

                        const pending = pendingJson.success && pendingJson.data
                            ? (pendingJson.data.data || []).slice(0, 2)
                            : [];

                        const normalizedPublished = published.map((p) => ({
                            id: p.id,
                            title: p.title,
                            city: p.city,
                            district: p.district,
                            status: 'published',
                            primary_image_url: p.primary_image_url || (p.primaryImage ? `/storage/${p.primaryImage.image_path}` : null),
                        }));

                        const normalizedPending = pending.map((p) => ({
                            id: p.id,
                            title: p.title,
                            city: p.city,
                            district: p.district,
                            status: 'pending_review',
                            primary_image_url: p.primary_image_url || null,
                        }));

                        // Combine and sort by date (most recent first)
                        this.recentProperties = [...normalizedPending, ...normalizedPublished]
                            .sort((a, b) => b.id - a.id)
                            .slice(0, 5);
                    } catch (error) {
                        console.error('Error loading recent properties:', error);
                    }
                },

                async loadPendingVerifications() {
                    const token = localStorage.getItem('auth_token');
                    if (!token) return;

                    try {
                        const response = await fetch('/api/v1/admin/verifications?status=pending&per_page=5', {
                            headers: {
                                Accept: 'application/json',
                                Authorization: `Bearer ${token}`,
                            },
                        });

                        const data = await response.json();
                        if (data.success && data.data) {
                            this.pendingVerifications = (data.data.data || []).map((v) => ({
                                id: v.id,
                                landlord_name: v.landlord_name || v.user?.name,
                                created_at: v.created_at,
                            }));
                        }
                    } catch (error) {
                        console.error('Error loading pending verifications:', error);
                    }
                },

                formatCurrency(amount) {
                    if (amount >= 1000000) {
                        return (amount / 1000000).toFixed(1) + 'M';
                    } else if (amount >= 1000) {
                        return (amount / 1000).toFixed(1) + 'K';
                    }
                    return amount.toLocaleString();
                },
            }));

            Alpine.data('adminContactMessages', () => ({
                messages: [],
                loading: true,

                async init() {
                    await this.loadMessages();
                },

                async loadMessages(page = 1) {
                    this.loading = true;
                    const token = localStorage.getItem('auth_token');
                    if (!token) {
                        this.loading = false;
                        return;
                    }

                    try {
                        const response = await fetch(`/api/v1/admin/contact-messages?page=${page}`, {
                            headers: {
                                Accept: 'application/json',
                                Authorization: `Bearer ${token}`,
                            },
                        });

                        const data = await response.json();
                        if (data.success && data.data) {
                            const collection = Array.isArray(data.data)
                                ? data.data
                                : (data.data.data || []);
                            this.messages = collection;
                        }
                    } catch (error) {
                        console.error('Error loading contact messages:', error);
                        this.messages = [];
                    } finally {
                        this.loading = false;
                    }
                },
            }));
        });
    </script>
</body>
</html>