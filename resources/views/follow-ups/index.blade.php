<x-app-layout>

    <div class="db-root py-8 sm:py-10 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Page Header --}}
            <div>
                <h2 class="db-heading text-2xl font-semibold" style="color: var(--db-ink);">
                    Follow-Up Management
                </h2>
                <p class="mt-1 text-sm" style="color: var(--db-ink-soft);">
                    Review and manage lead follow-up reminders and escalations.
                </p>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="db-tint-block db-tint-block--success db-text-success text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Message --}}
            @if (session('error'))
                <div class="db-tint-block db-tint-block--danger db-text-danger text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ========================================================= --}}
            {{-- REMINDERS DUE TODAY --}}
            {{-- ========================================================= --}}
            <div class="db-card">
                <div class="p-5 sm:p-6 flex flex-wrap items-center justify-between gap-3" style="border-bottom: 1px solid var(--db-border);">
                    <div>
                        <h3 class="db-heading text-base font-semibold" style="color: var(--db-ink);">
                            🔴 Follow-Up Reminders Due Today
                        </h3>
                        <p class="text-sm mt-0.5" style="color: var(--db-ink-soft);">
                            Leads with no recorded activity for 3 days or more.
                        </p>
                    </div>

                    @if ($remindersDueToday->count() > 0)
                        <form action="{{ route('follow-ups.send-all') }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="db-btn-primary"
                                onclick="return confirm('Send follow-up reminders to all staff listed here?')"
                            >
                                Send All Reminders
                            </button>
                        </form>
                    @endif
                </div>

                @if ($remindersDueToday->count() > 0)

                    <div class="overflow-x-auto">
                        <table class="db-table w-full min-w-[720px]">
                            <thead>
                                <tr>
                                    <th>Lead</th>
                                    <th>Assigned Staff</th>
                                    <th>Last Activity</th>
                                    <th>Days Since Activity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($remindersDueToday as $lead)
                                    @php
                                        $latestActivity = $lead->activities->first();
                                        $daysSinceActivity = $latestActivity
                                            ? $latestActivity->created_at->diffInDays(now())
                                            : null;
                                    @endphp
                                    <tr>
                                        <td>
                                            <p class="font-medium" style="color: var(--db-ink);">{{ $lead->name }}</p>
                                            <p class="text-xs mt-0.5" style="color: var(--db-ink-faint);">{{ $lead->product_service }}</p>
                                        </td>
                                        <td style="color: var(--db-ink-soft);">
                                            {{ $lead->assignedStaff->name ?? 'Unassigned' }}
                                        </td>
                                        <td style="color: var(--db-ink-soft);">
                                            {{ $latestActivity
                                                ? $latestActivity->created_at->format('d M Y, h:i A')
                                                : 'No activity' }}
                                        </td>
                                        <td>
                                            <span class="db-badge-danger">{{ $daysSinceActivity }} days</span>
                                        </td>
                                        <td>
                                            <form action="{{ route('follow-ups.send', $lead) }}" method="POST">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="db-btn-primary"
                                                    onclick="return confirm('Send a follow-up reminder to {{ $lead->assignedStaff->name ?? 'the assigned staff' }}?')"
                                                >
                                                    Send Reminder
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @else

                    <div class="text-center py-12" style="color: var(--db-ink-soft);">
                        No follow-up reminders are due today.
                    </div>

                @endif
            </div>

            {{-- ========================================================= --}}
            {{-- REMINDERS DUE TOMORROW --}}
            {{-- ========================================================= --}}
            <div class="db-card">
                <div class="p-5 sm:p-6" style="border-bottom: 1px solid var(--db-border);">
                    <h3 class="db-heading text-base font-semibold" style="color: var(--db-ink);">
                        🟡 Follow-Up Reminders Due Tomorrow
                    </h3>
                    <p class="text-sm mt-0.5" style="color: var(--db-ink-soft);">
                        Leads expected to require a reminder tomorrow if no new activity is recorded.
                    </p>
                </div>

                @if ($remindersDueTomorrow->count() > 0)

                    <div class="overflow-x-auto">
                        <table class="db-table w-full min-w-[640px]">
                            <thead>
                                <tr>
                                    <th>Lead</th>
                                    <th>Assigned Staff</th>
                                    <th>Last Activity</th>
                                    <th>Reminder Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($remindersDueTomorrow as $lead)
                                    @php
                                        $latestActivity = $lead->activities->first();
                                        $reminderDueDate = $latestActivity
                                            ? $latestActivity->created_at->copy()->addDays(3)
                                            : null;
                                    @endphp
                                    <tr>
                                        <td>
                                            <p class="font-medium" style="color: var(--db-ink);">{{ $lead->name }}</p>
                                            <p class="text-xs mt-0.5" style="color: var(--db-ink-faint);">{{ $lead->product_service }}</p>
                                        </td>
                                        <td style="color: var(--db-ink-soft);">
                                            {{ $lead->assignedStaff->name ?? 'Unassigned' }}
                                        </td>
                                        <td style="color: var(--db-ink-soft);">
                                            {{ $latestActivity
                                                ? $latestActivity->created_at->format('d M Y, h:i A')
                                                : 'No activity' }}
                                        </td>
                                        <td>
                                            @if ($reminderDueDate)
                                                <span class="db-badge-warning">{{ $reminderDueDate->format('d M Y') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @else

                    <div class="text-center py-12" style="color: var(--db-ink-soft);">
                        No follow-up reminders are expected tomorrow.
                    </div>

                @endif
            </div>

            {{-- ========================================================= --}}
            {{-- ADMIN ESCALATIONS --}}
            {{-- ========================================================= --}}
            <div class="db-card">
                <div class="p-5 sm:p-6" style="border-bottom: 1px solid var(--db-border);">
                    <h3 class="db-heading text-base font-semibold" style="color: var(--db-ink);">
                        🔴 Admin Escalations Due
                    </h3>
                    <p class="text-sm mt-0.5" style="color: var(--db-ink-soft);">
                        Leads where a staff reminder was sent but no new activity was recorded afterward.
                    </p>
                </div>

                @if ($escalationsDue->count() > 0)

                    <div class="overflow-x-auto">
                        <table class="db-table w-full min-w-[640px]">
                            <thead>
                                <tr>
                                    <th>Lead</th>
                                    <th>Assigned Staff</th>
                                    <th>Reminder Sent</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($escalationsDue as $lead)
                                    <tr>
                                        <td>
                                            <p class="font-medium" style="color: var(--db-ink);">{{ $lead->name }}</p>
                                            <p class="text-xs mt-0.5" style="color: var(--db-ink-faint);">{{ $lead->product_service }}</p>
                                        </td>
                                        <td style="color: var(--db-ink-soft);">
                                            {{ $lead->assignedStaff->name ?? 'Unassigned' }}
                                        </td>
                                        <td style="color: var(--db-ink-soft);">
                                            {{ $lead->follow_up_reminder_sent_at
                                                ? $lead->follow_up_reminder_sent_at->format('d M Y, h:i A')
                                                : 'Not recorded' }}
                                        </td>
                                        <td>
                                            <form action="{{ route('follow-ups.escalate', $lead) }}" method="POST">
                                                @csrf
                                                <button
                                                    type="submit"
                                                    class="db-btn-danger"
                                                    onclick="return confirm('Send an escalation notification to the admin?')"
                                                >
                                                    Send Escalation
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                @else

                    <div class="text-center py-12" style="color: var(--db-ink-soft);">
                        No admin escalations are currently due.
                    </div>

                @endif
            </div>

        </div>

    </div>

</x-app-layout>