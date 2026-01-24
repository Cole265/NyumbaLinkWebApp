{{-- resources/views/auth/register.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - NyumbaLink Malawi</title>
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
                    <a href="/login" class="text-gray-700 hover:text-blue-600">Already have an account?</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Register Form --}}
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full">
            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Create Your Account</h2>
                    <p class="text-gray-600">Join NyumbaLink today</p>
                </div>

                <div x-data="registerForm()">
                    {{-- Role Selection --}}
                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-4">I am a:</label>
                        <div class="grid grid-cols-2 gap-4">
                            {{-- Landlord --}}
                            <button 
                                @click="form.role = 'landlord'"
                                :class="form.role === 'landlord' ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                                class="p-6 border-2 rounded-xl text-center hover:border-blue-600 transition"
                            >
                                <div class="text-4xl mb-2">🏠</div>
                                <div class="font-bold text-lg mb-1">Landlord</div>
                                <div class="text-sm text-gray-600">I want to list properties</div>
                            </button>

                            {{-- Tenant --}}
                            <button 
                                @click="form.role = 'tenant'"
                                :class="form.role === 'tenant' ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                                class="p-6 border-2 rounded-xl text-center hover:border-blue-600 transition"
                            >
                                <div class="text-4xl mb-2">🔍</div>
                                <div class="font-bold text-lg mb-1">Tenant</div>
                                <div class="text-sm text-gray-600">I'm looking for a property</div>
                            </button>
                        </div>
                    </div>

                    {{-- Form --}}
                    <form @submit.prevent="register()">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- Name --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                                <input 
                                    type="text" 
                                    x-model="form.name"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="John Banda"
                                >
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                <input 
                                    type="tel" 
                                    x-model="form.phone"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="+265 888 000 000"
                                >
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                            <input 
                                type="email" 
                                x-model="form.email"
                                required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="john@example.com"
                            >
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            {{-- Password --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                                <input 
                                    type="password" 
                                    x-model="form.password"
                                    required
                                    minlength="8"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Min. 8 characters"
                                >
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm Password</label>
                                <input 
                                    type="password" 
                                    x-model="form.password_confirmation"
                                    required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Re-enter password"
                                >
                            </div>
                        </div>

                        {{-- Terms --}}
                        <div class="mb-6">
                            <label class="flex items-start">
                                <input type="checkbox" required class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
                                <span class="ml-2 text-sm text-gray-700">
                                    I agree to the <a href="/terms" class="text-blue-600 hover:text-blue-700">Terms of Service</a> 
                                    and <a href="/privacy" class="text-blue-600 hover:text-blue-700">Privacy Policy</a>
                                </span>
                            </label>
                        </div>

                        {{-- Error Message --}}
                        <div x-show="errorMessage" x-text="errorMessage" class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg"></div>

                        {{-- Submit Button --}}
                        <button 
                            type="submit"
                            :disabled="loading || !form.role"
                            class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-semibold shadow-lg disabled:opacity-50"
                        >
                            <span x-show="!loading">Create Account</span>
                            <span x-show="loading">Creating Account...</span>
                        </button>
                    </form>

                    {{-- Login Link --}}
                    <div class="mt-6 text-center">
                        <span class="text-gray-600">Already have an account?</span>
                        <a href="/login" class="text-blue-600 hover:text-blue-700 font-semibold ml-1">Login</a>
                    </div>
                </div>
            </div>

            {{-- Quick Link --}}
            <div class="mt-6 text-center">
                <a href="/" class="text-gray-600 hover:text-blue-600">← Back to Home</a>
            </div>
        </div>
    </div>

    <script>
        function registerForm() {
            return {
                form: {
                    name: '',
                    email: '',
                    phone: '',
                    password: '',
                    password_confirmation: '',
                    role: 'tenant' // default
                },
                loading: false,
                errorMessage: '',

                async register() {
                    this.loading = true;
                    this.errorMessage = '';

                    try {
                        const response = await fetch('/api/v1/register', {
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
                            } else {
                                window.location.href = '/tenant/dashboard';
                            }
                        } else {
                            this.errorMessage = data.message || 'Registration failed';
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