<x-layouts.app :title="__('Add Inquiry')">
    <div class="max-w-4xl mx-auto py-8"
         x-data="inquiryWithAjax({
             companies: @js($companyOptions ?? $companies ?? []),
             requirementTypes: @js($requirementTypes ?? []),
             processLevels: @js($processLevels ?? $processLevels ?? []),
             industries: @js(\App\Models\Industry::orderBy('name')->get(['id','name']))
         })"
         x-init="init()"
    >
        <h1 class="text-3xl font-bold text-gray-900 dark:text-neutral-100 mb-6">{{ __('Add Inquiry') }}</h1>

        <form action="{{ route('inquiries.store') }}" method="POST" class="space-y-6" @submit="onSubmit">
            @csrf

            {{-- Inquiry Date --}}
            <flux:input name="inquiry_date" :label="__('Inquiry Date')" type="date" required />

            {{-- Receiver --}}
            <flux:label>{{ __('Inquiry Receiver') }}<span class="text-red-500">*</span></flux:label>
            <flux:select name="receiver_name" required>
                <option value="">{{ __('Select Receiver') }}</option>
                @foreach ($receivers as $receiver)
                    <option value="{{ $receiver }}">{{ $receiver }}</option>
                @endforeach
            </flux:select>

            {{-- Requirement Type --}}
            <flux:label>{{ __('Requirement Type') }}<span class="text-red-500">*</span></flux:label>
            <div class="relative" @click.outside="openRequirement = false">
                <div @click="openRequirement = !openRequirement" class="flex items-center w-full p-2 border rounded-md cursor-pointer min-h-[40px]">
                    <span x-text="selectedRequirementName || 'Select a requirement type...'"></span>
                    <button x-show="selectedRequirementName" type="button" @click.stop="clearRequirement()" class="ml-auto text-gray-400">&times;</button>
                </div>

                <div x-show="openRequirement" x-cloak class="absolute z-10 w-full mt-2 p-2 rounded-lg bg-white dark:bg-neutral-900 border">
                    <input type="text" x-model="requirementSearch" placeholder="Search..." class="w-full p-2 mb-2 border rounded text-sm">
                    <div class="max-h-60 overflow-y-auto">
                        <template x-for="opt in filteredRequirementTypes" :key="opt.id">
                            <div @click="selectRequirement(opt)" class="p-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800" x-text="opt.name"></div>
                        </template>
                    </div>
                </div>

                <input type="hidden" name="requirement_type" :value="selectedRequirementName">
            </div>

            {{-- Company --}}
            <flux:label>{{ __('Company') }}<span class="text-red-500">*</span></flux:label>
            <div class="flex items-center gap-2 mb-4">
                <div class="relative flex-1" @click.outside="openCompany = false">
                    <div @click="openCompany = !openCompany" class="flex items-center w-full p-2 border rounded-md cursor-pointer min-h-[40px]">
                        <span x-text="selectedCompanyName || 'Select a company...'"></span>
                        <button x-show="selectedCompanyId" type="button" @click.stop="clearCompany()" class="ml-auto text-gray-400">&times;</button>
                    </div>

                    <div x-show="openCompany" x-cloak class="absolute z-10 w-full mt-2 p-2 rounded-lg bg-white dark:bg-neutral-900 border">
                        <input type="text" x-model="companySearch" placeholder="Search companies..." class="w-full p-2 mb-2 border rounded text-sm">
                        <div class="max-h-60 overflow-y-auto">
                            <template x-for="c in filteredCompanies" :key="c.id">
                                <div @click="selectCompany(c)" class="p-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800" x-text="c.name"></div>
                            </template>
                        </div>
                    </div>

                    <input type="hidden" name="company_id" :value="selectedCompanyId">
                </div>

                <button type="button" @click="openCompanyModal()" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium">+ Add New</button>
            </div>

            {{-- Company Modal --}}
           <div x-show="showCompanyModal" x-cloak class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto pt-10">
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="closeCompanyModal()"></div>
                <div class="relative bg-white dark:bg-neutral-900 rounded-lg p-6 w-full max-w-md" @click.stop>
                    <h3 class="text-lg font-semibold mb-3">Add Company</h3>

                    <div class="space-y-3">
                        <input type="text" x-model="newCompany.name" placeholder="Company name" class="w-full p-2 border rounded">

                        {{-- Industry Dropdown (search + create) --}}
                        <div class="relative" @click.outside="openIndustry = false">
                            <div @click="openIndustry = !openIndustry" class="flex items-center w-full p-2 border rounded-md cursor-pointer min-h-[40px]">
                                <span x-text="selectedIndustryName || 'Select Industry'"></span>
                                <button x-show="selectedIndustryName" type="button" @click.stop="clearIndustry()" class="ml-auto text-gray-400">&times;</button>
                            </div>

                            <div x-show="openIndustry" x-cloak class="absolute z-50 w-full mt-1 p-3 rounded-lg bg-white dark:bg-neutral-900 border shadow-md">
                                <input type="text" x-model="industrySearch" placeholder="Search industry..." class="w-full p-2 mb-2 border rounded text-sm">

                                <div class="max-h-52 overflow-y-auto">
                                    <template x-for="ind in filteredIndustries" :key="ind.id">
                                        <div @click="selectIndustry(ind)" class="p-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800" x-text="ind.name"></div>
                                    </template>
                                </div>

                                <div class="mt-3 border-t pt-3">
                                    <input type="text" x-model="newIndustryName" placeholder="New industry name" class="w-full p-2 border rounded text-sm mb-2">
                                    <button type="button"
                                            class="w-full bg-green-600 hover:bg-green-700 text-white text-sm rounded-md px-3 py-2"
                                            @click="saveNewIndustryAjax()"
                                            :disabled="addingIndustry">
                                        <span x-show="!addingIndustry">+ Add Industry</span>
                                        <span x-show="addingIndustry">Saving...</span>
                                    </button>
                                </div>
                            </div>

                            <input type="hidden" name="industry_id" :value="newCompany.industry_id">
                        </div>

                        <p x-show="companyAjaxError" class="text-sm text-red-500" x-text="companyAjaxError"></p>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <flux:button variant="danger" type="button" @click="closeCompanyModal()">Cancel</flux:button>
                        <flux:button variant="primary" type="button" @click="saveNewCompanyAjax()">Save</flux:button>
                    </div>
                </div>
            </div>

            {{-- Customer --}}
            <flux:label>{{ __('Customer') }}<span class="text-red-500">*</span></flux:label>
            <div class="flex items-center gap-2 mb-4">
                <div class="relative flex-1" @click.outside="openCustomer = false">
                    <div @click="openCustomer = !openCustomer" class="flex items-center w-full p-2 border rounded-md cursor-pointer min-h-[40px]">
                        <span x-text="selectedCustomerName || 'Select a customer...'"></span>
                        <button x-show="selectedCustomerId" type="button" @click.stop="clearCustomer()" class="ml-auto text-gray-400">&times;</button>
                    </div>

                    <div x-show="openCustomer" x-cloak class="absolute z-10 w-full mt-2 p-2 rounded-lg bg-white dark:bg-neutral-900 border">
                        <input type="text" x-model="customerSearch" placeholder="Search customers..." class="w-full p-2 mb-2 border rounded text-sm">
                        <div class="max-h-60 overflow-y-auto">
                            <template x-for="cu in filteredCustomers" :key="cu.id">
                                <div @click="selectCustomer(cu)" class="p-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800" x-text="cu.name"></div>
                            </template>
                        </div>
                    </div>

                    <input type="hidden" name="customer_id" :value="selectedCustomerId">
                </div>

                <button type="button" @click="openCustomerModal()" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md text-sm font-medium">+ Add New</button>
            </div>

            {{-- Customer Modal --}}
            <div x-show="showCustomerModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" @click="closeCustomerModal()"></div>
                <div class="relative bg-white dark:bg-neutral-900 rounded-lg p-6 w-full max-w-md" @click.stop>
                    <h3 class="text-lg font-semibold mb-3">Add Customer</h3>

                    <div class="space-y-2">
                        <div class="relative" @click.outside="openCompanyForCustomer = false">
                            <div @click="openCompanyForCustomer = !openCompanyForCustomer" class="flex items-center w-full p-2 border rounded-md cursor-pointer min-h-[40px]">
                                <span x-text="selectedCustomerCompanyName || 'Select Company'"></span>
                                <button x-show="selectedCustomerCompanyName" type="button" @click.stop="clearCompanyForCustomer()" class="ml-auto text-gray-400">&times;</button>
                            </div>

                            <div x-show="openCompanyForCustomer" x-cloak class="absolute z-50 w-full mt-1 p-2 rounded-lg bg-white dark:bg-neutral-900 border">
                                <input type="text" x-model="companySearchForCustomer" placeholder="Search company..." class="w-full p-2 mb-2 border rounded text-sm">
                                <div class="max-h-60 overflow-y-auto">
                                    <template x-for="c in filteredCompanyListForCustomer" :key="c.id">
                                        <div @click="selectCompanyForCustomer(c)" class="p-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800" x-text="c.name"></div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <input type="text" x-model="newCustomer.name" placeholder="Full name" class="w-full p-2 border rounded">
                        <input type="email" x-model="newCustomer.email" placeholder="Email" class="w-full p-2 border rounded">
                        <input type="text" x-model="newCustomer.phone" placeholder="Phone" class="w-full p-2 border rounded">
                        <input type="text" x-model="newCustomer.position" placeholder="Position" class="w-full p-2 border rounded">
                        <textarea x-model="newCustomer.notes" rows="2" placeholder="Notes" class="w-full p-2 border rounded"></textarea>
                        <p x-show="customerAjaxError" class="text-sm text-red-500" x-text="customerAjaxError"></p>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <flux:button variant="danger" type="button" @click="closeCustomerModal()">Cancel</flux:button>
                        <flux:button variant="primary" type="button" @click="saveNewCustomerAjax()">Save</flux:button>
                    </div>
                </div>
            </div>

            {{-- Process Level --}}
            <flux:label>{{ __('Process Level') }}<span class="text-red-500">*</span></flux:label>
            <flux:select name="process_level" required>
                <option value="">{{ __('Select Process Level') }}</option>
                <template x-for="lvl in processLevels" :key="lvl">
                    <option :value="lvl" x-text="lvl"></option>
                </template>
            </flux:select>

            <flux:input name="amount" :label="__('Amount (LKR)')" type="number" step="0.01" />
            <flux:textarea name="more_info" :label="__('More Information')" rows="3"></flux:textarea>

            <div class="flex justify-end pt-4 gap-3">
                <flux:button as="a" href="{{ route('inquiries.index') }}" variant="danger">Cancel</flux:button>
                <flux:button variant="primary" type="submit">Save Inquiry</flux:button>
            </div>
        </form>
    </div>

    {{-- sweetalert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    function inquiryWithAjax(cfg) {
        return {
            // initial data
            companies: cfg.companies || [],
            industries: cfg.industries || [],
            requirementTypes: cfg.requirementTypes || [],
            processLevels: cfg.processLevels || [],

            // selection state
            selectedCompanyId: null,
            selectedCustomerId: null,
            selectedRequirementName: null,

            // dropdown state
            openCompany: false,
            openCustomer: false,
            openRequirement: false,

            // modal state
            showCompanyModal: false,
            showCustomerModal: false,

            // searches
            companySearch: '',
            customerSearch: '',
            requirementSearch: '',

            // new models
            newCompany: { name: '', industry_id: '' },
            newCustomer: { company_id: '', name: '', email: '', phone: '', position: '', notes: '' },
            newIndustryName: '',

            // misc
            companyAjaxError: '',
            customerAjaxError: '',
            addingIndustry: false,

            // industry dropdown
            openIndustry: false,
            industrySearch: '',
            selectedIndustryName: null,

            // company-for-customer dropdown
            openCompanyForCustomer: false,
            companySearchForCustomer: '',
            selectedCustomerCompanyName: null,

            init() {
                // ensure each company has customers array
                this.companies.forEach(c => { if (!Array.isArray(c.customers)) c.customers = []; });
            },

            // toast
            toast(icon, title) {
                Swal.fire({
                    icon: icon,
                    title: title,
                    toast: true,
                    position: 'top-end',
                    timer: 2000,
                    showConfirmButton: false,
                    background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                    color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827'
                });
            },

            // requirement helpers
            get filteredRequirementTypes() {
                const q = (this.requirementSearch || '').trim().toLowerCase();
                if (!q) return this.requirementTypes;
                return this.requirementTypes.filter(r => (r.name || r).toLowerCase().includes(q));
            },
            selectRequirement(opt) { this.selectedRequirementName = opt.name || opt; this.openRequirement = false; this.requirementSearch = ''; },
            clearRequirement() { this.selectedRequirementName = null; },

            // companies
            get filteredCompanies() {
                const q = (this.companySearch || '').trim().toLowerCase();
                if (!q) return this.companies;
                return this.companies.filter(c => c.name.toLowerCase().includes(q));
            },
            get selectedCompanyName() {
                const s = this.companies.find(c => String(c.id) === String(this.selectedCompanyId));
                return s ? s.name : null;
            },
            selectCompany(c) { this.selectedCompanyId = c.id; this.selectedCustomerId = null; this.openCompany = false; this.companySearch = ''; },
            clearCompany() { this.selectedCompanyId = null; this.selectedCustomerId = null; },

            // customers
            get currentCompanyCustomers() {
                const c = this.companies.find(x => String(x.id) === String(this.selectedCompanyId));
                return c ? (c.customers || []) : [];
            },
            get filteredCustomers() {
                const q = (this.customerSearch || '').trim().toLowerCase();
                const list = this.currentCompanyCustomers;
                if (!q) return list;
                return list.filter(u => u.name.toLowerCase().includes(q));
            },
            get selectedCustomerName() {
                const c = this.currentCompanyCustomers.find(x => String(x.id) === String(this.selectedCustomerId));
                return c ? c.name : null;
            },
            selectCustomer(cu) { this.selectedCustomerId = cu.id; this.openCustomer = false; this.customerSearch = ''; },
            clearCustomer() { this.selectedCustomerId = null; },

            // modals
            openCompanyModal() { this.showCompanyModal = true; this.companyAjaxError = ''; this.newCompany = { name: '', industry_id: '' }; this.selectedIndustryName = null; this.newIndustryName = ''; },
            closeCompanyModal() { this.showCompanyModal = false; this.companyAjaxError = ''; this.openIndustry = false; },

            openCustomerModal() { this.showCustomerModal = true; this.customerAjaxError = ''; this.newCustomer = { company_id: this.selectedCompanyId || '', name: '', email: '', phone: '', position: '', notes: '' }; this.selectedCustomerCompanyName = ''; },
            closeCustomerModal() { this.showCustomerModal = false; this.customerAjaxError = ''; this.openCompanyForCustomer = false; },

            // industry helpers
            get filteredIndustries() {
                const q = (this.industrySearch || '').trim().toLowerCase();
                if (!q) return this.industries;
                return this.industries.filter(i => i.name.toLowerCase().includes(q));
            },
            selectIndustry(ind) {
                this.newCompany.industry_id = ind.id;
                this.selectedIndustryName = ind.name;
                this.openIndustry = false;
            },
            clearIndustry() {
                this.newCompany.industry_id = '';
                this.selectedIndustryName = null;
            },

            // select company for customer modal
            get filteredCompanyListForCustomer() {
                const q = (this.companySearchForCustomer || '').trim().toLowerCase();
                if (!q) return this.companies;
                return this.companies.filter(c => c.name.toLowerCase().includes(q));
            },
            selectCompanyForCustomer(c) {
                this.newCustomer.company_id = c.id;
                this.selectedCustomerCompanyName = c.name;
                this.openCompanyForCustomer = false;
            },
            clearCompanyForCustomer() {
                this.newCustomer.company_id = '';
                this.selectedCustomerCompanyName = null;
            },

            // AJAX - create company
            async saveNewCompanyAjax() {
                this.companyAjaxError = '';
                const name = (this.newCompany.name || '').trim();
                const industry_id = this.newCompany.industry_id || '';
                if (!name) { this.companyAjaxError = 'Company name required.'; return; }
                if (!industry_id) { this.companyAjaxError = 'Please select industry.'; return; }

                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const res = await fetch("{{ route('companies.ajaxStore') }}", {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ name, industry_id })
                    });

                    const text = await res.text();
                    let payload; try { payload = JSON.parse(text); } catch { payload = null; }

                    if (!res.ok) {
                        const message = payload?.message || (payload?.errors ? Object.values(payload.errors)[0][0] : 'Failed to create company');
                        throw new Error(message);
                    }

                    if (payload?.success && payload.company) {
                        // add to list and select
                        this.companies.unshift(payload.company);
                        this.selectedCompanyId = payload.company.id;
                        this.companySearch = '';
                        this.closeCompanyModal();
                        this.toast('success', 'Company added successfully!');
                    }
                } catch (e) {
                    console.error('saveNewCompanyAjax error', e);
                    this.companyAjaxError = e.message || 'Network error';
                    this.toast('error', this.companyAjaxError);
                }
            },

            // AJAX - create customer
            async saveNewCustomerAjax() {
                this.customerAjaxError = '';
                const cId = this.newCustomer.company_id || this.selectedCompanyId;
                const name = (this.newCustomer.name || '').trim();
                if (!cId) { this.customerAjaxError = 'Please select company first.'; return; }
                if (!name) { this.customerAjaxError = 'Customer name required.'; return; }

                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const res = await fetch("{{ route('customers.ajaxStore') }}", {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            company_id: cId,
                            name: this.newCustomer.name,
                            email: this.newCustomer.email,
                            phone: this.newCustomer.phone,
                            position: this.newCustomer.position,
                            notes: this.newCustomer.notes
                        })
                    });

                    const text = await res.text();
                    let payload; try { payload = JSON.parse(text); } catch { payload = null; }

                    if (!res.ok) {
                        const message = payload?.message || (payload?.errors ? Object.values(payload.errors)[0][0] : 'Failed to create customer');
                        throw new Error(message);
                    }

                    if (payload?.success && payload.customer) {
                        let comp = this.companies.find(x => String(x.id) === String(payload.customer.company_id));
                        if (!comp) {
                            comp = { id: payload.customer.company_id, name: payload.customer.company_name || 'Company', customers: [] };
                            this.companies.unshift(comp);
                        }
                        comp.customers = comp.customers || [];
                        comp.customers.unshift(payload.customer);
                        this.selectedCompanyId = payload.customer.company_id;
                        this.selectedCustomerId = payload.customer.id;
                        this.closeCustomerModal();
                        this.toast('success', 'Customer added successfully!');
                    }
                } catch (e) {
                    console.error('saveNewCustomerAjax error', e);
                    this.customerAjaxError = e.message || 'Network error';
                    this.toast('error', this.customerAjaxError);
                }
            },

            // AJAX - create industry
            async saveNewIndustryAjax() {
                const name = (this.newIndustryName || '').trim();
                if (!name) { this.toast('error', 'Please enter an industry name.'); return; }
                this.addingIndustry = true;

                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const res = await fetch("{{ route('industries.ajaxStore') }}", {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ name })
                    });

                    const text = await res.text();
                    let payload; try { payload = JSON.parse(text); } catch { payload = null; }

                    if (!res.ok) {
                        const message = payload?.message || (payload?.errors ? Object.values(payload.errors)[0][0] : 'Failed to create industry');
                        throw new Error(message);
                    }

                    if (payload?.success && payload.industry) {
                        this.industries.unshift(payload.industry);
                        this.selectIndustry(payload.industry);
                        this.newIndustryName = '';
                        this.toast('success', 'Industry added successfully!');
                    }
                } catch (e) {
                    console.error('saveNewIndustryAjax error', e);
                    this.toast('error', e.message || 'Failed to add industry');
                } finally {
                    this.addingIndustry = false;
                }
            },

            // final submit hook
            onSubmit(e) {
                // nothing special needed, server validates
                return true;
            }
        };
    }
    </script>
</x-layouts.app>
