<x-layouts.app :title="__('Edit User')">
    <div class="max-w-3xl mx-auto py-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-neutral-100 mb-6">
            {{ __('Edit User') }}
        </h1>

        <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <flux:input name="name" :label="__('Name')" type="text"
                        value="{{ old('name', $user->name) }}" required autofocus />

            <flux:input name="email" :label="__('Email')" type="email"
                        value="{{ old('email', $user->email) }}" required />

            <flux:input name="password" :label="__('New Password (optional)')" type="password" />

            <flux:input name="password_confirmation" :label="__('Confirm New Password')" type="password" />

            <div class="flex justify-end pt-4 gap-3">
                <flux:button as="a" href="{{ route('users.index') }}" variant="danger">
                    {{ __('Cancel') }}
                </flux:button>

                <flux:button variant="primary" type="submit">
                    {{ __('Update User') }}
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
