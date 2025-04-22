<section>
    <header>
        <h2 class="h4 text-white">
            {{ __('Update Password') }}
        </h2>

        <p class="mb-3 text-gray-300">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label text-white">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control bg-gray-700 text-white border border-gray-600 @error('current_password') is-invalid @enderror" autocomplete="current-password" />
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label text-white">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-control bg-gray-700 text-white border border-gray-600 @error('password') is-invalid @enderror" autocomplete="new-password" />
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label text-white">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control bg-gray-700 text-white border border-gray-600 @error('password_confirmation') is-invalid @enderror" autocomplete="new-password" />
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-outline-primary">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <p class="text-gray-400 small mb-0" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
