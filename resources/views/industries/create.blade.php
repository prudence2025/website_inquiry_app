<x-layouts.app :title="__('Add Industry')">
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-neutral-100 mb-6">
            {{ __('Add Industry') }}
        </h1>

        {{-- Create Industry Form --}}
        <form action="{{ route('industries.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Industry Name --}}
            <flux:input name="name" :label="__('Industry Name')" type="text" required autofocus />

            {{-- Description --}}
            <div>
                <flux:label>{{ __('Description') }}</flux:label>
                <flux:textarea name="description" rows="3"></flux:textarea>
            </div>

            {{-- Actions --}}
            <div class="flex justify-end pt-4 gap-3">
                <flux:button as="a" href="{{ route('industries.index') }}" variant="danger">
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button variant="primary" type="submit">
                    {{ __('Save Industry') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
