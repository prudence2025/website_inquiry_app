<x-layouts.app :title="__('Edit Inquiry')">
    <div class="max-w-4xl mx-auto py-8"
         x-data="{}"> <h1 class="text-3xl font-bold text-gray-900 dark:text-neutral-100 mb-6">
            {{ __('Edit Inquiry') }}
        </h1>

        <form action="{{ route('inquiries.update', $inquiry) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Inquiry Date --}}
            <flux:input name="inquiry_date" :label="__('Inquiry Date')" type="date"
                        value="{{ old('inquiry_date', $inquiry->inquiry_date) }}" required />

            {{-- Receiver --}}
            <flux:label>{{ __('Inquiry Receiver') }}<span class="text-red-500">*</span></flux:label>
            <flux:select name="receiver_name" required>
                <option value="">{{ __('Select Receiver') }}</option>
                @foreach ($receivers as $receiver)
                    <option value="{{ $receiver }}" @selected(old('receiver_name', $inquiry->receiver_name) === $receiver)>{{ $receiver }}</option>
                @endforeach
            </flux:select>

            {{-- Requirement Type (searchable single select) --}}
            <flux:label>{{ __('Requirement Type') }}<span class="text-red-500">*</span></flux:label>
            <div x-data="singleSelect({
                         selectedName: @js(old('requirement_type', $inquiry->requirement_type)),
                         options: @js($requirementTypes->map(fn($r)=>['id'=>$r->name,'name'=>$r->name]))
                     })"
                 @click.outside="open = false" class="relative">
                <div @click="open = !open"
                     class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md cursor-pointer min-h-[40px]">
                    <span x-text="selectedName || 'Select a requirement type...'"></span>
                    <button x-show="selectedName" type="button" @click.stop="clearSelection()"
                            class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <div x-show="open" x-transition.origin.top.left
                     class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700"
                     style="display:none;">
                    <input type="text" x-model="search" placeholder="Search..."
                           class="w-full p-2 mb-2 border-gray-300 dark:border-neutral-600 rounded-md text-sm bg-gray-50 dark:bg-neutral-700" />
                    <div class="max-h-60 overflow-y-auto">
                        <template x-for="option in filteredOptions" :key="option.id">
                            <div @click="select(option); open = false;"
                                 class="p-2 cursor-pointer rounded-md text-black dark:text-black hover:bg-gray-100 dark:hover:bg-neutral-700"
                                 x-text="option.name"></div>
                        </template>
                        <p x-show="filteredOptions.length === 0" class="p-2 text-center text-gray-500 dark:text-gray-400 text-sm">No results found</p>
                    </div>
                </div>
                <input type="hidden" name="requirement_type" :value="selectedName">
            </div>

            {{-- Company (searchable single select) --}}
            <flux:label>{{ __('Company') }}</flux:label>
            <div x-data="companySelect({
                         companies: @js($companies),
                         selectedId: @js(old('company_id', $inquiry->company_id))
                     })"
                 @click.outside="open = false" class="relative">
                <div @click="open = !open"
                     class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md cursor-pointer min-h-[40px]">
                    <span x-text="selectedCompanyName || 'Select a company...'"></span>
                    <button x-show="selectedCompanyId" type="button" @click.stop="clearCompany()"
                            class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <div x-show="open" x-transition.origin.top.left
                     class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700"
                     style="display:none;">
                    <input type="text" x-model="search" placeholder="Search companies..."
                           class="w-full p-2 mb-2 border-gray-300 dark:border-neutral-600 rounded-md text-sm bg-gray-50 dark:bg-neutral-700" />
                    <div class="max-h-60 overflow-y-auto">
                        <template x-for="company in filteredCompanies" :key="company.id">
                            <div @click="selectCompany(company); open = false;"
                                 class="p-2 cursor-pointer rounded-md text-black dark:text-black hover:bg-gray-100 dark:hover:bg-neutral-700"
                                 x-text="company.name"></div>
                        </template>
                    </div>
                </div>
                <input type="hidden" name="company_id" :value="selectedCompanyId" />
            </div>

            {{-- Customer (filtered by selected company) --}}
            <flux:label>{{ __('Customer') }}</flux:label>
            <div x-data="customerSelect({
                         companies: @js($companies),
                         selectedCompanyId: @js(old('company_id', $inquiry->company_id)),
                         selectedCustomerId: @js(old('customer_id', $inquiry->customer_id))
                     })"
                 x-init="init()"
                 @company-changed.window="loadCustomers($event.detail)"
                 @click.outside="open = false"
                 class="relative">
                {{-- Display field --}}
                <div @click="open = !open"
                     class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md cursor-pointer min-h-[40px]">
                    <span x-text="selectedCustomerName || 'Select a customer...'"></span>
                    <button x-show="selectedCustomerId" type="button" @click.stop="clearCustomer()"
                            class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                {{-- Dropdown --}}
                <div x-show="open" x-transition.origin.top.left
                     class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700"
                     style="display:none;">
                    <input type="text" x-model="search" placeholder="Search customers..."
                           class="w-full p-2 mb-2 border-gray-300 dark:border-neutral-600 rounded-md text-sm bg-gray-50 dark:bg-neutral-700" />
                    <div class="max-h-60 overflow-y-auto">
                        <template x-for="customer in filteredCustomers" :key="customer.id">
                            <div @click="selectCustomer(customer); open = false;"
                                 class="p-2 cursor-pointer rounded-md text-black dark:text-black hover:bg-gray-100 dark:hover:bg-neutral-700"
                                 x-text="customer.name"></div>
                        </template>
                        <p x-show="filteredCustomers.length === 0"
                           class="p-2 text-center text-gray-500 dark:text-gray-400 text-sm">No customers</p>
                    </div>
                </div>
                <input type="hidden" name="customer_id" :value="selectedCustomerId" />
            </div>


            <flux:label>{{ __('More Information') }}</flux:label>
            <flux:textarea name="more_info" rows="3">{{ old('more_info', $inquiry->more_info) }}</flux:textarea>

                        {{-- Process Level --}}
            <flux:label>{{ __('Process Level') }}</flux:label>
            <flux:select name="process_level" required>
                <option value="">{{ __('Select Process Level') }}</option>
                @foreach ($processLevels as $level)
                    <option value="{{ $level }}" @selected(old('process_level', $inquiry->process_level) === $level)>{{ $level }}</option>
                @endforeach
            </flux:select>
            
            <flux:input name="amount" :label="__('Amount (LKR)')" type="number" step="0.01"
                        value="{{ old('amount', $inquiry->amount) }}" />

            {{-- Buttons --}}
            <div class="flex justify-end pt-4 gap-3 mt-4">
                <flux:button as="a" href="{{ route('inquiries.index') }}" variant="danger">{{ __('Cancel') }}</flux:button>
                <flux:button variant="primary" type="submit">{{ __('Update Inquiry') }}</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>

{{-- Alpine components (same as create) --}}
<script>
    function singleSelect(config) {
        return {
            open: false,
            search: '',
            selectedId: config.selectedId ?? null,
            selectedName: config.selectedName ?? null,
            options: config.options || [],

            get filteredOptions() {
                if (!this.search) return this.options;
                return this.options.filter(o => o.name.toLowerCase().includes(this.search.toLowerCase()));
            },

            select(option) {
                this.selectedId = option.id ?? option.name;
                this.selectedName = option.name ?? option.id;
                this.search = this.selectedName || '';
            },

            clearSelection() {
                this.selectedId = null;
                this.selectedName = null;
                this.search = '';
            }
        }
    }

    function companySelect(config) {
        return {
            open: false,
            search: '',
            companies: config.companies || [],
            selectedCompanyId: config.selectedId ?? null,

            get selectedCompanyName() {
                const c = this.companies.find(c => c.id === this.selectedCompanyId);
                return c ? c.name : null;
            },

            get filteredCompanies() {
                if (!this.search) return this.companies;
                return this.companies.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
            },

            selectCompany(company) {
                this.selectedCompanyId = company.id;
                this.search = company.name;
                this.open = false;
                window.dispatchEvent(new CustomEvent('company-changed', { detail: company.id }));
            },

            clearCompany() {
                this.selectedCompanyId = null;
                this.search = '';
                window.dispatchEvent(new CustomEvent('company-changed', { detail: null }));
            }
        }
    }

    function customerSelect(config = {}) {
        return {
            open: false,
            search: '',
            companies: config.companies || [],
            customers: [],
            selectedCompanyId: config.selectedCompanyId || null,
            selectedCustomerId: config.selectedCustomerId || null,

            init() {
                // load customers for the selected company
                if (this.selectedCompanyId) {
                    this.loadCustomers(this.selectedCompanyId);

                    // preselect existing customer if found
                    const company = this.companies.find(c => c.id === this.selectedCompanyId);
                    if (company && this.selectedCustomerId) {
                        const existingCustomer = company.customers.find(c => c.id === this.selectedCustomerId);
                        if (existingCustomer) {
                            this.selectedCustomerName = existingCustomer.name;
                        }
                    }
                }
            },

            loadCustomers(companyId) {
                if (!companyId) {
                    this.customers = [];
                    this.selectedCustomerId = null;
                    this.selectedCustomerName = null;
                    return;
                }

                const company = this.companies.find(c => c.id === companyId);
                this.customers = company ? company.customers || [] : [];

                // reset customer selection if company changes
                if (this.selectedCompanyId !== companyId) {
                    this.selectedCustomerId = null;
                    this.selectedCustomerName = null;
                }

                this.selectedCompanyId = companyId;
            },

            get selectedCustomerName() {
                const c = this.customers.find(c => c.id === this.selectedCustomerId);
                return c ? c.name : null;
            },

            get filteredCustomers() {
                if (!this.search) return this.customers;
                return this.customers.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
            },

            selectCustomer(customer) {
                this.selectedCustomerId = customer.id;
                this.search = customer.name;
                this.open = false;
            },

            clearCustomer() {
                this.selectedCustomerId = null;
                this.search = '';
            }
        }
    }
</script>