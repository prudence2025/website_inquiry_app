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
                                         class="p-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-neutral-700"
                                         x-text="option.name"></div>
                                </template>
                            </div>
                        </div>
                        <input type="hidden" name="requirement_type" :value="selectedName">
                    </div>
                </div>

                {{-- Assign To --}}
                <div>
                    <flux:label>{{ __('Assign To') }}</flux:label>
                    <div x-data="singleSelect({ selectedId: @js(request('receiver_name')), options: @js($allReceivers) })"
                         @click.outside="open = false" class="relative mt-1">
                        <div @click="open = !open"
                             class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md cursor-pointer min-h-[40px]">
                            <span x-text="selectedName || 'All Assign To'"></span>
                            <button x-show="selectedId" type="button" @click.stop="clearSelection()"
                                    class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                        </div>
                        <div x-show="open" x-transition.origin.top.left
                             class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700"
                             style="display:none;">
                            <input type="text" x-model="search" placeholder="Search assign to..."
                                   class="w-full p-2 mb-2 border-gray-300 rounded-md text-sm dark:bg-neutral-700 dark:text-gray-200">
                            <div class="max-h-60 overflow-y-auto">
                                <template x-for="option in filteredOptions" :key="option.id">
                                    <div @click="select(option); open=false;"
                                         class="p-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-neutral-700"
                                         x-text="option.name"></div>
                                </template>
                            </div>
                        </div>
                        <input type="hidden" name="receiver_name" :value="selectedName">
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
                                         class="p-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-neutral-700"
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
                <div class="col-span-full flex gap-3 justify-end">
                    <flux:button type="submit" variant="primary">Filter</flux:button>
                    <flux:button as="a" href="{{ route('inquiries.index') }}" variant="danger">Reset</flux:button>
                   <flux:button type="submit" name="export" value="csv" variant="primary">
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
                    <th scope="col" class="px-6 py-3">{{ __('ID') }}</th>
                    <th scope="col" class="px-6 py-3 min-w-[120px]">{{ __('Date') }}</th>
                    <th scope="col" class="px-6 py-3">{{ __('Requirement Type') }}</th>
                    <th scope="col" class="px-6 py-3">{{ __('Customer / Contact') }}</th>
                    <th scope="col" class="px-6 py-3">{{ __('Company') }}</th>
                    <th scope="col" class="px-6 py-3 text-center">{{ __('Status') }}</th>
                    <th scope="col" class="px-6 py-3 w-[150px] text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody x-data="inquiryTable()">
                @forelse ($inquiries as $inquiry)
                    <tr 
                        class="border-b dark:border-neutral-700 transition cursor-pointer hover:bg-blue-50/50 dark:hover:bg-blue-900/20"
                        :class="selectedId === {{ $inquiry->id }} ? 'bg-blue-100 dark:bg-blue-900/40' : ''"
                        @click="openPopup($event, {
                            id: {{ $inquiry->id }},
                            date: '{{ \Carbon\Carbon::parse($inquiry->inquiry_date)->format('Y/m/d') }}',
                            type: '{{ $inquiry->requirement_type }}',
                            receiver: '{{ $inquiry->receiver_name }}',
                            company: '{{ $inquiry->company->name ?? 'N/A' }}',
                            industry: '{{ $inquiry->company && $inquiry->company->industries->isNotEmpty() ? $inquiry->company->industries->first()->name : 'N/A' }}',
                            customer: '{{ $inquiry->customer->name ?? 'N/A' }}',
                            email: '{{ $inquiry->customer->email ?? '-' }}',
                            phone: '{{ $inquiry->customer->phone ?? '-' }}',
                            status: '{{ $inquiry->process_level }}',
                            amount: '{{ number_format($inquiry->amount ?? 0, 2) }}',
                            info: @js($inquiry->more_info ?? '-')
                        })"
                    >
                        <td class="px-6 py-2 text-gray-900 dark:text-white">{{ $inquiry->id }}</td>
                        <td class="px-6 py-2 text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($inquiry->inquiry_date)->format('Y/m/d') }}</td>
                        <td class="px-6 py-2 font-medium text-gray-900 dark:text-white">{{ $inquiry->requirement_type }}</td>
                        <td class="px-6 py-2">
                            <p class="font-medium text-gray-900 dark:text-white leading-tight">{{ $inquiry->customer->name ?? 'N/A' }}</p>
                            @if ($inquiry->customer)
                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight">
                                    {{ $inquiry->customer->email ?? '' }} {{ $inquiry->customer->phone ? ' | ' . $inquiry->customer->phone : '' }}
                                </p>
                            @endif
                        </td>
                        <td class="px-6 py-2 leading-tight">
                            <p>{{ $inquiry->company->name ?? 'N/A' }}</p>
                        @if ($inquiry->company && $inquiry->company->industries->isNotEmpty())
                            <p class="text-xs text-gray-500 dark:text-gray-400">({{ $inquiry->company->industries->first()->name }})</p>
                        @endif
                    </td>
                    <td class="px-6 py-2 text-center">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @switch($inquiry->process_level)
                                @case('Settled') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 @break
                                @case('Discussing') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300 @break
                                @case('Quoted') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 @break
                                @case('Received') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 @break
                                @case('Dropped') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @break
                                @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                            @endswitch">
                            {{ $inquiry->process_level }}
                        </span>
                    </td>
                    <td class="px-6 py-2 text-center">
                        <div class="flex justify-center items-center gap-3" @click.stop>
                            <a href="{{ route('inquiries.edit', $inquiry) }}" class="font-medium text-blue-600 dark:text-blue-400 hover:underline">Edit</a>
                            <form action="{{ route('inquiries.destroy', $inquiry) }}" method="POST" class="inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="font-medium text-red-600 dark:text-red-400 hover:underline delete-btn">Delete</button>
                            </form>
                        </div>
                    </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                            {{ __('No inquiries found.') }}
                        </td>
                    </tr>
                @endforelse
                
            {{-- MODAL POPUP --}}
            <template x-if="popupOpen">
                <div class="fixed inset-0 z-50 flex items-center justify-center">
                    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="closePopup()"></div>
            
                    <div x-show="popupOpen"
                         x-transition.scale.origin.center
                         class="relative bg-white dark:bg-neutral-900 text-gray-900 dark:text-gray-100
                                w-full max-w-xl rounded-xl shadow-2xl border border-gray-200 dark:border-neutral-700 p-6">
            
                        {{-- Header --}}
                        <div class="flex justify-between items-center border-b border-gray-200 dark:border-neutral-700 pb-3 mb-4">
                            <h2 class="text-lg font-semibold">
                                Inquiry <span class="text-blue-600 dark:text-blue-400">#<span x-text="selectedInquiry.id"></span></span>
                            </h2>
                            <button @click="closePopup()" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xl">&times;</button>
                        </div>
            
                        {{-- Content --}}
                        <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Date:</span><br> 
                                <span x-text="selectedInquiry.date"></span>
                            </p>
                            <div>
                                <span class="font-semibold text-gray-600 dark:text-gray-300">Status:</span><br>
                                <template x-if="selectedInquiry.status">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full mt-1"
                                        :class="{
                                            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300': selectedInquiry.status === 'Settled',
                                            'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300': selectedInquiry.status === 'Discussing',
                                            'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300': selectedInquiry.status === 'Quoted',
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300': selectedInquiry.status === 'Received',
                                            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300': selectedInquiry.status === 'Dropped',
                                            'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': !['Settled','Discussing','Quoted','Received','Dropped'].includes(selectedInquiry.status)
                                        }">
                                        <span x-text="selectedInquiry.status"></span>
                                    </span>
                                </template>
                            </div>
            
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Assign To:</span><br> 
                                <span x-text="selectedInquiry.receiver"></span>
                            </p>
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Requirement Type:</span><br> 
                                <span x-text="selectedInquiry.type"></span>
                            </p>
            
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Company:</span><br> 
                                <span x-text="selectedInquiry.company"></span>
                            </p>
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Industry:</span><br> 
                                <span x-text="selectedInquiry.industry"></span>
                            </p>
            
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Customer:</span><br> 
                                <span x-text="selectedInquiry.customer"></span>
                            </p>
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Phone:</span><br> 
                                <span x-text="selectedInquiry.phone"></span>
                            </p>
            
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Email:</span><br> 
                                <span x-text="selectedInquiry.email"></span>
                            </p>
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Amount:</span><br> 
                                LKR <span x-text="selectedInquiry.amount"></span>
                            </p>
                        </div>
            
                        {{-- More Info --}}
                        <div class="mt-5 bg-gray-50 dark:bg-neutral-800/60 p-3 rounded-md">
                            <p class="font-semibold text-gray-700 dark:text-gray-200 mb-1">More Info:</p>
                            <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-300" x-text="selectedInquiry.info || '—'"></p>
                        </div>
            
                        {{-- Buttons --}}
                        <div class="flex justify-end gap-3 mt-6">
                            <button @click="closePopup()" 
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-neutral-800 dark:hover:bg-neutral-700 text-gray-800 dark:text-gray-300 rounded-md text-sm font-medium transition">
                                Cancel
                            </button>
                            <a :href="`/inquiries/${selectedInquiry.id}/edit`"
                               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium transition">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </template>
            </tbody>
            {{-- Pagination --}}
            <div class="mt-1 MX-auto px-6 mb-4">
                {{ $inquiries->links() }}
            </div>
        </table>
    </div>
</div>


<!-- view model scripts  -->
<script>
function inquiryTable() {
    return {
        popupOpen: false,
        selectedId: null,
        selectedInquiry: {},
        openPopup(event, data) {
            // prevent popup when clicking buttons, links, icons
            if (event.target.closest('a, button, form')) return;

            this.selectedId = data.id;
            this.selectedInquiry = data;
            this.popupOpen = true;
        },
        closePopup() {
            this.popupOpen = false;
            this.selectedId = null;
        }
    };
    }
    </script>

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