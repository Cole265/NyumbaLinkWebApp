{{-- resources/views/tenant/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard - Khomolanu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    
    <div class="flex h-screen overflow-hidden" x-data="tenantDashboard()" x-init="init()">
        
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
                        <div class="text-blue-300 text-xs">Tenant Portal</div>
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

                <button @click="activeTab = 'rentals'"
                   :class="activeTab === 'rentals' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="font-medium">My Rentals</span>
                    <span class="ml-auto bg-blue-500 text-white text-xs px-2 py-1 rounded-full" x-text="tenancies.length"></span>
                </button>

                <button @click="activeTab = 'inquiries'"
                   :class="activeTab === 'inquiries' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-medium">My Inquiries</span>
                </button>

                <button @click="activeTab = 'ratings'; loadRatings()"
                   :class="activeTab === 'ratings' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.802 2.035a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118L12 14.347l-2.802 2.035c-.784.57-1.838-.197-1.54-1.118l1.07-3.292a1 1 0 00-.364-1.118L5.562 8.72c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"/>
                    </svg>
                    <span class="font-medium">My Ratings</span>
                    <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-1 rounded-full" x-text="totalRatings"></span>
                </button>

                <button @click="activeTab = 'saved'; loadFavorites()"
                   :class="activeTab === 'saved' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span class="font-medium">Saved</span>
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full" x-text="favorites.length"></span>
                </button>

                <a href="/properties" 
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition text-gray-300 hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span class="font-medium">Browse Properties</span>
                </a>
            </nav>

            {{-- User Profile & Logout (bottom of sidebar) --}}
            <div class="p-4 border-t border-white/10 shrink-0 mt-auto">
                <div class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl backdrop-blur-sm">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-full flex items-center justify-center font-bold text-white">
                        <span x-text="userName?.[0] || 'T'"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium truncate" x-text="userName"></div>
                        <div class="text-xs text-blue-300">Tenant</div>
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
        <main class="flex-1 overflow-y-auto">
            <div class="p-6 lg:p-8">
                {{-- Header --}}
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900" x-text="pageTitle"></h1>
                    <p class="text-gray-600 mt-2" x-text="pageSubtitle"></p>
                </div>

                {{-- Overview Tab --}}
                <div x-show="activeTab === 'overview'" x-cloak>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-purple-100 rounded-xl">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-bold text-gray-900 mb-1" x-text="tenancies.length"></div>
                            <div class="text-sm text-gray-600">Active Rentals</div>
                        </div>

                        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-blue-100 rounded-xl">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-bold text-gray-900 mb-1" x-text="inquiries.length"></div>
                            <div class="text-sm text-gray-600">My Inquiries</div>
                        </div>

                        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                            <div class="flex items-center justify-between mb-4">
                                <div class="p-3 bg-green-100 rounded-xl">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="text-3xl font-bold text-gray-900 mb-1" x-text="totalRatings"></div>
                            <div class="text-sm text-gray-600">Properties Rated</div>
                        </div>
                    </div>

                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200 mb-6">
                        <h3 class="text-xl font-bold mb-4">Recent Activity</h3>
                        <div x-show="tenancies.length === 0 && inquiries.length === 0" class="text-center py-8 text-gray-500">
                            <p>No recent activity. Start by browsing properties or making an inquiry!</p>
                        </div>
                        <div x-show="tenancies.length > 0 || inquiries.length > 0" class="space-y-4">
                            <template x-for="tenancy in tenancies.slice(0, 3)" :key="tenancy.id">
                                <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                                    <div class="p-2 bg-purple-100 rounded-lg">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-900" x-text="tenancy.property.title"></p>
                                        <p class="text-sm text-gray-600">Rented since <span x-text="new Date(tenancy.start_date).toLocaleDateString()"></span></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Rentals Tab --}}
                <div x-show="activeTab === 'rentals'" x-cloak>
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-200">
                        <div class="p-6 border-b">
                            <h3 class="text-2xl font-bold">My Rented Properties</h3>
                            <p class="text-gray-600 mt-1">Manage your current rentals and rate properties when you vacate</p>
                            <div class="mt-3 p-3 bg-purple-50 border border-purple-200 rounded-lg">
                                <p class="text-sm text-purple-800">
                                    <strong>How to vacate:</strong> Click "Rate & Mark as Vacated" on any rented property. You'll be asked to rate the landlord and property, then the property will become available for rent again.
                                </p>
                            </div>
                        </div>

                        <div x-show="loading" class="p-12 text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        </div>

                        <div x-show="!loading && tenancies.length === 0" class="text-center py-20">
                            <div class="text-5xl mb-4">🏠</div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Active Rentals</h3>
                            <p class="text-gray-600 mb-6">You don't have any active rental properties at the moment.</p>
                            <a href="/properties" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">
                                Browse Available Properties
                            </a>
                        </div>

                        <div x-show="!loading && tenancies.length > 0" class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <template x-for="tenancy in tenancies" :key="tenancy.id">
                                    <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition border border-gray-200">
                                        <img :src="tenancy.property.primary_image_url || 'https://placehold.co/400x200/e2e8f0/64748b?text=No+Image'" class="w-full h-48 object-cover">
                                        <div class="p-6">
                                            <div class="flex items-start justify-between mb-4">
                                                <div>
                                                    <h3 class="text-xl font-bold text-gray-900 mb-1" x-text="tenancy.property.title"></h3>
                                                    <p class="text-sm text-gray-600" x-text="`${tenancy.property.city}, ${tenancy.property.district}`"></p>
                                                </div>
                                                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">Rented</span>
                                            </div>

                                            <div class="space-y-2 mb-4 text-sm text-gray-600">
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span>MWK <span x-text="Number(tenancy.property.price).toLocaleString()"></span>/month</span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    <span>Rented since: <span x-text="new Date(tenancy.start_date).toLocaleDateString()"></span></span>
                                                </div>
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    <span>Landlord: <span x-text="tenancy.landlord_name"></span></span>
                                                </div>
                                            </div>

                                            <button
                                                @click="openVacateModal(tenancy)"
                                                class="w-full px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold transition"
                                            >
                                                Rate & Mark as Vacated
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Inquiries Tab --}}
                <div x-show="activeTab === 'inquiries'" x-cloak>
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-200">
                        <div class="p-6 border-b">
                            <h3 class="text-2xl font-bold">My Inquiries</h3>
                            <p class="text-gray-600 mt-1">View and manage your property inquiries</p>
                        </div>

                        <div x-show="loadingInquiries" class="p-12 text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        </div>

                        <div x-show="!loadingInquiries && inquiries.length === 0" class="text-center py-20">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <h3 class="text-xl font-bold text-gray-700 mb-2">No Inquiries Yet</h3>
                            <p class="text-gray-500 mb-6">You haven't made any inquiries yet. Browse properties and send inquiries to landlords.</p>
                            <a href="/properties" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-semibold">Browse Properties</a>
                        </div>

                        <div x-show="!loadingInquiries && inquiries.length > 0" class="p-6">
                            <div class="space-y-4">
                                <template x-for="inquiry in inquiries" :key="inquiry.id">
                                    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <h4 class="font-bold text-lg text-gray-900" x-text="inquiry.property_title || 'Property'"></h4>
                                                <p class="text-sm text-gray-600" x-text="inquiry.property_location || ''"></p>
                                            </div>
                                            <span :class="{
                                                'bg-yellow-100 text-yellow-700': inquiry.status === 'pending',
                                                'bg-green-100 text-green-700': inquiry.status === 'responded',
                                                'bg-gray-100 text-gray-700': inquiry.status === 'closed'
                                            }" class="px-3 py-1 rounded-full text-xs font-semibold capitalize" x-text="inquiry.status"></span>
                                        </div>
                                        <p class="text-gray-700 mb-4" x-text="inquiry.message"></p>
                                        <div class="text-sm text-gray-500">
                                            Sent on <span x-text="new Date(inquiry.created_at).toLocaleDateString()"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ratings Tab --}}
                <div x-show="activeTab === 'ratings'" x-cloak>
                    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">My Ratings</h2>
                                <p class="text-sm text-gray-600">See the landlords and properties you have rated.</p>
                            </div>
                            <button
                                @click="loadRatings()"
                                class="px-3 py-1.5 text-xs font-semibold text-blue-700 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-100"
                            >
                                Refresh
                            </button>
                        </div>

                        <div x-show="loadingRatings" class="py-10 text-center text-gray-500">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mb-2"></div>
                            <p class="text-sm">Loading your ratings...</p>
                        </div>

                        <div x-show="!loadingRatings && ratings.length === 0" class="py-10 text-center text-gray-500">
                            <p class="text-sm">You haven't rated any landlords yet.</p>
                        </div>

                        <div x-show="!loadingRatings && ratings.length > 0" class="space-y-4">
                            <template x-for="rating in ratings" :key="rating.id">
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div class="flex items-start space-x-4">
                                        <img
                                            :src="rating.property_image || 'https://placehold.co/80x80/e2e8f0/64748b?text=Home'"
                                            class="w-16 h-16 rounded-lg object-cover"
                                        >
                                        <div>
                                            <h3 class="text-sm font-semibold text-gray-900" x-text="rating.property_title"></h3>
                                            <p class="text-xs text-gray-600 mt-1">
                                                Landlord:
                                                <span class="font-medium" x-text="rating.landlord_business || rating.landlord_name"></span>
                                            </p>
                                            <div class="flex items-center space-x-1 mt-1 text-yellow-500 text-xs">
                                                <template x-for="i in 5">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                                                         :class="i <= Math.round(rating.overall_rating) ? '' : 'text-gray-300'">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.802 2.035a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.802-2.035a1 1 0 00-1.175 0l-2.802 2.035c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z"/>
                                                    </svg>
                                                </template>
                                                <span class="ml-1 text-gray-700" x-text="Number(rating.overall_rating).toFixed(1)"></span>
                                            </div>
                                            <p class="mt-2 text-xs text-gray-700 line-clamp-2" x-text="rating.review || 'No written review.'"></p>
                                            <p class="mt-1 text-[11px] text-gray-400" x-text="new Date(rating.created_at).toLocaleDateString()"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Saved Tab --}}
                <div x-show="activeTab === 'saved'" x-cloak>
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl border border-gray-200">
                        <div class="p-6 border-b">
                            <h3 class="text-2xl font-bold">Saved Properties</h3>
                            <p class="text-gray-600 mt-1">Properties you've saved for later</p>
                        </div>
                        <div x-show="loadingFavorites" class="p-12 text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                        </div>
                        <div x-show="!loadingFavorites && favorites.length === 0" class="p-12 text-center text-gray-500">
                            <p class="text-lg">No saved properties yet.</p>
                            <a href="/properties" class="inline-block mt-4 text-blue-600 hover:text-blue-700 font-semibold">Browse properties →</a>
                        </div>
                        <div x-show="!loadingFavorites && favorites.length > 0" class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <template x-for="property in favorites" :key="property.id">
                                    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 flex">
                                        <img :src="property.primary_image_url || 'https://placehold.co/200x200/e2e8f0/64748b?text=No+Image'" class="w-36 h-36 object-cover flex-shrink-0" alt="">
                                        <div class="p-4 flex-1 min-w-0">
                                            <h4 class="font-bold text-gray-900 line-clamp-1" x-text="property.title"></h4>
                                            <p class="text-sm text-gray-600 mt-1" x-text="`${property.city || ''}${property.district ? ', ' + property.district : ''}`"></p>
                                            <p class="text-lg font-bold text-blue-600 mt-2">MWK <span x-text="Number(property.price).toLocaleString()"></span></p>
                                            <div class="mt-3 flex gap-2">
                                                <a :href="`/properties/${property.id}`" class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700">View</a>
                                                <button type="button" @click="removeFavorite(property.id)" class="px-3 py-1.5 bg-gray-200 text-gray-700 text-sm rounded-lg hover:bg-gray-300">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        {{-- Vacate & Rate Modal --}}
        <div x-show="showVacateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" x-cloak>
            <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto" @click.away="showVacateModal = false">
                <div class="p-6 border-b sticky top-0 bg-white z-10">
                    <h3 class="text-2xl font-bold text-gray-900">Rate Property & Mark as Vacated</h3>
                    <p class="text-sm text-gray-600 mt-1" x-text="selectedTenancy?.property?.title || ''"></p>
                </div>

                <form @submit.prevent="submitVacateAndRate()" class="p-6 space-y-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-sm text-blue-800">
                            <strong>Note:</strong> Once you mark this property as vacated, it will become available for rent again. 
                            Please provide honest ratings to help other tenants make informed decisions.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Communication <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="range" min="1" max="5" step="1" x-model="ratingForm.communication_rating" class="flex-1">
                                <span class="text-lg font-bold text-blue-600 w-8 text-center" x-text="ratingForm.communication_rating || 0"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Accuracy <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="range" min="1" max="5" step="1" x-model="ratingForm.accuracy_rating" class="flex-1">
                                <span class="text-lg font-bold text-blue-600 w-8 text-center" x-text="ratingForm.accuracy_rating || 0"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Cleanliness <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="range" min="1" max="5" step="1" x-model="ratingForm.cleanliness_rating" class="flex-1">
                                <span class="text-lg font-bold text-blue-600 w-8 text-center" x-text="ratingForm.cleanliness_rating || 0"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Professionalism <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="range" min="1" max="5" step="1" x-model="ratingForm.professionalism_rating" class="flex-1">
                                <span class="text-lg font-bold text-blue-600 w-8 text-center" x-text="ratingForm.professionalism_rating || 0"></span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Fairness <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-2">
                                <input type="range" min="1" max="5" step="1" x-model="ratingForm.fairness_rating" class="flex-1">
                                <span class="text-lg font-bold text-blue-600 w-8 text-center" x-text="ratingForm.fairness_rating || 0"></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Review (Optional)
                        </label>
                        <textarea
                            x-model="ratingForm.review"
                            rows="4"
                            placeholder="Share your experience with this property and landlord..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            maxlength="1000"
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1" x-text="`${(ratingForm.review || '').length}/1000 characters`"></p>
                    </div>

                    <div x-show="vacateError" class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-700" x-text="vacateError"></p>
                    </div>

                    <div class="flex space-x-3 pt-4">
                        <button
                            type="button"
                            @click="showVacateModal = false; resetRatingForm()"
                            class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 font-semibold"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="!isRatingFormValid || submittingVacate"
                            class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-semibold disabled:bg-gray-400 disabled:cursor-not-allowed"
                        >
                            <span x-show="!submittingVacate">Submit Rating & Vacate</span>
                            <span x-show="submittingVacate">Processing...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('tenantDashboard', () => ({
            activeTab: 'overview',
            userName: '',
            tenancies: [],
            inquiries: [],
            ratings: [],
            loading: false,
            loadingInquiries: false,
            loadingRatings: false,
            showVacateModal: false,
            selectedTenancy: null,
            ratingForm: {
                communication_rating: 5,
                accuracy_rating: 5,
                cleanliness_rating: 5,
                professionalism_rating: 5,
                fairness_rating: 5,
                review: '',
            },
            submittingVacate: false,
            vacateError: '',

            get pageTitle() {
                const titles = {
                    overview: 'Dashboard Overview',
                    rentals: 'My Rentals',
                    inquiries: 'My Inquiries',
                    ratings: 'My Ratings',
                    saved: 'Saved Properties',
                };
                return titles[this.activeTab] || 'Dashboard';
            },

            get pageSubtitle() {
                const subtitles = {
                    overview: 'Welcome back! Here\'s your activity summary',
                    rentals: 'Manage your current rentals and rate properties when you vacate',
                    inquiries: 'View and manage your property inquiries',
                    ratings: 'View and edit your landlord ratings',
                    saved: 'Properties you\'ve saved for later',
                };
                return subtitles[this.activeTab] || '';
            },

            favorites: [],
            loadingFavorites: false,

            get totalRatings() {
                return this.ratings.length;
            },

            get isRatingFormValid() {
                return this.ratingForm.communication_rating &&
                       this.ratingForm.accuracy_rating &&
                       this.ratingForm.cleanliness_rating &&
                       this.ratingForm.professionalism_rating &&
                       this.ratingForm.fairness_rating;
            },

            async init() {
                // Prevent browser back from leaving the platform when logged in
                if (localStorage.getItem('auth_token')) {
                    history.pushState(null, null, location.href);
                    window.addEventListener('popstate', function () {
                        history.pushState(null, null, location.href);
                    });
                }
                this.loadUser();
                await this.loadTenancies();
                await this.loadInquiries();
                // Ratings and favorites are loaded when opening their tabs
            },

            async loadFavorites() {
                this.loadingFavorites = true;
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    this.loadingFavorites = false;
                    return;
                }
                try {
                    const response = await fetch('/api/v1/tenant/favorites', {
                        headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                    });
                    const result = await response.json();
                    if (result.success && result.data) {
                        this.favorites = result.data.map(p => ({
                            id: p.id,
                            title: p.title,
                            city: p.city,
                            district: p.district,
                            price: p.price,
                            primary_image_url: p.primary_image_url,
                        }));
                    } else {
                        this.favorites = [];
                    }
                } catch (error) {
                    console.error('Error loading favorites:', error);
                    this.favorites = [];
                } finally {
                    this.loadingFavorites = false;
                }
            },

            async removeFavorite(propertyId) {
                const token = localStorage.getItem('auth_token');
                if (!token) return;
                try {
                    const response = await fetch(`/api/v1/tenant/favorites/${propertyId}`, {
                        method: 'DELETE',
                        headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                    });
                    const result = await response.json();
                    if (result.success) {
                        this.favorites = this.favorites.filter(p => p.id !== propertyId);
                    }
                } catch (error) {
                    console.error('Error removing favorite:', error);
                }
            },

            loadUser() {
                try {
                    const user = localStorage.getItem('user');
                    if (user) {
                        const userData = JSON.parse(user);
                        this.userName = userData.name || 'Tenant';
                    }
                } catch (error) {
                    console.error('Error loading user:', error);
                }
            },

            async loadTenancies() {
                this.loading = true;
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    this.loading = false;
                    window.location.href = '/login';
                    return;
                }

                try {
                    const response = await fetch('/api/v1/tenant/tenancies', {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (result.success && result.data) {
                        this.tenancies = result.data.map(t => ({
                            id: t.id,
                            property: {
                                id: t.property.id,
                                title: t.property.title,
                                city: t.property.city,
                                district: t.property.district,
                                price: t.property.price,
                                primary_image_url: t.property.primary_image_url,
                            },
                            landlord_name: t.landlord_name,
                            start_date: t.start_date,
                        }));
                    }
                } catch (error) {
                    console.error('Error loading tenancies:', error);
                    this.tenancies = [];
                } finally {
                    this.loading = false;
                }
            },

            async loadInquiries() {
                this.loadingInquiries = true;
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    this.loadingInquiries = false;
                    return;
                }

                try {
                    const response = await fetch('/api/v1/tenant/inquiries', {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (result.success && result.data) {
                        this.inquiries = (result.data.data || result.data || []).map(i => ({
                            id: i.id,
                            property_title: i.property?.title || i.listing?.property?.title,
                            property_location: i.property?.city || i.listing?.property?.city,
                            message: i.message,
                            status: i.status,
                            created_at: i.created_at,
                        }));
                    }
                } catch (error) {
                    console.error('Error loading inquiries:', error);
                    this.inquiries = [];
                } finally {
                    this.loadingInquiries = false;
                }
            },

            async loadRatings() {
                this.loadingRatings = true;
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    this.loadingRatings = false;
                    return;
                }

                try {
                    const response = await fetch('/api/v1/tenant/ratings', {
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        }
                    });

                    const result = await response.json();
                    if (result.success && result.data) {
                        const collection = Array.isArray(result.data)
                            ? result.data
                            : (result.data.data || []);

                        this.ratings = collection.map(r => ({
                            id: r.id,
                            landlord_name: r.landlord_name,
                            landlord_business: r.landlord_business,
                            property_title: r.property_title,
                            property_image: r.property_image,
                            overall_rating: r.overall_rating,
                            communication_rating: r.communication_rating,
                            accuracy_rating: r.accuracy_rating,
                            cleanliness_rating: r.cleanliness_rating,
                            professionalism_rating: r.professionalism_rating,
                            fairness_rating: r.fairness_rating,
                            review: r.review,
                            created_at: r.created_at,
                        }));
                    }
                } catch (error) {
                    console.error('Error loading ratings:', error);
                    this.ratings = [];
                } finally {
                    this.loadingRatings = false;
                }
            },

            openVacateModal(tenancy) {
                this.selectedTenancy = tenancy;
                this.showVacateModal = true;
                this.resetRatingForm();
            },

            resetRatingForm() {
                this.ratingForm = {
                    communication_rating: 5,
                    accuracy_rating: 5,
                    cleanliness_rating: 5,
                    professionalism_rating: 5,
                    fairness_rating: 5,
                    review: '',
                };
                this.vacateError = '';
            },

            async submitVacateAndRate() {
                if (!this.isRatingFormValid) {
                    this.vacateError = 'Please provide all ratings.';
                    return;
                }

                this.submittingVacate = true;
                this.vacateError = '';
                const token = localStorage.getItem('auth_token');
                if (!token) {
                    this.vacateError = 'Please login to continue.';
                    this.submittingVacate = false;
                    window.location.href = '/login';
                    return;
                }

                try {
                    const response = await fetch(`/api/v1/tenant/tenancies/${this.selectedTenancy.id}/vacate`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Authorization': `Bearer ${token}`,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.ratingForm)
                    });

                    const result = await response.json();
                    if (result.success) {
                        alert(result.message || 'Property marked as vacated and rated successfully.');
                        this.showVacateModal = false;
                        await this.loadTenancies();
                    } else {
                        // Prefer backend error detail if available
                        this.vacateError = result.error || result.message || 'Failed to vacate property. Please try again.';
                        if (result.errors) {
                            const errorMessages = Object.values(result.errors).flat();
                            this.vacateError = errorMessages.join(', ');
                        }
                    }
                } catch (error) {
                    console.error('Error vacating property:', error);
                    this.vacateError = 'An error occurred. Please try again.';
                } finally {
                    this.submittingVacate = false;
                }
            },

            logout() {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user');
                window.location.href = '/login';
            }
        }));
    });
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>
