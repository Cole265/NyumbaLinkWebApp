{{-- Property View Modal - for landlord and admin dashboards. Include with apiBase: 'landlord' or 'admin' --}}
<div x-show="showPropertyViewModal"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     aria-modal="true"
     role="dialog">
    <div class="flex min-h-full items-center justify-center p-4">
        {{-- Backdrop --}}
        <div x-show="showPropertyViewModal"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black/60"
             @click="closePropertyViewModal()"></div>

        {{-- Modal panel --}}
        <div x-show="showPropertyViewModal"
             x-transition:enter="ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col"
             @click.stop>

            {{-- Close button --}}
            <button type="button"
                    @click="closePropertyViewModal()"
                    class="absolute top-4 right-4 z-10 p-2 rounded-full bg-white/90 hover:bg-gray-100 text-gray-600 hover:text-gray-900 shadow">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            {{-- Loading --}}
            <div x-show="viewModalLoading" class="p-12 text-center">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent"></div>
                <p class="mt-4 text-gray-600">Loading property...</p>
            </div>

            {{-- Error --}}
            <div x-show="viewModalError && !viewModalLoading" class="p-8 text-center">
                <p class="text-red-600" x-text="viewModalError"></p>
                <button @click="closePropertyViewModal()" class="mt-4 px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">Close</button>
            </div>

            {{-- Content --}}
            <div x-show="viewModalProperty && !viewModalLoading && !viewModalError" class="flex-1 overflow-y-auto">
                {{-- Images --}}
                <div class="relative bg-gray-200">
                    <div class="aspect-video max-h-80 flex items-center justify-center">
                        <img :src="viewModalCurrentImage || (viewModalProperty.formatted_images && viewModalProperty.formatted_images.length ? (viewModalProperty.formatted_images.find(i => i.is_primary)?.url || viewModalProperty.formatted_images[0]?.url) : 'https://via.placeholder.com/800x450?text=No+Image')"
                             alt="Property"
                             class="w-full h-full object-cover">
                    </div>
                    <template x-if="viewModalProperty.formatted_images && viewModalProperty.formatted_images.length > 1">
                        <div class="absolute bottom-2 left-2 right-2 flex gap-2 overflow-x-auto pb-1">
                            <template x-for="(img, idx) in viewModalProperty.formatted_images" :key="img.id || idx">
                                <button type="button"
                                        @click="viewModalCurrentImage = img.url"
                                        class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 border-white shadow ring-offset-1"
                                        :class="(viewModalCurrentImage === img.url) ? 'ring-2 ring-blue-600' : ''">
                                    <img :src="img.url" class="w-full h-full object-cover" alt="">
                                </button>
                            </template>
                        </div>
                    </template>
                </div>

                <div class="p-6">
                    <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                        <h2 class="text-2xl font-bold text-gray-900" x-text="viewModalProperty.title"></h2>
                        <span class="px-3 py-1 rounded-full text-xs font-medium capitalize"
                              :class="{
                                  'bg-green-100 text-green-700': viewModalProperty.status === 'published',
                                  'bg-yellow-100 text-yellow-700': viewModalProperty.status === 'pending_review',
                                  'bg-gray-100 text-gray-700': viewModalProperty.status === 'draft',
                                  'bg-purple-100 text-purple-700': viewModalProperty.status === 'rented'
                              }"
                              x-text="(viewModalProperty.status || '').replace('_', ' ')"></span>
                    </div>
                    <p class="text-gray-600 flex items-center gap-1 mb-4">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <span x-text="`${viewModalProperty.area || viewModalProperty.district || ''}, ${viewModalProperty.city || ''}`"></span>
                    </p>
                    <div class="text-3xl font-bold text-blue-600 mb-4">
                        MWK <span x-text="Number(viewModalProperty.price).toLocaleString()"></span>
                        <span class="text-lg font-normal text-gray-500" x-text="viewModalProperty.property_type === 'land' ? '(one-time)' : '/month'"></span>
                    </div>
                    {{-- Features --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6 pb-6 border-b border-gray-200">
                        <template x-if="viewModalProperty.bedrooms">
                            <div><span class="text-gray-500 text-sm">Bedrooms</span><div class="font-semibold" x-text="viewModalProperty.bedrooms"></div></div>
                        </template>
                        <template x-if="viewModalProperty.bathrooms">
                            <div><span class="text-gray-500 text-sm">Bathrooms</span><div class="font-semibold" x-text="viewModalProperty.bathrooms"></div></div>
                        </template>
                        <template x-if="viewModalProperty.size_sqm">
                            <div><span class="text-gray-500 text-sm">Size</span><div class="font-semibold" x-text="viewModalProperty.size_sqm + ' m²'"></div></div>
                        </template>
                        <div>
                            <span class="text-gray-500 text-sm">Furnished</span>
                            <div class="font-semibold" x-text="viewModalProperty.is_furnished ? 'Yes' : 'No'"></div>
                        </div>
                    </div>
                    <div class="mb-4" x-show="viewModalProperty.total_views !== undefined || viewModalProperty.total_inquiries !== undefined">
                        <span class="text-sm text-gray-500">👁️ <span x-text="viewModalProperty.total_views || 0"></span> views</span>
                        <span class="text-sm text-gray-500 ml-4">💬 <span x-text="viewModalProperty.total_inquiries || 0"></span> inquiries</span>
                    </div>
                    <div x-show="viewModalProperty.description">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-wrap" x-text="viewModalProperty.description"></p>
                    </div>
                    <template x-if="viewModalProperty.amenities && viewModalProperty.amenities.length">
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Amenities</h3>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="a in viewModalProperty.amenities" :key="a.id">
                                    <span class="px-3 py-1 bg-gray-100 rounded-full text-sm" x-text="a.amenity"></span>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
