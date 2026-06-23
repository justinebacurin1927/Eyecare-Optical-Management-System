<x-app-layout>
    <div class="panel panel-headers">
        <div class="headermenu-title">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <h3 class="headerstitle">Edit Patient</h3>
        </div>
    </div>

    <div class="panel">
        <form action="{{ route('patients.update', $patient) }}" method="POST">
            @csrf @method('PUT')
            <h3 style="margin-bottom:15px;color:#333;">Personal Information</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;">
                <div>
                    <label>First Name</label>
                    <input type="text" name="first_name" value="{{ $patient->first_name }}" required>
                </div>
                <div>
                    <label>Middle Name</label>
                    <input type="text" name="middle_name" value="{{ $patient->middle_name }}">
                </div>
                <div>
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="{{ $patient->last_name }}" required>
                </div>
                <div>
                    <label>Birthdate</label>
                    <input type="date" name="birthdate" value="{{ $patient->birthdate->format('Y-m-d') }}" required>
                </div>
                <div>
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="Male" {{ $patient->gender === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ $patient->gender === 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>
                <div>
                    <label>Phone</label>
                    <input type="text" name="phone_number" value="{{ $patient->phone_number }}">
                </div>
                <div style="grid-column:span 3;">
                    <label>Address</label>
                    <textarea name="address" rows="2">{{ $patient->address }}</textarea>
                </div>
            </div>

            <h3 style="margin:20px 0 15px;color:#333;">Prescription</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;">
                <div>
                    <label>Sphere</label>
                    <input type="text" name="sphere" value="{{ $patient->prescription->sphere ?? '' }}">
                </div>
                <div>
                    <label>Cylinder</label>
                    <input type="text" name="cylinder" value="{{ $patient->prescription->cylinder ?? '' }}">
                </div>
                <div>
                    <label>Axis</label>
                    <input type="text" name="axis" value="{{ $patient->prescription->axis ?? '' }}">
                </div>
                <div>
                    <label>Addition</label>
                    <input type="text" name="addition" value="{{ $patient->prescription->addition ?? '' }}">
                </div>
                <div>
                    <label>PD</label>
                    <input type="text" name="pd" value="{{ $patient->prescription->pd ?? '' }}">
                </div>
                <div>
                    <label>Tint</label>
                    <input type="text" name="tint" value="{{ $patient->prescription->tint ?? '' }}">
                </div>
                <div>
                    <label>Frame</label>
                    <select name="frame_id">
                        <option value="">Select Frame</option>
                        @foreach($frames as $frame)
                        <option value="{{ $frame->id }}" {{ ($patient->prescription->frame_id ?? '') == $frame->id ? 'selected' : '' }}>{{ $frame->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Lens Type</label>
                    <select name="lens_type_id">
                        <option value="">Select Lens Type</option>
                        @foreach($lensTypes as $lens)
                        <option value="{{ $lens->id }}" {{ ($patient->prescription->lens_type_id ?? '') == $lens->id ? 'selected' : '' }}>{{ $lens->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="text-align:center;margin-top:20px;display:flex;gap:10px;justify-content:center;">
                <a href="{{ route('patients.index') }}" class="btn-sort">Cancel</a>
                <button type="submit" class="btn-add" style="min-width:200px;">Update Patient</button>
            </div>
        </form>
    </div>
</x-app-layout>
