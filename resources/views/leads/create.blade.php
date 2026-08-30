<x-app-layout>

    <div class="db-root py-8 sm:py-10 min-h-screen">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Page Header --}}
            <div>
                <h2 class="db-heading text-2xl font-semibold" style="color: var(--db-ink);">
                    Register New Lead
                </h2>
                <p class="mt-1 text-sm" style="color: var(--db-ink-soft);">
                    Add a new lead to the system.
                </p>
            </div>

            @if (session('success'))
                <div class="db-tint-block db-tint-block--success db-text-success text-sm">
                    {{ session('success') }}
                </div>
            @endif

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

                    <form method="POST" action="{{ route('leads.store') }}">

                        @csrf

                        <div class="mb-4">
                            <label class="db-label">Lead Name</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="db-input"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="db-label">Email Address</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="db-input"
                            >
                        </div>

                        <div class="mb-4">
                            <label class="db-label">Phone Number</label>
                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                class="db-input"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="db-label">Requested Product / Service</label>
                            <input
                                type="text"
                                name="product_service"
                                value="{{ old('product_service') }}"
                                class="db-input"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="db-label">Lead Source</label>
                            <input
                                type="text"
                                name="lead_source"
                                value="{{ old('lead_source') }}"
                                placeholder="Facebook, Website, Referral, etc."
                                class="db-input"
                            >
                        </div>

                        <div class="mb-5">
                            <label class="db-label">Preferred Communication Method</label>

                            <div class="flex items-center gap-6 mt-1 text-sm" style="color: var(--db-ink);">
                                <label class="inline-flex items-center gap-2">
                                    <input
                                        type="radio"
                                        name="communication_method"
                                        value="email"
                                        {{ old('communication_method') == 'email' ? 'checked' : '' }}
                                        required
                                    >
                                    Email
                                </label>

                                <label class="inline-flex items-center gap-2">
                                    <input
                                        type="radio"
                                        name="communication_method"
                                        value="whatsapp"
                                        {{ old('communication_method') == 'whatsapp' ? 'checked' : '' }}
                                    >
                                    WhatsApp
                                </label>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="db-label">Additional Requirements / Notes</label>
                            <textarea
                                name="requirements"
                                rows="4"
                                class="db-input"
                            >{{ old('requirements') }}</textarea>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="db-btn-primary">
                                Register Lead
                            </button>

                            <a href="{{ route('leads.index') }}" class="db-btn-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>

</x-app-layout>