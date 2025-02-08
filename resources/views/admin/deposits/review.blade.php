<x-app-layout>

    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    @if(session('success'))
    <x-alert type="success" message="{{ session('success') }}" dismissable="true" />
    @endif
    @if(session('error'))
    <x-alert type="error" message="{{ session('error') }}" dismissable="true" />
    @endif
    

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="container mx-auto py-6">
            
            <h1 class="text-2xl font-semibold mb-4">Review Deposit Request</h1>

<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-xl font-semibold mb-4">Deposit Information</h2>
    <p><strong>User:</strong> {{ $deposit->user->name }}</p>
    <p><strong>Amount:</strong> ${{ number_format($deposit->amount, 2) }}</p>
    <p><strong>Payment Method:</strong> {{ $deposit->payment_method ?? 'N/A' }}</p>
    <p><strong>Deposited At:</strong> {{ $deposit->deposited_at ? $deposit->deposited_at : 'N/A' }}</p>

    <h3 class="text-lg font-semibold mt-6 mb-4">Deposit Status</h3>
    <p class="text-sm">
        <span class="px-2 py-1 text-sm font-semibold rounded-lg
            @if($deposit->status === 'pending') bg-yellow-100 text-yellow-800 @endif
            @if($deposit->status === 'approved') bg-green-100 text-green-800 @endif
            @if($deposit->status === 'rejected') bg-red-100 text-red-800 @endif">
            {{ ucfirst($deposit->status) }}
        </span>
    </p>

    @if($deposit->status === 'rejected')
        <h3 class="mt-4 text-lg font-semibold">Rejection Comment</h3>
        <p>{{ $deposit->rejection_comment ?? 'No comment provided' }}</p>
    @endif

    @if($deposit->status === 'pending')
        <div class="mt-6">
            <form action="{{ route('admin.deposits.approve', $deposit) }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Approve</button>
            </form>
            
            <form action="{{ route('admin.deposits.reject', $deposit) }}" method="POST" class="inline-block ml-4">
                @csrf
                <textarea name="rejection_comment" class="block w-full mt-2 p-2 border border-gray-300 rounded-md" placeholder="Provide a rejection comment..." required></textarea>
                <button type="submit" class="px-4 py-2 mt-4 bg-red-600 text-white rounded-md hover:bg-red-700">Reject</button>
            </form>
        </div>
    @endif
</div>
                
            </div>
        </div>
    </div>
</div>

</x-app-layout>