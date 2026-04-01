<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Quick Add Lead
            </h2>
            <a href="{{ route('dashboard') }}"
               class="text-sm text-gray-500 hover:text-gray-700">
                &larr; Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-lg px-4 sm:px-6">

            {{-- Duplicate Warning --}}
            @if (session('warning'))
                <div class="mb-6 rounded-lg border border-yellow-300 bg-yellow-50 p-4">
                    <div class="flex">
                        <svg class="mr-2 h-5 w-5 shrink-0 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-yellow-800">{{ session('warning') }}</p>
                    </div>
                </div>
            @endif

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-300 bg-red-50 p-4">
                    <ul class="list-inside list-disc space-y-1 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('leads.quick-store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Phone --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input type="tel"
                           name="phone"
                           id="phone"
                           autofocus
                           required
                           value="{{ old('phone') }}"
                           placeholder="+91 98765 43210"
                           class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-3 text-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name') }}"
                           placeholder="Lead name"
                           class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-3 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                {{-- Source --}}
                <div>
                    <label for="source" class="block text-sm font-medium text-gray-700">Source</label>
                    <select name="source"
                            id="source"
                            class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-3 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @foreach ($sources as $source)
                            <option value="{{ $source->value }}"
                                    @selected(old('source', 'call') === $source->value)>
                                {{ $source->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Agent --}}
                <div>
                    <label for="assigned_agent_id" class="block text-sm font-medium text-gray-700">Assign To</label>
                    <select name="assigned_agent_id"
                            id="assigned_agent_id"
                            class="mt-1 block w-full rounded-lg border-gray-300 px-4 py-3 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Unassigned --</option>
                        @foreach ($agents as $agent)
                            <option value="{{ $agent->id }}"
                                    @selected(old('assigned_agent_id') == $agent->id)>
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Submit --}}
                <div class="pt-2">
                    <button type="submit"
                            class="w-full rounded-lg bg-indigo-600 px-6 py-3 text-base font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                        Save Lead
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
