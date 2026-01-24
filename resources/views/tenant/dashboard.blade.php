{{-- resources/views/tenant/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard - NyumbaLink</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 min-h-screen">
    
    <div class="flex h-screen overflow-hidden" x-data="tenantDashboard" x-init="initialize()">
        
        {{-- Sidebar --}}
        <aside class="hidden lg:block lg:w-72 bg-gradient-to-b from-purple-900 via-pink-900 to-rose-900 text-white shadow-2xl">
            
            {{-- Logo --}}
            <div class="p-6 border-b border-white/10">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-pink-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <span class="text-white font-bold text-2xl">NL</span>
                    </div>
                    <div>
                        <div class="font-bold text-xl">NyumbaLink</div>
                        <div class="text-pink-300 text-xs">Tenant Portal</div>
                    </div>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="p-4 space-y-2">
                <button @click="activeTab = 'browse'" 
                   :class="activeTab === 'browse' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span class="font-medium">Browse Properties</span>
                </button>

                <button @click="activeTab = 'inquiries'"
                   :class="activeTab === 'inquiries' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-medium">My Inquiries</span>
                    <span class="ml-auto bg-pink-500 text-white text-xs px-2 py-1 rounded-full" x-text="totalInquiries"></span>
                </button>

                <button @click="activeTab = 'ratings'"
                   :class="activeTab === 'ratings' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    <span class="font-medium">My Ratings</span>
                </button>

                <button @click="activeTab = 'saved'"
                   :class="activeTab === 'saved' ? 'bg-white/20 text-white' : 'text-gray-300 hover:bg-white/10'"
                   class="w-full flex items-center space-x-3 px-4 py-3 rounded-xl transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span class="font-medium">Saved Properties</span>
                </button>
            </nav>

            {{-- User Profile --}}
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10">
                <div class="flex items-center space-x-3 px-4 py-3 bg-white/10 rounded-xl backdrop-blur-sm">
                    <div class="w-10 h-10 bg-gradient-to-br from-pink-400 to-purple-600 rounded-full flex items-center justify-center font-bold text-white">
                        <span x-text="userName?.[0] || 'T'"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-medium truncate" x-text="userName"></div>
                        <div class="text-xs text-pink-300">Tenant</div>
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

                        <a href="/properties" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-2 rounded-xl hover:from-purple-700 hover:to-pink-700 transition shadow-lg flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Browse All</span>
                        </a>
                    </div>
                </div>
            </header>

            {{-- Content Area --}}
            <main class="flex-1 overflow-y-auto p-8">
                
                {{-- Browse Tab --}}
                <div x-show="activeTab === 'browse'">
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                        <h3 class="text-xl font-bold mb-6">Featured Properties</h3>
                        <div class="text-center py-20 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <p class="text-lg mb-4">Discover amazing properties</p>
                            <a href="/properties" class="inline-block bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700">Browse All Properties</a>
                        </div>
                    </div>
                </div>

                {{-- Inquiries Tab --}}
                <div x-show="activeTab === 'inquiries'">
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                        <h3 class="text-xl font-bold mb-6">My Inquiries</h3>
                        
                        <div x-show="inquiries.length > 0" class="space-y-4">
                            <template x-for="inquiry in inquiries" :key="inquiry.id">
                                <div class="bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl p-6 border border-purple-200">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-lg text-gray-900" x-text="inquiry.property_title"></h4>
                                            <p class="text-sm text-gray-600" x-text="inquiry.landlord_name"></p>
                                        </div>
                                        <span :class="{
                                            'bg-yellow-100 text-yellow-800': inquiry.status === 'pending',
                                            'bg-green-100 text-green-800': inquiry.status === 'responded',
                                            'bg-gray-100 text-gray-800': inquiry.status === 'closed'
                                        }" class="px-3 py-1 rounded-full text-xs font-semibold capitalize" x-text="inquiry.status"></span>
                                    </div>
                                    
                                    <div class="bg-white rounded-lg p-4 mb-4">
                                        <p class="text-sm text-gray-700" x-text="inquiry.message"></p>
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-500" x-text="new Date(inquiry.created_at).toLocaleDateString()"></span>
                                        <a :href="inquiry.whatsapp_link" target="_blank" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm flex items-center space-x-2">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                            </svg>
                                            <span>Contact</span>
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div x-show="inquiries.length === 0" class="text-center py-20 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-lg mb-4">No inquiries yet</p>
                            <p class="text-sm">Browse properties and send inquiries to landlords</p>
                        </div>
                    </div>
                </div>

                {{-- Ratings Tab --}}
                <div x-show="activeTab === 'ratings'">
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                        <h3 class="text-xl font-bold mb-6">My Ratings</h3>
                        
                        <div x-show="ratings.length > 0" class="space-y-4">
                            <template x-for="rating in ratings" :key="rating.id">
                                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl p-6 border border-yellow-200">
                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h4 class="font-bold text-lg text-gray-900" x-text="rating.landlord_name"></h4>
                                            <p class="text-sm text-gray-600" x-text="rating.property_title"></p>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-2xl font-bold text-yellow-600" x-text="rating.overall_rating.toFixed(1)"></div>
                                            <div class="text-xs text-gray-500">Overall</div>
                                        </div>
                                    </div>

                                    <div x-show="rating.review" class="bg-white rounded-lg p-4 mb-4">
                                        <p class="text-sm text-gray-700" x-text="rating.review"></p>
                                    </div>

                                    <div class="text-xs text-gray-500" x-text="new Date(rating.created_at).toLocaleDateString()"></div>
                                </div>
                            </template>
                        </div>

                        <div x-show="ratings.length === 0" class="text-center py-20 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                            </svg>
                            <p class="text-lg mb-4">No ratings yet</p>
                            <p class="text-sm">After renting, you can rate your landlord</p>
                        </div>
                    </div>
                </div>

                {{-- Saved Tab --}}
                <div x-show="activeTab === 'saved'">
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-xl p-6 border border-gray-200">
                        <h3 class="text-xl font-bold mb-6">Saved Properties</h3>
                        <div class="text-center py-20 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <p class="text-lg mb-4">No saved properties yet</p>
                            <p class="text-sm">Coming soon: Save your favorite properties</p>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tenantDashboard', () => ({
                // State
                activeTab: 'browse',
                userName: '',
                totalInquiries: 0,
                inquiries: [],
                ratings: [],
                
                // Computed
                get pageTitle() {
                    const titles = {
                        browse: 'Browse Properties',
                        inquiries: 'My Inquiries',
                        ratings: 'My Ratings',
                        saved: 'Saved Properties'
                    };
                    return titles[this.activeTab] || 'Dashboard';
                },
                
                get pageSubtitle() {
                    const subtitles = {
                        browse: 'Discover your next home',
                        inquiries: 'Track your property inquiries',
                        ratings: 'Your landlord reviews',
                        saved: 'Your favorite properties'
                    };
                    return subtitles[this.activeTab] || '';
                },

                // Methods
                async initialize() {
                    this.loadUser();
                    await this.loadInquiries();
                    await this.loadRatings();
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
                        this.userName = 'Tenant';
                    }
                },

                async loadInquiries() {
                    const token = localStorage.getItem('auth_token');
                    if (!token) return;

                    try {
                        const response = await fetch('/api/v1/tenant/inquiries', {
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            const result = await response.json();
                            if (result.success && Array.isArray(result.data.data)) {
                                this.inquiries = result.data.data;
                                this.totalInquiries = this.inquiries.length;
                            }
                        }
                    } catch (error) {
                        console.error('Error loading inquiries:', error);
                    }
                },

                async loadRatings() {
                    const token = localStorage.getItem('auth_token');
                    if (!token) return;

                    try {
                        const response = await fetch('/api/v1/tenant/ratings', {
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            }
                        });

                        if (response.ok) {
                            const result = await response.json();
                            if (result.success && Array.isArray(result.data.data)) {
                                this.ratings = result.data.data;
                            }
                        }
                    } catch (error) {
                        console.error('Error loading ratings:', error);
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