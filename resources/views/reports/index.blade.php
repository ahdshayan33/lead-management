<x-app-layout>

    <div class="db-root py-8 sm:py-10 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Page Header --}}
            <div>
                <h2 class="db-heading text-2xl font-semibold" style="color: var(--db-ink);">
                    Reports & Analytics
                </h2>
                <p class="mt-1 text-sm" style="color: var(--db-ink-soft);">
                    Overview of lead performance, conversions, follow-ups and staff performance.
                </p>
            </div>

            {{-- ========================================================= --}}
            {{-- OVERALL LEAD STATISTICS --}}
            {{-- ========================================================= --}}

            <div>
                <h3 class="db-heading text-lg font-semibold mb-4" style="color: var(--db-ink);">
                    Lead Reports
                </h3>

                <div class="db-card overflow-hidden">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 divide-x divide-y sm:divide-y-0" style="border-color: var(--db-border);">

                        <div class="px-4 sm:px-5 py-4 sm:py-5">
                            <p class="db-metric-label">Total Leads</p>
                            <p class="db-metric-value">{{ $totalLeads }}</p>
                        </div>

                        <div class="px-4 sm:px-5 py-4 sm:py-5">
                            <p class="db-metric-label">New Leads</p>
                            <p class="db-metric-value db-metric-value--accent">{{ $newLeads }}</p>
                        </div>

                        <div class="px-4 sm:px-5 py-4 sm:py-5">
                            <p class="db-metric-label">Converted Leads</p>
                            <p class="db-metric-value db-metric-value--success">{{ $convertedLeads }}</p>
                        </div>

                        <div class="px-4 sm:px-5 py-4 sm:py-5">
                            <p class="db-metric-label">Lost Leads</p>
                            <p class="db-metric-value db-metric-value--danger">{{ $lostLeads }}</p>
                        </div>

                        <div class="px-4 sm:px-5 py-4 sm:py-5">
                            <p class="db-metric-label">Conversion Rate</p>
                            <p class="db-metric-value db-metric-value--accent">{{ $conversionRate }}%</p>
                        </div>

                        <div class="px-4 sm:px-5 py-4 sm:py-5">
                            <p class="db-metric-label">Total Conversion Revenue</p>
                            <p class="db-metric-value">{{ number_format($totalRevenue, 2) }}</p>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- FOLLOW-UP STATISTICS --}}
            {{-- ========================================================= --}}

            <div class="db-card overflow-hidden">
                <div class="grid grid-cols-2 divide-x" style="border-color: var(--db-border);">

                    <div class="px-4 sm:px-5 py-4 sm:py-5">
                        <p class="db-metric-label">Pending Follow-ups</p>
                        <p class="db-metric-value db-metric-value--accent">{{ $pendingFollowUps }}</p>
                    </div>

                    <div class="px-4 sm:px-5 py-4 sm:py-5">
                        <p class="db-metric-label">Overdue Follow-ups</p>
                        <p class="db-metric-value db-metric-value--danger">{{ $overdueFollowUps }}</p>
                    </div>

                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- LEAD STATUS ANALYSIS --}}
            {{-- ========================================================= --}}

            <div class="db-card">
                <div class="p-5 sm:p-6">

                    <h3 class="db-heading text-lg font-semibold mb-5" style="color: var(--db-ink);">
                        Lead Status Analysis
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="db-table w-full">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Number of Leads</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($statusCounts as $status => $count)
                                    <tr>
                                        <td class="font-medium" style="color: var(--db-ink);">
                                            {{ $status }}
                                        </td>
                                        <td class="font-semibold" style="color: var(--db-ink);">
                                            {{ $count }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- COMMUNICATION PREFERENCE --}}
            {{-- ========================================================= --}}

            <div class="db-card">
                <div class="p-5 sm:p-6">

                    <h3 class="db-heading text-lg font-semibold mb-5" style="color: var(--db-ink);">
                        Communication Preference Analysis
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">

                        <!-- Email -->
                        <div class="db-tint-block db-tint-block--accent">
                            <p class="text-sm font-medium" style="color: var(--db-ink-soft);">
                                Email
                            </p>
                            <p class="db-metric-value db-metric-value--accent">
                                {{ $emailLeads }}
                            </p>
                            <p class="text-sm mt-1" style="color: var(--db-ink-soft);">
                                Leads preferring email communication
                            </p>
                        </div>

                        <!-- WhatsApp -->
                        <div class="db-tint-block db-tint-block--success">
                            <p class="text-sm font-medium" style="color: var(--db-ink-soft);">
                                WhatsApp
                            </p>
                            <p class="db-metric-value db-metric-value--success">
                                {{ $whatsappLeads }}
                            </p>
                            <p class="text-sm mt-1" style="color: var(--db-ink-soft);">
                                Leads preferring WhatsApp communication
                            </p>
                        </div>

                    </div>

                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- MOST REQUESTED PRODUCTS --}}
            {{-- ========================================================= --}}

            <div class="db-card">
                <div class="p-5 sm:p-6">

                    <h3 class="db-heading text-lg font-semibold mb-5" style="color: var(--db-ink);">
                        Most Requested Products / Services
                    </h3>

                    @if ($topProducts->count() > 0)

                        <div class="overflow-x-auto">
                            <table class="db-table w-full">
                                <thead>
                                    <tr>
                                        <th>Product / Service</th>
                                        <th>Number of Leads</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topProducts as $product)
                                        <tr>
                                            <td class="font-medium" style="color: var(--db-ink);">
                                                {{ $product->product_service }}
                                            </td>
                                            <td class="font-semibold" style="color: var(--db-ink);">
                                                {{ $product->total }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @else

                        <p class="text-sm" style="color: var(--db-ink-soft);">
                            No product/service data available.
                        </p>

                    @endif

                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- STAFF PERFORMANCE --}}
            {{-- ========================================================= --}}

            <div class="db-card">
                <div class="p-5 sm:p-6">

                    <div class="mb-5">
                        <h3 class="db-heading text-lg font-semibold" style="color: var(--db-ink);">
                            Staff Performance
                        </h3>
                        <p class="text-sm mt-0.5" style="color: var(--db-ink-soft);">
                            Performance overview for each staff member.
                        </p>
                    </div>

                    @if ($staffPerformance->count() > 0)

                        <div class="overflow-x-auto">
                            <table class="db-table w-full min-w-[820px]">
                                <thead>
                                    <tr>
                                        <th>Staff Member</th>
                                        <th>Assigned Leads</th>
                                        <th>Completed Follow-ups</th>
                                        <th>Avg. Response Time</th>
                                        <th>Converted Leads</th>
                                        <th>Pending Follow-ups</th>
                                        <th>Overdue Follow-ups</th>
                                        <th>Conversion %</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($staffPerformance as $member)
                                        <tr>
                                            <td class="font-semibold" style="color: var(--db-ink);">
                                                {{ $member['name'] }}
                                            </td>
                                            <td style="color: var(--db-ink);">
                                                {{ $member['assigned_leads'] }}
                                            </td>
                                            <td style="color: var(--db-ink);">
                                                {{ $member['completed_follow_ups'] }}
                                            </td>
                                            <td style="color: var(--db-ink);">
                                                {{ $member['average_response_time'] }}
                                            </td>
                                            <td class="db-text-success font-semibold">
                                                {{ $member['converted_leads'] }}
                                            </td>
                                            <td class="db-text-accent">
                                                {{ $member['pending_follow_ups'] }}
                                            </td>
                                            <td class="db-text-danger font-semibold">
                                                {{ $member['overdue_follow_ups'] }}
                                            </td>
                                            <td class="db-text-accent font-semibold">
                                                {{ $member['conversion_percentage'] }}%
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @else

                        <div class="text-center py-10" style="color: var(--db-ink-soft);">
                            No staff performance data available.
                        </div>

                    @endif

                </div>
            </div>

        </div>

    </div>

</x-app-layout>