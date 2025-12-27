<x-app-layout>
    <div class="py-8">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Page Header --}}
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Activity Logs</h1>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Monitor all user actions across the system with IP tracking and device detection</p>
                    </div>
                    {{-- Export Button --}}
                    <form method="GET" action="{{ route('admin.activity-logs.index') }}">
                        @foreach(request()->except('export') as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <input type="hidden" name="export" value="csv">
                        <button type="submit" class="inline-flex items-center px-4 py-2 font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export CSV
                        </button>
                    </form>
                </div>
            </div>

            {{-- Filters --}}
            <div class="mb-6 bg-white border border-gray-200 shadow-sm dark:bg-gray-800 rounded-xl dark:border-gray-700">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-5">
                        {{-- Search --}}
                        <div>
                            <label for="search" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   placeholder="Description..."
                                   class="w-full px-4 py-2 text-gray-900 bg-white border border-gray-300 rounded-lg dark:bg-gray-900 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Action Filter --}}
                        <div>
                            <label for="action" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Action</label>
                            <select name="action" id="action" class="w-full px-4 py-2 text-gray-900 bg-white border border-gray-300 rounded-lg dark:bg-gray-900 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Actions</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                        {{ ucfirst(str_replace('_', ' ', $action)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Model Filter --}}
                        <div>
                            <label for="model_type" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Model</label>
                            <select name="model_type" id="model_type" class="w-full px-4 py-2 text-gray-900 bg-white border border-gray-300 rounded-lg dark:bg-gray-900 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">All Models</option>
                                @foreach($modelTypes as $modelType)
                                    <option value="{{ $modelType }}" {{ request('model_type') == $modelType ? 'selected' : '' }}>
                                        {{ class_basename($modelType) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- IP Address Filter --}}
                        <div>
                            <label for="ip_address" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">IP Address</label>
                            <input type="text" name="ip_address" id="ip_address" value="{{ request('ip_address') }}"
                                   placeholder="127.0.0.1"
                                   class="w-full px-4 py-2 text-gray-900 bg-white border border-gray-300 rounded-lg dark:bg-gray-900 dark:border-gray-600 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        {{-- Filter Button --}}
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 font-medium text-white transition-colors bg-blue-600 rounded-lg hover:bg-blue-700">
                                Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Activity Logs Table --}}
            <div class="overflow-hidden bg-white border border-gray-200 shadow-sm dark:bg-gray-800 rounded-xl dark:border-gray-700">
                <!-- Desktop Table View -->
                <div class="hidden overflow-x-auto lg:block">
                    <table class="w-full">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:bg-gray-900 dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Time</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">User</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Action</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Model</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">IP Address</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Browser/Device</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/50 {{ $log->isSuspicious() ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                                    <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap dark:text-gray-100">
                                        {{ $log->created_at->format('M d, Y H:i') }}
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">{{ $log->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex items-center justify-center w-8 h-8 mr-3 text-xs font-medium text-white rounded-full bg-primary-600">
                                                {{ substr($log->user->name ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $log->user->name ?? 'Unknown' }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->user->email ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            @if($log->isSuspicious())
                                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20" title="Suspicious Activity">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if(str_contains($log->action, 'created')) bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                                @elseif(str_contains($log->action, 'updated')) bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                                                @elseif(str_contains($log->action, 'deleted')) bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ $log->model_type ? class_basename($log->model_type) : '-' }}
                                        @if($log->model_id)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">#{{ $log->model_id }}</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-mono text-sm text-gray-900 dark:text-gray-100">{{ $log->ip_address ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            {{-- Browser Badge --}}
                                            @if($log->browser)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 w-fit">
                                                    @if($log->browser == 'Chrome')
                                                        <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C8.21 0 4.831 1.757 2.632 4.501l3.953 6.848A5.454 5.454 0 0 1 12 6.545h10.691A12 12 0 0 0 12 0zM1.931 5.47A11.943 11.943 0 0 0 0 12c0 6.012 4.42 10.991 10.189 11.864l3.953-6.847a5.45 5.45 0 0 1-6.865-2.29zm13.342 2.166a5.446 5.446 0 0 1 1.45 7.09l.002.001h-.002l-5.344 9.257c.206.01.413.016.621.016 6.627 0 12-5.373 12-12 0-1.54-.29-3.011-.818-4.364zM12 16.364a4.364 4.364 0 1 1 0-8.728 4.364 4.364 0 0 1 0 8.728z"/></svg>
                                                    @elseif($log->browser == 'Firefox')
                                                        <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor"><path d="M8.824 7.287c.008 0 .004 0 0 0zm-2.8-1.4c.006 0 .003 0 0 0zm16.754 2.161c-.505-1.215-1.53-2.528-2.333-2.943.654 1.283 1.033 2.57 1.177 3.53l.002.02c-1.314-3.278-3.544-4.6-5.366-7.477-.091-.147-.184-.292-.273-.446a3.545 3.545 0 01-.13-.24 2.118 2.118 0 01-.172-.46.03.03 0 00-.027-.03.038.038 0 00-.021 0l-.006.001a.037.037 0 00-.01.005L15.624 0c-2.585 1.515-3.657 4.168-3.932 5.856a6.197 6.197 0 00-2.305.587.297.297 0 00-.147.37c.057.162.24.24.396.17a5.622 5.622 0 012.008-.523l.067-.005a5.847 5.847 0 011.957.222l.095.03a5.816 5.816 0 01.616.228c.08.036.16.073.238.112l.107.055a5.835 5.835 0 01.368.211 5.953 5.953 0 012.034 2.104c-.62-.437-1.733-.868-2.803-.681 4.183 2.09 3.06 9.292-2.737 9.02a5.164 5.164 0 01-1.513-.292 4.42 4.42 0 01-.538-.232c-1.42-.735-2.593-2.121-2.74-3.806 0 0 .537-2.032 3.845-2.032.357 0 .673.031.945.082l.034.006c-.281-.14-.602-.245-.948-.295a1.516 1.516 0 00-.065-.005 5.466 5.466 0 00-.678-.04c-.86 0-1.57.16-2.11.366-.539.205-.896.445-1.058.644-.208.269-.277.562-.277.899 0 .02.002.04.002.06a1.846 1.846 0 01-.015-.172c0-.236.041-.463.115-.678a1.962 1.962 0 01.348-.608c-.05.009-.09.037-.116.079a.996.996 0 00-.048.045 1.025 1.025 0 00-.07.093.953.953 0 00-.062.105c-.137.265-.21.577-.21.911 0 .134.006.265.017.394-.006-.077-.009-.155-.009-.233 0-.25.035-.487.1-.713.067-.227.168-.435.299-.626.131-.19.294-.36.483-.501a2.533 2.533 0 01.653-.34c.24-.09.493-.159.758-.202a4.99 4.99 0 01.86-.065c.302 0 .599.016.889.048a6.086 6.086 0 011.668.387c.232.093.454.201.668.32.213.12.42.252.618.397a5.956 5.956 0 011.396 1.425 6.034 6.034 0 01.908 2.006c.12.434.187.878.2 1.327v.026a6.03 6.03 0 01-.13 1.184 5.987 5.987 0 01-.455 1.372 6.008 6.008 0 01-.743 1.177 5.943 5.943 0 01-1.008.968 5.955 5.955 0 01-1.223.707 5.967 5.967 0 01-1.364.358c-.47.063-.947.086-1.429.069-.48-.017-.96-.07-1.439-.162a7.951 7.951 0 01-1.405-.402c-.47-.174-.928-.387-1.37-.64l-.018-.01-.005-.003a9.557 9.557 0 01-1.31-.828c-.435-.327-.844-.688-1.225-1.08a11.41 11.41 0 01-1.064-1.277c-.317-.43-.607-.88-.867-1.347a11.52 11.52 0 01-.66-1.443 11.642 11.642 0 01-.42-1.51 11.776 11.776 0 01-.156-1.553v-.078c0-.087.001-.174.003-.261.002-.087.006-.174.01-.261.008-.173.022-.346.04-.517.037-.342.09-.682.16-1.02.14-.676.344-1.339.615-1.982.27-.643.61-1.262 1.016-1.853.406-.59.88-1.15 1.415-1.672.535-.522 1.13-1.005 1.775-1.44.645-.435 1.34-.82 2.073-1.15a12.04 12.04 0 012.335-.668 12.178 12.178 0 012.473-.225c.418 0 .834.017 1.248.051.414.034.827.085 1.237.155.41.07.818.156 1.221.261.403.105.803.225 1.198.361.395.136.786.288 1.172.455.386.167.767.35 1.141.547.374.197.743.408 1.105.634.362.226.717.466 1.065.72.348.254.689.522 1.022.803.333.281.658.576.975.884.317.308.625.629.924.962.299.333.59.679.87 1.036.28.357.551.727.81 1.109.26.382.509.776.745 1.181.236.405.462.82.675 1.245.213.425.415.859.603 1.302.188.443.364.895.525 1.354.161.459.31.926.443 1.4.133.474.254.955.359 1.441.105.486.198.979.275 1.476.077.497.141 1 .189 1.507.048.507.083 1.018.101 1.531.018.513.023 1.03.013 1.547-.01.517-.035 1.036-.075 1.555a15.01 15.01 0 01-.165 1.554c-.07.517-.157 1.033-.26 1.547-.103.514-.223 1.026-.359 1.536-.136.51-.289 1.018-.459 1.524a15.13 15.13 0 01-.578 1.498 15.294 15.294 0 01-.707 1.462c-.258.484-.534.963-.827 1.437-.293.474-.605.942-.933 1.403a15.74 15.74 0 01-1.048 1.347c-.375.441-.77.873-1.182 1.294-.412.421-.842.831-1.288 1.229-.446.398-.908.784-1.385 1.156-.477.372-.97.73-1.477 1.073-.507.343-1.029.67-1.564.98-.535.31-1.084.604-1.646.879-.562.275-1.137.533-1.723.772-.586.24-1.184.46-1.793.661-.609.201-1.228.382-1.857.542-.629.16-1.268.3-1.916.418a19.936 19.936 0 01-1.968.257c-.66.059-1.325.095-1.993.106-.668.011-1.339.001-2.009-.029a19.84 19.84 0 01-2.009-.179 19.67 19.67 0 01-2-.391 19.447 19.447 0 01-1.978-.6c-.648-.23-1.288-.49-1.918-.777-.63-.287-1.25-.6-1.856-.938-.606-.338-1.198-.701-1.774-1.088a18.998 18.998 0 01-1.674-1.255c-.539-.44-1.062-.903-1.567-1.386-.505-.483-.993-.986-1.462-1.506-.469-.52-.919-1.057-1.35-1.611-.431-.554-.842-1.124-1.232-1.709a19.353 19.353 0 01-1.065-1.823 19.556 19.556 0 01-.875-1.914 19.722 19.722 0 01-.66-1.985 19.853 19.853 0 01-.431-2.038 19.943 19.943 0 01-.191-2.074c-.014-.693-.01-1.387.013-2.08.023-.693.065-1.385.125-2.076.06-.691.139-1.38.237-2.067.098-.687.214-1.372.349-2.053.135-.681.288-1.359.46-2.032.172-.673.362-1.342.57-2.006.208-.664.434-1.323.678-1.976.244-.653.506-1.3.786-1.941.28-.641.577-1.275.89-1.902.313-.627.644-1.246.99-1.857.346-.611.709-1.213 1.088-1.806.379-.593.774-1.177 1.184-1.75.41-.573.836-1.136 1.278-1.687.442-.551.899-1.09 1.37-1.616.471-.526.957-1.04 1.458-1.54.501-.5 1.017-.986 1.546-1.457.529-.471 1.072-.927 1.628-1.368.556-.441 1.126-.867 1.709-1.277.583-.41 1.179-.803 1.786-1.18.607-.377 1.227-.737 1.857-1.081.63-.344 1.272-.67 1.923-.98.651-.31 1.313-.602 1.984-.877.671-.275 1.352-.532 2.042-.771.69-.239 1.389-.46 2.096-.663.707-.203 1.423-.388 2.146-.553.723-.165 1.454-.311 2.191-.438.737-.127 1.481-.234 2.23-.321.749-.087 1.503-.155 2.26-.203.757-.048 1.518-.076 2.281-.085.763-.009 1.53.001 2.297.031.767.03 1.537.08 2.306.15.769.07 1.538.16 2.306.27.768.11 1.535.24 2.3.39.765.15 1.528.32 2.289.51.761.19 1.52.4 2.275.63.755.23 1.507.48 2.254.749.747.269 1.491.558 2.229.866.738.308 1.471.635 2.198.98.727.345 1.448.71 2.162 1.093.714.383 1.421.785 2.12 1.204.699.419 1.391.856 2.075 1.311.684.455 1.36.927 2.027 1.416.667.489 1.326.995 1.975 1.517.649.522 1.289 1.061 1.919 1.615.63.554 1.25 1.123 1.858 1.707.608.584 1.206 1.183 1.791 1.795.585.612 1.159 1.238 1.72 1.877.561.639 1.109 1.29 1.644 1.954.535.664 1.056 1.34 1.564 2.027.508.687.003 1.385 1.013 2.092.01.007.02.014.03.021.01.007.02.014.03.021.01.007.02.014.03.021.01.007.02.014.03.021z"/></svg>
                                                    @elseif($log->browser == 'Safari')
                                                        <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor"><path d="M12 24C5.373 24 0 18.627 0 12S5.373 0 12 0s12 5.373 12 12-5.373 12-12 12zm0-1.5c5.799 0 10.5-4.701 10.5-10.5S17.799 1.5 12 1.5 1.5 6.201 1.5 12 6.201 22.5 12 22.5zm5.367-12.957l-6.683 3.35-3.35 6.683 6.683-3.35 3.35-6.683z"/></svg>
                                                    @else
                                                        <svg class="w-3 h-3 mr-1" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm0 22C6.486 22 2 17.514 2 12S6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg>
                                                    @endif
                                                    {{ $log->browser }}
                                                </span>
                                            @endif
                                            {{-- Device Badge --}}
                                            @if($log->device)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 w-fit">
                                                    @if($log->device == 'Mobile')
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7 2a2 2 0 00-2 2v12a2 2 0 002 2h6a2 2 0 002-2V4a2 2 0 00-2-2H7zm3 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>
                                                    @elseif($log->device == 'Tablet')
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm4 14a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path></svg>
                                                    @else
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5z" clip-rule="evenodd"></path></svg>
                                                    @endif
                                                    {{ $log->device }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium">No activity logs found</p>
                                        <p class="mt-1 text-sm">Try adjusting your filters</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="divide-y divide-gray-200 lg:hidden dark:divide-gray-700">
                    @forelse($logs as $log)
                        <div class="p-4 {{ $log->isSuspicious() ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-10 h-10 mr-3 text-sm font-medium text-white rounded-full bg-primary-600">
                                        {{ substr($log->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $log->user->name ?? 'Unknown' }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                @if($log->isSuspicious())
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>

                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if(str_contains($log->action, 'created')) bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                        @elseif(str_contains($log->action, 'updated')) bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                                        @elseif(str_contains($log->action, 'deleted')) bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                    </span>
                                    @if($log->model_type)
                                        <span class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ class_basename($log->model_type) }}
                                            @if($log->model_id)#{{ $log->model_id }}@endif
                                        </span>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                    <span class="font-mono">{{ $log->ip_address ?? '-' }}</span>
                                </div>

                                <div class="flex gap-2">
                                    @if($log->browser)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                            {{ $log->browser }}
                                        </span>
                                    @endif
                                    @if($log->device)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                            {{ $log->device }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-lg font-medium">No activity logs found</p>
                            <p class="mt-1 text-sm">Try adjusting your filters</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
