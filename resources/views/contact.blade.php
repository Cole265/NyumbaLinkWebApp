{{-- resources/views/contact.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Khomolanu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col">
        @include('partials.nav')

        <main class="flex-1">
            {{-- Hero with warm office / support imagery --}}
            <section class="relative overflow-hidden border-b">
                <div class="absolute inset-0">
                    <img
                        src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=1600&q=80&auto=format&fit=crop"
                        alt="Customer support team in modern office"
                        class="w-full h-full object-cover opacity-35">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 via-indigo-900/85 to-slate-900/80"></div>
                </div>
                <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center space-y-4">
                    <p class="text-sm font-semibold tracking-wide text-blue-200 uppercase"></p>
                    <h1 class="text-4xl md:text-5xl font-bold text-white">
                        Talk to the Khomolanu team
                    </h1>
                    <p class="text-lg text-blue-100 max-w-2xl mx-auto">
                        Whether you are closing a property deal, verifying your landlord account or searching for
                        a new home, we are here to help.
                    </p>
                </div>
            </section>

            <section class="py-16 bg-gray-50">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-10">
                    <div class="space-y-4">
                        <h2 class="text-2xl font-semibold mb-2">Get in touch</h2>
                        <p class="text-gray-600 text-sm">
                            We typically respond within 24–48 hours on business days.
                        </p>
                        <div class="space-y-2 text-sm text-gray-700">
                            <p><span class="font-semibold">Email:</span> info@khomolanu.com</p>
                            <p><span class="font-semibold">Phone:</span> +265 888 000 000</p>
                            <p><span class="font-semibold">WhatsApp:</span> +265 888 000 000</p>
                            <p><span class="font-semibold">Location:</span> Lilongwe, Malawi</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 transform transition duration-300 hover:-translate-y-1 hover:shadow-2xl">
                        <h3 class="text-lg font-semibold mb-2">Send us a message</h3>
                        <p class="text-xs text-gray-500 mb-4">Share a few details and we’ll get back to you as soon as possible.</p>

                        @if (session('status'))
                            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-xs text-green-800">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs text-red-800">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="space-y-4" method="POST" action="{{ route('contact.submit') }}">
                            @csrf
                            <div class="space-y-1">
                                <label class="block text-xs font-semibold tracking-wide text-gray-600 uppercase">Your Name</label>
                                <div class="relative group">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 group-focus-within:text-blue-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A7 7 0 1118 10m-6 10a9 9 0 100-18 9 9 0 000 18z" />
                                        </svg>
                                    </span>
                                    <input
                                        type="text"
                                        name="name"
                                        class="w-full pl-9 pr-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition-all duration-200"
                                        placeholder="Full name"
                                        value="{{ old('name') }}">
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-semibold tracking-wide text-gray-600 uppercase">Email</label>
                                <div class="relative group">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400 group-focus-within:text-blue-500 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12l-4 4-4-4m8-4l-4 4-4-4" />
                                        </svg>
                                    </span>
                                    <input
                                        type="email"
                                        name="email"
                                        class="w-full pl-9 pr-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition-all duration-200"
                                        placeholder="you@example.com"
                                        value="{{ old('email') }}">
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="block text-xs font-semibold tracking-wide text-gray-600 uppercase">Message</label>
                                <textarea
                                    name="message"
                                    rows="4"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-300 rounded-lg bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none transition-all duration-200 resize-none"
                                    placeholder="How can we help?">{{ old('message') }}</textarea>
                            </div>
                            <button
                            type="submit"
                                class="w-full inline-flex justify-center items-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-2.5 rounded-lg font-semibold text-sm shadow-md hover:from-blue-700 hover:to-indigo-700 hover:shadow-lg focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 focus:outline-none transition-all duration-150">
                                <span>Submit</span>
                            </button>
                        </form>
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

