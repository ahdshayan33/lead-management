<x-app-layout>

    <div class="db-root py-8 sm:py-10 min-h-screen">

        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Page Header --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="db-heading text-2xl font-semibold" style="color: var(--db-ink);">
                        Edit Staff
                    </h2>
                    <p class="mt-1 text-sm" style="color: var(--db-ink-soft);">
                        Update this staff member's account details.
                    </p>
                </div>

                <a href="{{ route('staff.show', $user) }}" class="db-btn-secondary self-start sm:self-auto">
                    Back
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

                    <form method="POST" action="{{ route('staff.update', $user) }}">

                        @csrf
                        @method('PUT')

                        <div class="mb-5">
                            <label class="db-label">Full Name</label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                class="db-input"
                                required
                            >
                        </div>

                        <div class="mb-5">
                            <label class="db-label">Email Address</label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                class="db-input"
                                required
                            >
                            <p class="text-xs mt-1" style="color: var(--db-ink-faint);">
                                Follow-up reminder emails will be sent to this address.
                            </p>
                        </div>

                        <div class="mb-5">
                            <label class="db-label">New Password</label>
                            <input
                                type="password"
                                name="password"
                                class="db-input"
                            >
                            <p class="text-xs mt-1" style="color: var(--db-ink-faint);">
                                Leave this blank if you do not want to change the password.
                            </p>
                        </div>

                        <div class="mb-6">
                            <label class="db-label">Confirm New Password</label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="db-input"
                            >
                        </div>

                        <div class="mb-6" style="background-color: var(--db-neutral-tint); border-radius: 0.5rem; padding: 0.75rem 1rem;">
                            <p class="text-sm font-semibold" style="color: var(--db-ink);">
                                Account Role: Staff
                            </p>
                            <p class="text-xs mt-1" style="color: var(--db-ink-faint);">
                                The staff role cannot be changed from this page.
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="db-btn-primary">
                                Update Staff
                            </button>

                            <a href="{{ route('staff.show', $user) }}" class="db-btn-secondary">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>

</x-app-layout>