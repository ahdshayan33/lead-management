
<x-app-layout>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <!-- Header -->

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">

                        <div>

                            <h2 class="text-2xl font-bold">
                                Lead Management
                            </h2>

                            <p class="text-gray-600">
                                View and manage registered leads.
                            </p>

                        </div>


                        <!-- Only Admin can register new leads -->

                        @if (auth()->user()->role === 'admin')

                            <a
                                href="{{ route('leads.create') }}"
                                style="background-color: #2563eb; color: white; padding: 10px 18px; border-radius: 6px; text-decoration: none; font-weight: 600;"
                            >
                                + Register New Lead
                            </a>

                        @endif

                    </div>


                    <!-- Search & Filters -->

                    <form
                        method="GET"
                        action="{{ route('leads.index') }}"
                        style="margin-bottom: 25px;"
                    >

                        <div
                            style="
                                display: grid;
                                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                                gap: 12px;
                                align-items: end;
                            "
                        >

                            <!-- Search -->

                            <div>

                                <label
                                    style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px;"
                                >
                                    Search
                                </label>

                                <input
                                    type="text"
                                    name="search"
                                    value="{{ request('search') }}"
                                    placeholder="Name, email or phone"
                                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                >

                            </div>


                            <!-- Status -->

                            <div>

                                <label
                                    style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px;"
                                >
                                    Status
                                </label>

                                <select
                                    name="status"
                                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                >

                                    <option value="">
                                        All Statuses
                                    </option>

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

                                <label
                                    style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px;"
                                >
                                    Priority
                                </label>

                                <select
                                    name="priority"
                                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                >

                                    <option value="">
                                        All Priorities
                                    </option>

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

                                    <label
                                        style="display: block; font-size: 14px; font-weight: 600; margin-bottom: 6px;"
                                    >
                                        Assigned Staff
                                    </label>

                                    <select
                                        name="assigned_to"
                                        style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                    >

                                        <option value="">
                                            All Staff
                                        </option>

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


                            <!-- Filter Button -->

                            <div>

                                <button
                                    type="submit"
                                    style="
                                        background-color: #2563eb;
                                        color: white;
                                        padding: 10px 18px;
                                        border: none;
                                        border-radius: 6px;
                                        font-weight: 600;
                                        cursor: pointer;
                                        width: 100%;
                                    "
                                >
                                    Filter
                                </button>

                            </div>


                            <!-- Clear Button -->

                            <div>

                                <a
                                    href="{{ route('leads.index') }}"
                                    style="
                                        display: block;
                                        background-color: #6b7280;
                                        color: white;
                                        padding: 10px 18px;
                                        border-radius: 6px;
                                        text-decoration: none;
                                        font-weight: 600;
                                        text-align: center;
                                    "
                                >
                                    Clear
                                </a>

                            </div>

                        </div>

                    </form>


                    <!-- Lead Count -->

                    <div style="margin-bottom: 15px; color: #6b7280; font-size: 14px;">

                        Showing
                        <strong>{{ $leads->count() }}</strong>
                        lead(s)

                    </div>


                    <!-- Leads Table -->

                    @if ($leads->count() > 0)

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
                                            Communication
                                        </th>

                                        <th style="padding: 12px; text-align: left;">
                                            Status
                                        </th>

                                        <th style="padding: 12px; text-align: left;">
                                            Priority
                                        </th>

                                        <th style="padding: 12px; text-align: left;">
                                            Follow-up
                                        </th>

                                        <th style="padding: 12px; text-align: left;">
                                            Assigned Staff
                                        </th>

                                        <th style="padding: 12px; text-align: left;">
                                            Date
                                        </th>

                                        <th style="padding: 12px; text-align: left;">
                                            Action
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach ($leads as $lead)

                                        <tr style="border-bottom: 1px solid #e5e7eb;">

                                            <!-- Name -->

                                            <td style="padding: 12px;">

                                                <strong>
                                                    {{ $lead->name }}
                                                </strong>

                                            </td>


                                            <!-- Product / Service -->

                                            <td style="padding: 12px;">
                                                {{ $lead->product_service }}
                                            </td>


                                            <!-- Communication -->

                                            <td style="padding: 12px;">
                                                {{ ucfirst($lead->communication_method) }}
                                            </td>


                                            <!-- Status -->

                                            <td style="padding: 12px;">
                                                {{ $lead->status }}
                                            </td>


                                            <!-- Priority -->

                                            <td style="padding: 12px;">

                                                {{ $lead->priority ?? 'Medium' }}

                                            </td>


                                            <!-- Follow-up Date -->

                                            <td style="padding: 12px;">

                                                @if ($lead->follow_up_date)

                                                    {{ \Carbon\Carbon::parse($lead->follow_up_date)->format('d M Y') }}

                                                @else

                                                    <span style="color: #9ca3af;">
                                                        Not set
                                                    </span>

                                                @endif

                                            </td>


                                            <!-- Assigned Staff -->

                                            <td style="padding: 12px;">

                                                {{ $lead->assignedStaff?->name ?? 'Unassigned' }}

                                            </td>


                                            <!-- Created Date -->

                                            <td style="padding: 12px;">

                                                {{ $lead->created_at->format('d M Y') }}

                                            </td>


                                            <!-- View -->

                                            <td style="padding: 12px;">

                                                <a
                                                    href="{{ route('leads.show', $lead) }}"
                                                    style="
                                                        background-color: #2563eb;
                                                        color: white;
                                                        padding: 6px 12px;
                                                        border-radius: 5px;
                                                        text-decoration: none;
                                                    "
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

                        <div
                            style="
                                padding: 30px;
                                text-align: center;
                                color: #6b7280;
                            "
                        >

                            <p style="font-weight: 600; margin-bottom: 5px;">
                                No leads found.
                            </p>

                            <p style="font-size: 14px;">
                                Try changing your search or filters.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
```
