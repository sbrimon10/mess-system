<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-xl font-semibold mb-4">Admin Histories</h2>
            <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-800 table-auto">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2">User</th>
                                <th class="px-4 py-2">Start Date</th>
                                <th class="px-4 py-2">End Date</th>
                                <th class="px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($adminHistories as $history)
                                <tr class="{{ $history->admin_end_date ? 'bg-white' : 'bg-yellow-100' }}">
                                    <td class="px-4 py-2">{{ $history->user->name }}</td>
                                    <td class="px-4 py-2">@if($history->admin_start_date)
                    {{ \Carbon\Carbon::parse($history->admin_start_date)->format('M d, Y') }}
                @else
                    N/A
                @endif</td>
                                    <td class="px-4 py-2">
                                    @if($history->admin_end_date)
                    {{ \Carbon\Carbon::parse($history->admin_end_date)->format('M d, Y') }}
                @else
                    <span class="text-green-500">Current Admin</span>
                @endif
                                    </td>
                                    <td class="px-4 py-2">
                                        <a href="{{ route('admin_histories.edit', $history) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                        <!-- Delete Button -->
                <form action="{{ route('admin_histories.destroy', $history) }}" method="POST" class="inline-block ml-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
</div>
                    <div class="mt-4">
                        <a href="{{ route('admin_histories.create') }}" class="inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                            Create New Admin History
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
