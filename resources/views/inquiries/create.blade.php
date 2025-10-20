<x-layouts.app :title="__('Add Inquiry')">
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-neutral-100 mb-6">
            {{ __('Add Inquiry') }}
        </h1>

        <form action="{{ route('inquiries.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Inquiry Date --}}
            <flux:input name="inquiry_date" :label="__('Inquiry Date')" type="date" required />

            {{-- Receiver Name --}}
            <flux:label>{{ __('Inquiry Receiver') }}<span class="text-red-500">*</span></flux:label>
            <flux:select name="receiver_name" required>
                <option value="">Select Receiver</option>
                @foreach ($receivers as $receiver)
                    <option value="{{ $receiver }}">{{ $receiver }}</option>
                @endforeach
            </flux:select>

            {{-- Requirement Type --}}
            <flux:label>{{ __('Requirement Type') }}<span class="text-red-500">*</span></flux:label>
            <div x-data="singleSelect({ selectedId: @js(old('requirement_type', null)), options: @js($requirementTypes) })"
                 @click.outside="open = false" class="relative">
                <div @click="open = !open"
                     class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md cursor-pointer min-h-[40px]">
                    <span x-text="selectedName || 'Select a requirement type...'"></span>
                    <button x-show="selectedId" type="button" @click.stop="clearSelection()"
                            class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <div x-show="open"
                     x-transition.origin.top.left
                     class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700"
                     style="display:none;">
                    <input type="text" x-model="search" placeholder="Search..."
                           class="w-full p-2 mb-2 border-gray-300 dark:border-neutral-600 rounded-md text-sm bg-gray-50 dark:bg-neutral-700">
                    <div class="max-h-60 overflow-y-auto">
                        <template x-for="option in filteredOptions" :key="option.id">
                            <div @click="select(option); open = false;"
                                 class="p-2 cursor-pointer rounded-md text-black dark:text-black hover:bg-gray-100 dark:hover:bg-neutral-700"
                                 x-text="option.name"></div>
                        </template>
                    </div>
                </div>
                <input type="hidden" name="requirement_type" :value="selectedName">
            </div>

            {{-- Company --}}
            <flux:label>{{ __('Company') }}</flux:label>
            <div x-data="companySelect({ companies: @js($companies) })"
                 @click.outside="open = false" class="relative">
                <div @click="open = !open"
                     class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md cursor-pointer min-h-[40px]">
                    <span x-text="selectedCompanyName || 'Select a company...'"></span>
                    <button x-show="selectedCompanyId" type="button" @click.stop="clearCompany()"
                            class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <div x-show="open"
                     x-transition.origin.top.left
                     class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700"
                     style="display:none;">
                    <input type="text" x-model="search" placeholder="Search companies..."
                           class="w-full p-2 mb-2 border-gray-300 dark:border-neutral-600 rounded-md text-sm bg-gray-50 dark:bg-neutral-700">
                    <div class="max-h-60 overflow-y-auto">
                        <template x-for="company in filteredCompanies" :key="company.id">
                            <div @click="selectCompany(company); open = false;"
                                 class="p-2 cursor-pointer rounded-md text-black dark:text-black hover:bg-gray-100 dark:hover:bg-neutral-700"
                                 x-text="company.name"></div>
                        </template>
                    </div>
                </div>
                <input type="hidden" name="company_id" :value="selectedCompanyId">
            </div>

             {{-- Process Level --}}
            <flux:label>{{ __('Process Level') }}</flux:label>
            <flux:select name="process_level" required>
                <option value="">Select Process Level</option>
                @foreach ($processLevels as $level)
                    <option value="{{ $level }}">{{ $level }}</option>
                @endforeach
            </flux:select>
            <flux:input name="amount" :label="__('Amount (LKR)')" type="number" step="0.01" />

            {{-- Customer (filtered by selected company) --}}
            <flux:label>{{ __('Customer') }}</flux:label>
            <div x-data="customerSelect()" x-init="setCompanies(@js($companies))"
                 @company-changed.window="loadCustomers($event.detail)"
                 @click.outside="open = false" class="relative">
                <div @click="open = !open"
                     class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md cursor-pointer min-h-[40px]">
                    <span x-text="selectedCustomerName || 'Select a customer...'"></span>
                    <button x-show="selectedCustomerId" type="button" @click.stop="clearCustomer()"
                            class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                </div>

                <div x-show="open"
                     x-transition.origin.top.left
                     class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700"
                     style="display:none;">
                    <input type="text" x-model="search" placeholder="Search customers..."
                           class="w-full p-2 mb-2 border-gray-300 dark:border-neutral-600 rounded-md text-sm bg-gray-50 dark:bg-neutral-700">
                    <div class="max-h-60 overflow-y-auto">
                        <template x-for="customer in filteredCustomers" :key="customer.id">
                            <div @click="selectCustomer(customer); open = false;"
                                 class="p-2 cursor-pointer rounded-md text-black dark:text-black hover:bg-gray-100 dark:hover:bg-neutral-700"
                                 x-text="customer.name"></div>
                        </template>
                    </div>
                </div>
                <input type="hidden" name="customer_id" :value="selectedCustomerId">
            </div>

            {{-- Other Fields --}}
            <flux:textarea name="more_info" :label="__('More Information')" rows="3"></flux:textarea>

            {{-- Submit --}}
            <div class="flex justify-end pt-4 gap-3">
                <flux:button as="a" href="{{ route('inquiries.index') }}" variant="danger">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button variant="primary" type="submit">
                    {{ __('Save Inquiry') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>

{{-- Alpine.js logic --}}
<script>
    function singleSelect(config) {
        return {
            open: false,
            search: '',
            selectedId: config.selectedId,
            options: config.options || [],

            get selectedName() {
                const selected = this.options.find(o => o.id === this.selectedId);
                return selected ? selected.name : null;
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

    function companySelect(config) {
        return {
            open: false,
            search: '',
            companies: config.companies || [],
            selectedCompanyId: null,

            get selectedCompanyName() {
                const c = this.companies.find(c => c.id === this.selectedCompanyId);
                return c ? c.name : null;
            },

            get filteredCompanies() {
                return this.search === ''
                    ? this.companies
                    : this.companies.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
            },

            selectCompany(company) {
                this.selectedCompanyId = company.id;
                this.search = company.name;
                this.open = false;
                window.dispatchEvent(new CustomEvent('company-changed', { detail: company.id }));
            },

            clearCompany() {
                this.selectedCompanyId = null;
                window.dispatchEvent(new CustomEvent('company-changed', { detail: null }));
            }
        }
    }

    function customerSelect() {
        return {
            open: false,
            search: '',
            companies: [],
            customers: [],
            selectedCustomerId: null,

            setCompanies(data) {
                this.companies = data;
            },

            loadCustomers(companyId) {
                if (!companyId) {
                    this.customers = [];
                    this.selectedCustomerId = null;
                    return;
                }
                const company = this.companies.find(c => c.id === companyId);
                this.customers = company ? company.customers : [];
            },

            get selectedCustomerName() {
                const c = this.customers.find(c => c.id === this.selectedCustomerId);
                return c ? c.name : null;
            },

            get filteredCustomers() {
                return this.search === ''
                    ? this.customers
                    : this.customers.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
            },

            selectCustomer(customer) {
                this.selectedCustomerId = customer.id;
                this.search = customer.name;
            },

            clearCustomer() {
                this.selectedCustomerId = null;
            }
        }
    }
</script>