<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-4 flex-wrap">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Property Inventory</h2>
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                        {{ $stats['available'] }} Available
                    </span>
                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">
                        {{ $stats['reserved'] }} Reserved
                    </span>
                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                        {{ $stats['sold'] }} Sold
                    </span>
                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                        {{ $stats['total'] }} Total
                    </span>
                </div>
            </div>
            @role('admin')
                <a href="{{ route('properties.create') }}"
                   class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    + Add Property
                </a>
            @endrole
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Filter Bar --}}
            <form method="GET" action="{{ route('properties.index') }}" class="mb-6 rounded-lg bg-white p-4 shadow">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-6">
                    {{-- Search --}}
                    <div>
                        <label for="search" class="block text-xs font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" value="{{ $filters['search'] ?? '' }}"
                               placeholder="Title, location..."
                               class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Type --}}
                    <div>
                        <label for="type" class="block text-xs font-medium text-gray-700">Type</label>
                        <select name="type" id="type"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Types</option>
                            @foreach($types as $type)
                                <option value="{{ $type->value }}" @selected(($filters['type'] ?? '') == $type->value)>
                                    {{ $type->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sub Type --}}
                    <div>
                        <label for="sub_type" class="block text-xs font-medium text-gray-700">Sub Type</label>
                        <select name="sub_type" id="sub_type"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Sub Types</option>
                            @foreach($subTypes as $subType)
                                <option value="{{ $subType->value }}" @selected(($filters['sub_type'] ?? '') == $subType->value)>
                                    {{ $subType->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="block text-xs font-medium text-gray-700">Status</label>
                        <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->value }}" @selected(($filters['status'] ?? '') == $status->value)>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Price Min --}}
                    <div>
                        <label for="price_min" class="block text-xs font-medium text-gray-700">Min Price</label>
                        <input type="number" name="price_min" id="price_min" value="{{ $filters['price_min'] ?? '' }}"
                               placeholder="0"
                               class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Price Max --}}
                    <div>
                        <label for="price_max" class="block text-xs font-medium text-gray-700">Max Price</label>
                        <input type="number" name="price_max" id="price_max" value="{{ $filters['price_max'] ?? '' }}"
                               placeholder="No limit"
                               class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-3">
                    <button type="submit"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        Filter
                    </button>
                    <a href="{{ route('properties.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Clear</a>
                </div>
            </form>

            {{-- Property Grid --}}
            @if($properties->count())
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($properties as $property)
                        <a href="{{ route('properties.show', $property) }}"
                           class="block rounded-lg bg-white shadow transition hover:shadow-lg">
                            <div class="p-5">
                                {{-- Title & Status --}}
                                <div class="mb-3 flex items-start justify-between gap-2">
                                    <h3 class="text-lg font-semibold text-gray-900 line-clamp-1">{{ $property->title }}</h3>
                                    @php
                                        $statusColor = match($property->status->color()) {
                                            'green'  => 'bg-green-100 text-green-800',
                                            'yellow' => 'bg-yellow-100 text-yellow-800',
                                            'red'    => 'bg-red-100 text-red-800',
                                            default  => 'bg-gray-100 text-gray-800',
                                        };
                                    @endphp
                                    <span class="inline-flex shrink-0 items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $statusColor }}">
                                        {{ $property->status->label() }}
                                    </span>
                                </div>

                                {{-- Type Badges --}}
                                <div class="mb-3 flex flex-wrap gap-1.5">
                                    <span class="inline-flex items-center rounded bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700">
                                        {{ $property->type->label() }}
                                    </span>
                                    @if($property->sub_type)
                                        <span class="inline-flex items-center rounded bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-700">
                                            {{ $property->sub_type->label() }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Location --}}
                                <p class="mb-2 text-sm text-gray-600">
                                    <svg class="mr-1 inline h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                    {{ $property->location }}
                                </p>

                                {{-- Size & Price --}}
                                <div class="mb-3 flex items-center justify-between">
                                    <span class="text-sm text-gray-500">{{ $property->size_label }}</span>
                                    <span class="text-lg font-bold text-indigo-600">{{ $property->formatted_price }}</span>
                                </div>

                                {{-- Bedrooms / Bathrooms --}}
                                @if($property->bedrooms || $property->bathrooms)
                                    <div class="mb-3 flex items-center gap-4 text-sm text-gray-500">
                                        @if($property->bedrooms)
                                            <span>{{ $property->bedrooms }} Bed{{ $property->bedrooms > 1 ? 's' : '' }}</span>
                                        @endif
                                        @if($property->bathrooms)
                                            <span>{{ $property->bathrooms }} Bath{{ $property->bathrooms > 1 ? 's' : '' }}</span>
                                        @endif
                                    </div>
                                @endif

                                {{-- Tags --}}
                                @if(!empty($property->tags))
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($property->tags as $tag)
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-600">
                                                {{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $properties->withQueryString()->links() }}
                </div>
            @else
                <div class="rounded-lg bg-white p-12 text-center shadow">
                    <p class="text-gray-500">No properties found matching your criteria.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
