<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('role List') }}
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
            

                <!-- rolepermission List Table -->
                 <div class="overflow-x-auto">
                <table class="min-w-full text-left table-auto">
                    <thead>
                        <tr>
                        <th class="px-4 py-2">SL</th>
                        <th class="px-4 py-2">Roles</th>
                        <th class="px-4 py-2">Guard</th>
                        <th class="px-4 py-2">Date</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if ($roles->isEmpty())
                            <tr>
                                <td colspan="5" class="border px-4 py-2 text-center">No  Roles found.</td>
                            </tr>
                        @endif
                        <!-- Loop through users (replace this with dynamic content from your backend) -->
                        @foreach ($roles as $role)
                            <tr>
                               
           <td class="border px-4 py-2">{{$role->id}}</td> 

                               
                           
                                <td class="border px-4 py-2">{{$role->name}}</td>
                                <td class="border px-4 py-2">{{$role->guard_name}}</td>
                               
                                <td class="border px-4 py-2">{{$role->created_at }}</td>
                                
                                <td class="border px-4 py-2 flex space-x-2">
                                <!-- Edit Button -->
                                <a href="{{ route('admin.roles.permissions.edit', $role->id) }}" class="text-blue-500 hover:text-blue-700">Edit</a>

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