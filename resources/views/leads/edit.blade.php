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
                                id="lead-status"
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
                        {{-- CONVERSION DETAILS --}}
                        {{-- ================================================= --}}

                        <div
                            id="conversion-section"
                            style="
                                display: {{ old('status', $lead->status) === 'Converted' ? 'block' : 'none' }};
                                background-color: #f0fdf4;
                                border: 1px solid #bbf7d0;
                                padding: 20px;
                                border-radius: 8px;
                                margin-bottom: 20px;
                            "
                        >

                            <h3 class="text-lg font-bold text-green-800 mb-1">
                                Conversion Details
                            </h3>

                            <p class="text-sm text-green-700 mb-5">
                                Enter the details of the successful conversion.
                            </p>


                            <!-- Conversion Value -->

                            <div class="mb-4">

                                <label class="block font-medium text-sm text-gray-700">
                                    Conversion Value
                                </label>

                                <input
                                    type="number"
                                    name="conversion_value"
                                    value="{{ old('conversion_value', $lead->conversion_value) }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="e.g. 1500.00"
                                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                >

                                <p class="text-xs text-gray-500 mt-1">
                                    Enter the value or revenue generated from this conversion.
                                </p>

                            </div>


                            <!-- Conversion Notes -->

                            <div class="mb-2">

                                <label class="block font-medium text-sm text-gray-700">
                                    Conversion Notes
                                </label>

                                <textarea
                                    name="conversion_notes"
                                    rows="4"
                                    placeholder="Add notes about the conversion..."
                                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                >{{ old('conversion_notes', $lead->conversion_notes) }}</textarea>

                            </div>


                            @if ($lead->converted_at)

                                <p class="text-xs text-green-700 mt-3">
                                    Converted on
                                    {{ $lead->converted_at->format('d M Y, h:i A') }}
                                </p>

                            @endif

                        </div>


                       
                        {{-- ================================================= --}}
                        {{-- CONVERSION DETAILS --}}
                        {{-- ================================================= --}}

                        @if ($lead->status === 'Converted' || old('status', $lead->status) === 'Converted')

                            <div class="mb-4">

                                <label class="block font-medium text-sm text-gray-700">
                                    Conversion Value
                                </label>

                                <input
                                    type="number"
                                    name="conversion_value"
                                    value="{{ old('conversion_value', $lead->conversion_value) }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="Enter conversion value"
                                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                >

                                <p class="text-xs text-gray-500 mt-1">
                                    Enter the value/revenue generated from this conversion.
                                </p>

                            </div>


                            <div class="mb-6">

                                <label class="block font-medium text-sm text-gray-700">
                                    Conversion Notes
                                </label>

                                <textarea
                                    name="conversion_notes"
                                    rows="4"
                                    placeholder="Add any notes about the conversion..."
                                    style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                >{{ old('conversion_notes', $lead->conversion_notes) }}</textarea>

                            </div>

                        @endif



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


    {{-- ================================================= --}}
    {{-- SHOW / HIDE CONVERSION SECTION --}}
    {{-- ================================================= --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const statusSelect = document.getElementById('lead-status');
            const conversionSection = document.getElementById('conversion-section');

            function toggleConversionSection() {

                if (statusSelect.value === 'Converted') {

                    conversionSection.style.display = 'block';

                } else {

                    conversionSection.style.display = 'none';

                }
            }

            statusSelect.addEventListener('change', toggleConversionSection);

            toggleConversionSection();

        });

    </script>

</x-app-layout>