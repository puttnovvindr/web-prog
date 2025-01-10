<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Wishlist') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if (session('message'))
                <p class="text-green-500">{{ session('message') }}</p>
            @endif

            <!-- Wishlist Items -->
            <div class="mb-12">
                <h3 class="text-xl font-semibold mb-4">Your Wishlist</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($wishlistItems as $item)
                        <div class="bg-white text-black border rounded-lg p-4 flex flex-col items-center shadow-lg hover:shadow-xl transition">
                            <img
                                src="{{ $item->profile_picture ?? 'https://via.placeholder.com/150' }}"
                                alt="{{ $item->user->name }}"
                                class="w-24 h-24 rounded-full mb-4"
                            />
                            <h2 class="text-lg font-semibold" style="color: #000000;">
                                {{ $item->targetUser->name }}
                            </h2>
                            <p class="text-sm mt-2 text-black" style="color: #000000;">
                                @php
                                    $fields = json_decode($item->targetUser->fields_of_work);
                                    if (is_array($fields)) {
                                        echo implode(', ', $fields);
                                    } else {
                                        echo $item->targetUser->fields_of_work;
                                    }
                                @endphp
                            </p>

                            <form action="{{ route('wishlist.remove', $item->id) }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="relative z-10 bg-red-500 text-black px-12 py-4 rounded hover:bg-red-600 transition"
                                >
                                    Remove from Wishlist
                                </button>
                            </form>

                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Pending Requests -->
            <div class="mt-12">
                <h3 class="text-xl font-semibold mb-4">Pending Requests</h3>
                @if ($pendingRequests->isEmpty())
                    <p>No pending requests.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($pendingRequests as $request)
                            <div class="bg-white text-black border rounded-lg p-4 flex flex-col items-center shadow-lg hover:shadow-xl transition">
                                <img
                                    src="{{ $request->user->profile_picture ?? 'https://via.placeholder.com/150' }}"
                                    alt="{{ $request->user->name }}"
                                    class="w-24 h-24 rounded-full mb-4"
                                />
                                <h2 class="text-lg font-semibold">{{ $request->user->name }}</h2>
                                <p class="text-sm mt-2">
                                    @php
                                        $fields = json_decode($request->user->fields_of_work);
                                        echo implode(', ', $fields);
                                    @endphp
                                </p>

                                <!-- Accept or Decline Request -->
                                <form action="{{ route('wishlist.accept', $request->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-green-500 text-black px-12 py-4 rounded hover:bg-green-600 transition">
                                        Accept Request
                                    </button>
                                </form>
                                <form action="{{ route('wishlist.decline', $request->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="bg-red-500 text-black px-12 py-4 rounded hover:bg-red-600 transition">
                                        Decline Request
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Accepted Wishlist Items -->
            <!-- Accepted Wishlist Items -->
            <div class="mb-12">
                <h3 class="text-xl font-semibold mb-4">Accepted Wishlist</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($acceptedRequests as $item)
                        @if ($item->user_id == auth()->id())
                            <!-- Ini adalah request yang diterima oleh User A -->
                            <div class="bg-white text-black border rounded-lg p-4 flex flex-col items-center shadow-lg hover:shadow-xl transition">
                                <img
                                    src="{{ $item->targetUser->profile_picture ?? 'https://via.placeholder.com/150' }}"
                                    alt="{{ $item->targetUser->name }}"
                                    class="w-24 h-24 rounded-full mb-4"
                                />
                                <h2 class="text-lg font-semibold">{{ $item->targetUser->name }}</h2>
                                <p class="text-sm mt-2">
                                    @php
                                        $fields = json_decode($item->targetUser->fields_of_work);
                                        echo implode(', ', $fields);
                                    @endphp
                                </p>
                                <a href="{{ route('chat.index', $item->user_id === auth()->id() ? $item->targetUser->id : $item->user->id) }}"
                                class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 transition mt-4">
                                    Chat
                                </a>
                            </div>
                        @elseif ($item->target_user_id == auth()->id())
                            <!-- Ini adalah request yang diterima oleh User B -->
                            <div class="bg-white text-black border rounded-lg p-4 flex flex-col items-center shadow-lg hover:shadow-xl transition">
                                <img
                                    src="{{ $item->user->profile_picture ?? 'https://via.placeholder.com/150' }}"
                                    alt="{{ $item->user->name }}"
                                    class="w-24 h-24 rounded-full mb-4"
                                />
                                <h2 class="text-lg font-semibold">{{ $item->user->name }}</h2>
                                <p class="text-sm mt-2">
                                    @php
                                        $fields = json_decode($item->user->fields_of_work);
                                        echo implode(', ', $fields);
                                    @endphp
                                </p>
                                <a href="{{ route('chat.index', $item->user_id === auth()->id() ? $item->targetUser->id : $item->user->id) }}"
                                class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600 transition mt-4">
                                    Chat
                                </a>
                            </div>
                        @endif
                    @endforeach
                    @if ($acceptedRequests->isEmpty())
                        <p>No accepted requests found.</p>
                    @endif
                </div>
            </div>


        </div>
    </div>
</x-app-layout>
