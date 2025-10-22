<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-4">

        {{-- TOP SECTION: 3-column grid --}}
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">

            {{-- COLUMN 1: Inquiry totals --}}
            <div class="flex flex-col gap-4">
                <div class="p-4 rounded-lg border">
                    <div class="text-sm">Total Inquiries</div>
                    <div class="text-2xl font-semibold mt-2">{{ $totalInquiries }}</div>
                </div>

                <div class="p-4 rounded-lg border">
                    <div class="text-sm">
                        Inquiries <span class="ml-1 text-xs font-normal">
                            ({{ $from }} — {{ $to }})
                        </span>
                    </div>
                    <div class="text-2xl font-semibold mt-2">{{ $rangeInquiries }}</div>
                </div>
            </div>

            {{-- COLUMN 2: Companies & Customers --}}
            <div class="flex flex-col gap-4">
                <div class="p-4 rounded-lg border">
                    <div class="text-sm">Companies</div>
                    <div class="text-2xl font-semibold mt-2">{{ $totalCompanies }}</div>
                </div>

                <div class="p-4 rounded-lg border">
                    <div class="text-sm">Customers</div>
                    <div class="text-2xl font-semibold mt-2">{{ $totalCustomers }}</div>
                </div>
            </div>

            {{-- COLUMN 3: Inquiry Statuses --}}
           <div class="p-4 rounded-lg border flex flex-col gap-2">
            <div class="text-sm font-medium">Inquiry Statuses</div>
            <div class="grid grid-cols-2">
                @foreach ($statusCounts as $status => $count)
                    <div class="flex items-center justify-between px-2 py-1 rounded-md">
                        <div class="text-xs">{{ $status }}</div>
                        <div class="text-sm font-semibold">{{ $count }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        </div>

        {{-- BOTTOM SECTION: Chart --}}
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border p-4 flex flex-col">

            {{-- Date range form --}}
            <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <div class="flex items-center gap-3">
                    <label class="text-sm">From</label>
                    <input type="date" name="from" value="{{ $from }}" class="form-input rounded-md" />
                    <label class="text-sm">To</label>
                    <input type="date" name="to" value="{{ $to }}" class="form-input rounded-md" />
                    <button type="submit"
                        class="ml-2 inline-flex items-center rounded-md px-3 py-1 text-sm font-semibold hover:bg-indigo-700">
                        Apply
                    </button>
                    <a href="{{ route('dashboard') }}"
                        class="ml-2 inline-flex items-center rounded-md px-3 py-1 text-sm font-semibold">
                        Reset
                    </a>
                </div>

                <div class="text-sm">
                    Showing results from {{ $from }} to {{ $to }}.
                </div>
            </form>

            {{-- Chart --}}
            <div class="flex-1 h-full">
                <canvas id="inquiriesChart"  height="400"></canvas>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('inquiriesChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                        label: 'Inquiries',
                        data: {!! json_encode($series) !!},
                        fill: true,
                        tension: 0.3,
                        borderWidth: 2,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        pointRadius: 3,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { display: true },
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: { mode: 'index' }
                    }
                }
            });
        });
    </script>
</x-layouts.app>