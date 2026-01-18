<x-layouts.app :title="__('Trash')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        @if(session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-neutral-900 dark:text-neutral-100">Trash</h1>
                <p class="mt-1 text-sm text-neutral-600 dark:text-neutral-400">
                    Manage deleted items - restore or permanently delete
                </p>
            </div>
            <a href="{{ route('dashboard') }}"
               class="rounded-lg bg-neutral-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-neutral-700">
                Back to Dashboard
            </a>
        </div>

        <!-- Products Trash Section -->
        <div>
            <h2 class="mb-4 text-lg font-semibold text-neutral-900 dark:text-neutral-100">Deleted Products</h2>
            
            <div class="rounded-xl border border-neutral-200 bg-white p-6 dark:border-neutral-700 dark:bg-neutral-800 mb-6">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-red-100 p-3 dark:bg-red-900/30">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-neutral-600 dark:text-neutral-400">Products in Trash</p>
                        <h3 class="mt-1 text-2xl font-bold text-neutral-900 dark:text-neutral-100">{{ $products->count() }}</h3>
                    </div>
                </div>
            </div>

        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 bg-white dark:border-neutral-700 dark:bg-neutral-800">
            <div class="flex h-full flex-col p-6">
                @if($products->isEmpty())
                    <div class="flex flex-1 items-center justify-center rounded-lg border-2 border-dashed border-neutral-300 p-12 dark:border-neutral-600">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-neutral-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-neutral-900 dark:text-neutral-100">No deleted products</h3>
                            <p class="mt-1 text-sm text-neutral-500 dark:text-neutral-400">All products are safe.</p>
                        </div>
                    </div>
                @else
                    <div class="overflow-x-auto">
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
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Deleted At</th>
                                    <th class="px-4 py-3 text-left text-sm font-semibold text-neutral-700 dark:text-neutral-300">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                                @foreach($products as $product)
                                    <tr class="transition-colors hover:bg-neutral-50 dark:hover:bg-neutral-800/50">
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3">
                                            @if($product->photo)
                                                <img src="{{ Storage::url($product->photo) }}"
                                                     alt="{{ $product->name }}"
                                                     class="h-12 w-12 rounded-full object-cover ring-2 ring-blue-100 dark:ring-blue-900">
                                            @else
                                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 text-sm font-semibold text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                                    {{ strtoupper(substr($product->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-neutral-900 dark:text-neutral-100">{{ $product->name }}</td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">
                                            <span class="font-mono text-xs">{{ $product->sku }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">
                                            ₱{{ number_format($product->price, 2) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">
                                            {{ $product->stock }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">
                                            {{ $product->category ? $product->category->name : '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-400">
                                            {{ $product->deleted_at ? $product->deleted_at->format('M d, Y h:i A') : '—' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            <form action="{{ route('products.restore', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Restore this product?')">
                                                @csrf
                                                <button type="submit" class="text-green-600 transition-colors hover:text-green-700 dark:text-green-400 dark:hover:text-green-300">
                                                    Restore
                                                </button>
                                            </form>
                                            <span class="mx-1 text-neutral-400">|</span>
                                            <form action="{{ route('products.force-delete', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('PERMANENTLY delete this product? This cannot be undone!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 transition-colors hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Delete Forever</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>       
    </div>
</x-layouts.app>
