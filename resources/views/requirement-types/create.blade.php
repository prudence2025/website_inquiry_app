<x-layouts.app :title="__('Add Requirement Type')">
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-neutral-100 mb-6">
            {{ __('Add Requirement Type') }}
        </h1>

        <form action="{{ route('requirement-types.store') }}" method="POST" class="space-y-6">
            @csrf

            <flux:input name="name" :label="__('Type Name')" type="text" required autofocus />

            <div>
                <flux:label>{{ __('Description') }}</flux:label>
                <flux:textarea name="description" rows="3"></flux:textarea>
            </div>

            <div class="flex justify-end pt-4 gap-3">
                <flux:button as="a" href="{{ route('requirement-types.index') }}" variant="danger">
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button variant="primary" type="submit">
                    {{ __('Save Type') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
