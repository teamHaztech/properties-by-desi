<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Owner Dashboard</h2>
                <p class="text-sm text-gray-500 mt-1">Business overview &amp; analytics</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" class="flex items-center gap-2">
                    <select name="range" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-lg focus:ring-indigo-500">
                        <option value="7" {{ $dateRange == '7' ? 'selected' : '' }}>Last 7 days</option>
                        <option value="14" {{ $dateRange == '14' ? 'selected' : '' }}>Last 14 days</option>
                        <option value="30" {{ $dateRange == '30' ? 'selected' : '' }}>Last 30 days</option>
                        <option value="90" {{ $dateRange == '90' ? 'selected' : '' }}>Last 90 days</option>
                    </select>
                </form>
                <a href="{{ route('owner.daily-report') }}" class="inline-flex items-center px-3 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                    Daily Report
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Revenue Overview Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4">
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-green-500">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total Revenue</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">
                        @if($revenue['total_revenue'] >= 10000000)
                            {{ number_format($revenue['total_revenue'] / 10000000, 2) }} Cr
                        @elseif($revenue['total_revenue'] >= 100000)
                            {{ number_format($revenue['total_revenue'] / 100000, 2) }} L
                        @else
                            {{ number_format($revenue['total_revenue']) }}
                        @endif
                    </p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-blue-500">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">This Month</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">
                        @if($revenue['monthly_revenue'] >= 10000000)
                            {{ number_format($revenue['monthly_revenue'] / 10000000, 2) }} Cr
                        @elseif($revenue['monthly_revenue'] >= 100000)
                            {{ number_format($revenue['monthly_revenue'] / 100000, 2) }} L
                        @else
                            {{ number_format($revenue['monthly_revenue']) }}
                        @endif
                    </p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-emerald-500">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Total Deals</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $revenue['total_deals'] }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-cyan-500">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Monthly Deals</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $revenue['monthly_deals'] }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-yellow-500">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Avg Deal Size</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">
                        @if($revenue['avg_deal_size'] >= 100000)
                            {{ number_format($revenue['avg_deal_size'] / 100000, 1) }} L
                        @else
                            {{ number_format($revenue['avg_deal_size']) }}
                        @endif
                    </p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-purple-500">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">In Pipeline</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">{{ $revenue['deals_in_pipeline'] }}</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border-l-4 border-orange-500">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Pipeline Value</p>
                    <p class="text-xl font-bold text-gray-800 mt-1">
                        @if($revenue['pipeline_value'] >= 10000000)
                            {{ number_format($revenue['pipeline_value'] / 10000000, 1) }} Cr
                        @elseif($revenue['pipeline_value'] >= 100000)
                            {{ number_format($revenue['pipeline_value'] / 100000, 1) }} L
                        @else
                            {{ number_format($revenue['pipeline_value']) }}
                        @endif
                    </p>
                </div>
            </div>

            {{-- Charts Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Daily Lead Trend --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Lead Inflow Trend</h3>
                    <canvas id="leadTrendChart" height="200"></canvas>
                </div>

                {{-- Monthly Comparison --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Overview (6 months)</h3>
                    <canvas id="monthlyTrendChart" height="200"></canvas>
                </div>
            </div>

            {{-- Source Performance + Conversion Trend --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Source Performance Pie --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Leads by Source</h3>
                    <div class="flex items-center justify-center">
                        <canvas id="sourcePieChart" height="250" style="max-height: 300px;"></canvas>
                    </div>
                </div>

                {{-- Won vs Lost Trend --}}
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Won vs Lost Trend</h3>
                    <canvas id="conversionChart" height="250"></canvas>
                </div>
            </div>

            {{-- Today's Activity Summary --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Today's Activity — {{ $todayActivity['date_label'] }}</h3>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-4">
                    <div class="text-center p-3 bg-blue-50 rounded-lg">
                        <p class="text-2xl font-bold text-blue-600">{{ $todayActivity['leads_created'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">New Leads</p>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-lg">
                        <p class="text-2xl font-bold text-green-600">{{ $todayActivity['leads_won'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Won</p>
                    </div>
                    <div class="text-center p-3 bg-red-50 rounded-lg">
                        <p class="text-2xl font-bold text-red-600">{{ $todayActivity['leads_lost'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Lost</p>
                    </div>
                    <div class="text-center p-3 bg-indigo-50 rounded-lg">
                        <p class="text-2xl font-bold text-indigo-600">{{ $todayActivity['calls_made'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Calls</p>
                    </div>
                    <div class="text-center p-3 bg-purple-50 rounded-lg">
                        <p class="text-2xl font-bold text-purple-600">{{ $todayActivity['total_communications'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Communications</p>
                    </div>
                    <div class="text-center p-3 bg-teal-50 rounded-lg">
                        <p class="text-2xl font-bold text-teal-600">{{ $todayActivity['site_visits'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">Site Visits</p>
                    </div>
                    <div class="text-center p-3 bg-yellow-50 rounded-lg">
                        <p class="text-2xl font-bold text-yellow-600">{{ $todayActivity['follow_ups_scheduled'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">F/U Scheduled</p>
                    </div>
                    <div class="text-center p-3 bg-emerald-50 rounded-lg">
                        <p class="text-2xl font-bold text-emerald-600">{{ $todayActivity['follow_ups_completed'] }}</p>
                        <p class="text-xs text-gray-500 mt-1">F/U Completed</p>
                    </div>
                </div>
            </div>

            {{-- Weekly Day-by-Day Breakdown --}}
            <div class="bg-white rounded-xl shadow-sm p-6 overflow-x-auto">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Last 7 Days — Day by Day</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="text-left py-3 px-3 font-medium text-gray-600">Day</th>
                            <th class="text-center py-3 px-2 font-medium text-gray-600">New Leads</th>
                            <th class="text-center py-3 px-2 font-medium text-green-600">Won</th>
                            <th class="text-center py-3 px-2 font-medium text-red-600">Lost</th>
                            <th class="text-center py-3 px-2 font-medium text-gray-600">Calls</th>
                            <th class="text-center py-3 px-2 font-medium text-gray-600">Comms</th>
                            <th class="text-center py-3 px-2 font-medium text-gray-600">Site Visits</th>
                            <th class="text-center py-3 px-2 font-medium text-gray-600">F/U Done</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weeklyBreakdown as $day)
                            <tr class="border-b hover:bg-gray-50 {{ $day['date'] === today()->format('Y-m-d') ? 'bg-indigo-50' : '' }}">
                                <td class="py-3 px-3 font-medium {{ $day['date'] === today()->format('Y-m-d') ? 'text-indigo-700' : 'text-gray-800' }}">
                                    {{ $day['date_label'] }}
                                    @if($day['date'] === today()->format('Y-m-d'))
                                        <span class="ml-1 text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full">Today</span>
                                    @endif
                                </td>
                                <td class="text-center py-3 px-2">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $day['leads_created'] > 0 ? 'bg-blue-100 text-blue-700' : 'text-gray-400' }}">
                                        {{ $day['leads_created'] }}
                                    </span>
                                </td>
                                <td class="text-center py-3 px-2">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $day['leads_won'] > 0 ? 'bg-green-100 text-green-700 font-bold' : 'text-gray-400' }}">
                                        {{ $day['leads_won'] }}
                                    </span>
                                </td>
                                <td class="text-center py-3 px-2">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $day['leads_lost'] > 0 ? 'bg-red-100 text-red-700' : 'text-gray-400' }}">
                                        {{ $day['leads_lost'] }}
                                    </span>
                                </td>
                                <td class="text-center py-3 px-2">{{ $day['calls_made'] }}</td>
                                <td class="text-center py-3 px-2">{{ $day['total_communications'] }}</td>
                                <td class="text-center py-3 px-2">{{ $day['site_visits'] }}</td>
                                <td class="text-center py-3 px-2">{{ $day['follow_ups_completed'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Agent Leaderboard --}}
            <div class="bg-white rounded-xl shadow-sm p-6 overflow-x-auto">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Agent Leaderboard</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="text-left py-3 px-3 font-medium text-gray-600">#</th>
                            <th class="text-left py-3 px-3 font-medium text-gray-600">Agent</th>
                            <th class="text-center py-3 px-2 font-medium text-gray-600">Total Leads</th>
                            <th class="text-center py-3 px-2 font-medium text-gray-600">Active</th>
                            <th class="text-center py-3 px-2 font-medium text-green-600">Won</th>
                            <th class="text-center py-3 px-2 font-medium text-red-600">Lost</th>
                            <th class="text-center py-3 px-2 font-medium text-gray-600">Conv %</th>
                            <th class="text-center py-3 px-2 font-medium text-gray-600">Calls</th>
                            <th class="text-center py-3 px-2 font-medium text-gray-600">Comms</th>
                            <th class="text-center py-3 px-2 font-medium text-orange-600">Overdue F/U</th>
                            <th class="text-right py-3 px-3 font-medium text-gray-600">Revenue</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agentLeaderboard as $i => $agent)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-3">
                                    @if($i === 0)
                                        <span class="text-yellow-500 font-bold text-lg">1</span>
                                    @elseif($i === 1)
                                        <span class="text-gray-400 font-bold text-lg">2</span>
                                    @elseif($i === 2)
                                        <span class="text-orange-400 font-bold text-lg">3</span>
                                    @else
                                        <span class="text-gray-400">{{ $i + 1 }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm">
                                            {{ strtoupper(substr($agent['name'], 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">{{ $agent['name'] }}</p>
                                            <p class="text-xs text-gray-400">{{ '@' . $agent['username'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center py-3 px-2 font-medium">{{ $agent['total_leads'] }}</td>
                                <td class="text-center py-3 px-2">
                                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs font-medium">{{ $agent['active'] }}</span>
                                </td>
                                <td class="text-center py-3 px-2">
                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-bold">{{ $agent['won'] }}</span>
                                </td>
                                <td class="text-center py-3 px-2">
                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-xs">{{ $agent['lost'] }}</span>
                                </td>
                                <td class="text-center py-3 px-2">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full {{ $agent['conversion_rate'] >= 50 ? 'bg-green-500' : ($agent['conversion_rate'] >= 25 ? 'bg-yellow-500' : 'bg-red-400') }}"
                                                 style="width: {{ min($agent['conversion_rate'], 100) }}%"></div>
                                        </div>
                                        <span class="text-xs font-medium">{{ $agent['conversion_rate'] }}%</span>
                                    </div>
                                </td>
                                <td class="text-center py-3 px-2">{{ $agent['total_calls'] }}</td>
                                <td class="text-center py-3 px-2">{{ $agent['total_comms'] }}</td>
                                <td class="text-center py-3 px-2">
                                    @if($agent['overdue_followups'] > 0)
                                        <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-xs font-bold animate-pulse">
                                            {{ $agent['overdue_followups'] }}
                                        </span>
                                    @else
                                        <span class="text-green-500 text-xs">0</span>
                                    @endif
                                </td>
                                <td class="text-right py-3 px-3 font-medium text-gray-800">
                                    @if($agent['revenue'] >= 10000000)
                                        {{ number_format($agent['revenue'] / 10000000, 1) }} Cr
                                    @elseif($agent['revenue'] >= 100000)
                                        {{ number_format($agent['revenue'] / 100000, 1) }} L
                                    @else
                                        {{ number_format($agent['revenue']) }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Source Performance Table --}}
            <div class="bg-white rounded-xl shadow-sm p-6 overflow-x-auto">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Source-wise Performance</h3>
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="text-left py-3 px-3 font-medium text-gray-600">Source</th>
                            <th class="text-center py-3 px-3 font-medium text-gray-600">Total Leads</th>
                            <th class="text-center py-3 px-3 font-medium text-gray-600">Active</th>
                            <th class="text-center py-3 px-3 font-medium text-green-600">Won</th>
                            <th class="text-center py-3 px-3 font-medium text-red-600">Lost</th>
                            <th class="text-center py-3 px-3 font-medium text-gray-600">Conversion %</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sourcePerformance as $source)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-3 px-3 font-medium text-gray-800">{{ $source['source'] }}</td>
                                <td class="text-center py-3 px-3 font-bold">{{ $source['total'] }}</td>
                                <td class="text-center py-3 px-3">
                                    <span class="bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full text-xs">{{ $source['active'] }}</span>
                                </td>
                                <td class="text-center py-3 px-3">
                                    <span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs font-bold">{{ $source['won'] }}</span>
                                </td>
                                <td class="text-center py-3 px-3">
                                    <span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-xs">{{ $source['lost'] }}</span>
                                </td>
                                <td class="text-center py-3 px-3">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-20 bg-gray-200 rounded-full h-2">
                                            <div class="h-2 rounded-full bg-indigo-500" style="width: {{ min($source['conversion_rate'], 100) }}%"></div>
                                        </div>
                                        <span class="text-xs font-medium">{{ $source['conversion_rate'] }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Property Inventory Overview --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Property Inventory</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <div class="text-center p-4 bg-indigo-50 rounded-lg">
                        <p class="text-3xl font-bold text-indigo-700">{{ $propertyStats['total_properties'] }}</p>
                        <p class="text-sm text-gray-500">Total Properties</p>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <p class="text-3xl font-bold text-green-700">
                            @if($propertyStats['total_inventory_value'] >= 10000000)
                                {{ number_format($propertyStats['total_inventory_value'] / 10000000, 1) }} Cr
                            @else
                                {{ number_format($propertyStats['total_inventory_value'] / 100000, 1) }} L
                            @endif
                        </p>
                        <p class="text-sm text-gray-500">Available Inventory Value</p>
                    </div>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <p class="text-3xl font-bold text-yellow-700">
                            @if($propertyStats['total_sold_value'] >= 10000000)
                                {{ number_format($propertyStats['total_sold_value'] / 10000000, 1) }} Cr
                            @else
                                {{ number_format($propertyStats['total_sold_value'] / 100000, 1) }} L
                            @endif
                        </p>
                        <p class="text-sm text-gray-500">Sold Value</p>
                    </div>
                </div>

                @if(count($propertyStats['by_type']) > 0)
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b bg-gray-50">
                            <th class="text-left py-2 px-3 font-medium text-gray-600">Type</th>
                            <th class="text-center py-2 px-3 font-medium text-green-600">Available</th>
                            <th class="text-center py-2 px-3 font-medium text-yellow-600">Reserved</th>
                            <th class="text-center py-2 px-3 font-medium text-red-600">Sold</th>
                            <th class="text-center py-2 px-3 font-medium text-gray-600">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($propertyStats['by_type'] as $type => $counts)
                            <tr class="border-b">
                                <td class="py-2 px-3 font-medium capitalize">{{ $type }}</td>
                                <td class="text-center py-2 px-3"><span class="bg-green-100 text-green-700 px-2 py-0.5 rounded-full text-xs">{{ $counts['available'] }}</span></td>
                                <td class="text-center py-2 px-3"><span class="bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full text-xs">{{ $counts['reserved'] }}</span></td>
                                <td class="text-center py-2 px-3"><span class="bg-red-100 text-red-700 px-2 py-0.5 rounded-full text-xs">{{ $counts['sold'] }}</span></td>
                                <td class="text-center py-2 px-3 font-bold">{{ $counts['total'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>

        </div>
    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        // Lead Inflow Trend
        new Chart(document.getElementById('leadTrendChart'), {
            type: 'line',
            data: {
                labels: @json($dailyLeadTrend['labels']),
                datasets: [{
                    label: 'New Leads',
                    data: @json($dailyLeadTrend['data']),
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 2,
                    pointHoverRadius: 5,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { ticks: { maxTicksLimit: 10, font: { size: 10 } } }
                }
            }
        });

        // Monthly Trend
        new Chart(document.getElementById('monthlyTrendChart'), {
            type: 'bar',
            data: {
                labels: @json($monthlyTrend['labels']),
                datasets: [
                    {
                        label: 'New Leads',
                        data: @json($monthlyTrend['new_leads']),
                        backgroundColor: 'rgba(99, 102, 241, 0.7)',
                    },
                    {
                        label: 'Won',
                        data: @json($monthlyTrend['closed_won']),
                        backgroundColor: 'rgba(34, 197, 94, 0.7)',
                    },
                    {
                        label: 'Lost',
                        data: @json($monthlyTrend['closed_lost']),
                        backgroundColor: 'rgba(239, 68, 68, 0.7)',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Source Pie Chart
        const sourceColors = ['#6366f1', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#f97316'];
        new Chart(document.getElementById('sourcePieChart'), {
            type: 'doughnut',
            data: {
                labels: @json(array_column($sourcePerformance, 'source')),
                datasets: [{
                    data: @json(array_column($sourcePerformance, 'total')),
                    backgroundColor: sourceColors.slice(0, {{ count($sourcePerformance) }}),
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 12, padding: 15, font: { size: 11 } } }
                }
            }
        });

        // Won vs Lost Trend
        new Chart(document.getElementById('conversionChart'), {
            type: 'line',
            data: {
                labels: @json($conversionTrend['labels']),
                datasets: [
                    {
                        label: 'Won',
                        data: @json($conversionTrend['won']),
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 2,
                    },
                    {
                        label: 'Lost',
                        data: @json($conversionTrend['lost']),
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true,
                        tension: 0.3,
                        pointRadius: 2,
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                    x: { ticks: { maxTicksLimit: 10, font: { size: 10 } } }
                },
                plugins: { legend: { position: 'bottom' } }
            }
        });
    </script>
</x-app-layout>
