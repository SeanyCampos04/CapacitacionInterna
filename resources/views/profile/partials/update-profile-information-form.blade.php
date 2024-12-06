<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="nombre" :value="__('Nombre')" />
            <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $user->datos_generales->nombre)"
                required autofocus autocomplete="nombre" />
            <x-input-error class="mt-2" :messages="$errors->get('nombre')" />
        </div>

        <div>
            <x-input-label for="apellidoP" :value="__('Apellido Paterno')" />
            <x-text-input id="apellidoP" name="apellidoP" type="text" class="mt-1 block w-full" :value="old('apellidoP', $user->datos_generales->apellido_paterno)"
                autocomplete="apellidoP" />
            <x-input-error class="mt-2" :messages="$errors->get('apellidoP')" />
        </div>

        <div>
            <x-input-label for="apellidoM" :value="__('Apellido Materno')" />
            <x-text-input id="apellidoM" name="apellidoM" type="text" class="mt-1 block w-full" :value="old('apellidoM', $user->datos_generales->apellido_materno)"
                autocomplete="apellidoM" />
            <x-input-error class="mt-2" :messages="$errors->get('apellidoM')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="fecha_nacimiento" :value="__('Fecha de Nacimiento')" />
            <x-text-input id="fecha_nacimiento" name="fecha_nacimiento" type="date" class="mt-1 block w-full"
                :value="old('fecha_nacimiento', $user->datos_generales->fecha_nacimiento)" />
            <x-input-error class="mt-2" :messages="$errors->get('fecha_nacimiento')" />
        </div>

        <div>
            <x-input-label for="curp" :value="__('CURP')" />
            <x-text-input id="curp" name="curp" type="text" class="mt-1 block w-full" :value="old('curp', $user->datos_generales->curp)" />
            <x-input-error class="mt-2" :messages="$errors->get('curp')" />
        </div>

        <div>
            <x-input-label for="rfc" :value="__('RFC')" />
            <x-text-input id="rfc" name="rfc" type="text" class="mt-1 block w-full" :value="old('rfc', $user->datos_generales->rfc)" />
            <x-input-error class="mt-2" :messages="$errors->get('rfc')" />
        </div>

        <div>
            <x-input-label for="telefono" :value="__('Teléfono')" />
            <x-text-input id="telefono" name="telefono" type="tel" class="mt-1 block w-full" :value="old('telefono', $user->datos_generales->telefono)" />
            <x-input-error class="mt-2" :messages="$errors->get('telefono')" />
        </div>

        @if (in_array('Instructor', $user_roles))
            <div>
                <x-input-label for="cvu" :value="__('CVU (PDF)')" />

                @if ($user->instructor && $user->instructor->cvu)
                    <p class="text-sm text-gray-600">
                        {{ __('Archivo actual:') }}
                        <a href="{{ asset('uploads/' . $user->instructor->cvu) }}" target="_blank"
                            class="text-blue-600 underline">
                            {{ __('Ver CVU') }}
                        </a>
                    </p>
                @endif

                <input id="cvu" name="cvu" type="file" accept="application/pdf"
                    class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('cvu')" />
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
