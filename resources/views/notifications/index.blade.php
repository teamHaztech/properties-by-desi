<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold text-gray-800">Notifications</h2>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-indigo-600 hover:underline">Mark all as read</button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($notifications->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <p class="text-gray-500">No notifications yet.</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($notifications as $notification)
                        <div class="bg-white rounded-lg shadow-sm border {{ $notification->read_at ? 'border-gray-100' : 'border-indigo-200 bg-indigo-50/30' }}">
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full text-left p-4 hover:bg-gray-50 transition rounded-lg">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1">
                                            <p class="text-sm {{ $notification->read_at ? 'text-gray-600' : 'text-gray-800 font-medium' }}">
                                                {{ $notification->data['message'] ?? 'New notification' }}
                                            </p>
                                            <div class="flex items-center gap-3 mt-1">
                                                <span class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</span>
                                                @if(!$notification->read_at)
                                                    <span class="inline-flex w-2 h-2 bg-indigo-500 rounded-full"></span>
                                                @endif
                                            </div>
                                        </div>
                                        @if(isset($notification->data['lead_id']))
                                            <span class="text-xs text-indigo-500 whitespace-nowrap">View Lead &rarr;</span>
                                        @endif
                                    </div>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
