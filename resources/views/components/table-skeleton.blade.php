@props([
    'rows' => 5,
    'columns' => 3,
    'headers' => false, // Option to show a header row
    'colSizes' => []    // Custom widths: ['w-1/2', 'w-1/6', ...]
])

@php
    // If colSizes provided, use that count; otherwise use $columns
    $loopCount = count($colSizes) > 0 ? count($colSizes) : $columns;
@endphp

<div {{ $attributes->merge(['class' => 'animate-pulse w-full']) }}>

    {{-- Optional: Skeleton Header Row --}}
    @if($headers)
    <div class="flex items-center gap-4 px-6 py-3 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
        @for($j = 0; $j < $loopCount; $j++)
        <div class="{{ $colSizes[$j] ?? 'flex-1' }}">
            <div class="h-3 bg-gray-300 rounded dark:bg-gray-600 w-24"></div>
        </div>
        @endfor
    </div>
    @endif

    {{-- Body Rows --}}
    @for($i = 0; $i < $rows; $i++)
    <div class="flex items-center gap-4 px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        @for($j = 0; $j < $loopCount; $j++)
        <div class="{{ $colSizes[$j] ?? 'flex-1' }}">
            {{-- Randomize width slightly for organic feel --}}
            <div class="h-4 bg-gray-200 rounded dark:bg-gray-700"
                 style="width: {{ rand(50, 90) }}%">
            </div>
        </div>
        @endfor
    </div>
    @endfor
</div>
