<x-app-layout>

    <div class="db-root py-8 sm:py-10 min-h-screen">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Page Header --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="db-heading text-2xl font-semibold" style="color: var(--db-ink);">
                        Staff Management
                    </h2>
                    <p class="mt-1 text-sm" style="color: var(--db-ink-soft);">
                        View and manage staff members.
                    </p>
                </div>

                <a href="{{ route('staff.create') }}" class="db-btn-primary self-start sm:self-auto">
                    + Add New Staff
                </a>
            </div>

            {{-- Staff Table --}}
            @if ($staff->count() > 0)

                <div class="db-card">
                    <div class="overflow-x-auto">
                        <table class="db-table w-full min-w-[640px]">
                            <thead>
                                <tr>
                                    <th>Staff Name</th>
                                    <th>Email Address</th>
                                    <th>Role</th>
                                    <th>Assigned Leads</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staff as $member)
                                    <tr>
                                        <td class="font-medium" style="color: var(--db-ink);">
                                            {{ $member->name }}
                                        </td>
                                        <td style="color: var(--db-ink-soft);">
                                            {{ $member->email }}
                                        </td>
                                        <td style="color: var(--db-ink-soft);">
                                            {{ ucfirst($member->role) }}
                                        </td>
                                        <td style="color: var(--db-ink-soft);">
                                            {{ $member->assigned_leads_count }}
                                        </td>
                                        <td>
                                            <a href="{{ route('staff.show', $member) }}" class="db-link-accent">
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
                    No staff members have been registered yet.
                </div>

            @endif

        </div>

    </div>

</x-app-layout>