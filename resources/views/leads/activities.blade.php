<x-app-layout>

    <div class="db-root py-8 sm:py-10 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Page Header --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="db-heading text-2xl font-semibold" style="color: var(--db-ink);">
                        Lead Activities
                    </h2>
                    <p class="mt-1 text-sm" style="color: var(--db-ink-soft);">
                        Monitor all activity recorded for your leads.
                    </p>
                </div>

                <a href="{{ route('leads.index') }}" class="db-btn-primary self-start sm:self-auto">
                    View All Leads
                </a>
            </div>

            {{-- Filters --}}
            <div class="db-card">
                <div class="p-5 sm:p-6">

                    <h3 class="db-heading text-lg font-semibold mb-5" style="color: var(--db-ink);">
                        Filter Activities
                    </h3>

                    <form method="GET" action="{{ route('leads.activities.index') }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

                            <!-- Search -->
                            <div>
                                <label class="db-label">Search Lead</label>
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Lead name..."
                                    class="db-input"
                                >
                            </div>

                            <!-- Staff -->
                            <div>
                                <label class="db-label">Staff</label>
                                <select name="user_id" class="db-input">
                                    <option value="">All Staff</option>
                                    @foreach ($staff as $member)
                                        <option
                                            value="{{ $member->id }}"
                                            {{ request('user_id') == $member->id ? 'selected' : '' }}
                                        >
                                            {{ $member->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Activity Type -->
                            <div>
                                <label class="db-label">Activity Type</label>
                                <select name="activity_type" class="db-input">
                                    <option value="">All Activities</option>
                                    @foreach ($activityTypes as $type)
                                        <option
                                            value="{{ $type }}"
                                            {{ request('activity_type') == $type ? 'selected' : '' }}
                                        >
                                            {{ ucwords(str_replace('_', ' ', $type)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Lead Status -->
                            <div>
                                <label class="db-label">Lead Status</label>
                                <select name="status" class="db-input">
                                    <option value="">All Statuses</option>
                                    @foreach ($statuses as $status)
                                        <option
                                            value="{{ $status }}"
                                            {{ request('status') == $status ? 'selected' : '' }}
                                        >
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date -->
                            <div>
                                <label class="db-label">Date</label>
                                <input
                                    type="date"
                                    name="date"
                                    value="{{ request('date') }}"
                                    class="db-input"
                                >
                            </div>

                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3 mt-5">
                            <button type="submit" class="db-btn-primary">
                                Apply Filters
                            </button>

                            <a href="{{ route('leads.activities.index') }}" class="db-btn-secondary">
                                Clear Filters
                            </a>
                        </div>

                    </form>

                </div>
            </div>

            {{-- Activity List --}}
            <div>

                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="db-heading text-lg font-semibold" style="color: var(--db-ink);">
                            Activity History
                        </h3>
                        <p class="text-sm mt-0.5" style="color: var(--db-ink-soft);">
                            All recorded interactions and lead updates.
                        </p>
                    </div>

                    <span class="db-count-pill db-count-pill--accent">
                        {{ $activities->total() }} Activities
                    </span>
                </div>

                @if ($activities->count() > 0)

                    <div class="space-y-3">
                        @foreach ($activities as $activity)
                            <div class="db-card p-4 sm:p-5">

                                <div class="flex flex-wrap items-start justify-between gap-3">

                                    <div class="min-w-0">
                                        @if ($activity->lead)
                                            <a href="{{ route('leads.show', $activity->lead) }}"
                                               class="font-semibold" style="color: var(--db-ink);">
                                                {{ $activity->lead->name }}
                                            </a>
                                            <p class="text-xs mt-0.5" style="color: var(--db-ink-faint);">
                                                {{ $activity->lead->status }}
                                            </p>
                                        @else
                                            <span style="color: var(--db-ink-faint);">
                                                Lead deleted
                                            </span>
                                        @endif
                                    </div>

                                    <div class="text-right shrink-0">
                                        <p class="text-sm font-medium" style="color: var(--db-ink);">
                                            {{ $activity->created_at->format('d M Y') }}
                                        </p>
                                        <p class="text-xs" style="color: var(--db-ink-faint);">
                                            {{ $activity->created_at->format('h:i A') }}
                                        </p>
                                    </div>

                                </div>

                                <div class="flex flex-wrap items-center gap-3 mt-3">
                                    <span class="db-badge-neutral">
                                        {{ ucwords(str_replace('_', ' ', $activity->activity_type)) }}
                                    </span>

                                    @if ($activity->communication_method)
                                        <span class="text-xs" style="color: var(--db-ink-soft);">
                                            via {{ $activity->communication_method }}
                                        </span>
                                    @endif

                                    <span class="text-xs ml-auto" style="color: var(--db-ink-faint);">
                                        @if ($activity->user)
                                            {{ $activity->user->name }}
                                        @else
                                            System
                                        @endif
                                    </span>
                                </div>

                                @if ($activity->notes)
                                    <p class="text-sm mt-3 pt-3" style="color: var(--db-ink-soft); border-top: 1px solid var(--db-border-soft);">
                                        {{ $activity->notes }}
                                    </p>
                                @endif

                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $activities->links() }}
                    </div>

                @else

                    <div class="db-card text-center py-12" style="color: var(--db-ink-soft);">
                        <p class="font-medium" style="color: var(--db-ink);">
                            No activities found.
                        </p>
                        <p class="text-sm mt-1">
                            Try changing your filters or search criteria.
                        </p>
                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>