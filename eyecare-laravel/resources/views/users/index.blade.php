<x-app-layout>
    <div class="panel panel-headers">
        <div class="headermenu-title">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
            </svg>
            <h3 class="headerstitle">User Management</h3>
        </div>
    </div>

    <button class="btn btn-success btn-add-user" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>

    <div class="panel panel-categories">
        <table class="table table-bordered user-table">
            <thead class="table-light">
                <tr>
                    <th class="table-success">Username</th>
                    <th class="table-success">Email</th>
                    <th class="table-success">Role</th>
                    <th class="table-success">Status</th>
                    <th class="table-success">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->username ?? $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge-custom {{ strtolower($user->role) }}">{{ $user->role }}</span>
                    </td>
                    <td>
                        <a href="{{ route('users.toggle-status', $user) }}" class="btn-status {{ $user->status ? 'active' : 'inactive' }}" onclick="event.preventDefault();document.getElementById('toggle-form-{{ $user->id }}').submit();">
                            {{ $user->status ? 'Active' : 'Inactive' }}
                        </a>
                        <form id="toggle-form-{{ $user->id }}" action="{{ route('users.toggle-status', $user) }}" method="POST" style="display:none;">@csrf @method('PATCH')</form>
                    </td>
                    <td>
                        <button class="btn-edit"
                            data-bs-toggle="modal"
                            data-bs-target="#editUserModal"
                            data-user-id="{{ $user->id }}"
                            data-username="{{ $user->username ?? $user->name }}"
                            data-email="{{ $user->email }}"
                            data-role="{{ $user->role }}"
                            data-status="{{ $user->status ? 'Active' : 'Inactive' }}">
                            Edit
                        </button>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this user?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Add User Modal --}}
    <div class="modal fade" id="addUserModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" placeholder="Full Name" required>
                        <input type="text" name="username" placeholder="Username">
                        <input type="email" name="email" placeholder="Email" required>
                        <input type="password" name="password" placeholder="Password" required minlength="8">
                        <select name="role" required>
                            <option value="">Select Role</option>
                            <option value="Admin">Admin</option>
                            <option value="Staff">Staff</option>
                            <option value="Doctor">Doctor</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit User Modal --}}
    <div class="modal fade" id="editUserModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="" method="POST" id="editUserForm">
                @csrf @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" id="editName" placeholder="Full Name" required>
                        <input type="text" name="username" id="editUsername" placeholder="Username">
                        <input type="email" name="email" id="editEmail" placeholder="Email" required>
                        <select name="role" id="editRole" required>
                            <option value="Admin">Admin</option>
                            <option value="Staff">Staff</option>
                            <option value="Doctor">Doctor</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editModal = document.getElementById('editUserModal');
            editModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-user-id');
                document.getElementById('editUserForm').action = '{{ url("users") }}/' + id;
                document.getElementById('editName').value = button.getAttribute('data-username');
                document.getElementById('editUsername').value = button.getAttribute('data-username');
                document.getElementById('editEmail').value = button.getAttribute('data-email');
                document.getElementById('editRole').value = button.getAttribute('data-role');
            });
        });
    </script>
    @endpush
</x-app-layout>
