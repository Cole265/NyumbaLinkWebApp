{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Khomolanu Malawi - Find Your Perfect Property</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    
    {{-- Navigation --}}
    @include('partials.nav')

    {{-- Hero Section --}}
    <section class="relative bg-gray-900 text-white min-h-[600px] flex items-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1582407947304-fd86f028f716?w=1920&q=80" alt="African Family Home" class="w-full h-full object-cover opacity-40">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 via-indigo-900/80 to-purple-900/90"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-block px-4 py-2 bg-blue-500/20 backdrop-blur-sm rounded-full text-sm font-semibold mb-6 border border-blue-300/30">
                        🏠 Malawi's #1 Property Platform
                    </div>
                    <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                        Find Your Dream Home in <span class="text-blue-400">Malawi</span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-gray-200">Browse thousands of verified properties across Lilongwe, Blantyre, Mzuzu, and beyond</p>

                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/properties" class="inline-flex items-center justify-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-semibold text-lg shadow-2xl">
                            Browse Properties →
                        </a>
                        <a href="/register" class="inline-flex items-center justify-center bg-white/10 backdrop-blur-sm text-white px-8 py-4 rounded-lg hover:bg-white/20 transition font-semibold text-lg border border-white/30">
                            List Your Property
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-2xl p-8 text-gray-900">
                    <h3 class="text-2xl font-bold mb-6 text-gray-900">Start Your Search</h3>
                    <form action="/properties" method="GET" class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-700">City</label>
                            <select name="city" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">All Cities</option>
                                <option>Lilongwe</option>
                                <option>Blantyre</option>
                                <option>Mzuzu</option>
                                <option>Zomba</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-2 text-gray-700">Property Type</label>
                            <select name="property_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="">All Types</option>
                                <option value="residential">Residential</option>
                                <option value="commercial">Commercial</option>
                                <option value="land">Land/Plot</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-gray-700">Min Price</label>
                                <input type="number" name="min_price" placeholder="100,000" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-2 text-gray-700">Max Price</label>
                                <input type="number" name="max_price" placeholder="500,000" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-semibold shadow-lg text-lg">
                            🔍 Search Properties
                        </button>
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
                <p class="text-xl text-gray-600">Discover premium properties handpicked for you</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition group border border-gray-100">
                    <div class="relative overflow-hidden h-64">
                        <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?w=600&q=80" alt="Modern African Home" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute top-4 right-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-3 py-1 rounded-full text-sm font-semibold shadow-lg">⭐ Featured</div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Modern 3BR House</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            Area 47, Lilongwe
                        </p>
                        <div class="flex items-center justify-between mb-4 pb-4 border-b">
                            <span class="text-3xl font-bold text-blue-600">MWK 350,000</span>
                            <span class="text-gray-500 text-sm">/month</span>
                        </div>
                        <div class="grid grid-cols-3 gap-4 text-center mb-4 text-sm">
                            <div><div class="text-2xl">🛏️</div><div class="font-semibold">3 Beds</div></div>
                            <div><div class="text-2xl">🚿</div><div class="font-semibold">2 Baths</div></div>
                            <div><div class="text-2xl">📐</div><div class="font-semibold">120m²</div></div>
                        </div>
                        <a href="/properties/1" class="block w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-center py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-semibold shadow-lg">View Details →</a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition group border border-gray-100">
                    <div class="relative overflow-hidden h-64">
                        <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=600&q=80" alt="Commercial Property" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Prime Office Space</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            City Centre, Blantyre
                        </p>
                        <div class="flex items-center justify-between mb-4 pb-4 border-b">
                            <span class="text-3xl font-bold text-blue-600">MWK 800,000</span>
                            <span class="text-gray-500 text-sm">/month</span>
                        </div>
                        <div class="grid grid-cols-3 gap-4 text-center mb-4 text-sm">
                            <div><div class="text-2xl">🏢</div><div class="font-semibold">Office</div></div>
                            <div><div class="text-2xl">🚿</div><div class="font-semibold">2 Baths</div></div>
                            <div><div class="text-2xl">📐</div><div class="font-semibold">180m²</div></div>
                        </div>
                        <a href="/properties/2" class="block w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-center py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-semibold shadow-lg">View Details →</a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition group border border-gray-100">
                    <div class="relative overflow-hidden h-64">
                        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=600&q=80" alt="Land Plot" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    </div>
                    <div class="p-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Residential Plot</h3>
                        <p class="text-gray-600 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            Area 49, Lilongwe
                        </p>
                        <div class="flex items-center justify-between mb-4 pb-4 border-b">
                            <span class="text-3xl font-bold text-blue-600">MWK 15M</span>
                            <span class="text-gray-500 text-sm">one-time</span>
                        </div>
                        <div class="grid grid-cols-3 gap-4 text-center mb-4 text-sm">
                            <div><div class="text-2xl">🏞️</div><div class="font-semibold">Land</div></div>
                            <div><div class="text-2xl">📋</div><div class="font-semibold">Title Deed</div></div>
                            <div><div class="text-2xl">📐</div><div class="font-semibold">2,023m²</div></div>
                        </div>
                        <a href="/properties/3" class="block w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-center py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-semibold shadow-lg">View Details →</a>
                    </div>
                </div>
            </div>

            <div class="text-center mt-12">
                <a href="/properties" class="inline-flex items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-semibold text-lg shadow-lg">
                    View All Properties →
                </a>
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="py-20 bg-gradient-to-br from-blue-50 to-indigo-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Why Choose Khomolanu?</h2>
                <p class="text-xl text-gray-600">The most trusted property platform in Malawi</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Verified Properties</h3>
                    <p class="text-gray-600">All properties and landlords are verified by our team to ensure safety and authenticity.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Small Fee Amount</h3>
                    <p class="text-gray-600">Browse and connect with landlords with a small fee. No need to pay large sums to connect with landlords.</p>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg hover:shadow-xl transition">
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Quick & Easy</h3>
                    <p class="text-gray-600">Find your perfect property in minutes. Contact landlords directly via WhatsApp.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
                <p class="text-xl text-gray-600">Find your perfect property in 3 simple steps</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-600 text-white rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-xl">1</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Search Properties</h3>
                    <p class="text-gray-600 text-lg">Browse thousands of verified properties with advanced filters for location, price, and type.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-indigo-600 to-purple-600 text-white rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-xl">2</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Contact Landlord</h3>
                    <p class="text-gray-600 text-lg">Send inquiries and connect directly with verified landlords via WhatsApp or phone.</p>
                </div>

                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-600 to-pink-600 text-white rounded-full flex items-center justify-center text-3xl font-bold mx-auto mb-6 shadow-xl">3</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Move In</h3>
                    <p class="text-gray-600 text-lg">Visit the property, complete the deal, and move into your dream home.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="relative py-20 overflow-hidden">
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1560184897-ae75f418493e?w=1920&q=80" alt="Happy African Family" class="w-full h-full object-cover opacity-20">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
        </div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <h2 class="text-4xl md:text-5xl font-bold mb-6 text-white">Ready to List Your Property?</h2>
            <p class="text-xl mb-8 text-blue-100">Join hundreds of verified landlords on Khomolanu</p>
            <a href="/register" class="inline-block bg-white text-blue-600 px-8 py-4 rounded-lg hover:bg-gray-100 transition font-bold text-lg shadow-2xl">Get Started Free →</a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Khomolanu</h3>
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
                    <p class="text-gray-400">Email: info@khomolanu.com</p>
                    <p class="text-gray-400">Phone: +265 888 000 000</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2025 Khomolanu Malawi. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>