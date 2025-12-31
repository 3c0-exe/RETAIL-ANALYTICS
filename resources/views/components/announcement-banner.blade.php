@php
    $announcements = \App\Models\SystemAnnouncement::getActiveForUser();
@endphp

@foreach($announcements as $announcement)
<div id="announcement-{{ $announcement->id }}"
     x-data="{ show: true }"
     x-show="show"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform -translate-y-2"
     class="relative px-4 py-3 mb-4 border rounded-lg
        {{ $announcement->type === 'info' ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800' : '' }}
        {{ $announcement->type === 'success' ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : '' }}
        {{ $announcement->type === 'warning' ? 'bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800' : '' }}
        {{ $announcement->type === 'error' ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' : '' }}">

    <div class="flex items-start gap-3">
        <!-- Icon -->
        <div class="flex-shrink-0 mt-0.5
            {{ $announcement->type === 'info' ? 'text-blue-600 dark:text-blue-400' : '' }}
            {{ $announcement->type === 'success' ? 'text-green-600 dark:text-green-400' : '' }}
            {{ $announcement->type === 'warning' ? 'text-yellow-600 dark:text-yellow-400' : '' }}
            {{ $announcement->type === 'error' ? 'text-red-600 dark:text-red-400' : '' }}">
            @if($announcement->type === 'info')
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
            @elseif($announcement->type === 'success')
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
            @elseif($announcement->type === 'warning')
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            @else
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
            @endif
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <h3 class="text-sm font-semibold
                {{ $announcement->type === 'info' ? 'text-blue-900 dark:text-blue-200' : '' }}
                {{ $announcement->type === 'success' ? 'text-green-900 dark:text-green-200' : '' }}
                {{ $announcement->type === 'warning' ? 'text-yellow-900 dark:text-yellow-200' : '' }}
                {{ $announcement->type === 'error' ? 'text-red-900 dark:text-red-200' : '' }}">
                {{ $announcement->title }}
            </h3>
            <p class="mt-1 text-sm
                {{ $announcement->type === 'info' ? 'text-blue-800 dark:text-blue-300' : '' }}
                {{ $announcement->type === 'success' ? 'text-green-800 dark:text-green-300' : '' }}
                {{ $announcement->type === 'warning' ? 'text-yellow-800 dark:text-yellow-300' : '' }}
                {{ $announcement->type === 'error' ? 'text-red-800 dark:text-red-300' : '' }}">
                {{ $announcement->message }}
            </p>
        </div>

        <!-- Dismiss Button -->
        @if($announcement->is_dismissible)
        <button @click="dismissAnnouncement({{ $announcement->id }}); show = false"
                class="flex-shrink-0 p-1 rounded-md hover:bg-black/5 dark:hover:bg-white/5
                    {{ $announcement->type === 'info' ? 'text-blue-600 dark:text-blue-400' : '' }}
                    {{ $announcement->type === 'success' ? 'text-green-600 dark:text-green-400' : '' }}
                    {{ $announcement->type === 'warning' ? 'text-yellow-600 dark:text-yellow-400' : '' }}
                    {{ $announcement->type === 'error' ? 'text-red-600 dark:text-red-400' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        @endif
    </div>
</div>
@endforeach

<script>
function dismissAnnouncement(announcementId) {
    fetch(`/admin/announcements/${announcementId}/dismiss`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    });
}
</script>
