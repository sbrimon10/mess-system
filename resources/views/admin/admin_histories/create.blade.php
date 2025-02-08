<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-xl font-semibold mb-4">Create Admin History</h2>

                    <!-- Admin History Creation Form -->
                    <form action="{{ route('admin_histories.store') }}" method="POST">
                        @csrf
                        <div class="bg-white shadow-md rounded-lg p-6">

                            <!-- User Selection -->
                            <div class="mt-4">
                                <x-input-label for="user_id" :value="__('Select User')" />
                                <select id="user_id" name="user_id" class="form-select block w-full mt-1">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" 
                                            @if($user->is_current_admin) selected @endif>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                            </div>

                            <!-- Start Date -->
                            <div class="mt-4">
                                <x-input-label for="admin_start_date" :value="__('Admin Start Date')" />
                                <x-text-input id="admin_start_date" class="block mt-1 w-full" type="date" name="admin_start_date" :value="old('admin_start_date')" autocomplete="off" />
                                <x-input-error :messages="$errors->get('admin_start_date')" class="mt-2" />
                            </div>

                            <!-- End Date (nullable) -->
                            <div class="mt-4">
                                <x-input-label for="admin_end_date" :value="__('Admin End Date (Optional)')" />
                                <x-text-input id="admin_end_date" class="block mt-1 w-full" type="date" name="admin_end_date" :value="old('admin_end_date')" autocomplete="off" />
                                <x-input-error :messages="$errors->get('admin_end_date')" class="mt-2" />
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-4">
                                <x-primary-button>
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
