<x-guest-layout>
    <!-- Session Status -->

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Correo')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4 flex justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-700 shadow-sm focus:ring-indigo-600 "
                    name="remember">
                <span class="ms-2 text-sm">{{ __('Recuérdame') }}</span>
            </label>
            <!--
                <a class="underline text-sm text-gray-600  hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 "
                    href="{{ route('password.request') }}">
                    {{ __('¿No te sabes tu contraseña?') }}
                </a>
            -->
        </div>

        <div class="flex items-center justify-center mt-6">
            <button type="submit"
                    style="background: #4f46e5; border: none; color: white; padding: 12px 24px; border-radius: 8px; font-size: 1rem; font-weight: 500; cursor: pointer; transition: all 0.2s ease;"
                    onmouseover="this.style.background='#4338ca'; this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 12px rgba(79, 70, 229, 0.3)';"
                    onmouseout="this.style.background='#4f46e5'; this.style.transform='translateY(0px)'; this.style.boxShadow='none';">
                {{ __('Iniciar sesión') }}
            </button>
        </div>
    </form>

</x-guest-layout>
