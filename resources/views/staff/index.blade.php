<x-app-layout>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <!-- Header -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">

                        <div>
                            <h2 class="text-2xl font-bold">
                                Staff Management
                            </h2>
                            <a
                                href="{{ route('staff.create') }}"
                                style="background-color: #2563eb; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 600;"
                            >
                                + Add New Staff
                            </a>

                            <p class="text-gray-600">
                                View and manage staff members.
                            </p>
                        </div>

                    </div>

                    @if ($staff->count() > 0)

                        <div style="overflow-x: auto;">

                            <table style="width: 100%; border-collapse: collapse;">

                                <thead>

                                    <tr style="background-color: #f3f4f6;">

                                        <th style="padding: 12px; text-align: left;">
                                            Staff Name
                                        </th>

                                        <th style="padding: 12px; text-align: left;">
                                            Email Address
                                        </th>

                                        <th style="padding: 12px; text-align: left;">
                                            Role
                                        </th>

                                        <th style="padding: 12px; text-align: left;">
                                            Assigned Leads
                                        </th>

                                        <th style="padding: 12px; text-align: left;">
                                            Action
                                        </th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($staff as $member)

                                        <tr style="border-bottom: 1px solid #e5e7eb;">

                                            <td style="padding: 12px;">
                                                {{ $member->name }}
                                            </td>

                                            <td style="padding: 12px;">
                                                {{ $member->email }}
                                            </td>

                                            <td style="padding: 12px;">
                                                {{ ucfirst($member->role) }}
                                            </td>

                                            <td style="padding: 12px;">
                                                {{ $member->assigned_leads_count }}
                                            </td>

                                            <td style="padding: 12px;">

                                                <a
                                                    href="{{ route('staff.show', $member) }}"
                                                    style="background-color: #2563eb; color: white; padding: 6px 12px; border-radius: 5px; text-decoration: none;"
                                                >
                                                    View
                                                </a>

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div style="padding: 30px; text-align: center; color: #6b7280;">
                            No staff members have been registered yet.
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>