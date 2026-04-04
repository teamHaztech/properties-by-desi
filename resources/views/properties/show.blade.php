<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('properties.index') }}" class="text-sm text-indigo-600 hover:text-indigo-500">&larr; Back to Inventory</a>
                <h2 class="mt-1 text-xl font-semibold leading-tight text-gray-800">{{ $property->title }}</h2>
            </div>
            @role('admin')
                <div class="flex items-center gap-3">
                    <a href="{{ route('properties.edit', $property) }}"
                       class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('properties.destroy', $property) }}"
                          onsubmit="return confirm('Are you sure you want to delete this property?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                            Delete
                        </button>
                    </form>
                </div>
            @endrole
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Top Summary --}}
            <div class="rounded-lg bg-white p-6 shadow">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center rounded bg-indigo-100 px-2.5 py-1 text-sm font-medium text-indigo-700">
                            {{ $property->type->label() }}
                        </span>
                        @if($property->sub_type)
                            <span class="inline-flex items-center rounded bg-purple-100 px-2.5 py-1 text-sm font-medium text-purple-700">
                                {{ $property->sub_type->label() }}
                            </span>
                        @endif
                        @php
                            $statusColor = match($property->status->color()) {
                                'green'  => 'bg-green-100 text-green-800',
                                'yellow' => 'bg-yellow-100 text-yellow-800',
                                'red'    => 'bg-red-100 text-red-800',
                                default  => 'bg-gray-100 text-gray-800',
                            };
                        @endphp
                        <span class="inline-flex items-center rounded-full px-2.5 py-1 text-sm font-medium {{ $statusColor }}">
                            {{ $property->status->label() }}
                        </span>
                    </div>
                    <p class="text-2xl font-bold text-indigo-600">{{ $property->formatted_price }}</p>
                </div>
            </div>

            {{-- Pricing Breakdown (Super Admin / Admin only) --}}
            @role(['admin', 'super_admin'])
            @if($property->quoted_price || $property->min_rate_sqm || $property->owner_expected_price)
            <div class="rounded-lg bg-white p-6 shadow border-l-4 border-green-500">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Pricing Breakdown</h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">

                    {{-- Plot: Min/Max rate --}}
                    @if($property->type->value === 'plot')
                        @if($property->min_rate_sqm)
                        <div class="text-center p-3 bg-purple-50 rounded-lg">
                            <p class="text-xs text-gray-500">Min Rate/sq.m</p>
                            <p class="text-lg font-bold text-purple-700">₹{{ number_format($property->min_rate_sqm) }}</p>
                            @if($property->size_sqm)
                            <p class="text-xs text-purple-500">Total: {{ \App\Models\Property::formatIndian($property->size_sqm * $property->min_rate_sqm) }}</p>
                            @endif
                        </div>
                        @endif
                        @if($property->max_rate_sqm)
                        <div class="text-center p-3 bg-indigo-50 rounded-lg">
                            <p class="text-xs text-gray-500">Max Rate/sq.m</p>
                            <p class="text-lg font-bold text-indigo-700">₹{{ number_format($property->max_rate_sqm) }}</p>
                            @if($property->size_sqm)
                            <p class="text-xs text-indigo-500">Total: {{ \App\Models\Property::formatIndian($property->size_sqm * $property->max_rate_sqm) }}</p>
                            @endif
                        </div>
                        @endif
                    @else
                        {{-- Villa/Flat: Quoted + Final --}}
                        @if($property->quoted_price)
                        <div class="text-center p-3 bg-indigo-50 rounded-lg">
                            <p class="text-xs text-gray-500">Quoted Price</p>
                            <p class="text-lg font-bold text-indigo-700">{{ $property->formatted_quoted_price }}</p>
                        </div>
                        @endif
                        @if($property->final_selling_price)
                        <div class="text-center p-3 bg-green-50 rounded-lg">
                            <p class="text-xs text-gray-500">Final Selling</p>
                            <p class="text-lg font-bold text-green-700">{{ \App\Models\Property::formatIndian($property->final_selling_price) }}</p>
                        </div>
                        @endif
                    @endif

                    {{-- Commission --}}
                    @if($property->commission_amount)
                    <div class="text-center p-3 bg-emerald-50 rounded-lg">
                        <p class="text-xs text-gray-500">Commission</p>
                        <p class="text-lg font-bold text-emerald-700">{{ \App\Models\Property::formatIndian($property->commission_amount) }}</p>
                        <p class="text-xs text-emerald-600">({{ $property->commission_percent }}%)</p>
                    </div>
                    @endif

                    {{-- Negotiable --}}
                    <div class="text-center p-3 {{ $property->is_negotiable ? 'bg-yellow-50' : 'bg-red-50' }} rounded-lg">
                        <p class="text-xs text-gray-500">Negotiable?</p>
                        <p class="text-lg font-bold {{ $property->is_negotiable ? 'text-yellow-700' : 'text-red-700' }}">
                            {{ $property->is_negotiable ? 'Yes' : 'No' }}
                        </p>
                    </div>
                    @if($property->is_negotiable && $property->negotiable_price)
                    <div class="text-center p-3 bg-orange-50 rounded-lg">
                        <p class="text-xs text-gray-500">Lowest We Go</p>
                        <p class="text-lg font-bold text-orange-700">{{ \App\Models\Property::formatIndian($property->negotiable_price) }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif
            @endrole

            {{-- Details Card --}}
            <div class="rounded-lg bg-white p-6 shadow">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Property Details</h3>

                <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">City</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $property->city?->name ?? $property->location }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Location</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $property->location }}</dd>
                    </div>

                    @if($property->area)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Area</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $property->area }}</dd>
                        </div>
                    @endif

                    @if($property->size_label)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Size</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $property->size_label }}</dd>
                        </div>
                    @endif

                    @if($property->price_per_sqm)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Price per sq.m</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ number_format($property->price_per_sqm) }}</dd>
                        </div>
                    @endif

                    @if($property->bedrooms)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bedrooms</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $property->bedrooms }}</dd>
                        </div>
                    @endif

                    @if($property->bathrooms)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Bathrooms</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $property->bathrooms }}</dd>
                        </div>
                    @endif

                    @if($property->owner_name)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Owner</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $property->owner_name }}</dd>
                        </div>
                    @endif

                    @if($property->owner_phone)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Owner Phone</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="tel:{{ $property->owner_phone }}" class="text-indigo-600 hover:text-indigo-500">{{ $property->owner_phone }}</a>
                            </dd>
                        </div>
                    @endif

                    @if($property->map_link)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Map</dt>
                            <dd class="mt-1 text-sm">
                                <a href="{{ $property->map_link }}" target="_blank" rel="noopener"
                                   class="text-indigo-600 hover:text-indigo-500">View on Map &rarr;</a>
                            </dd>
                        </div>
                    @endif

                    @if($property->addedBy)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Added By</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $property->addedBy->name }}</dd>
                        </div>
                    @endif
                </dl>

                {{-- Description --}}
                @if($property->description)
                    <div class="mt-6 border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-500">Description</h4>
                        <p class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $property->description }}</p>
                    </div>
                @endif

                {{-- Amenities --}}
                @if(!empty($property->amenities))
                    <div class="mt-6 border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-500">Amenities</h4>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($property->amenities as $amenity)
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-0.5 text-sm font-medium text-blue-800">
                                    {{ $amenity }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Tags --}}
                @if(!empty($property->tags))
                    <div class="mt-6 border-t pt-4">
                        <h4 class="text-sm font-medium text-gray-500">Tags</h4>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($property->tags as $tag)
                                <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-0.5 text-sm text-gray-700">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            {{-- Documents --}}
            @if($property->documents && $property->documents->count())
                <div class="rounded-lg bg-white p-6 shadow">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900">Documents</h3>
                    <ul class="divide-y divide-gray-100">
                        @foreach($property->documents as $document)
                            <li class="flex items-center justify-between py-3">
                                <span class="text-sm text-gray-900">{{ $document->name ?? $document->original_name ?? 'Document' }}</span>
                                <a href="{{ $document->url ?? '#' }}" target="_blank"
                                   class="text-sm text-indigo-600 hover:text-indigo-500">Download</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Linked Leads --}}
            <div class="rounded-lg bg-white p-6 shadow">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Interested Leads</h3>

                @if($property->leads && $property->leads->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Phone</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Agent</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Feedback</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach($property->leads as $lead)
                                    <tr>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm font-medium text-gray-900">
                                            {{ $lead->name }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">
                                            <a href="tel:{{ $lead->phone }}" class="text-indigo-600 hover:text-indigo-500">{{ $lead->phone }}</a>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-600">
                                            {{ $lead->assignedAgent->name ?? '-' }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 text-sm">
                                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-800">
                                                {{ $lead->pivot->status ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            {{ $lead->pivot->feedback ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-sm text-gray-500">No leads linked to this property yet.</p>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
