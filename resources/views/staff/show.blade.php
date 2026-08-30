<x-app-layout>

    @php
        $statusDot = [
            'new lead'           => 'db-dot--new',
            'contacted'          => 'db-dot--contacted',
            'interested'         => 'db-dot--contacted',
            'follow-up required' => 'db-dot--follow-up',
            'quotation sent'     => 'db-dot--follow-up',
            'converted'          => 'db-dot--converted',
            'lost'               => 'db-dot--lost',
        ];

        $priorityClass = [
            'high'   => 'db-priority-high',
            'medium' => 'db-priority-medium',
            'low'    => 'db-priority-low',
        ];
    @endphp

    <div class="db-root py-8 sm:py-10 min-h-screen">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Page Header --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="db-heading text-2xl font-semibold" style="color: var(--db-ink);">
                        Staff Details
                    </h2>
                    <p class="mt-1 text-sm" style="color: var(--db-ink-soft);">
                        View staff member information and assigned leads.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('staff.index') }}" class="db-btn-secondary">
                        Back to Staff
                    </a>
                    <a href="{{ route('staff.edit', $user) }}" class="db-btn-primary">
                        Edit Staff
                    </a>
                </div>
            </div>

            {{-- Staff Information --}}
            <div class="db-card">
                <div class="p-5 sm:p-6">

                    <h3 class="db-heading text-base font-semibold mb-5" style="color: var(--db-ink);">
                        Staff Information
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div>
                            <p class="db-metric-label">Name</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">{{ $user->name }}</p>
                        </div>

                        <div>
                            <p class="db-metric-label">Email Address</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">{{ $user->email }}</p>
                        </div>

                        <div>
                            <p class="db-metric-label">Role</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">{{ ucfirst($user->role) }}</p>
                        </div>

                        <div>
                            <p class="db-metric-label">Assigned Leads</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">{{ $user->assigned_leads_count }}</p>
                        </div>

                        <div>
                            <p class="db-metric-label">Account Created</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">{{ $user->created_at->format('d M Y, h:i A') }}</p>
                        </div>

                    </div>

                </div>
            </div>

            {{-- Assigned Leads --}}
            <div>

                <h3 class="db-heading text-lg font-semibold mb-4" style="color: var(--db-ink);">
                    Assigned Leads
                </h3>

                @if ($assignedLeads->count() > 0)

                    <div class="db-card">
                        <div class="overflow-x-auto">
                            <table class="db-table w-full min-w-[640px]">
                                <thead>
                                    <tr>
                                        <th>Lead Name</th>
                                        <th>Product / Service</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Follow-up Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($assignedLeads as $lead)
                                        <tr>
                                            <td class="font-medium" style="color: var(--db-ink);">
                                                {{ $lead->name }}
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
                                            <td>
                                                @if ($lead->follow_up_date)
                                                    <span style="color: var(--db-ink-soft);">
                                                        {{ \Carbon\Carbon::parse($lead->follow_up_date)->format('d M Y') }}
                                                    </span>
                                                @else
                                                    <span style="color: var(--db-ink-faint);">Not scheduled</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('leads.show', $lead) }}" class="db-link-accent">
                                                    View Lead
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                @else

                    <div class="db-card text-center py-12" style="color: var(--db-ink-soft);">
                        No leads are currently assigned to this staff member.
                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>