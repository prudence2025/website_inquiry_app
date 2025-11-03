<x-layouts.app :title="__('Add Inquiry')">
    <div class="max-w-4xl mx-auto py-8"
         x-data="inquiryWithAjax({
             companies: @js($companyOptions ?? $companies ?? []),
             requirementTypes: @js($requirementTypes ?? []),
             processLevels: @js($processLevels ?? [])
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
            <div class="relative">
                <div @click="toggleRequirement()" class="flex items-center w-full p-2 border rounded-md cursor-pointer min-h-[40px]">
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
            <flux:label>{{ __('Company') }}</flux:label>
            <div class="flex items-center gap-2 mb-4">
                <div class="relative flex-1">
                    <div @click="toggleCompanyDropdown()" class="flex items-center w-full p-2 border rounded-md cursor-pointer min-h-[40px]">
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

                <button type="button" @click="openCompanyModal()" class="px-3 py-2 bg-blue-600 text-white rounded-md">+ Add New</button>
            </div>

            {{-- Company modal --}}
            <div x-show="showCompanyModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="fixed inset-0 bg-black/20 backdrop-blur-sm" @click="closeCompanyModal()"></div>
                <div class="relative bg-white dark:bg-neutral-900 rounded-lg p-6 w-full max-w-md">
                    <h3 class="text-lg font-semibold mb-3">Add Company</h3>

                    <div class="space-y-3">
                        <input type="text" x-model="newCompany.name" placeholder="Company name" class="w-full p-2 border rounded">
                       <div class="relative" x-data="{ allIndustries: @js(\App\Models\Industry::orderBy('name')->get(['id','name'])), industrySearch: '' }">
                        <div @click="openIndustry = !openIndustry"
                             class="flex items-center w-full p-2 border rounded-md cursor-pointer min-h-[40px]">
                            <span x-text="selectedIndustryName || 'Select Industry'"></span>
                            <button x-show="selectedIndustryName"
                                    type="button"
                                    @click.stop="clearIndustry()"
                                    class="ml-auto text-gray-400">&times;</button>
                        </div>
                    
                        <div x-show="openIndustry" x-cloak
                             class="absolute z-50 w-full mt-1 p-2 rounded-lg bg-white dark:bg-neutral-900 border shadow-md">
                            <input type="text"
                                   x-model="industrySearch"
                                   placeholder="Search industry..."
                                   class="w-full p-2 mb-3 border rounded text-sm">
                    
                            <div class="max-h-60 overflow-y-auto">
                                <template
                                    x-for="ind in allIndustries.filter(i => i.name.toLowerCase().includes(industrySearch.toLowerCase()))"
                                    :key="ind.id">
                                    <div @click="selectIndustry(ind)"
                                         class="p-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800"
                                         x-text="ind.name"></div>
                                </template>
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
            <flux:label>{{ __('Customer') }}</flux:label>
            <div class="flex items-center gap-2 mb-4">
                <div class="relative flex-1">
                    <div @click="toggleCustomerDropdown()" class="flex items-center w-full p-2 border rounded-md cursor-pointer min-h-[40px]">
                        <span x-text="selectedCustomerName || 'Select a customer...'"></span>
                        <button x-show="selectedCustomerId" type="button" @click.stop="clearCustomer()" class="ml-auto text-gray-400">&times;</button>
                    </div>

                    <div x-show="openCustomer" x-cloak class="absolute z-10 w-full mt-1 p-2 rounded-lg bg-white dark:bg-neutral-900 border">
                        <input type="text" x-model="customerSearch" placeholder="Search customers..." class="w-full p-2 mb-2 border rounded text-sm">
                        <div class="max-h-60 overflow-y-auto">
                            <template x-for="cu in filteredCustomers" :key="cu.id">
                                <div @click="selectCustomer(cu)" class="p-2 cursor-pointer rounded-md hover:bg-gray-100 dark:hover:bg-neutral-800" x-text="cu.name"></div>
                            </template>
                        </div>
                    </div>

                    <input type="hidden" name="customer_id" :value="selectedCustomerId">
                </div>

                <button type="button" @click="openCustomerModal()" class="px-3 py-2 bg-blue-600 text-white rounded-md">+ Add New</button>
            </div>

            {{-- Customer modal --}}
            <div x-show="showCustomerModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="fixed inset-0 bg-black/20 backdrop-blur-sm" @click="closeCustomerModal()"></div>
                <div class="relative bg-white dark:bg-neutral-900 rounded-lg p-6 w-full max-w-md">
                    <h3 class="text-lg font-semibold mb-3">Add Customer</h3>

                    <div class="space-y-2">
                       <div class="relative">
                        <div @click="toggleCompanyForCustomer()" class="flex items-center w-full p-2 border rounded-md cursor-pointer min-h-[40px]">
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
            <flux:label>{{ __('Process Level') }}</flux:label>
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function inquiryWithAjax(cfg) {
        return {
            companies: cfg.companies || [],
            requirementTypes: cfg.requirementTypes || [],
            processLevels: cfg.processLevels || [],

            selectedCompanyId: null,
            selectedCustomerId: null,
            selectedRequirementName: null,

            openCompany: false,
            openCustomer: false,
            openRequirement: false,

            showCompanyModal: false,
            showCustomerModal: false,

            companySearch: '',
            customerSearch: '',
            requirementSearch: '',

            newCompany: { name: '', industry_id: '' },
            newCustomer: { company_id: '', name: '', email: '', phone: '', position: '', notes: '' },

            companyAjaxError: '',
            customerAjaxError: '',

            init() {
                this.companies.forEach(c => { if (!Array.isArray(c.customers)) c.customers = []; });
            },

            // — SweetAlert helper
            toast(icon, title) {
                Swal.fire({
                    icon: icon,
                    title: title,
                    toast: true,
                    position: 'top-end',
                    timer: 2000,
                    showConfirmButton: false,
                    background: document.documentElement.classList.contains('dark') ? '#1f2937' : '#fff',
                    color: document.documentElement.classList.contains('dark') ? '#f9fafb' : '#111827',
                });
            },

            // — requirement
            toggleRequirement() { this.openRequirement = !this.openRequirement; this.openCompany=false; this.openCustomer=false; },
            get filteredRequirementTypes() {
                const q = this.requirementSearch.trim().toLowerCase();
                if (!q) return this.requirementTypes;
                return this.requirementTypes.filter(r => (r.name || r).toLowerCase().includes(q));
            },
            selectRequirement(opt) { this.selectedRequirementName = opt.name || opt; this.openRequirement=false; this.requirementSearch=''; },
            clearRequirement(){ this.selectedRequirementName = null; },

            // — company
            toggleCompanyDropdown(){ this.openCompany = !this.openCompany; this.openCustomer=false; this.openRequirement=false; },
            get filteredCompanies() {
                const q = this.companySearch.trim().toLowerCase();
                if (!q) return this.companies;
                return this.companies.filter(c => c.name.toLowerCase().includes(q));
            },
            get selectedCompanyName() {
                const s = this.companies.find(c => c.id == this.selectedCompanyId);
                return s ? s.name : null;
            },
            selectCompany(c){ this.selectedCompanyId = c.id; this.selectedCustomerId = null; this.openCompany=false; },
            clearCompany(){ this.selectedCompanyId = null; this.selectedCustomerId = null; },

            // — customer
            toggleCustomerDropdown(){ this.openCustomer = !this.openCustomer; this.openCompany=false; this.openRequirement=false; },
            get currentCompanyCustomers() {
                const c = this.companies.find(x => x.id == this.selectedCompanyId);
                return c ? (c.customers || []) : [];
            },
            get filteredCustomers() {
                const q = this.customerSearch.trim().toLowerCase();
                const list = this.currentCompanyCustomers;
                if (!q) return list;
                return list.filter(u => u.name.toLowerCase().includes(q));
            },
            get selectedCustomerName() {
                const c = this.currentCompanyCustomers.find(x => x.id == this.selectedCustomerId);
                return c ? c.name : null;
            },
            selectCustomer(cu){ this.selectedCustomerId = cu.id; this.openCustomer=false; },
            clearCustomer(){ this.selectedCustomerId = null; },

            // — modals
            openCompanyModal(){ this.showCompanyModal = true; this.companyAjaxError=''; this.newCompany = {name:'', industry_id:''}; },
            closeCompanyModal(){ this.showCompanyModal = false; this.companyAjaxError=''; },
            openCustomerModal(){ this.showCustomerModal = true; this.customerAjaxError=''; this.newCustomer = { company_id: this.selectedCompanyId || '', name:'', email:'', phone:'', position:'', notes:'' }; },
            closeCustomerModal(){ this.showCustomerModal = false; this.customerAjaxError=''; },

            // — industry
            openIndustry: false,
            industrySearch: '',
            selectedIndustryName: null,
            toggleIndustry() { this.openIndustry = !this.openIndustry; },
            clearIndustry() { this.newCompany.industry_id = ''; this.selectedIndustryName = null; },
            selectIndustry(ind) {
                this.newCompany.industry_id = ind.id;
                this.selectedIndustryName = ind.name;
                this.openIndustry = false;
            },

            // — Company for Customer modal
            openCompanyForCustomer: false,
            companySearchForCustomer: '',
            selectedCustomerCompanyName: null,
            toggleCompanyForCustomer() { this.openCompanyForCustomer = !this.openCompanyForCustomer; },
            clearCompanyForCustomer() { this.newCustomer.company_id = ''; this.selectedCustomerCompanyName = null; },
            get filteredCompanyListForCustomer() {
                const q = this.companySearchForCustomer.trim().toLowerCase();
                if (!q) return this.companies;
                return this.companies.filter(c => c.name.toLowerCase().includes(q));
            },
            selectCompanyForCustomer(c) {
                this.newCustomer.company_id = c.id;
                this.selectedCustomerCompanyName = c.name;
                this.openCompanyForCustomer = false;
            },

            // — AJAX for Company
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

                    if (!res.ok) throw new Error(payload?.message || 'Failed to create company');

                    if (payload?.success && payload.company) {
                        this.companies.unshift(payload.company);
                        this.selectedCompanyId = payload.company.id;
                        this.closeCompanyModal();
                        this.toast('success', 'Company added successfully!');
                    }
                } catch (e) {
                    console.error(e);
                    this.companyAjaxError = e.message || 'Network error';
                    this.toast('error', 'Failed to create company');
                }
            },

            // — AJAX for Customer
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

                    if (!res.ok) throw new Error(payload?.message || 'Failed to create customer');

                    if (payload?.success && payload.customer) {
                        let comp = this.companies.find(x => String(x.id) === String(payload.customer.company_id));
                        if (!comp) {
                            comp = { id: payload.customer.company_id, name: payload.customer.company_name, customers: [] };
                            this.companies.unshift(comp);
                        }
                        comp.customers.push(payload.customer);
                        this.selectedCustomerId = payload.customer.id;
                        this.closeCustomerModal();
                        this.toast('success', 'Customer added successfully!');
                    }
                } catch (e) {
                    console.error(e);
                    this.customerAjaxError = e.message || 'Network error';
                    this.toast('error', 'Failed to create customer');
                }
            },
            
        };
    }

    // existing delete confirmation and flash success
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
                if (result.isConfirmed) form.submit();
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
