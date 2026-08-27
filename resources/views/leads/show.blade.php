```blade
<x-app-layout>

    <div class="py-12">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Lead Details -->

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">

                        <div>

                            <h2 class="text-2xl font-bold">
                                Lead Details
                            </h2>

                            <p style="color: #6b7280;">
                                View information about this lead.
                            </p>

                        </div>

                        <div>

                            <a
                                href="{{ route('leads.index') }}"
                                style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none;"
                            >
                                Back to Leads
                            </a>

                            <a
                                href="{{ route('leads.edit', $lead) }}"
                                style="background-color: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; margin-left: 10px;"
                            >
                                Edit Lead
                            </a>

                        </div>

                    </div>


                    <!-- Success Message -->

                    @if (session('success'))

                        <div
                            style="
                                background-color: #dcfce7;
                                color: #166534;
                                padding: 12px;
                                margin-bottom: 20px;
                                border-radius: 6px;
                            "
                        >
                            {{ session('success') }}
                        </div>

                    @endif


                    <!-- Lead Information -->

                    <div style="display: grid; gap: 18px;">

                        <div>
                            <strong>Lead Name</strong>
                            <p>{{ $lead->name }}</p>
                        </div>

                        <div>
                            <strong>Email</strong>
                            <p>{{ $lead->email ?? 'Not provided' }}</p>
                        </div>

                        <div>
                            <strong>Phone</strong>
                            <p>{{ $lead->phone }}</p>
                        </div>

                        <div>
                            <strong>Product / Service</strong>
                            <p>{{ $lead->product_service }}</p>
                        </div>

                        <div>
                            <strong>Lead Source</strong>
                            <p>{{ $lead->lead_source ?? 'Not provided' }}</p>
                        </div>

                        <div>
                            <strong>Communication Method</strong>
                            <p>{{ ucfirst($lead->communication_method) }}</p>
                        </div>

                        <div>
                            <strong>Status</strong>
                            <p>{{ $lead->status }}</p>
                        </div>

                        <div>

                            <strong>Follow-up Date</strong>

                            <p>
                                {{ $lead->follow_up_date
                                    ? \Carbon\Carbon::parse($lead->follow_up_date)->format('d F Y')
                                    : 'Not scheduled'
                                }}
                            </p>

                        </div>

                        <div>

                            <strong>Priority</strong>

                            <p>
                                {{ $lead->priority ?? 'Medium' }}
                            </p>

                        </div>

                        <div>

                            <strong>Assigned Staff</strong>

                            <p>
                                {{ $lead->assignedStaff?->name ?? 'Unassigned' }}
                            </p>

                        </div>

                        <div>

                            <strong>Requirements / Notes</strong>

                            <p>
                                {{ $lead->requirements ?? 'No additional requirements.' }}
                            </p>

                        </div>

                        <div>

                            <strong>Registered Date</strong>

                            <p>
                                {{ $lead->created_at->format('d M Y, h:i A') }}
                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Add Activity -->

            <div class="bg-white shadow-sm sm:rounded-lg" style="margin-top: 25px;">

                <div class="p-6">

                    <h2 class="text-xl font-bold mb-2">
                        Add Activity
                    </h2>

                    <p style="color: #6b7280; margin-bottom: 20px;">
                        Record a call, WhatsApp message, email, meeting, or other interaction with this lead.
                    </p>


                    @if ($errors->any())

                        <div
                            style="
                                background-color: #fee2e2;
                                color: #991b1b;
                                padding: 15px;
                                margin-bottom: 20px;
                                border-radius: 6px;
                            "
                        >

                            <ul>

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <form
                        method="POST"
                        action="{{ route('leads.activities.store', $lead) }}"
                    >

                        @csrf


                        <!-- Communication Method -->

                        <div class="mb-4">

                            <label
                                class="block font-medium text-sm text-gray-700"
                                style="margin-bottom: 6px;"
                            >
                                Communication Method
                            </label>

                            <select
                                name="communication_method"
                                required
                                style="
                                    width: 100%;
                                    padding: 10px;
                                    border: 1px solid #d1d5db;
                                    border-radius: 6px;
                                "
                            >

                                <option value="">
                                    Select method
                                </option>

                                <option value="Call">
                                    Call
                                </option>

                                <option value="WhatsApp">
                                    WhatsApp
                                </option>

                                <option value="Email">
                                    Email
                                </option>

                                <option value="Meeting">
                                    Meeting
                                </option>

                                <option value="Other">
                                    Other
                                </option>

                            </select>

                        </div>


                        <!-- Notes -->

                        <div class="mb-4">

                            <label
                                class="block font-medium text-sm text-gray-700"
                                style="margin-bottom: 6px;"
                            >
                                Activity Notes
                            </label>

                            <textarea
                                name="notes"
                                rows="4"
                                required
                                placeholder="Describe what happened during the interaction..."
                                style="
                                    width: 100%;
                                    padding: 10px;
                                    border: 1px solid #d1d5db;
                                    border-radius: 6px;
                                "
                            >{{ old('notes') }}</textarea>

                        </div>


                        <!-- Follow-up Date -->

                        <div class="mb-6">

                            <label
                                class="block font-medium text-sm text-gray-700"
                                style="margin-bottom: 6px;"
                            >
                                Next Follow-up Date
                            </label>

                            <input
                                type="date"
                                name="follow_up_date"
                                value="{{ old('follow_up_date') }}"
                                style="
                                    width: 100%;
                                    padding: 10px;
                                    border: 1px solid #d1d5db;
                                    border-radius: 6px;
                                "
                            >

                        </div>


                        <!-- Submit -->

                        <button
                            type="submit"
                            style="
                                background-color: #2563eb;
                                color: white;
                                padding: 10px 20px;
                                border: none;
                                border-radius: 6px;
                                font-weight: 600;
                                cursor: pointer;
                            "
                        >
                            Add Activity
                        </button>

                    </form>

                </div>

            </div>


            <!-- Activity History -->

            <div class="bg-white shadow-sm sm:rounded-lg" style="margin-top: 25px;">

                <div class="p-6">

                    <h2 class="text-xl font-bold mb-2">
                        Activity History
                    </h2>

                    <p style="color: #6b7280; margin-bottom: 20px;">
                        Previous interactions with this lead.
                    </p>


                    @if ($lead->activities->count() > 0)

                        <div style="display: grid; gap: 15px;">

                            @foreach ($lead->activities as $activity)

                                <div
                                    style="
                                        border: 1px solid #e5e7eb;
                                        border-radius: 8px;
                                        padding: 15px;
                                    "
                                >

                                    <div
                                        style="
                                            display: flex;
                                            justify-content: space-between;
                                            align-items: center;
                                            margin-bottom: 8px;
                                        "
                                    >

                                        <strong>
                                            {{ $activity->communication_method }}
                                        </strong>

                                        <span style="color: #6b7280; font-size: 14px;">
                                            {{ $activity->created_at->format('d M Y, h:i A') }}
                                        </span>

                                    </div>


                                    <p style="margin-bottom: 8px;">
                                        {{ $activity->notes }}
                                    </p>


                                    <div style="font-size: 14px; color: #6b7280;">

                                        <strong>
                                            Recorded by:
                                        </strong>

                                        {{ $activity->user?->name ?? 'Unknown' }}

                                    </div>


                                    @if ($activity->follow_up_date)

                                        <div
                                            style="
                                                margin-top: 8px;
                                                font-size: 14px;
                                                color: #374151;
                                            "
                                        >

                                            <strong>
                                                Next Follow-up:
                                            </strong>

                                            {{ \Carbon\Carbon::parse($activity->follow_up_date)->format('d F Y') }}

                                        </div>

                                    @endif

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div
                            style="
                                padding: 25px;
                                text-align: center;
                                color: #6b7280;
                                border: 1px solid #e5e7eb;
                                border-radius: 8px;
                            "
                        >
                            No activities have been recorded for this lead yet.
                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
```
