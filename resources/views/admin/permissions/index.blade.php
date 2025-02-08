<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Permission List') }}
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
            <x-link-button href="{{ route('permissions.create') }}" text="New Permission" classes="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" icon="<i class='fas fa-tachometer-alt'></i>" />

                <!-- permi$permission List Table -->
                 <div class="overflow-x-auto">
                <table class="min-w-full text-left table-auto">
                    <thead>
                        <tr>
                        <th class="px-4 py-2">SL</th>
                        <th class="px-4 py-2">Permission</th>
                        <th class="px-4 py-2">Guard</th>
                        <th class="px-4 py-2">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if ($permissions->isEmpty())
                            <tr>
                                <td colspan="5" class="border px-4 py-2 text-center">No  Permissions found.</td>
                            </tr>
                        @endif
                        <!-- Loop through users (replace this with dynamic content from your backend) -->
                        @foreach ($permissions as $permission)
                            <tr>
                               
           <td class="border px-4 py-2">{{$permission->id}}</td> 

                               
                           
                                <td class="border px-4 py-2">{{$permission->name}}</td>
                                <td class="border px-4 py-2">{{$permission->guard_name}}</td>
                               
                                <td class="border px-4 py-2">{{$permission->created_at }}</td>
                                
                                <td class="border px-4 py-2 flex space-x-2">
                                <!-- Edit Button -->
                                <a href="{{ route('permissions.edit', $permission->id) }}" class="text-blue-500 hover:text-blue-700">Edit</a>
                                    <!-- Delete Button -->
                                    <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this permission')" class="inline">
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
                
                
            </div>
        </div>
    </div>
</div>

</x-app-layout>