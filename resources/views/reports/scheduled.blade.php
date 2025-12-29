<x-app-layout>
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <nav class="mb-4 flex" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-purple-600 dark:text-gray-400">
                Dashboard
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 mx-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path>
                </svg>
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Scheduled Reports</span>
            </div>
        </li>
    </ol>
</nav>
        <!-- Page Header -->
        <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-gray-100">
                    Scheduled Reports
                </h1>
                <p class="mt-1 text-sm text-gray-600 sm:mt-2 dark:text-gray-400">
                    Automate your reports with daily, weekly, or monthly email delivery
                </p>
            </div>
            <button onclick="openCreateModal()" class="inline-flex items-center justify-center w-full gap-2 px-4 py-2 text-sm font-medium text-white transition-all duration-200 rounded-md sm:w-auto bg-primary-600 hover:bg-primary-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Schedule
            </button>
        </div>

        <!-- Scheduled Reports List -->
        <div class="space-y-4">
            @forelse($scheduledReports as $report)
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <!-- Report Info -->
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $report->name }}
                            </h3>
                            @if($report->is_active)
                            <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">
                                Active
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-900/20 dark:text-gray-400">
                                Paused
                            </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 gap-2 text-sm text-gray-600 sm:grid-cols-3 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>{{ ucfirst($report->report_type) }} Report</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>{{ ucfirst($report->frequency) }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>{{ $report->time->format('g:i A') }}</span>
                            </div>
                        </div>

                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <strong>Recipients:</strong> {{ implode(', ', array_slice($report->recipients, 0, 2)) }}
                            @if(count($report->recipients) > 2)
                                <span>+{{ count($report->recipients) - 2 }} more</span>
                            @endif
                        </div>

                        @if($report->next_run_at)
                        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <strong>Next Run:</strong> {{ $report->next_run_at->format('M d, Y \a\t g:i A') }}
                        </div>
                        @endif

                        @if($report->last_run_at)
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <strong>Last Sent:</strong> {{ $report->last_run_at->diffForHumans() }}
                        </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <button onclick="toggleReport({{ $report->id }})"
                                class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700">
                            @if($report->is_active)
                                Pause
                            @else
                                Resume
                            @endif
                        </button>
                        <button onclick="sendNow({{ $report->id }})"
                                class="px-3 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700">
                            Send Now
                        </button>
                        <button onclick="viewLogs({{ $report->id }})"
                                class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700">
                            History
                        </button>
                        <button onclick="deleteReport({{ $report->id }})"
                                class="px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                            Delete
                        </button>
                    </div>
                </div>

                <!-- Recent Logs -->
                @if($report->logs->count() > 0)
                <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                    <h4 class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Recent Activity</h4>
                    <div class="space-y-1">
                        @foreach($report->logs->take(3) as $log)
                        <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                            @if($log->status === 'success')
                                <span class="text-green-600 dark:text-green-400">✓</span>
                            @else
                                <span class="text-red-600 dark:text-red-400">✗</span>
                            @endif
                            <span>{{ $log->sent_at->format('M d, Y g:i A') }}</span>
                            <span>•</span>
                            <span>{{ $log->recipient_count }} recipient(s)</span>
                            @if($log->status === 'failed')
                                <span class="text-red-600 dark:text-red-400">- {{ $log->error_message }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-12 bg-white border border-gray-200 rounded-lg dark:bg-[#171717] dark:border-gray-800">
                <svg class="w-16 h-16 mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="mb-1 text-lg font-medium text-gray-900 dark:text-gray-100">No Scheduled Reports</p>
                <p class="mb-4 text-sm text-gray-500 dark:text-gray-400">Create your first automated report to get started</p>
                <button onclick="openCreateModal()" class="px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700">
                    Create Schedule
                </button>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div id="scheduleModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeScheduleModal()"></div>

            <div class="relative z-50 w-full max-w-2xl p-6 bg-white rounded-lg dark:bg-gray-800">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Create Scheduled Report
                    </h3>
                    <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="scheduleForm" onsubmit="saveSchedule(event)" class="space-y-4">
                    <!-- Report Name -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Report Name</label>
                        <input type="text" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                               placeholder="Weekly Sales Summary">
                    </div>

                    <!-- Report Type -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Report Type</label>
                        <select name="report_type" required class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="sales">Sales Report</option>
                            <option value="customers">Customer Report</option>
                        </select>
                    </div>

                    <!-- Frequency -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Frequency</label>
                        <select name="frequency" id="frequencySelect" onchange="updateFrequencyFields()" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>

                    <!-- Day of Week (for weekly) -->
                    <div id="dayOfWeekField" style="display: none;">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Day of Week</label>
                        <select name="day_of_week" class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="monday">Monday</option>
                            <option value="tuesday">Tuesday</option>
                            <option value="wednesday">Wednesday</option>
                            <option value="thursday">Thursday</option>
                            <option value="friday">Friday</option>
                            <option value="saturday">Saturday</option>
                            <option value="sunday">Sunday</option>
                        </select>
                    </div>

                    <!-- Day of Month (for monthly) -->
                    <div id="dayOfMonthField" style="display: none;">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Day of Month</label>
                        <input type="number" name="day_of_month" min="1" max="31"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                               placeholder="1-31">
                    </div>

                    <!-- Time -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Send Time</label>
                        <input type="time" name="time" value="08:00" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                    </div>

                    <!-- Recipients -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Email Recipients</label>
                        <textarea name="recipients" required rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                                  placeholder="email1@example.com, email2@example.com"></textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Separate multiple emails with commas</p>
                    </div>

                    <!-- Format -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Report Format</label>
                        <select name="format" required class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="csv">CSV</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-4">
                        <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700">
                            Create Schedule
                        </button>
                        <button type="button" onclick="closeScheduleModal()" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
       function openCreateModal() {
    document.getElementById('scheduleModal').classList.remove('hidden');
}

function closeScheduleModal() {
    document.getElementById('scheduleModal').classList.add('hidden');
    document.getElementById('scheduleForm').reset();
}

function updateFrequencyFields() {
    const frequency = document.getElementById('frequencySelect').value;
    document.getElementById('dayOfWeekField').style.display = frequency === 'weekly' ? 'block' : 'none';
    document.getElementById('dayOfMonthField').style.display = frequency === 'monthly' ? 'block' : 'none';
}

async function saveSchedule(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    // Parse recipients
    const recipients = formData.get('recipients').split(',').map(e => e.trim()).filter(e => e);

    if (recipients.length === 0) {
        alert('Please enter at least one email recipient');
        return;
    }

    const data = {
        name: formData.get('name'),
        report_type: formData.get('report_type'),
        frequency: formData.get('frequency'),
        day_of_week: formData.get('day_of_week') || null,
        day_of_month: formData.get('day_of_month') || null,
        time: formData.get('time'),
        recipients: recipients,
        format: formData.get('format')
    };

    // Show loading state
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.textContent = 'Creating...';

    try {
        const response = await fetch('/reports/scheduled', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            alert('Scheduled report created successfully!');
            // Use window.location.href for a hard reload
            window.location.href = '/reports/scheduled';
        } else {
            // Show validation errors
            if (result.errors) {
                const errorMessages = Object.values(result.errors).flat().join('\n');
                alert('Validation errors:\n' + errorMessages);
            } else {
                alert('Error: ' + (result.message || 'Failed to create schedule'));
            }
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to create schedule. Check your internet connection and try again.');
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    }
}

async function toggleReport(id) {
    try {
        const response = await fetch(`/reports/scheduled/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            window.location.href = '/reports/scheduled';
        } else {
            alert('Failed to toggle report status');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to toggle report status');
    }
}

async function sendNow(id) {
    if (!confirm('Send this report immediately?')) return;

    const button = event.target;
    const originalText = button.textContent;
    button.disabled = true;
    button.textContent = 'Sending...';

    try {
        const response = await fetch(`/reports/scheduled/${id}/send-now`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (response.ok) {
            alert('✅ Report sent successfully!\n\nCheck storage/logs/laravel.log to see the email content.');
            button.disabled = false;
            button.textContent = originalText;
        } else {
            // Show detailed error information
            let errorMsg = '❌ Failed to send report\n\n';
            errorMsg += 'Status: ' + response.status + '\n';
            errorMsg += 'Error: ' + (result.error || result.message || 'Unknown error') + '\n\n';
            errorMsg += 'Check browser console (F12) for more details.';

            alert(errorMsg);

            // Log detailed error to console
            console.error('=== SEND NOW ERROR ===');
            console.error('Status:', response.status);
            console.error('Response:', result);
            console.error('Full response body:', JSON.stringify(result, null, 2));
            console.error('===================');

            button.disabled = false;
            button.textContent = originalText;
        }
    } catch (error) {
        console.error('=== NETWORK ERROR ===');
        console.error('Error object:', error);
        console.error('Error message:', error.message);
        console.error('Error stack:', error.stack);
        console.error('===================');

        alert('❌ Network error: ' + error.message + '\n\nCheck browser console (F12) for details.');
        button.disabled = false;
        button.textContent = originalText;
    }
}

async function deleteReport(id) {
    if (!confirm('Delete this scheduled report? This cannot be undone.')) return;

    try {
        const response = await fetch(`/reports/scheduled/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        });

        if (response.ok) {
            window.location.href = '/reports/scheduled';
        } else {
            alert('Failed to delete report');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Failed to delete report');
    }
}

function viewLogs(id) {
    alert('Report history feature coming soon! For now, check the database report_logs table or storage/logs/laravel.log');
}
    </script>
</x-app-layout>
