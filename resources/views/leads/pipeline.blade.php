<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Sales Pipeline
            </h2>
            <a href="{{ route('leads.index') }}"
               class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-300 hover:bg-gray-50">
                <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                List View
            </a>
        </div>
    </x-slot>

    @php
        $statusColors = [
            0 => ['bg' => 'bg-blue-100',    'text' => 'text-blue-800',    'border' => 'border-blue-300',    'badge' => 'bg-blue-600'],
            1 => ['bg' => 'bg-indigo-100',  'text' => 'text-indigo-800',  'border' => 'border-indigo-300',  'badge' => 'bg-indigo-600'],
            2 => ['bg' => 'bg-purple-100',  'text' => 'text-purple-800',  'border' => 'border-purple-300',  'badge' => 'bg-purple-600'],
            3 => ['bg' => 'bg-yellow-100',  'text' => 'text-yellow-800',  'border' => 'border-yellow-300',  'badge' => 'bg-yellow-600'],
            4 => ['bg' => 'bg-teal-100',    'text' => 'text-teal-800',    'border' => 'border-teal-300',    'badge' => 'bg-teal-600'],
            5 => ['bg' => 'bg-orange-100',  'text' => 'text-orange-800',  'border' => 'border-orange-300',  'badge' => 'bg-orange-600'],
            6 => ['bg' => 'bg-cyan-100',    'text' => 'text-cyan-800',    'border' => 'border-cyan-300',    'badge' => 'bg-cyan-600'],
            7 => ['bg' => 'bg-green-100',   'text' => 'text-green-800',   'border' => 'border-green-300',   'badge' => 'bg-green-600'],
        ];
    @endphp

    <div class="py-6">
        <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
            <div class="overflow-x-auto pb-4">
                <div class="flex gap-4" style="min-width: max-content;">
                    @foreach ($statuses as $index => $status)
                        @php
                            $colors = $statusColors[$index % count($statusColors)];
                            $leads = $pipeline[$status->value] ?? collect();
                        @endphp

                        <div class="flex w-72 min-w-[280px] flex-shrink-0 flex-col rounded-lg bg-gray-50">
                            {{-- Column Header --}}
                            <div class="flex items-center justify-between rounded-t-lg border-b px-4 py-3 {{ $colors['bg'] }} {{ $colors['border'] }}">
                                <h3 class="text-sm font-semibold {{ $colors['text'] }}">
                                    {{ $status->label() }}
                                </h3>
                                <span class="inline-flex h-6 min-w-[24px] items-center justify-center rounded-full px-2 text-xs font-bold text-white {{ $colors['badge'] }}">
                                    {{ $leads->count() }}
                                </span>
                            </div>

                            {{-- Cards --}}
                            <div class="flex-1 space-y-3 overflow-y-auto p-3" style="max-height: calc(100vh - 240px);">
                                @forelse ($leads as $lead)
                                    <a href="{{ route('leads.show', $lead) }}"
                                       class="block rounded-lg border border-gray-200 bg-white p-4 shadow-sm transition hover:shadow-md hover:border-gray-300">
                                        <div class="mb-2 flex items-start justify-between">
                                            <h4 class="text-sm font-semibold text-gray-900 truncate mr-2">
                                                {{ $lead->name }}
                                            </h4>
                                            @if ($lead->urgency)
                                                @php
                                                    $urgencyColors = [
                                                        'hot'  => 'bg-red-100 text-red-700',
                                                        'warm' => 'bg-yellow-100 text-yellow-700',
                                                        'cold' => 'bg-blue-100 text-blue-700',
                                                    ];
                                                    $urgencyClass = $urgencyColors[$lead->urgency] ?? 'bg-gray-100 text-gray-700';
                                                @endphp
                                                <span class="inline-flex shrink-0 items-center rounded-full px-2 py-0.5 text-xs font-medium {{ $urgencyClass }}">
                                                    {{ ucfirst($lead->urgency) }}
                                                </span>
                                            @endif
                                        </div>

                                        @if ($lead->phone)
                                            <p class="mb-1 text-xs text-gray-500">
                                                <svg class="mr-1 inline h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                                {{ $lead->phone }}
                                            </p>
                                        @endif

                                        <div class="mt-3 flex items-center justify-between">
                                            @if ($lead->assignedAgent)
                                                <span class="inline-flex items-center text-xs text-gray-500">
                                                    <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    {{ $lead->assignedAgent->name }}
                                                </span>
                                            @else
                                                <span class="text-xs italic text-gray-400">Unassigned</span>
                                            @endif

                                            <time class="text-xs text-gray-400" datetime="{{ $lead->created_at->toIso8601String() }}">
                                                {{ $lead->created_at->diffForHumans() }}
                                            </time>
                                        </div>
                                    </a>
                                @empty
                                    <div class="py-8 text-center text-xs text-gray-400">
                                        No leads
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
