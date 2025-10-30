<x-layouts.app :title="__('Add Customer')">
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-neutral-100 mb-6">
            {{ __('Add Customer') }}
        </h1>

        <form action="{{ route('customers.store') }}" method="POST" class="space-y-6">
            @csrf
            <flux:label>{{ __('Company') }}</flux:label>
            <div x-data="singleSelectSingle({ selectedId: null, options: @js($companies) })"
                 @click.outside="open = false" class="relative mt-1">

                <div @click="open = !open"
                     class="flex items-center w-full p-2 border border-gray-300 dark:border-neutral-700 rounded-md shadow-sm cursor-pointer min-h-[40px] 
                            focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <span x-text="selectedName ?? 'Select a company...'"
                          :class="{'text-gray-900 dark:text-gray-100': selectedId, 'text-gray-400 dark:text-gray-200': !selectedId}">
                    </span>
                    <button x-show="selectedId" type="button" @click.stop="clearSelection()"
                            class="ml-auto text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">&times;</button>
                </div>

                <div x-show="open" x-transition.origin.top.left
                     class="absolute z-10 w-full mt-1 p-2 rounded-lg shadow-xl 
                            backdrop-blur-md dark:backdrop-blur-sm 
                            bg-white/70 dark:bg-neutral-800/70 
                            border border-gray-200 dark:border-neutral-700/50 
                            ring-1 ring-black/5"
                     style="display: none;">

                    <input type="text" x-model="search" placeholder="Search companies..."
                           class="w-full p-2 mb-2 border-gray-300 dark:border-neutral-600 rounded-md text-sm dark:bg-neutral-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">

                    <div class="max-h-60 overflow-y-auto">
                        <template x-for="option in filteredOptions" :key="option.id">
                            <div @click="select(option); open = false;"
                                 :class="{'bg-indigo-50 dark:bg-indigo-900/50': option.id === selectedId, 'hover:bg-gray-100 dark:hover:bg-neutral-700/70': option.id !== selectedId}"
                                 class="p-2 cursor-pointer rounded-md text-gray-900 dark:text-gray-100 text-sm">
                                <span x-text="option.name"></span>
                            </div>
                        </template>
                        <p x-show="filteredOptions.length === 0" class="p-2 text-center text-gray-500 dark:text-gray-400 text-sm">No results found.</p>
                    </div>
                </div>

                <input type="hidden" name="company_id" :value="selectedId" x-model="selectedId">
            </div>        
         
            <flux:label>
            {{ __('Customer Name') }}<span class="text-red-500">*</span>
            </flux:label>
            <flux:input 
                name="name" 
                type="text" 
                class="mt-2"
                required 
                autofocus 
            />
            <flux:input name="email" :label="__('Email')" type="email" />
            <flux:input name="phone" :label="__('Phone')" type="text" />
            <flux:input name="position" :label="__('Position')" type="text" />
                        <div>
                <flux:label>{{ __('Notes') }}</flux:label>
                <flux:textarea name="notes" rows="3" class="mt-1"></flux:textarea>
            </div>

            <div class="flex justify-end pt-4 gap-3">
                <flux:button as="a" href="{{ route('customers.index') }}" variant="danger">Cancel</flux:button>
                <flux:button variant="primary" type="submit">Save Customer</flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>

<script>
function singleSelectSingle(config) {
    return {
        open: false,
        search: '',
        selectedId: config.selectedId ?? null,
        options: config.options || [],

        get selectedName() {
            const sel = this.options.find(o => o.id === this.selectedId);
            return sel ? sel.name : null;
        },
        get filteredOptions() {
            if (this.search === '') return this.options;
            const t = this.search.toLowerCase();
            return this.options.filter(o => o.name.toLowerCase().includes(t));
        },
        select(option) { this.selectedId = option.id; },
        clearSelection() { this.selectedId = null; this.open = false; }
    }
}
</script>
