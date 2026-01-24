{{-- resources/views/properties/index.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Properties - NyumbaLink Malawi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50" x-data="propertiesPage()">
    
    {{-- Navigation --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="/" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xl">NL</span>
                    </div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                        NyumbaLink
                    </span>
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/properties" class="text-blue-600 font-semibold">Browse Properties</a>
                    <a href="/about" class="text-gray-700 hover:text-blue-600">About</a>
                    <a href="/login" class="text-gray-700 hover:text-blue-600">Login</a>
                    <a href="/register" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">List Property</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Header --}}
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-2">Browse Properties</h1>
            <p class="text-blue-100" x-text="`${totalProperties} properties available across Malawi`"></p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white shadow-sm border-b sticky top-16 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                {{-- City Filter --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">City</label>
                    <select x-model="filters.city" @change="applyFilters()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">All Cities</option>
                        <option value="Lilongwe">Lilongwe</option>
                        <option value="Blantyre">Blantyre</option>
                        <option value="Mzuzu">Mzuzu</option>
                        <option value="Zomba">Zomba</option>
                        <option value="Mangochi">Mangochi</option>
                    </select>
                </div>

                {{-- Property Type --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Property Type</label>
                    <select x-model="filters.property_type" @change="applyFilters()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option value="residential">Residential</option>
                        <option value="commercial">Commercial</option>
                        <option value="land">Land/Plot</option>
                    </select>
                </div>

                {{-- Min Price --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Min Price (MWK)</label>
                    <input type="number" x-model="filters.min_price" @input="applyFilters()" placeholder="100,000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Max Price --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Max Price (MWK)</label>
                    <input type="number" x-model="filters.max_price" @input="applyFilters()" placeholder="1,000,000" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Sort --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sort By</label>
                    <select x-model="filters.sort" @change="applyFilters()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="newest">Newest First</option>
                        <option value="price_asc">Price: Low to High</option>
                        <option value="price_desc">Price: High to Low</option>
                        <option value="popular">Most Popular</option>
                    </select>
                </div>
            </div>

            {{-- Search Bar --}}
            <div class="mt-4">
                <input 
                    type="text" 
                    x-model="filters.search" 
                    @input.debounce.500ms="applyFilters()"
                    placeholder="Search by location, keywords..." 
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                >
            </div>

            {{-- Clear Filters --}}
            <div class="mt-4 flex justify-between items-center">
                <button @click="clearFilters()" class="text-blue-600 hover:text-blue-700 font-semibold">
                    Clear All Filters
                </button>
                <span class="text-gray-600" x-text="`Showing ${properties.length} properties`"></span>
            </div>
        </div>
    </div>

    {{-- Property Grid --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Loading State --}}
        <div x-show="loading" class="text-center py-20">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            <p class="mt-4 text-gray-600">Loading properties...</p>
        </div>

        {{-- Properties Grid --}}
        <div x-show="!loading && properties.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <template x-for="property in properties" :key="property.id">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition group">
                    {{-- Image --}}
                    <div class="relative overflow-hidden h-64">
                        <img 
                            :src="property.primary_image_url || 'https://via.placeholder.com/500x300?text=No+Image'" 
                            :alt="property.title"
                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                        >
                        {{-- Boosted Badge --}}
                        <div x-show="property.is_boosted" class="absolute top-4 right-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold flex items-center">
                            ⭐ Featured
                        </div>
                        {{-- Property Type Badge --}}
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-full text-sm font-semibold capitalize">
                            <span x-text="property.property_type"></span>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2" x-text="property.title"></h3>
                        
                        {{-- Location --}}
                        <p class="text-gray-600 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <span x-text="`${property.area || property.district}, ${property.city}`"></span>
                        </p>

                        {{-- Price --}}
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-3xl font-bold text-blue-600">
                                MWK <span x-text="Number(property.price).toLocaleString()"></span>
                            </span>
                            <span class="text-gray-500" x-text="property.property_type === 'land' ? 'one-time' : '/month'"></span>
                        </div>

                        {{-- Features --}}
                        <div class="flex items-center justify-between text-gray-600 text-sm mb-4">
                            <span x-show="property.bedrooms">🛏️ <span x-text="property.bedrooms"></span> Beds</span>
                            <span x-show="property.bathrooms">🚿 <span x-text="property.bathrooms"></span> Baths</span>
                            <span x-show="property.size_sqm">📐 <span x-text="property.size_sqm"></span>m²</span>
                        </div>

                        {{-- Landlord Info --}}
                        <div class="flex items-center justify-between text-sm text-gray-600 mb-4 pb-4 border-b">
                            <span class="flex items-center">
                                👤 <span x-text="property.landlord_name" class="ml-1"></span>
                            </span>
                            <span class="flex items-center" x-show="property.landlord_rating">
                                ⭐ <span x-text="property.landlord_rating" class="ml-1"></span>
                            </span>
                        </div>

                        {{-- View Button --}}
                        <a 
                            :href="`/properties/${property.id}`"
                            class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 transition font-semibold"
                        >
                            View Details
                        </a>
                    </div>
                </div>
            </template>
        </div>

        {{-- Empty State --}}
        <div x-show="!loading && properties.length === 0" class="text-center py-20">
            <div class="text-6xl mb-4">🏠</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-2">No Properties Found</h3>
            <p class="text-gray-600 mb-6">Try adjusting your filters</p>
            <button @click="clearFilters()" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">
                Clear Filters
            </button>
        </div>

        {{-- Pagination --}}
        <div x-show="!loading && totalPages > 1" class="mt-12 flex justify-center items-center space-x-4">
            <button 
                @click="changePage(currentPage - 1)"
                :disabled="currentPage === 1"
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50"
            >
                Previous
            </button>
            
            <span class="text-gray-700">
                Page <span x-text="currentPage"></span> of <span x-text="totalPages"></span>
            </span>
            
            <button 
                @click="changePage(currentPage + 1)"
                :disabled="currentPage === totalPages"
                class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50"
            >
                Next
            </button>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white py-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">NyumbaLink</h3>
                    <p class="text-gray-400">Malawi's trusted property marketplace</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/" class="hover:text-white">Home</a></li>
                        <li><a href="/properties" class="hover:text-white">Browse Properties</a></li>
                        <li><a href="/about" class="hover:text-white">About Us</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">For Landlords</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/register" class="hover:text-white">List Property</a></li>
                        <li><a href="/login" class="hover:text-white">Login</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Contact</h4>
                    <p class="text-gray-400">Email: info@nyumbalink.com</p>
                    <p class="text-gray-400">Phone: +265 888 000 000</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 NyumbaLink Malawi. All rights reserved.</p>
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

                async init() {
                    await this.loadProperties();
                },

                async loadProperties() {
                    this.loading = true;
                    
                    // Build query params
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
                            headers: {
                                'Accept': 'application/json'
                            }
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
                }
            }
        }
    </script>
</body>
</html>