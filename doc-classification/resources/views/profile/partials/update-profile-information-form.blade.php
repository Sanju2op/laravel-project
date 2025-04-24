<section>
    <header>
        <h2 class="text-white text-xl font-semibold">
            {{ __('Profile Information') }}
        </h2>

        <p class="mb-3 text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-4" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="mb-6 text-center">
            @if ($user->profile_photo_path)
                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" class="rounded-full mx-auto mb-4" style="width: 120px; height: 120px; object-fit: cover;">
            @else
                <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Photo" class="rounded-full mx-auto mb-4" style="width: 120px; height: 120px; object-fit: cover;">
            @endif
        </div>

        <div class="mb-4">
            <label for="profile_photo" class="block text-sm font-medium text-gray-300">{{ __('Profile Photo') }}</label>
            <input id="profile_photo" name="profile_photo" type="file" accept="image/*"
                class="mt-1 block w-full rounded-md border border-gray-600 bg-gray-900 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" />
            @error('profile_photo')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-300">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" required autofocus autocomplete="name"
                value="{{ old('name', $user->name) }}"
                class="mt-1 block w-full rounded-md border border-gray-600 bg-gray-900 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" />
            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-300">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" required autocomplete="username"
                value="{{ old('email', $user->email) }}"
                class="mt-1 block w-full rounded-md border border-gray-600 bg-gray-900 text-gray-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" />
            @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-red-500">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-indigo-400 hover:text-indigo-600">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm text-green-500">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p class="text-gray-400 text-sm" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
