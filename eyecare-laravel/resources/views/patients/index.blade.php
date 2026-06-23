<x-app-layout>
    <div class="panel panel-headers">
        <div class="headermenu-title">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <h3 class="headerstitle">Patient List</h3>
        </div>
    </div>

    <div class="button-row">
        <form action="{{ route('patients.search') }}" method="GET" style="display:flex;gap:10px;width:100%;">
            <input type="text" name="q" placeholder="Search by name or phone..." value="{{ request('q') }}" class="search-form" style="width:40%;">
            <button type="submit" class="btn-search">Search</button>
            <a href="{{ route('patients.create') }}" class="btn-add">+ Add Patient</a>
        </form>
    </div>

    <div class="panel panel-categories">
        <table class="table table-bordered category-table">
            <thead>
                <tr>
                    <th class="table-success">Name</th>
                    <th class="table-success">Gender</th>
                    <th class="table-success">Birthdate</th>
                    <th class="table-success">Age</th>
                    <th class="table-success">Phone</th>
                    <th class="table-success">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                <tr>
                    <td>{{ $patient->full_name }}</td>
                    <td>{{ $patient->gender }}</td>
                    <td>{{ $patient->birthdate->format('M d, Y') }}</td>
                    <td>{{ $patient->age }}</td>
                    <td>{{ $patient->phone_number ?? '—' }}</td>
                    <td>
                        <a href="{{ route('patients.show', $patient) }}" class="btn-edit" style="text-transform:none;">View</a>
                        <a href="{{ route('patients.edit', $patient) }}" class="btn-edit" style="background-color:#ffc107;text-transform:none;">Edit</a>
                        <form action="{{ route('patients.destroy', $patient) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete {{ $patient->first_name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6">No patients found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($patients->hasPages())
    <div style="display:flex;justify-content:center;margin-top:20px;">
        {{ $patients->links() }}
    </div>
    @endif
</x-app-layout>
