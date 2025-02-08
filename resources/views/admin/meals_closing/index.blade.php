<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Meals Closing Report') }}
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
                        <h3 class="text-2xl font-semibold">Report for {{ $month }} ({{ $year }})</h3>
                        <div class="mt-2">
                            <form method="GET" action="{{ route('meals.index') }}">
                                <div class="flex space-x-4">
                                    <input type="month" name="month" value="{{ $month }}" class="border border-gray-300 p-2 rounded">
                                    <input type="number" name="year" value="{{ $year }}" class="border border-gray-300 p-2 rounded">
                                    <button type="submit" class="bg-blue-500 text-white p-2 rounded">View</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Current Month Report -->
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="font-bold">Total Approved Amount</h4>
                            <p class="text-xl text-green-500">₱{{ number_format($approvedTotal, 2) }}</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="font-bold">Total Expenses</h4>
                            <p class="text-xl text-red-500">₱{{ number_format($totalExpenses, 2) }}</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="font-bold">Extra Charges</h4>
                            <p class="text-xl text-yellow-500">₱{{ number_format($extraCharges, 2) }}</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="font-bold">Total Meals Count</h4>
                            <p class="text-xl">{{ $totalMeals }} Meals</p>
                        </div>
                    </div>

                    <!-- Previous Month Report -->
                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="font-bold">Previous Month Approved Amount</h4>
                            <p class="text-xl text-green-500">₱{{ number_format($previousApprovedTotal, 2) }}</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="font-bold">Previous Month Expenses</h4>
                            <p class="text-xl text-red-500">₱{{ number_format($previousTotalExpenses, 2) }}</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="font-bold">Previous Month Extra Charges</h4>
                            <p class="text-xl text-yellow-500">₱{{ number_format($previousExtraCharges, 2) }}</p>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="font-bold">Previous Month Meals Count</h4>
                            <p class="text-xl">{{ $previousTotalMeals }} Meals</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
