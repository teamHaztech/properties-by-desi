<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Preview Import</h2>
            <a href="{{ route('leads.import') }}" class="text-sm text-indigo-600 hover:underline">Back to Upload</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Summary --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">File Preview</h3>
                        <p class="text-sm text-gray-500 mt-1">Showing first 10 rows of <strong>{{ $totalRows }}</strong> total rows.</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-indigo-600">{{ $totalRows }}</p>
                        <p class="text-xs text-gray-500">rows found</p>
                    </div>
                </div>
            </div>

            {{-- Data Preview Table --}}
            <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b">
                            <th class="text-left py-3 px-3 text-xs font-medium text-gray-500 uppercase">#</th>
                            @foreach($headers as $header)
                                <th class="text-left py-3 px-3 text-xs font-medium text-gray-500 uppercase whitespace-nowrap">
                                    {{ $header }}
                                    @if(in_array(strtolower($header), ['name', 'phone', 'client_name', 'lead_name', 'mobile', 'phone_number']))
                                        <span class="text-red-500">*</span>
                                    @endif
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($preview as $index => $row)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="py-2 px-3 text-gray-400">{{ $index + 1 }}</td>
                                @foreach($headers as $header)
                                    <td class="py-2 px-3 whitespace-nowrap max-w-[200px] truncate">
                                        {{ $row[$header] ?? '' }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($totalRows > 10)
                    <div class="p-3 bg-gray-50 text-center text-sm text-gray-500">
                        ... and {{ $totalRows - 10 }} more rows
                    </div>
                @endif
            </div>

            {{-- Confirm Import --}}
            <div class="bg-white rounded-xl shadow-sm p-6">
                <form action="{{ route('leads.import.process') }}" method="POST">
                    @csrf
                    <input type="hidden" name="file_path" value="{{ $filePath }}" />
                    <input type="hidden" name="assigned_agent_id" value="{{ $assignedAgentId }}" />

                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-sm text-gray-600">
                            <p>Ready to import <strong>{{ $totalRows }}</strong> leads.</p>
                            @if($assignedAgentId)
                                @php $agent = \App\Models\User::find($assignedAgentId); @endphp
                                <p>All leads will be assigned to: <strong>{{ $agent?->name ?? 'Unknown' }}</strong></p>
                            @else
                                <p>No default agent assigned.</p>
                            @endif
                            <p class="text-xs text-gray-400 mt-1">Duplicates (same phone) will be automatically skipped.</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('leads.import') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition text-sm">
                                Confirm Import
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
