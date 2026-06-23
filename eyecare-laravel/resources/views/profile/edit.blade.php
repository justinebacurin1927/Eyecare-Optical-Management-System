<x-app-layout>
    <div class="panel panel-headers">
        <div class="headermenu-title">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <h3 class="headerstitle">{{ __('Profile') }}</h3>
        </div>
    </div>

    <div class="panel profile-section">
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="panel profile-section">
        @include('profile.partials.update-password-form')
    </div>

    <div class="panel profile-section">
        @include('profile.partials.delete-user-form')
    </div>
</x-app-layout>
