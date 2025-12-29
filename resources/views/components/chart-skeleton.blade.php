<div class="animate-pulse space-y-3">
    <!-- Title skeleton -->
    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/3"></div>

    <!-- Chart area skeleton -->
    <div class="relative" style="height: 250px;">
        <div class="absolute inset-0 flex items-end justify-around px-4 pb-4">
            @for($i = 0; $i < 7; $i++)
                <div class="w-full mx-1 bg-gray-200 dark:bg-gray-700 rounded-t"
                     style="height: {{ rand(30, 90) }}%;"></div>
            @endfor
        </div>
    </div>

    <!-- Legend skeleton -->
    <div class="flex gap-4">
        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
        <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-24"></div>
    </div>
</div>
