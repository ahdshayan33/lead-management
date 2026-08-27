<x-app-layout>

    <div class="py-12">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <h2 class="text-2xl font-bold mb-6">
                        Edit Lead
                    </h2>

                    @if ($errors->any())

                        <div style="background-color: #fee2e2; color: #991b1b; padding: 15px; margin-bottom: 20px; border-radius: 6px;">

                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>

                        </div>

                    @endif


                    <form method="POST" action="{{ route('leads.update', $lead) }}">

                        @csrf
                        @method('PUT')


                        {{-- ================================================= --}}
                        {{-- ADMIN ONLY FIELDS --}}
                        {{-- ================================================= --}}

                        @if (auth()->user()->role === 'admin')

                            <!-- Lead Name -->
                            <div class="mb-4">

                                <label class="block font-medium text-sm text-gray-700">
                                    Lead Name
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $lead->name) }}"
                                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                    required
                                >

                            </div>


                            <!-- Email -->
                            <div class="mb-4">

                                <label class="block font-medium text-sm text-gray-700">
                                    Email Address
                                </label>

                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $lead->email) }}"
                                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                >

                            </div>


                            <!-- Phone -->
                            <div class="mb-4">

                                <label class="block font-medium text-sm text-gray-700">
                                    Phone Number
                                </label>

                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', $lead->phone) }}"
                                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                    required
                                >

                            </div>


                            <!-- Product / Service -->
                            <div class="mb-4">

                                <label class="block font-medium text-sm text-gray-700">
                                    Product / Service
                                </label>

                                <input
                                    type="text"
                                    name="product_service"
                                    value="{{ old('product_service', $lead->product_service) }}"
                                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                    required
                                >

                            </div>


                            <!-- Lead Source -->
                            <div class="mb-4">

                                <label class="block font-medium text-sm text-gray-700">
                                    Lead Source
                                </label>

                                <input
                                    type="text"
                                    name="lead_source"
                                    value="{{ old('lead_source', $lead->lead_source) }}"
                                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                >

                            </div>


                            <!-- Communication Method -->
                            <div class="mb-4">

                                <label class="block font-medium text-sm text-gray-700">
                                    Communication Method
                                </label>

                                <label style="margin-right: 20px;">

                                    <input
                                        type="radio"
                                        name="communication_method"
                                        value="email"
                                        {{ old('communication_method', $lead->communication_method) == 'email' ? 'checked' : '' }}
                                        required
                                    >

                                    Email

                                </label>


                                <label>

                                    <input
                                        type="radio"
                                        name="communication_method"
                                        value="whatsapp"
                                        {{ old('communication_method', $lead->communication_method) == 'whatsapp' ? 'checked' : '' }}
                                    >

                                    WhatsApp

                                </label>

                            </div>

                        @endif


                        {{-- ================================================= --}}
                        {{-- STATUS --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">

                            <label class="block font-medium text-sm text-gray-700">
                                Lead Status
                            </label>

                            <select
                                name="status"
                                style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                required
                            >

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
                                        {{ old('status', $lead->status) == $status ? 'selected' : '' }}
                                    >
                                        {{ $status }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- ================================================= --}}
                        {{-- FOLLOW-UP DATE --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">

                            <label class="block font-medium text-sm text-gray-700">
                                Follow-up Date
                            </label>

                            <input
                                type="date"
                                name="follow_up_date"
                                value="{{ old('follow_up_date', $lead->follow_up_date) }}"
                                style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                            >

                        </div>


                        {{-- ================================================= --}}
                        {{-- PRIORITY --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">

                            <label class="block font-medium text-sm text-gray-700">
                                Priority
                            </label>

                            <select
                                name="priority"
                                style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                required
                            >

                                @foreach (['Low', 'Medium', 'High'] as $priority)

                                    <option
                                        value="{{ $priority }}"
                                        {{ old('priority', $lead->priority ?? 'Medium') == $priority ? 'selected' : '' }}
                                    >
                                        {{ $priority }}
                                    </option>

                                @endforeach

                            </select>

                        </div>


                        {{-- ================================================= --}}
                        {{-- ASSIGN STAFF - ADMIN ONLY --}}
                        {{-- ================================================= --}}

                        @if (auth()->user()->role === 'admin')

                            <div class="mb-4">

                                <label class="block font-medium text-sm text-gray-700">
                                    Assign Staff Member
                                </label>

                                <select
                                    name="assigned_to"
                                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                >

                                    <option value="">
                                        Unassigned
                                    </option>

                                    @foreach ($staff as $member)

                                        <option
                                            value="{{ $member->id }}"
                                            {{ old('assigned_to', $lead->assigned_to) == $member->id ? 'selected' : '' }}
                                        >
                                            {{ $member->name }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        @endif


                        {{-- ================================================= --}}
                        {{-- REQUIREMENTS / NOTES --}}
                        {{-- ================================================= --}}

                        <div class="mb-6">

                            <label class="block font-medium text-sm text-gray-700">
                                Requirements / Notes
                            </label>

                            <textarea
                                name="requirements"
                                rows="5"
                                style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                            >{{ old('requirements', $lead->requirements) }}</textarea>

                        </div>


                        {{-- ================================================= --}}
                        {{-- BUTTONS --}}
                        {{-- ================================================= --}}

                        <button
                            type="submit"
                            style="background-color: #2563eb; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;"
                        >
                            Update Lead
                        </button>


                        <a
                            href="{{ route('leads.show', $lead) }}"
                            style="margin-left: 10px; background-color: #6b7280; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none;"
                        >
                            Cancel
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>