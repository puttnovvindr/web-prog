<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Gender -->
        <div class="mt-4">
            <x-input-label for="gender" :value="__('Gender')" />
            <select id="gender" name="gender" class="block mt-1 w-full" required>
                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        <!-- Field of Work -->
        <div class="mt-4">
            <x-input-label for="field_of_work" :value="__('Field of Work (Min 3)')" />
            <input type="text" name="field_of_work[]" placeholder="Field 1" class="block mt-1 w-full" required>
            <input type="text" name="field_of_work[]" placeholder="Field 2" class="block mt-1 w-full" required>
            <input type="text" name="field_of_work[]" placeholder="Field 3" class="block mt-1 w-full" required>
            <x-input-error :messages="$errors->get('field_of_work')" class="mt-2" />
        </div>

        <!-- LinkedIn -->
        <div class="mt-4">
            <x-input-label for="linkedin" :value="__('LinkedIn Username (URL)')" />
            <x-text-input id="linkedin" class="block mt-1 w-full" type="text" name="linkedin" placeholder="linkedin.com/in/username" :value="old('linkedin')" required />
            <x-input-error :messages="$errors->get('linkedin')" class="mt-2" />
        </div>

        <!-- Mobile Number -->
        <div class="mt-4">
            <x-input-label for="mobile_number" :value="__('Mobile Number')" />
            <x-text-input id="mobile_number" class="block mt-1 w-full" type="text" name="mobile_number" :value="old('mobile_number')" required />
            <x-input-error :messages="$errors->get('mobile_number')" class="mt-2" />
        </div>

        <!-- Random Registration Price -->
        <div class="mt-4">
            <x-input-label for="registration_price" :value="__('Registration Price')" />
            <p id="registration-price" class="block mt-1 w-full">{{ rand(100000, 125000) }}</p>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
