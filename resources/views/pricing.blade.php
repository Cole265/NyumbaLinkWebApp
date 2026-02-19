{{-- resources/views/pricing.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pricing - Khomolanu Landlord Plans</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        @include('partials.nav')

        <main class="flex-1">
            {{-- Hero section with deal imagery --}}
            <section class="relative overflow-hidden border-b">
                <div class="absolute inset-0">
                    <img
                        src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=1600&q=80&auto=format&fit=crop"
                        alt="Real estate agent closing a property deal"
                        class="w-full h-full object-cover opacity-35">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 via-indigo-900/85 to-slate-900/80"></div>
                </div>
                <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20 grid md:grid-cols-2 gap-10 items-center">
                    <div>
                        <p class="text-sm font-semibold tracking-wide text-blue-200 uppercase mb-3"></p>
                        <h1 class="text-4xl md:text-5xl font-bold mb-4 text-white">
                            Fair, transparent pricing for every property
                        </h1>
                        <p class="text-lg text-blue-100 mb-6">
                            Pay a small, upfront fee to get your listing in front of high-intent tenants and buyers.
                            No surprise commissions, no middlemen.
                        </p>
                        <div class="flex flex-wrap gap-4 text-blue-100 text-sm">
                            <span class="inline-flex items-center space-x-2 bg-white/10 px-3 py-1 rounded-full">
                                <span>✅</span><span>No long-term lock-in</span>
                            </span>
                            <span class="inline-flex items-center space-x-2 bg-white/10 px-3 py-1 rounded-full">
                                <span>📊</span><span>Analytics on views & inquiries</span>
                            </span>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-white/10 border border-white/20 rounded-2xl p-6 backdrop-blur-md shadow-2xl">
                            <p class="text-sm text-blue-100 mb-4">Example deal</p>
                            <div class="flex items-center space-x-4 mb-4">
                                <img
                                    src="https://images.unsplash.com/photo-1568605114967-8130f3a36994?w=400&q=80&auto=format&fit=crop"
                                    alt="Listed family home"
                                    class="h-24 w-32 rounded-xl object-cover border border-white/30">
                                <div>
                                    <p class="text-white font-semibold">3BR Family Home · Area 47</p>
                                    <p class="text-blue-100 text-sm">Lilongwe · Residential listing</p>
                                    <p class="text-blue-100 text-sm mt-1">Listed with a small Khomolanu fee, found a tenant in days.</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-4 text-xs text-blue-100">
                                <div>
                                    <p class="font-semibold text-white">Listing Fee</p>
                                    <p>MWK 3,000</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-white">Boost Optional</p>
                                    <p>From MWK 3,000</p>
                                </div>
                                <div>
                                    <p class="font-semibold text-white">Commission</p>
                                    <p>0% from Khomolanu</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="py-16 bg-gray-50">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                            <h2 class="text-xl font-semibold mb-2">Residential Listing</h2>
                            <p class="text-gray-500 mb-4 text-sm">For houses, apartments and flats</p>
                            <div class="text-3xl font-bold text-blue-600 mb-1">MWK 3,000</div>
                            <p class="text-gray-500 text-sm mb-6">per listing / 30 days</p>
                            <ul class="space-y-2 text-sm text-gray-600 mb-6">
                                <li>✓ Listed on Khomolanu for 30 days</li>
                                <li>✓ Appear in city & area searches</li>
                                <li>✓ Receive tenant inquiries via WhatsApp</li>
                            </ul>
                            <a href="/register" class="block text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700">
                                List Residential Property
                            </a>
                        </div>

                        <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-blue-600 relative">
                            <div class="absolute -top-3 right-4 bg-blue-600 text-white text-xs px-3 py-1 rounded-full shadow">
                                Most Popular
                            </div>
                            <h2 class="text-xl font-semibold mb-2">Commercial Listing</h2>
                            <p class="text-gray-500 mb-4 text-sm">For offices, shops and warehouses</p>
                            <div class="text-3xl font-bold text-blue-600 mb-1">MWK 5,000</div>
                            <p class="text-gray-500 text-sm mb-6">per listing / 30 days</p>
                            <ul class="space-y-2 text-sm text-gray-600 mb-6">
                                <li>✓ High-intent business tenants</li>
                                <li>✓ Priority in commercial searches</li>
                                <li>✓ Full analytics on views & inquiries</li>
                            </ul>
                            <a href="/register" class="block text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700">
                                List Commercial Property
                            </a>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm p-8 border border-gray-100">
                            <h2 class="text-xl font-semibold mb-2">Land / Plot Listing</h2>
                            <p class="text-gray-500 mb-4 text-sm">For residential & commercial land</p>
                            <div class="text-3xl font-bold text-blue-600 mb-1">MWK 2,000</div>
                            <p class="text-gray-500 text-sm mb-6">per listing / 30 days</p>
                            <ul class="space-y-2 text-sm text-gray-600 mb-6">
                                <li>✓ Appears in land & investment searches</li>
                                <li>✓ Show title deed status and size</li>
                                <li>✓ Direct contact with serious buyers</li>
                            </ul>
                            <a href="/register" class="block text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700">
                                List Land / Plot
                            </a>
                        </div>
                    </div>

                    <div class="mt-12 grid md:grid-cols-3 gap-8">
                        <div class="relative bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-[1px] shadow-md md:col-span-1">
                            <div class="absolute -top-3 left-4 bg-blue-600 text-white text-xs px-3 py-1 rounded-full shadow">
                                For Tenants
                            </div>
                            <div class="bg-white rounded-2xl shadow-sm p-6 border border-blue-100">
                            <h3 class="text-xl font-semibold mb-2 text-blue-700">Tenant Access Fee</h3>
                            <p class="text-gray-600 text-sm mb-3">
                                For people looking for a property, there is a one-time fee to unlock full contact details.
                            </p>
                            <div class="text-3xl font-bold text-blue-600 mb-1">MWK 5,000</div>
                            <p class="text-gray-500 text-sm mb-4">per tenant account</p>
                            <ul class="space-y-1 text-sm text-gray-600 mb-4">
                                <li>• View landlord contact information</li>
                                <li>• Send unlimited inquiries to verified listings</li>
                                <li>• Contact landlords directly via WhatsApp and phone</li>
                            </ul>
                            <p class="text-xs text-gray-400">
                                This fee helps us verify listings, support the platform and keep landlord commissions at 0%.
                            </p>
                            </div>
                        </div>
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <h3 class="text-xl font-semibold mb-3">Boost Your Listing</h3>
                            <p class="text-gray-600 text-sm mb-2">
                                Want extra visibility? Khomolanu offers optional boost packages:
                            </p>
                            <ul class="space-y-1 text-sm text-gray-600">
                                <li>• 7-Day Boost — MWK 3,000 (top of search results)</li>
                                <li>• 14-Day Featured — MWK 5,000 (highlighted badge & top placement)</li>
                            </ul>
                        </div>
                        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                            <h3 class="text-xl font-semibold mb-3">Payments</h3>
                            <p class="text-gray-600 text-sm">
                                We support popular local payment methods including Airtel Money, TNM Mpamba, bank
                                transfer and cash (via our team). In production, these payments are processed securely
                                through integrated gateways.
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

