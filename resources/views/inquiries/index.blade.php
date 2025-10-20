<x-layouts.app :title="__('Inquiries')">
<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-neutral-100 min-w-0 truncate">{{ __('Inquiries') }}</h1>
        
        <flux:button as="a" href="{{ route('inquiries.create') }}" variant="primary" class="flex-shrink-0">
            + {{ __('Add Inquiry') }}
        </flux:button>
    </div>

{{-- START: Filter Section --}}
<div class="p-4 bg-gray-50 dark:bg-neutral-800/50 rounded-lg border dark:border-neutral-700">
    <form action="{{ route('inquiries.index') }}" method="GET" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">

            {{-- Date From --}}
            <div>
                <flux:label>{{ __('Date From') }}</flux:label>
                <flux:input name="date_from" type="date" value="{{ request('date_from') }}" />
            </div>

            {{-- Date To --}}
            <div>
                <flux:label>{{ __('Date To') }}</flux:label>
                <flux:input name="date_to" type="date" value="{{ request('date_to') }}" />
            </div>

            {{-- Requirement Type --}}
            <div>
                <flux:label>{{ __('Requirement Type') }}</flux:label>
                <div x-data="singleSelect({ selectedId: @js(request('requirement_type')), options: @js($allRequirementTypes) })"
                     @click.outside="open = false" class="relative mt-1">
                    <div @click="open = !open"
                         class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md cursor-pointer min-h-[40px]">
                        <span x-text="selectedName || 'All Requirement Types'"></span>
                        <button x-show="selectedId" type="button" @click.stop="clearSelection()"
                                class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                    </div>
                    <div x-show="open" x-transition.origin.top.left
                         class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700"
                         style="display:none;">
                        <input type="text" x-model="search" placeholder="Search types..."
                               class="w-full p-2 mb-2 border-gray-300 rounded-md text-sm dark:bg-neutral-700 dark:text-gray-200">
                        <div class="max-h-60 overflow-y-auto">
                            <template x-for="option in filteredOptions" :key="option.id">
                                <div @click="select(option); open=false;"
                                     class="p-2 cursor-pointer text-black rounded-md hover:bg-gray-100 dark:hover:bg-neutral-700"
                                     x-text="option.name"></div>
                            </template>
                        </div>
                    </div>
                    <input type="hidden" name="requirement_type" :value="selectedName">
                </div>
            </div>

            {{-- Company --}}
            <div>
                <flux:label>{{ __('Company') }}</flux:label>
                <div x-data="singleSelect({ selectedId: @js(request('company_id')), options: @js($allCompanies) })"
                     @click.outside="open = false" class="relative mt-1">
                    <div @click="open = !open"
                         class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md cursor-pointer min-h-[40px]">
                        <span x-text="selectedName || 'All Companies'"></span>
                        <button x-show="selectedId" type="button" @click.stop="clearSelection()"
                                class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                    </div>
                    <div x-show="open" x-transition.origin.top.left
                         class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700"
                         style="display:none;">
                        <input type="text" x-model="search" placeholder="Search companies..."
                               class="w-full p-2 mb-2 border-gray-300 rounded-md text-sm dark:bg-neutral-700 dark:text-gray-200">
                        <div class="max-h-60 overflow-y-auto">
                            <template x-for="option in filteredOptions" :key="option.id">
                                <div @click="select(option); open=false;"
                                     class="p-2 cursor-pointer text-black rounded-md hover:bg-gray-100 dark:hover:bg-neutral-700"
                                     x-text="option.name"></div>
                            </template>
                        </div>
                    </div>
                    <input type="hidden" name="company_id" :value="selectedId">
                </div>
            </div>

            {{-- Process Level --}}
            <div>
                <flux:label>{{ __('Status') }}</flux:label>
                <flux:select name="process_level">
                    <option value="">{{ __('All Statuses') }}</option>
                    @foreach ($processLevels as $level)
                        <option value="{{ $level }}" @selected(request('process_level') === $level)>{{ $level }}</option>
                    @endforeach
                </flux:select>
            </div>

            {{-- Buttons --}}
            <div class="col-span-full flex gap-3 mt-3 justify-end">
                <flux:button type="submit" class="mt-3" variant="primary">Filter</flux:button>
                <flux:button as="a" class="mt-3" href="{{ route('inquiries.index') }}" variant="danger">Reset</flux:button>
               <flux:button type="submit" class="mt-3" name="export" value="csv" variant="primary">
                    <x-gmdi-download class="inline w-4 h-4 me-2" width="16" height="16" /> 
                    Export CSV
                </flux:button>
            </div>
        </div>
    </form>
</div>
{{-- END: Filter Section --}}



    {{-- Inquiry Table --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-neutral-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 min-w-[120px]">{{ __('ID') }}</th>
                    <th scope="col" class="px-6 py-3">{{ __('Date') }}</th>
                    <th scope="col" class="px-6 py-3">{{ __('Requirement Type') }}</th>
                    <th scope="col" class="px-6 py-3">{{ __('Customer / Contact') }}</th>
                    <th scope="col" class="px-6 py-3">{{ __('Company') }}</th>
                    <th scope="col" class="px-6 py-3">{{ __('Status') }}</th>
                    <th scope="col" class="px-6 py-3 w-[150px] text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($inquiries as $inquiry)
                    <tr class="border-b dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-800 transition">
                        {{-- ID --}}
                        <td class="px-6 py-2 text-gray-900 dark:text-white">
                             {{ $inquiry->id }}
                        </td>
                        
                        {{-- Date --}}
                        <td class="px-6 py-2 text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($inquiry->inquiry_date)->format('Y-m-d') }}
                        </td>

                        {{-- Requirement Type --}}
                        <td class="px-6 py-2 text-gray-900 dark:text-white font-medium">
                            {{ $inquiry->requirement_type }}
                        </td>

                        {{-- Customer / Contact (Using leadin-tight for spacing fix) --}}
                        <td class="px-6 py-2">
                            <p class="font-medium text-gray-900 dark:text-white leading-tight">
                                {{ $inquiry->customer->name ?? 'N/A' }}
                            </p>
                            @if ($inquiry->customer)
                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight">
                                    {{ $inquiry->customer->email ?? '' }} {{ $inquiry->customer->phone ? ' | ' . $inquiry->customer->phone : '' }}
                                </p>
                            @endif
                        </td>
                        
                        {{-- Company (Using leadin-tight for spacing fix) --}}
                        <td class="px-6 py-2">
                            <p class="leading-tight">{{ $inquiry->company->name ?? $inquiry->companny_name ?? 'N/A' }}</p>
                            @if ($inquiry->company && $inquiry->company->industries->isNotEmpty())
                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight">
                                    ({{ $inquiry->company->industries->first()->name }})
                                </p>
                            @endif
                        </td>
                        
                        {{-- Status/Level (UPDATED COLOR LOGIC) --}}
                        <td class="px-6 py-2 whitespace-nowrap">
                            @php
                                $status = $inquiry->process_level;
                                $colorClasses = '';

                                switch ($status) {
                                    case 'Settled':
                                        $colorClasses = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                        break;
                                    case 'Discussing':
                                    case 'Quoted':
                                        $colorClasses = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                                        break;
                                    case 'Received':
                                        $colorClasses = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                        break;
                                    case 'Dropped':
                                        $colorClasses = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                        break;
                                    default:
                                        $colorClasses = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                                }
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colorClasses }}">
                                {{ $inquiry->process_level }}
                            </span>
                        </td>

                        {{-- Actions (UPDATED FOR VERTICAL CENTERING) --}}
                        <td class="px-6 py-2 align-middle text-center">
                            <div class="flex justify-center items-center gap-3">
                                {{-- Edit --}}
                                <a href="{{ route('inquiries.edit', $inquiry) }}"
                                   class="font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                    Edit
                                </a>
        
                                {{-- Delete --}}
                                <form action="{{ route('inquiries.destroy', $inquiry) }}" method="POST" class="delete-form inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                            class="font-medium text-red-600 dark:text-red-400 hover:underline delete-btn">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr class="bg-white dark:bg-neutral-900">
                        <td colspan="6" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                            {{ __('No inquiries found. Use the button above to add a new one.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
            {{-- Pagination --}}
            <div class="mt-1 MX-auto px-6 mb-4">
                {{ $inquiries->links() }}
            </div>
        </table>
    </div>
</div>
<script>
function singleSelect(config) {
    return {
        open: false,
        search: '',
        selectedId: config.selectedId || null,
        options: config.options || [],
        get selectedName() {
            if (!this.selectedId) return null;
            const item = this.options.find(o => o.id == this.selectedId);
            return item ? item.name : null;
        },
        get filteredOptions() {
            return this.search === ''
                ? this.options
                : this.options.filter(o => o.name.toLowerCase().includes(this.search.toLowerCase()));
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

{{-- SweetAlert Delete Script (REST OF SCRIPT REMAINS UNCHANGED) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.delete-btn').forEach((btn) => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const form = this.closest('form');

            Swal.fire({
                title: '{{ __("Are you sure?") }}',
                text: "{{ __('This action cannot be undone.') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '{{ __("Yes, delete it!") }}',
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
            title: '{{ __("Success!") }}',
            text: '{{ session('success') }}',
            timer: 2000,
            showConfirmButton: false,
            background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827',
        });
    @endif
</script>


</x-layouts.app>