<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard - Job') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('message'))
                <p class="text-green-500">{{ session('message') }}</p>
            @endif

            <!-- Filter Form -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <form id="filter-form" method="GET" action="{{ route('dashboard') }}" class="flex flex-col gap-4 md:flex-row">
                    <!-- Gender Filter -->
                    <select id="gender-filter" name="gender" class="border px-3 py-2 rounded">
                        <option value="">Filter by Gender</option>
                        <option value="Male" {{ request('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ request('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                    </select>

                    <!-- Field of Work Filter -->
                    <input
                        id="field-of-work-filter"
                        type="text"
                        name="field_of_work"
                        class="border px-3 py-2 rounded"
                        placeholder="Search by Field of Work"
                        value="{{ request('field_of_work') }}"
                    />
                </form>
            </div>

            <!-- User Cards -->
            <div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($users as $user)
                        <div class="bg-white border rounded-lg p-4 flex flex-col items-center shadow-lg hover:shadow-xl transition">
                            <img
                                src="{{ $user->profile_picture ?? 'https://via.placeholder.com/150' }}"
                                alt="{{ $user->name }}"
                                class="w-24 h-24 rounded-full mb-4"
                            />
                            <h2 class="text-lg font-semibold text-gray-800">{{ $user->name }}</h2>
                            <p class="text-sm text-gray-600 mt-2">{{ implode(', ', json_decode($user->fields_of_work)) }}</p>
                            <form action="{{ route('wishlist.add', $user->id) }}" method="POST" class="mt-2">
                                @csrf
                                <button
                                    type="submit"
                                    class="relative z-10 bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 transition"
                                >
                                    Add to Wishlist
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <!-- Script for Filter Form -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('filter-form');
            const genderFilter = document.getElementById('gender-filter');
            const fieldOfWorkFilter = document.getElementById('field-of-work-filter');

            // Submit form when gender filter changes
            genderFilter.addEventListener('change', function () {
                filterForm.submit();
            });

            // Submit form when field of work input changes
            fieldOfWorkFilter.addEventListener('input', function () {
                clearTimeout(this.delay);
                this.delay = setTimeout(() => {
                    filterForm.submit();
                }, 500); // Delay to prevent frequent requests
            });
        });
    </script>
</x-app-layout>
