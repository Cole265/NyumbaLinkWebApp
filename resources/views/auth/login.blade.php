{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NyumbaLink Malawi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50">
    
    {{-- Navigation --}}
    <nav class="bg-white shadow-sm">
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
                <div class="flex items-center space-x-4">
                    <a href="/register" class="text-gray-700 hover:text-blue-600">Don't have an account?</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Login Form --}}
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h2>
                    <p class="text-gray-600">Login to access your account</p>
                </div>

                {{-- Form --}}
                <form action="/api/v1/login" method="POST" x-data="loginForm()">
                    @csrf
                    
                    {{-- Email --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input 
                            type="email" 
                            name="email" 
                            x-model="form.email"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="john@example.com"
                        >
                    </div>

                    {{-- Password --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            x-model="form.password"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter your password"
                        >
                    </div>

                    {{-- Remember Me & Forgot Password --}}
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Remember me</span>
                        </label>
                        <a href="/forgot-password" class="text-sm text-blue-600 hover:text-blue-700">Forgot password?</a>
                    </div>

                    {{-- Error Message --}}
                    <div x-show="errorMessage" x-text="errorMessage" class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg"></div>

                    {{-- Submit Button --}}
                    <button 
                        type="submit" 
                        @click.prevent="login()"
                        :disabled="loading"
                        class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-semibold shadow-lg disabled:opacity-50"
                    >
                        <span x-show="!loading">Login</span>
                        <span x-show="loading">Logging in...</span>
                    </button>
                </form>

                {{-- Divider --}}
                <div class="relative my-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">New to NyumbaLink?</span>
                    </div>
                </div>

                {{-- Register Link --}}
                <a href="/register" class="block w-full bg-gray-100 text-gray-900 text-center py-3 rounded-lg hover:bg-gray-200 transition font-semibold">
                    Create an Account
                </a>
            </div>

            {{-- Quick Links --}}
            <div class="mt-6 text-center">
                <a href="/" class="text-gray-600 hover:text-blue-600">← Back to Home</a>
            </div>
        </div>
    </div>

    <script>
        function loginForm() {
            return {
                form: {
                    email: '',
                    password: ''
                },
                loading: false,
                errorMessage: '',

                async login() {
                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        const response = await fetch('/api/v1/login', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(this.form)
                        });

                        const data = await response.json();

                        if (response.ok) {
                            // Store token
                            localStorage.setItem('auth_token', data.token);
                            localStorage.setItem('user', JSON.stringify(data.user));

                            // Redirect based on role
                            if (data.user.role === 'landlord') {
                                window.location.href = '/landlord/dashboard';
                            } else if (data.user.role === 'tenant') {
                                window.location.href = '/tenant/dashboard';
                            } else if (data.user.role === 'admin') {
                                window.location.href = '/admin/dashboard';
                            }
                        } else {
                            this.errorMessage = data.message || 'Invalid credentials';
                        }
                    } catch (error) {
                        this.errorMessage = 'An error occurred. Please try again.';
                    } finally {
                        this.loading = false;
                    }
                }
            }
        }
    </script>
</body>
</html>