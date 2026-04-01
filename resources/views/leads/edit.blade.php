<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Edit Lead: {{ $lead->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('leads.update', $lead) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">

                            {{-- Name --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $lead->name) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Phone --}}
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Phone <span class="text-red-500">*</span></label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $lead->phone) }}" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $lead->email) }}"
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
                                        <option value="{{ $source->value }}" @selected(old('source', $lead->source?->value) === $source->value)>
                                            {{ $source->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('source')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    <option value="">-- Select Status --</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status->value }}" @selected(old('status', $lead->status?->value) === $status->value)>
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')
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
                                        <option value="{{ $agent->id }}" @selected(old('assigned_agent_id', $lead->assigned_agent_id) == $agent->id)>
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
                                    <input type="number" name="budget_min" id="budget_min" value="{{ old('budget_min', $lead->budget_min) }}" min="0" step="1"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                    @error('budget_min')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="budget_max" class="block text-sm font-medium text-gray-700">Budget Max</label>
                                    <input type="number" name="budget_max" id="budget_max" value="{{ old('budget_max', $lead->budget_max) }}" min="0" step="1"
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
                                    <option value="plot" @selected(old('preferred_property_type', $lead->preferred_property_type) === 'plot')>Plot</option>
                                    <option value="villa" @selected(old('preferred_property_type', $lead->preferred_property_type) === 'villa')>Villa</option>
                                    <option value="flat" @selected(old('preferred_property_type', $lead->preferred_property_type) === 'flat')>Flat</option>
                                </select>
                                @error('preferred_property_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Location Preference --}}
                            <div>
                                <label for="location_preference" class="block text-sm font-medium text-gray-700">Location Preference</label>
                                <input type="text" name="location_preference" id="location_preference" value="{{ old('location_preference', $lead->location_preference) }}"
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
                                    <option value="low" @selected(old('urgency', $lead->urgency) === 'low')>Low</option>
                                    <option value="medium" @selected(old('urgency', $lead->urgency) === 'medium')>Medium</option>
                                    <option value="high" @selected(old('urgency', $lead->urgency) === 'high')>High</option>
                                    <option value="immediate" @selected(old('urgency', $lead->urgency) === 'immediate')>Immediate</option>
                                </select>
                                @error('urgency')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                                Save Lead
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
