{{-- resources/views/landlord/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - NyumbaLink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
    
    <div class="flex h-screen overflow-hidden" x-data="dashboardApp" x-init="initialize()">
        
        {{-- Sidebar --}}
        <aside class="hidden lg:block lg:w-72 bg-gradient-to-b from-slate-900 via-blue-900 to-indigo-900 text-white shadow-2xl">
            
            {{-- Logo --}}
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-2xl">NL</span>
                    </div>
                    <div>
                        <div class="font-bold text-xl">NyumbaLink</div>
                        <div class="text-blue-300 text-xs">Landlord Portal</div>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="p-4 space-y-2">
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

                <button @click="activeTab = 'inquiries'"
                   :class="activeTab === 'inquiries' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-medium">Inquiries</span>
                    <span x-show="pendingInquiries > 0" class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse" x-text="pendingInquiries"></span>
                </button>

                <button @click="activeTab = 'analytics'"
                   :class="activeTab === 'analytics' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="font-medium">Analytics</span>
                </button>
            </nav>

            {{-- User Profile --}}
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10">
                <div class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl backdrop-blur-sm">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-full flex items-center justify-center font-bold text-white">
                        <span x-text="userName?.[0] || 'L'"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium truncate" x-text="userName"></div>
                        <div class="text-xs text-blue-300">Verified</div>
                    </div>
                    <button @click="logout()" class="text-gray-400 hover:text-white">
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

                        <button class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-2 rounded-xl hover:from-blue-700 hover:to-indigo-700 transition shadow-lg flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Add Property</span>
                        </button>
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

                {{-- Other Tabs (Placeholders) --}}
                <div x-show="activeTab === 'properties'">
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                        <h3 class="text-xl font-bold mb-6">My Properties</h3>
                        <div class="text-center py-20 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p class="text-lg mb-4">Properties list coming soon</p>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'inquiries'">
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                        <h3 class="text-xl font-bold mb-6">All Inquiries</h3>
                        <div class="text-center py-20 text-gray-500">
                            <p>Inquiries management coming soon</p>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'analytics'">
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                        <h3 class="text-xl font-bold mb-6">Analytics</h3>
                        <div class="text-center py-20 text-gray-500">
                            <p>Detailed analytics coming soon</p>
                        </div>
                    </div>
                </div>

            </main>
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
            
            // Computed
            get pageTitle() {
                const titles = {
                    overview: 'Dashboard Overview',
                    properties: 'My Properties',
                    inquiries: 'Inquiries',
                    analytics: 'Analytics'
                };
                return titles[this.activeTab] || 'Dashboard';
            },
            
            get pageSubtitle() {
                const subtitles = {
                    overview: 'Welcome back! Here\'s what\'s happening',
                    properties: 'Manage your listed properties',
                    inquiries: 'View and respond to inquiries',
                    analytics: 'Performance insights'
                };
                return subtitles[this.activeTab] || '';
            },

            get displayRating() {
                return (this.avgRating || 0).toFixed(1);
            },

            // Methods
            async initialize() {
                this.loadUser();
                await this.loadAnalytics();
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

            logout() {
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user');
                window.location.href = '/login';
            }
        }))
    });
</script>
</body>
</html>