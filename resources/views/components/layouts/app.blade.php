<x-layouts.app.sidebar :title="config('app.name') . ' | ' . ($title ?? 'Welcome')">
    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>  
