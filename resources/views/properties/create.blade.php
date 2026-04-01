<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Add New Property</h2>
            <a href="{{ route('properties.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">&larr; Back to Inventory</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-6 shadow" x-data="{
                type: '{{ old('type', '') }}',
                get showSubType() { return this.type === 'plot'; },
                get showBedBath() { return this.type !== '' && this.type !== 'plot'; }
            }">

                @if($errors->any())
                    <div class="mb-6 rounded-md bg-red-50 p-4">
                        <ul class="list-disc space-y-1 pl-5 text-sm text-red-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('properties.store') }}">
                    @csrf

                    <div class="space-y-6">

                        {{-- Title --}}
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
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

                            <div x-show="showSubType" x-transition>
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

                        {{-- Location & Area --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700">Location <span class="text-red-500">*</span></label>
                                <input type="text" name="location" id="location" value="{{ old('location') }}" required
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('location') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="area" class="block text-sm font-medium text-gray-700">Area</label>
                                <input type="text" name="area" id="area" value="{{ old('area') }}"
                                       placeholder="e.g. Banjara Hills"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('area') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Price, Price/sqm, Size --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Price <span class="text-red-500">*</span></label>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" required step="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('price') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="price_per_sqm" class="block text-sm font-medium text-gray-700">Price / sq.m</label>
                                <input type="number" name="price_per_sqm" id="price_per_sqm" value="{{ old('price_per_sqm') }}" step="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('price_per_sqm') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="size_sqm" class="block text-sm font-medium text-gray-700">Size (sq.m)</label>
                                <input type="number" name="size_sqm" id="size_sqm" value="{{ old('size_sqm') }}" step="0.01"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('size_sqm') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Size Label --}}
                        <div>
                            <label for="size_label" class="block text-sm font-medium text-gray-700">Size Label</label>
                            <input type="text" name="size_label" id="size_label" value="{{ old('size_label') }}"
                                   placeholder="e.g. 200 sq.yd / 1500 sqft"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            @error('size_label') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Bedrooms & Bathrooms (conditional) --}}
                        <div x-show="showBedBath" x-transition class="grid grid-cols-1 gap-4 sm:grid-cols-2">
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

                        {{-- Description --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4"
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

                        {{-- Owner Info --}}
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

                    {{-- Submit --}}
                    <div class="mt-8 flex items-center justify-end gap-3">
                        <a href="{{ route('properties.index') }}"
                           class="rounded-md px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Create Property
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
