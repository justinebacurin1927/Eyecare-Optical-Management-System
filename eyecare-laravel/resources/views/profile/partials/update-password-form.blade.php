<section>
    <header>
        <h3 style="margin-bottom:5px;color:#333;">{{ __('Update Password') }}</h3>
        <p style="color:#666;font-size:14px;margin-bottom:20px;">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div style="margin-bottom:15px;">
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <input id="update_password_current_password" name="current_password" type="password" style="width:100%;margin-top:5px;padding:8px;border:1px solid #ccc;border-radius:4px;" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div style="margin-bottom:15px;">
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <input id="update_password_password" name="password" type="password" style="width:100%;margin-top:5px;padding:8px;border:1px solid #ccc;border-radius:4px;" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div style="margin-bottom:15px;">
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" style="width:100%;margin-top:5px;padding:8px;border:1px solid #ccc;border-radius:4px;" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div style="display:flex;align-items:center;gap:10px;">
            <button type="submit" class="btn-add" style="min-width:auto;padding:8px 20px;">{{ __('Save') }}</button>

            @if (session('status') === 'password-updated')
                <p style="color:green;font-size:14px;">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
