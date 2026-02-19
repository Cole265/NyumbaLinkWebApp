{{-- resources/views/about.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Khomolanu - Malawi's Property Marketplace</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        @include('partials.nav')

        <main class="flex-1">
            {{-- Hero --}}
            <section class="relative overflow-hidden">
                <div class="absolute inset-0">
                    <img
                        src="https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=1600&q=80&auto=format&fit=crop"
                        alt="Modern Malawian-style home"
                        class="w-full h-full object-cover opacity-40">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 via-indigo-900/80 to-slate-900/80"></div>
                </div>
                <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center">
                    <div>
                        <p class="text-sm font-semibold tracking-wide text-blue-200 uppercase mb-3"></p>
                        <h1 class="text-4xl md:text-5xl font-bold mb-6 text-white leading-tight">
                            Powering smarter real estate deals in Malawi
                        </h1>
                        <p class="text-lg text-blue-100 mb-6">
                            Khomolanu brings verified landlords and serious tenants together, combining local
                            market knowledge with modern technology and beautiful listings.
                        </p>
                        <div class="flex flex-wrap gap-4 justify-center text-sm text-blue-100">
                            <span class="inline-flex items-center space-x-2 bg-white/10 px-3 py-1 rounded-full">
                                <span>🏠</span>
                                <span>Verified rental & sale listings</span>
                            </span>
                            <span class="inline-flex items-center space-x-2 bg-white/10 px-3 py-1 rounded-full">
                                <span>🤝</span>
                                <span>Transparent landlord–tenant relationships</span>
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-16 bg-gray-50">
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
                    {{-- Mission block --}}
                    <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                        <h2 class="text-2xl font-semibold mb-3">Our Mission</h2>
                        <p class="text-gray-600 text-base leading-relaxed">
                            To become Malawi's most trusted property platform by verifying landlords,
                            increasing transparency, and reducing the cost and stress of house-hunting.
                        </p>
                    </div>

                    {{-- Two-column story --}}
                    <div class="grid md:grid-cols-2 gap-8 items-start">
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <h2 class="text-xl font-semibold mb-3">Who We Serve</h2>
                            <ul class="space-y-2 text-gray-600 text-sm">
                                <li>• Tenants looking for verified homes, plots, and commercial spaces</li>
                                <li>• Landlords who want serious, high-intent tenants without paying agents</li>
                                <li>• Property managers who need a digital platform for their listings</li>
                            </ul>
                        </div>
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <h2 class="text-xl font-semibold mb-3">How Khomolanu helps</h2>
                            <p class="text-gray-600 text-sm mb-3">
                                We sit in the middle of every rental conversation: verifying landlords, showcasing
                                quality listings, and giving tenants the tools they need to make confident decisions.
                            </p>
                            <p class="text-gray-600 text-sm">
                                From first search to final agreement, Khomolanu is designed to make the process
                                faster, clearer and more affordable for everyone involved.
                            </p>
                        </div>
                    </div>

                    {{-- Pillars --}}
                    <div class="grid md:grid-cols-3 gap-6">
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <p class="text-xs font-semibold text-blue-600 tracking-wide uppercase mb-2">Trust</p>
                            <h3 class="font-semibold text-lg mb-2">Verified Landlords</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Landlords go through a verification process so that tenants can make decisions with confidence.
                            </p>
                        </div>
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <p class="text-xs font-semibold text-blue-600 tracking-wide uppercase mb-2">Transparency</p>
                            <h3 class="font-semibold text-lg mb-2">Honest Ratings</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Tenants can rate their experience, helping build a more transparent and accountable rental ecosystem.
                            </p>
                        </div>
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <p class="text-xs font-semibold text-blue-600 tracking-wide uppercase mb-2">Local First</p>
                            <h3 class="font-semibold text-lg mb-2">Built for Malawi</h3>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                Designed around local cities, neighbourhoods, pricing, and payment methods like mobile money.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="bg-gray-900 text-white py-8 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0">
                <p class="text-gray-400 text-sm">&copy; {{ date('Y') }} Khomolanu Malawi. All rights reserved.</p>
                <div class="flex space-x-4 text-sm">
                    <a href="/about" class="text-gray-400 hover:text-white">About Us</a>
                    <a href="/pricing" class="text-gray-400 hover:text-white">Pricing</a>
                    <a href="/contact" class="text-gray-400 hover:text-white">Contact</a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>

