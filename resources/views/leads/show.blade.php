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

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Page Header --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="db-heading text-2xl font-semibold" style="color: var(--db-ink);">
                        Lead Details
                    </h2>
                    <p class="mt-1 text-sm" style="color: var(--db-ink-soft);">
                        View information about this lead.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('leads.index') }}" class="db-btn-secondary">
                        Back to Leads
                    </a>

                    @if ($lead->status !== 'Converted')
                        <a href="{{ route('leads.edit', $lead) }}" class="db-btn-primary">
                            Edit Lead
                        </a>
                    @endif
                </div>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="db-tint-block db-tint-block--success db-text-success text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Lead Information --}}
            <div class="db-card">
                <div class="p-5 sm:p-6">

                    <h3 class="db-heading text-base font-semibold mb-5" style="color: var(--db-ink);">
                        Lead Information
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div>
                            <p class="db-metric-label">Lead Name</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">{{ $lead->name }}</p>
                        </div>

                        <div>
                            <p class="db-metric-label">Email</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">{{ $lead->email ?? 'Not provided' }}</p>
                        </div>

                        <div>
                            <p class="db-metric-label">Phone</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">{{ $lead->phone }}</p>
                        </div>

                        <div>
                            <p class="db-metric-label">Product / Service</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">{{ $lead->product_service }}</p>
                        </div>

                        <div>
                            <p class="db-metric-label">Lead Source</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">{{ $lead->lead_source ?? 'Not provided' }}</p>
                        </div>

                        <div>
                            <p class="db-metric-label">Communication Method</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">{{ ucfirst($lead->communication_method) }}</p>
                        </div>

                        <div>
                            <p class="db-metric-label">Status</p>
                            <p class="text-sm mt-1 inline-flex items-center gap-1.5" style="color: var(--db-ink);">
                                <span class="db-dot {{ $statusDot[strtolower($lead->status)] ?? 'db-dot--contacted' }}"></span>
                                {{ $lead->status }}
                            </p>
                        </div>

                        <div>
                            <p class="db-metric-label">Priority</p>
                            <p class="text-sm mt-1 {{ $priorityClass[strtolower($lead->priority ?? 'medium')] ?? $priorityClass['medium'] }}">
                                {{ $lead->priority ?? 'Medium' }}
                            </p>
                        </div>

                        <div>
                            <p class="db-metric-label">Follow-up Date</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">
                                {{ $lead->follow_up_date
                                    ? \Carbon\Carbon::parse($lead->follow_up_date)->format('d F Y')
                                    : 'Not scheduled' }}
                            </p>
                        </div>

                        <div>
                            <p class="db-metric-label">Assigned Staff</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">{{ $lead->assignedStaff?->name ?? 'Unassigned' }}</p>
                        </div>

                        <div class="sm:col-span-2">
                            <p class="db-metric-label">Requirements / Notes</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">{{ $lead->requirements ?? 'No additional requirements.' }}</p>
                        </div>

                        <div>
                            <p class="db-metric-label">Registered Date</p>
                            <p class="text-sm mt-1" style="color: var(--db-ink);">{{ $lead->created_at->format('d M Y, h:i A') }}</p>
                        </div>

                    </div>

                </div>
            </div>

            {{-- Add Activity --}}
            <div class="db-card">
                <div class="p-5 sm:p-6">

                    <h3 class="db-heading text-base font-semibold" style="color: var(--db-ink);">
                        Add Activity
                    </h3>
                    <p class="text-sm mt-0.5 mb-5" style="color: var(--db-ink-soft);">
                        Record a call, WhatsApp message, email, meeting, or other interaction with this lead.
                    </p>

                    @if ($errors->any())
                        <div class="db-tint-block db-tint-block--danger db-text-danger text-sm mb-5">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('leads.activities.store', $lead) }}">
                        @csrf

                        <div class="mb-4">
                            <label class="db-label">Communication Method</label>
                            <select name="communication_method" required class="db-input">
                                <option value="">Select method</option>
                                <option value="Call">Call</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Email">Email</option>
                                <option value="Meeting">Meeting</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="db-label">Activity Notes</label>
                            <textarea
                                name="notes"
                                rows="4"
                                required
                                placeholder="Describe what happened during the interaction..."
                                class="db-input"
                            >{{ old('notes') }}</textarea>
                        </div>

                        <div class="mb-5">
                            <label class="db-label">Next Follow-up Date</label>
                            <input type="date" name="follow_up_date" value="{{ old('follow_up_date') }}" class="db-input">
                        </div>

                        <button type="submit" class="db-btn-primary">
                            Add Activity
                        </button>
                    </form>

                </div>
            </div>

            {{-- Activity History --}}
            <div>

                <div class="mb-4">
                    <h3 class="db-heading text-lg font-semibold" style="color: var(--db-ink);">
                        Activity History
                    </h3>
                    <p class="text-sm mt-0.5" style="color: var(--db-ink-soft);">
                        Previous interactions with this lead.
                    </p>
                </div>

                @if ($lead->activities->count() > 0)

                    <div class="space-y-3">
                        @foreach ($lead->activities as $activity)
                            <div class="db-card p-4 sm:p-5">

                                <div class="flex flex-wrap items-start justify-between gap-3">
                                    <span class="db-badge-neutral">
                                        {{ $activity->communication_method }}
                                    </span>

                                    <span class="text-xs" style="color: var(--db-ink-faint);">
                                        {{ $activity->created_at->format('d M Y, h:i A') }}
                                    </span>
                                </div>

                                <p class="text-sm mt-3" style="color: var(--db-ink);">
                                    {{ $activity->notes }}
                                </p>

                                <div class="flex flex-wrap items-center gap-3 mt-3 pt-3 text-xs" style="color: var(--db-ink-faint); border-top: 1px solid var(--db-border-soft);">
                                    <span>Recorded by: {{ $activity->user?->name ?? 'Unknown' }}</span>

                                    @if ($activity->follow_up_date)
                                        <span>&bull;</span>
                                        <span>
                                            Next Follow-up:
                                            {{ \Carbon\Carbon::parse($activity->follow_up_date)->format('d F Y') }}
                                        </span>
                                    @endif
                                </div>

                            </div>
                        @endforeach
                    </div>

                @else

                    <div class="db-card text-center py-12" style="color: var(--db-ink-soft);">
                        No activities have been recorded for this lead yet.
                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>