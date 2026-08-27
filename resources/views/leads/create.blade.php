<x-app-layout>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <h2 class="text-2xl font-bold mb-6">
                        Register New Lead
                    </h2>

                    @if (session('success'))
                        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-100 text-red-800 rounded">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('leads.store') }}">

                        @csrf

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">
                                Lead Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="mt-1 block w-full rounded-md border-gray-300"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">
                                Email Address
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="mt-1 block w-full rounded-md border-gray-300"
                            >
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">
                                Phone Number
                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone') }}"
                                class="mt-1 block w-full rounded-md border-gray-300"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">
                                Requested Product / Service
                            </label>

                            <input
                                type="text"
                                name="product_service"
                                value="{{ old('product_service') }}"
                                class="mt-1 block w-full rounded-md border-gray-300"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">
                                Lead Source
                            </label>

                            <input
                                type="text"
                                name="lead_source"
                                value="{{ old('lead_source') }}"
                                placeholder="Facebook, Website, Referral, etc."
                                class="mt-1 block w-full rounded-md border-gray-300"
                            >
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700">
                                Preferred Communication Method
                            </label>

                            <div class="mt-2">
                                <label class="mr-6">
                                    <input
                                        type="radio"
                                        name="communication_method"
                                        value="email"
                                        {{ old('communication_method') == 'email' ? 'checked' : '' }}
                                        required
                                    >
                                    Email
                                </label>

                                <label>
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
                            <label class="block font-medium text-sm text-gray-700">
                                Additional Requirements / Notes
                            </label>

                            <textarea
                                name="requirements"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300"
                            >{{ old('requirements') }}</textarea>
                        </div>

                        <button
                            type="submit"
                            style="background-color: #2563eb; color: white; padding: 12px 24px; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;"
                        >
                            Register Lead
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

</x-app-layout>