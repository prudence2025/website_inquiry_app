<x-layouts.app :title="__('Edit Industry')">
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-neutral-100 mb-6">
            {{ __('Edit Industry') }}
        </h1>

        <form action="{{ route('industries.update', $industry) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <flux:input name="name" :label="__('Industry Name')" type="text"
                        value="{{ old('name', $industry->name) }}" required autofocus />

            <div>
                <flux:label>{{ __('Description') }}</flux:label>
                <flux:textarea name="description" rows="3">{{ old('description', $industry->description) }}</flux:textarea>
            </div>

            <div class="flex justify-end pt-4 gap-3">
                 <flux:button as="a" href="{{ route('industries.index') }}" variant="danger">
                    {{ __('Cancel') }}
                </flux:button>
                <flux:button variant="primary" type="submit">
                    {{ __('Update Industry') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
