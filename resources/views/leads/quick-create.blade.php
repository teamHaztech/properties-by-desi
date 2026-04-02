<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Quick Add Lead
            </h2>
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700">&larr; Dashboard</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-2xl px-4 sm:px-6">

            @if (session('warning'))
                <div class="mb-6 rounded-lg border border-yellow-300 bg-yellow-50 p-4">
                    <div class="flex">
                        <svg class="mr-2 h-5 w-5 shrink-0 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-300 bg-red-50 p-4">
                    <ul class="list-inside list-disc space-y-1 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm p-6">
                <form action="{{ route('leads.quick-store') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Phone (large, autofocus) --}}
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number *</label>
                        <input type="tel" name="phone" id="phone" autofocus required value="{{ old('phone') }}"
                            placeholder="+91 98765 43210"
                            class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-3 text-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name *</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                            placeholder="Lead name"
                            class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-3 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email (optional)</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            placeholder="email@example.com"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Source (default: call) --}}
                        <div>
                            <label for="source" class="block text-sm font-medium text-gray-700">Source</label>
                            <select name="source" id="source"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @foreach ($sources as $source)
                                    <option value="{{ $source->value }}" @selected(old('source', 'call') === $source->value)>
                                        {{ $source->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Agent (default: Pasad) --}}
                        @php $defaultAgent = old('assigned_agent_id', $agents->firstWhere('username', 'pasad')?->id); @endphp
                        <div>
                            <label for="assigned_agent_id" class="block text-sm font-medium text-gray-700">Assign To</label>
                            <select name="assigned_agent_id" id="assigned_agent_id"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <option value="">-- Unassigned --</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}" @selected($defaultAgent == $agent->id)>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Budget Range Dual Slider --}}
                    @include('components.budget-slider', ['defaultMin' => old('budget_min', 3000000), 'defaultMax' => old('budget_max', 20000000)])

                    {{-- Property Type --}}
                    <div>
                        <label for="preferred_property_type" class="block text-sm font-medium text-gray-700">Preferred Property Type</label>
                        <select name="preferred_property_type" id="preferred_property_type"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="">-- Select Type --</option>
                            <option value="plot" @selected(old('preferred_property_type') === 'plot')>Plot</option>
                            <option value="villa" @selected(old('preferred_property_type') === 'villa')>Villa</option>
                            <option value="flat" @selected(old('preferred_property_type') === 'flat')>Flat</option>
                        </select>
                    </div>

                    {{-- Urgency --}}
                    <div>
                        <label for="urgency" class="block text-sm font-medium text-gray-700">Urgency</label>
                        <select name="urgency" id="urgency"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="low" @selected(old('urgency') === 'low')>Low</option>
                            <option value="medium" @selected(old('urgency', 'medium') === 'medium')>Medium</option>
                            <option value="high" @selected(old('urgency') === 'high')>High</option>
                            <option value="immediate" @selected(old('urgency') === 'immediate')>Immediate</option>
                        </select>
                    </div>

                    {{-- Preferred Locations --}}
                    @if(isset($cities) && $cities->count() > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Preferred Locations</label>
                        <div class="flex flex-wrap gap-2 max-h-40 overflow-y-auto border border-gray-200 rounded-lg p-3">
                            @foreach ($cities as $city)
                                <label class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full border cursor-pointer transition bg-white border-gray-200 text-gray-600 hover:border-indigo-300 hover:bg-indigo-50">
                                    <input type="checkbox" name="city_ids[]" value="{{ $city->id }}"
                                        {{ in_array($city->id, old('city_ids', [])) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-3.5 h-3.5">
                                    <span class="text-sm">{{ $city->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Submit --}}
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full rounded-lg bg-indigo-600 px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Save Lead
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
