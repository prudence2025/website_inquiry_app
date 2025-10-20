<x-layouts.app :title="__('Edit Company')">
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-neutral-100 mb-6">
            {{ __('Edit Company') }}
        </h1>

        <form action="{{ route('companies.update', $company) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Company Name Field Group --}}
            <div>
                <flux:label>
                    {{ __('Company Name') }}<span class="text-red-500">*</span>
                </flux:label>
                <flux:input 
                    name="name" 
                    type="text"
                    class="mt-2" 
                    value="{{ old('name', $company->name) }}" 
                    required 
                    autofocus 
                />
                @error('name')
                    <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Industry Field Group --}}
            <div>
                <flux:label>{{ __('Industry') }}<span class="text-red-500">*</span></flux:label>
                <div x-data="singleSelect({
                                 selectedId: @js(old('industry_id', $selectedIndustry)),
                                 options: @js($industries)
                             })"
                     @click.outside="open = false"
                     class="relative mt-2">

                    {{-- Display Field --}}
                    <div @click="open = !open"
                         class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md cursor-pointer min-h-[40px]">
                        <span x-text="selectedName || 'Select an industry...'"></span>
                        <button x-show="selectedId" type="button" @click.stop="clearSelection()"
                                class="ml-auto text-gray-400 hover:text-gray-600">&times;</button>
                    </div>

                    {{-- Dropdown Panel --}}
                    <div x-show="open" x-transition.origin.top.left
                         class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl bg-white dark:bg-neutral-900 border border-gray-200 dark:border-neutral-700"
                         style="display: none;">

                        {{-- Search --}}
                        <input type="text" x-model="search" placeholder="Search industries..."
                               class="w-full p-2 mb-2 border-gray-300 dark:border-neutral-600 rounded-md text-sm bg-gray-50 dark:bg-neutral-700">

                        {{-- Options --}}
                        <div class="max-h-60 overflow-y-auto">
                            <template x-for="option in filteredOptions" :key="option.id">
                                <div @click="select(option); open = false;"
                                     class="p-2 cursor-pointer rounded-md text-black dark:text-black hover:bg-gray-100 dark:hover:bg-neutral-700">
                                    <span x-text="option.name"></span>
                                </div>
                            </template>
                            <p x-show="filteredOptions.length === 0" class="p-2 text-center text-gray-500 dark:text-gray-400 text-sm">
                                No results found.
                            </p>
                        </div>
                    </div>

                    {{-- Hidden Input for Submission --}}
                    <input type="hidden" name="industry_id" :value="selectedId">
                    
                    {{-- Display Laravel Validation Error --}}
                    @error('industry_id')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end pt-4 gap-3">
                <flux:button as="a" href="{{ route('companies.index') }}" variant="danger">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button variant="primary" type="submit">
                    {{ __('Update Company') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>

{{-- I've copied the corrected Alpine script from the previous step for consistency --}}
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
                const term = this.search.toLowerCase();
                return this.options.filter(opt => opt.name.toLowerCase().includes(term));
            },
            
            select(option) {
                this.selectedId = option.id;
            },
            
            clearSelection() {
                this.selectedId = null;
            }
        }
    }
</script>