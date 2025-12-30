<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl">

        {{-- 🔹 TOP SECTION: 3x2 GRID (6 CARDS) --}}
        <div class="grid gap-4 md:grid-cols-3">

            {{-- 🟦 Total Inquiries --}}
            <div class="p-4 rounded-xl border border-blue-200 dark:border-blue-800 bg-blue-50 dark:bg-blue-900/30 shadow-sm">
                <div class="flex items-center gap-2 text-sm text-blue-700 dark:text-blue-300 font-medium">
                    <x-gmdi-assignment width="18" height="18" class="text-blue-600 dark:text-blue-400" />
                    Total Inquiries
                </div>
                <div class="text-2xl font-semibold mt-1 text-blue-800 dark:text-blue-200">{{ $totalInquiries }}</div>
            </div>

             {{-- 🟨 Total Earnings --}}
            <div class="p-4 rounded-xl border border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-900/30 shadow-sm">
                <div class="flex items-center gap-2 text-sm text-yellow-700 dark:text-yellow-300 font-medium">
                    <x-gmdi-payments width="18" height="18" class="text-yellow-600 dark:text-yellow-400" />
                    Total Earnings (Settled)
                </div>
                <div class="text-2xl font-semibold mt-1 text-yellow-800 dark:text-yellow-200">
                    LKR {{ number_format($totalEarnings, 2) }}
                </div>
            </div>

             {{-- 🟪 Companies --}}
            <div class="p-4 rounded-xl border border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-900/30 shadow-sm">
                <div class="flex items-center gap-2 text-sm text-indigo-700 dark:text-indigo-300 font-medium">
                    <x-gmdi-business-center width="18" height="18" class="text-indigo-600 dark:text-indigo-400" />
                    Companies
                </div>
                <div class="text-2xl font-semibold mt-1 text-indigo-800 dark:text-indigo-200">{{ $totalCompanies }}</div>
            </div>

             {{-- 🟩 Inquiries in Range --}}
            <div class="p-4 rounded-xl border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-900/30 shadow-sm">
                <div class="flex items-center gap-2 text-sm text-green-700 dark:text-green-300 font-medium">
                    <x-gmdi-date-range width="18" height="18" class="text-green-600 dark:text-green-400" />
                    Inquiries <span class="text-xs opacity-80 ml-1">({{ $from }} — {{ $to }})</span>
                </div>
                <div class="text-2xl font-semibold mt-1 text-green-800 dark:text-green-200">{{ $rangeInquiries }}</div>
            </div>

              {{-- 🟧 Range Earnings --}}
            <div class="p-4 rounded-xl border border-orange-200 dark:border-orange-800 bg-orange-50 dark:bg-orange-900/30 shadow-sm">
                <div class="flex items-center gap-2 text-sm text-orange-700 dark:text-orange-300 font-medium">
                    <x-gmdi-trending-up width="18" height="18" class="text-orange-600 dark:text-orange-400" />
                    Earnings <span class="text-xs opacity-80 ml-1">({{ $from }} — {{ $to }})</span>
                </div>
                <div class="text-2xl font-semibold mt-1 text-orange-800 dark:text-orange-200">
                    LKR {{ number_format($rangeEarnings, 2) }}
                </div>
            </div>


            {{-- 🩷 Customers --}}
            <div class="p-4 rounded-xl border border-pink-200 dark:border-pink-800 bg-pink-50 dark:bg-pink-900/30 shadow-sm">
                <div class="flex items-center gap-2 text-sm text-pink-700 dark:text-pink-300 font-medium">
                    <x-gmdi-group width="18" height="18" class="text-pink-600 dark:text-pink-400" />
                    Customers
                </div>
                <div class="text-2xl font-semibold mt-1 text-pink-800 dark:text-pink-200">{{ $totalCustomers }}</div>
            </div>

           

        </div>

        {{-- 🔹 CHART SECTION --}}
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border p-4 flex flex-col shadow-sm">
            <form method="GET" action="{{ route('dashboard') }}"
                  class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
                <div class="flex flex-wrap items-center gap-3">
                    <x-gmdi-date-range width="18" height="18" class="text-indigo-500" />
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">From</label>
                    <input type="date" name="from" value="{{ $from }}"
                           class="rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200 text-sm px-2 py-1 focus:ring-2 focus:ring-indigo-500" />
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">To</label>
                    <input type="date" name="to" value="{{ $to }}"
                           class="rounded-md border-gray-300 dark:border-neutral-700 dark:bg-neutral-900 dark:text-neutral-200 text-sm px-2 py-1 focus:ring-2 focus:ring-indigo-500" />

                    <div class="flex items-center gap-2 ml-2">
                        <button type="submit"
                                class="text-blue-500 hover:bg-blue-100 dark:hover:bg-blue-900/30 border border-blue-600 text-xs font-medium rounded-md px-3 py-1.5 transition">
                            <x-gmdi-check class="inline w-4 h-4 mr-1" /> Apply
                        </button>
                        <a href="{{ route('dashboard') }}"
                           class="text-red-500 hover:bg-red-100 dark:hover:bg-red-900/30 border border-red-400 text-xs font-medium rounded-md px-3 py-1.5 transition">
                            <x-gmdi-refresh class="inline w-4 h-4 mr-1" /> Reset
                        </a>
                    </div>
                </div>

                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Showing results from <strong>{{ $from }}</strong> to <strong>{{ $to }}</strong>.
                </div>
            </form>

            {{-- Chart --}}
            <div x-data="dashboardChart()" x-init="initChart()" style="height:400px;">
                <canvas id="inquiriesChart"></canvas>
            </div>

        </div>

        {{-- 🔹 STATUSES BELOW CHART --}}
        <div class=" p-4 rounded-xl border border-gray-200 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-900/50 shadow-sm">
            <div class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                <x-gmdi-insights width="18" height="18" class="text-gray-500 dark:text-gray-400" />
                Inquiry Statuses
            </div>

            <div class="grid grid-cols-[repeat(auto-fit,minmax(120px,1fr))] gap-2">
                @foreach ($statusCounts as $status => $count)
                    @php
                        $color = match($status) {
                            'Settled' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                            'Discussing' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
                            'Quoted' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                            'Received' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                            'Dropped' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                        };
                    @endphp
                    <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $color }}">
                        {{ $status }}: {{ $count }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>

     {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- UPDATED: Alpine.js Component --}}
    <script>
    function dashboardChart() {
        let chartInstance = null;
        let chartLoaded = true;
        let errorMsg = '';

        return {
            chartLoaded,
            errorMsg,

            initChart() {
                this.refreshChart();
            },

            destroyChart() {
                if (chartInstance) {
                    chartInstance.destroy();
                    chartInstance = null;
                }
                chartLoaded = true;
                errorMsg = '';
            },

            refreshChart() {
                this.destroyChart();

                // Wait a bit for DOM/data to settle (helps on fresh login)
                setTimeout(() => {
                    const ctx = document.getElementById('inquiriesChart');
                    if (!ctx) {
                        errorMsg = 'Canvas element not found.';
                        return;
                    }

                    try {
                        // Verify Chart.js  loaded
                        if (typeof Chart === 'undefined') {
                            errorMsg = 'Chart.js not loaded. Retrying...';
                            // Retry once after 1s
                            setTimeout(() => this.refreshChart(), 1000);
                            return;
                        }

                        chartInstance = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: @json($labels),
                                datasets: [{
                                    label: 'Inquiries',
                                    data: @json($series),
                                    fill: true,
                                    tension: 0.3,
                                    borderWidth: 2,
                                    borderColor: '#2B7BD8',
                                    backgroundColor: 'rgba(34, 53, 109, 0.1)',
                                    pointRadius: 3,
                                }]
                            },
                           options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                animation: {
                                    duration: 1000, // 1s animation
                                    easing: 'easeOutQuart'
                                },
                                scales: {
                                    x: { display: true },
                                    y: { 
                                        beginAtZero: true,
                                        ticks: {
                                            stepSize: 1
                                        }
                                    }
                                },
                                plugins: {
                                    legend: { display: false },
                                    tooltip: { mode: 'index' }
                                }
                            }
                        });

                        chartLoaded = true;
                    } catch (error) {
                        errorMsg = `Chart error: ${error.message}`;
                        console.error('Chart init error:', error);
                    }
                }, 200); // 200ms delay for stability
            }
        }
    }
    </script>
</x-layouts.app>
    