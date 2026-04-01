@php
if (!function_exists('formatIndianCurrency')) {
    function formatIndianCurrency($amount) {
        if ($amount >= 10000000) {
            $value = round($amount / 10000000, 2);
            return '₹' . $value . ' Cr';
        } elseif ($amount >= 100000) {
            $value = round($amount / 100000, 2);
            return '₹' . $value . ' L';
        } else {
            return '₹' . number_format($amount);
        }
    }
}
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('leads.index') }}"
                    class="inline-flex items-center rounded-md bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ $lead->name }}
                </h2>
                {{-- Status Badge --}}
                <span class="inline-flex items-center rounded-full bg-{{ $lead->status->color() }}-100 px-2.5 py-0.5 text-xs font-medium text-{{ $lead->status->color() }}-800">
                    {{ $lead->status->label() }}
                </span>
                {{-- Urgency Badge --}}
                @if ($lead->urgency)
                    @php
                        $urgencyColors = [
                            'low' => 'gray',
                            'medium' => 'blue',
                            'high' => 'orange',
                            'immediate' => 'red',
                        ];
                        $urgencyColor = $urgencyColors[$lead->urgency] ?? 'gray';
                    @endphp
                    <span class="inline-flex items-center rounded-full bg-{{ $urgencyColor }}-100 px-2.5 py-0.5 text-xs font-medium text-{{ $urgencyColor }}-800">
                        {{ ucfirst($lead->urgency) }}
                    </span>
                @endif
                {{-- Converted Badge --}}
                @if ($lead->is_converted)
                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                        Converted
                    </span>
                @endif
            </div>
            <a href="{{ route('leads.edit', $lead) }}"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Lead
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-300 bg-green-50 p-4">
                    <div class="flex">
                        <svg class="h-5 w-5 flex-shrink-0 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Quick Status Update --}}
            <div class="mb-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-4">
                    <h3 class="mb-3 text-sm font-medium text-gray-500 uppercase tracking-wide">Quick Status Update</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach (\App\Enums\LeadStatus::cases() as $status)
                            <form method="POST" action="{{ route('leads.update-status', $lead) }}" class="inline">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $status->value }}">
                                <button type="submit"
                                    class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-medium transition-colors
                                        {{ $lead->status === $status
                                            ? 'bg-' . $status->color() . '-600 text-white ring-2 ring-' . $status->color() . '-600 ring-offset-2'
                                            : 'bg-' . $status->color() . '-100 text-' . $status->color() . '-800 hover:bg-' . $status->color() . '-200' }}">
                                    {{ $status->label() }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Two Column Layout --}}
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                {{-- Left Column (2/3) --}}
                <div class="space-y-6 lg:col-span-2">

                    {{-- Lead Info Card --}}
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900">Lead Information</h3>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if ($lead->phone)
                                            <a href="tel:{{ $lead->phone }}" class="text-indigo-600 hover:text-indigo-500">{{ $lead->phone }}</a>
                                        @else
                                            <span class="text-gray-400">--</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if ($lead->email)
                                            <a href="mailto:{{ $lead->email }}" class="text-indigo-600 hover:text-indigo-500">{{ $lead->email }}</a>
                                        @else
                                            <span class="text-gray-400">--</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Source</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->source?->label() ?? '--' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Assigned Agent</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->assignedAgent?->name ?? 'Unassigned' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Budget Range</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if ($lead->budget_min || $lead->budget_max)
                                            @if ($lead->budget_min) {{ formatIndianCurrency($lead->budget_min) }} @endif
                                            @if ($lead->budget_min && $lead->budget_max) &mdash; @endif
                                            @if ($lead->budget_max) {{ formatIndianCurrency($lead->budget_max) }} @endif
                                        @else
                                            <span class="text-gray-400">--</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Preferred Property Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->preferred_property_type ? ucfirst($lead->preferred_property_type) : '--' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Location Preference</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->location_preference ?? '--' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Contacted</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $lead->last_contacted_at ? $lead->last_contacted_at->format('d M Y, h:i A') : '--' }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->created_at->format('d M Y, h:i A') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {{-- Communication Log --}}
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900">Communication Log</h3>
                        </div>
                        <div class="p-6">
                            {{-- Existing Communications --}}
                            @if ($lead->communications->count())
                                <div class="mb-6 space-y-3">
                                    @foreach ($lead->communications->sortByDesc('created_at') as $comm)
                                        <div class="flex items-start gap-3 rounded-lg border border-gray-200 p-4">
                                            {{-- Type Icon --}}
                                            <div class="flex-shrink-0">
                                                @switch($comm->type)
                                                    @case('call')
                                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-green-100">
                                                            <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                            </svg>
                                                        </div>
                                                        @break
                                                    @case('whatsapp')
                                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-emerald-100">
                                                            <svg class="h-5 w-5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z" />
                                                                <path d="M12 2C6.477 2 2 6.477 2 12c0 1.89.525 3.66 1.438 5.168L2 22l4.832-1.438A9.955 9.955 0 0012 22c5.523 0 10-4.477 10-10S17.523 2 12 2zm0 18a7.96 7.96 0 01-4.11-1.14l-.29-.174-3.01.79.8-2.93-.19-.3A7.96 7.96 0 014 12c0-4.41 3.59-8 8-8s8 3.59 8 8-3.59 8-8 8z" />
                                                            </svg>
                                                        </div>
                                                        @break
                                                    @case('email')
                                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100">
                                                            <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                        @break
                                                    @case('sms')
                                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-purple-100">
                                                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                                                            </svg>
                                                        </div>
                                                        @break
                                                    @case('meeting')
                                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-yellow-100">
                                                            <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </div>
                                                        @break
                                                    @case('site_visit')
                                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-teal-100">
                                                            <svg class="h-5 w-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            </svg>
                                                        </div>
                                                        @break
                                                    @default
                                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100">
                                                            <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                                                            </svg>
                                                        </div>
                                                @endswitch
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $comm->type)) }}</span>
                                                    <span class="inline-flex items-center rounded px-1.5 py-0.5 text-xs font-medium {{ $comm->direction === 'inbound' ? 'bg-blue-100 text-blue-700' : 'bg-orange-100 text-orange-700' }}">
                                                        {{ ucfirst($comm->direction) }}
                                                    </span>
                                                    @if ($comm->duration)
                                                        <span class="text-xs text-gray-500">{{ $comm->duration }} min</span>
                                                    @endif
                                                </div>
                                                <p class="mt-1 text-sm text-gray-600">{{ $comm->summary }}</p>
                                                <p class="mt-1 text-xs text-gray-400">
                                                    by {{ $comm->user?->name ?? 'System' }} &middot; {{ $comm->created_at->format('d M Y, h:i A') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="mb-6 text-sm text-gray-500">No communications logged yet.</p>
                            @endif

                            {{-- Add Communication Form --}}
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <h4 class="mb-3 text-sm font-medium text-gray-700">Log Communication</h4>
                                <form method="POST" action="{{ route('leads.add-communication', $lead) }}">
                                    @csrf
                                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        <div>
                                            <label for="comm_type" class="block text-sm font-medium text-gray-700">Type</label>
                                            <select name="type" id="comm_type"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                <option value="call">Call</option>
                                                <option value="whatsapp">WhatsApp</option>
                                                <option value="email">Email</option>
                                                <option value="sms">SMS</option>
                                                <option value="meeting">Meeting</option>
                                                <option value="site_visit">Site Visit</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label for="comm_direction" class="block text-sm font-medium text-gray-700">Direction</label>
                                            <select name="direction" id="comm_direction"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                <option value="outbound">Outbound</option>
                                                <option value="inbound">Inbound</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label for="comm_summary" class="block text-sm font-medium text-gray-700">Summary</label>
                                        <textarea name="summary" id="comm_summary" rows="3" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="What was discussed..."></textarea>
                                    </div>
                                    <div class="mt-4 flex items-end gap-4">
                                        <div class="w-32">
                                            <label for="comm_duration" class="block text-sm font-medium text-gray-700">Duration (min)</label>
                                            <input type="number" name="duration" id="comm_duration" min="0"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <button type="submit"
                                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                            Log Communication
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Notes Section --}}
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900">Notes</h3>
                        </div>
                        <div class="p-6">
                            {{-- Existing Notes --}}
                            @if ($lead->notes->count())
                                <div class="mb-6 space-y-3">
                                    @foreach ($lead->notes->sortByDesc('created_at') as $note)
                                        <div class="rounded-lg border {{ $note->is_pinned ? 'border-yellow-300 bg-yellow-50' : 'border-gray-200' }} p-4">
                                            <div class="flex items-start justify-between">
                                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $note->content }}</p>
                                                @if ($note->is_pinned)
                                                    <span class="ml-2 inline-flex flex-shrink-0 items-center rounded-full bg-yellow-100 px-2 py-0.5 text-xs font-medium text-yellow-800">
                                                        <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                        Pinned
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="mt-2 text-xs text-gray-400">
                                                by {{ $note->user?->name ?? 'System' }} &middot; {{ $note->created_at->format('d M Y, h:i A') }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="mb-6 text-sm text-gray-500">No notes yet.</p>
                            @endif

                            {{-- Add Note Form --}}
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <h4 class="mb-3 text-sm font-medium text-gray-700">Add Note</h4>
                                <form method="POST" action="{{ route('leads.add-note', $lead) }}">
                                    @csrf
                                    <div>
                                        <textarea name="content" rows="3" required
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            placeholder="Write a note..."></textarea>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between">
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" name="is_pinned" value="1"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                            <span class="text-sm text-gray-600">Pin this note</span>
                                        </label>
                                        <button type="submit"
                                            class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                            Add Note
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right Column (1/3) --}}
                <div class="space-y-6">

                    {{-- Follow-ups Card --}}
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900">Follow-ups</h3>
                        </div>
                        <div class="p-6">
                            @if ($lead->followUps->count())
                                <div class="mb-6 space-y-3">
                                    @foreach ($lead->followUps->sortBy('scheduled_at') as $followUp)
                                        @php
                                            $priorityColors = [
                                                'low' => 'gray',
                                                'medium' => 'blue',
                                                'high' => 'orange',
                                                'urgent' => 'red',
                                            ];
                                            $fpColor = $priorityColors[$followUp->priority] ?? 'gray';
                                            $isOverdue = $followUp->status === 'pending' && $followUp->scheduled_at->isPast();
                                        @endphp
                                        <div class="rounded-lg border {{ $isOverdue ? 'border-red-300 bg-red-50' : 'border-gray-200' }} p-3">
                                            <div class="flex items-start justify-between gap-2">
                                                <div class="min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $followUp->title }}</p>
                                                    <p class="mt-1 text-xs text-gray-500">
                                                        {{ $followUp->scheduled_at->format('d M Y, h:i A') }}
                                                        @if ($isOverdue)
                                                            <span class="font-medium text-red-600">- Overdue</span>
                                                        @endif
                                                    </p>
                                                    <div class="mt-1 flex items-center gap-2">
                                                        <span class="inline-flex items-center rounded px-1.5 py-0.5 text-xs font-medium bg-{{ $fpColor }}-100 text-{{ $fpColor }}-700">
                                                            {{ ucfirst($followUp->priority) }}
                                                        </span>
                                                        <span class="inline-flex items-center rounded px-1.5 py-0.5 text-xs font-medium {{ $followUp->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                            {{ ucfirst($followUp->status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @if ($followUp->status === 'pending')
                                                    <form method="POST" action="{{ route('follow-ups.complete', $followUp) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                            class="flex-shrink-0 rounded bg-green-600 px-2 py-1 text-xs font-medium text-white hover:bg-green-500">
                                                            Complete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="mb-6 text-sm text-gray-500">No follow-ups scheduled.</p>
                            @endif

                            {{-- Schedule Follow-up Form --}}
                            <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <h4 class="mb-3 text-sm font-medium text-gray-700">Schedule Follow-up</h4>
                                <form method="POST" action="{{ route('follow-ups.store', $lead) }}">
                                    @csrf
                                    <div class="space-y-3">
                                        <div>
                                            <label for="fu_title" class="block text-sm font-medium text-gray-700">Title</label>
                                            <input type="text" name="title" id="fu_title" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                placeholder="e.g. Follow-up call">
                                        </div>
                                        <div>
                                            <label for="fu_description" class="block text-sm font-medium text-gray-700">Description</label>
                                            <textarea name="description" id="fu_description" rows="2"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                placeholder="Optional details..."></textarea>
                                        </div>
                                        <div>
                                            <label for="fu_scheduled_at" class="block text-sm font-medium text-gray-700">Scheduled At</label>
                                            <input type="datetime-local" name="scheduled_at" id="fu_scheduled_at" required
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        </div>
                                        <div>
                                            <label for="fu_priority" class="block text-sm font-medium text-gray-700">Priority</label>
                                            <select name="priority" id="fu_priority"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                <option value="low">Low</option>
                                                <option value="medium" selected>Medium</option>
                                                <option value="high">High</option>
                                                <option value="urgent">Urgent</option>
                                            </select>
                                        </div>
                                        <button type="submit"
                                            class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                            Schedule Follow-up
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Mapped Properties Card --}}
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900">Mapped Properties</h3>
                        </div>
                        <div class="p-6">
                            @if ($lead->properties->count())
                                <div class="space-y-3">
                                    @foreach ($lead->properties as $property)
                                        <a href="{{ route('properties.show', $property) }}"
                                            class="block rounded-lg border border-gray-200 p-3 transition hover:border-indigo-300 hover:bg-indigo-50">
                                            <p class="text-sm font-medium text-gray-900">{{ $property->title }}</p>
                                            <div class="mt-1 flex flex-wrap items-center gap-2">
                                                @if ($property->type)
                                                    <span class="text-xs text-gray-500">{{ ucfirst($property->type) }}</span>
                                                @endif
                                                @if ($property->price)
                                                    <span class="text-xs font-medium text-green-700">{{ formatIndianCurrency($property->price) }}</span>
                                                @endif
                                                @if ($property->pivot?->status)
                                                    <span class="inline-flex items-center rounded bg-gray-100 px-1.5 py-0.5 text-xs font-medium text-gray-700">
                                                        {{ ucfirst(str_replace('_', ' ', $property->pivot->status)) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-500">No properties mapped.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Activity Timeline --}}
                    <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                        <div class="border-b border-gray-200 px-6 py-4">
                            <h3 class="text-lg font-medium text-gray-900">Activity Timeline</h3>
                        </div>
                        <div class="p-6">
                            @if ($lead->activities->count())
                                <div class="flow-root">
                                    <ul class="-mb-8">
                                        @foreach ($lead->activities->sortByDesc('created_at') as $activity)
                                            <li>
                                                <div class="relative pb-8">
                                                    @if (!$loop->last)
                                                        <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                                    @endif
                                                    <div class="relative flex items-start space-x-3">
                                                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 ring-8 ring-white">
                                                            <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <p class="text-sm text-gray-700">
                                                                <span class="font-medium">{{ $activity->action }}</span>
                                                            </p>
                                                            @if ($activity->changes)
                                                                <div class="mt-1 text-xs text-gray-500">
                                                                    @if (is_array($activity->changes))
                                                                        @foreach ($activity->changes as $field => $change)
                                                                            <p>
                                                                                <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $field)) }}:</span>
                                                                                @if (is_array($change))
                                                                                    {{ $change['old'] ?? '--' }} &rarr; {{ $change['new'] ?? '--' }}
                                                                                @else
                                                                                    {{ $change }}
                                                                                @endif
                                                                            </p>
                                                                        @endforeach
                                                                    @else
                                                                        <p>{{ $activity->changes }}</p>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                            <p class="mt-1 text-xs text-gray-400">
                                                                {{ $activity->user?->name ?? 'System' }} &middot; {{ $activity->created_at->format('d M Y, h:i A') }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <p class="text-sm text-gray-500">No activity recorded yet.</p>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
