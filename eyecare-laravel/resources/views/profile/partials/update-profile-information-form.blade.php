<section>
    <header>
        <h3 style="margin-bottom:5px;color:#333;">{{ __('Profile Information') }}</h3>
        <p style="color:#666;font-size:14px;margin-bottom:20px;">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div style="margin-bottom:15px;">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" style="width:100%;margin-top:5px;padding:8px;border:1px solid #ccc;border-radius:4px;" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div style="margin-bottom:15px;">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" style="width:100%;margin-top:5px;padding:8px;border:1px solid #ccc;border-radius:4px;" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div style="display:flex;align-items:center;gap:10px;">
            <button type="submit" class="btn-add" style="min-width:auto;padding:8px 20px;">{{ __('Save') }}</button>

            @if (session('status') === 'profile-updated')
                <p style="color:green;font-size:14px;">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
