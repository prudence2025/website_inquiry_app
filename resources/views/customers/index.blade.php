<x-layouts.app :title="__('Customers')">
    <div class="flex flex-col gap-6">

        {{-- Header --}}
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-neutral-100 min-w-0 truncate">Customers</h1>
            <flux:button as="a" href="{{ route('customers.create') }}" variant="primary" class="flex-shrink-0">
                + Add Customer
            </flux:button>
        </div>

        {{-- Filter Section --}}
        <div class="p-4 bg-gray-50 dark:bg-neutral-800/50 rounded-lg border dark:border-neutral-700">
            <form action="{{ route('customers.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                    {{-- Search --}}
                    <div>
                        <flux:label for="search_input">{{ __('Search (Name, Email, Position)') }}</flux:label>
                        <input type="text" name="search" id="search_input" value="{{ request('search') }}"
                               placeholder="Search customers..."
                               class="mt-1 w-full p-2.5 border border-gray-300 dark:border-neutral-700 rounded-md dark:bg-neutral-900 text-sm dark:text-white">
                    </div>

                    {{-- Company Filter --}}
                    <div>
                        <flux:label>{{ __('Company') }}</flux:label>
                        <div x-data="singleSelect({ selectedId: @js(request('company_id')), options: @js($allCompanies) })"
                             @click.outside="open = false" class="relative mt-1">
                            <div @click="open = !open"
                                 class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md dark:bg-neutral-900 cursor-pointer min-h-[40px]">
                                <span x-text="selectedName || 'All Companies'"></span>
                                <button x-show="selectedId" type="button" @click.stop="clearSelection()"
                                        class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                            </div>
                            <div x-show="open" x-transition.origin.top.left
                                 class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700"
                                 style="display: none;">
                                <input type="text" x-model="search" placeholder="Search companies..."
                                       class="w-full p-2 mb-2 border-gray-300 dark:border-neutral-600 rounded-md text-sm bg-gray-50 dark:bg-neutral-700">
                                <div class="max-h-40 overflow-y-auto">
                                    <template x-for="option in filteredOptions" :key="option.id">
                                        <div @click="select(option); open = false;"
                                             class="p-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-neutral-700"
                                             x-text="option.name"></div>
                                    </template>
                                </div>
                            </div>
                            <input type="hidden" name="company_id" :value="selectedId">
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div>
                        <div class="h-6"></div>
                        <div class="flex gap-3">
                            <flux:button type="submit" variant="primary">Filter</flux:button>
                            <flux:button as="a" href="{{ route('customers.index') }}" variant="danger">Reset</flux:button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Pagination and Show All Button Container --}}
        <div class="mt-1 MX-auto px-6 ">
            <div>
                {{-- Laravel Pagination --}}
                @if($customers instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    {{ $customers->links() }}
                @endif
            </div>
           {{-- Show All / Show Paginated --}}
            @if($customers instanceof \Illuminate\Pagination\LengthAwarePaginator && $customers->lastPage() > 1)
                <div>
                    <a href="{{ request('show') === 'all' ? route('customers.index') : route('customers.index', ['show' => 'all']) }}"
                       class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                        {{ request('show') === 'all' ? 'Show Paginated' : 'Show All' }}
                    </a>
                </div>
            @endif
        </div>

        {{-- Customer Table --}}
        <div class="relative overflow-x-auto shadow-md" x-data="customerTable()">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-neutral-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-1/4">Customer Name</th>
                        <th scope="col" class="px-6 py-3">Email / Phone</th>
                        <th scope="col" class="px-6 py-3 w-1/4">Company / Position</th>
                        <th scope="col" class="px-6 py-3 w-[150px] text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr class="border-b dark:border-neutral-700 transition cursor-pointer hover:bg-blue-50/50 dark:hover:bg-blue-900/20"
                            :class="selectedCustomer?.id === {{ $customer->id }} ? 'bg-blue-100 dark:bg-blue-900/40' : ''"
                            @click="openPopup($event, {
                                id: {{ $customer->id }},
                                name: '{{ $customer->name }}',
                                email: '{{ $customer->email ?? '' }}',
                                phone: '{{ $customer->phone ?? '' }}',
                                position: '{{ $customer->position ?? '' }}',
                                notes: `{{ addslashes($customer->notes ?? '') }}`,
                                company: '{{ $customer->company->name ?? 'N/A' }}'
                            })">
                            <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $customer->name }}
                            </th>
                            <td class="px-6 py-3">
                                <p class="text-gray-900 dark:text-white">{{ $customer->email ?? 'No Email' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->phone ?? 'No Phone' }}</p>
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-gray-900 dark:text-white">{{ $customer->company->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $customer->position ?? 'No Position' }}</p>
                            </td>
                            <td class="px-6 py-3 text-center flex justify-center gap-3" @click.stop>
                                <a href="{{ route('customers.edit', $customer) }}"
                                   class="font-medium text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="delete-form inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="font-medium text-red-600 dark:text-red-400 hover:underline delete-btn">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class="bg-white dark:bg-neutral-900">
                            <td colspan="4" class="px-6 py-3 text-center text-gray-500 dark:text-gray-400">
                                No customers found for the selected filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Customer Popup --}}
            <template x-if="popupOpen">
                <div class="fixed inset-0 z-50 flex items-center justify-center">
                    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="closePopup()"></div>
                    <div x-show="popupOpen"
                         x-transition.scale.origin.center
                         class="relative bg-white dark:bg-neutral-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-neutral-700 w-full max-w-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Customer Details</h2>
                            <button @click="closePopup()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">✕</button>
                        </div>

                        <div class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Name:</span><br>
                                <span x-text="selectedCustomer.name"></span></p>
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Company:</span><br>
                                <span x-text="selectedCustomer.company"></span></p>
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Email:</span><br>
                                <span x-text="selectedCustomer.email || '—'"></span></p>
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Phone:</span><br>
                                <span x-text="selectedCustomer.phone || '—'"></span></p>
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Position:</span><br>
                                <span x-text="selectedCustomer.position || '—'"></span></p>
                        </div>

                        {{-- Notes --}}
                        <div class="mt-5 bg-gray-50 dark:bg-neutral-800/60 p-3 rounded-md">
                            <p class="font-semibold text-gray-700 dark:text-gray-200 mb-1">Notes:</p>
                            <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300 whitespace-pre-line max-h-40 overflow-y-auto"
                               x-text="selectedCustomer.notes || 'No notes provided.'"></p>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button @click="closePopup()"
                                    class="px-4 py-2 bg-gray-200 dark:bg-neutral-800 text-gray-800 dark:text-gray-300 rounded-md text-sm hover:bg-gray-300 dark:hover:bg-neutral-700 transition">
                                Close
                            </button>
                             <a :href="`/customers/${selectedCustomer.id}/edit`"
                               class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700 transition">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>

    {{-- Alpine Scripts --}}
    <script>
        function customerTable() {
            return {
                popupOpen: false,
                selectedCustomer: null,
                openPopup(event, data) {
                    if (event.target.closest('a, button, form')) return;
                    this.selectedCustomer = data;
                    this.popupOpen = true;
                },
                closePopup() {
                    this.popupOpen = false;
                    this.selectedCustomer = null;
                }
            };
        }

        function singleSelect(config) {
            return {
                open: false,
                search: '',
                selectedId: config.selectedId || null,
                options: config.options || [],
                get selectedName() {
                    if (!this.selectedId) return null;
                    const selected = this.options.find(o => o.id == this.selectedId);
                    return selected ? selected.name : null;
                },
                get filteredOptions() {
                    if (this.search === '') return this.options;
                    return this.options.filter(o => o.name.toLowerCase().includes(this.search.toLowerCase()));
                },
                select(option) {
                    this.selectedId = option.id;
                    this.open = false;
                },
                clearSelection() {
                    this.selectedId = null;
                }
            }
        }

        // SweetAlert Delete
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                    color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827',
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false,
            background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827',
        });
        @endif
    </script>
</x-layouts.app>
