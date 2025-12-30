{{-- resources/views/components/chart-skeleton.blade.php --}}
@props(['height' => '250px'])

<div class="animate-pulse" style="height: {{ $height }}">
    <!-- Simulated chart bars/lines -->
    <div class="flex items-end justify-around h-full gap-2 p-4">
        <div class="w-full bg-gray-200 rounded-t dark:bg-gray-700" style="height: 60%"></div>
        <div class="w-full bg-gray-200 rounded-t dark:bg-gray-700" style="height: 85%"></div>
        <div class="w-full bg-gray-200 rounded-t dark:bg-gray-700" style="height: 45%"></div>
        <div class="w-full bg-gray-200 rounded-t dark:bg-gray-700" style="height: 70%"></div>
        <div class="w-full bg-gray-200 rounded-t dark:bg-gray-700" style="height: 55%"></div>
        <div class="w-full bg-gray-200 rounded-t dark:bg-gray-700" style="height: 90%"></div>
        <div class="w-full bg-gray-200 rounded-t dark:bg-gray-700" style="height: 40%"></div>
    </div>

    <!-- Simulated axis labels -->
    <div class="flex justify-around px-4 mt-2">
        <div class="w-8 h-2 bg-gray-200 rounded dark:bg-gray-700"></div>
        <div class="w-8 h-2 bg-gray-200 rounded dark:bg-gray-700"></div>
        <div class="w-8 h-2 bg-gray-200 rounded dark:bg-gray-700"></div>
        <div class="w-8 h-2 bg-gray-200 rounded dark:bg-gray-700"></div>
    </div>
</div>
