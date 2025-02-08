<x-app-layout>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
            <h1 class="text-2xl font-bold mb-4">Assign Permissions to Role: {{ $role->name }}</h1>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.roles.permissions.update', $role->id) }}" method="POST">
        @csrf
        @method('POST')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($permissions as $permission)
                <div class="flex items-center">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                        id="permission-{{ $permission->id }}"
                        @if(in_array($permission->id, $rolePermissions)) checked @endif
                        class="form-checkbox text-blue-600 h-5 w-5">
                    <label for="permission-{{ $permission->id }}" class="ml-2 text-lg">{{ $permission->name }}</label>
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">Update Permissions</button>
        </div>
    </form>


                
            </div>
        </div>
    </div>
</div>

</x-app-layout>