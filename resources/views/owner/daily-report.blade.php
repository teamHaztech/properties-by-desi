<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daily Report</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $activity['date_label'] }}</p>
            </div>
            <div class="flex items-center gap-3">
                <form method="GET" class="flex items-center gap-2">
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
                           class="text-sm border-gray-300 rounded-lg focus:ring-indigo-500" />
                </form>
                <a href="{{ route('owner.dashboard') }}" class="text-sm text-indigo-600 hover:underline">Back to Overview</a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-6">Activity Summary</h3>

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-blue-100 flex items-center justify-center mb-2">
                            <span class="text-2xl font-bold text-blue-600">{{ $activity['leads_created'] }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">New Leads</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-green-100 flex items-center justify-center mb-2">
                            <span class="text-2xl font-bold text-green-600">{{ $activity['leads_won'] }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Deals Won</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-red-100 flex items-center justify-center mb-2">
                            <span class="text-2xl font-bold text-red-600">{{ $activity['leads_lost'] }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Deals Lost</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-indigo-100 flex items-center justify-center mb-2">
                            <span class="text-2xl font-bold text-indigo-600">{{ $activity['calls_made'] }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Calls Made</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-purple-100 flex items-center justify-center mb-2">
                            <span class="text-2xl font-bold text-purple-600">{{ $activity['total_communications'] }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Total Comms</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-teal-100 flex items-center justify-center mb-2">
                            <span class="text-2xl font-bold text-teal-600">{{ $activity['site_visits'] }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">Site Visits</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-yellow-100 flex items-center justify-center mb-2">
                            <span class="text-2xl font-bold text-yellow-600">{{ $activity['follow_ups_scheduled'] }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">F/U Scheduled</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 mx-auto rounded-full bg-emerald-100 flex items-center justify-center mb-2">
                            <span class="text-2xl font-bold text-emerald-600">{{ $activity['follow_ups_completed'] }}</span>
                        </div>
                        <p class="text-sm font-medium text-gray-700">F/U Completed</p>
                    </div>
                </div>
            </div>

            {{-- Quick Navigation --}}
            <div class="flex items-center justify-center gap-3">
                <a href="{{ route('owner.daily-report', ['date' => \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d')]) }}"
                   class="px-4 py-2 bg-white rounded-lg shadow-sm border text-sm hover:bg-gray-50 transition">
                    &larr; Previous Day
                </a>
                @if($date !== today()->format('Y-m-d'))
                    <a href="{{ route('owner.daily-report', ['date' => \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d')]) }}"
                       class="px-4 py-2 bg-white rounded-lg shadow-sm border text-sm hover:bg-gray-50 transition">
                        Next Day &rarr;
                    </a>
                @endif
                @if($date !== today()->format('Y-m-d'))
                    <a href="{{ route('owner.daily-report') }}"
                       class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 transition">
                        Go to Today
                    </a>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
