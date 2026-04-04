<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Add New Property</h2>
            <a href="{{ route('properties.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">&larr; Back to Inventory</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-6 rounded-md bg-red-50 p-4">
                    <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('properties.store') }}" x-data="{
                type: '{{ old('type', '') }}',
                city_id: '{{ old('city_id', '') }}',
                location: '{{ old('location', '') }}',

                size_sqm: {{ old('size_sqm', 0) ?: 0 }},
                price_per_sqm: {{ old('price_per_sqm', 0) ?: 0 }},
                owner_expected_price: {{ old('owner_expected_price', 0) ?: 0 }},
                commission_percent: {{ old('commission_percent', 2) ?: 2 }},
                is_negotiable: {{ old('is_negotiable') ? 'true' : 'false' }},
                negotiable_price: {{ old('negotiable_price', 0) ?: 0 }},

                get showSubType() { return this.type === 'plot'; },
                get showBedBath() { return this.type !== '' && this.type !== 'plot'; },
                get total_plot_price() { return (Number(this.size_sqm) || 0) * (Number(this.price_per_sqm) || 0); },
                get commission_amount() { return (Number(this.owner_expected_price) || 0) * (Number(this.commission_percent) || 0) / 100; },
                get quoted_price() { return (Number(this.owner_expected_price) || 0) + this.commission_amount; },

                formatIndian(val) {
                    val = Number(val) || 0;
                    if (val >= 10000000) return '\u20B9' + (val / 10000000).toFixed(2) + ' Cr';
                    if (val >= 100000) return '\u20B9' + (val / 100000).toFixed(2) + ' L';
                    return '\u20B9' + val.toLocaleString('en-IN');
                },

                onCityChange(e) {
                    const select = e.target;
                    const text = select.options[select.selectedIndex]?.text || '';
                    if (!this.location || this.location === '') {
                        this.location = text !== 'Select City' ? text : '';
                    }
                }
            }">
                @csrf

                {{-- ── Section 1: Basic Info ── --}}
                <div class="rounded-lg bg-white p-6 shadow">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Basic Info</h3>
                    <div class="space-y-5">

                        {{-- Title --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                   placeholder="e.g. Premium Plot near Calangute Beach">
                            @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Type & Sub Type --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700">Type <span class="text-red-500">*</span></label>
                                <select name="type" id="type" x-model="type" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Select Type</option>
                                    @foreach($types as $t)
                                        <option value="{{ $t->value }}" @selected(old('type') == $t->value)>{{ $t->label() }}</option>
                                    @endforeach
                                </select>
                                @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div x-show="showSubType" x-transition x-cloak>
                                <label for="sub_type" class="block text-sm font-medium text-gray-700">Sub Type</label>
                                <select name="sub_type" id="sub_type"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">Select Sub Type</option>
                                    @foreach($subTypes as $st)
                                        <option value="{{ $st->value }}" @selected(old('sub_type') == $st->value)>{{ $st->label() }}</option>
                                    @endforeach
                                </select>
                                @error('sub_type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- City --}}
                        <div>
                            <label for="city_id" class="block text-sm font-medium text-gray-700">City <span class="text-red-500">*</span></label>
                            <select name="city_id" id="city_id" x-model="city_id" @change="onCityChange($event)" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">Select City</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}" @selected(old('city_id') == $city->id)>{{ $city->name }}</option>
                                @endforeach
                            </select>
                            @error('city_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Location & Area --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Locality / Landmark (optional)</label>
                                <input type="text" name="location" id="location" x-model="location"
                                       placeholder="e.g. Near Holy Cross Chapel, Candolim"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('location') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="area" class="block text-sm font-medium text-gray-700">Area</label>
                                <input type="text" name="area" id="area" value="{{ old('area') }}"
                                       placeholder="e.g. North Goa"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('area') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Section 2: Size & Pricing ── --}}
                <div class="mt-6 rounded-lg bg-white p-6 shadow">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Size & Pricing</h3>
                    <div class="space-y-5">

                        {{-- Size --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="size_sqm" class="block text-sm font-medium text-gray-700">Size (sq.m)</label>
                                <input type="number" name="size_sqm" id="size_sqm" x-model.number="size_sqm" step="0.01" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('size_sqm') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="size_label" class="block text-sm font-medium text-gray-700">Size Label</label>
                                <input type="text" name="size_label" id="size_label" value="{{ old('size_label') }}"
                                       placeholder="e.g. 500 sq.m or 2000 sq.ft"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('size_label') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Price per sq.m & Total Plot Price --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="price_per_sqm" class="block text-sm font-medium text-gray-700">Price per sq.m</label>
                                <input type="number" name="price_per_sqm" id="price_per_sqm" x-model.number="price_per_sqm" step="0.01" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('price_per_sqm') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Total Plot Price</label>
                                <div class="mt-1 flex h-[38px] items-center rounded-md border border-gray-200 bg-gray-50 px-3 text-sm font-medium text-gray-700"
                                     x-text="formatIndian(total_plot_price)"></div>
                                <input type="hidden" name="total_plot_price" :value="total_plot_price">
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        {{-- Owner Expected Price --}}
                        <div>
                            <label for="owner_expected_price" class="block text-sm font-medium text-gray-700">Owner Expected Price</label>
                            <p class="text-xs text-gray-500">What the owner wants to receive</p>
                            <input type="number" name="owner_expected_price" id="owner_expected_price" x-model.number="owner_expected_price" step="0.01" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('owner_expected_price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Commission --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="commission_percent" class="block text-sm font-medium text-gray-700">Commission %</label>
                                <input type="number" name="commission_percent" id="commission_percent" x-model.number="commission_percent" step="0.1" min="0" max="100"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('commission_percent') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Commission Amount</label>
                                <div class="mt-1 flex h-[38px] items-center rounded-md border border-gray-200 bg-gray-50 px-3 text-sm font-medium text-gray-700"
                                     x-text="formatIndian(commission_amount)"></div>
                                <input type="hidden" name="commission_amount" :value="commission_amount">
                            </div>
                        </div>

                        {{-- Quoted Price --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quoted Price / Our Price</label>
                            <p class="text-xs text-gray-500">Owner price + commission -- shown to customers</p>
                            <div class="mt-1 flex h-[38px] items-center rounded-md border border-indigo-200 bg-indigo-50 px-3 text-sm font-semibold text-indigo-700"
                                 x-text="formatIndian(quoted_price)"></div>
                            <input type="hidden" name="quoted_price" :value="quoted_price">
                            <input type="hidden" name="price" :value="quoted_price">
                        </div>

                        {{-- Negotiable --}}
                        <div class="space-y-3">
                            <label class="inline-flex items-center gap-2">
                                <input type="hidden" name="is_negotiable" value="0">
                                <input type="checkbox" name="is_negotiable" value="1" x-model="is_negotiable"
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="text-sm font-medium text-gray-700">Price is negotiable</span>
                            </label>

                            <div x-show="is_negotiable" x-transition x-cloak>
                                <label for="negotiable_price" class="block text-sm font-medium text-gray-700">Negotiable Price (lowest we'll go)</label>
                                <input type="number" name="negotiable_price" id="negotiable_price" x-model.number="negotiable_price" step="0.01" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('negotiable_price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Summary Card --}}
                        <div class="rounded-lg border border-gray-200 bg-gray-50 p-4" x-show="owner_expected_price > 0">
                            <h4 class="mb-2 text-xs font-semibold uppercase tracking-wider text-gray-500">Pricing Summary</h4>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-sm">
                                <span>Owner wants: <strong class="text-gray-900" x-text="formatIndian(owner_expected_price)"></strong></span>
                                <span class="text-gray-300">|</span>
                                <span>Our cut: <strong class="text-gray-900" x-text="formatIndian(commission_amount)"></strong>
                                    (<span x-text="commission_percent"></span>%)</span>
                                <span class="text-gray-300">|</span>
                                <span>We quote: <strong class="text-indigo-700" x-text="formatIndian(quoted_price)"></strong></span>
                                <template x-if="is_negotiable && negotiable_price > 0">
                                    <span>
                                        <span class="text-gray-300">|</span>
                                        Negotiable down to: <strong class="text-amber-700" x-text="formatIndian(negotiable_price)"></strong>
                                    </span>
                                </template>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── Section 3: Details (villa/flat only) ── --}}
                <div class="mt-6 rounded-lg bg-white p-6 shadow" x-show="showBedBath" x-transition x-cloak>
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Details</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700">Bedrooms</label>
                            <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms') }}" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('bedrooms') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700">Bathrooms</label>
                            <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms') }}" min="0"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('bathrooms') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- ── Section 4: Additional ── --}}
                <div class="mt-6 rounded-lg bg-white p-6 shadow">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Additional</h3>
                    <div class="space-y-5">

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      placeholder="Property highlights, nearby landmarks, legal status..."
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description') }}</textarea>
                            @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tags & Amenities --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                                <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                                       placeholder="corner, gated, prime (comma-separated)"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('tags') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="amenities" class="block text-sm font-medium text-gray-700">Amenities</label>
                                <input type="text" name="amenities" id="amenities" value="{{ old('amenities') }}"
                                       placeholder="pool, gym, parking (comma-separated)"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('amenities') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Map Link --}}
                        <div>
                            <label for="map_link" class="block text-sm font-medium text-gray-700">Map Link</label>
                            <input type="url" name="map_link" id="map_link" value="{{ old('map_link') }}"
                                   placeholder="https://maps.google.com/..."
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('map_link') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- ── Section 5: Owner Info ── --}}
                <div class="mt-6 rounded-lg bg-white p-6 shadow">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Owner Info</h3>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="owner_name" class="block text-sm font-medium text-gray-700">Owner Name</label>
                            <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('owner_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="owner_phone" class="block text-sm font-medium text-gray-700">Owner Phone</label>
                            <input type="text" name="owner_phone" id="owner_phone" value="{{ old('owner_phone') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('owner_phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- ── Actions ── --}}
                <div class="mt-6 flex items-center justify-end gap-3">
                    <a href="{{ route('properties.index') }}"
                       class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Save Property
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>
