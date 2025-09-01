<x-guest-layout>
    <h1 class="text-3xl font-bold text-center mb-8">Registering as Seller</h1>
    <form method="POST" action="{{ route('register.seller.store') }}">
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

        <!-- Number -->
        <div class="mt-4">
            <x-input-label for="number" :value="__('Number')" />
            <x-text-input id="number" class="block mt-1 w-full" type="text" name="number" :value="old('number')" required />
            <x-input-error :messages="$errors->get('number')" class="mt-2" />
        </div>

        <!-- Organization -->
        <div class="mt-4">
            <x-input-label for="organization" :value="__('Organization (if any)')" />
            <x-text-input id="organization" class="block mt-1 w-full" type="text" name="organization" :value="old('organization')" />
            <x-input-error :messages="$errors->get('organization')" class="mt-2" />
        </div>

        <!-- Type of Business -->
        <div class="mt-4">
            <x-input-label for="type_of_business" :value="__('Type of Business')" />
            <x-text-input id="type_of_business" class="block mt-1 w-full" type="text" name="type_of_business" :value="old('type_of_business')" required />
            <x-input-error :messages="$errors->get('type_of_business')" class="mt-2" />
        </div>

        <!-- Way of Payment -->
        <div class="mt-4">
            <x-input-label for="way_of_payment" :value="__('Way of Payment')" />
            <x-text-input id="way_of_payment" class="block mt-1 w-full" type="text" name="way_of_payment" :value="old('way_of_payment')" required />
            <x-input-error :messages="$errors->get('way_of_payment')" class="mt-2" />
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

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
