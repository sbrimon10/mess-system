<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Category List') }}
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
            <x-link-button href="{{ route('categories.create') }}" text="New Category" classes="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" icon="<i class='fas fa-tachometer-alt'></i>" />

                <!-- Category List Table -->
                 <div class="overflow-x-auto">
                <table class="min-w-full text-left table-auto">
                    <thead>
                        <tr>
                        <th class="px-4 py-2">SL</th>
                        <th class="px-4 py-2">Category</th>
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Created by</th>
                        <th class="px-4 py-2">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if ($categories->isEmpty())
                            <tr>
                                <td colspan="5" class="border px-4 py-2 text-center">No  Categories found.</td>
                            </tr>
                        @endif
                        <!-- Loop through users (replace this with dynamic content from your backend) -->
                        @foreach ($categories as $category)
                            <tr>
                               
           <td class="border px-4 py-2">{{$category->id}}</td>   <!-- Admins see the user's name -->

                               
                           
                                <td class="border px-4 py-2">{{$category->name}}</td>
                                <td class="border px-4 py-2">{{$category->description}}</td>
                                <td class="border px-4 py-2">@if($category->users->isNotEmpty())
                        {{ $category->users->first()->name }}  <!-- Display the name of the first user (the creator) -->
                    @else
                        Unknown
                    @endif</td>
                               
                                
                                <td class="border px-4 py-2">{{$category->created_at }}</td>
                                
                                <td class="border px-4 py-2 flex space-x-2">
                                <!-- Edit Button -->
                                <a href="{{ route('categories.edit', $category->id) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <!-- Delete Button -->
                                    <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?')" class="inline">
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
                <div class="mt-4">
                    {{ $categories->links() }}
                </div>
                
            </div>
        </div>
    </div>
</div>

</x-app-layout>