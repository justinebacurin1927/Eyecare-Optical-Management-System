<x-guest-layout>
    <h2 style="font-size:28px;text-align:center;margin-bottom:25px;color:#000;">Verify Email</h2>

    <p style="margin-bottom:20px;font-size:14px;color:#000;text-align:center;">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </p>

    @if (session('status') == 'verification-link-sent')
        <p style="margin-bottom:20px;font-size:14px;color:green;text-align:center;">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}" style="margin-bottom:15px;">
        @csrf
        <button type="submit"
                style="width:100%;padding:12px;background:rgb(110,172,218);border-radius:8px;border:none;cursor:pointer;font-size:16px;color:white;font-weight:500;transition:0.5s;"
                onmouseover="this.style.background='rgb(3,52,110)'"
                onmouseout="this.style.background='rgb(110,172,218)'">
            {{ __('Resend Verification Email') }}
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                style="width:100%;padding:12px;background:#dc3545;border-radius:8px;border:none;cursor:pointer;font-size:16px;color:white;font-weight:500;transition:0.5s;">
            {{ __('Log Out') }}
        </button>
    </form>
</x-guest-layout>
