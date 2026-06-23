<x-app-layout>
    <div class="panel patient-info">
        <div class="headermenu-title" style="margin-bottom:0;">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <h3 class="headerstitle" style="font-size:22px;">{{ $patient->full_name }}</h3>
        </div>
    </div>

    <div class="button-row">
        <a href="{{ route('patients.edit', $patient) }}" class="btn-add">Edit</a>
        <a href="{{ route('patients.index') }}" class="btn-show">Back</a>
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="panel">
            <h3 style="margin-bottom:15px;color:#333;">Personal Information</h3>
            <div>
                <div class="detail-row">
                    <span class="detail-label">Full Name</span>
                    <span class="detail-value">{{ $patient->full_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Birthdate</span>
                    <span class="detail-value">{{ $patient->birthdate->format('M d, Y') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Age</span>
                    <span class="detail-value">{{ $patient->age }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Gender</span>
                    <span class="detail-value">{{ $patient->gender }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone</span>
                    <span class="detail-value">{{ $patient->phone_number ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Address</span>
                    <span class="detail-value">{{ $patient->address ?? '—' }}</span>
                </div>
            </div>
        </div>

        <div class="panel">
            <h3 style="margin-bottom:15px;color:#333;">Prescription</h3>
            @if($patient->prescription)
            <div>
                <div class="detail-row">
                    <span class="detail-label">Sphere</span>
                    <span class="detail-value">{{ $patient->prescription->sphere ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Cylinder</span>
                    <span class="detail-value">{{ $patient->prescription->cylinder ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Axis</span>
                    <span class="detail-value">{{ $patient->prescription->axis ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Addition</span>
                    <span class="detail-value">{{ $patient->prescription->addition ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">PD</span>
                    <span class="detail-value">{{ $patient->prescription->pd ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tint</span>
                    <span class="detail-value">{{ $patient->prescription->tint ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Frame</span>
                    <span class="detail-value">{{ $patient->prescription->frame->name ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Lens Type</span>
                    <span class="detail-value">{{ $patient->prescription->lensType->name ?? '—' }}</span>
                </div>
            </div>
            @else
            <p style="color:#666;">No prescription recorded.</p>
            @endif
        </div>
    </div>
</x-app-layout>
