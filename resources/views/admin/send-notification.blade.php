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


            <div class="max-w-lg mx-auto bg-white dark:bg-gray-700 rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-semibold mb-6">Send System Notification</h1>

            <form action="{{ route('admin.sendSystemNotification') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="message" class="block text-sm font-medium">Notification Message</label>
                    <input type="text" name="message" id="message" class="mt-1 block w-full px-4 py-2 bg-gray-100 dark:bg-gray-600 dark:text-white rounded-md focus:ring-2 focus:ring-indigo-500" required>
                </div>

                <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Send Notification</button>
            </form>
        </div>
                
            </div>
        </div>
    </div>
</div>

</x-app-layout>