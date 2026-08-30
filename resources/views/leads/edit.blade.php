<x-app-layout>

    <div class="db-root py-8 sm:py-10 min-h-screen">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Page Header --}}
            <div>
                <h2 class="db-heading text-2xl font-semibold" style="color: var(--db-ink);">
                    Edit Lead
                </h2>
                <p class="mt-1 text-sm" style="color: var(--db-ink-soft);">
                    Update this lead's information.
                </p>
            </div>

            @if ($errors->any())
                <div class="db-tint-block db-tint-block--danger db-text-danger text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="db-card">
                <div class="p-5 sm:p-6">

                    <form method="POST" action="{{ route('leads.update', $lead) }}">

                        @csrf
                        @method('PUT')

                        {{-- ================================================= --}}
                        {{-- ADMIN ONLY FIELDS --}}
                        {{-- ================================================= --}}

                        @if (auth()->user()->role === 'admin')

                            <div class="mb-4">
                                <label class="db-label">Lead Name</label>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name', $lead->name) }}"
                                    class="db-input"
                                    required
                                >
                            </div>

                            <div class="mb-4">
                                <label class="db-label">Email Address</label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email', $lead->email) }}"
                                    class="db-input"
                                >
                            </div>

                            <div class="mb-4">
                                <label class="db-label">Phone Number</label>
                                <input
                                    type="text"
                                    name="phone"
                                    value="{{ old('phone', $lead->phone) }}"
                                    class="db-input"
                                    required
                                >
                            </div>

                            <div class="mb-4">
                                <label class="db-label">Product / Service</label>
                                <input
                                    type="text"
                                    name="product_service"
                                    value="{{ old('product_service', $lead->product_service) }}"
                                    class="db-input"
                                    required
                                >
                            </div>

                            <div class="mb-4">
                                <label class="db-label">Lead Source</label>
                                <input
                                    type="text"
                                    name="lead_source"
                                    value="{{ old('lead_source', $lead->lead_source) }}"
                                    class="db-input"
                                >
                            </div>

                            <div class="mb-4">
                                <label class="db-label">Registered Date & Time</label>
                                <input
                                    type="datetime-local"
                                    name="registered_at"
                                    value="{{ old('registered_at', $lead->created_at ? $lead->created_at->format('Y-m-d\TH:i') : '') }}"
                                    class="db-input"
                                    required
                                >

                                <p class="text-xs mt-1" style="color: var(--db-ink-faint);">
                                    This is the date and time when the lead was registered.
                                </p>
                            </div>

                            <div class="mb-5">
                                <label class="db-label">Communication Method</label>

                                <div class="flex items-center gap-6 mt-1 text-sm" style="color: var(--db-ink);">
                                    <label class="inline-flex items-center gap-2">
                                        <input
                                            type="radio"
                                            name="communication_method"
                                            value="email"
                                            {{ old('communication_method', $lead->communication_method) == 'email' ? 'checked' : '' }}
                                            required
                                        >
                                        Email
                                    </label>

                                    <label class="inline-flex items-center gap-2">
                                        <input
                                            type="radio"
                                            name="communication_method"
                                            value="whatsapp"
                                            {{ old('communication_method', $lead->communication_method) == 'whatsapp' ? 'checked' : '' }}
                                        >
                                        WhatsApp
                                    </label>
                                </div>
                            </div>

                        @endif

                        {{-- ================================================= --}}
                        {{-- STATUS --}}
                        {{-- ================================================= --}}

                        <div class="mb-5">
                            <label class="db-label">Lead Status</label>
                            <select
                                name="status"
                                id="lead-status"
                                class="db-input"
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
                        {{-- CONVERSION DETAILS (shown only when status = Converted) --}}
                        {{-- ================================================= --}}

                        <div
                            id="conversion-section"
                            class="db-tint-block db-tint-block--success mb-5"
                            style="display: {{ old('status', $lead->status) === 'Converted' ? 'block' : 'none' }};"
                        >
                            <h3 class="text-base font-semibold db-text-success mb-1">
                                Conversion Details
                            </h3>
                            <p class="text-sm db-text-success mb-5" style="opacity: 0.85;">
                                Enter the details of the successful conversion.
                            </p>

                            <div class="mb-4">
                                <label class="db-label">Conversion Value</label>
                                <input
                                    type="number"
                                    name="conversion_value"
                                    value="{{ old('conversion_value', $lead->conversion_value) }}"
                                    min="0"
                                    step="0.01"
                                    placeholder="e.g. 1500.00"
                                    class="db-input"
                                >
                                <p class="text-xs mt-1" style="color: var(--db-ink-faint);">
                                    Enter the value or revenue generated from this conversion.
                                </p>
                            </div>

                            <div class="mb-1">
                                <label class="db-label">Conversion Notes</label>
                                <textarea
                                    name="conversion_notes"
                                    rows="4"
                                    placeholder="Add notes about the conversion..."
                                    class="db-input"
                                >{{ old('conversion_notes', $lead->conversion_notes) }}</textarea>
                            </div>

                            @if ($lead->converted_at)
                                <p class="text-xs db-text-success mt-3">
                                    Converted on {{ $lead->converted_at->format('d M Y, h:i A') }}
                                </p>
                            @endif
                        </div>

                        {{-- ================================================= --}}
                        {{-- FOLLOW-UP DATE --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">
                            <label class="db-label">Follow-up Date</label>
                            <input
                                type="date"
                                name="follow_up_date"
                                value="{{ old('follow_up_date', $lead->follow_up_date) }}"
                                class="db-input"
                            >
                        </div>

                        {{-- ================================================= --}}
                        {{-- PRIORITY --}}
                        {{-- ================================================= --}}

                        <div class="mb-4">
                            <label class="db-label">Priority</label>
                            <select name="priority" class="db-input" required>
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
                                <label class="db-label">Assign Staff Member</label>
                                <select name="assigned_to" class="db-input">
                                    <option value="">Unassigned</option>
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
                            <label class="db-label">Requirements / Notes</label>
                            <textarea
                                name="requirements"
                                rows="5"
                                class="db-input"
                            >{{ old('requirements', $lead->requirements) }}</textarea>
                        </div>

                        {{-- ================================================= --}}
                        {{-- BUTTONS --}}
                        {{-- ================================================= --}}

                        <div class="flex items-center gap-3">
                            <button type="submit" class="db-btn-primary">
                                Update Lead
                            </button>

                            <a href="{{ route('leads.show', $lead) }}" class="db-btn-secondary">
                                Cancel
                            </a>
                        </div>

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
                conversionSection.style.display = statusSelect.value === 'Converted' ? 'block' : 'none';
            }

            statusSelect.addEventListener('change', toggleConversionSection);
            toggleConversionSection();
        });
    </script>

</x-app-layout>