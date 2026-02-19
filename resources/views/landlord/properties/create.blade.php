{{-- resources/views/landlord/properties/create.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List Your Property - Khomolanu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50" x-data="propertyForm()">
    
    {{-- Navigation --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-lg flex items-center justify-center">
                            <span class="text-white font-bold text-xl">KL</span>
                        </div>
                        <span class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                            Khomolanu
                        </span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/landlord/dashboard" class="text-gray-700 hover:text-blue-600 transition">Dashboard</a>
                    <a href="/logout" class="text-gray-700 hover:text-blue-600 transition">Logout</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">List Your Property</h1>
            <p class="text-lg text-gray-600">Fill in the details below to list your property on Khomolanu</p>
        </div>

        {{-- Form --}}
        <form @submit.prevent="submitForm($event)" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-lg p-8">
            @csrf

            {{-- Progress Indicator --}}
            <div class="mb-8">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium" :class="currentStep >= 1 ? 'text-blue-600' : 'text-gray-500'">Basic Info</span>
                    <span class="text-sm font-medium" :class="currentStep >= 2 ? 'text-blue-600' : 'text-gray-500'">Details</span>
                    <span class="text-sm font-medium" :class="currentStep >= 3 ? 'text-blue-600' : 'text-gray-500'">Location</span>
                    <span class="text-sm font-medium" :class="currentStep >= 4 ? 'text-blue-600' : 'text-gray-500'">Images</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" :style="`width: ${(currentStep / 4) * 100}%`"></div>
                </div>
            </div>

            {{-- Step 1: Basic Information --}}
            <div x-show="currentStep === 1" x-cloak>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Basic Information</h2>

                {{-- Property Type --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Property Type *</label>
                    <div class="grid grid-cols-3 gap-4">
                        <label class="relative">
                            <input type="radio" name="property_type" value="residential" x-model="formData.property_type" class="peer sr-only" required>
                            <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 transition hover:border-blue-400">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-600 peer-checked:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                    </svg>
                                    <span class="font-semibold">Residential</span>
                                </div>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="property_type" value="commercial" x-model="formData.property_type" class="peer sr-only">
                            <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 transition hover:border-blue-400">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span class="font-semibold">Commercial</span>
                                </div>
                            </div>
                        </label>

                        <label class="relative">
                            <input type="radio" name="property_type" value="land" x-model="formData.property_type" class="peer sr-only">
                            <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:bg-blue-50 transition hover:border-blue-400">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                                    </svg>
                                    <span class="font-semibold">Land/Plot</span>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>

                {{-- Title --}}
                <div class="mb-6">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Property Title *</label>
                    <input type="text" id="title" name="title" x-model="formData.title" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Modern 3 Bedroom House in Area 47">
                </div>

                {{-- Description --}}
                <div class="mb-6">
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                    <textarea id="description" name="description" x-model="formData.description" required rows="5"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Describe your property in detail..."></textarea>
                    <p class="text-sm text-gray-500 mt-1">Be detailed about features, amenities, and what makes your property special</p>
                </div>

                <div class="flex justify-end">
                    <button type="button" @click="currentStep = 2" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        Next Step →
                    </button>
                </div>
            </div>

            {{-- Step 2: Property Details --}}
            <div x-show="currentStep === 2" x-cloak>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Property Details</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- Price --}}
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Price *</label>
                        <input type="number" id="price" name="price" x-model="formData.price" required step="0.01"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="350000">
                    </div>

                    {{-- Currency --}}
                    <div>
                        <label for="currency" class="block text-sm font-semibold text-gray-700 mb-2">Currency *</label>
                        <select id="currency" name="currency" x-model="formData.currency"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="MWK">MWK (Malawi Kwacha)</option>
                            <option value="USD">USD (US Dollar)</option>
                        </select>
                    </div>
                </div>

                {{-- Bedrooms & Bathrooms (only for residential/commercial) --}}
                <div x-show="formData.property_type !== 'land'" class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="bedrooms" class="block text-sm font-semibold text-gray-700 mb-2">Bedrooms</label>
                        <input type="number" id="bedrooms" name="bedrooms" x-model="formData.bedrooms" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="3">
                    </div>

                    <div>
                        <label for="bathrooms" class="block text-sm font-semibold text-gray-700 mb-2">Bathrooms</label>
                        <input type="number" id="bathrooms" name="bathrooms" x-model="formData.bathrooms" min="0"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="2">
                    </div>
                </div>

                {{-- Size --}}
                <div class="mb-6">
                    <label for="size_sqm" class="block text-sm font-semibold text-gray-700 mb-2">Size (Square Meters)</label>
                    <input type="number" id="size_sqm" name="size_sqm" x-model="formData.size_sqm" step="0.01"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="120">
                </div>

                {{-- Furnished (only for residential/commercial) --}}
                <div x-show="formData.property_type !== 'land'" class="mb-6">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_furnished" value="1" x-model="formData.is_furnished" class="w-5 h-5 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                        <span class="text-sm font-semibold text-gray-700">Property is Furnished</span>
                    </label>
                </div>

                {{-- Amenities --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Amenities (Select all that apply)</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="amenities[]" value="WiFi" class="w-4 h-4 text-blue-600 rounded">
                            <span class="text-sm">WiFi</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="amenities[]" value="Parking" class="w-4 h-4 text-blue-600 rounded">
                            <span class="text-sm">Parking</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="amenities[]" value="Security" class="w-4 h-4 text-blue-600 rounded">
                            <span class="text-sm">24/7 Security</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="amenities[]" value="Water" class="w-4 h-4 text-blue-600 rounded">
                            <span class="text-sm">Reliable Water</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="amenities[]" value="Generator" class="w-4 h-4 text-blue-600 rounded">
                            <span class="text-sm">Generator/Solar</span>
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="amenities[]" value="Garden" class="w-4 h-4 text-blue-600 rounded">
                            <span class="text-sm">Garden</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-between">
                    <button type="button" @click="currentStep = 1" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                        ← Previous
                    </button>
                    <button type="button" @click="currentStep = 3" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        Next Step →
                    </button>
                </div>
            </div>

            {{-- Step 3: Location --}}
            <div x-show="currentStep === 3" x-cloak>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Location Details</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {{-- City --}}
                    <div>
                        <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">City *</label>
                        <select id="city" name="city" x-model="formData.city" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select City</option>
                            <option value="Lilongwe">Lilongwe</option>
                            <option value="Blantyre">Blantyre</option>
                            <option value="Mzuzu">Mzuzu</option>
                            <option value="Zomba">Zomba</option>
                            <option value="Mangochi">Mangochi</option>
                            <option value="Karonga">Karonga</option>
                            <option value="Kasungu">Kasungu</option>
                            <option value="Salima">Salima</option>
                        </select>
                    </div>

                    {{-- District --}}
                    <div>
                        <label for="district" class="block text-sm font-semibold text-gray-700 mb-2">District *</label>
                        <input type="text" id="district" name="district" x-model="formData.district" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., Lilongwe">
                    </div>
                </div>

                {{-- Area --}}
                <div class="mb-6">
                    <label for="area" class="block text-sm font-semibold text-gray-700 mb-2">Area/Neighborhood</label>
                    <input type="text" id="area" name="area" x-model="formData.area"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="e.g., Area 47, Old Town, City Centre">
                </div>

                {{-- GPS Coordinates --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">GPS Coordinates (Optional)</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" name="latitude" x-model="formData.latitude"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Latitude (e.g., -13.9626)">
                        <input type="text" name="longitude" x-model="formData.longitude"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Longitude (e.g., 33.7741)">
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Adding GPS coordinates helps tenants find your property easily</p>
                </div>

                <div class="flex justify-between">
                    <button type="button" @click="currentStep = 2" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                        ← Previous
                    </button>
                    <button type="button" @click="currentStep = 4" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                        Next Step →
                    </button>
                </div>
            </div>

            {{-- Step 4: Images --}}
            <div x-show="currentStep === 4" x-cloak>
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Property Images</h2>

                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Upload Images *</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-500 transition">
                        <input type="file" name="images[]" multiple accept="image/*" @change="handleFileSelect" 
                               class="hidden" id="imageUpload">
                        <label for="imageUpload" class="cursor-pointer">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-lg font-semibold text-gray-700 mb-2">Click to upload images</p>
                            <p class="text-sm text-gray-500">or drag and drop</p>
                            <p class="text-xs text-gray-400 mt-2">PNG, JPG up to 10MB each (Max 10 images)</p>
                        </label>
                    </div>

                    <div x-show="selectedFiles.length > 0" class="mt-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Selected Images (<span x-text="selectedFiles.length"></span>)</p>
                        <div class="grid grid-cols-3 gap-4">
                            <template x-for="(file, index) in selectedFiles" :key="index">
                                <div class="relative">
                                    <img :src="file.preview" class="w-full h-32 object-cover rounded-lg">
                                    <button type="button" @click="removeFile(index)" 
                                            class="absolute top-2 right-2 bg-red-600 text-white rounded-full p-1 hover:bg-red-700">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="mb-6">
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Listing Status *</label>
                    <select id="status" name="status" x-model="formData.status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="draft">Save as Draft</option>
                        <option value="pending_review">Submit for Review</option>
                    </select>
                    <p class="text-sm text-gray-500 mt-1">Submit for review to publish your property after admin approval</p>
                </div>

                <div class="flex justify-between">
                    <button type="button" @click="currentStep = 3" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                        ← Previous
                    </button>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 transition font-semibold shadow-lg">
                        🏠 List Property
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white py-8 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-400">&copy; 2025 Khomolanu Malawi. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function propertyForm() {
            return {
                currentStep: 1,
                selectedFiles: [],
                formData: {
                    property_type: 'residential',
                    title: '',
                    description: '',
                    price: '',
                    currency: 'MWK',
                    bedrooms: '',
                    bathrooms: '',
                    size_sqm: '',
                    is_furnished: false,
                    city: '',
                    district: '',
                    area: '',
                    latitude: '',
                    longitude: '',
                    status: 'pending_review'
                },
                handleFileSelect(event) {
                    const files = Array.from(event.target.files);
                    if (files.length > 10) {
                        alert('Maximum 10 images allowed');
                        return;
                    }
                    
                    this.selectedFiles = [];
                    const maxSizeBytes = 5 * 1024 * 1024; // 5MB to match backend validation

                    files.forEach(file => {
                        if (file.size > maxSizeBytes) {
                            alert(`${file.name} is too large. Maximum 5MB per image.`);
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.selectedFiles.push({
                                file: file,
                                preview: e.target.result
                            });
                        };
                        reader.readAsDataURL(file);
                    });
                },
                removeFile(index) {
                    this.selectedFiles.splice(index, 1);
                },
                async submitForm(event) {
                    console.log('🏠 Starting property form submission');
                    
                    const token = localStorage.getItem('auth_token');
                    if (!token) {
                        alert('Please login as a landlord to list a property.');
                        window.location.href = '/login';
                        return;
                    }
                    console.log('✓ Auth token found');

                    const formElement = event.target;
                    const formData = new FormData(formElement);

                    // Ensure boolean field matches Laravel's expected true/false
                    formData.set('is_furnished', this.formData.is_furnished ? '1' : '0');
                    
                    // Log form data for debugging
                    console.log('Form data being sent:');
                    for (let [key, value] of formData.entries()) {
                        console.log(`  ${key}:`, value);
                    }

                    try {
                        console.log('📤 Sending request to /api/v1/landlord/properties');
                        const response = await fetch('/api/v1/landlord/properties', {
                            method: 'POST',
                            headers: {
                                'Authorization': `Bearer ${token}`,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        console.log('📥 Response received with status:', response.status);
                        
                        let data;
                        const text = await response.text();
                        try {
                            data = text ? JSON.parse(text) : {};
                        } catch (e) {
                            console.error('❌ Non-JSON response:', text);
                            data = {};
                        }

                        console.log('Response data:', data);

                        if (response.ok && data.success) {
                            console.log('✅ Property created successfully!');
                            window.location.href = '/landlord/dashboard';
                        } else {
                            const message = data.message 
                                || (data.errors ? Object.values(data.errors).flat().join('\n') : '')
                                || `Failed to create property (status ${response.status}). Please check your details and try again.`;
                            console.error('❌ Error:', message);
                            alert(message);
                        }
                    } catch (error) {
                        console.error('❌ Error creating property:', error);
                        alert('An error occurred while creating the property. Please try again.\n\nDetails: ' + error.message);
                    }
                }
            }
        }
    </script>

</body>
</html>