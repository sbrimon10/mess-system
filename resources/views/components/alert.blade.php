@props(['type', 'message', 'dismissable' => false])
@php
$getType = '';

switch ($type) {
    case 'success':
        $getType = 'bg-green-100 border border-green-400 text-green-700';
        break;  // Break added here
    case 'error':
        $getType = 'bg-red-100 border border-red-400 text-red-700';
        break;  // Break added here
    case 'warning':
        $getType = 'bg-yellow-100 border border-yellow-400 text-yellow-700';
        break;  // Break added here
    case 'info':
        $getType = 'bg-blue-100 border border-blue-400 text-blue-700';
        break;  // Break added here
    default:
        $getType = 'bg-green-100 border border-green-400 text-green-700'; // default to success
        break;  // Break added here
}
@endphp
<div 
    class="alert {{ $getType }} {{ $dismissable ? 'relative' : '' }} p-4 rounded-md mb-4"
    role="alert"
>
    @if ($dismissable)
        <button 
            type="button"
            class="absolute top-0 right-0 p-2 text-gray-600 hover:text-gray-900"
            aria-label="Close"
            onclick="this.parentElement.style.display='none';"
        >
            &times;
        </button>
    @endif

    <div class="font-semibold">
        {{ $message }}
    </div>
</div>