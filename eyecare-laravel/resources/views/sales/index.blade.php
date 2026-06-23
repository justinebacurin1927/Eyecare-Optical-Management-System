<x-app-layout>
    <div class="panel panel-headers">
        <div class="headermenu-title">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
            </svg>
            <h3 class="headerstitle">Point of Sale</h3>
        </div>
    </div>

    <div class="tab">
        <a href="javascript:void(0)" class="active" onclick="openTab(event, 'newTab')">New Transaction</a>
        <a href="javascript:void(0)" onclick="openTab(event, 'listTab')">Sales List</a>
    </div>

    {{-- New Transaction Tab --}}
    <div id="newTab" class="tabcontent" style="display:block;">
        <div class="sales-panel">
            <h3>New Transaction</h3>
            <div x-data="pos()">
                <form @submit.prevent="submitTransaction" class="inputtrans2">
                    @csrf
                    <div>
                        <label style="color:white;">Patient</label>
                        <select name="patient_id" x-model="form.patient_id" required style="width:100%;padding:8px;border-radius:4px;border:1px solid #ccc;">
                            <option value="">Select Patient</option>
                            @foreach($patients as $patient)
                            <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="color:white;">Date</label>
                        <input type="date" name="transaction_date" x-model="form.transaction_date" required style="width:100%;padding:8px;border-radius:4px;border:1px solid #ccc;">
                    </div>
                    <div>
                        <label style="color:white;">Payment Status</label>
                        <select name="payment_status" x-model="form.payment_status" required style="width:100%;padding:8px;border-radius:4px;border:1px solid #ccc;">
                            <option value="Paid">Paid</option>
                            <option value="Pending">Pending</option>
                            <option value="Refunded">Refunded</option>
                        </select>
                    </div>
                    <div>
                        <label style="color:white;">Discount Amount</label>
                        <input type="number" step="0.01" x-model="form.discount_amount" @input="calcTotals" min="0" style="width:100%;padding:8px;border-radius:4px;border:1px solid #ccc;">
                    </div>
                </form>

                <div style="margin-top:20px;border-top:1px solid rgba(255,255,255,0.2);padding-top:20px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                        <h4 style="color:white;margin:0;">Order Items</h4>
                        <button type="button" @click="addItem" class="itembtn" style="margin:0;">+ Add Item</button>
                    </div>

                    <template x-for="(item, index) in form.items" :key="index">
                        <div style="display:grid;grid-template-columns:3fr 1fr 1fr 1fr 1fr;gap:10px;margin-bottom:8px;align-items:end;">
                            <div>
                                <label style="color:rgba(255,255,255,0.7);font-size:12px;">Product</label>
                                <select x-model="item.product_id" @change="updateProduct(index)" style="width:100%;padding:6px;border-radius:4px;border:1px solid #ccc;font-size:13px;">
                                    <option value="">Select</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-qty="{{ $product->quantity }}">{{ $product->name }} ({{ $product->quantity }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label style="color:rgba(255,255,255,0.7);font-size:12px;">Qty</label>
                                <input type="number" x-model="item.quantity" @input="calcItem(index)" min="1" style="width:100%;padding:6px;border-radius:4px;border:1px solid #ccc;font-size:13px;">
                            </div>
                            <div>
                                <label style="color:rgba(255,255,255,0.7);font-size:12px;">Price</label>
                                <input type="number" step="0.01" x-model="item.unit_price" @input="calcItem(index)" style="width:100%;padding:6px;border-radius:4px;border:1px solid #ccc;font-size:13px;">
                            </div>
                            <div>
                                <label style="color:rgba(255,255,255,0.7);font-size:12px;">Total</label>
                                <input type="text" x-model="item.total_price" readonly style="width:100%;padding:6px;border-radius:4px;border:1px solid #ccc;font-size:13px;background:#f0f0f0;">
                            </div>
                            <div>
                                <button type="button" @click="removeItem(index)" class="itembtn1" style="padding:6px 12px;margin:0;font-size:12px;">Remove</button>
                            </div>
                        </div>
                    </template>

                    <div style="display:flex;justify-content:flex-end;gap:30px;margin-top:20px;padding-top:15px;border-top:1px solid rgba(255,255,255,0.2);">
                        <div style="text-align:right;">
                            <p style="color:rgba(255,255,255,0.7);margin:0;font-size:14px;">Subtotal</p>
                            <p style="color:white;font-size:22px;font-weight:bold;margin:0;" x-text="'₱' + subtotal.toFixed(2)">₱0.00</p>
                        </div>
                        <div style="text-align:right;">
                            <p style="color:rgba(255,255,255,0.7);margin:0;font-size:14px;">Total</p>
                            <p style="color:#85abf2;font-size:28px;font-weight:bold;margin:0;" x-text="'₱' + total.toFixed(2)">₱0.00</p>
                        </div>
                    </div>

                    <div style="text-align:center;margin-top:20px;">
                        <button type="button" @click="submitTransaction" class="itembtn2" style="font-size:16px;padding:12px 30px;" :disabled="form.items.length === 0">
                            Complete Transaction
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sales List Tab --}}
    <div id="listTab" class="tabcontent">
        <div class="panel panel-categories">
            <table class="table table-bordered category-table">
                <thead>
                    <tr>
                        <th class="table-success">#</th>
                        <th class="table-success">Patient</th>
                        <th class="table-success">Date</th>
                        <th class="table-success">Total</th>
                        <th class="table-success">Discount</th>
                        <th class="table-success">Status</th>
                        <th class="table-success">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $sale)
                    <tr>
                        <td>{{ $sale->id }}</td>
                        <td>{{ $sale->patient->full_name }}</td>
                        <td>{{ $sale->transaction_date->format('M d, Y') }}</td>
                        <td>₱{{ number_format($sale->total_amount, 2) }}</td>
                        <td>₱{{ number_format($sale->discount_amount, 2) }}</td>
                        <td>
                            <span class="badge-custom {{ strtolower($sale->payment_status) }}">{{ $sale->payment_status }}</span>
                        </td>
                        <td>
                            <button class="btn-edit" data-bs-toggle="modal" data-bs-target="#editSaleModal-{{ $sale->id }}" style="text-transform:none;">Edit</button>
                            <form action="{{ route('sales.destroy', $sale) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete sale #{{ $sale->id }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-delete">Delete</button>
                            </form>
                        </td>
                    </tr>

                    {{-- Edit Sale Modal --}}
                    <div class="modal fade" id="editSaleModal-{{ $sale->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('sales.update', $sale) }}" method="POST">
                                @csrf @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Sale #{{ $sale->id }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <select name="patient_id" required style="width:100%;margin-bottom:10px;padding:8px;border:1px solid #ccc;border-radius:4px;">
                                            @foreach($patients as $patient)
                                            <option value="{{ $patient->id }}" {{ $sale->patient_id === $patient->id ? 'selected' : '' }}>{{ $patient->full_name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="date" name="transaction_date" value="{{ $sale->transaction_date->format('Y-m-d') }}" required style="width:100%;margin-bottom:10px;padding:8px;border:1px solid #ccc;border-radius:4px;">
                                        <input type="number" step="0.01" name="total_amount" value="{{ $sale->total_amount }}" required min="0" style="width:100%;margin-bottom:10px;padding:8px;border:1px solid #ccc;border-radius:4px;">
                                        <input type="number" step="0.01" name="discount_amount" value="{{ $sale->discount_amount }}" min="0" style="width:100%;margin-bottom:10px;padding:8px;border:1px solid #ccc;border-radius:4px;">
                                        <select name="payment_status" required style="width:100%;padding:8px;border:1px solid #ccc;border-radius:4px;">
                                            <option value="Paid" {{ $sale->payment_status === 'Paid' ? 'selected' : '' }}>Paid</option>
                                            <option value="Pending" {{ $sale->payment_status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Refunded" {{ $sale->payment_status === 'Refunded' ? 'selected' : '' }}>Refunded</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="7">No sales yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div style="display:flex;justify-content:center;margin-top:20px;">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>

    @push('scripts')
    <script>
        function pos() {
            return {
                form: {
                    patient_id: '',
                    transaction_date: new Date().toISOString().split('T')[0],
                    payment_status: 'Paid',
                    discount_amount: 0,
                    items: []
                },
                get subtotal() {
                    return this.form.items.reduce((sum, item) => sum + parseFloat(item.total_price || 0), 0);
                },
                get total() {
                    return Math.max(0, this.subtotal - parseFloat(this.form.discount_amount || 0));
                },
                addItem() {
                    this.form.items.push({ product_id: '', quantity: 1, unit_price: 0, total_price: 0 });
                },
                removeItem(index) {
                    this.form.items.splice(index, 1);
                },
                updateProduct(index) {
                    const select = event.target;
                    const option = select.options[select.selectedIndex];
                    if (option.value) {
                        this.form.items[index].unit_price = parseFloat(option.dataset.price || 0);
                        this.form.items[index].quantity = 1;
                        this.calcItem(index);
                    }
                },
                calcItem(index) {
                    const item = this.form.items[index];
                    item.total_price = (parseFloat(item.quantity || 0) * parseFloat(item.unit_price || 0)).toFixed(2);
                },
                submitTransaction() {
                    const formData = new FormData();
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('patient_id', this.form.patient_id);
                    formData.append('transaction_date', this.form.transaction_date);
                    formData.append('payment_status', this.form.payment_status);
                    formData.append('total_amount', this.total);
                    formData.append('discount_amount', this.form.discount_amount || 0);
                    formData.append('items', JSON.stringify(this.form.items));

                    fetch('{{ route("sales.store") }}', {
                        method: 'POST',
                        body: new URLSearchParams(formData),
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                    }).then(r => {
                        if (r.redirected) window.location.href = r.url;
                    });
                }
            }
        }
    </script>
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
