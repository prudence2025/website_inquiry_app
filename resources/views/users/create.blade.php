<x-layouts.app :title="__('Add User')">
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-neutral-100 mb-6">
            {{ __('Add User') }}
        </h1>

        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf

            <flux:input name="name" :label="__('Name')" type="text" required autofocus />

            <flux:input name="email" :label="__('Email')" type="email" required />

            <flux:input name="password" :label="__('Password')" type="password" required />

            <flux:input name="password_confirmation" :label="__('Confirm Password')" type="password" required />

            <div class="flex justify-end pt-4 gap-3">
                <flux:button as="a" href="{{ route('users.index') }}" variant="danger">
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button variant="primary" type="submit">
                    {{ __('Save User') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
