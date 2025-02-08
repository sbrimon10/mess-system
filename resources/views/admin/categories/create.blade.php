<x-app-layout>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
            <h2 class="text-xl font-semibold mb-4">Category Creation</h2>

                <!-- Category Creation Form -->
                <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="bg-white shadow-md rounded-lg p-6">
            
            <!-- Name -->
            <div class="mt-4">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"  autocomplete="off" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <!-- Description -->
            <div class="mt-4">
                <x-input-label for="description" :value="__('Description')" />
                <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')"  autocomplete="off" />
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
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