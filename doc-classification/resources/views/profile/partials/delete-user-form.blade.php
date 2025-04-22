<section class="mb-4">
    <header>
        <h2 class="h4 text-white">
            {{ __('Delete Account') }}
        </h2>

        <p class="mb-3 text-gray-300">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        {{ __('Delete Account') }}
    </button>

    <!-- Modal -->
    <div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
    <form method="post" action="{{ route('profile.destroy') }}" class="modal-content p-4 bg-gray-800 text-white border border-gray-700">
        @csrf
        @method('delete')

        <div class="modal-header border-b border-gray-700">
            <h5 class="modal-title" id="confirmUserDeletionModalLabel">{{ __('Are you sure you want to delete your account?') }}</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">
            <p class="mb-3 text-gray-300">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mb-3">
                <label for="password" class="form-label visually-hidden text-white">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" class="form-control bg-gray-700 text-white border border-gray-600 @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}" />
                @error('password')
                    @php
                        $errorMessage = $message;
                        // Remove port number pattern like :587 or :anynumber
                        $errorMessage = preg_replace('/:\d+/', '', $errorMessage);
                        // Remove any IP address pattern (optional)
                        $errorMessage = preg_replace('/\b\d{1,3}(\.\d{1,3}){3}\b/', '', $errorMessage);
                        // Trim whitespace
                        $errorMessage = trim($errorMessage);
                    @endphp
                    <div class="invalid-feedback">{!! nl2br(e($errorMessage)) !!}</div>
                @enderror
            </div>
        </div>

                <div class="modal-footer border-t border-gray-700">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
                </div>
            </form>
        </div>
    </div>
</section>
