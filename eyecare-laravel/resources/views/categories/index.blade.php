<x-app-layout>
    <div class="panel panel-headers">
        <div class="headermenu-title">
            <svg class="headerslogo" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            <h3 class="headerstitle">Categories Panel</h3>
        </div>
    </div>

    <div class="button-row">
        <input type="text" id="searchInput" class="search-form" placeholder="Search for ID or Name">
        <button type="button" class="btn-search" onclick="searchCategories()">Search</button>
        <a href="{{ route('categories.index') }}" class="btn-show">Show All</a>
        <button type="button" class="btn-add" data-bs-toggle="modal" data-bs-target="#addModal">Add Category</button>
    </div>

    <div class="panel panel-categories">
        <table class="table table-bordered category-table">
            <thead>
                <tr>
                    <th class="table-success">ID</th>
                    <th class="table-success">Category Name</th>
                    <th class="table-success">Description</th>
                    <th class="table-success">Products</th>
                    <th class="table-success">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description ?? '—' }}</td>
                    <td>{{ $category->products_count }}</td>
                    <td>
                        <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editModal"
                            data-category-id="{{ $category->id }}"
                            data-category-name="{{ $category->name }}"
                            data-description="{{ $category->description }}">EDIT</button>

                        @if($category->products_count === 0)
                        <button class="btn-delete" data-bs-toggle="modal" data-bs-target="#deleteModal"
                            data-category-id="{{ $category->id }}">DELETE</button>
                        @else
                        <span class="btn-delete" style="opacity:0.5;cursor:not-allowed;" title="Has {{ $category->products_count }} product(s)">DELETE</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5">No categories found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" placeholder="Category Name" required>
                        <textarea name="description" placeholder="Description" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="" method="POST" id="editForm">
                @csrf @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" id="editCategoryName" required>
                        <textarea name="description" id="editDescription" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="" method="POST" id="deleteForm">
                @csrf @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this category?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        const editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-category-id');
            const name = button.getAttribute('data-category-name');
            const desc = button.getAttribute('data-description');
            document.getElementById('editForm').action = '{{ url("categories") }}/' + id;
            document.getElementById('editCategoryName').value = name;
            document.getElementById('editDescription').value = desc;
        });

        const deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-category-id');
            document.getElementById('deleteForm').action = '{{ url("categories") }}/' + id;
        });

        function searchCategories() {
            const q = document.getElementById('searchInput').value;
            if (q) {
                window.location.href = '{{ route("categories.index") }}?search=' + encodeURIComponent(q);
            }
        }
    </script>
    @endpush
</x-app-layout>
