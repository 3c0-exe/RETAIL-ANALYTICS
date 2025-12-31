<x-app-layout>
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 py-6">

        {{-- ============================================================== --}}
        {{-- 1. FULL PAGE SKELETON (Visible on Load)                        --}}
        {{-- ============================================================== --}}
        <div id="PageSkeleton" class="animate-pulse space-y-6">

            <div class="flex items-center space-x-2 mb-4">
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-16"></div>
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-4"></div>
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-24"></div>
            </div>

            <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1">
                    <div class="h-8 bg-gray-200 rounded dark:bg-gray-700 w-48 mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-full max-w-md"></div>
                </div>
                <div class="h-10 bg-gray-200 rounded-md dark:bg-gray-700 w-full sm:w-36"></div>
            </div>

            <div class="space-y-4">
                @for($i=0; $i<3; $i++)
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                    <div class="flex flex-col gap-4">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 space-y-3">
                                <div class="flex items-center gap-3 flex-wrap">
                                    <div class="h-5 bg-gray-200 rounded dark:bg-gray-700 w-40 sm:w-48"></div>
                                    <div class="h-5 bg-gray-200 rounded-full dark:bg-gray-700 w-16"></div>
                                </div>
                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
                                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                                </div>
                                <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                            </div>
                        </div>
                        <div class="flex gap-2 flex-wrap">
                            <div class="h-9 bg-gray-200 rounded dark:bg-gray-700 w-20"></div>
                            <div class="h-9 bg-gray-200 rounded dark:bg-gray-700 w-24"></div>
                            <div class="h-9 bg-gray-200 rounded dark:bg-gray-700 w-20"></div>
                            <div class="h-9 bg-gray-200 rounded dark:bg-gray-700 w-20"></div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 2. REAL PAGE CONTENT (Hidden Initially)                        --}}
        {{-- ============================================================== --}}
        <div id="RealPageContent" class="hidden opacity-0 transition-opacity duration-500">

            {{-- Breadcrumb --}}
            <nav class="mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 flex-wrap">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Scheduled Reports</span>
                        </div>
                    </li>
                </ol>
            </nav>

            {{-- Header Section --}}
            <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-gray-100 mb-1.5">
                        Scheduled Reports
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Automate your reports with daily, weekly, or monthly email delivery
                    </p>
                </div>
                <button onclick="openCreateModal()" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-200 rounded-lg shadow-sm bg-primary-600 hover:bg-primary-700 hover:shadow-md active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    New Schedule
                </button>
            </div>

            {{-- Reports List --}}
            <div class="space-y-4">
                @forelse($scheduledReports as $report)
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col gap-4">
                            {{-- Report Header --}}
                            <div class="flex items-start justify-between gap-3 flex-wrap">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2.5 mb-3 flex-wrap">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">
                                            {{ $report->name }}
                                        </h3>
                                        @if($report->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold text-green-700 bg-green-100 rounded-full dark:bg-green-900/30 dark:text-green-400 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 bg-green-600 dark:bg-green-400 rounded-full mr-1.5 animate-pulse"></span>
                                            Active
                                        </span>
                                        @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full dark:bg-gray-900/30 dark:text-gray-400 whitespace-nowrap">
                                            <span class="w-1.5 h-1.5 bg-gray-600 dark:bg-gray-400 rounded-full mr-1.5"></span>
                                            Paused
                                        </span>
                                        @endif
                                    </div>

                                    {{-- Report Details Grid --}}
                                    <div class="grid grid-cols-1 gap-2.5 text-sm text-gray-600 dark:text-gray-400 sm:grid-cols-3 mb-3">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span class="truncate">{{ ucfirst($report->report_type) }} Report</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="truncate">{{ ucfirst($report->frequency) }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="truncate">{{ $report->time->format('g:i A') }}</span>
                                        </div>
                                    </div>

                                    {{-- Recipients --}}
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        <span class="font-medium text-gray-700 dark:text-gray-300">Recipients:</span>
                                        <span class="ml-1">{{ implode(', ', array_slice($report->recipients, 0, 2)) }}</span>
                                        @if(count($report->recipients) > 2)
                                            <span class="inline-flex items-center px-2 py-0.5 ml-1 text-xs font-medium text-gray-700 bg-gray-100 rounded dark:bg-gray-800 dark:text-gray-300">
                                                +{{ count($report->recipients) - 2 }} more
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Next Run --}}
                                    @if($report->next_run_at)
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                        <span class="font-medium text-gray-700 dark:text-gray-300">Next Run:</span>
                                        <span class="ml-1">{{ $report->next_run_at->format('M d, Y \a\t g:i A') }}</span>
                                    </div>
                                    @endif

                                    {{-- Last Sent --}}
                                    @if($report->last_run_at)
                                    <div class="text-sm text-gray-500 dark:text-gray-500">
                                        <span class="font-medium">Last Sent:</span>
                                        <span class="ml-1">{{ $report->last_run_at->diffForHumans() }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex gap-2 flex-wrap pt-3 border-t border-gray-100 dark:border-gray-800">
                                <button onclick="toggleReport({{ $report->id }}, this)"
                                        class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 active:scale-95 transition-all dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 whitespace-nowrap">
                                    @if($report->is_active)
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pause
                                    @else
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Resume
                                    @endif
                                </button>
                                <button onclick="sendNow({{ $report->id }}, this)"
                                        class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white rounded-lg bg-primary-600 hover:bg-primary-700 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 whitespace-nowrap">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Send Now
                                </button>
                                <button onclick="viewLogs({{ $report->id }})"
                                        class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 active:scale-95 transition-all dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 whitespace-nowrap">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    History
                                </button>
                                <button onclick="deleteReport({{ $report->id }}, this)"
                                        class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 whitespace-nowrap">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            </div>
                        </div>

                        {{-- Recent Activity --}}
                        @if($report->logs->count() > 0)
                        <div class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                            <h4 class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Recent Activity</h4>
                            <div class="space-y-2">
                                @foreach($report->logs->take(3) as $log)
                                <div class="flex items-start gap-2.5 text-xs text-gray-600 dark:text-gray-400 p-2 rounded bg-gray-50 dark:bg-gray-800/50">
                                    @if($log->status === 'success')
                                        <span class="text-green-600 dark:text-green-400 text-base flex-shrink-0">✓</span>
                                    @else
                                        <span class="text-red-600 dark:text-red-400 text-base flex-shrink-0">✗</span>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="font-medium">{{ $log->sent_at->format('M d, Y g:i A') }}</span>
                                            <span class="text-gray-400">•</span>
                                            <span>{{ $log->recipient_count }} recipient(s)</span>
                                        </div>
                                        @if($log->status === 'failed' && $log->error_message)
                                            <div class="text-red-600 dark:text-red-400 mt-1">{{ $log->error_message }}</div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-16 px-4 bg-white border border-gray-200 rounded-lg dark:bg-[#171717] dark:border-gray-800">
                    <div class="flex items-center justify-center w-16 h-16 mb-4 bg-gray-100 rounded-full dark:bg-gray-800">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="mb-1 text-lg font-semibold text-gray-900 dark:text-gray-100">No Scheduled Reports</p>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 text-center max-w-sm">Create your first automated report to get started with scheduled email delivery</p>
                    <button onclick="openCreateModal()" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-200 rounded-lg shadow-sm bg-primary-600 hover:bg-primary-700 hover:shadow-md active:scale-95 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Schedule
                    </button>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div id="scheduleModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" onclick="closeScheduleModal()" aria-hidden="true"></div>

            {{-- Center Modal Trick --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block w-full max-w-2xl my-8 overflow-hidden text-left align-middle transition-all transform bg-white rounded-xl shadow-2xl dark:bg-gray-800 sm:align-middle">
                {{-- Modal Header --}}
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Create Scheduled Report
                    </h3>
                    <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-lg p-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <form id="scheduleForm" onsubmit="saveSchedule(event)" class="px-6 py-6 max-h-[calc(100vh-200px)] overflow-y-auto">
                    <div class="space-y-5">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Report Name</label>
                            <input type="text" name="name" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                   placeholder="e.g., Weekly Sales Summary">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Report Type</label>
                            <select name="report_type" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                <option value="sales">Sales Report</option>
                                <option value="customers">Customer Report</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Frequency</label>
                            <select name="frequency" id="frequencySelect" onchange="updateFrequencyFields()" required
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                <option value="daily">Daily</option>
                                <option value="weekly">Weekly</option>
                                <option value="monthly">Monthly</option>
                            </select>
                        </div>

                        <div id="dayOfWeekField" style="display: none;" class="transition-all">
                            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Day of Week</label>
                            <select name="day_of_week" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                <option value="monday">Monday</option>
                                <option value="tuesday">Tuesday</option>
                                <option value="wednesday">Wednesday</option>
                                <option value="thursday">Thursday</option>
                                <option value="friday">Friday</option>
                                <option value="saturday">Saturday</option>
                                <option value="sunday">Sunday</option>
                            </select>
                        </div>

                        <div id="dayOfMonthField" style="display: none;" class="transition-all">
                            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Day of Month</label>
                            <input type="number" name="day_of_month" min="1" max="31"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all"
                                   placeholder="1-31">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Send Time</label>
                            <input type="time" name="time" value="08:00" required
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Email Recipients</label>
                            <textarea name="recipients" required rows="3"
                                      class="w-full px-4 py-2.5 border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all resize-none"
                                      placeholder="email1@example.com, email2@example.com"></textarea>
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">Separate multiple emails with commas</p>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">Report Format</label>
                            <select name="format" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                                <option value="csv">CSV</option>
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel</option>
                            </select>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex gap-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-white rounded-lg bg-primary-600 hover:bg-primary-700 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Create Schedule
                        </button>
                        <button type="button" onclick="closeScheduleModal()" class="flex-1 px-4 py-2.5 text-sm font-semibold text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 active:scale-95 transition-all dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- 3. JAVASCRIPT LOGIC                                            --}}
    {{-- ============================================================== --}}
    <script>
        // Initial Page Skeleton Logic
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const pageSkeleton = document.getElementById('PageSkeleton');
                const realContent = document.getElementById('RealPageContent');
                if (pageSkeleton) pageSkeleton.remove();
                if (realContent) {
                    realContent.classList.remove('hidden');
                    setTimeout(() => realContent.classList.remove('opacity-0'), 10);
                }
            }, 500);
        });

        function openCreateModal() {
            document.getElementById('scheduleModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeScheduleModal() {
            document.getElementById('scheduleModal').classList.add('hidden');
            document.getElementById('scheduleForm').reset();
            updateFrequencyFields();
            document.body.style.overflow = 'auto';
        }

        function updateFrequencyFields() {
            const frequency = document.getElementById('frequencySelect').value;
            document.getElementById('dayOfWeekField').style.display = frequency === 'weekly' ? 'block' : 'none';
            document.getElementById('dayOfMonthField').style.display = frequency === 'monthly' ? 'block' : 'none';
        }

        async function saveSchedule(event) {
            event.preventDefault();
            const form = event.target;
            const btn = form.querySelector('button[type="submit"]');

            if(typeof showButtonLoading === 'function') {
                showButtonLoading(btn, 'Creating...');
            } else {
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Creating...';
            }

            const formData = new FormData(form);
            const recipients = formData.get('recipients').split(',').map(e => e.trim()).filter(e => e);

            if (recipients.length === 0) {
                alert('Please enter at least one email recipient');
                if(typeof hideButtonLoading === 'function') {
                    hideButtonLoading(btn);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Create Schedule';
                }
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
                    window.location.href = '/reports/scheduled';
                } else {
                    if (result.errors) {
                        const errorMessages = Object.values(result.errors).flat().join('\n');
                        alert('Validation errors:\n' + errorMessages);
                    } else {
                        alert('Error: ' + (result.message || 'Failed to create schedule'));
                    }
                    if(typeof hideButtonLoading === 'function') {
                        hideButtonLoading(btn);
                    } else {
                        btn.disabled = false;
                        btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Create Schedule';
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to create schedule. Check your internet connection.');
                if(typeof hideButtonLoading === 'function') {
                    hideButtonLoading(btn);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Create Schedule';
                }
            }
        }

        async function toggleReport(id, btn) {
            const originalHTML = btn.innerHTML;
            if(typeof showButtonLoading === 'function') {
                showButtonLoading(btn, '...');
            } else {
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            }

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
                    if(typeof hideButtonLoading === 'function') {
                        hideButtonLoading(btn);
                    } else {
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to toggle report status');
                if(typeof hideButtonLoading === 'function') {
                    hideButtonLoading(btn);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                }
            }
        }

        async function sendNow(id, btn) {
            if (!confirm('Send this report immediately?')) return;

            const originalHTML = btn.innerHTML;
            if(typeof showButtonLoading === 'function') {
                showButtonLoading(btn, 'Sending...');
            } else {
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Sending...';
            }

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
                    alert('✅ Report sent successfully!');
                } else {
                    alert('❌ Failed: ' + (result.error || result.message));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('❌ Network error');
            } finally {
                if(typeof hideButtonLoading === 'function') {
                    hideButtonLoading(btn);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                }
            }
        }

        async function deleteReport(id, btn) {
            if (!confirm('Delete this scheduled report? This cannot be undone.')) return;

            const originalHTML = btn.innerHTML;
            if(typeof showButtonLoading === 'function') {
                showButtonLoading(btn, 'Deleting...');
            } else {
                btn.disabled = true;
                btn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Deleting...';
            }

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
                    if(typeof hideButtonLoading === 'function') {
                        hideButtonLoading(btn);
                    } else {
                        btn.disabled = false;
                        btn.innerHTML = originalHTML;
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to delete report');
                if(typeof hideButtonLoading === 'function') {
                    hideButtonLoading(btn);
                } else {
                    btn.disabled = false;
                    btn.innerHTML = originalHTML;
                }
            }
        }

        function viewLogs(id) {
            alert('Report history feature coming soon!');
        }

        // Close modal on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const modal = document.getElementById('scheduleModal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeScheduleModal();
                }
            }
        });
    </script>
</x-app-layout>
