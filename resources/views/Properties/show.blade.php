{{-- resources/views/properties/show.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details - NyumbaLink Malawi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50" x-data="propertyDetail()">
    
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
                <div class="flex items-center space-x-4">
                    <a href="/properties" class="text-gray-700 hover:text-blue-600">← Back to Properties</a>
                </div>
            </div>
        </div>
    </nav>

    {{-- Loading State --}}
    <div x-show="loading" class="flex items-center justify-center min-h-screen">
        <div class="text-center">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            <p class="mt-4 text-gray-600">Loading property details...</p>
        </div>
    </div>

    {{-- Property Content --}}
    <div x-show="!loading && property" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        {{-- Back Button & Share --}}
        <div class="flex justify-between items-center mb-6">
            <a href="/properties" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center">
                ← Back to Search
            </a>
            <button class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Left Column: Images & Details --}}
            <div class="lg:col-span-2">
                
                {{-- Image Gallery --}}
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
                    {{-- Main Image --}}
                    <div class="relative h-96 bg-gray-200">
                        <img 
                            :src="currentImage || 'https://via.placeholder.com/800x600?text=No+Image'" 
                            alt="Property"
                            class="w-full h-full object-cover"
                        >
                        {{-- Featured Badge --}}
                        <div x-show="property.is_boosted" class="absolute top-4 right-4 bg-yellow-500 text-white px-4 py-2 rounded-full font-semibold flex items-center shadow-lg">
                            ⭐ Featured Property
                        </div>
                    </div>

                    {{-- Thumbnail Gallery --}}
                    <div class="p-4 grid grid-cols-4 gap-4" x-show="property.formatted_images && property.formatted_images.length > 0">
                        <template x-for="(image, index) in property.formatted_images" :key="index">
                            <div 
                                @click="currentImage = image.url"
                                :class="currentImage === image.url ? 'ring-2 ring-blue-600' : ''"
                                class="cursor-pointer rounded-lg overflow-hidden hover:opacity-75 transition"
                            >
                                <img :src="image.url" alt="Thumbnail" class="w-full h-20 object-cover">
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Property Details --}}
                <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                    <h1 class="text-4xl font-bold text-gray-900 mb-4" x-text="property.title"></h1>
                    
                    {{-- Location --}}
                    <div class="flex items-center text-gray-600 mb-6">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-lg" x-text="`${property.area || property.district}, ${property.city}`"></span>
                    </div>

                    {{-- Price --}}
                    <div class="mb-8">
                        <div class="flex items-baseline">
                            <span class="text-5xl font-bold text-blue-600">
                                MWK <span x-text="Number(property.price).toLocaleString()"></span>
                            </span>
                            <span class="text-xl text-gray-600 ml-2" x-text="property.property_type === 'land' ? 'one-time' : '/month'"></span>
                        </div>
                    </div>

                    {{-- Features Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8 pb-8 border-b">
                        <div x-show="property.bedrooms">
                            <div class="text-3xl mb-2">🛏️</div>
                            <div class="font-semibold" x-text="property.bedrooms + ' Bedrooms'"></div>
                        </div>
                        <div x-show="property.bathrooms">
                            <div class="text-3xl mb-2">🚿</div>
                            <div class="font-semibold" x-text="property.bathrooms + ' Bathrooms'"></div>
                        </div>
                        <div x-show="property.size_sqm">
                            <div class="text-3xl mb-2">📐</div>
                            <div class="font-semibold" x-text="property.size_sqm + ' m²'"></div>
                        </div>
                        <div>
                            <div class="text-3xl mb-2" x-text="property.is_furnished ? '🛋️' : '📦'"></div>
                            <div class="font-semibold" x-text="property.is_furnished ? 'Furnished' : 'Unfurnished'"></div>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="mb-8">
                        <h2 class="text-2xl font-bold mb-4">Description</h2>
                        <p class="text-gray-700 leading-relaxed" x-text="property.description"></p>
                    </div>

                    {{-- Amenities --}}
                    <div x-show="property.amenities && property.amenities.length > 0" class="mb-8">
                        <h2 class="text-2xl font-bold mb-4">Amenities</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <template x-for="amenity in property.amenities" :key="amenity.id">
                                <div class="flex items-center space-x-2 bg-gray-50 px-4 py-3 rounded-lg">
                                    <span class="text-green-600">✓</span>
                                    <span x-text="amenity.amenity"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Property Stats --}}
                    <div class="grid grid-cols-3 gap-4 p-6 bg-gray-50 rounded-xl">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600" x-text="property.total_views || 0"></div>
                            <div class="text-sm text-gray-600">Views</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600" x-text="property.total_inquiries || 0"></div>
                            <div class="text-sm text-gray-600">Inquiries</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-blue-600" x-text="property.landlord_rating || 'N/A'"></div>
                            <div class="text-sm text-gray-600">Landlord Rating</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Contact Card --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-lg p-8 sticky top-24">
                    
                    {{-- Landlord Info --}}
                    <div class="mb-6 pb-6 border-b">
                        <h3 class="text-xl font-bold mb-4">Listed By</h3>
                        <div class="flex items-center space-x-4 mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                                <span x-text="property.landlord_name ? property.landlord_name[0] : 'L'"></span>
                            </div>
                            <div>
                                <div class="font-bold text-lg" x-text="property.landlord_name"></div>
                                <div class="flex items-center text-yellow-500">
                                    <span x-text="'⭐ ' + (property.landlord_rating || 'New')"></span>
                                    <span x-show="property.landlord_total_ratings" class="text-gray-600 text-sm ml-1">
                                        (<span x-text="property.landlord_total_ratings"></span> reviews)
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div x-show="property.landlord_verified" class="flex items-center text-green-600 text-sm">
                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Verified Landlord
                        </div>
                    </div>

                    {{-- Contact Options --}}
                    <div class="space-y-4">
                        {{-- WhatsApp --}}
                        <a 
                            :href="`https://wa.me/${property.landlord_phone?.replace(/[^0-9]/g, '')}`"
                            target="_blank"
                            class="flex items-center justify-center w-full bg-green-600 text-white py-4 rounded-lg hover:bg-green-700 transition font-semibold shadow-lg"
                        >
                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                            </svg>
                            Contact via WhatsApp
                        </a>

                        {{-- Send Inquiry --}}
                        <button 
                            @click="showInquiryModal = true"
                            class="flex items-center justify-center w-full bg-blue-600 text-white py-4 rounded-lg hover:bg-blue-700 transition font-semibold shadow-lg"
                        >
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Send Inquiry
                        </button>

                        {{-- Call --}}
                        <a 
                            :href="`tel:${property.landlord_phone}`"
                            class="flex items-center justify-center w-full bg-gray-600 text-white py-4 rounded-lg hover:bg-gray-700 transition font-semibold shadow-lg"
                        >
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            Call Now
                        </a>
                    </div>

                    {{-- Safety Tips --}}
                    <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-sm text-yellow-800">
                                <p class="font-semibold mb-1">Safety Tips:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Meet in person before paying</li>
                                    <li>Never send money in advance</li>
                                    <li>Verify property ownership</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Inquiry Modal --}}
    <div 
        x-show="showInquiryModal" 
        x-cloak
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
        @click.self="showInquiryModal = false"
    >
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8" @click.stop>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold">Send Inquiry</h3>
                <button @click="showInquiryModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form @submit.prevent="sendInquiry()">
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Your Message</label>
                    <textarea 
                        x-model="inquiryMessage"
                        rows="4"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Hello, I'm interested in this property..."
                    ></textarea>
                </div>

                <div x-show="inquiryError" x-text="inquiryError" class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm"></div>
                <div x-show="inquirySuccess" x-text="inquirySuccess" class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm"></div>

                <div class="flex space-x-4">
                    <button 
                        type="button"
                        @click="showInquiryModal = false"
                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        :disabled="sendingInquiry"
                        class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                    >
                        <span x-show="!sendingInquiry">Send</span>
                        <span x-show="sendingInquiry">Sending...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function propertyDetail() {
            return {
                property: null,
                loading: true,
                currentImage: null,
                showInquiryModal: false,
                inquiryMessage: '',
                sendingInquiry: false,
                inquiryError: '',
                inquirySuccess: '',

                async init() {
                    const propertyId = window.location.pathname.split('/').pop();
                    await this.loadProperty(propertyId);
                    this.incrementView(propertyId);
                },

                async loadProperty(id) {
                    try {
                        const response = await fetch(`/api/v1/properties/${id}`, {
                            headers: { 'Accept': 'application/json' }
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.property = data.data;
                            this.currentImage = this.property.primary_image_url || 
                                (this.property.formatted_images && this.property.formatted_images[0]?.url);
                        }
                    } catch (error) {
                        console.error('Error loading property:', error);
                    } finally {
                        this.loading = false;
                    }
                },

                async incrementView(id) {
                    try {
                        await fetch(`/api/v1/properties/${id}/view`, {
                            method: 'POST',
                            headers: { 'Accept': 'application/json' }
                        });
                    } catch (error) {
                        console.error('Error incrementing view:', error);
                    }
                },

                async sendInquiry() {
                    const token = localStorage.getItem('auth_token');
                    
                    if (!token) {
                        this.inquiryError = 'Please login to send an inquiry';
                        setTimeout(() => {
                            window.location.href = '/login';
                        }, 2000);
                        return;
                    }

                    this.sendingInquiry = true;
                    this.inquiryError = '';
                    this.inquirySuccess = '';

                    try {
                        const response = await fetch('/api/v1/tenant/inquiries', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'Authorization': `Bearer ${token}`
                            },
                            body: JSON.stringify({
                                property_id: this.property.id,
                                message: this.inquiryMessage
                            })
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.inquirySuccess = 'Inquiry sent successfully! You can contact the landlord via WhatsApp.';
                            this.inquiryMessage = '';
                            setTimeout(() => {
                                this.showInquiryModal = false;
                                this.inquirySuccess = '';
                            }, 3000);
                        } else {
                            this.inquiryError = data.message || 'Failed to send inquiry';
                        }
                    } catch (error) {
                        this.inquiryError = 'An error occurred. Please try again.';
                    } finally {
                        this.sendingInquiry = false;
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>