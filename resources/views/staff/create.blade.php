<x-app-layout>

    <div class="db-root py-8 sm:py-10 min-h-screen">

        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Page Header --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="db-heading text-2xl font-semibold" style="color: var(--db-ink);">
                        Add New Staff
                    </h2>
                    <p class="mt-1 text-sm" style="color: var(--db-ink-soft);">
                        Create a new staff account.
                    </p>
                </div>

                <a href="{{ route('staff.index') }}" class="db-btn-secondary self-start sm:self-auto">
                    Back to Staff
                </a>
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

                    <form method="POST" action="{{ route('staff.store') }}">

                        @csrf

                        <div class="mb-5">
                            <label class="db-label">Full Name</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="db-input"
                                required
                                autofocus
                            >
                        </div>

                        <div class="mb-5">
                            <label class="db-label">Email Address</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="db-input"
                                required
                            >
                            <p class="text-xs mt-1" style="color: var(--db-ink-faint);">
                                This email address will be used for follow-up reminder notifications.
                            </p>
                        </div>

                        <div class="mb-5">
                            <label class="db-label">Password</label>
                            <input
                                type="password"
                                name="password"
                                class="db-input"
                                required
                            >
                            <p class="text-xs mt-1" style="color: var(--db-ink-faint);">
                                Password must be at least 8 characters.
                            </p>
                        </div>

                        <div class="mb-6">
                            <label class="db-label">Confirm Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="db-input"
                                required
                            >
                        </div>

                        <div class="mb-6" style="background-color: var(--db-neutral-tint); border-radius: 0.5rem; padding: 0.75rem 1rem;">
                            <p class="text-sm font-semibold" style="color: var(--db-ink);">
                                Account Role: Staff
                            </p>
                            <p class="text-xs mt-1" style="color: var(--db-ink-faint);">
                                New accounts created here are automatically assigned the Staff role.
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="db-btn-primary">
                                Create Staff Account
                            </button>

                            <a href="{{ route('staff.index') }}" class="db-btn-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>

</x-app-layout>