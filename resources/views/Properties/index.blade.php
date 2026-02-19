{{-- resources/views/properties/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Properties - Khomolanu Malawi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50" x-data="propertiesPage()">
    
    {{-- Navigation --}}
    @include('partials.nav')

    {{-- Hero Header with Background --}}
    <div class="relative bg-gray-900 text-white py-16 overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1920&q=80" alt="Properties" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 to-indigo-900/90"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <h1 class="text-5xl font-bold mb-4">Find Your Perfect Property</h1>
            <p class="text-xl text-blue-100 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                <span x-text="`${totalProperties} properties available across Malawi`"></span>
            </p>
        </div>
    </div>

    {{-- Filters Section --}}
    <div class="bg-white shadow-md border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            {{-- Primary Filters --}}
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">📍 City</label>
                    <select x-model="filters.city" @change="applyFilters()" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">All Cities</option>
                        <option value="Lilongwe">Lilongwe</option>
                        <option value="Blantyre">Blantyre</option>
                        <option value="Mzuzu">Mzuzu</option>
                        <option value="Zomba">Zomba</option>
                        <option value="Mangochi">Mangochi</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🏠 Type</label>
                    <select x-model="filters.property_type" @change="applyFilters()" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="">All Types</option>
                        <option value="residential">Residential</option>
                        <option value="commercial">Commercial</option>
                        <option value="land">Land/Plot</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">💰 Min Price</label>
                    <input type="number" x-model="filters.min_price" @input="applyFilters()" placeholder="100,000" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">💵 Max Price</label>
                    <input type="number" x-model="filters.max_price" @input="applyFilters()" placeholder="1,000,000" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">🔄 Sort By</label>
                    <select x-model="filters.sort" @change="applyFilters()" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                        <option value="newest">Newest First</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="popular">Most Popular</option>
                    </select>
                </div>
            </div>

            {{-- Search Bar --}}
            <div class="relative">
                <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input 
                    type="text" 
                    x-model="filters.search" 
                    @input.debounce.500ms="applyFilters()"
                    placeholder="🔍 Search by location, area, or keywords..." 
                    class="w-full pl-12 pr-4 py-3.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                >
            </div>

            {{-- Filter Info & Clear --}}
            <div class="mt-4 flex justify-between items-center">
                <button @click="clearFilters()" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold transition">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Clear All Filters
                </button>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-700 font-medium">
                        Showing <span class="text-blue-600 font-bold" x-text="properties.length"></span> of 
                        <span class="text-blue-600 font-bold" x-text="totalProperties"></span> properties
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Properties Grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Loading State --}}
        <div x-show="loading" class="text-center py-20">
            <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-blue-600 border-t-transparent"></div>
            <p class="mt-4 text-gray-600 text-lg font-medium">Loading amazing properties...</p>
        </div>

        {{-- Properties Grid --}}
        <div x-show="!loading && properties.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-8" x-cloak>
            <template x-for="property in properties" :key="property.id">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 group border border-gray-100 transform hover:-translate-y-1">
                    {{-- Image --}}
                    <div class="relative overflow-hidden h-96 cursor-pointer" @click="openImageModal(property)">
                        <img 
                            :src="property.primary_image_url || 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=600&q=80'" 
                            :alt="property.title"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                        >
                        {{-- Zoom Icon --}}
                        <div class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/20 transition duration-300 opacity-0 group-hover:opacity-100">
                            <svg class="w-8 h-8 text-white drop-shadow-lg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"></path>
                            </svg>
                        </div>
                        {{-- Favorite heart (tenants only) --}}
                        <div x-show="canShowFavorites" class="absolute top-4 left-4 z-10">
                            <button type="button" @click.stop="toggleFavorite(property.id)"
                                class="p-2 rounded-full shadow-lg transition"
                                :class="favoriteIds.includes(property.id) ? 'bg-red-500/90 text-white hover:bg-red-600' : 'bg-white/90 text-gray-600 hover:bg-white'"
                                :title="favoriteIds.includes(property.id) ? 'Remove from saved' : 'Save property'">
                                <svg class="w-6 h-6" :class="favoriteIds.includes(property.id) ? 'fill-current' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        </div>
                        {{-- Featured Badge --}}
                        <div x-show="property.is_boosted" class="absolute top-4 right-4 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1.5 rounded-full text-sm font-bold flex items-center shadow-lg animate-pulse">
                            ⭐ Featured
                        </div>
                        {{-- Property Type Badge --}}
                        <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur-sm text-gray-900 px-3 py-1.5 rounded-full text-sm font-semibold capitalize shadow-md">
                            <span x-text="property.property_type"></span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-1" x-text="property.title"></h3>
                        
                        {{-- Location --}}
                        <p class="text-gray-600 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="line-clamp-1" x-text="`${property.area || property.district}, ${property.city}`"></span>
                        </p>

                        {{-- Price --}}
                        <div class="flex items-center justify-between mb-4 pb-4 border-b">
                            <span class="text-2xl font-bold text-blue-600">
                                MWK <span x-text="Number(property.price).toLocaleString()"></span>
                            </span>
                            <span class="text-gray-500 text-sm font-medium" x-text="property.property_type === 'land' ? 'one-time' : '/month'"></span>
                        </div>

                        {{-- Features Grid --}}
                        <div class="grid grid-cols-3 gap-3 mb-4 text-center text-sm">
                            <div x-show="property.bedrooms">
                                <div class="text-2xl mb-1">🛏️</div>
                                <div class="font-semibold text-gray-900" x-text="property.bedrooms"></div>
                                <div class="text-gray-500 text-xs">Beds</div>
                            </div>
                            <div x-show="property.bathrooms">
                                <div class="text-2xl mb-1">🚿</div>
                                <div class="font-semibold text-gray-900" x-text="property.bathrooms"></div>
                                <div class="text-gray-500 text-xs">Baths</div>
                            </div>
                            <div x-show="property.size_sqm">
                                <div class="text-2xl mb-1">📐</div>
                                <div class="font-semibold text-gray-900" x-text="property.size_sqm"></div>
                                <div class="text-gray-500 text-xs">m²</div>
                            </div>
                        </div>

                        {{-- Landlord Info --}}
                        <div class="flex items-center justify-between text-sm mb-4 pb-4 border-b bg-gray-50 -mx-6 px-6 py-3">
                            <span class="flex items-center text-gray-700">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full flex items-center justify-center text-white font-bold mr-2">
                                    <span x-text="property.landlord_name?.[0] || 'L'"></span>
                                </div>
                                <span class="font-medium" x-text="property.landlord_name"></span>
                            </span>
                            <span class="flex items-center text-yellow-500 font-medium" x-show="property.landlord_rating">
                                ⭐ <span x-text="property.landlord_rating" class="ml-1"></span>
                            </span>
                        </div>

                        {{-- View Button --}}
                        <a 
                            :href="`/properties/${property.id}`"
                            class="block w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-center py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-semibold shadow-lg"
                        >
                            View Details →
                        </a>
                    </div>
                </div>
            </template>
        </div>

        {{-- Empty State --}}
        <div x-show="!loading && properties.length === 0" class="text-center py-20 bg-white rounded-2xl shadow-lg" x-cloak>
            <div class="text-7xl mb-4">🔍</div>
            <h3 class="text-3xl font-bold text-gray-900 mb-3">No Properties Found</h3>
            <p class="text-gray-600 text-lg mb-8">We couldn't find any properties matching your search criteria</p>
            <button @click="clearFilters()" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-semibold shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Clear Filters & Try Again
            </button>
        </div>

        {{-- Pagination --}}
        <div x-show="!loading && totalPages > 1" class="mt-12 flex justify-center items-center space-x-4" x-cloak>
            <button 
                @click="changePage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed font-semibold transition shadow-sm"
            >
                ← Previous
            </button>
            
            <div class="flex items-center space-x-2">
                <template x-for="page in totalPages" :key="page">
                    <button 
                        x-show="page === 1 || page === totalPages || (page >= currentPage - 1 && page <= currentPage + 1)"
                        @click="changePage(page)"
                        :class="page === currentPage ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white' : 'bg-white text-gray-700 border-2 border-gray-300 hover:border-blue-500'"
                        class="w-12 h-12 rounded-lg font-semibold transition shadow-sm"
                        x-text="page"
                    ></button>
                </template>
            </div>
            
            <button 
                @click="changePage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 hover:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed font-semibold transition shadow-sm"
            >
                Next →
            </button>
        </div>
    </div>

    {{-- Image Modal --}}
    <div x-show="imageModal.open" @keydown.escape="imageModal.open = false" class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4" x-cloak @click="imageModal.open = false">
        <div @click.stop class="max-w-4xl w-full max-h-screen flex flex-col rounded-2xl overflow-hidden bg-black/50">
            {{-- Close Button --}}
            <div class="flex justify-end p-4">
                <button @click="imageModal.open = false" class="text-white hover:text-gray-300 transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Image Container --}}
            <div class="flex-1 flex items-center justify-center overflow-hidden">
                <img 
                    :src="imageModal.url" 
                    :alt="imageModal.title"
                    class="max-w-full max-h-full object-contain"
                >
            </div>

            {{-- Image Info --}}
            <div class="bg-black/70 text-white p-6 border-t border-white/10">
                <h3 class="text-xl font-bold mb-2" x-text="imageModal.title"></h3>
                <p class="text-gray-300 text-sm" x-text="imageModal.location"></p>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Khomolanu</h3>
                    <p class="text-gray-400">Malawi's trusted property marketplace</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/" class="hover:text-white transition">Home</a></li>
                        <li><a href="/properties" class="hover:text-white transition">Browse Properties</a></li>
                        <li><a href="/about" class="hover:text-white transition">About Us</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">For Landlords</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/register" class="hover:text-white transition">List Property</a></li>
                        <li><a href="/login" class="hover:text-white transition">Login</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Contact</h4>
                    <p class="text-gray-400">Email: info@khomolanu.com</p>
                    <p class="text-gray-400">Phone: +265 888 000 000</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Khomolanu Malawi. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function propertiesPage() {
            return {
                properties: [],
                loading: true,
                currentPage: 1,
                totalPages: 1,
                totalProperties: 0,
                filters: {
                    city: '',
                    property_type: '',
                    min_price: '',
                    max_price: '',
                    search: '',
                    sort: 'newest'
                },
                imageModal: {
                    open: false,
                    url: '',
                    title: '',
                    location: ''
                },
                favoriteIds: [],
                canShowFavorites: false,

                async init() {
                    await this.loadProperties();
                    const token = localStorage.getItem('auth_token');
                    if (token) this.loadFavoriteIds();
                },

                async loadFavoriteIds() {
                    const token = localStorage.getItem('auth_token');
                    if (!token) return;
                    try {
                        const response = await fetch('/api/v1/tenant/favorites/ids', {
                            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                        });
                        if (response.ok) {
                            const data = await response.json();
                            this.favoriteIds = (data.data || data.ids || []) || [];
                            this.canShowFavorites = true;
                        }
                    } catch (e) {
                        this.canShowFavorites = false;
                    }
                },

                async toggleFavorite(propertyId) {
                    const token = localStorage.getItem('auth_token');
                    if (!token) return;
                    const isFav = this.favoriteIds.includes(propertyId);
                    try {
                        const url = `/api/v1/tenant/favorites${isFav ? `/${propertyId}` : ''}`;
                        const options = {
                            method: isFav ? 'DELETE' : 'POST',
                            headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                        };
                        if (!isFav) options.headers['Content-Type'] = 'application/json';
                        if (!isFav) options.body = JSON.stringify({ property_id: propertyId });
                        const response = await fetch(url, options);
                        const data = await response.json();
                        if (response.ok && data.success) {
                            if (isFav) this.favoriteIds = this.favoriteIds.filter(id => id !== propertyId);
                            else this.favoriteIds = [...this.favoriteIds, propertyId];
                        }
                    } catch (e) {
                        console.error('Toggle favorite failed:', e);
                    }
                },

                async loadProperties() {
                    this.loading = true;
                    
                    const params = new URLSearchParams();
                    if (this.filters.city) params.append('city', this.filters.city);
                    if (this.filters.property_type) params.append('property_type', this.filters.property_type);
                    if (this.filters.min_price) params.append('min_price', this.filters.min_price);
                    if (this.filters.max_price) params.append('max_price', this.filters.max_price);
                    if (this.filters.search) params.append('search', this.filters.search);
                    if (this.filters.sort) params.append('sort', this.filters.sort);
                    params.append('page', this.currentPage);

                    try {
                        const response = await fetch(`/api/v1/properties?${params}`, {
                            headers: { 'Accept': 'application/json' }
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.properties = data.data.data;
                            this.currentPage = data.data.current_page;
                            this.totalPages = data.data.last_page;
                            this.totalProperties = data.data.total;
                        }
                    } catch (error) {
                        console.error('Error loading properties:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                applyFilters() {
                    this.currentPage = 1;
                    this.loadProperties();
                },

                clearFilters() {
                    this.filters = {
                        city: '',
                        property_type: '',
                        min_price: '',
                        max_price: '',
                        search: '',
                        sort: 'newest'
                    };
                    this.applyFilters();
                },

                changePage(page) {
                    if (page >= 1 && page <= this.totalPages) {
                        this.currentPage = page;
                        this.loadProperties();
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                openImageModal(property) {
                    this.imageModal = {
                        open: true,
                        url: property.primary_image_url,
                        title: property.title,
                        location: `${property.area || property.district}, ${property.city}`
                    };
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>