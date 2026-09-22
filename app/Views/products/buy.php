<?php
/** @var array $product */
$errors = session()->getFlashdata('errors') ?? [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Beli Produk</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="container">
    <div class="card">
        <h2>Beli: <?= esc($product['product_name']) ?></h2>
        <div class="subtitle">Stok tersedia: <?= esc($product['qty_in_stock']) ?></div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($product['qty_in_stock'] == 0): ?>
            <div class="alert alert-error">Stok produk ini habis, tidak bisa dibeli.</div>
            <a href="/products" class="btn btn-secondary">Kembali</a>
        <?php else: ?>
        <form id="buyForm" action="/products/processBuy/<?= $product['product_id'] ?>" method="post" data-price="<?= (float) $product['price'] ?>">
            <label>Jumlah</label>
            <input type="number" id="qty" name="qty" min="1" max="<?= $product['qty_in_stock'] ?>" value="<?= esc(old('qty') ?? 1) ?>" required>

            <div class="price-preview">
                Total: <strong id="totalPrice">Rp 0</strong>
            </div>

            <label>Metode Pembayaran</label>
            <select id="payment_method" name="payment_method" required>
                <option value="">- Pilih -</option>
                <option value="cash" <?= old('payment_method') == 'cash' ? 'selected' : '' ?>>Cash</option>
                <option value="transfer" <?= old('payment_method') == 'transfer' ? 'selected' : '' ?>>Transfer</option>
                <option value="qris" <?= old('payment_method') == 'qris' ? 'selected' : '' ?>>QRIS</option>
            </select>

            <div class="form-actions">
                <button type="submit" class="btn" id="beliBtn">Beli</button>
                <a href="/products">Batal</a>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>

<div class="modal-overlay" id="cashModal">
    <div class="modal-box">
        <h3>Pembayaran Cash</h3>
        <p>Total belanja: <strong id="cashModalTotal">Rp 0</strong></p>

        <label>Uang Diterima</label>
        <input type="number" id="cashInput" min="0" step="1000" placeholder="Masukkan jumlah uang">

        <div class="change-row">
            <span>Kembalian:</span>
            <strong id="changeAmount">Rp 0</strong>
        </div>
        <div id="cashError" class="field-error" style="display:none;">Uang yang dimasukkan kurang dari total belanja.</div>

        <div class="form-actions">
            <button type="button" class="btn" id="confirmCashBtn" disabled>Bayar</button>
            <a href="#" id="cancelCashBtn">Batal</a>
        </div>
    </div>
</div>

<div class="modal-overlay" id="successModal">
    <div class="modal-box modal-box-center">
        <div class="success-icon">&#10003;</div>
        <h3>Pembelian Berhasil</h3>
        <p id="successMessage">Transaksi kamu sudah tercatat.</p>
        <button type="button" class="btn" id="successOkBtn">OK</button>
    </div>
</div>

<script>
const form = document.getElementById('buyForm');

if (form) {
    const price = parseFloat(form.dataset.price);
    const qtyInput = document.getElementById('qty');
    const totalPriceEl = document.getElementById('totalPrice');
    const paymentSelect = document.getElementById('payment_method');
    const beliBtn = document.getElementById('beliBtn');

    function formatRupiah(num) {
        return 'Rp ' + Math.max(0, Math.round(num)).toLocaleString('id-ID');
    }

    function currentTotal() {
        const qty = parseInt(qtyInput.value) || 0;
        return price * qty;
    }

    function updateTotal() {
        totalPriceEl.textContent = formatRupiah(currentTotal());
    }
    qtyInput.addEventListener('input', updateTotal);
    updateTotal();

    const cashModal = document.getElementById('cashModal');
    const cashInput = document.getElementById('cashInput');
    const changeAmountEl = document.getElementById('changeAmount');
    const cashModalTotal = document.getElementById('cashModalTotal');
    const confirmCashBtn = document.getElementById('confirmCashBtn');
    const cashError = document.getElementById('cashError');
    const cancelCashBtn = document.getElementById('cancelCashBtn');

    const successModal = document.getElementById('successModal');
    const successMessage = document.getElementById('successMessage');
    const successOkBtn = document.getElementById('successOkBtn');

    let redirectAfterSuccess = '/products';

    function openCashModal() {
        cashInput.value = '';
        changeAmountEl.textContent = formatRupiah(0);
        cashModalTotal.textContent = formatRupiah(currentTotal());
        confirmCashBtn.disabled = true;
        cashError.style.display = 'none';
        cashModal.classList.add('open');
        cashInput.focus();
    }

    function closeCashModal() {
        cashModal.classList.remove('open');
    }

    cashInput.addEventListener('input', function () {
        const cash = parseFloat(cashInput.value) || 0;
        const total = currentTotal();
        const change = cash - total;
        changeAmountEl.textContent = formatRupiah(change);

        if (cash >= total && cash > 0) {
            confirmCashBtn.disabled = false;
            cashError.style.display = 'none';
        } else {
            confirmCashBtn.disabled = true;
            cashError.style.display = cash > 0 ? 'block' : 'none';
        }
    });

    cancelCashBtn.addEventListener('click', function (e) {
        e.preventDefault();
        closeCashModal();
    });

    function setLoading(isLoading) {
        beliBtn.disabled = isLoading;
        beliBtn.textContent = isLoading ? 'Memproses...' : 'Beli';
    }

    function submitPurchase() {
        setLoading(true);
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData,
        })
            .then((res) => res.json().then((data) => ({ status: res.status, data })))
            .then(({ data }) => {
                setLoading(false);
                if (data.success) {
                    redirectAfterSuccess = data.redirect || '/products';
                    successMessage.textContent = data.message || 'Transaksi kamu sudah tercatat.';
                    successModal.classList.add('open');
                } else {
                    const msg = data.errors ? Object.values(data.errors).join('\n') : (data.message || 'Terjadi kesalahan.');
                    alert(msg);
                }
            })
            .catch(() => {
                setLoading(false);
                alert('Gagal menghubungi server, coba lagi.');
            });
    }

    confirmCashBtn.addEventListener('click', function () {
        closeCashModal();
        submitPurchase();
    });

    successOkBtn.addEventListener('click', function () {
        window.location.href = redirectAfterSuccess;
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!paymentSelect.value) {
            alert('Pilih metode pembayaran dulu.');
            return;
        }

        if (paymentSelect.value === 'cash') {
            openCashModal();
        } else {
            submitPurchase();
        }
    });
}
</script>
</body>
</html>