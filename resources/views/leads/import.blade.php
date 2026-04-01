<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Import Leads</h2>
            <a href="{{ route('leads.index') }}" class="text-sm text-indigo-600 hover:underline">Back to Leads</a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Upload your leads file</h3>
                    <p class="text-sm text-gray-500 mt-1">Supports CSV and Excel files (.csv, .xlsx, .xls). Max 10MB.</p>
                </div>

                {{-- Template Download --}}
                <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-indigo-800">Need a template?</p>
                            <p class="text-sm text-indigo-600 mt-1">Download our CSV template with the correct column headers.</p>
                        </div>
                        <a href="{{ route('leads.import.template') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
                            Download Template
                        </a>
                    </div>
                </div>

                {{-- Expected Columns --}}
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="font-medium text-gray-700 mb-2">Expected columns:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['name *', 'phone *', 'email', 'source', 'status', 'budget_min', 'budget_max', 'property_type', 'location', 'urgency'] as $col)
                            <span class="px-2 py-1 bg-white border rounded text-xs {{ str_contains($col, '*') ? 'border-red-300 text-red-700 font-medium' : 'text-gray-600' }}">
                                {{ $col }}
                            </span>
                        @endforeach
                    </div>
                    <p class="text-xs text-gray-400 mt-2">* Required fields. Column names are flexible (e.g., "client_name" works for "name", "mobile" works for "phone").</p>
                </div>

                {{-- Upload Form --}}
                <form action="{{ route('leads.import.preview') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select File</label>
                        <div class="flex items-center justify-center w-full">
                            <label class="flex flex-col items-center justify-center w-full h-40 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer hover:bg-gray-50 transition">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-10 h-10 mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> or drag and drop</p>
                                    <p class="text-xs text-gray-400">CSV, XLSX, XLS (Max 10MB)</p>
                                </div>
                                <input type="file" name="file" class="hidden" accept=".csv,.xlsx,.xls" required />
                            </label>
                        </div>
                        @error('file')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assign all leads to (optional)</label>
                        <select name="assigned_agent_id" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 text-sm">
                            <option value="">-- No default agent --</option>
                            @foreach(\App\Models\User::role('sales_agent')->get() as $agent)
                                <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                        Preview Import
                    </button>
                </form>

                {{-- Smart Mapping Info --}}
                <div class="text-xs text-gray-400 space-y-1">
                    <p><strong>Source values:</strong> call, whatsapp, instagram, facebook, referral, website, walk_in, other</p>
                    <p><strong>Status values:</strong> new, contacted, spoken, interested, not_interested, visited_site, follow_up_required, loan_processing, closed_won, closed_lost</p>
                    <p><strong>Urgency:</strong> low, medium, high, immediate</p>
                    <p><strong>Phone:</strong> +91 prefix is auto-stripped. Spaces/dashes are cleaned.</p>
                    <p><strong>Duplicates:</strong> Leads with existing phone numbers are automatically skipped.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
