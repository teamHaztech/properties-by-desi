<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enquiry - Properties By Desi</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen">

    {{-- Header --}}
    <header class="bg-white shadow-sm">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-center gap-3">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-12 w-12 rounded-full object-cover">
            <div class="text-center">
                <h1 class="text-xl font-bold text-indigo-600">Properties By Desi</h1>
                <p class="text-xs text-gray-500">Your Dream Property in Goa</p>
            </div>
        </div>
    </header>

    <main class="max-w-3xl mx-auto px-4 py-8">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-8 bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                <svg class="w-16 h-16 mx-auto text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h2 class="text-xl font-bold text-green-800 mb-2">{{ session('duplicate') ? 'Welcome Back!' : 'Enquiry Submitted!' }}</h2>
                <p class="text-green-700">{{ session('success') }}</p>
                <a href="{{ route('public.lead-form') }}" class="inline-block mt-4 px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">Submit Another Enquiry</a>
            </div>
        @else

        {{-- Form --}}
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="bg-indigo-600 px-6 py-4">
                <h2 class="text-lg font-bold text-white">Property Enquiry Form</h2>
                <p class="text-indigo-200 text-sm mt-1">Fill in your details and we'll get back to you within 24 hours.</p>
            </div>

            <form action="{{ route('public.lead-form.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                {{-- Name & Phone --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Enter your name">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="+91 98765 43210">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email (optional)</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="your@email.com">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Property Type --}}
                <div>
                    <label for="preferred_property_type" class="block text-sm font-medium text-gray-700">Looking for</label>
                    <select name="preferred_property_type" id="preferred_property_type"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Select Type --</option>
                        <option value="plot" @selected(old('preferred_property_type') === 'plot')>Plot</option>
                        <option value="villa" @selected(old('preferred_property_type') === 'villa')>Villa</option>
                        <option value="flat" @selected(old('preferred_property_type') === 'flat')>Flat</option>
                    </select>
                </div>

                {{-- Preferred Locations (City checkboxes) --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Locations</label>
                    <div class="flex flex-wrap gap-2 max-h-48 overflow-y-auto border border-gray-200 rounded-lg p-3">
                        @foreach($cities as $city)
                            <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border cursor-pointer transition bg-white border-gray-200 text-gray-600 hover:border-indigo-300 hover:bg-indigo-50">
                                <input type="checkbox" name="city_ids[]" value="{{ $city->id }}"
                                    {{ in_array($city->id, old('city_ids', [])) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5">
                                <span class="text-sm">{{ $city->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Other Location (not listed above) --}}
                <div>
                    <label for="location_preference" class="block text-sm font-medium text-gray-700">Other location (not listed above)</label>
                    <input type="text" name="location_preference" id="location_preference" value="{{ old('location_preference') }}"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="e.g. North Goa, Vagator, Panjim">
                </div>

                {{-- Budget --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="budget_min" class="block text-sm font-medium text-gray-700">Budget Min (₹)</label>
                        <input type="number" name="budget_min" id="budget_min" value="{{ old('budget_min') }}" min="0"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. 3000000">
                    </div>
                    <div>
                        <label for="budget_max" class="block text-sm font-medium text-gray-700">Budget Max (₹)</label>
                        <input type="number" name="budget_max" id="budget_max" value="{{ old('budget_max') }}" min="0"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="e.g. 5000000">
                    </div>
                </div>

                {{-- Available Properties --}}
                @if($properties->count() > 0)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Interested in any of these properties?</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-72 overflow-y-auto border border-gray-200 rounded-lg p-3">
                        @foreach($properties as $property)
                            <label class="flex items-start gap-3 p-3 rounded-lg border border-gray-100 hover:border-indigo-300 hover:bg-indigo-50/50 cursor-pointer transition">
                                <input type="checkbox" name="property_ids[]" value="{{ $property->id }}"
                                    {{ in_array($property->id, old('property_ids', [])) ? 'checked' : '' }}
                                    class="mt-0.5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $property->title }}</p>
                                    <p class="text-xs text-gray-500">{{ $property->location }} &middot; {{ $property->type->label() }}</p>
                                    <p class="text-xs font-semibold text-indigo-600 mt-0.5">{{ $property->formatted_price }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Message --}}
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700">Message (optional)</label>
                    <textarea name="message" id="message" rows="3"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Tell us more about what you're looking for...">{{ old('message') }}</textarea>
                    @error('message') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition text-sm">
                    Submit Enquiry
                </button>

                <p class="text-xs text-gray-400 text-center">
                    By submitting, you agree to be contacted by our team via phone or WhatsApp.
                </p>
            </form>
        </div>
        @endif

    </main>

    {{-- Footer --}}
    <footer class="text-center py-6 text-xs text-gray-400">
        &copy; {{ date('Y') }} Properties By Desi. All rights reserved.
    </footer>

</body>
</html>
