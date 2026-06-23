<x-app-layout>
    <div class="panel panel-headers">
        <div class="headermenu-title">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
            <h3 class="headerstitle">Add Patient</h3>
        </div>
    </div>

    <div class="panel">
        <form action="{{ route('patients.store') }}" method="POST">
            @csrf
            <h3 style="margin-bottom:15px;color:#333;">Personal Information</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;">
                <div>
                    <label>First Name</label>
                    <input type="text" name="first_name" required>
                </div>
                <div>
                    <label>Middle Name</label>
                    <input type="text" name="middle_name">
                </div>
                <div>
                    <label>Last Name</label>
                    <input type="text" name="last_name" required>
                </div>
                <div>
                    <label>Birthdate</label>
                    <input type="date" name="birthdate" required onchange="document.getElementById('age').textContent = Math.floor((new Date() - new Date(this.value)) / 31557600000) || 0">
                </div>
                <div>
                    <label>Age</label>
                    <p id="age" style="margin-top:8px;color:#666;">Select birthdate</p>
                </div>
                <div>
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div>
                    <label>Phone Number</label>
                    <input type="text" name="phone_number">
                </div>
                <div style="grid-column:span 2;">
                    <label>Address</label>
                    <textarea name="address" rows="2"></textarea>
                </div>
            </div>

            <h3 style="margin:20px 0 15px;color:#333;">Prescription</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:15px;">
                <div>
                    <label>Sphere</label>
                    <input type="text" name="sphere">
                </div>
                <div>
                    <label>Cylinder</label>
                    <input type="text" name="cylinder">
                </div>
                <div>
                    <label>Axis</label>
                    <input type="text" name="axis">
                </div>
                <div>
                    <label>Addition</label>
                    <input type="text" name="addition">
                </div>
                <div>
                    <label>PD</label>
                    <input type="text" name="pd">
                </div>
                <div>
                    <label>Tint</label>
                    <input type="text" name="tint">
                </div>
                <div>
                    <label>Frame</label>
                    <select name="frame_id">
                        <option value="">Select Frame</option>
                        @foreach($frames as $frame)
                        <option value="{{ $frame->id }}">{{ $frame->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Lens Type</label>
                    <select name="lens_type_id">
                        <option value="">Select Lens Type</option>
                        @foreach($lensTypes as $lens)
                        <option value="{{ $lens->id }}">{{ $lens->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="text-align:center;margin-top:20px;">
                <button type="submit" class="btn-add" style="min-width:200px;">Register Patient</button>
            </div>
        </form>
    </div>
</x-app-layout>
