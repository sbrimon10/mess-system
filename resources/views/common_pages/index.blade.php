<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        {{ __($user->name . "'s Meals") }}
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


    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="px-6 py-4">
            <h3 class="text-lg font-semibold">Total Meals Summary</h3>
            <div class="grid grid-cols-3 gap-4 mt-4">
                <!-- Eaten Meals -->
                <div class="text-center">
                    <div class="text-sm font-medium text-gray-500">Eaten Meals</div>
                    <div class="text-xl font-bold text-green-600">{{ $eatenMeals }}</div>
                </div>

                <!-- Will Eat Meals -->
                <div class="text-center">
                    <div class="text-sm font-medium text-gray-500">Will Eat Meals</div>
                    <div class="text-xl font-bold text-yellow-600">{{ $willEatMeals }}</div>
                </div>

                <!-- Total Meals -->
                <div class="text-center">
                    <div class="text-sm font-medium text-gray-500">Total Meals</div>
                    <div class="text-xl font-bold text-blue-600">{{ $totalMeals }}</div>
                </div>
            </div>
        </div>
    </div>

            <!-- Tailwind CSS Table -->
            <div class="overflow-x-auto shadow-md sm:rounded-lg">
    <table class="min-w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-100">
            <tr>
                <th scope="col" class="px-6 py-3">Date</th>
                <th scope="col" class="px-6 py-3">Breakfast</th>
                <th scope="col" class="px-6 py-3">Lunch</th>
                <th scope="col" class="px-6 py-3">Dinner</th>
                <th scope="col" class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Generate an array of all days in the month
                $daysInMonth = \Carbon\Carbon::parse($startOfMonth)->daysInMonth;
                $today = \Carbon\Carbon::today();
                $yesterday = \Carbon\Carbon::yesterday();
                $tomorrow = \Carbon\Carbon::tomorrow();
            @endphp

            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    // Create a Carbon date for each day in the month
                    $date = \Carbon\Carbon::parse($startOfMonth)->setDay($day);
                    $mealsForDay = $meals->where('meal_date', $date->toDateString());
                @endphp
                
                <tr class="{{ $date->isToday() ? 'bg-yellow-100' : ($date->isYesterday() ? 'bg-gray-300' : ($date->isTomorrow() ? 'bg-blue-100' : 'bg-white')) }} border-b">
                    <td class="px-6 py-4">{{ $date->format('l, F j, Y') }}</td> <!-- Display the full date -->
                    
                    <!-- Breakfast -->
                    <td class="px-6 py-4">
                        @if ($mealsForDay->where('foodSchedule.meal_type', 'breakfast')->isEmpty())
                            <span class="text-red-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
</svg>
</span>
                        @else
                            @php $totalValue = 0 @endphp
                            <span class="flex">
                            @foreach ($mealsForDay->where('foodSchedule.meal_type', 'breakfast')  as $index => $meal)
                                @if ($meal->will_eat === 'yes')
                                @php
            $totalValue += $meal->foodSchedule->meal_value_multiplier;
        @endphp
                                    <span class="text-green-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
</svg>
</span>
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

                    <!-- Lunch -->
                    <td class="px-6 py-4">
                        @if ($mealsForDay->where('foodSchedule.meal_type', 'lunch')->isEmpty())
                            <span class="text-red-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
</svg>
</span>
                        @else
                        @php $totalValue = 0 @endphp
                        <span class="flex">
                            @foreach ($mealsForDay->where('foodSchedule.meal_type', 'lunch') as $index => $meal)
                                @if ($meal->will_eat === 'yes')
                                    @php $totalValue += $meal->foodSchedule->meal_value_multiplier @endphp
                                    <span class="text-green-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
</svg>
</span>
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
                        @if ($mealsForDay->where('foodSchedule.meal_type', 'dinner')->isEmpty())
                            <span class="text-red-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
</svg>
</span>
                        @else
                        @php $totalValue = 0 @endphp
                        <span class="flex">
                            @foreach ($mealsForDay->where('foodSchedule.meal_type', 'dinner') as $index => $meal)
                                @if ($meal->will_eat === 'yes')
                                    @php $totalValue += $meal->foodSchedule->meal_value_multiplier @endphp
                                    <span class="text-green-500"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
</svg>
</span>
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
                    <td class="px-6 py-4"><a href="{{ route('meals.editmealsbydate', $date->toDateString()) }}" class="text-blue-500 hover:text-blue-700"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
</svg>
</a></td>
                </tr>
            @endfor
        </tbody>
    </table>
</div>




                <!-- Pagination (if needed) -->
                
            </div>
        </div>
    </div>
</div>

</x-app-layout>