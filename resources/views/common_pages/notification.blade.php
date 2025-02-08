<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Expense List') }}
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    @if(session('success'))
    <x-alert type="success" message="{{ session('success') }}" dismissable="true" />
    @endif
    @if(session('error'))
    <x-alert type="error" message="{{ session('error') }}" dismissable="true" />
    @endif
    

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
            <div id="notifications-list"></div>


            <div class="max-w-7xl mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Notifications</h1>

    @if ($notifications->isEmpty())
        <div class="bg-gray-100 p-4 rounded-lg shadow-md text-center text-gray-500">
            No notifications yet.
        </div>
    @else
        <div class="space-y-4">
            @foreach ($notifications as $notification)
            @if ($notification->read_at === null)
                <div class="bg-blue-100 p-4 rounded-lg shadow-md">
                @else
                <div class="bg-white p-4 rounded-lg shadow-md">
                @endif
                    <div class="flex justify-between items-center">
                        <p class="text-lg font-semibold text-gray-800">{{ $notification->data['message'] }}</p>
                        <span class="text-sm text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="mt-2">
                        
                    @if ($notification->type === 'App\Notifications\DepositStatusNotification')
                <a href="{{ url('/deposits/'.$notification->data['deposit_id'].'/view') }}">View Details</a>
                @endif
                @if($notification->read_at === null)
                    <form action="{{ route('mark-as-read', $notification->id) }}" method="POST">
                @csrf
                @method('PATCH')
                <button class="text-blue-500 hover:underline" type="submit">Mark as read</button>
            </form>
                @endif       
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
                
            </div>
        </div>
    </div>
</div>

</x-app-layout>