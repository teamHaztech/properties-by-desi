<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Create New Lead
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">

            {{-- Duplicate Warning --}}
            @if (session('warning'))
                <div class="mb-6 rounded-lg border border-yellow-300 bg-yellow-50 p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 flex-shrink-0 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.168 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 6a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                {{ session('warning') }}
                                @if (session('duplicate_id'))
                                    <a href="{{ route('leads.show', session('duplicate_id')) }}" class="font-medium underline hover:text-yellow-800">
                                        View existing lead
                                    </a>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('leads.store') }}">
                        @csrf

                        {{-- Force create when duplicate detected --}}
                        @if (session('warning') && session('duplicate_id'))
                            <input type="hidden" name="force_create" value="1">
                        @endif

                        <div class="space-y-6">

                            {{-- Name --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Source --}}
                            <div>
                                <label for="source" class="block text-sm font-medium text-gray-700">Source</label>
                                <select name="source" id="source"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Select Source --</option>
                                    @foreach ($sources as $source)
                                        <option value="{{ $source->value }}" @selected(old('source') === $source->value)>
                                            {{ $source->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('source')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Assigned Agent --}}
                            <div>
                                <label for="assigned_agent_id" class="block text-sm font-medium text-gray-700">Assigned Agent</label>
                                <select name="assigned_agent_id" id="assigned_agent_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Select Agent --</option>
                                    @foreach ($agents as $agent)
                                        <option value="{{ $agent->id }}" @selected(old('assigned_agent_id') == $agent->id)>
                                            {{ $agent->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('assigned_agent_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Budget Min / Budget Max --}}
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="budget_min" class="block text-sm font-medium text-gray-700">Budget Min</label>
                                    <input type="number" name="budget_min" id="budget_min" value="{{ old('budget_min') }}" min="0" step="1"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('budget_min')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="budget_max" class="block text-sm font-medium text-gray-700">Budget Max</label>
                                    <input type="number" name="budget_max" id="budget_max" value="{{ old('budget_max') }}" min="0" step="1"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('budget_max')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Preferred Property Type --}}
                            <div>
                                <label for="preferred_property_type" class="block text-sm font-medium text-gray-700">Preferred Property Type</label>
                                <select name="preferred_property_type" id="preferred_property_type"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Select Type --</option>
                                    <option value="plot" @selected(old('preferred_property_type') === 'plot')>Plot</option>
                                    <option value="villa" @selected(old('preferred_property_type') === 'villa')>Villa</option>
                                    <option value="flat" @selected(old('preferred_property_type') === 'flat')>Flat</option>
                                </select>
                                @error('preferred_property_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Location Preference --}}
                            <div>
                                <label for="location_preference" class="block text-sm font-medium text-gray-700">Location Preference</label>
                                <input type="text" name="location_preference" id="location_preference" value="{{ old('location_preference') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('location_preference')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Urgency --}}
                            <div>
                                <label for="urgency" class="block text-sm font-medium text-gray-700">Urgency</label>
                                <select name="urgency" id="urgency"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Select Urgency --</option>
                                    <option value="low" @selected(old('urgency') === 'low')>Low</option>
                                    <option value="medium" @selected(old('urgency') === 'medium')>Medium</option>
                                    <option value="high" @selected(old('urgency') === 'high')>High</option>
                                    <option value="immediate" @selected(old('urgency') === 'immediate')>Immediate</option>
                                </select>
                                @error('urgency')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        {{-- Interested Properties --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Interested Properties</label>
                                <div class="max-h-60 overflow-y-auto border border-gray-200 rounded-lg p-3 space-y-2">
                                    @forelse ($properties as $property)
                                        <label class="flex items-start gap-3 p-2 rounded hover:bg-gray-50 cursor-pointer">
                                            <input type="checkbox" name="property_ids[]" value="{{ $property->id }}"
                                                {{ in_array($property->id, old('property_ids', [])) ? 'checked' : '' }}
                                                class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-800">{{ $property->title }}</p>
                                                <p class="text-xs text-gray-500">{{ $property->location }} &middot; {{ $property->type->label() }} &middot; {{ $property->formatted_price }}</p>
                                            </div>
                                            <span class="text-xs px-2 py-0.5 rounded-full {{ $property->status->value === 'available' ? 'bg-green-100 text-green-700' : ($property->status->value === 'reserved' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                                {{ $property->status->label() }}
                                            </span>
                                        </label>
                                    @empty
                                        <p class="text-sm text-gray-400 text-center py-4">No properties added yet.</p>
                                    @endforelse
                                </div>
                                <p class="text-xs text-gray-400 mt-1">Select properties the client is interested in.</p>
                            </div>

                        </div>

                        {{-- Actions --}}
                        <div class="mt-8 flex items-center justify-end gap-4">
                            <a href="{{ route('leads.index') }}"
                                class="rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                {{ session('warning') ? 'Force Create Lead' : 'Save Lead' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
