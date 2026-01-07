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
    <div class="p-4 bg-gray-50 dark:bg-neutral-800/50 rounded-lg border dark:border-neutral-700 shadow-sm">
        <form action="{{ route('inquiries.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 items-end">
    
                {{-- Date From --}}
                <div>
                    <flux:label>{{ __('Date From') }}</flux:label>
                    <flux:input name="date_from" type="date" value="{{ request('date_from') }}" class="bg-transparent" />
                </div>
    
                {{-- Date To --}}
                <div>
                    <flux:label>{{ __('Date To') }}</flux:label>
                    <flux:input name="date_to" type="date" value="{{ request('date_to') }}" class="bg-transparent" />
                </div>
    
                {{-- Requirement Type --}}
                <div>
                    <flux:label>{{ __('Requirement Type') }}</flux:label>
                    <div x-data="singleSelect({ selectedId: @js(request('requirement_type')), options: @js($allRequirementTypes) })"
                         @click.outside="open = false" class="relative mt-1">
                        <div @click="open = !open"
                             class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md bg-transparent cursor-pointer min-h-[40px]">
                            <span x-text="selectedName || 'All Requirement Types'"></span>
                            <button x-show="selectedId" type="button" @click.stop="clearSelection()"
                                    class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                        </div>
                        <div x-show="open" x-transition.origin.top.left
                             class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700"
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
                             class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md bg-transparent cursor-pointer min-h-[40px]">
                            <span x-text="selectedName || 'All Assign To'"></span>
                            <button x-show="selectedId" type="button" @click.stop="clearSelection()"
                                    class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                        </div>
                        <div x-show="open" x-transition.origin.top.left
                             class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700"
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
                             class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md bg-transparent cursor-pointer min-h-[40px]">
                            <span x-text="selectedName || 'All Companies'"></span>
                            <button x-show="selectedId" type="button" @click.stop="clearSelection()"
                                    class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                        </div>
                        <div x-show="open" x-transition.origin.top.left
                             class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700"
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

                {{-- Factory Location --}}
                <div>
                    <flux:label>{{ __('Factory Location') }}</flux:label>
                    <div x-data="singleSelect({ selectedId: @js(request('factory_location')), options: @js($allFactoryLocations) })"
                        @click.outside="open = false" class="relative mt-1">
                        <div @click="open = !open"
                            class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md bg-transparent cursor-pointer min-h-[40px]">
                            <span x-text="selectedName || 'All Factory Locations'"></span>
                            <button x-show="selectedId" type="button" @click.stop="clearSelection()"
                                class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                        </div>
                        <div x-show="open" x-transition.origin.top.left
                            class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700"
                            style="display: none;">
                            <input type="text" x-model="search" placeholder="Search locations..."
                                class="w-full p-2 mb-2 border-gray-300 dark:border-neutral-600 rounded-md text-sm bg-gray-50 dark:bg-neutral-700">
                            <div class="max-h-60 overflow-y-auto">
                                <template x-for="option in filteredOptions" :key="option.id">
                                    <div @click="select(option); open = false;"
                                        class="p-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-neutral-700"
                                        x-text="option.name"></div>
                                </template>
                            </div>
                        </div>
                        <input type="hidden" name="factory_location" :value="selectedId">
                    </div>
                </div>
    
                {{-- Process Level --}}
                <div>
                    <flux:label>{{ __('Status') }}</flux:label>
                    <flux:select name="process_level" class="bg-transparent">
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
                            inquiry_date: '{{ $inquiry->inquiry_date }}',
                            date: '{{ \Carbon\Carbon::parse($inquiry->inquiry_date)->format('Y/m/d') }}',
                            type: '{{ $inquiry->requirement_type }}',
                            requirement_type: '{{ $inquiry->requirement_type }}', // Alias for edit
                            receiver: '{{ $inquiry->receiver_name }}',
                            receiver_name: '{{ $inquiry->receiver_name }}', // Alias for edit
                            company_id: '{{ $inquiry->company_id }}',
                            company: '{{ $inquiry->company->name ?? 'N/A' }}',
                            company_name: '{{ $inquiry->company->name ?? '' }}', // Alias for edit
                            factory_location: '{{ $inquiry->company->factory_location ?? '-' }}',
                            office_location: '{{ $inquiry->company->office_location ?? '-' }}',
                            industry: '{{ $inquiry->company && $inquiry->company->industries->isNotEmpty() ? $inquiry->company->industries->first()->name : 'N/A' }}',
                            customer_id: '{{ $inquiry->customer_id }}',
                            customer: '{{ $inquiry->customer->name ?? 'N/A' }}',
                            customer_name: '{{ $inquiry->customer->name ?? '' }}', // Alias for edit
                            email: '{{ $inquiry->customer->email ?? '-' }}',
                            phone: '{{ $inquiry->customer->phone ?? '-' }}',
                            status: '{{ $inquiry->process_level }}',
                            process_level: '{{ $inquiry->process_level }}', // Alias for edit
                            amount_val: '{{ $inquiry->amount ?? 0 }}',
                            amount: '{{ $inquiry->amount ?? 0 }}', // Raw amount for edit
                            amount_formatted: '{{ number_format($inquiry->amount ?? 0, 2) }}', // Display amount
                            info: @js($inquiry->more_info ?? ''),
                            more_info: @js($inquiry->more_info ?? '') // Alias for edit
                        })"
                            type: '{{ $inquiry->requirement_type }}',
                            receiver: '{{ $inquiry->receiver_name }}',
                            company: '{{ $inquiry->company->name ?? 'N/A' }}',
                            factory_location: '{{ $inquiry->company->factory_location ?? '-' }}',
                            office_location: '{{ $inquiry->company->office_location ?? '-' }}',
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
                            <button @click.stop="openEditPopup($event, {
                                id: {{ $inquiry->id }},
                                inquiry_date: '{{ $inquiry->inquiry_date }}',
                                requirement_type: '{{ $inquiry->requirement_type }}',
                                receiver_name: '{{ $inquiry->receiver_name }}',
                                company_id: '{{ $inquiry->company_id }}',
                                company_name: '{{ $inquiry->company->name ?? '' }}',
                                customer_id: '{{ $inquiry->customer_id }}',
                                customer_name: '{{ $inquiry->customer->name ?? '' }}',
                                process_level: '{{ $inquiry->process_level }}',
                                amount: '{{ $inquiry->amount }}',
                                more_info: @js($inquiry->more_info ?? '')
                            })" class="font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                Edit
                            </button>
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
                                w-full max-w-4xl rounded-xl shadow-2xl border border-gray-200 dark:border-neutral-700 p-6">
            
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
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Factory Location:</span><br> 
                                <span x-text="selectedInquiry.factory_location"></span>
                            </p>
                            <p><span class="font-semibold text-gray-600 dark:text-gray-300">Office Location:</span><br> 
                                <span x-text="selectedInquiry.office_location"></span>
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
                                LKR <span x-text="selectedInquiry.amount_formatted"></span>
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
                            <button @click="closePopup(); openEditPopup($event, selectedInquiry)"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium transition">
                                Edit
                            </button>
                        </div>
                    </div>
                </div>
            </template>

            {{-- EDIT MODAL POPUP --}}
            <template x-if="editOpen">
                <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
                    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" @click="closeEditPopup()"></div>
                    
                    <div class="relative bg-white dark:bg-neutral-900 w-full max-w-4xl rounded-xl shadow-2xl border border-gray-200 dark:border-neutral-700 p-6" x-trap.noscroll="editOpen">
                        
                        <div class="flex justify-between items-center mb-4 pb-2 border-b dark:border-neutral-700">
                            <h2 class="text-xl font-bold dark:text-neutral-100">Edit Inquiry #<span x-text="editForm.id"></span></h2>
                            <button @click="closeEditPopup()" class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
                        </div>

                        <form @submit.prevent="saveEdit" class="space-y-4">
                            
                            {{-- Date & Receiver --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <flux:label>{{ __('Inquiry Date') }}</flux:label>
                                    <input type="date" x-model="editForm.inquiry_date" class="w-full p-2 border rounded bg-gray-50 text-gray-900 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white" required>
                                </div>
                                <div>
                                    <flux:label>{{ __('Assign To') }}</flux:label>
                                    <select x-model="editForm.receiver_name" class="w-full p-2 border rounded bg-gray-50 text-gray-900 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white" required>
                                        <option value="">Select Assign To</option>
                                        <template x-for="r in allReceivers" :key="r.id">
                                            <option :value="r.id" x-text="r.name" :selected="r.id == editForm.receiver_name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            {{-- Requirement --}}
                            <div>
                                <flux:label>{{ __('Requirement Type') }}</flux:label>
                                <div class="relative">
                                    <input type="text" x-model="editRequirementSearch" @input="filterRequirementTypes()" @focus="showRequirementDropdown=true" @click.outside="showRequirementDropdown=false"
                                           class="w-full p-2 border rounded bg-gray-50 text-gray-900 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white" placeholder="Search requirement type...">
                                    <div x-show="showRequirementDropdown" class="absolute z-10 w-full mt-1 bg-white dark:bg-neutral-800 border dark:border-neutral-700 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                        <template x-for="rt in filteredRequirementTypes" :key="rt.id">
                                            <div @click="selectEditRequirement(rt)" class="p-2 hover:bg-gray-100 dark:hover:bg-neutral-700 cursor-pointer" x-text="rt.name"></div>
                                        </template>
                                        <div x-show="filteredRequirementTypes.length === 0" class="p-2 text-gray-500 dark:text-gray-400 text-sm italic">No results found</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Company --}}
                            <div>
                                <flux:label>{{ __('Company') }}</flux:label>
                                <div class="relative">
                                    <input type="text" x-model="editCompanySearch" @input="filterCompanies()" @focus="showCompanyDropdown=true" @click.outside="showCompanyDropdown=false"
                                           class="w-full p-2 border rounded bg-gray-50 text-gray-900 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white" placeholder="Search company...">
                                    <div x-show="showCompanyDropdown" class="absolute z-10 w-full mt-1 bg-white dark:bg-neutral-800 border dark:border-neutral-700 rounded-md shadow-lg max-h-60 overflow-y-auto">
                                        <template x-for="c in filteredCompanies" :key="c.id">
                                            <div @click="selectEditCompany(c)" class="p-2 hover:bg-gray-100 dark:hover:bg-neutral-700 cursor-pointer" x-text="c.name"></div>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            {{-- Customer (Dynamic) --}}
                            <div>
                                <flux:label>{{ __('Customer') }}</flux:label>
                                <select x-model="editForm.customer_id" class="w-full p-2 border rounded bg-gray-50 text-gray-900 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white" :disabled="!editForm.company_id">
                                    <option value="">Select Customer</option>
                                    <template x-for="cust in customersForCompany" :key="cust.id">
                                        <option :value="cust.id" x-text="cust.name" :selected="cust.id == editForm.customer_id"></option>
                                    </template>
                                </select>
                                <p x-show="!editForm.company_id" class="text-xs text-gray-500 mt-1">Select a company first.</p>
                                <p x-show="loadingCustomers" class="text-xs text-blue-500 mt-1">Loading customers...</p>
                            </div>

                            {{-- Status & Amount --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <flux:label>{{ __('Status') }}</flux:label>
                                    <select x-model="editForm.process_level" class="w-full p-2 border rounded bg-gray-50 text-gray-900 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white" required>
                                        @foreach ($processLevels as $level)
                                            <option value="{{ $level }}">{{ $level }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <flux:label>{{ __('Amount (LKR)') }}</flux:label>
                                    <input type="number" step="0.01" x-model="editForm.amount" class="w-full p-2 border rounded bg-gray-50 text-gray-900 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white">
                                </div>
                            </div>

                            {{-- More Info --}}
                            <div>
                                <flux:label>{{ __('More Info') }}</flux:label>
                                <textarea x-model="editForm.more_info" rows="3" class="w-full p-2 border rounded bg-gray-50 text-gray-900 dark:bg-neutral-800 dark:border-neutral-700 dark:text-white"></textarea>
                            </div>

                            <div class="flex justify-end gap-3 pt-2">
                                <button @click="closeEditPopup()" 
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-neutral-800 dark:hover:bg-neutral-700 text-gray-800 dark:text-gray-300 rounded-md text-sm font-medium transition">
                                Cancel
                            </button>
                                <flux:button type="submit" variant="primary">Save Changes</flux:button>
                            </div>
                        </form>
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
        editOpen: false,
        selectedId: null,
        selectedInquiry: {},
        
        // Data sources from PHP
        allCompanies: @js($allCompanies),
        allReceivers: @js($allReceivers),
        allRequirementTypes: @js($allRequirementTypes),
        
        // Edit state
        editForm: {
            id: null,
            inquiry_date: '',
            receiver_name: '',
            requirement_type: '',
            company_id: '',
            customer_id: '',
            process_level: '',
            amount: '',
            more_info: ''
        },
        
        // Company Autocomplete in Edit
        editCompanySearch: '',
        showCompanyDropdown: false,
        filteredCompanies: [],

        // Requirement Autocomplete in Edit
        editRequirementSearch: '',
        showRequirementDropdown: false,
        filteredRequirementTypes: [],
        
        // Customer Loading
        customersForCompany: [],
        loadingCustomers: false,

        init() {
            this.filteredCompanies = this.allCompanies;
            this.filteredRequirementTypes = this.allRequirementTypes;
        },

        openPopup(event, data) {
            if (event.target.closest('a, button, form')) return;
            this.selectedId = data.id;
            this.selectedInquiry = data;
            this.popupOpen = true;
        },

        closePopup() {
            this.popupOpen = false;
            this.selectedId = null;
        },
        
        openEditPopup(event, data) {
            this.editForm = { ...data };
            this.editOpen = true;
            
            // Setup Company Search Info
            this.editCompanySearch = data.company_name || '';
            this.filterCompanies();

            // Setup Requirement Search Info
            this.editRequirementSearch = data.requirement_type || '';
            this.filterRequirementTypes();
            
            // Fetch Customers
            if(this.editForm.company_id) {
                this.fetchCustomers(this.editForm.company_id);
            } else {
                this.customersForCompany = [];
            }
        },
        
        closeEditPopup() {
            this.editOpen = false;
            this.editForm = { id: null, inquiry_date: '', receiver_name: '', requirement_type: '', company_id: '', customer_id: '', process_level: '', amount: '', more_info: '' };
        },
        
        filterCompanies() {
            if(this.editCompanySearch === '') {
                this.filteredCompanies = this.allCompanies;
            } else {
                this.filteredCompanies = this.allCompanies.filter(c => c.name.toLowerCase().includes(this.editCompanySearch.toLowerCase()));
            }
        },
        
        selectEditCompany(company) {
            this.editForm.company_id = company.id;
            this.editCompanySearch = company.name;
            this.showCompanyDropdown = false;
            // Fetch customers
            this.fetchCustomers(company.id);
            this.editForm.customer_id = '';
        },

        filterRequirementTypes() {
            if(this.editRequirementSearch === '') {
                this.filteredRequirementTypes = this.allRequirementTypes;
            } else {
                this.filteredRequirementTypes = this.allRequirementTypes.filter(rt => rt.name.toLowerCase().includes(this.editRequirementSearch.toLowerCase()));
            }
        },

        selectEditRequirement(rt) {
            this.editForm.requirement_type = rt.name; // Use name as expected by the form/backend
            this.editRequirementSearch = rt.name;
            this.showRequirementDropdown = false;
        },
        
        async fetchCustomers(companyId) {
            this.loadingCustomers = true;
            this.customersForCompany = [];
            try {
                // Use the route generated by Laravel
                const url = `/ajax/companies/${companyId}/customers`; 
                const res = await fetch(url);
                if(res.ok) {
                    const data = await res.json();
                    this.customersForCompany = data.customers;
                }
            } catch(e) {
                console.error(e);
            } finally {
                this.loadingCustomers = false;
            }
        },
        
        async saveEdit() {
            try {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const url = `/inquiries/${this.editForm.id}`;
                
                const res = await fetch(url, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(this.editForm)
                });
                
                const data = await res.json();

                if (!res.ok) {
                     if (res.status === 422) {
                        let errorMsg = data.message || 'Validation Failed';
                        if (data.errors) {
                             // Get the first error message from the object
                             const firstErrorKey = Object.keys(data.errors)[0];
                             if(firstErrorKey) {
                                 errorMsg = data.errors[firstErrorKey][0];
                             }
                        }
                        
                        Swal.fire({
                             title: 'Validation Error',
                             text: errorMsg,
                             icon: 'error',
                             background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                             color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827',
                        });
                        return;
                     }
                }
                
                if(data.success) {
                    this.editOpen = false;
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Inquiry updated successfully.',
                        timer: 1500,
                        showConfirmButton: false,
                        background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                        color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827',
                    }).then(() => {
                        window.location.reload(); // Reload to refresh table data (simplest way to update UI)
                        // Ideally we would update the row data here without reload, 
                        // but since the table is server-side rendered with blade loop, 
                        // a reload or full datatable JS re-render is needed. 
                        // Given 'filter preservation' requirement, a reload preserves filters 
                        // because we kept the query params in URL when filtering.
                    });
                } else {
                     Swal.fire({
                         title: 'Error',
                         text: 'Failed to update inquiry.',
                         icon: 'error',
                         background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                         color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827',
                     });
                }
            } catch(e) {
                console.error(e);
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred.',
                    icon: 'error',
                    background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                    color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827',
                });
            }
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