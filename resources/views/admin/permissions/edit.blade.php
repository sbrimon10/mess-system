<x-app-layout>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
            <h2 class="text-xl font-semibold mb-4">Permission Edit</h2>

                <!-- Category Edit Form -->
                <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="bg-white shadow-md rounded-lg p-6">
            
             <!-- Name -->
             <div class="mt-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name',$permission->name)"  autocomplete="off" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            
            <!-- Gurad Name -->
            <div class="mt-4">
            <x-input-label for="guard_name" :value="__('Guard Name')" />
            <x-text-input id="guard_name" class="block mt-1 w-full" type="text" name="guard_name" :value="old('guard_name',$permission->guard_name)"  autocomplete="off" />
            <x-input-error :messages="$errors->get('guard_name')" class="mt-2" />
            </div>
        
            <div class="mt-4">
            <x-primary-button class="ms-4">
                {{ __('Submit') }}
            </x-primary-button>
            </div>
        </div>
    </form>


                
            </div>
        </div>
    </div>
</div>

</x-app-layout>