<x-layouts.app :title="__('Companies')">
    <div class="flex flex-col gap-6">

        {{-- Header --}}
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-neutral-100 min-w-0 truncate">Companies</h1>
            <flux:button as="a" href="{{ route('companies.create') }}" variant="primary" class="flex-shrink-0">
                + Add Company
            </flux:button>
        </div>

        {{-- START: Filter Section --}}
        <div class="p-4 bg-gray-50 dark:bg-neutral-800/50 rounded-lg border dark:border-neutral-700">
            <form action="{{ route('companies.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
                    
                    {{-- Company Filter --}}
                    <div>
                        <flux:label>{{ __('Company') }}</flux:label>
                        <div x-data="singleSelect({ selectedId: @js(request('company_id')), options: @js($allCompanies) })"
                             @click.outside="open = false" class="relative mt-1">
                            <div @click="open = !open" class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md  dark:bg-neutral-900 cursor-pointer min-h-[40px]">
                                <span x-text="selectedName || 'All Companies'"></span>
                                <button x-show="selectedId" type="button" @click.stop="clearSelection()" class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                            </div>
                            <div x-show="open" x-transition.origin.top.left class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700" style="display: none;">
                                <input type="text" x-model="search" placeholder="Search companies..." class="w-full p-2 mb-2 border-gray-300 dark:border-neutral-600 rounded-md text-sm bg-gray-50 dark:bg-neutral-700">
                                <div class="max-h-60 overflow-y-auto">
                                    <template x-for="option in filteredOptions" :key="option.id">
                                        <div @click="select(option); open = false;" class="p-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-neutral-700" x-text="option.name"></div>
                                    </template>
                                </div>
                            </div>
                            <input type="hidden" name="company_id" :value="selectedId">
                        </div>
                    </div>

                    {{-- Industry Filter (Unchanged) --}}
                    <div>
                            <flux:label>{{ __('Industry') }}</flux:label>
                            <div x-data="singleSelect({ selectedId: @js(request('industry_id')), options: @js($allIndustries) })"
                                @click.outside="open = false" class="relative mt-1">
                                <div @click="open = !open" class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md  dark:bg-neutral-900 cursor-pointer min-h-[40px]">
                                    <span x-text="selectedName || 'All Industries'"></span>
                                    <button x-show="selectedId" type="button" @click.stop="clearSelection()" class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                                </div>
                                <div x-show="open" x-transition.origin.top.left class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700" style="display: none;">
                                    <input type="text" x-model="search" placeholder="Search industries..." class="w-full p-2 mb-2 border-gray-300 dark:border-neutral-600 rounded-md text-sm bg-gray-50 dark:bg-neutral-700">
                                    <div class="max-h-60 overflow-y-auto">
                                        <template x-for="option in filteredOptions" :key="option.id">
                                            <div @click="select(option); open = false;" class="p-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-neutral-700" x-text="option.name"></div>
                                        </template>
                                    </div>
                                </div>
                                <input type="hidden" name="industry_id" :value="selectedId">
                            </div>
                    </div>      

                 {{-- Buttons --}}
                    <div>
                        <div class="h-6"></div> 
                        <div class="flex gap-3">
                            <flux:button type="submit" variant="primary">Filter</flux:button>
                            <flux:button as="a" href="{{ route('companies.index') }}" variant="danger">Reset</flux:button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        {{-- END: Filter Section --}}


        {{-- Company Table --}}
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            {{-- ... your table code remains exactly the same ... --}}
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-neutral-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Company Name</th>
                        <th scope="col" class="px-6 py-3">Industries</th>
                        <th scope="col" class="px-6 py-3 w-[150px] text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($companies as $company)
                        <tr class="border-b dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-800">
                            <th scope="row" class="px-6 py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $company->name }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $company->industries->pluck('name')->join(', ') ?: 'None' }}
                            </td>
                            <td class="px-6 py-3 text-center flex justify-center gap-3">
                                <a href="{{ route('companies.edit', $company) }}" class="font-medium text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                                <form action="{{ route('companies.destroy', $company) }}" method="POST" class="delete-form inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="font-medium text-red-600 dark:text-red-400 hover:underline delete-btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr class=" dark:bg-neutral-900">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No companies found for the selected filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
              {{-- Pagination and Show All Button Container --}}
                <div class="mt-1 MX-auto px-6 mb-4">
                    <div>
                        {{-- Laravel Pagination --}}
                        @if($companies instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $companies->links() }}
                        @endif
                    </div>
                
                    {{-- Show All Button (Only shows if pagination is necessary) --}}
                    @if(
                        $companies instanceof \Illuminate\Pagination\LengthAwarePaginator && 
                        $companies->lastPage() > 1
                    )
                    <div>
                        <a href="{{ request('show') === 'all' ? route('companies.index') : route('companies.index', ['show' => 'all']) }}"
                           class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                            {{ request('show') === 'all' ? 'Show Paginated' : 'Show All' }}
                        </a>
                    </div>
                    @endif
                </div>
            </table>
        </div>
    </div>

    {{-- Alpine.js for dropdowns --}}
    <script>
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
    </script>

    {{-- SweetAlert2 Delete Confirmation --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ... your SweetAlert2 script remains exactly the same ...
        document.querySelectorAll('.delete-btn').forEach((btn) => {
            btn.addEventListener('click', function (e) {
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
                    if (result.isConfirmed) {
                        form.submit();
                    }
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