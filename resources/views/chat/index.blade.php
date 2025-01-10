<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Chat with {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    @foreach ($messages as $message)
                        <div class="{{ $message->sender_id === auth()->id() ? 'text-right' : '' }}">
                            <p class="text-sm text-gray-600">
                                <strong>{{ $message->sender_id === auth()->id() ? 'You' : $user->name }}:</strong>
                                {{ $message->message }}
                            </p>
                        </div>
                    @endforeach
                </div>

                <form action="{{ route('chat.store', $user->id) }}" method="POST">
                    @csrf
                    <div class="flex">
                        <input
                            type="text"
                            name="message"
                            class="w-full border-gray-300 rounded-md shadow-sm"
                            placeholder="Type your message..."
                            required
                        />
                        <button
                            type="submit"
                            class="ml-2 bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition"
                        >
                            Send
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
