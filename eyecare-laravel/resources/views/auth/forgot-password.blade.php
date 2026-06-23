<x-guest-layout>
    <h2 style="font-size:28px;text-align:center;margin-bottom:25px;color:#000;">Forgot Password</h2>

    <p style="margin-bottom:20px;font-size:14px;color:#000;text-align:center;">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div style="margin-bottom:20px;">
            <x-input-label for="email" :value="__('Email')" style="color:#000;margin-bottom:5px;" />
            <input id="email" type="email" name="email" :value="old('email')" required autofocus
                   style="width:100%;padding:12px;background:rgb(247,247,249);border-radius:6px;border:none;outline:none;font-size:16px;color:#000;">
            <x-input-error :messages="$errors->get('email')" style="margin-top:5px;" />
        </div>

        <button type="submit"
                style="width:100%;padding:12px;background:rgb(110,172,218);border-radius:8px;border:none;cursor:pointer;font-size:16px;color:white;font-weight:500;margin-bottom:15px;transition:0.5s;"
                onmouseover="this.style.background='rgb(3,52,110)'"
                onmouseout="this.style.background='rgb(110,172,218)'">
            {{ __('Email Password Reset Link') }}
        </button>
    </form>
</x-guest-layout>
