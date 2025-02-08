<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Food Preference List') }}
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
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-3">
    <div class="p-6 text-gray-900 dark:text-gray-100">
     <!-- Filter Form -->
     <form method="GET" action="{{ route('admin.deposits.index') }}" class="mb-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-lg">
    <div class="flex space-x-4">
        <!-- Status Filter -->
        <select name="status" class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white p-2 rounded focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
            <option value="">All Statuses</option>
            <option value="pending" @if(request('status') === 'pending') selected @endif>Pending</option>
            <option value="approved" @if(request('status') === 'approved') selected @endif>Approved</option>
            <option value="rejected" @if(request('status') === 'rejected') selected @endif>Rejected</option>
        </select>

        <!-- User Filter -->
        <select name="user_id" class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white p-2 rounded focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
            <option value="">All Users</option>
            @foreach($users as $user) <!-- Assuming you have an array of users to filter by -->
                <option value="{{ $user->id }}" @if(request('user_id') == $user->id) selected @endif>{{ $user->name }}</option>
            @endforeach
        </select>

        <!-- Month and Year Filter -->
        <select name="month" class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white p-2 rounded focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
            <option value="">All Months</option>
            @foreach(range(1, 12) as $month)
                <option value="{{ $month }}" @if(request('month') == $month) selected @endif>{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
            @endforeach
        </select>

        <select name="year" class="border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-white p-2 rounded focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
            <option value="">All Years</option>
            @foreach(range(2020, date('Y')) as $year) <!-- Adjust year range as needed -->
                <option value="{{ $year }}" @if(request('year') == $year) selected @endif>{{ $year }}</option>
            @endforeach
        </select>

        <!-- Submit Button -->
        <button type="submit" class="bg-blue-500 dark:bg-blue-600 text-white p-2 rounded hover:bg-blue-400 dark:hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500">
            Filter
        </button>
    </div>
</form>

<!-- Display Active Filters -->
<div class="mb-4">
                <h4 class="font-semibold text-lg text-gray-800 dark:text-gray-200">
                    Applied Filters:
                    @if ($filters['status']) Status: {{ ucfirst($filters['status']) }} | @endif
                    @if ($filters['user_id']) User: {{ $filters['user_id'] }} | @endif
                    @if ($filters['month']) Month: {{ DateTime::createFromFormat('!m', $filters['month'])->format('F') }} | @endif
                    @if ($filters['year']) Year: {{ $filters['year'] }} | @endif
                </h4>
            </div>
</div></div>
            
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
            <x-link-button href="{{ route('admin.deposits.create') }}" text="New Deposit" classes="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded" icon="<i class='fas fa-tachometer-alt'></i>" />
    <div class="overflow-x-auto">
                <!-- User List Table -->
                 <div class="overflow-x-auto">
                <table class="min-w-full text-left table-auto">
                    <thead>
                        <tr>
                        @if(!$deposits->isEmpty() && Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin'))
                        <th class="px-4 py-2">Name</th> <!-- Admins see the user's name -->
        @endif
                    <th class="px-4 py-2">Amount</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Rejection Comment</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($deposits->isEmpty())
                            <tr>
                                <td colspan="5" class="border px-4 py-2 text-center">No  preferences found.</td>
                            </tr>
                        @endif
                        <!-- Loop through users (replace this with dynamic content from your backend) -->
                        @foreach ($deposits as $deposit)
                        <tr>
                             
                        @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('super-admin'))
           <td class="border px-4 py-2">{{ucfirst($deposit->user->roles->first()->name)}}: {{ $deposit->user->name }}</td>   <!-- Admins see the user's name -->
        @endif
           <td class="border px-4 py-2"><span class="flex"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 7.5.415-.207a.75.75 0 0 1 1.085.67V10.5m0 0h6m-6 0h-1.5m1.5 0v5.438c0 .354.161.697.473.865a3.751 3.751 0 0 0 5.452-2.553c.083-.409-.263-.75-.68-.75h-.745M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
</svg>
{{ number_format($deposit->amount, 2) }}</span></td>   <!-- Admins see the user's name -->

                               
                           
                                <td class="border px-4 py-2">@if($deposit->status === 'pending')
                                   <span class="text-yellow-500"> Pending</span>
                                @elseif($deposit->status === 'approved')
                                    <span class="text-green-500"> Approved by {{ $deposit->admin_approved_by ? $deposit->approvedBy->name : 'N/A' }}</span>
                                @elseif($deposit->status === 'rejected')
                                  <span class="text-red-500"> Rejected</span>
                                @endif</td>
                               
                                
                                <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($deposit->deposited_at)->format('Y-m-d h:i A') }}
                                </td>
                                <td class="border px-4 py-2">@if($deposit->status === 'rejected')
                                    <p>{{ $deposit->rejection_comment ?? 'No comment provided' }}</p>
                                @else
                                    <p>N/A</p>
                                @endif</td>
                                
                                <td class="border px-4 py-2">
                                @if($deposit->status === 'pending')
                                <a href="{{ route('admin.deposits.review', $deposit) }}" class="text-blue-600 hover:text-blue-800">Review</a>
                            @else
                                <span class="text-gray-500">N/A</span>
                            @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
</div>
<!-- Bottom summary: Total approved amount for selected month -->
<div class="mt-4">
    <div class="text-gray-900 dark:text-gray-100">
        <h4 class="font-semibold text-lg">Total Approved Amount for Selected Month:</h4>
        <p class="flex text-lg font-bold"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 7.5.415-.207a.75.75 0 0 1 1.085.67V10.5m0 0h6m-6 0h-1.5m1.5 0v5.438c0 .354.161.697.473.865a3.751 3.751 0 0 0 5.452-2.553c.083-.409-.263-.75-.68-.75h-.745M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
</svg>{{ number_format($totalApprovedAmount, 2) }}</p> <!-- Total for the selected month -->
    </div>

    <div class="text-gray-900 dark:text-gray-100 mt-2">
        <h4 class="font-semibold text-lg">Total Approved Amount for Current Page:</h4>
        <p class="flex text-lg font-bold"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 7.5.415-.207a.75.75 0 0 1 1.085.67V10.5m0 0h6m-6 0h-1.5m1.5 0v5.438c0 .354.161.697.473.865a3.751 3.751 0 0 0 5.452-2.553c.083-.409-.263-.75-.68-.75h-.745M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
</svg>{{ number_format($currentPageApprovedAmount, 2) }}</p> <!-- Total for the current page -->
    </div>
</div>
                <!-- Pagination (if needed) -->
                <div class="mt-4">
                    {{ $deposits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>