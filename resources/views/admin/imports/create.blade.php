<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Import Sales Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Upload CSV or Excel File
                    </h3>

                    @if($errors->any())
                        <div class="p-4 mb-4 text-red-700 bg-red-100 border border-red-400 rounded-md dark:bg-red-900/30 dark:border-red-600 dark:text-red-400">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.imports.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Branch Selection -->
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
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Select which branch this data belongs to</p>
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label for="file" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Sales Data File <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="file" id="file" accept=".csv,.xlsx,.xls" required class="block w-full text-sm text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 dark:file:bg-purple-900/30 dark:file:text-purple-400 dark:hover:file:bg-purple-900/50">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Accepted formats: CSV, XLSX, XLS (Max: 10MB)</p>
                        </div>

                        <!-- Expected Format Info -->
                        <div class="p-4 border border-blue-200 rounded-md bg-blue-50 dark:bg-blue-900/20 dark:border-blue-800">
                            <h4 class="mb-2 text-sm font-semibold text-blue-900 dark:text-blue-400">Expected CSV/Excel Format:</h4>
                            <div class="space-y-1 text-xs text-blue-800 dark:text-blue-300">
                                <p><strong>Required columns:</strong></p>
                                <ul class="ml-2 list-disc list-inside">
                                    <li>transaction_code or invoice_number</li>
                                    <li>date or transaction_date (YYYY-MM-DD)</li>
                                    <li>product_name or item_name</li>
                                    <li>quantity or qty</li>
                                    <li>price or unit_price</li>
                                    <li>total or amount</li>
                                </ul>
                                <p class="mt-2"><strong>Optional columns:</strong></p>
                                <ul class="ml-2 list-disc list-inside">
                                    <li>customer_name, customer_email, customer_phone</li>
                                    <li>payment_method</li>
                                    <li>discount</li>
                                    <li>sku or product_code</li>
                                    <li>cashier or staff_name</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Sample CSV Download -->
                        <div class="p-4 border border-gray-200 rounded-md bg-gray-50 dark:bg-gray-900/50 dark:border-gray-700">
                            <h4 class="mb-2 text-sm font-semibold text-gray-900 dark:text-gray-100">Need a template?</h4>
                            <p class="mb-2 text-sm text-gray-600 dark:text-gray-400">Download our sample CSV file to see the expected format:</p>
                            <a href="#" class="inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download Sample CSV
                            </a>
                        </div>

                        <!-- Action Buttons -->
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
            </div>
        </div>
    </div>
</x-app-layout>
