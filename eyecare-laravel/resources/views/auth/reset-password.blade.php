<x-guest-layout>
    <h2 style="font-size:28px;text-align:center;margin-bottom:25px;color:#000;">Reset Password</h2>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div style="margin-bottom:15px;">
            <x-input-label for="email" :value="__('Email')" style="color:#000;margin-bottom:5px;" />
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                   style="width:100%;padding:12px;background:rgb(247,247,249);border-radius:6px;border:none;outline:none;font-size:16px;color:#000;">
            <x-input-error :messages="$errors->get('email')" style="margin-top:5px;" />
        </div>

        <div style="margin-bottom:15px;">
            <x-input-label for="password" :value="__('Password')" style="color:#000;margin-bottom:5px;" />
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   style="width:100%;padding:12px;background:rgb(247,247,249);border-radius:6px;border:none;outline:none;font-size:16px;color:#000;">
            <x-input-error :messages="$errors->get('password')" style="margin-top:5px;" />
        </div>

        <div style="margin-bottom:20px;">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" style="color:#000;margin-bottom:5px;" />
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   style="width:100%;padding:12px;background:rgb(247,247,249);border-radius:6px;border:none;outline:none;font-size:16px;color:#000;">
            <x-input-error :messages="$errors->get('password_confirmation')" style="margin-top:5px;" />
        </div>

        <button type="submit"
                style="width:100%;padding:12px;background:rgb(110,172,218);border-radius:8px;border:none;cursor:pointer;font-size:16px;color:white;font-weight:500;margin-bottom:15px;transition:0.5s;"
                onmouseover="this.style.background='rgb(3,52,110)'"
                onmouseout="this.style.background='rgb(110,172,218)'">
            {{ __('Reset Password') }}
        </button>
    </form>
</x-guest-layout>
