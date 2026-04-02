<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Leads') }}
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('leads.pipeline') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7" /></svg>
                    Pipeline View
                </a>
                <a href="{{ route('leads.import') }}"
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                    Import
                </a>
                <a href="{{ route('leads.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                    Add Lead
                </a>
            </div>
        </div>
    </x-slot>

    @php
        $statusColors = [
            'blue'   => 'bg-blue-100 text-blue-800',
            'green'  => 'bg-green-100 text-green-800',
            'red'    => 'bg-red-100 text-red-800',
            'yellow' => 'bg-yellow-100 text-yellow-800',
            'gray'   => 'bg-gray-100 text-gray-800',
            'orange' => 'bg-orange-100 text-orange-800',
            'purple' => 'bg-purple-100 text-purple-800',
            'indigo' => 'bg-indigo-100 text-indigo-800',
            'pink'   => 'bg-pink-100 text-pink-800',
            'teal'   => 'bg-teal-100 text-teal-800',
        ];

        $urgencyColors = [
            'low'       => 'bg-gray-100 text-gray-800',
            'medium'    => 'bg-blue-100 text-blue-800',
            'high'      => 'bg-orange-100 text-orange-800',
            'immediate' => 'bg-red-100 text-red-800',
        ];

        function formatBudget($value) {
            if (!$value) return null;
            if ($value >= 10000000) {
                return number_format($value / 10000000, 2) . ' Cr';
            }
            return number_format($value / 100000, 2) . ' L';
        }
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md flex items-center justify-between"
                     x-data="{ show: true }" x-show="show" x-transition>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                        {{ session('success') }}
                    </div>
                    <button @click="show = false" class="text-green-500 hover:text-green-700">&times;</button>
                </div>
            @endif

            @if (session('warning'))
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-md flex items-center justify-between"
                     x-data="{ show: true }" x-show="show" x-transition>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        {{ session('warning') }}
                    </div>
                    <button @click="show = false" class="text-yellow-500 hover:text-yellow-700">&times;</button>
                </div>
            @endif

            {{-- Filter Bar --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form method="GET" action="{{ route('leads.index') }}" class="p-4 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                        {{-- Search --}}
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" name="search" id="search" value="{{ $filters['search'] ?? '' }}"
                                   placeholder="Name, phone, email..."
                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Status --}}
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" id="status"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Statuses</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') == $status->value)>
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Source --}}
                        <div>
                            <label for="source" class="block text-sm font-medium text-gray-700 mb-1">Source</label>
                            <select name="source" id="source"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Sources</option>
                                @foreach ($sources as $source)
                                    <option value="{{ $source->value }}" @selected(($filters['source'] ?? '') == $source->value)>
                                        {{ $source->label() }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Agent --}}
                        <div>
                            <label for="agent" class="block text-sm font-medium text-gray-700 mb-1">Agent</label>
                            <select name="agent" id="agent"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Agents</option>
                                @foreach ($agents as $agent)
                                    <option value="{{ $agent->id }}" @selected(($filters['agent'] ?? '') == $agent->id)>
                                        {{ $agent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- City --}}
                        <div>
                            <label for="city_id" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <select name="city_id" id="city_id"
                                    class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">All Cities</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->id }}" @selected(($filters['city_id'] ?? '') == $city->id)>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date From --}}
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                            <input type="date" name="date_from" id="date_from" value="{{ $filters['date_from'] ?? '' }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        {{-- Date To --}}
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                            <input type="date" name="date_to" id="date_to" value="{{ $filters['date_to'] ?? '' }}"
                                   class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="flex items-center gap-3 mt-4">
                        <x-primary-button type="submit">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                            Filter
                        </x-primary-button>
                        <a href="{{ route('leads.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">
                            Clear Filters
                        </a>
                    </div>
                </form>
            </div>

            {{-- Desktop Table --}}
            <div class="hidden md:block bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urgency</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($leads as $lead)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('leads.show', $lead) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                            {{ $lead->name }}
                                        </a>
                                        @if ($lead->email)
                                            <div class="text-xs text-gray-500">{{ $lead->email }}</div>
                                        @endif
                                        @if ($lead->cities->isNotEmpty())
                                            <div class="flex flex-wrap gap-1 mt-1">
                                                @foreach ($lead->cities as $city)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-indigo-50 text-indigo-700">
                                                        {{ $city->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $lead->phone }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $lead->source->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$lead->status->color()] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $lead->status->label() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        {{ $lead->assignedAgent?->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        @if ($lead->budget_min || $lead->budget_max)
                                            {{ formatBudget($lead->budget_min) ?? '?' }}
                                            &ndash;
                                            {{ formatBudget($lead->budget_max) ?? '?' }}
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($lead->urgency)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $urgencyColors[$lead->urgency] ?? 'bg-gray-100 text-gray-800' }}">
                                                {{ ucfirst($lead->urgency) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $lead->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('leads.edit', $lead) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                            </a>
                                            <form method="POST" action="{{ route('leads.destroy', $lead) }}"
                                                  onsubmit="return confirm('Are you sure you want to delete this lead?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        <p class="mt-2 text-sm">No leads found.</p>
                                        <a href="{{ route('leads.create') }}" class="mt-2 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900">
                                            Add your first lead &rarr;
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Mobile Card Layout --}}
            <div class="md:hidden space-y-4">
                @forelse ($leads as $lead)
                    <div class="bg-white shadow-sm rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div>
                                <a href="{{ route('leads.show', $lead) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900">
                                    {{ $lead->name }}
                                </a>
                                @if ($lead->cities->isNotEmpty())
                                    <div class="flex flex-wrap gap-1 mt-0.5">
                                        @foreach ($lead->cities as $city)
                                            <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-medium bg-indigo-50 text-indigo-700">
                                                {{ $city->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                                <p class="text-sm text-gray-600 mt-0.5">{{ $lead->phone }}</p>
                                @if ($lead->email)
                                    <p class="text-xs text-gray-500">{{ $lead->email }}</p>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('leads.edit', $lead) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </a>
                                <form method="POST" action="{{ route('leads.destroy', $lead) }}"
                                      onsubmit="return confirm('Are you sure you want to delete this lead?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$lead->status->color()] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $lead->status->label() }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $lead->source->label() }}
                            </span>
                            @if ($lead->urgency)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $urgencyColors[$lead->urgency] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($lead->urgency) }}
                                </span>
                            @endif
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-2 text-xs text-gray-600">
                            <div>
                                <span class="font-medium text-gray-500">Agent:</span>
                                {{ $lead->assignedAgent?->name ?? '—' }}
                            </div>
                            <div>
                                <span class="font-medium text-gray-500">Budget:</span>
                                @if ($lead->budget_min || $lead->budget_max)
                                    {{ formatBudget($lead->budget_min) ?? '?' }} &ndash; {{ formatBudget($lead->budget_max) ?? '?' }}
                                @else
                                    —
                                @endif
                            </div>
                            @if ($lead->location_preference)
                                <div class="col-span-2">
                                    <span class="font-medium text-gray-500">Location:</span>
                                    {{ $lead->location_preference }}
                                </div>
                            @endif
                            <div>
                                <span class="font-medium text-gray-500">Created:</span>
                                {{ $lead->created_at->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white shadow-sm rounded-lg p-8 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <p class="mt-2 text-sm">No leads found.</p>
                        <a href="{{ route('leads.create') }}" class="mt-2 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900">
                            Add your first lead &rarr;
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($leads->hasPages())
                <div class="bg-white shadow-sm sm:rounded-lg px-4 py-3">
                    {{ $leads->withQueryString()->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
