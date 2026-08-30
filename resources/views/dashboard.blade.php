<x-app-layout>

    @php
        $statusDot = [
            'new'        => 'db-dot--new',
            'contacted'  => 'db-dot--contacted',
            'follow-up'  => 'db-dot--follow-up',
            'converted'  => 'db-dot--converted',
            'lost'       => 'db-dot--lost',
        ];

        $priorityClass = [
            'high'   => 'db-priority-high',
            'medium' => 'db-priority-medium',
            'low'    => 'db-priority-low',
        ];
    @endphp

    <div class="db-root py-8 sm:py-10 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Page Header --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                <div>
                    <h2 class="db-heading text-2xl font-semibold" style="color: var(--db-ink);">
                        Dashboard
                    </h2>
                    <p class="mt-1 text-sm" style="color: var(--db-ink-soft);">
                        Welcome back, {{ auth()->user()->name }}. Here's what's happening with your leads.
                    </p>
                </div>

                <div class="flex items-center gap-4 sm:mt-1">
                    <nav class="flex items-center gap-3 text-sm">
                        <a href="{{ route('reports.index') }}" class="db-link-muted">
                            Reports & Analytics
                        </a>

                        @if (auth()->user()->role === 'admin')
                            <span style="color: var(--db-border);">&bull;</span>
                            <a href="{{ route('leads.activities.index') }}" class="db-link-muted">
                                Lead Activities
                            </a>
                        @endif
                    </nav>

                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('follow-ups.index') }}" class="db-btn-primary">
                            Follow-up Management
                        </a>
                    @endif

                    <a href="{{ route('leads.index') }}" class="db-btn-primary">
                        Leads
                    </a>
                </div>

            </div>

            {{-- Metrics ledger strip --}}
            <div class="db-card overflow-hidden">
                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 divide-x divide-y sm:divide-y-0" style="border-color: var(--db-border);">

                    <div class="px-4 sm:px-5 py-4 sm:py-5" style="border-color: var(--db-border);">
                        <p class="db-metric-label">Total Leads</p>
                        <p class="db-metric-value">{{ $totalLeads }}</p>
                    </div>

                    <div class="px-4 sm:px-5 py-4 sm:py-5" style="border-color: var(--db-border);">
                        <p class="db-metric-label">New Leads</p>
                        <p class="db-metric-value db-metric-value--accent">{{ $newLeads }}</p>
                    </div>

                    <div class="px-4 sm:px-5 py-4 sm:py-5" style="border-color: var(--db-border);">
                        <p class="db-metric-label">Contacted</p>
                        <p class="db-metric-value">{{ $contactedLeads }}</p>
                    </div>

                    <div class="px-4 sm:px-5 py-4 sm:py-5" style="border-color: var(--db-border);">
                        <p class="db-metric-label">Follow-up</p>
                        <p class="db-metric-value db-metric-value--warning">{{ $followUpLeads }}</p>
                    </div>

                    <div class="px-4 sm:px-5 py-4 sm:py-5" style="border-color: var(--db-border);">
                        <p class="db-metric-label">Converted</p>
                        <p class="db-metric-value db-metric-value--success">{{ $convertedLeads }}</p>
                    </div>

                    <div class="px-4 sm:px-5 py-4 sm:py-5" style="border-color: var(--db-border);">
                        <p class="db-metric-label">Lost</p>
                        <p class="db-metric-value db-metric-value--muted">{{ $lostLeads }}</p>
                    </div>

                    <div class="px-4 sm:px-5 py-4 sm:py-5" style="border-color: var(--db-border);">
                        <p class="db-metric-label">High Priority</p>
                        <p class="db-metric-value db-metric-value--danger">{{ $highPriorityLeads }}</p>
                    </div>

                    <div class="px-4 sm:px-5 py-4 sm:py-5" style="border-color: var(--db-border);">
                        <p class="db-metric-label">Conversion Rate</p>
                        <p class="db-metric-value db-metric-value--accent">
                            @if ($totalLeads > 0)
                                {{ round(($convertedLeads / $totalLeads) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </p>
                    </div>

                </div>
            </div>

            {{-- Recent Leads --}}
            <div class="db-card">

                <div class="p-5 sm:p-6">

                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="db-heading text-base font-semibold" style="color: var(--db-ink);">Recent Leads</h3>
                            <p class="text-sm mt-0.5" style="color: var(--db-ink-soft);">Your most recently registered leads.</p>
                        </div>

                        <a href="{{ route('leads.index') }}" class="db-link-accent text-sm whitespace-nowrap">
                            View all &rarr;
                        </a>
                    </div>

                    @if ($recentLeads->count() > 0)

                        <div class="overflow-x-auto -mx-5 sm:mx-0">
                            <table class="db-table w-full min-w-[640px] sm:min-w-0">
                                <thead>
                                    <tr>
                                        <th>Lead Name</th>
                                        <th>Product / Service</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Assigned Staff</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recentLeads as $lead)
                                        <tr>
                                            <td>
                                                <a href="{{ route('leads.show', $lead) }}"
                                                   class="font-medium" style="color: var(--db-ink);">
                                                    {{ $lead->name }}
                                                </a>
                                            </td>
                                            <td style="color: var(--db-ink-soft);">
                                                {{ $lead->product_service }}
                                            </td>
                                            <td>
                                                <span class="inline-flex items-center gap-1.5" style="color: var(--db-ink);">
                                                    <span class="db-dot {{ $statusDot[strtolower($lead->status)] ?? 'db-dot--contacted' }}"></span>
                                                    {{ $lead->status }}
                                                </span>
                                            </td>
                                            <td class="{{ $priorityClass[strtolower($lead->priority ?? 'medium')] ?? $priorityClass['medium'] }}">
                                                {{ $lead->priority ?? 'Medium' }}
                                            </td>
                                            <td style="color: var(--db-ink-soft);">
                                                {{ $lead->assignedStaff->name ?? 'Unassigned' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @else

                        <div class="text-center py-12" style="color: var(--db-ink-soft);">
                            <p class="font-medium" style="color: var(--db-ink);">No leads found.</p>
                            <p class="text-sm mt-1">New leads you add will show up here.</p>
                        </div>

                    @endif

                </div>

            </div>

            {{-- Follow-up Management --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Overdue Follow-ups --}}
                <div class="db-card">
                    <div class="p-5 sm:p-6">

                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="db-heading text-base font-semibold" style="color: var(--db-ink);">Overdue Follow-ups</h3>
                                <p class="text-sm mt-0.5" style="color: var(--db-ink-soft);">Leads that need your attention.</p>
                            </div>
                            <span class="db-count-pill db-count-pill--danger">
                                {{ $overdueFollowUps->count() }}
                            </span>
                        </div>

                        @if ($overdueFollowUps->count() > 0)

                            <div class="divide-y max-h-[24rem] overflow-y-auto" style="border-color: var(--db-border-soft);">
                                @foreach ($overdueFollowUps as $lead)
                                    <div class="py-3.5 first:pt-0">
                                        <div class="flex justify-between items-start gap-3">
                                            <div class="min-w-0 flex items-start gap-2.5">
                                                <span class="db-dot db-dot--lost mt-2 shrink-0"></span>
                                                <div class="min-w-0">
                                                    <a href="{{ route('leads.show', $lead) }}"
                                                       class="font-medium truncate block text-sm" style="color: var(--db-ink);">
                                                        {{ $lead->name }}
                                                    </a>
                                                    <p class="text-xs mt-0.5 truncate" style="color: var(--db-ink-soft);">
                                                        {{ $lead->product_service }}
                                                    </p>
                                                </div>
                                            </div>
                                            <span class="text-xs font-medium whitespace-nowrap" style="color: var(--db-danger);">
                                                {{ \Carbon\Carbon::parse($lead->follow_up_date)->format('d M Y') }}
                                            </span>
                                        </div>

                                        <div class="flex flex-wrap justify-between items-center gap-2 mt-2 pl-4">
                                            <div class="flex items-center gap-3 text-xs" style="color: var(--db-ink-faint);">
                                                <span>{{ $lead->status }}</span>
                                                @if (auth()->user()->role === 'admin')
                                                    <span>{{ $lead->assignedStaff->name ?? 'Unassigned' }}</span>
                                                @endif
                                            </div>
                                            <span class="text-[11px] font-medium uppercase tracking-wide" style="color: var(--db-danger);">
                                                Overdue
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @else

                            <div class="text-center py-8" style="color: var(--db-ink-soft);">
                                <p class="font-medium" style="color: var(--db-ink);">No overdue follow-ups 🎉</p>
                                <p class="text-sm mt-1">You're all caught up.</p>
                            </div>

                        @endif

                    </div>
                </div>

                {{-- Upcoming Follow-ups --}}
                <div class="db-card">
                    <div class="p-5 sm:p-6">

                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="db-heading text-base font-semibold" style="color: var(--db-ink);">Upcoming Follow-ups</h3>
                                <p class="text-sm mt-0.5" style="color: var(--db-ink-soft);">Your upcoming follow-up dates.</p>
                            </div>
                            <span class="db-count-pill db-count-pill--accent">
                                {{ $upcomingFollowUps->count() }}
                            </span>
                        </div>

                        @if ($upcomingFollowUps->count() > 0)

                            <div class="divide-y max-h-[24rem] overflow-y-auto" style="border-color: var(--db-border-soft);">
                                @foreach ($upcomingFollowUps as $lead)
                                    <div class="py-3.5 first:pt-0">
                                        <div class="flex justify-between items-start gap-3">
                                            <div class="min-w-0 flex items-start gap-2.5">
                                                <span class="db-dot db-dot--new mt-2 shrink-0"></span>
                                                <div class="min-w-0">
                                                    <a href="{{ route('leads.show', $lead) }}"
                                                       class="font-medium truncate block text-sm" style="color: var(--db-ink);">
                                                        {{ $lead->name }}
                                                    </a>
                                                    <p class="text-xs mt-0.5 truncate" style="color: var(--db-ink-soft);">
                                                        {{ $lead->product_service }}
                                                    </p>
                                                </div>
                                            </div>
                                            <span class="text-xs font-medium whitespace-nowrap" style="color: var(--db-accent);">
                                                {{ \Carbon\Carbon::parse($lead->follow_up_date)->format('d M Y') }}
                                            </span>
                                        </div>

                                        <div class="flex justify-between items-center mt-2 pl-4">
                                            <span class="text-xs" style="color: var(--db-ink-faint);">{{ $lead->status }}</span>
                                            <span class="text-[11px] font-medium" style="color: var(--db-accent);">
                                                {{ \Carbon\Carbon::parse($lead->follow_up_date)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        @else

                            <div class="text-center py-8" style="color: var(--db-ink-soft);">
                                <p class="font-medium" style="color: var(--db-ink);">No upcoming follow-ups.</p>
                                <p class="text-sm mt-1">There are no scheduled follow-ups.</p>
                            </div>

                        @endif

                    </div>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>