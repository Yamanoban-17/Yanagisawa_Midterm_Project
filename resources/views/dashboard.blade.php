<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <!-- Session Messages (Success/Error Notifications) -->
        @if (session('success'))
            <div id="success-message" class="rounded-lg bg-green-100 p-4 text-sm text-green-800 dark:bg-green-900 dark:text-green-300" role="alert">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(() => {
                    const msg = document.getElementById('success-message');
                    if (msg) {
                        msg.classList.add('opacity-0');
                        setTimeout(() => msg.remove(), 500);
                    }
                }, 3000);
            </script>

        @endif

        <!-- Stats Cards -->
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Products</p>
                        <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-neutral-100">{{ $totalProducts ?? 0 }}</h3>
                    </div>
                    <div class="rounded-full bg-blue-100 p-3 dark:bg-blue-900/30">
                        <svg class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">In Stock Items</p>
                        <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-neutral-100">{{ $inStock ?? 0 }}</h3>
                    </div>
                    <div class="rounded-full bg-green-100 p-3 dark:bg-green-900/30">
                        <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Total Categories</p>
                        <h3 class="mt-2 text-3xl font-bold text-neutral-900 dark:text-neutral-100">{{ $totalCategories ?? 0 }}</h3>
                    </div>
                    <div class="rounded-full bg-purple-100 p-3 dark:bg-purple-900/30">
                        <svg class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inventory Management Section -->
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex h-full flex-col p-6">



            <div class="mb-4 flex justify-end">
                    <form method="GET" action="{{ route('products.export') }}" class="inline">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="category_filter" value="{{ request('category_filter') }}">

                        <button type="submit"
                                class="flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-700">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export to PDF
                        </button>
                    </form>
                </div>

                <!-- Add New Product Form -->
                <div class="mb-6 rounded-lg border border-neutral-200 bg-neutral-50 p-6 dark:border-neutral-700 dark:bg-neutral-900/50">
                    <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Add New Product</h2>
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-4 md:grid-cols-2">
                        @csrf
                        <div>
                            <label for="name" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Product Name</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter product name" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="sku" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">SKU</label>
                            <input type="text" id="sku" name="sku" value="{{ old('sku') }}" placeholder="Enter SKU (e.g., PROD-001)" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('sku')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="price" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Price</label>
                            <input type="number" id="price" name="price" value="{{ old('price') }}" step="0.01" min="0.01" placeholder="0.00" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('price')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="stock" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Stock Quantity</label>
                            <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" placeholder="0" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('stock')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="category_id" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Category</label>
                            <select id="category_id" name="category_id" class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                                <option value="">Select a category (Optional)</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="details" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Details</label>
                            <input type="text" id="details" name="details" value="{{ old('details') }}" placeholder="Product details (Optional)" class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            @error('details')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Photo Upload -->
                        <div class="md:col-span-2">
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                Employee Photo (Optional)
                            </label>
                            <input
                                type="file"
                                name="photo"
                                accept="image/jpeg,image/png,image/jpg"
                                class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:file:bg-blue-900/20 dark:file:text-blue-400"
                            >
                            <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                                JPG, PNG or JPEG. Max 2MB.
                            </p>
                            @error('photo')
                                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="md:col-span-2">
                            <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                                Add Product
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Search & Filter Section -->
                <div class="rounded-xl border mb-10 border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800">
                    <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Search & Filter Employees</h2>

                    <form action="{{ route('dashboard') }}" method="GET" class="grid gap-4 md:grid-cols-3">
                        <!-- Search Input -->
                        <div class="md:col-span-1">
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Search</label>
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search by name or email"
                                class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                            >
                        </div>

                        
                        <!-- Category Filter Dropdown -->
                        <div class="md:col-span-1">
                            <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Filter by Category</label>
                            <select
                                name="category_filter"
                                class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                            >
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_filter') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach 
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-end gap-2 md:col-span-1">
                            <button
                                type="submit"
                                class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700"
                            >
                                Apply Filters
                            </button>
                            <a
                                href="{{ route('dashboard') }}"
                                class="rounded-lg border border-neutral-300 px-4 py-2 text-sm font-medium text-neutral-700 transition-colors hover:bg-neutral-100 dark:border-neutral-600 dark:text-neutral-300 dark:hover:bg-neutral-700"
                            >
                                Clear
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Product List Table -->
                <div class="flex-1 overflow-auto">
                    <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Product Inventory</h2>
                    <div class="overflow-x-auto">
                        @if(isset($products) && $products->count() > 0)
                            <table class="w-full min-w-full">
                                <thead>
                                    <tr class="border-b border-neutral-200 bg-neutral-50 dark:border-neutral-700 dark:bg-neutral-900/50">
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">#</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Photo</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Name</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">SKU</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Price</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Stock</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Category</th>
                                        <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                    @foreach($products as $index => $product)
                                        <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-800/50">
                                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3">
                                            @if($product->photo)
                                                <img
                                                    src="{{ Storage::url($product->photo) }}"
                                                    alt="{{ $product->name }}"
                                                    class="h-12 w-12 rounded-full object-cover ring-2 ring-blue-100 dark:ring-blue-900"
                                                >
                                            @else
                                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                                    {{ strtoupper(substr($product->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </td>
                                            <td class="px-4 py-3 text-sm font-medium text-neutral-900 dark:text-neutral-100">{{ $product->name }}</td>
                                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">
                                                <span class="font-mono text-xs">{{ $product->sku }}</span>
                                            </td>
                                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">
                                                ₱{{ number_format($product->price, 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                @if($product->stock <= 5)
                                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                                        {{ $product->stock }} (Low)
                                                    </span>
                                                @elseif($product->stock <= 20)
                                                    <span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                        {{ $product->stock }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                        {{ $product->stock }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">
                                                {{ $product->category ? $product->category->name : '—' }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <button type="button" onclick="openEditModal({{ $product->id }}, '{{ addslashes($product->name) }}', '{{ addslashes($product->sku) }}', {{ $product->price }}, {{ $product->stock }}, {{ $product->category_id ?? 'null' }}, '{{ addslashes($product->details ?? '') }}', '{{ $product->photo ?? '' }}')" class="text-blue-600 transition-colors hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">Edit</button>
                                                <span class="mx-1 text-neutral-400">|</span>
                                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 transition-colors hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Trash</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="rounded-lg border border-neutral-200 bg-neutral-50 p-8 text-center dark:border-neutral-700 dark:bg-neutral-900/50">
                                <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="mt-4 text-sm font-medium text-neutral-900 dark:text-neutral-100">No products found</p>
                                <p class="mt-2 text-sm text-neutral-600 dark:text-neutral-400">Get started by adding your first product above.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="relative mx-4 w-full max-w-2xl rounded-xl border border-neutral-200 bg-white shadow-xl dark:border-neutral-700 dark:bg-neutral-800">
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-neutral-200 px-6 py-4 dark:border-neutral-700">
                <h3 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">Edit Product</h3>
                <button type="button" onclick="closeEditModal()" class="rounded-lg p-1 text-neutral-400 transition-colors hover:bg-neutral-100 hover:text-neutral-600 dark:hover:bg-neutral-700 dark:hover:text-neutral-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="editProductForm" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label for="edit_name" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Product Name</label>
                        <input type="text" id="edit_name" name="name" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="edit_sku" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">SKU</label>
                        <input type="text" id="edit_sku" name="sku" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                        @error('sku')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="edit_price" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Price</label>
                        <input type="number" id="edit_price" name="price" step="0.01" min="0.01" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                        @error('price')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="edit_stock" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Stock Quantity</label>
                        <input type="number" id="edit_stock" name="stock" min="0" required class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                        @error('stock')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="edit_category_id" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Category</label>
                        <select id="edit_category_id" name="category_id" class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                            <option value="">Select a category (Optional)</option>
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="edit_details" class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">Details</label>
                        <input type="text" id="edit_details" name="details" class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/20 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100">
                        @error('details')
                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Photo Upload in Edit Modal -->
                <div class="md:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-neutral-700 dark:text-neutral-300">
                        Product Photo
                    </label>

                    <!-- Current Photo Preview -->
                    <div id="currentPhotoPreview" class="mb-3"></div>

                    <input
                        type="file"
                        id="edit_photo"
                        name="photo"
                        accept="image/jpeg,image/png,image/jpg"
                        class="w-full rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm file:mr-4 file:rounded-md file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-blue-700 hover:file:bg-blue-100 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:file:bg-blue-900/20 dark:file:text-blue-400"
                    >
                    <p class="mt-1 text-xs text-neutral-500 dark:text-neutral-400">
                        Leave empty to keep current photo. JPG, PNG or JPEG. Max 2MB.
                    </p>
                </div>

                <!-- Modal Footer -->
                <div class="mt-6 flex gap-3">
                    <button type="submit" class="flex-1 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20">
                        Update Product
                    </button>
                    <button type="button" onclick="closeEditModal()" class="rounded-lg border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 transition-colors hover:bg-neutral-50 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-300 dark:hover:bg-neutral-700">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(productId, name, sku, price, stock, categoryId, details, photo) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editProductForm');
            const nameInput = document.getElementById('edit_name');
            const skuInput = document.getElementById('edit_sku');
            const priceInput = document.getElementById('edit_price');
            const stockInput = document.getElementById('edit_stock');
            const categoryInput = document.getElementById('edit_category_id');
            const detailsInput = document.getElementById('edit_details');

            // Set form action
            form.action = '{{ route("products.update", ":id") }}'.replace(':id', productId);

            // Populate form fields
            nameInput.value = name;
            skuInput.value = sku;
            priceInput.value = price;
            stockInput.value = stock;
            detailsInput.value = details || '';

            // Set category selection
            if (categoryId && categoryId !== 'null') {
                categoryInput.value = categoryId;
            } else {
                categoryInput.value = '';
            }

            // Display current photo in edit modal
            const photoPreview = document.getElementById('currentPhotoPreview');
            if (photo) {
                photoPreview.innerHTML = `
                    <div class="flex items-center gap-3 rounded-lg border border-neutral-200 p-3 dark:border-neutral-700">
                        <img src="/storage/${photo}" alt="${name}" class="h-16 w-16 rounded-full object-cover">
                        <div>
                            <p class="text-sm font-medium text-neutral-700 dark:text-neutral-300">Current Photo</p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">Upload new photo to replace</p>
                        </div>
                    </div>
                `;
            } else {
                photoPreview.innerHTML = `
                    <div class="rounded-lg border border-dashed border-neutral-300 p-4 text-center dark:border-neutral-600">
                        <p class="text-sm text-neutral-500 dark:text-neutral-400">No photo uploaded</p>
                    </div>
                `;
            }

            // Show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Prevent body scroll when modal is open
            document.body.style.overflow = 'hidden';
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // Restore body scroll
            document.body.style.overflow = 'auto';
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
            }
        });
    </script>
</x-layouts.app>
