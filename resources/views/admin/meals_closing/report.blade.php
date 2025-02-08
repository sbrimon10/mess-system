<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Report: Meal and Deposit Statistics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success or Error Alerts -->
            @if(session('success'))
                <x-alert type="success" message="{{ session('success') }}" dismissable="true" />
            @endif
            @if(session('error'))
                <x-alert type="error" message="{{ session('error') }}" dismissable="true" />
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <h3 class="text-2xl font-semibold">User Meal and Deposit Statistics for {{ $month }} ({{ $year }})</h3>
                    </div>

                    <!-- User Stats Table -->
                    <table class="min-w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 border-b">Name</th>
                                <th class="px-4 py-2 border-b">Total Meals</th>
                                <th class="px-4 py-2 border-b">Total Deposits</th>
                                <th class="px-4 py-2 border-b">Due Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userStats as $userStat)
                                <tr>
                                    <td class="border px-4 py-2">{{ $userStat->name }}</td>
                                    <td class="border px-4 py-2">{{ $userStat->totalMeals }} Meals</td>
                                    <td class="border px-4 py-2">₱{{ number_format($userStat->totalDeposits, 2) }}</td>
                                    <td class="border px-4 py-2">₱{{ number_format($userStat->dueAmount, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
