<x-app-layout>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
            <h2 class="text-xl font-semibold mb-4">User Creation</h2>

                <!-- Food Schedule Creation Form -->
                <form action="{{ route('expenses.store') }}" method="POST">
        @csrf
        <div class="bg-white shadow-md rounded-lg p-6">
            
            <!-- Expense Type -->
            <div class="mt-4">
            <x-input-label for="type" :value="__('Expense Type')" />
            <x-text-input id="type" class="block mt-1 w-full" type="text" name="type" :value="old('type')"  autocomplete="off" />
            <x-input-error :messages="$errors->get('type')" class="mt-2" />
        </div>
        <!-- Amount -->
        <div class="mt-4">
            <x-input-label for="amount" :value="__('Amount')" />
            <x-text-input id="amount" class="block mt-1 w-full" type="text" name="amount" :value="old('amount')"  autocomplete="off" />
            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
        </div>
        <!-- Description -->
        <div class="mt-4">
            <x-input-label for="description" :value="__('Description')" />
            <x-text-input id="description" class="block mt-1 w-full" type="text" name="description" :value="old('description')"  autocomplete="off" />
            <x-input-error :messages="$errors->get('description')" class="mt-2" />
        </div>
        
        <!-- Date -->
        <div class="mt-4">
            <x-input-label for="date" :value="__('Date')" />
            <x-text-input id="date" class="block mt-1 w-full" type="date" name="expense_date" :value="old('expense_date')"  autocomplete="off" />
            <x-input-error :messages="$errors->get('expense_date')" class="mt-2" />
        </div>
        
        <!-- Category -->

        <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                <select name="category_id" id="category_id" class="form-select block w-full mt-1">
                <option value="">None</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }} _ {{ $category->users->first()->name??'' }}</option>
            @endforeach
        </select>
        </div>
        <livewire:multi-user-search />
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
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