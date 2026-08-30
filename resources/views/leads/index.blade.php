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

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Page Header --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="db-heading text-2xl font-semibold" style="color: var(--db-ink);">
                        Lead Management
                    </h2>
                    <p class="mt-1 text-sm" style="color: var(--db-ink-soft);">
                        View and manage registered leads.
                    </p>
                </div>

                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('leads.create') }}" class="db-btn-primary self-start sm:self-auto">
                        + Register New Lead
                    </a>
                @endif
            </div>

            {{-- Search & Filters --}}
            <div class="db-card">
                <div class="p-5 sm:p-6">

                    <h3 class="db-heading text-lg font-semibold mb-5" style="color: var(--db-ink);">
                        Filter Leads
                    </h3>

                    <form method="GET" action="{{ route('leads.index') }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                            <!-- Search -->
                            <div>
                                <label class="db-label">Search</label>
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Name, email or phone"
                                    class="db-input"
                                >
                            </div>

                            <!-- Status -->
                            <div>
                                <label class="db-label">Status</label>
                                <select name="status" class="db-input">
                                    <option value="">All Statuses</option>
                                    @foreach ([
                                        'New Lead',
                                        'Contacted',
                                        'Interested',
                                        'Follow-up Required',
                                        'Quotation Sent',
                                        'Converted',
                                        'Lost'
                                    ] as $status)
                                        <option
                                            value="{{ $status }}"
                                            {{ request('status') === $status ? 'selected' : '' }}
                                        >
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Priority -->
                            <div>
                                <label class="db-label">Priority</label>
                                <select name="priority" class="db-input">
                                    <option value="">All Priorities</option>
                                    @foreach (['Low', 'Medium', 'High'] as $priority)
                                        <option
                                            value="{{ $priority }}"
                                            {{ request('priority') === $priority ? 'selected' : '' }}
                                        >
                                            {{ $priority }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Assigned Staff - Admin Only -->
                            @if (auth()->user()->role === 'admin')
                                <div>
                                    <label class="db-label">Assigned Staff</label>
                                    <select name="assigned_to" class="db-input">
                                        <option value="">All Staff</option>
                                        @foreach ($staff as $member)
                                            <option
                                                value="{{ $member->id }}"
                                                {{ request('assigned_to') == $member->id ? 'selected' : '' }}
                                            >
                                                {{ $member->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif

                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3 mt-5">
                            <button type="submit" class="db-btn-primary">
                                Filter
                            </button>

                            <a href="{{ route('leads.index') }}" class="db-btn-secondary">
                                Clear
                            </a>
                        </div>

                    </form>

                </div>
            </div>

            {{-- Lead Count --}}
            <p class="text-sm" style="color: var(--db-ink-soft);">
                Showing <strong style="color: var(--db-ink);">{{ $leads->count() }}</strong> lead(s)
            </p>

            {{-- Leads Table --}}
            @if ($leads->count() > 0)

                <div class="db-card">
                    <div class="overflow-x-auto">
                        <table class="db-table w-full min-w-[900px]">
                            <thead>
                                <tr>
                                    <th>Lead Name</th>
                                    <th>Product / Service</th>
                                    <th>Communication</th>
                                    <th>Status</th>
                                    <th>Priority</th>
                                    <th>Follow-up</th>
                                    <th>Assigned Staff</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leads as $lead)
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
                                        <td style="color: var(--db-ink-soft);">
                                            {{ ucfirst($lead->communication_method) }}
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
                                                <span style="color: var(--db-ink-faint);">Not set</span>
                                            @endif
                                        </td>
                                        <td style="color: var(--db-ink-soft);">
                                            {{ $lead->assignedStaff?->name ?? 'Unassigned' }}
                                        </td>
                                        <td style="color: var(--db-ink-soft);">
                                            {{ $lead->created_at->format('d M Y') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('leads.show', $lead) }}" class="db-link-accent">
                                                View
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
                    <p class="font-medium" style="color: var(--db-ink);">No leads found.</p>
                    <p class="text-sm mt-1">Try changing your search or filters.</p>
                </div>

            @endif

        </div>

    </div>

</x-app-layout>