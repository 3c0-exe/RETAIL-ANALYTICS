<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        {{-- ============================================================== --}}
        {{-- 1. FULL PAGE SKELETON (Visible on Load)                        --}}
        {{-- ============================================================== --}}
        <div id="PageSkeleton" class="animate-pulse space-y-6">

            <div class="flex items-center space-x-2 mb-4">
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-16"></div>
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-4"></div>
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-24"></div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div>
                    <div class="h-8 bg-gray-200 rounded dark:bg-gray-700 w-48 mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-32"></div>
                </div>
                <div class="h-10 bg-gray-200 rounded-md dark:bg-gray-700 w-full sm:w-32"></div>
            </div>

            <div class="space-y-4">
                <div class="h-10 bg-gray-200 rounded-lg dark:bg-gray-700 w-full"></div> <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                    <div class="h-9 bg-gray-200 rounded-lg dark:bg-gray-700 w-32"></div> <div class="h-9 bg-gray-200 rounded-lg dark:bg-gray-700 w-48"></div> </div>
            </div>

            <div class="space-y-3">
                @for($i=0; $i<4; $i++)
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex-1 space-y-2">
                            <div class="h-5 bg-gray-200 rounded dark:bg-gray-700 w-1/3"></div>
                            <div class="flex gap-2">
                                <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-16"></div>
                                <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-24"></div>
                            </div>
                        </div>
                        <div class="h-6 bg-gray-200 rounded-full dark:bg-gray-700 w-16"></div>
                        <div class="flex gap-2">
                            <div class="h-9 bg-gray-200 rounded-lg dark:bg-gray-700 w-9"></div>
                            <div class="h-9 bg-gray-200 rounded-lg dark:bg-gray-700 w-9"></div>
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
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Categories</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">Categories</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage product categories</p>
                </div>

                <a href="{{ route('admin.categories.create') }}"
                   onclick="showButtonLoading(this, 'Loading...')"
                   class="inline-flex items-center justify-center text-white font-medium transition-all duration-200 bg-primary-600 hover:bg-primary-700 px-4 py-2 rounded-md text-sm w-full sm:w-auto">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add Category</span>
                </a>
            </div>

            @if(session('success'))
                <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 border border-green-500 rounded-lg dark:bg-green-900/20 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 border border-red-500 rounded-lg dark:bg-red-900/20 dark:text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4">
                <div class="relative">
                    <input type="text"
                           id="searchInput"
                           placeholder="Search categories..."
                           class="w-full px-4 py-2.5 pl-10 text-sm border border-gray-200 rounded-lg bg-white dark:bg-[#171717] dark:border-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-600 transition">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-between items-stretch sm:items-center mb-4 gap-3">
                <div class="flex items-center gap-2">
                    <select id="statusFilter" class="px-3 py-2 text-sm border border-gray-200 rounded-lg bg-white dark:bg-[#171717] dark:border-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <option value="all">All Status</option>
                        <option value="active">Active Only</option>
                        <option value="inactive">Inactive Only</option>
                    </select>
                    <span id="resultCount" class="text-sm text-gray-600 dark:text-gray-400"></span>
                </div>

                <div class="inline-flex rounded-lg border border-gray-200 dark:border-gray-800 p-1 bg-white dark:bg-[#171717]">
                    <button onclick="toggleView('list')" id="listViewBtn"
                            class="flex-1 sm:flex-initial px-3 py-1.5 text-sm font-medium rounded-md transition-colors view-toggle active">
                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        <span class="ml-1.5 hidden sm:inline">List</span>
                    </button>
                    <button onclick="toggleView('grid')" id="gridViewBtn"
                            class="flex-1 sm:flex-initial px-3 py-1.5 text-sm font-medium rounded-md transition-colors view-toggle">
                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                        </svg>
                        <span class="ml-1.5 hidden sm:inline">Grid</span>
                    </button>
                    <button onclick="toggleView('table')" id="tableViewBtn"
                            class="hidden sm:flex flex-1 sm:flex-initial px-3 py-1.5 text-sm font-medium rounded-md transition-colors view-toggle">
                        <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                        </svg>
                        <span class="ml-1.5">Table</span>
                    </button>
                </div>
            </div>

            <div id="listView" class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden divide-y divide-gray-200 dark:divide-gray-800">
                @forelse($categories as $category)
                    <div class="category-list-card hover:bg-gray-50 dark:hover:bg-[#0a0a0a] transition"
                         data-name="{{ strtolower($category->name) }}"
                         data-slug="{{ strtolower($category->slug) }}"
                         data-status="{{ $category->is_active ? 'active' : 'inactive' }}">

                        <div class="flex items-center gap-3 p-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-medium text-gray-900 dark:text-gray-100 text-base">{{ $category->name }}</h3>
                                </div>
                                <div class="flex items-center gap-3 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $category->products_count }}</span>
                                    </span>
                                    <span class="truncate text-xs">{{ $category->slug }}</span>
                                </div>
                            </div>

                            <div class="flex-shrink-0">
                                @if($category->is_active)
                                    <span class="px-2.5 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">Active</span>
                                @else
                                    <span class="px-2.5 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-400">Inactive</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-1 flex-shrink-0">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   onclick="showButtonLoading(this, '')"
                                   class="inline-flex items-center justify-center w-9 h-9 text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-primary-900/20 rounded-lg transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return handleDelete(this)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center justify-center w-9 h-9 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 rounded-lg transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center" id="noResultsList">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">No categories found.</p>
                        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                            Create Category
                        </a>
                    </div>
                @endforelse
            </div>

            <div id="gridView" class="hidden grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($categories as $category)
                    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 hover:shadow-lg transition-shadow category-card"
                         data-name="{{ strtolower($category->name) }}"
                         data-slug="{{ strtolower($category->slug) }}"
                         data-status="{{ $category->is_active ? 'active' : 'inactive' }}">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-gray-900 dark:text-gray-100 truncate">{{ $category->name }}</h3>
                                @if($category->description)
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-2">{{ $category->description }}</p>
                                @endif
                            </div>
                            @if($category->is_active)
                                <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400 flex-shrink-0 ml-2">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-400 flex-shrink-0 ml-2">Inactive</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-4 pb-4 border-b border-gray-200 dark:border-gray-800">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Slug</p>
                                <code class="inline-block px-2 py-0.5 text-xs bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 rounded break-all">{{ $category->slug }}</code>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Products</p>
                                <div class="flex items-center gap-1 text-sm text-gray-900 dark:text-gray-100">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    <span class="font-medium">{{ $category->products_count }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}"
                               onclick="showButtonLoading(this, 'Editing...')"
                               class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category) }}"
                                  method="POST"
                                  class="flex-1"
                                  onsubmit="return handleDelete(this)">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-8 text-center" id="noResultsGrid">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">No categories found.</p>
                        <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                            Create Category
                        </a>
                    </div>
                @endforelse
            </div>

            <div id="tableView" class="hidden bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-[#0a0a0a] border-b border-gray-200 dark:border-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Name</th>
                                <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400 hidden lg:table-cell">Slug</th>
                                <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Products</th>
                                <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Status</th>
                                <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800" id="tableBody">
                            @forelse($categories as $category)
                                <tr class="hover:bg-gray-50 dark:hover:bg-[#0a0a0a] transition category-row"
                                    data-name="{{ strtolower($category->name) }}"
                                    data-slug="{{ strtolower($category->slug) }}"
                                    data-status="{{ $category->is_active ? 'active' : 'inactive' }}">
                                    <td class="px-4 py-3">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $category->name }}</div>
                                        @if($category->description)
                                            <div class="text-sm text-gray-500 dark:text-gray-400 lg:hidden">{{ Str::limit($category->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 hidden lg:table-cell">
                                        <code class="px-2 py-0.5 text-xs bg-gray-100 dark:bg-gray-800 rounded">{{ $category->slug }}</code>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="inline-flex items-center gap-1">
                                            <svg class="w-4 h-4 text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                            {{ $category->products_count }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($category->is_active)
                                            <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">Active</span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-400">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.categories.edit', $category) }}"
                                               onclick="showButtonLoading(this, '')"
                                               class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.categories.destroy', $category) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return handleDelete(this)">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr id="noResultsTable">
                                    <td colspan="5" class="px-4 py-8 text-sm text-center text-gray-500 dark:text-gray-400">
                                        No categories found. <a href="{{ route('admin.categories.create') }}" class="text-primary-600 hover:underline">Create one</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($categories->hasPages())
                <div class="mt-6">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- 3. JAVASCRIPT LOGIC                                            --}}
    {{-- ============================================================== --}}
    <script>
        // 1. Initial Page Skeleton
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                const pageSkeleton = document.getElementById('PageSkeleton');
                const realContent = document.getElementById('RealPageContent');

                if (pageSkeleton) pageSkeleton.remove();

                if (realContent) {
                    realContent.classList.remove('hidden');
                    // Setup view AFTER content is revealed
                    const savedView = localStorage.getItem('categoryView') || 'list';
                    const isMobile = window.innerWidth < 640;
                    const viewToShow = (isMobile && savedView === 'table') ? 'list' : savedView;
                    toggleView(viewToShow);
                    updateResultCount();

                    setTimeout(() => realContent.classList.remove('opacity-0'), 10);
                }
            }, 500);

            // Listeners for search/filter
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            if (searchInput) searchInput.addEventListener('input', searchCategories);
            if (statusFilter) statusFilter.addEventListener('change', searchCategories);

            // Update view on window resize
            window.addEventListener('resize', function() {
                const currentView = localStorage.getItem('categoryView');
                const isMobileNow = window.innerWidth < 640;
                if (isMobileNow && currentView === 'table') {
                    toggleView('list');
                }
            });
        });

        // 2. Handle Delete Submission
        window.handleDelete = (form) => {
            if (confirm('Delete this category? Products will become uncategorized.')) {
                const btn = form.querySelector('button[type="submit"]');
                showButtonLoading(btn, 'Deleting...');
                return true;
            }
            return false;
        };

        // Existing View Logic (unchanged functionality, just kept for context)
        function toggleView(view) {
            const listView = document.getElementById('listView');
            const gridView = document.getElementById('gridView');
            const tableView = document.getElementById('tableView');
            const listBtn = document.getElementById('listViewBtn');
            const gridBtn = document.getElementById('gridViewBtn');
            const tableBtn = document.getElementById('tableViewBtn');

            listView.style.display = 'none';
            gridView.style.display = 'none';
            tableView.style.display = 'none';

            listBtn.classList.remove('active');
            gridBtn.classList.remove('active');
            if (tableBtn) tableBtn.classList.remove('active');

            if (view === 'list') {
                listView.style.display = 'block';
                listBtn.classList.add('active');
                localStorage.setItem('categoryView', 'list');
            } else if (view === 'grid') {
                gridView.style.display = 'grid';
                gridBtn.classList.add('active');
                localStorage.setItem('categoryView', 'grid');
            } else if (view === 'table') {
                tableView.style.display = 'block';
                if (tableBtn) tableBtn.classList.add('active');
                localStorage.setItem('categoryView', 'table');
            }
            updateResultCount();
        }

        function searchCategories() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;

            // Filter logic for all views...
            ['category-list-card', 'category-card', 'category-row'].forEach(className => {
                document.querySelectorAll('.' + className).forEach(el => {
                    const name = el.getAttribute('data-name');
                    const slug = el.getAttribute('data-slug');
                    const status = el.getAttribute('data-status');
                    const matchesSearch = name.includes(searchTerm) || slug.includes(searchTerm);
                    const matchesStatus = statusFilter === 'all' || status === statusFilter;
                    el.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
                });
            });
            updateResultCount();
        }

        function updateResultCount() {
            const currentView = localStorage.getItem('categoryView') || 'list';
            let visibleCount = 0;
            if (currentView === 'list') {
                visibleCount = Array.from(document.querySelectorAll('.category-list-card')).filter(el => el.style.display !== 'none').length;
            } else if (currentView === 'grid') {
                visibleCount = Array.from(document.querySelectorAll('.category-card')).filter(el => el.style.display !== 'none').length;
            } else if (currentView === 'table') {
                visibleCount = Array.from(document.querySelectorAll('.category-row')).filter(el => el.style.display !== 'none').length;
            }
            const resultCount = document.getElementById('resultCount');
            if (resultCount) resultCount.textContent = `${visibleCount} ${visibleCount === 1 ? 'category' : 'categories'}`;
        }
    </script>

    <style>
        .view-toggle { color: #6b7280; background-color: transparent; }
        .view-toggle.active { color: #1f2937; background-color: #f3f4f6; }
        .dark .view-toggle { color: #9ca3af; }
        .dark .view-toggle.active { color: #f9fafb; background-color: #374151; }
        #gridView.grid { display: grid; }
    </style>
</x-app-layout>
