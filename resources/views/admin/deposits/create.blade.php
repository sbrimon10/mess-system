<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-xl font-semibold mb-4">Create Deposit on Behalf of User</h2>

                    <!-- Deposit Creation Form -->
                    <form action="{{ route('admin.deposits.store') }}" method="POST">
                        @csrf

                        <div class="bg-white shadow-md rounded-lg p-6">
 <!-- Livewire User Search Component -->
 <div class="mt-4">
                                <x-input-label for="user_id" :value="__('User')" />
                                <livewire:user-search />  <!-- Ensure this is correctly placed -->
                                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                            </div>

                            <!-- Amount -->
                            <div class="mt-4">
                                <x-input-label for="amount" :value="__('Amount')" />
                                <x-text-input id="amount" class="block mt-1 w-full" type="number" name="amount" :value="old('amount')" step="0.01" min="0" required autocomplete="off" />
                                <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                            </div>
                            <div class="flex items-center space-x-4">
                            <!-- deposit_date -->
                            <div class="mt-4">
                                <x-input-label for="deposit_date" :value="__('deposit_date')" />
                                <x-text-input id="deposit_date" class="border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" type="date" name="deposit_date" :value="old('deposit_date')" step="0.01" min="0" required autocomplete="off" />
                                <x-input-error :messages="$errors->get('deposit_date')" class="mt-2" />
                            </div>
                            <!-- deposit_time -->
                            <div class="mt-4">
                                <x-input-label for="deposit_time" :value="__('deposit_time')" />
                                <x-text-input id="deposit_time" class="border border-gray-300 rounded-lg p-2 focus:outline-none focus:ring-2 focus:ring-blue-500" type="time" name="deposit_time" :value="old('deposit_time')" required autocomplete="off" />
                                <x-input-error :messages="$errors->get('deposit_time')" class="mt-2" />
                            </div>
                            </div>
                            
                            <!-- Payment Method -->
                            <div class="mt-4">
                                <x-input-label for="payment_method" :value="__('Payment Method')" />
                                <x-text-input id="payment_method" class="block mt-1 w-full" type="text" name="payment_method" :value="old('payment_method')" required autocomplete="off" />
                                <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
                            </div>

                            <div class="mt-4">
                                <x-primary-button>
                                    {{ __('Create and Approve Deposit') }}
                                </x-primary-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
