<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ============================================================== --}}
        {{-- 1. FORM SKELETON                                               --}}
        {{-- ============================================================== --}}
        <div id="FormSkeleton" class="animate-pulse space-y-6">
            <div class="h-8 bg-gray-200 rounded dark:bg-gray-700 w-48 mb-2"></div>
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 space-y-6">
                <div class="h-6 bg-gray-200 rounded dark:bg-gray-700 w-48 mb-4"></div>
                <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                <div class="h-32 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                <div class="flex justify-end gap-3">
                    <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-24"></div>
                    <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-32"></div>
                </div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 2. REAL CONTENT                                                --}}
        {{-- ============================================================== --}}
        <div id="RealFormContent" class="hidden opacity-0 transition-opacity duration-500">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200 mb-6">
                {{ __('Import Sales Data') }}
            </h2>

            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6">

                    {{-- UPLOAD FORM CONTAINER --}}
                    <div id="uploadFormContainer">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Upload CSV or Excel File
                        </h3>

                        <form action="{{ route('admin.imports.upload') }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="space-y-6"
                              onsubmit="handleUpload(event)">
                            @csrf

                            <div>
                                <label for="branch_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Branch <span class="text-red-500">*</span>
                                </label>
                                <select name="branch_id" id="branch_id" required class="block w-full border-gray-300 rounded-md shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500">
                                    <option value="">Select Branch</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="file" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Sales Data File <span class="text-red-500">*</span>
                                </label>
                                <input type="file" name="file" id="file" accept=".csv,.xlsx,.xls" required class="block w-full text-sm text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 dark:file:bg-purple-900/30 dark:file:text-purple-400 dark:hover:file:bg-purple-900/50">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Accepted formats: CSV, XLSX, XLS (Max: 10MB)</p>
                            </div>

                            <div class="p-4 border border-gray-200 rounded-md bg-gray-50 dark:bg-gray-900/50 dark:border-gray-700">
                                <h4 class="mb-2 text-sm font-semibold text-gray-900 dark:text-gray-100">Need a template?</h4>
                                <a href="{{ route('admin.imports.download-sample') }}" class="inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 01-2-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download Sample CSV
                                </a>
                            </div>

                            <div class="flex items-center justify-end space-x-3">
                                <a href="{{ route('admin.imports.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 transition bg-white border border-gray-300 rounded-md dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Cancel
                                </a>
                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white transition bg-purple-600 border border-transparent rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                    Upload & Preview
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- PROGRESS INDICATOR (Hidden Initially) --}}
                    <div id="uploadProgress" class="hidden py-8">
                        <div class="max-w-xl mx-auto text-center">

                            <div class="mb-8">
                                <div class="flex justify-between mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span>Uploading file...</span>
                                    <span id="progressPercent">0%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-4 dark:bg-gray-700 overflow-hidden">
                                    <div id="progressBar" class="bg-purple-600 h-4 rounded-full transition-all duration-300 ease-out" style="width: 0%"></div>
                                </div>
                            </div>

                            <div class="flex flex-col items-center justify-center">
                                <svg class="w-12 h-12 text-purple-600 animate-spin mb-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Processing Data...</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Please wait while we validate your file.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const formSkeleton = document.getElementById('FormSkeleton');
                const realForm = document.getElementById('RealFormContent');
                if (formSkeleton) formSkeleton.remove();
                if (realForm) {
                    realForm.classList.remove('hidden');
                    setTimeout(() => realForm.classList.remove('opacity-0'), 10);
                }
            }, 500);
        });

        function handleUpload(event) {
            // We want to show the progress bar immediately
            // Note: Since this is a standard form submission, we simulate the progress
            // visually before the page navigates away.
            const formContainer = document.getElementById('uploadFormContainer');
            const progressContainer = document.getElementById('uploadProgress');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');

            // Hide form, show progress
            formContainer.classList.add('hidden');
            progressContainer.classList.remove('hidden');

            // Simulate progress bar animation
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress > 95) progress = 95; // Hold at 95% until server responds

                progressBar.style.width = `${progress}%`;
                progressPercent.textContent = `${Math.round(progress)}%`;
            }, 300);
        }
    </script>
</x-app-layout>
