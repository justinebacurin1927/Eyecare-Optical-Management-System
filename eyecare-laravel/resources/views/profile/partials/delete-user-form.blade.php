<section>
    <header>
        <h3 style="margin-bottom:5px;color:#333;">{{ __('Delete Account') }}</h3>
        <p style="color:#666;font-size:14px;margin-bottom:20px;">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button type="button" class="btn-delete" style="padding:8px 20px;" data-bs-toggle="modal" data-bs-target="#confirmUserDeletion">{{ __('Delete Account') }}</button>

    <div class="modal fade" id="confirmUserDeletion" tabindex="-1">
        <div class="modal-dialog">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Delete Account') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('Are you sure you want to delete your account?') }}</p>
                        <p style="margin:10px 0;font-size:13px;color:#666;">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}</p>
                        <input id="password" name="password" type="password" placeholder="{{ __('Password') }}" style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;" required>
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Delete Account') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
