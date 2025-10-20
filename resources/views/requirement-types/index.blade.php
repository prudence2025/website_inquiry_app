<x-layouts.app :title="__('Requirement Types')">
    <div class="flex flex-col gap-6">

        {{-- Header --}}
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-neutral-100 min-w-0 truncate">
                Requirement Types
            </h1>

            <flux:button as="a" href="{{ route('requirement-types.create') }}" variant="primary" class="flex-shrink-0">
                + Add Requirement Type
            </flux:button>
        </div>

        {{-- Requirement Type Table --}}
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-neutral-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3 w-1/4">Type Name</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3 w-[150px] text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($types as $type)
                        <tr class="border-b dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-800 transition">
                            <th scope="row" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $type->name }}
                            </th>
                            <td class="px-6 py-3">
                                {{ $type->description ?? 'No description' }}
                            </td>
                            <td class="px-6 py-3 text-center flex justify-center gap-3">
                                {{-- Edit --}}
                                <a href="{{ route('requirement-types.edit', $type) }}"
                                   class="font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                    Edit
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('requirement-types.destroy', $type) }}" method="POST" class="delete-form inline">
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
                            <td colspan="3" class="px-6 py-3 text-center text-gray-500 dark:text-gray-400">
                                No requirement types found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                 {{-- Pagination and Show All Button Container --}}
                <div class="mt-1 MX-auto px-6 mb-4">
                    <div>
                        {{-- Laravel Pagination --}}
                        @if($types instanceof \Illuminate\Pagination\LengthAwarePaginator)
                            {{ $types->links() }}
                        @endif
                    </div>
                
                    {{-- Show All Button (Only shows if pagination is necessary) --}}
                    @if(
                        $types instanceof \Illuminate\Pagination\LengthAwarePaginator && 
                        $types->lastPage() > 1
                    )
                    <div>
                        <a href="{{ request('show') === 'all' ? route('requirement-types.index') : route('requirement-types.index', ['show' => 'all']) }}"
                           class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                            {{ request('show') === 'all' ? 'Show Paginated' : 'Show All' }}
                        </a>
                    </div>
                    @endif
                </div>
            </table>
        </div>
    </div>

    {{-- SweetAlert2 Delete Confirmation --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
