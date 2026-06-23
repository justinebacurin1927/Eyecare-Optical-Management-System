<x-guest-layout>
    <h2 style="font-size:28px;text-align:center;margin-bottom:25px;color:#000;">Login</h2>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <input type="text" name="email" placeholder="EMAIL" value="{{ old('email') }}" required autofocus autocomplete="username"
               style="width:100%;padding:12px;background:rgb(247,247,249);border-radius:6px;border:none;outline:none;font-size:16px;color:#000;margin-bottom:20px;">

        <x-input-error :messages="$errors->get('email')" style="margin-bottom:10px;" />

        <input type="password" name="password" placeholder="PASSWORD" required autocomplete="current-password"
               style="width:100%;padding:12px;background:rgb(247,247,249);border-radius:6px;border:none;outline:none;font-size:16px;color:#000;margin-bottom:20px;">

        <x-input-error :messages="$errors->get('password')" style="margin-bottom:10px;" />

        <div style="display:flex;align-items:center;margin-bottom:20px;">
            <input id="remember_me" type="checkbox" name="remember" style="width:auto;margin-bottom:0;margin-right:8px;">
            <label for="remember_me" style="font-size:14px;color:#000;">{{ __('Remember me') }}</label>
        </div>

        <button type="submit" name="login"
                style="width:100%;padding:12px;background:rgb(110,172,218);border-radius:8px;border:none;cursor:pointer;font-size:16px;color:white;font-weight:500;margin-bottom:20px;transition:0.5s;"
                onmouseover="this.style.background='rgb(3,52,110)'"
                onmouseout="this.style.background='rgb(110,172,218)'">
            LOGIN
        </button>

        @if (Route::has('password.request'))
            <p style="font-size:14.5px;text-align:center;color:#000;">
                <a href="{{ route('password.request') }}" style="color:rgb(3,52,110);text-decoration:none;">{{ __('Forgot your password?') }}</a>
            </p>
        @endif
    </form>
</x-guest-layout>
