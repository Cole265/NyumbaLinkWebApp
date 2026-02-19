{{-- resources/views/landlord/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Khomolanu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    
    <div class="flex h-screen overflow-hidden" x-data="dashboardApp" x-init="initialize()">
        
        {{-- Sidebar --}}
        <aside class="hidden lg:flex lg:flex-col lg:w-72 bg-gradient-to-b from-slate-900 via-blue-900 to-indigo-900 text-white shadow-2xl">
            
            {{-- Logo --}}
            <div class="p-6 border-b border-white/10 shrink-0">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-2xl">KL</span>
                    </div>
                    <div>
                        <div class="font-bold text-xl">Khomolanu</div>
                        <div class="text-blue-300 text-xs">Landlord Portal</div>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="p-4 space-y-2 flex-1 min-h-0 overflow-y-auto">
                <button @click="activeTab = 'overview'" 
                   :class="activeTab === 'overview' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </button>

                <button @click="activeTab = 'properties'"
                   :class="activeTab === 'properties' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="font-medium">Properties</span>
                    <span class="ml-auto bg-blue-500 text-white text-xs px-2 py-1 rounded-full" x-text="totalProperties"></span>
                </button>

                <button @click="activeTab = 'properties'; propertyFilter = 'rented'; loadRentedProperties();"
                   :class="activeTab === 'properties' && propertyFilter === 'rented' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span class="font-medium">Rented Properties</span>
                    <span class="ml-auto bg-purple-500 text-white text-xs px-2 py-1 rounded-full" x-text="rentedProperties.length"></span>
                </button>

                <button @click="activeTab = 'inquiries'"
                   :class="activeTab === 'inquiries' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-medium">Inquiries</span>
                    <span x-show="pendingInquiries > 0" class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse" x-text="pendingInquiries"></span>
                </button>

                <button @click="activeTab = 'verification'"
                   :class="activeTab === 'verification' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">Verification</span>
                    <span x-show="verificationStatus === 'pending'" class="ml-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">Pending</span>
                    <span x-show="verificationStatus === 'approved'" class="ml-auto bg-green-500 text-white text-xs px-2 py-1 rounded-full">✓</span>
                </button>

                <button @click="activeTab = 'analytics'"
                   :class="activeTab === 'analytics' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="font-medium">Analytics</span>
                </button>

                <button @click="activeTab = 'ratings'; loadLandlordRatings()"
                   :class="activeTab === 'ratings' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.802 2.035a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118L12 14.347l-2.802 2.035c-.784.57-1.838-.197-1.54-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"/>
                    </svg>
                    <span class="font-medium">Ratings</span>
                </button>
            </nav>

            {{-- User Profile & Logout (bottom of sidebar) --}}
            <div class="p-4 border-t border-white/10 shrink-0 mt-auto">
                <div class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl backdrop-blur-sm">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-full flex items-center justify-center font-bold text-white">
                        <span x-text="userName?.[0] || 'L'"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium truncate" x-text="userName"></div>
                        <div class="text-xs text-blue-300">Verified</div>
                    </div>
                    <button @click="logout()" class="text-gray-400 hover:text-white" title="Log out">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            
            {{-- Top Bar --}}
            <header class="bg-white/80 backdrop-blur-xl border-b border-gray-200 shadow-sm">
                <div class="px-8 py-4 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900" x-text="pageTitle"></h1>
                        <p class="text-sm text-gray-600 mt-1" x-text="pageSubtitle"></p>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-xl transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </button>

                        <a href="/landlord/properties/create" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Add Property</span>
                        </a>
                    </div>
                </div>
            </header>

            {{-- Content Area --}}
            <main class="flex-1 overflow-y-auto p-8">
                
                {{-- Overview Tab --}}
                <div x-show="activeTab === 'overview'">
                    
                    {{-- Stats Cards --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        
                        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-bold mb-1" x-text="totalProperties"></div>
                            <div class="text-blue-100 text-sm">Total Properties</div>
                        </div>

                        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-bold mb-1" x-text="totalViews.toLocaleString()"></div>
                            <div class="text-purple-100 text-sm">Total Views</div>
                        </div>

                        <div class="bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-bold mb-1" x-text="pendingInquiries"></div>
                            <div class="text-orange-100 text-sm">Pending Inquiries</div>
                        </div>

                        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-white/20 rounded-xl backdrop-blur-sm">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-bold mb-1" x-text="displayRating"></div>
                            <div class="text-green-100 text-sm">Average Rating</div>
                        </div>
                    </div>

                    {{-- Recent Activity --}}
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        {{-- Recent Inquiries --}}
                        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold">Recent Inquiries</h3>
                                <button @click="activeTab = 'inquiries'" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">View All →</button>
                            </div>
                            <div class="space-y-4">
                                <template x-for="inquiry in recentInquiries.slice(0, 5)" :key="inquiry.id">
                                    <div class="flex items-start space-x-4 p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-xl hover:shadow-md transition">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                            <span x-text="inquiry.tenant_name?.[0] || 'T'"></span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="font-semibold text-gray-900" x-text="inquiry.tenant_name"></div>
                                            <div class="text-sm text-gray-600 truncate" x-text="inquiry.message"></div>
                                            <div class="text-xs text-gray-500 mt-1">
                                                <span x-text="inquiry.property_title"></span>
                                            </div>
                                        </div>
                                        <span x-show="inquiry.status === 'pending'" class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full font-semibold flex-shrink-0">New</span>
                                    </div>
                                </template>
                                
                                <div x-show="recentInquiries.length === 0" class="text-center py-8 text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <p>No inquiries yet</p>
                                </div>
                            </div>
                        </div>

                        {{-- Top Properties --}}
                        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold">Top Properties</h3>
                                <button @click="activeTab = 'properties'" class="text-blue-600 hover:text-blue-700 text-sm font-semibold">View All →</button>
                            </div>
                            <div class="space-y-4">
                                <template x-for="property in topProperties" :key="property.id">
                                    <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-gray-50 to-green-50 rounded-xl hover:shadow-md transition">
                                        <img :src="property.image || 'https://placehold.co/100x100/e2e8f0/64748b?text=No+Image'" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                                        <div class="flex-1 min-w-0">
                                            <div class="font-semibold text-gray-900 truncate" x-text="property.title"></div>
                                            <div class="text-sm text-gray-600">MWK <span x-text="Number(property.price).toLocaleString()"></span></div>
                                        </div>
                                        <div class="text-right flex-shrink-0">
                                            <div class="text-2xl font-bold text-green-600" x-text="property.inquiries || 0"></div>
                                            <div class="text-xs text-gray-500">inquiries</div>
                                        </div>
                                    </div>
                                </template>

                                <div x-show="topProperties.length === 0" class="text-center py-8 text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <p>No properties yet</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Properties Tab --}}
                <div x-show="activeTab === 'properties'" x-cloak>
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-200">
                        <div class="p-6 border-b">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-2xl font-bold">My Properties</h3>
                                    <p class="text-sm text-gray-600 mt-1">Click "Mark as Rented" on published properties to assign them to tenants</p>
                                </div>
                                <a href="/landlord/properties/create" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 font-semibold">+ Add New</a>
                            </div>
                            <div class="flex space-x-2">
                                <button
                                    @click="propertyFilter = 'all'"
                                    :class="propertyFilter === 'all' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                                    class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                >
                                    All
                                </button>
                                <button
                                    @click="propertyFilter = 'published'"
                                    :class="propertyFilter === 'published' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                                    class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                >
                                    Published
                                </button>
                                <button
                                    @click="propertyFilter = 'rented'"
                                    :class="propertyFilter === 'rented' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                                    class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                >
                                    Rented
                                </button>
                                <button
                                    @click="propertyFilter = 'pending'"
                                    :class="propertyFilter === 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                                    class="px-4 py-2 rounded-lg text-sm font-medium transition"
                                >
                                    Vacant / Drafts
                                </button>
                            </div>
                        </div>
                        
                        <div x-show="loadingProperties" class="p-12 text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        </div>

                        <div x-show="!loadingProperties && allProperties.length === 0" class="text-center py-20">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <h3 class="text-xl font-bold text-gray-700 mb-2">No Properties Yet</h3>
                            <p class="text-gray-500 mb-6">Start by adding your first property</p>
                            <a href="/landlord/properties/create" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">Add Property</a>
                        </div>

                        <div x-show="!loadingProperties && filteredProperties.length === 0 && propertyFilter !== 'rented'" class="text-center py-20">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <h3 class="text-xl font-bold text-gray-700 mb-2">No Properties Found</h3>
                            <p class="text-gray-500">No properties match the selected filter.</p>
                        </div>

                        <div x-show="loadingRentedProperties && propertyFilter === 'rented'" class="p-12 text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        </div>

                        <div x-show="!loadingRentedProperties && propertyFilter === 'rented' && rentedProperties.length === 0" class="text-center py-20">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <h3 class="text-xl font-bold text-gray-700 mb-2">No Rented Properties</h3>
                            <p class="text-gray-500">You don't have any rented properties at the moment.</p>
                        </div>

                        <div x-show="!loadingProperties && filteredProperties.length > 0 && propertyFilter !== 'rented'" class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <template x-for="property in filteredProperties" :key="property.id">
                                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition border border-gray-200">
                                        <div class="flex">
                                            <img :src="property.primary_image_url || 'https://placehold.co/200x200/e2e8f0/64748b?text=No+Image'" class="w-40 h-40 object-cover">
                                            <div class="flex-1 p-4">
                                                <div class="flex items-start justify-between mb-2">
                                                    <h4 class="font-bold text-lg text-gray-900" x-text="property.title"></h4>
                                                    <span :class="{
                                                        'bg-yellow-100 text-yellow-700': property.status === 'pending_review',
                                                        'bg-green-100 text-green-700': property.status === 'published',
                                                        'bg-gray-100 text-gray-700': property.status === 'draft'
                                                    }" class="px-2 py-1 rounded-full text-xs font-semibold capitalize" x-text="property.status.replace('_', ' ')"></span>
                                                </div>
                                                <p class="text-sm text-gray-600 mb-2" x-text="`${property.city}, ${property.district}`"></p>
                                                <p class="text-xl font-bold text-blue-600 mb-3">MWK <span x-text="Number(property.price).toLocaleString()"></span></p>
                                                <div class="flex items-center justify-between text-sm text-gray-600">
                                                    <span>👁️ <span x-text="property.view_count || 0"></span> views</span>
                                                    <span>💬 <span x-text="property.inquiry_count || 0"></span> inquiries</span>
                                                </div>
                                                <div x-show="property.status === 'draft' && property.rejection_reason" class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg text-sm text-red-800">
                                                    <span class="font-semibold">Rejection reason:</span>
                                                    <p class="mt-0.5" x-text="property.rejection_reason"></p>
                                                </div>
                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    <button type="button" @click="openPropertyViewModal(property.id)" class="flex-1 text-center px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">View</button>
                                                    <a :href="`/landlord/properties/${property.id}/edit`" class="px-3 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300 text-center">Edit</a>
                                                    <button
                                                        x-show="property.status === 'draft'"
                                                        @click="submitForReview(property)"
                                                        class="px-3 py-2 bg-yellow-500 text-white text-sm rounded hover:bg-yellow-600 text-center"
                                                    >
                                                        Submit for Review
                                                    </button>
                                                    <button
                                                        x-show="property.status === 'published'"
                                                        @click="openMarkAsRentedModal(property)"
                                                        class="px-3 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700 text-center"
                                                    >
                                                        Mark as Rented
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div x-show="!loadingRentedProperties && propertyFilter === 'rented' && rentedProperties.length > 0" class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <template x-for="property in rentedProperties" :key="property.id">
                                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition border border-gray-200">
                                        <div class="flex">
                                            <img :src="property.primary_image_url || 'https://placehold.co/200x200/e2e8f0/64748b?text=No+Image'" class="w-40 h-40 object-cover">
                                            <div class="flex-1 p-4">
                                                <div class="flex items-start justify-between mb-2">
                                                    <h4 class="font-bold text-lg text-gray-900" x-text="property.title"></h4>
                                                    <span class="px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">Rented</span>
                                                </div>
                                                <p class="text-sm text-gray-600 mb-2" x-text="`${property.city}, ${property.district}`"></p>
                                                <p class="text-xl font-bold text-blue-600 mb-3">MWK <span x-text="Number(property.price).toLocaleString()"></span></p>
                                                
                                                <div class="mb-3 p-3 bg-purple-50 rounded-lg border border-purple-200">
                                                    <p class="text-xs font-semibold text-purple-700 mb-1">Current Tenant</p>
                                                    <p class="text-sm font-medium text-gray-900" x-text="property.tenant_name || 'N/A'"></p>
                                                    <p class="text-xs text-gray-600" x-text="property.tenant_email || ''"></p>
                                                    <p class="text-xs text-gray-500 mt-1" x-show="property.tenancy_start_date" x-text="`Rented since: ${new Date(property.tenancy_start_date).toLocaleDateString()}`"></p>
                                                </div>

                                                <div class="mt-3 flex flex-wrap gap-2">
                                                    <button type="button" @click="openPropertyViewModal(property.id)" class="flex-1 text-center px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">View</button>
                                                    <a :href="`/landlord/properties/${property.id}/edit`" class="px-3 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300 text-center">Edit</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Property View Modal --}}
                    @include('components.PropertyViewModal')

                    {{-- Mark as Rented Modal --}}
                    <div x-show="showRentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" x-cloak>
                        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4" @click.away="showRentModal = false">
                            <div class="p-6 border-b">
                                <h3 class="text-xl font-bold text-gray-900">Mark Property as Rented</h3>
                                <p class="text-sm text-gray-600 mt-1" x-text="`${selectedProperty?.title || ''}`"></p>
                                <div class="mt-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-xs text-blue-800">
                                        <strong>Note:</strong> Once marked as rented, this property will be removed from public listings and only visible in your "Rented Properties" section.
                                    </p>
                                </div>
                            </div>
                            <form @submit.prevent="markPropertyAsRented()" class="p-6 space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Select Tenant from Inquiries <span class="text-red-500">*</span>
                                    </label>
                                    
                                    <div x-show="loadingInquiries" class="mt-2 text-sm text-gray-500 text-center py-4">
                                        <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600"></div>
                                        <span class="ml-2">Loading inquiries...</span>
                                    </div>
                                    
                                    <div x-show="!loadingInquiries && propertyInquiries.length === 0" class="mt-2 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                        <p class="text-sm text-yellow-800">
                                            <strong>No inquiries yet.</strong> This property doesn't have any inquiries. You can still search for a tenant manually.
                                        </p>
                                        <button
                                            type="button"
                                            @click="showManualSearch = true"
                                            class="mt-2 text-sm text-yellow-700 hover:text-yellow-900 underline"
                                        >
                                            Search for tenant manually
                                        </button>
                                    </div>
                                    
                                    <div x-show="!loadingInquiries && propertyInquiries.length > 0 && !showManualSearch" class="mt-2 max-h-60 overflow-y-auto border border-gray-200 rounded-lg">
                                        <template x-for="inquiry in propertyInquiries" :key="inquiry.id">
                                            <button
                                                type="button"
                                                @click="selectTenantFromInquiry(inquiry)"
                                                :class="selectedTenant?.tenant_id === inquiry.tenant_id ? 'bg-blue-50 border-blue-300' : 'hover:bg-gray-50'"
                                                class="w-full text-left px-4 py-3 border-b border-gray-100 last:border-b-0 transition"
                                            >
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="font-medium text-gray-900" x-text="inquiry.tenant_name"></div>
                                                        <div class="text-sm text-gray-600 mt-1" x-text="inquiry.tenant_email"></div>
                                                        <div class="text-xs text-gray-500 mt-1" x-text="inquiry.tenant_phone"></div>
                                                        <div class="text-xs text-gray-400 mt-2 italic" x-text="`Inquiry: ${inquiry.message.substring(0, 50)}...`"></div>
                                                        <div class="text-xs text-gray-400 mt-1" x-text="`Sent on ${new Date(inquiry.created_at).toLocaleDateString()}`"></div>
                                                    </div>
                                                    <span :class="{
                                                        'bg-yellow-100 text-yellow-700': inquiry.status === 'pending',
                                                        'bg-green-100 text-green-700': inquiry.status === 'responded',
                                                        'bg-gray-100 text-gray-700': inquiry.status === 'closed'
                                                    }" class="ml-2 px-2 py-1 rounded-full text-xs font-semibold capitalize" x-text="inquiry.status"></span>
                                                </div>
                                            </button>
                                        </template>
                                    </div>
                                    
                                    <!-- Manual Search Fallback -->
                                    <div x-show="showManualSearch" class="mt-2">
                                        <input
                                            type="text"
                                            x-model="tenantSearch"
                                            @input="searchTenants()"
                                            placeholder="Search by name, email, or phone..."
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                        >
                                        <div x-show="searchingTenants" class="mt-2 text-sm text-gray-500">Searching...</div>
                                        <div x-show="!searchingTenants && tenantSearchResults.length > 0" class="mt-2 max-h-40 overflow-y-auto border border-gray-200 rounded-lg">
                                            <template x-for="tenant in tenantSearchResults" :key="tenant.id">
                                                <button
                                                    type="button"
                                                    @click="selectTenant(tenant)"
                                                    class="w-full text-left px-4 py-2 hover:bg-gray-100 border-b border-gray-100 last:border-b-0"
                                                >
                                                    <div class="font-medium text-gray-900" x-text="tenant.name"></div>
                                                    <div class="text-sm text-gray-600" x-text="tenant.email"></div>
                                                </button>
                                            </template>
                                        </div>
                                        <button
                                            type="button"
                                            @click="showManualSearch = false"
                                            class="mt-2 text-sm text-gray-600 hover:text-gray-800 underline"
                                        >
                                            ← Back to inquiries list
                                        </button>
                                    </div>
                                    
                                    <div x-show="selectedTenant" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-sm font-semibold text-blue-900">Selected Tenant:</p>
                                        <p class="text-sm text-blue-700" x-text="selectedTenant?.name || selectedTenant?.tenant_name || 'N/A'"></p>
                                        <p class="text-xs text-blue-600" x-text="selectedTenant?.email || selectedTenant?.tenant_email || ''"></p>
                                        <button
                                            type="button"
                                            @click="selectedTenant = null; showManualSearch = false"
                                            class="mt-2 text-xs text-blue-600 hover:text-blue-800"
                                        >
                                            Change
                                        </button>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Rental Start Date <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="date"
                                        x-model="rentalStartDate"
                                        required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                </div>

                                <div x-show="rentError" class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-sm text-red-700" x-text="rentError"></p>
                                </div>

                                <div class="flex space-x-3 pt-4">
                                    <button
                                        type="button"
                                        @click="showRentModal = false; selectedTenant = null; propertyInquiries = []; showManualSearch = false; tenantSearch = ''; rentalStartDate = ''"
                                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold"
                                    >
                                        Cancel
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="!selectedTenant || !rentalStartDate || markingAsRented"
                                        class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed"
                                    >
                                        <span x-show="!markingAsRented">Mark as Rented</span>
                                        <span x-show="markingAsRented">Processing...</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Inquiries Tab --}}
                <div x-show="activeTab === 'inquiries'" x-cloak>
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                        <h3 class="text-2xl font-bold mb-6">All Inquiries</h3>
                        
                        <div x-show="loadingInquiries" class="text-center py-12">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        </div>

                        <div x-show="!loadingInquiries && allInquiries.length === 0" class="text-center py-20 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-lg">No inquiries yet</p>
                        </div>

                        <div x-show="!loadingInquiries && allInquiries.length > 0" class="space-y-4">
                            <template x-for="inquiry in allInquiries" :key="inquiry.id">
                                <div class="border border-gray-200 rounded-xl p-6 hover:shadow-md transition">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-start space-x-4">
                                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold">
                                                <span x-text="inquiry.tenant_name?.[0] || 'T'"></span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900" x-text="inquiry.tenant_name"></div>
                                                <div class="text-sm text-gray-600" x-text="inquiry.property_title"></div>
                                                <div class="mt-2 text-gray-700" x-text="inquiry.message"></div>
                                                <div class="mt-2 text-xs text-gray-500" x-text="new Date(inquiry.created_at).toLocaleDateString()"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <span :class="{
                                                'bg-yellow-100 text-yellow-700': inquiry.status === 'pending',
                                                'bg-green-100 text-green-700': inquiry.status === 'responded',
                                                'bg-gray-100 text-gray-700': inquiry.status === 'closed'
                                            }" class="px-3 py-1 rounded-full text-xs font-semibold capitalize" x-text="inquiry.status"></span>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <a :href="`https://wa.me/${inquiry.tenant_phone?.replace(/[^0-9]/g, '')}`" target="_blank" class="flex-1 text-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-semibold">WhatsApp</a>
                                        <a :href="`tel:${inquiry.tenant_phone}`" class="flex-1 text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold">Call</a>
                                        <button
                                            x-show="inquiry.status === 'pending'"
                                            @click="markAsResponded(inquiry)"
                                            class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 text-sm font-semibold"
                                        >
                                            Mark as Responded
                                        </button>
                                        <button
                                            x-show="inquiry.status === 'responded'"
                                            @click="markAsClosed(inquiry)"
                                            class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 text-sm font-semibold"
                                        >
                                            Mark as Closed
                                        </button>
                                        <button
                                            type="button"
                                            @click="deleteInquiry(inquiry)"
                                            class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 text-sm font-semibold"
                                            title="Delete inquiry"
                                        >
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Verification Tab --}}
                <div x-show="activeTab === 'verification'" x-cloak x-data="verificationApp()" x-init="loadVerificationStatus()">
                    <div class="space-y-6">
                        {{-- Verification Status Card --}}
                        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                            <h3 class="text-2xl font-bold mb-4">Verification Status</h3>
                            
                            <div x-show="loading" class="text-center py-8">
                                <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-blue-600 border-t-transparent"></div>
                                <p class="mt-3 text-gray-600 text-sm">Loading verification status...</p>
                            </div>

                            <div x-show="!loading" class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">Current Status</p>
                                        <span
                                            class="px-4 py-2 rounded-full text-sm font-semibold capitalize"
                                            :class="{
                                                'bg-yellow-100 text-yellow-700': verificationStatus === 'pending',
                                                'bg-green-100 text-green-700': verificationStatus === 'approved',
                                                'bg-red-100 text-red-700': verificationStatus === 'rejected',
                                                'bg-gray-100 text-gray-700': !verificationStatus || verificationStatus === 'unverified'
                                            }"
                                            x-text="verificationStatus || 'Not Submitted'"
                                        ></span>
                                    </div>
                                    <template x-if="verificationStatus === 'approved'">
                                        <div class="text-green-600">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                    </template>
                                </div>

                                <template x-if="verificationStatus === 'pending' && verificationNotes">
                                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                        <p class="text-sm font-semibold text-blue-900 mb-1">Note:</p>
                                        <p class="text-sm text-blue-700" x-text="verificationNotes"></p>
                                    </div>
                                </template>

                                <template x-if="verificationStatus === 'rejected' && verificationNotes">
                                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                        <p class="text-sm font-semibold text-red-900 mb-1">Rejection Reason:</p>
                                        <p class="text-sm text-red-700" x-text="verificationNotes"></p>
                                        <p class="text-xs text-red-600 mt-2">You can resubmit your verification documents below.</p>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Verification Submission Form --}}
                        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                            <h3 class="text-2xl font-bold mb-4">Submit Verification Documents</h3>
                            <p class="text-sm text-gray-600 mb-6">
                                Upload your National ID and proof of ownership to get verified as a landlord. 
                                This helps build trust with potential tenants.
                            </p>

                            <form @submit.prevent="submitVerification()" class="space-y-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        National ID Number <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        x-model="formData.national_id"
                                        required
                                        placeholder="Enter your National ID number"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        National ID Document <span class="text-red-500">*</span>
                                    </label>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Upload a file</span>
                                                    <input
                                                        type="file"
                                                        @change="handleFileSelect($event, 'id_document')"
                                                        accept="image/*,.pdf"
                                                        required
                                                        class="sr-only"
                                                    >
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, PDF up to 5MB</p>
                                            <p x-show="formData.id_document" class="text-sm text-green-600 font-medium mt-2" x-text="`Selected: ${formData.id_document.name}`"></p>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                                        Proof of Ownership (Optional)
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Upload documents proving property ownership (deed, title, etc.)</p>
                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                    <span>Upload a file</span>
                                                    <input
                                                        type="file"
                                                        @change="handleFileSelect($event, 'proof_of_ownership')"
                                                        accept="image/*,.pdf"
                                                        class="sr-only"
                                                    >
                                                </label>
                                                <p class="pl-1">or drag and drop</p>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG, PDF up to 5MB</p>
                                            <p x-show="formData.proof_of_ownership" class="text-sm text-green-600 font-medium mt-2" x-text="`Selected: ${formData.proof_of_ownership.name}`"></p>
                                        </div>
                                    </div>
                                </div>

                                <div x-show="errorMessage" class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-sm text-red-700" x-text="errorMessage"></p>
                                </div>

                                <div x-show="successMessage" class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-sm text-green-700" x-text="successMessage"></p>
                                </div>

                                <div class="flex space-x-4">
                                    <button
                                        type="submit"
                                        :disabled="submitting || verificationStatus === 'pending'"
                                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed transition"
                                    >
                                        <span x-show="!submitting">Submit for Verification</span>
                                        <span x-show="submitting" class="flex items-center justify-center">
                                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            Submitting...
                                        </span>
                                    </button>
                                </div>

                                <template x-if="verificationStatus === 'pending'">
                                    <p class="text-sm text-yellow-600 text-center">
                                        ⚠️ You have a pending verification request. Please wait for admin review.
                                    </p>
                                </template>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Analytics Tab --}}
                <div x-show="activeTab === 'analytics'" x-cloak>
                    <div class="space-y-6">
                        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                            <h3 class="text-2xl font-bold mb-6">Performance Analytics</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                                <div class="p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                                    <div class="text-sm text-blue-700 font-semibold mb-2">Total Views (30 days)</div>
                                    <div class="text-3xl font-bold text-blue-900" x-text="totalViews.toLocaleString()"></div>
                                </div>
                                <div class="p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                                    <div class="text-sm text-green-700 font-semibold mb-2">Total Inquiries (30 days)</div>
                                    <div class="text-3xl font-bold text-green-900" x-text="(pendingInquiries + allInquiries.filter(i => i.status !== 'pending').length)"></div>
                                </div>
                                <div class="p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                                    <div class="text-sm text-purple-700 font-semibold mb-2">Avg. Views per Property</div>
                                    <div class="text-3xl font-bold text-purple-900" x-text="totalProperties > 0 ? Math.round(totalViews / totalProperties) : 0"></div>
                                </div>
                            </div>

                            <div class="border-t pt-6">
                                <h4 class="font-bold text-lg mb-4">Property Performance</h4>
                                <div class="space-y-4">
                                    <template x-for="property in allProperties.slice(0, 5)" :key="property.id">
                                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                            <div class="flex-1">
                                                <div class="font-semibold text-gray-900" x-text="property.title"></div>
                                                <div class="text-sm text-gray-600" x-text="`${property.city}, ${property.district}`"></div>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-2xl font-bold text-blue-600" x-text="property.view_count || 0"></div>
                                                <div class="text-xs text-gray-500">views</div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ratings Tab --}}
                <div x-show="activeTab === 'ratings'" x-cloak>
                    <div class="space-y-6">
                        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h3 class="text-2xl font-bold">My Ratings</h3>
                                    <p class="text-sm text-gray-600">See how tenants have rated your service and properties.</p>
                                </div>
                                <button
                                    @click="loadLandlordRatings()"
                                    class="px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-100"
                                >
                                    Refresh
                                </button>
                            </div>

                            <div x-show="loadingRatings" class="py-10 text-center text-gray-500">
                                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
                                <p class="text-sm">Loading ratings...</p>
                            </div>

                            <div x-show="!loadingRatings && landlordRatings.length === 0" class="py-10 text-center text-gray-500">
                                <p class="text-sm">You haven't received any ratings yet.</p>
                            </div>

                            <div x-show="!loadingRatings && landlordRatings.length > 0" class="space-y-4">
                                <template x-for="rating in landlordRatings" :key="rating.id">
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900" x-text="rating.property_title"></h4>
                                            <p class="text-xs text-gray-600 mt-1">
                                                Tenant:
                                                <span class="font-medium" x-text="rating.tenant_name"></span>
                                            </p>
                                            <div class="flex items-center space-x-1 mt-1 text-yellow-500 text-xs">
                                                <template x-for="i in 5">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                                         :class="i <= Math.round(rating.overall_rating) ? '' : 'text-gray-300'">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.802 2.035a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118L12 14.347l-2.802 2.035a1 1 0 00-1.175 0l-2.802 2.035c-.784.57-1.838-.197-1.54-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"/>
                                                    </svg>
                                                </template>
                                                <span class="ml-1 text-gray-700" x-text="Number(rating.overall_rating).toFixed(1)"></span>
                                            </div>
                                            <p class="mt-2 text-xs text-gray-700 line-clamp-2" x-text="rating.review || 'No written review.'"></p>
                                            <p class="mt-1 text-[11px] text-gray-400" x-text="new Date(rating.created_at).toLocaleDateString()"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

        </div>
    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboardApp', () => ({
            // State
            activeTab: 'overview',
            userName: '',
            totalProperties: 0,
            totalViews: 0,
            pendingInquiries: 0,
            avgRating: 0.0,
            recentInquiries: [],
            topProperties: [],
            allProperties: [],
            rentedProperties: [],
            allInquiries: [],
            landlordRatings: [],
            loadingRatings: false,
            loadingProperties: false,
            loadingRentedProperties: false,
            loadingInquiries: false,
            verificationStatus: null,
            propertyFilter: 'all',
            showRentModal: false,
            selectedProperty: null,
            selectedTenant: null,
            propertyInquiries: [],
            loadingInquiries: false,
            showManualSearch: false,
            tenantSearch: '',
            tenantSearchResults: [],
            searchingTenants: false,
            rentalStartDate: '',
            markingAsRented: false,
            rentError: '',
            showPropertyViewModal: false,
            viewModalProperty: null,
            viewModalLoading: false,
            viewModalError: null,
            viewModalCurrentImage: null,
            
            // Computed
            get pageTitle() {
                const titles = {
                    overview: 'Dashboard Overview',
                    properties: 'My Properties',
                    inquiries: 'Inquiries',
                    verification: 'Verification',
                    analytics: 'Analytics',
                    ratings: 'My Ratings',
                };
                return titles[this.activeTab] || 'Dashboard';
            },
            
            get pageSubtitle() {
                const subtitles = {
                    overview: 'Welcome back! Here\'s what\'s happening',
                    properties: 'Manage your listed properties',
                    inquiries: 'View and respond to inquiries',
                    verification: 'Get verified as a landlord',
                    analytics: 'Performance insights',
                    ratings: 'See how tenants are rating your service',
                };
                return subtitles[this.activeTab] || '';
            },

            get displayRating() {
                return (this.avgRating || 0).toFixed(1);
            },

            get filteredProperties() {
                if (this.propertyFilter === 'all') {
                    return this.allProperties.filter(p => p.status !== 'rented');
                } else if (this.propertyFilter === 'published') {
                    return this.allProperties.filter(p => p.status === 'published');
                } else if (this.propertyFilter === 'pending') {
                    return this.allProperties.filter(p => p.status === 'pending_review' || p.status === 'draft');
                }
                return [];
            },

            // Methods
            async initialize() {
                // Prevent browser back from leaving the platform when logged in
                if (localStorage.getItem('auth_token')) {
                    history.pushState(null, null, location.href);
                    window.addEventListener('popstate', function () {
                        history.pushState(null, null, location.href);
                    });
                }
                this.loadUser();
                await this.loadAnalytics();
                await this.loadAllProperties(); // Load properties on init
                await this.loadRentedProperties(); // Load rented properties count on init
                await this.loadVerificationStatus();
                
                // Watch for tab changes
                this.$watch('activeTab', (value) => {
                    if (value === 'inquiries') {
                        this.loadAllInquiries();
                    } else if (value === 'properties') {
                        // Reload rented properties when switching to properties tab
                        if (this.propertyFilter === 'rented') {
                            this.loadRentedProperties();
                        }
                    } else if (value === 'ratings') {
                        this.loadLandlordRatings();
                    }
                });

                // Watch for property filter changes
                this.$watch('propertyFilter', (value) => {
                    if (value === 'rented') {
                        this.loadRentedProperties();
                    }
                });
            },

            async loadVerificationStatus() {
                const token = localStorage.getItem('auth_token');
                if (!token) return;

                try {
                    const response = await fetch('/api/v1/landlord/verification/status', {
                        headers: {
                            Accept: 'application/json',
                            Authorization: `Bearer ${token}`,
                        },
                    });

                    const data = await response.json();
                    if (data.success && data.data) {
                        const latestRequest = data.data.latest_verification_request;
                        this.verificationStatus = latestRequest?.status || data.data.verification_status || null;
                    }
                } catch (error) {
                    console.error('Error loading verification status:', error);
                }
            },

            loadUser() {
                try {
                    const user = localStorage.getItem('user');
                    if (user) {
                        const userData = JSON.parse(user);
                        this.userName = userData.name || 'Landlord';
                    }
                } catch (error) {
                    console.error('Error loading user:', error);
                    this.userName = 'Landlord';
                }
            },

            async loadLandlordRatings() {
                this.loadingRatings = true;
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    this.loadingRatings = false;
                    return;
                }

                try {
                    const response = await fetch('/api/v1/landlord/ratings', {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json',
                        },
                    });

                    const result = await response.json();
                    if (result.success && result.data) {
                        const collection = Array.isArray(result.data)
                            ? result.data
                            : (result.data.data || []);

                        this.landlordRatings = collection.map((r) => ({
                            id: r.id,
                            tenant_name: r.tenant_name,
                            property_title: r.property_title,
                            overall_rating: r.overall_rating,
                            review: r.review,
                            created_at: r.created_at,
                        }));
                    }
                } catch (error) {
                    console.error('Error loading landlord ratings:', error);
                    this.landlordRatings = [];
                } finally {
                    this.loadingRatings = false;
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
                    const response = await fetch(`/api/v1/landlord/properties/${propertyId}`, {
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

            async loadAnalytics() {
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    console.warn('No auth token found');
                    return;
                }

                try {
                    const response = await fetch('/api/v1/landlord/analytics', {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Failed to load analytics');
                    }

                    const result = await response.json();
                    
                    if (result.success && result.data) {
                        const data = result.data;
                        this.totalProperties = data.property_stats?.total_properties || 0;
                        this.totalViews = data.view_stats?.total_views || 0;
                        this.pendingInquiries = data.inquiry_stats?.pending_inquiries || 0;
                        this.avgRating = parseFloat(data.rating_stats?.avg_rating) || 0;
                        this.recentInquiries = Array.isArray(data.recent_inquiries) ? data.recent_inquiries : [];
                        this.topProperties = Array.isArray(data.top_properties) ? data.top_properties : [];
                    }
                } catch (error) {
                    console.error('Error loading analytics:', error);
                }
            },

            async loadAllProperties() {
                this.loadingProperties = true;
                const token = localStorage.getItem('auth_token');
                
                try {
                    const response = await fetch('/api/v1/landlord/properties', {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (result.success && result.data) {
                        // The API returns a paginated response { data: { data: [...] } }
                        const collection = Array.isArray(result.data)
                            ? result.data
                            : (Array.isArray(result.data.data) ? result.data.data : []);

                        this.allProperties = collection;

                        // Derive real totals from the loaded properties
                        this.totalProperties = this.allProperties.length;
                        this.totalViews = this.allProperties.reduce((sum, property) => {
                            const listingViews = property.listing ? (property.listing.view_count || 0) : (property.view_count || 0);
                            return sum + listingViews;
                        }, 0);
                    }
                } catch (error) {
                    console.error('Error loading properties:', error);
                    // Fallback: use topProperties if API fails
                    if (this.topProperties.length > 0) {
                        this.allProperties = this.topProperties;
                    }
                } finally {
                    this.loadingProperties = false;
                }
            },

            async loadRentedProperties() {
                this.loadingRentedProperties = true;
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    this.loadingRentedProperties = false;
                    return;
                }

                try {
                    // Load all rented properties with pagination
                    let allRented = [];
                    let currentPage = 1;
                    let hasMore = true;

                    while (hasMore) {
                        const response = await fetch(`/api/v1/landlord/properties/rented?page=${currentPage}&per_page=100`, {
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            }
                        });

                        if (!response.ok) {
                            console.error('Failed to fetch rented properties:', response.status, response.statusText);
                            hasMore = false;
                            break;
                        }

                        const result = await response.json();
                        if (result.success && result.data) {
                            // Handle paginated response
                            const paginatedData = result.data;
                            const pageProperties = Array.isArray(paginatedData) 
                                ? paginatedData 
                                : (paginatedData.data || []);
                            
                            if (pageProperties.length > 0) {
                                allRented = [...allRented, ...pageProperties];
                            }
                            
                            // Check if there are more pages
                            if (Array.isArray(paginatedData)) {
                                hasMore = false; // If it's an array, it's not paginated
                            } else if (paginatedData.current_page && paginatedData.last_page) {
                                hasMore = paginatedData.current_page < paginatedData.last_page;
                            } else {
                                hasMore = false;
                            }
                            currentPage++;
                        } else {
                            hasMore = false;
                        }
                    }

                    this.rentedProperties = allRented.map((p) => ({
                        id: p.id,
                        title: p.title,
                        city: p.city,
                        district: p.district,
                        price: p.price,
                        status: 'rented',
                        primary_image_url: p.primary_image_url,
                        tenant_name: p.tenant_name,
                        tenant_email: p.tenant_email,
                        tenant_phone: p.tenant_phone,
                        tenancy_start_date: p.tenancy_start_date,
                    }));
                } catch (error) {
                    console.error('Error loading rented properties:', error);
                    this.rentedProperties = [];
                } finally {
                    this.loadingRentedProperties = false;
                }
            },

            async loadAllInquiries() {
                this.loadingInquiries = true;
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    this.loadingInquiries = false;
                    return;
                }
                
                try {
                    // Load all inquiries with pagination
                    let allInquiries = [];
                    let currentPage = 1;
                    let hasMore = true;
                    let lastStats = null;

                    while (hasMore) {
                        const response = await fetch(`/api/v1/landlord/inquiries?page=${currentPage}&per_page=100`, {
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            }
                        });

                        const result = await response.json();
                        if (result.success && result.data) {
                            // Handle paginated response
                            const pageInquiries = result.data.data || [];
                            allInquiries = [...allInquiries, ...pageInquiries];

                            // Store stats from last page (most up-to-date)
                            if (result.stats) {
                                lastStats = result.stats;
                            }

                            // Check if there are more pages
                            hasMore = result.data.current_page < result.data.last_page;
                            currentPage++;
                        } else {
                            hasMore = false;
                        }
                    }

                    // Update pending count from stats if available
                    if (lastStats && lastStats.pending !== undefined) {
                        this.pendingInquiries = lastStats.pending;
                    }

                    // Normalize inquiry data
                    this.allInquiries = allInquiries.map((inquiry) => ({
                        id: inquiry.id,
                        tenant_name: inquiry.tenant_name || inquiry.tenant?.name,
                        tenant_phone: inquiry.tenant_phone || inquiry.tenant?.phone,
                        tenant_email: inquiry.tenant_email || inquiry.tenant?.email,
                        property_title: inquiry.property_title || inquiry.listing?.property?.title,
                        message: inquiry.message,
                        status: inquiry.status,
                        created_at: inquiry.created_at,
                    }));

                    // Update pending count from loaded inquiries if stats weren't available
                    if (this.pendingInquiries === 0 || this.allInquiries.length > 0) {
                        this.pendingInquiries = this.allInquiries.filter(i => i.status === 'pending').length;
                    }
                } catch (error) {
                    console.error('Error loading inquiries:', error);
                    this.allInquiries = [];
                } finally {
                    this.loadingInquiries = false;
                }
            },

            async markAsResponded(inquiry) {
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    alert('Please login to continue.');
                    return;
                }

                if (!confirm('Mark this inquiry as responded? The tenant will be notified.')) {
                    return;
                }

                try {
                    const response = await fetch(`/api/v1/landlord/inquiries/${inquiry.id}/respond`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (result.success) {
                        inquiry.status = 'responded';
                        // Update pending count
                        this.pendingInquiries = Math.max(0, this.pendingInquiries - 1);
                        alert('Inquiry marked as responded. Tenant has been notified.');
                    } else {
                        alert(result.message || 'Failed to mark inquiry as responded.');
                    }
                } catch (error) {
                    console.error('Error marking inquiry as responded:', error);
                    alert('An error occurred. Please try again.');
                }
            },

            async markAsClosed(inquiry) {
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    alert('Please login to continue.');
                    return;
                }

                if (!confirm('Mark this inquiry as closed? This action cannot be undone.')) {
                    return;
                }

                try {
                    const response = await fetch(`/api/v1/landlord/inquiries/${inquiry.id}/close`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (result.success) {
                        inquiry.status = 'closed';
                        alert('Inquiry marked as closed.');
                    } else {
                        alert(result.message || 'Failed to mark inquiry as closed.');
                    }
                } catch (error) {
                    console.error('Error marking inquiry as closed:', error);
                    alert('An error occurred. Please try again.');
                }
            },

            async deleteInquiry(inquiry) {
                if (!confirm('Delete this inquiry? This cannot be undone.')) {
                    return;
                }
                try {
                    const token = localStorage.getItem('auth_token');
                    const response = await fetch(`/api/v1/landlord/inquiries/${inquiry.id}`, {
                        method: 'DELETE',
                        headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                    });
                    const result = await response.json();
                    if (response.ok && result.success) {
                        this.allInquiries = this.allInquiries.filter(i => i.id !== inquiry.id);
                        this.recentInquiries = this.recentInquiries.filter(i => i.id !== inquiry.id);
                    } else {
                        alert(result.message || 'Failed to delete inquiry.');
                    }
                } catch (error) {
                    console.error('Error deleting inquiry:', error);
                    alert('An error occurred. Please try again.');
                }
            },

            async openMarkAsRentedModal(property) {
                this.selectedProperty = property;
                this.showRentModal = true;
                this.selectedTenant = null;
                this.propertyInquiries = [];
                this.loadingInquiries = true;
                this.showManualSearch = false;
                this.tenantSearch = '';
                this.tenantSearchResults = [];
                this.rentalStartDate = '';
                this.rentError = '';
                
                // Load inquiries for this property
                await this.loadPropertyInquiries(property.id);
            },
            
            async loadPropertyInquiries(propertyId) {
                this.loadingInquiries = true;
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    this.loadingInquiries = false;
                    return;
                }

                try {
                    const response = await fetch(`/api/v1/landlord/properties/${propertyId}/inquiries`, {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (result.success && result.data) {
                        this.propertyInquiries = result.data;
                    }
                } catch (error) {
                    console.error('Error loading property inquiries:', error);
                    this.propertyInquiries = [];
                } finally {
                    this.loadingInquiries = false;
                }
            },
            
            selectTenantFromInquiry(inquiry) {
                this.selectedTenant = {
                    id: inquiry.tenant_id,
                    tenant_id: inquiry.tenant_id,
                    name: inquiry.tenant_name,
                    tenant_name: inquiry.tenant_name,
                    email: inquiry.tenant_email,
                    tenant_email: inquiry.tenant_email,
                    phone: inquiry.tenant_phone,
                    tenant_phone: inquiry.tenant_phone,
                };
            },

            async searchTenants() {
                if (!this.tenantSearch || this.tenantSearch.length < 2) {
                    this.tenantSearchResults = [];
                    return;
                }

                this.searchingTenants = true;
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    this.searchingTenants = false;
                    return;
                }

                try {
                    // Search tenants
                    const response = await fetch(`/api/v1/landlord/tenants/search?search=${encodeURIComponent(this.tenantSearch)}`, {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (result.success && result.data) {
                        this.tenantSearchResults = (Array.isArray(result.data) ? result.data : []).map(u => ({
                            id: u.id,
                            name: u.name,
                            email: u.email,
                            phone: u.phone,
                        }));
                    }
                } catch (error) {
                    console.error('Error searching tenants:', error);
                    this.tenantSearchResults = [];
                } finally {
                    this.searchingTenants = false;
                }
            },

            selectTenant(tenant) {
                this.selectedTenant = {
                    id: tenant.id,
                    tenant_id: tenant.id,
                    name: tenant.name,
                    tenant_name: tenant.name,
                    email: tenant.email,
                    tenant_email: tenant.email,
                    phone: tenant.phone,
                    tenant_phone: tenant.phone,
                };
                this.tenantSearch = tenant.name;
                this.tenantSearchResults = [];
            },

            async markPropertyAsRented() {
                if (!this.selectedTenant || !this.rentalStartDate) {
                    this.rentError = 'Please select a tenant and enter a start date.';
                    return;
                }

                this.markingAsRented = true;
                this.rentError = '';
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    this.rentError = 'Please login to continue.';
                    this.markingAsRented = false;
                    return;
                }

                try {
                    const response = await fetch(`/api/v1/landlord/properties/${this.selectedProperty.id}/rent`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            tenant_id: this.selectedTenant.id || this.selectedTenant.tenant_id,
                            start_date: this.rentalStartDate,
                        })
                    });

                    const result = await response.json();
                    if (result.success) {
                        // Update property status
                        this.selectedProperty.status = 'rented';
                        // Remove from allProperties and add to rentedProperties
                        this.allProperties = this.allProperties.filter(p => p.id !== this.selectedProperty.id);
                        // Reload rented properties
                        await this.loadRentedProperties();
                        // Close modal
                        this.showRentModal = false;
                        this.selectedTenant = null;
                        this.propertyInquiries = [];
                        this.showManualSearch = false;
                        this.tenantSearch = '';
                        this.rentalStartDate = '';
                        alert('Property marked as rented successfully! It will no longer appear in public listings.');
                    } else {
                        this.rentError = result.message || 'Failed to mark property as rented.';
                    }
                } catch (error) {
                    console.error('Error marking property as rented:', error);
                    this.rentError = 'An error occurred. Please try again.';
                } finally {
                    this.markingAsRented = false;
                }
            },

            async submitForReview(property) {
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    alert('Please login as a landlord.');
                    window.location.href = '/login';
                    return;
                }

                if (!confirm('Submit this property for admin review?')) {
                    return;
                }

                try {
                    const response = await fetch(`/api/v1/landlord/properties/${property.id}/publish`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        property.status = 'pending_review';
                        alert('Property submitted for review. Admin will approve it soon.');
                    } else {
                        alert(result.message || 'Failed to submit property for review.');
                    }
                } catch (error) {
                    console.error('Error submitting property for review:', error);
                    alert('An error occurred while submitting the property for review.');
                }
            },

            logout() {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user');
                window.location.href = '/login';
            }
        }));

        // Verification Component
        Alpine.data('verificationApp', () => ({
            verificationStatus: null,
            verificationNotes: null,
            loading: false,
            submitting: false,
            errorMessage: '',
            successMessage: '',
            formData: {
                national_id: '',
                id_document: null,
                proof_of_ownership: null
            },

            async loadVerificationStatus() {
                this.loading = true;
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    this.loading = false;
                    return;
                }

                try {
                    const response = await fetch('/api/v1/landlord/verification/status', {
                        headers: {
                            Accept: 'application/json',
                            Authorization: `Bearer ${token}`,
                        },
                    });

                    const data = await response.json();
                    if (data.success && data.data) {
                        // Use latest verification request status if available, otherwise use landlord profile status
                        const latestRequest = data.data.latest_verification_request;
                        this.verificationStatus = latestRequest?.status || data.data.verification_status || 'unverified';
                        this.verificationNotes = latestRequest?.admin_notes || null;
                        
                        // Update parent dashboard status
                        if (window.dashboardApp) {
                            window.dashboardApp.verificationStatus = this.verificationStatus;
                        }
                    }
                } catch (error) {
                    console.error('Error loading verification status:', error);
                } finally {
                    this.loading = false;
                }
            },

            handleFileSelect(event, field) {
                const file = event.target.files[0];
                if (!file) return;

                // Validate file size (5MB max)
                const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                if (file.size > maxSize) {
                    this.errorMessage = `File size must be less than 5MB. Selected file is ${(file.size / 1024 / 1024).toFixed(2)}MB.`;
                    event.target.value = '';
                    return;
                }

                // Validate file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
                if (!allowedTypes.includes(file.type)) {
                    this.errorMessage = 'File must be a JPG, PNG, or PDF.';
                    event.target.value = '';
                    return;
                }

                this.formData[field] = file;
                this.errorMessage = '';
            },

            async submitVerification() {
                this.submitting = true;
                this.errorMessage = '';
                this.successMessage = '';

                const token = localStorage.getItem('auth_token');
                if (!token) {
                    this.errorMessage = 'Please login to submit verification.';
                    this.submitting = false;
                    window.location.href = '/login';
                    return;
                }

                if (!this.formData.national_id || !this.formData.id_document) {
                    this.errorMessage = 'Please fill in all required fields.';
                    this.submitting = false;
                    return;
                }

                try {
                    const formData = new FormData();
                    formData.append('national_id', this.formData.national_id);
                    formData.append('id_document', this.formData.id_document);
                    if (this.formData.proof_of_ownership) {
                        formData.append('proof_of_ownership', this.formData.proof_of_ownership);
                    }

                    const response = await fetch('/api/v1/landlord/verification/submit', {
                        method: 'POST',
                        headers: {
                            Accept: 'application/json',
                            Authorization: `Bearer ${token}`,
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        this.successMessage = data.message || 'Verification documents submitted successfully! Admin will review within 24-48 hours.';
                        this.verificationStatus = 'pending';
                        this.verificationNotes = null;
                        
                        // Reset form
                        this.formData = {
                            national_id: '',
                            id_document: null,
                            proof_of_ownership: null
                        };
                        
                        // Reset file inputs
                        document.querySelectorAll('input[type="file"]').forEach(input => input.value = '');

                        // Update parent dashboard status
                        if (window.dashboardApp) {
                            window.dashboardApp.verificationStatus = 'pending';
                        }

                        // Reload status after a moment
                        setTimeout(() => {
                            this.loadVerificationStatus();
                        }, 1000);
                    } else {
                        this.errorMessage = data.message || 'Failed to submit verification. Please try again.';
                        if (data.errors) {
                            const errorMessages = Object.values(data.errors).flat();
                            this.errorMessage = errorMessages.join(', ');
                        }
                    }
                } catch (error) {
                    console.error('Error submitting verification:', error);
                    this.errorMessage = 'An error occurred while submitting verification. Please try again.';
                } finally {
                    this.submitting = false;
                }
            }
        }));
    });
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>