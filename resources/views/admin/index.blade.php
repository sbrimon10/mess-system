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
            <!-- Tailwind CSS Table -->
<div class="overflow-x-auto shadow-md sm:rounded-lg">
    <table class="min-w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
            <tr>
                <th scope="col" class="px-6 py-3">Room Number</th>
                <th scope="col" class="px-6 py-3">User Name</th>
                <th scope="col" class="px-6 py-3">Breakfast</th>
                <th scope="col" class="px-6 py-3">Lunch</th>
                <th scope="col" class="px-6 py-3">Dinner</th>
            </tr>
        </thead>
        <tbody>
            @foreach($meals as $userMeals)
                @php
                    // Get the user and room number (using the first meal for the user)
                    $user = $userMeals->first()->user;
                    $roomNumber = $user->userInfo->room_number; // Accessing the room number from the related UsersInfo
                @endphp
                <tr class="bg-white border-b">
                    <td class="px-6 py-4">{{ $roomNumber }}</td> <!-- Room number from UsersInfo -->
                    <td class="px-6 py-4">{{ $user->name }}</td> <!-- Assuming user has a name field -->

                    <!-- Breakfast -->
                    <td class="px-6 py-4">
                    @php
                            $breakfastMeals = $userMeals->where('foodSchedule.meal_type', 'breakfast');
                        @endphp
                        @if($breakfastMeals->isEmpty())
                            <span class="text-red-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
</svg>
</span> <!-- No breakfast meals -->
                        @else
                        @php $totalValue = 0 @endphp
                        <span class="flex">
                            @foreach($breakfastMeals as $meal)
                                @if($meal->will_eat === 'yes')
                                    <span class="text-green-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
</svg>
</span>
                                    @php $totalValue += $meal->foodSchedule->meal_value_multiplier @endphp
                                @else
                                    <span class="text-red-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
</svg>
</span>
                                @endif
                            @endforeach
                            ={{ $totalValue }}
</span>
                        @endif
                    </td>

                    <!-- Lunch -->
                    <td class="px-6 py-4">
                    @php
                            $lunchMeals = $userMeals->where('foodSchedule.meal_type', 'lunch');
                        @endphp
                        @if($lunchMeals->isEmpty())
                            <span class="text-red-500">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
</svg>
</span> <!-- No lunch meals -->
                        @else
                        @php $totalValue = 0 @endphp
                        <span class="flex">
                            @foreach($lunchMeals as $meal)
                                @if($meal->will_eat === 'yes')
                                    <span class="text-green-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
</svg>
</span>
                                    @php $totalValue += $meal->foodSchedule->meal_value_multiplier @endphp
                                @else
                                    <span class="text-red-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
</svg>
</span>
                                @endif
                            @endforeach
                           = {{ $totalValue }}
</span>
                        @endif
                    </td>

                    <!-- Dinner -->
                    <td class="px-6 py-4">
                    @php
                            $dinnerMeals = $userMeals->where('foodSchedule.meal_type', 'dinner');
                        @endphp
                        @if($dinnerMeals->isEmpty())
                            <span class="text-red-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
</svg>
</span> <!-- No dinner meals -->
                        @else
                        @php $totalValue = 0 @endphp 
                        <span class="flex">   
                            @foreach($dinnerMeals as $meal)
                                @if($meal->will_eat === 'yes')
                                    <span class="text-green-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
</svg>
</span>
                                    @php $totalValue += $meal->foodSchedule->meal_value_multiplier @endphp
                                @else
                                    <span class="text-red-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
</svg>
</span>
                                @endif
                            @endforeach
                            = {{ $totalValue }}
</span>
                        @endif
                        
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals Row -->
    <div class="px-6 py-4 bg-gray-200 text-right">
        <p><strong>Total Breakfasts: </strong>{{ $totalBreakfast }}</p>
        <p><strong>Total Lunches: </strong>{{ $totalLunch }}</p>
        <p><strong>Total Dinners: </strong>{{ $totalDinner }}</p>
    </div>
</div>


                <!-- Pagination (if needed) -->
                
            </div>
        </div>
    </div>
</div>

</x-app-layout>