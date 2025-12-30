@props(['count' => 3])

<div class="divide-y divide-gray-200 animate-pulse dark:divide-gray-800">
    @for($i = 0; $i < $count; $i++)
    <div class="p-4">
        <div class="flex items-start justify-between mb-4">
            <div class="space-y-2 w-1/2">
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-3/4"></div>
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-1/2"></div>
            </div>
            <div class="h-5 bg-gray-200 rounded-full dark:bg-gray-700 w-16"></div>
        </div>

        <div class="space-y-3 mb-4">
            <div>
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-10 mb-1"></div>
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-16"></div>
            </div>
            <div>
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-16 mb-1"></div>
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-1/2"></div>
            </div>
        </div>

        <div class="flex gap-4 pt-3 border-t border-gray-200 dark:border-gray-800">
            <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-12"></div>
            <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-12"></div>
        </div>
    </div>
    @endfor
</div>
