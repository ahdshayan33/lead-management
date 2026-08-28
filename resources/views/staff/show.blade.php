<x-app-layout>

    <div class="py-12">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <!-- Header -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">

                        <div>
                            <h2 class="text-2xl font-bold">
                                Staff Details
                            </h2>

                            <p style="color: #6b7280;">
                                View staff member information and assigned leads.
                            </p>
                        </div>

                        <a
                            href="{{ route('staff.index') }}"
                            style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none;"
                        >
                            Back to Staff
                        </a>

                        <a
                            href="{{ route('staff.edit', $user) }}"
                            style="background-color: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; margin-left: 10px;"
                        >
                            Edit Staff
                        </a>

                    </div>


                    <!-- Staff Information -->
                    <div
                        style="background-color: #f9fafb; padding: 20px; border-radius: 8px; margin-bottom: 30px;"
                    >

                        <h3 class="text-lg font-bold mb-4">
                            Staff Information
                        </h3>

                        <div style="display: grid; gap: 15px;">

                            <div>
                                <strong>Name</strong>
                                <p>{{ $user->name }}</p>
                            </div>

                            <div>
                                <strong>Email Address</strong>
                                <p>{{ $user->email }}</p>
                            </div>

                            <div>
                                <strong>Role</strong>
                                <p>{{ ucfirst($user->role) }}</p>
                            </div>

                            <div>
                                <strong>Assigned Leads</strong>
                                <p>{{ $user->assigned_leads_count }}</p>
                            </div>

                            <div>
                                <strong>Account Created</strong>
                                <p>
                                    {{ $user->created_at->format('d M Y, h:i A') }}
                                </p>
                            </div>

                        </div>

                    </div>


                    <!-- Assigned Leads -->
                    <div>

                        <h3 class="text-lg font-bold mb-4">
                            Assigned Leads
                        </h3>

                        @if ($assignedLeads->count() > 0)

                            <div style="overflow-x: auto;">

                                <table style="width: 100%; border-collapse: collapse;">

                                    <thead>

                                        <tr style="background-color: #f3f4f6;">

                                            <th style="padding: 12px; text-align: left;">
                                                Lead Name
                                            </th>

                                            <th style="padding: 12px; text-align: left;">
                                                Product / Service
                                            </th>

                                            <th style="padding: 12px; text-align: left;">
                                                Status
                                            </th>

                                            <th style="padding: 12px; text-align: left;">
                                                Priority
                                            </th>

                                            <th style="padding: 12px; text-align: left;">
                                                Follow-up Date
                                            </th>

                                            <th style="padding: 12px; text-align: left;">
                                                Action
                                            </th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        @foreach ($assignedLeads as $lead)

                                            <tr style="border-bottom: 1px solid #e5e7eb;">

                                                <td style="padding: 12px;">
                                                    {{ $lead->name }}
                                                </td>

                                                <td style="padding: 12px;">
                                                    {{ $lead->product_service }}
                                                </td>

                                                <td style="padding: 12px;">
                                                    {{ $lead->status }}
                                                </td>

                                                <td style="padding: 12px;">
                                                    {{ $lead->priority ?? 'Medium' }}
                                                </td>

                                                <td style="padding: 12px;">
                                                    {{ $lead->follow_up_date
                                                        ? \Carbon\Carbon::parse($lead->follow_up_date)->format('d M Y')
                                                        : 'Not scheduled'
                                                    }}
                                                </td>

                                                <td style="padding: 12px;">

                                                    <a
                                                        href="{{ route('leads.show', $lead) }}"
                                                        style="background-color: #2563eb; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none;"
                                                    >
                                                        View Lead
                                                    </a>

                                                </td>

                                            </tr>

                                        @endforeach

                                    </tbody>

                                </table>

                            </div>

                        @else

                            <div
                                style="padding: 30px; text-align: center; color: #6b7280;"
                            >
                                No leads are currently assigned to this staff member.
                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>