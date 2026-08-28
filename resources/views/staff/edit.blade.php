<x-app-layout>

    <div class="py-12">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <!-- Header -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">

                        <div>
                            <h2 class="text-2xl font-bold">
                                Edit Staff
                            </h2>

                            <p style="color: #6b7280;">
                                Update this staff member's account details.
                            </p>
                        </div>

                        <a
                            href="{{ route('staff.show', $user) }}"
                            style="background-color: #6b7280; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none;"
                        >
                            Back
                        </a>

                    </div>


                    <!-- Validation Errors -->
                    @if ($errors->any())

                        <div
                            style="background-color: #fee2e2; color: #991b1b; padding: 15px; margin-bottom: 20px; border-radius: 6px;"
                        >

                            <ul style="margin: 0; padding-left: 20px;">

                                @foreach ($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif


                    <!-- Edit Form -->
                    <form method="POST" action="{{ route('staff.update', $user) }}">

                        @csrf
                        @method('PUT')


                        <!-- Name -->
                        <div class="mb-5">

                            <label class="block font-medium text-sm text-gray-700">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                required
                            >

                        </div>


                        <!-- Email -->
                        <div class="mb-5">

                            <label class="block font-medium text-sm text-gray-700">
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user->email) }}"
                                style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                                required
                            >

                            <p style="font-size: 13px; color: #6b7280; margin-top: 5px;">
                                Follow-up reminder emails will be sent to this address.
                            </p>

                        </div>


                        <!-- Password -->
                        <div class="mb-5">

                            <label class="block font-medium text-sm text-gray-700">
                                New Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                            >

                            <p style="font-size: 13px; color: #6b7280; margin-top: 5px;">
                                Leave this blank if you do not want to change the password.
                            </p>

                        </div>


                        <!-- Confirm Password -->
                        <div class="mb-6">

                            <label class="block font-medium text-sm text-gray-700">
                                Confirm New Password
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px;"
                            >

                        </div>


                        <!-- Role -->
                        <div
                            style="background-color: #f3f4f6; padding: 12px; border-radius: 6px; margin-bottom: 20px;"
                        >

                            <strong>Account Role:</strong> Staff

                            <p style="font-size: 13px; color: #6b7280; margin-top: 4px;">
                                The staff role cannot be changed from this page.
                            </p>

                        </div>


                        <!-- Buttons -->
                        <div>

                            <button
                                type="submit"
                                style="background-color: #2563eb; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;"
                            >
                                Update Staff
                            </button>

                            <a
                                href="{{ route('staff.show', $user) }}"
                                style="margin-left: 10px; background-color: #6b7280; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none;"
                            >
                                Cancel
                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>