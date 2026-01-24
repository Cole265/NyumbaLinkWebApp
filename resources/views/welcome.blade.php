{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NyumbaLink Malawi - Find Your Perfect Property</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    
    {{-- Navigation --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">NL</span>
                        </div>
                        <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            NyumbaLink
                        </span>
                    </a>
                </div>

                {{-- Desktop Navigation --}}
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/properties" class="text-gray-700 hover:text-blue-600 transition">Browse Properties</a>
                    <a href="/about" class="text-gray-700 hover:text-blue-600 transition">About</a>
                    <a href="/login" class="text-gray-700 hover:text-blue-600 transition">Login</a>
                    <a href="/register" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        List Property
                    </a>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="md:hidden">
                    <button @click="mobileMenu = !mobileMenu" class="text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 text-white py-20 overflow-hidden">
        {{-- Animated Background --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full blur-3xl animate-pulse delay-1000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                    Find Your Perfect Home in Malawi
                </h1>
                <p class="text-xl md:text-2xl mb-12 text-blue-100">
                    Browse verified properties across Lilongwe, Blantyre, Mzuzu, and beyond
                </p>

                {{-- Search Box --}}
                <div class="bg-white rounded-2xl shadow-2xl p-8 text-gray-900">
                    <form action="/properties" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        {{-- City --}}
                        <div class="text-left">
                            <label class="block text-sm font-semibold mb-2 text-gray-700">City</label>
                            <select name="city" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Cities</option>
                                <option>Lilongwe</option>
                                <option>Blantyre</option>
                                <option>Mzuzu</option>
                                <option>Zomba</option>
                                <option>Mangochi</option>
                            </select>
                        </div>

                        {{-- Property Type --}}
                        <div class="text-left">
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Property Type</label>
                            <select name="property_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Types</option>
                                <option value="residential">Residential</option>
                                <option value="commercial">Commercial</option>
                                <option value="land">Land/Plot</option>
                            </select>
                        </div>

                        {{-- Max Price --}}
                        <div class="text-left">
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Max Price (MWK)</label>
                            <input type="number" name="max_price" placeholder="500,000" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Search Button --}}
                        <div class="text-left">
                            <label class="block text-sm font-semibold mb-2 text-gray-700 invisible">Search</label>
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-semibold shadow-lg">
                                🔍 Search Properties
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Properties --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Featured Properties</h2>
                <p class="text-xl text-gray-600">Discover the best properties available right now</p>
            </div>

            {{-- Property Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Property Card 1 --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition group">
                    <div class="relative overflow-hidden h-64">
                        <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=500" alt="Property" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute top-4 right-4 bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            Featured
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Modern 3BR House</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            Area 47, Lilongwe
                        </p>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-3xl font-bold text-blue-600">MWK 350,000</span>
                            <span class="text-gray-500">/month</span>
                        </div>
                        <div class="flex items-center justify-between text-gray-600 text-sm mb-4">
                            <span>🛏️ 3 Beds</span>
                            <span>🚿 2 Baths</span>
                            <span>📐 120m²</span>
                        </div>
                        <a href="/properties/1" class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                            View Details
                        </a>
                    </div>
                </div>

                {{-- Property Card 2 --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition group">
                    <div class="relative overflow-hidden h-64">
                        <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=500" alt="Property" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Commercial Shop</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            City Centre, Blantyre
                        </p>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-3xl font-bold text-blue-600">MWK 800,000</span>
                            <span class="text-gray-500">/month</span>
                        </div>
                        <div class="flex items-center justify-between text-gray-600 text-sm mb-4">
                            <span>🏢 Commercial</span>
                            <span>🚿 1 Bath</span>
                            <span>📐 60m²</span>
                        </div>
                        <a href="/properties/2" class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                            View Details
                        </a>
                    </div>
                </div>

                {{-- Property Card 3 --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition group">
                    <div class="relative overflow-hidden h-64">
                        <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?w=500" alt="Property" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Residential Plot</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            Area 49, Lilongwe
                        </p>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-3xl font-bold text-blue-600">MWK 15M</span>
                            <span class="text-gray-500">one-time</span>
                        </div>
                        <div class="flex items-center justify-between text-gray-600 text-sm mb-4">
                            <span>🏞️ Land</span>
                            <span>📋 Title Deed</span>
                            <span>📐 2023m²</span>
                        </div>
                        <a href="/properties/3" class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg hover:bg-blue-700 transition font-semibold">
                            View Details
                        </a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="/properties" class="inline-block bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-semibold text-lg shadow-lg">
                    View All Properties →
                </a>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-xl text-gray-600">Find your perfect property in 3 simple steps</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                {{-- Step 1 --}}
                <div class="text-center">
                    <div class="w-20 h-20 bg-blue-600 text-white rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg">
                        1
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Search Properties</h3>
                    <p class="text-gray-600 text-lg">Browse verified properties across Malawi with advanced filters</p>
                </div>

                {{-- Step 2 --}}
                <div class="text-center">
                    <div class="w-20 h-20 bg-indigo-600 text-white rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg">
                        2
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Contact Landlord</h3>
                    <p class="text-gray-600 text-lg">Send inquiries and connect directly via WhatsApp</p>
                </div>

                {{-- Step 3 --}}
                <div class="text-center">
                    <div class="w-20 h-20 bg-purple-600 text-white rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-lg">
                        3
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Move In</h3>
                    <p class="text-gray-600 text-lg">Complete the deal and rate your experience</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to List Your Property?</h2>
            <p class="text-xl mb-8 text-blue-100">Join hundreds of verified landlords on NyumbaLink</p>
            <a href="/register" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg hover:bg-gray-100 transition font-bold text-lg shadow-2xl">
                Get Started Free →
            </a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">NyumbaLink</h3>
                    <p class="text-gray-400">Malawi's trusted property marketplace</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/properties" class="hover:text-white">Browse Properties</a></li>
                        <li><a href="/about" class="hover:text-white">About Us</a></li>
                        <li><a href="/contact" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">For Landlords</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/register" class="hover:text-white">List Property</a></li>
                        <li><a href="/pricing" class="hover:text-white">Pricing</a></li>
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

</body>
</html>