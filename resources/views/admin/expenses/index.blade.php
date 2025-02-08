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
            <x-link-button href="{{ route('expenses.create') }}" text="New Expense" classes="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" icon="<i class='fas fa-tachometer-alt'></i>" />

                <!-- User List Table -->
                 <div class="overflow-x-auto">
                <table class="min-w-full text-left table-auto">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Type</th>
                            <th class="px-4 py-2">Amount</th>
                            <th class="px-4 py-2">Description</th>
                            <th class="px-4 py-2">Category</th>
                            <th class="px-4 py-2">Date</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($expenses->isEmpty())
                            <tr>
                                <td colspan="5" class="border px-4 py-2 text-center">No  Expenses found.</td>
                            </tr>
                        @endif
                        <!-- Loop through users (replace this with dynamic content from your backend) -->
                        @foreach ($expenses as $expense)
                            <tr>
                               
           <td class="border px-4 py-2">{{ $expense->type }}</td>   
           <td class="border px-4 py-2"><span>&#x09F3;</span>{{ number_format($expense->amount, 2) }}</td>   
             

                               
                           
                                <td class="border px-4 py-2">{{$expense->description}}</td>
                               
                                
                                <td class="border px-4 py-2">@if($expense->categories)
                                    @foreach($expense->categories as $category)
                                        {{ $category->name }}
                                    @endforeach
                                @endif</td>
                                <td class="border px-4 py-2">{{ $expense->expense_date }}</td>
                                
                                <td class="border px-4 py-2 flex space-x-2">
                                    <!-- Show Button -->
                                    <a href="{{ route('expenses.show', $expense->id) }}" class="text-blue-500 hover:text-blue-700">View</a>
                                    <!-- Edit Button -->
                                    <a href="{{ route('expenses.edit', $expense->id) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <!-- Delete Button -->
                                    <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
</div>
                <!-- Pagination (if needed) -->
                
            </div>
        </div>
    </div>
</div>

</x-app-layout>