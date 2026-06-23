<x-app-layout>
    <div class="panel panel-headers" style="margin-bottom:10px;">
        <div class="headermenu-title">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <h3 class="headerstitle">Products Panel</h3>
        </div>
    </div>

    <div class="tab" style="margin-top:10px;">
        <a href="javascript:void(0)" class="active" onclick="openTab(event, 'addTab')">Add Product</a>
        <a href="javascript:void(0)" onclick="openTab(event, 'listTab')">Product List</a>
        <a href="javascript:void(0)" onclick="openTab(event, 'framesTab')">Frames & Lens Types</a>
    </div>

    {{-- Add Product Tab --}}
    <div id="addTab" class="tabcontent" style="display:block;">
        <div class="panel">
            <form action="{{ route('products.store') }}" method="POST" class="inputtrans2">
                @csrf
                <div>
                    <label>Product Name</label>
                    <input type="text" name="name" required>
                </div>
                <div>
                    <label>Category</label>
                    <select name="category_id" required>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Quantity</label>
                    <input type="number" name="quantity" required min="0">
                </div>
                <div>
                    <label>Selling Price</label>
                    <input type="number" step="0.01" name="selling_price" required min="0">
                </div>
                <div>
                    <label>Discounted Price</label>
                    <input type="number" step="0.01" name="discounted_price" min="0">
                </div>
                <div>
                    <label>Reorder Level</label>
                    <input type="number" name="reorder_level" required min="0">
                </div>
                <div>
                    <label>Reorder Quantity</label>
                    <input type="number" name="reorder_quantity" required min="0">
                </div>
                <div style="grid-column:span 2;text-align:center;">
                    <button type="submit" class="itembtn2">Save Product</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Product List Tab --}}
    <div id="listTab" class="tabcontent">
        <div class="panel panel-categories">
            <table class="table table-bordered category-table">
                <thead>
                    <tr>
                        <th class="table-success">Name</th>
                        <th class="table-success">Category</th>
                        <th class="table-success">Qty</th>
                        <th class="table-success">Price</th>
                        <th class="table-success">Discounted</th>
                        <th class="table-success">Reorder</th>
                        <th class="table-success">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td style="{{ $product->quantity <= $product->reorder_level ? 'color:red;font-weight:bold;' : '' }}">{{ $product->quantity }}</td>
                        <td>₱{{ number_format($product->selling_price, 2) }}</td>
                        <td>{{ $product->discounted_price ? '₱'.number_format($product->discounted_price, 2) : '—' }}</td>
                        <td>{{ $product->reorder_level }}/{{ $product->reorder_quantity }}</td>
                        <td>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete {{ $product->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete">DELETE</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7">No products yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Frames & Lens Types Tab --}}
    <div id="framesTab" class="tabcontent">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
            <div class="panel">
                <h3 style="margin-bottom:15px;color:#333;">Frames</h3>
                <form action="{{ route('frames.store') }}" method="POST" class="inputtrans2" style="margin-bottom:20px;">
                    @csrf
                    <div>
                        <label>Frame Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div>
                        <label>Brand</label>
                        <input type="text" name="brand">
                    </div>
                    <div>
                        <label>Material</label>
                        <input type="text" name="material">
                    </div>
                    <div>
                        <label>Style</label>
                        <input type="text" name="style">
                    </div>
                    <div>
                        <label>Size</label>
                        <input type="text" name="size">
                    </div>
                    <div style="grid-column:span 2;text-align:center;">
                        <button type="submit" class="itembtn2">Add Frame</button>
                    </div>
                </form>

                <table class="table table-bordered" style="margin-top:10px;">
                    <thead>
                        <tr>
                            <th class="table-success">Name</th>
                            <th class="table-success">Brand</th>
                            <th class="table-success">Material</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($frames as $frame)
                        <tr>
                            <td>{{ $frame->name }}</td>
                            <td>{{ $frame->brand ?? '—' }}</td>
                            <td>{{ $frame->material ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3">No frames.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="panel">
                <h3 style="margin-bottom:15px;color:#333;">Lens Types</h3>
                <form action="{{ route('lens-types.store') }}" method="POST" class="inputtrans2" style="margin-bottom:20px;">
                    @csrf
                    <div>
                        <label>Lens Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div>
                        <label>Material</label>
                        <input type="text" name="material">
                    </div>
                    <div>
                        <label>Coating</label>
                        <input type="text" name="coating">
                    </div>
                    <div style="grid-column:span 2;text-align:center;">
                        <button type="submit" class="itembtn2">Add Lens Type</button>
                    </div>
                </form>

                <table class="table table-bordered" style="margin-top:10px;">
                    <thead>
                        <tr>
                            <th class="table-success">Name</th>
                            <th class="table-success">Material</th>
                            <th class="table-success">Coating</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lensTypes as $lens)
                        <tr>
                            <td>{{ $lens->name }}</td>
                            <td>{{ $lens->material ?? '—' }}</td>
                            <td>{{ $lens->coating ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3">No lens types.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function openTab(evt, tabName) {
            const tabcontents = document.getElementsByClassName('tabcontent');
            for (let i = 0; i < tabcontents.length; i++) {
                tabcontents[i].style.display = 'none';
            }
            const tablinks = document.querySelectorAll('.tab a');
            for (let i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(' active', '');
            }
            document.getElementById(tabName).style.display = 'block';
            evt.currentTarget.className += ' active';
        }
    </script>
    @endpush
</x-app-layout>
