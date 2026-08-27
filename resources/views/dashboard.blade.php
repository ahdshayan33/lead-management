<x-app-layout>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Page Header -->
            <div class="mb-8">

                <h2 class="text-3xl font-bold text-gray-800">
                    Dashboard
                </h2>

                <p class="text-gray-600 mt-1">
                    Welcome back, {{ auth()->user()->name }}.
                </p>

            </div>


            <!-- Statistics -->

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">


                <!-- Total Leads -->

                <div class="bg-white shadow-sm rounded-lg p-6">

                    <p class="text-sm font-medium text-gray-500">
                        Total Leads
                    </p>

                    <p class="text-3xl font-bold text-gray-800 mt-2">
                        {{ $totalLeads }}
                    </p>

                </div>


                <!-- New Leads -->

                <div class="bg-white shadow-sm rounded-lg p-6">

                    <p class="text-sm font-medium text-gray-500">
                        New Leads
                    </p>

                    <p class="text-3xl font-bold text-blue-600 mt-2">
                        {{ $newLeads }}
                    </p>

                </div>


                <!-- Contacted -->

                <div class="bg-white shadow-sm rounded-lg p-6">

                    <p class="text-sm font-medium text-gray-500">
                        Contacted
                    </p>

                    <p class="text-3xl font-bold text-indigo-600 mt-2">
                        {{ $contactedLeads }}
                    </p>

                </div>


                <!-- Follow-up -->

                <div class="bg-white shadow-sm rounded-lg p-6">

                    <p class="text-sm font-medium text-gray-500">
                        Follow-up Required
                    </p>

                    <p class="text-3xl font-bold text-orange-500 mt-2">
                        {{ $followUpLeads }}
                    </p>

                </div>


                <!-- Converted -->

                <div class="bg-white shadow-sm rounded-lg p-6">

                    <p class="text-sm font-medium text-gray-500">
                        Converted
                    </p>

                    <p class="text-3xl font-bold text-green-600 mt-2">
                        {{ $convertedLeads }}
                    </p>

                </div>


                <!-- Lost -->

                <div class="bg-white shadow-sm rounded-lg p-6">

                    <p class="text-sm font-medium text-gray-500">
                        Lost
                    </p>

                    <p class="text-3xl font-bold text-red-600 mt-2">
                        {{ $lostLeads }}
                    </p>

                </div>


                <!-- High Priority -->

                <div class="bg-white shadow-sm rounded-lg p-6">

                    <p class="text-sm font-medium text-gray-500">
                        High Priority
                    </p>

                    <p class="text-3xl font-bold text-red-500 mt-2">
                        {{ $highPriorityLeads }}
                    </p>

                </div>


                <!-- Conversion Rate -->

                <div class="bg-white shadow-sm rounded-lg p-6">

                    <p class="text-sm font-medium text-gray-500">
                        Conversion Rate
                    </p>

                    <p class="text-3xl font-bold text-purple-600 mt-2">

                        @if ($totalLeads > 0)

                            {{ round(($convertedLeads / $totalLeads) * 100, 1) }}%

                        @else

                            0%

                        @endif

                    </p>

                </div>

            </div>


            <!-- Recent Leads -->

            <div class="bg-white shadow-sm rounded-lg mt-8">

                <div class="p-6">

                    <div class="flex justify-between items-center mb-6">

                        <div>

                            <h3 class="text-xl font-bold text-gray-800">
                                Recent Leads
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Your most recently registered leads.
                            </p>

                        </div>

                        <a
                            href="{{ route('leads.index') }}"
                            style="background-color: #2563eb; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none;"
                        >
                            View All Leads
                        </a>

                    </div>


                    @if ($recentLeads->count() > 0)

                        <div class="overflow-x-auto">

                            <table class="w-full">

                                <thead>

                                    <tr style="border-bottom: 1px solid #e5e7eb;">

                                        <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">
                                            Lead Name
                                        </th>

                                        <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">
                                            Product / Service
                                        </th>

                                        <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">
                                            Status
                                        </th>

                                        <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">
                                            Priority
                                        </th>

                                        <th class="text-left py-3 px-2 text-sm font-semibold text-gray-600">
                                            Assigned Staff
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach ($recentLeads as $lead)

                                        <tr style="border-bottom: 1px solid #f3f4f6;">

                                            <td class="py-4 px-2">

                                                <a
                                                    href="{{ route('leads.show', $lead) }}"
                                                    class="font-medium text-blue-600 hover:underline"
                                                >
                                                    {{ $lead->name }}
                                                </a>

                                            </td>


                                            <td class="py-4 px-2 text-gray-700">
                                                {{ $lead->product_service }}
                                            </td>


                                            <td class="py-4 px-2">

                                                <span
                                                    style="background-color: #eff6ff; color: #1d4ed8; padding: 5px 10px; border-radius: 999px; font-size: 13px;"
                                                >
                                                    {{ $lead->status }}
                                                </span>

                                            </td>


                                            <td class="py-4 px-2">

                                                {{ $lead->priority ?? 'Medium' }}

                                            </td>


                                            <td class="py-4 px-2 text-gray-700">

                                                {{ $lead->assignedStaff->name ?? 'Unassigned' }}

                                            </td>

                                        </tr>

                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    @else

                        <div class="text-center py-10 text-gray-500">

                            No leads found.

                        </div>

                    @endif

                </div>

            </div>

            <!-- Follow-up Management -->

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">


                <!-- Overdue Follow-ups -->

                <div class="bg-white shadow-sm rounded-lg">

                    <div class="p-6">

                        <div class="flex items-center justify-between mb-6">

                            <div>

                                <h3 class="text-xl font-bold text-gray-800">
                                    Overdue Follow-ups
                                </h3>

                                <p class="text-sm text-gray-500 mt-1">
                                    Leads that need your attention.
                                </p>

                            </div>

                            <span
                                style="background-color: #fee2e2; color: #b91c1c; padding: 6px 12px; border-radius: 999px; font-size: 13px; font-weight: 600;"
                            >
                                {{ $overdueFollowUps->count() }}
                            </span>

                        </div>


                        @if ($overdueFollowUps->count() > 0)

                            <div class="space-y-4">

                                @foreach ($overdueFollowUps as $lead)

                                    <div
                                        style="border-left: 4px solid #dc2626; background-color: #fef2f2; padding: 15px; border-radius: 6px;"
                                    >

                                        <div class="flex justify-between items-start">

                                            <div>

                                                <a
                                                    href="{{ route('leads.show', $lead) }}"
                                                    class="font-semibold text-red-700 hover:underline"
                                                >
                                                    {{ $lead->name }}
                                                </a>

                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ $lead->product_service }}
                                                </p>

                                            </div>


                                            <span class="text-sm font-semibold text-red-600">

                                                {{ \Carbon\Carbon::parse($lead->follow_up_date)->format('d M Y') }}

                                            </span>

                                        </div>


                                        <div class="flex justify-between items-center mt-3">

                                            <div class="flex items-center gap-4">

                                                <span class="text-xs text-gray-500">
                                                    {{ $lead->status }}
                                                </span>

                                                @if (auth()->user()->role === 'admin')

                                                    <span class="text-xs text-gray-600">
                                                        Assigned to:
                                                        <span class="font-semibold">
                                                            {{ $lead->assignedStaff->name ?? 'Unassigned' }}
                                                        </span>
                                                    </span>

                                                @endif

                                            </div>


                                            <span class="text-xs font-semibold text-red-600">
                                                Overdue
                                            </span>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @else

                            <div class="text-center py-8 text-gray-500">

                                <p class="font-medium">
                                    No overdue follow-ups 🎉
                                </p>

                                <p class="text-sm mt-1">
                                    You're all caught up.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>



                <!-- Upcoming Follow-ups -->

                <div class="bg-white shadow-sm rounded-lg">

                    <div class="p-6">

                        <div class="flex items-center justify-between mb-6">

                            <div>

                                <h3 class="text-xl font-bold text-gray-800">
                                    Upcoming Follow-ups
                                </h3>

                                <p class="text-sm text-gray-500 mt-1">
                                    Your upcoming follow-up dates.
                                </p>

                            </div>

                            <span
                                style="background-color: #dbeafe; color: #1d4ed8; padding: 6px 12px; border-radius: 999px; font-size: 13px; font-weight: 600;"
                            >
                                {{ $upcomingFollowUps->count() }}
                            </span>

                        </div>


                        @if ($upcomingFollowUps->count() > 0)

                            <div class="space-y-4">

                                @foreach ($upcomingFollowUps as $lead)

                                    <div
                                        style="border-left: 4px solid #2563eb; background-color: #eff6ff; padding: 15px; border-radius: 6px;"
                                    >

                                        <div class="flex justify-between items-start">

                                            <div>

                                                <a
                                                    href="{{ route('leads.show', $lead) }}"
                                                    class="font-semibold text-blue-700 hover:underline"
                                                >
                                                    {{ $lead->name }}
                                                </a>

                                                <p class="text-sm text-gray-600 mt-1">
                                                    {{ $lead->product_service }}
                                                </p>

                                            </div>


                                            <span class="text-sm font-semibold text-blue-600">

                                                {{ \Carbon\Carbon::parse($lead->follow_up_date)->format('d M Y') }}

                                            </span>

                                        </div>


                                        <div class="flex justify-between items-center mt-3">

                                            <span class="text-xs text-gray-500">

                                                {{ $lead->status }}

                                            </span>


                                            <span class="text-xs font-semibold text-blue-600">

                                                {{ \Carbon\Carbon::parse($lead->follow_up_date)->diffForHumans() }}

                                            </span>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        @else

                            <div class="text-center py-8 text-gray-500">

                                <p class="font-medium">
                                    No upcoming follow-ups.
                                </p>

                                <p class="text-sm mt-1">
                                    There are no scheduled follow-ups.
                                </p>

                            </div>

                        @endif

                    </div>

                </div>

            </div>




        </div>

    </div>

</x-app-layout>