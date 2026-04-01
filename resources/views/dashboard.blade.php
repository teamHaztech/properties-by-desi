<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="flex items-center gap-3 text-sm text-gray-500">
                <span>{{ now()->format('l, F j, Y') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="rounded-md bg-green-50 border border-green-200 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('warning'))
                <div class="rounded-md bg-yellow-50 border border-yellow-200 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 6a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 6zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                {{-- Total Leads --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-shrink-0 rounded-md bg-blue-100 p-2">
                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total_leads']) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Total Leads</p>
                    </div>
                </div>

                {{-- New Today --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-shrink-0 rounded-md bg-green-100 p-2">
                            <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                        @if ($stats['new_leads_today'] > 0)
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Today</span>
                        @endif
                    </div>
                    <div class="mt-3">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['new_leads_today']) }}</p>
                        <p class="text-xs text-gray-500 mt-1">New Today</p>
                    </div>
                </div>

                {{-- Active Leads --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-shrink-0 rounded-md bg-indigo-100 p-2">
                            <svg class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['active_leads']) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Active Leads</p>
                    </div>
                </div>

                {{-- Conversion Rate --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-shrink-0 rounded-md bg-emerald-100 p-2">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['conversion_rate'] }}%</p>
                        <p class="text-xs text-gray-500 mt-1">Conversion Rate</p>
                    </div>
                </div>

                {{-- Properties Available --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex-shrink-0 rounded-md bg-purple-100 p-2">
                            <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['properties_available']) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Properties Available</p>
                    </div>
                </div>

                {{-- Overdue Follow-ups --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 {{ $stats['follow_ups_overdue'] > 0 ? 'ring-2 ring-red-300' : '' }}">
                    <div class="flex items-center justify-between">
                        <div class="flex-shrink-0 rounded-md {{ $stats['follow_ups_overdue'] > 0 ? 'bg-red-100' : 'bg-gray-100' }} p-2">
                            <svg class="h-5 w-5 {{ $stats['follow_ups_overdue'] > 0 ? 'text-red-600' : 'text-gray-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        @if ($stats['follow_ups_overdue'] > 0)
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-xs font-medium text-red-700">Overdue</span>
                        @endif
                    </div>
                    <div class="mt-3">
                        <p class="text-2xl font-bold {{ $stats['follow_ups_overdue'] > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ number_format($stats['follow_ups_overdue']) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Overdue Follow-ups</p>
                    </div>
                </div>
            </div>

            {{-- Two-Column Layout: Recent Leads + Follow-ups --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Recent Leads Table (takes 2/3 width on large screens) --}}
                <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-4 py-4 sm:px-6 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900">Recent Leads</h3>
                        <a href="{{ route('leads.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">View all</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th scope="col" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th scope="col" class="hidden md:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="hidden lg:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                                    <th scope="col" class="hidden sm:table-cell px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($recentLeads as $lead)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <a href="{{ route('leads.show', $lead) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                                {{ $lead->name }}
                                            </a>
                                        </td>
                                        <td class="hidden sm:table-cell px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                            {{ $lead->phone }}
                                        </td>
                                        <td class="hidden md:table-cell px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                            {{ $lead->source->label() }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            @php
                                                $color = $lead->status->color();
                                                $colorClasses = match($color) {
                                                    'blue' => 'bg-blue-100 text-blue-800',
                                                    'indigo' => 'bg-indigo-100 text-indigo-800',
                                                    'purple' => 'bg-purple-100 text-purple-800',
                                                    'yellow' => 'bg-yellow-100 text-yellow-800',
                                                    'red' => 'bg-red-100 text-red-800',
                                                    'teal' => 'bg-teal-100 text-teal-800',
                                                    'orange' => 'bg-orange-100 text-orange-800',
                                                    'cyan' => 'bg-cyan-100 text-cyan-800',
                                                    'green' => 'bg-green-100 text-green-800',
                                                    'gray' => 'bg-gray-100 text-gray-800',
                                                    default => 'bg-gray-100 text-gray-800',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $colorClasses }}">
                                                {{ $lead->status->label() }}
                                            </span>
                                        </td>
                                        <td class="hidden lg:table-cell px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                                            {{ $lead->assignedAgent?->name ?? '—' }}
                                        </td>
                                        <td class="hidden sm:table-cell px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                            {{ $lead->created_at->format('M j, g:ia') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                            No leads yet. <a href="{{ route('leads.index') }}" class="text-indigo-600 hover:underline">Add your first lead</a>.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Follow-ups Panel (takes 1/3 width on large screens) --}}
                <div class="space-y-6">
                    {{-- Overdue Follow-ups --}}
                    @if ($overdueFollowUps->count() > 0)
                        <div class="bg-white rounded-lg shadow-sm border border-red-200 ring-1 ring-red-100">
                            <div class="px-4 py-4 border-b border-red-200 bg-red-50 rounded-t-lg">
                                <div class="flex items-center gap-2">
                                    <svg class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                                    </svg>
                                    <h3 class="text-sm font-semibold text-red-800">Overdue Follow-ups ({{ $overdueFollowUps->count() }})</h3>
                                </div>
                            </div>
                            <ul class="divide-y divide-red-100">
                                @foreach ($overdueFollowUps as $followUp)
                                    <li class="px-4 py-3 bg-red-50/30">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $followUp->title }}</p>
                                                <p class="text-xs text-gray-600 mt-0.5">
                                                    <a href="{{ route('leads.show', $followUp->lead) }}" class="text-indigo-600 hover:underline">{{ $followUp->lead->name }}</a>
                                                    @if ($followUp->user)
                                                        <span class="text-gray-400 mx-1">&middot;</span> {{ $followUp->user->name }}
                                                    @endif
                                                </p>
                                                <p class="text-xs text-red-600 mt-0.5">{{ $followUp->scheduled_at->diffForHumans() }}</p>
                                            </div>
                                            <form method="POST" action="{{ route('follow-ups.complete', $followUp) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="flex-shrink-0 rounded bg-red-100 p-1 text-red-600 hover:bg-red-200 transition-colors" title="Mark complete">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Today's Follow-ups --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-4 py-4 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900">Today's Follow-ups ({{ $todayFollowUps->count() }})</h3>
                        </div>
                        @if ($todayFollowUps->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach ($todayFollowUps as $followUp)
                                    <li class="px-4 py-3">
                                        <div class="flex items-start justify-between gap-2">
                                            <div class="min-w-0 flex-1">
                                                <p class="text-sm font-medium text-gray-900 truncate">{{ $followUp->title }}</p>
                                                <p class="text-xs text-gray-600 mt-0.5">
                                                    <a href="{{ route('leads.show', $followUp->lead) }}" class="text-indigo-600 hover:underline">{{ $followUp->lead->name }}</a>
                                                    @if ($followUp->user)
                                                        <span class="text-gray-400 mx-1">&middot;</span> {{ $followUp->user->name }}
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500 mt-0.5">{{ $followUp->scheduled_at->format('g:i A') }}</p>
                                            </div>
                                            <form method="POST" action="{{ route('follow-ups.complete', $followUp) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="flex-shrink-0 rounded bg-gray-100 p-1 text-gray-500 hover:bg-green-100 hover:text-green-600 transition-colors" title="Mark complete">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="px-4 py-8 text-center">
                                <svg class="mx-auto h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500">All clear for today.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Quick Stats: Leads by Source --}}
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-4 py-4 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900">Leads by Source</h3>
                        </div>
                        <div class="px-4 py-3 space-y-2">
                            @forelse ($leadsBySource as $source => $count)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600 capitalize">{{ str_replace('_', ' ', $source) }}</span>
                                    <span class="font-medium text-gray-900">{{ $count }}</span>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 text-center py-2">No data yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Agent Performance Table --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-4 py-4 sm:px-6 border-b border-gray-200">
                    <h3 class="text-base font-semibold text-gray-900">Agent Performance</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Total Leads</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Won</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Lost</th>
                                <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Conversion Rate</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($agentPerformance as $agent)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-indigo-700">{{ strtoupper(substr($agent['agent'], 0, 1)) }}</span>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">{{ $agent['agent'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 text-center">
                                        {{ $agent['total'] }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                                            {{ $agent['won'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                            {{ $agent['lost'] }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <div class="w-16 bg-gray-200 rounded-full h-1.5">
                                                <div class="h-1.5 rounded-full {{ $agent['rate'] >= 50 ? 'bg-green-500' : ($agent['rate'] >= 25 ? 'bg-yellow-500' : 'bg-red-500') }}" style="width: {{ min($agent['rate'], 100) }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">{{ $agent['rate'] }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">
                                        No agent data available yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('leads.index') }}" class="flex items-center gap-3 bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:border-indigo-300 hover:shadow transition-all">
                    <div class="flex-shrink-0 rounded-md bg-blue-100 p-2">
                        <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0ZM3.75 12h.007v.008H3.75V12Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm-.375 5.25h.007v.008H3.75v-.008Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">All Leads</span>
                </a>
                <a href="{{ route('leads.pipeline') }}" class="flex items-center gap-3 bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:border-indigo-300 hover:shadow transition-all">
                    <div class="flex-shrink-0 rounded-md bg-purple-100 p-2">
                        <svg class="h-5 w-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0-3.75-3.75M17.25 21l3.75-3.75" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Pipeline</span>
                </a>
                <a href="{{ route('properties.index') }}" class="flex items-center gap-3 bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:border-indigo-300 hover:shadow transition-all">
                    <div class="flex-shrink-0 rounded-md bg-emerald-100 p-2">
                        <svg class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">Properties</span>
                </a>
                <a href="{{ route('leads.index') }}?status=new" class="flex items-center gap-3 bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:border-indigo-300 hover:shadow transition-all">
                    <div class="flex-shrink-0 rounded-md bg-yellow-100 p-2">
                        <svg class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700">New Leads</span>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
