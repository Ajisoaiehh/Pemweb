<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parkir Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Dashboard Parkir</h1>
            <div>
                <span class="me-3">Selamat datang, {{ session('user') ? session('user')->NAMA : 'Guest' }}</span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            </div>
        </div>

        <!-- QR Code Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>QR Code Anda</h5>
            </div>
            <div class="card-body text-center">
                <div id="qrcode"></div>
                <p class="mt-2">Gunakan QR Code ini untuk scan masuk/keluar parkir</p>
                <button class="btn btn-primary" onclick="generateQR()">Generate QR Code</button>
            </div>
        </div>

        <!-- Current Parking Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Status Parkir Saat Ini</h5>
            </div>
            <div class="card-body">
                <div id="currentParkingStatus">
                    <p>Tidak ada kendaraan yang sedang parkir</p>
                </div>
                <button class="btn btn-success me-2" onclick="scanMasuk()">Scan Masuk</button>
                <button class="btn btn-warning" onclick="scanKeluar()">Scan Keluar</button>
            </div>
        </div>

        <!-- Parking History -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Riwayat Parkir</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID Parkir</th>
                            <th>Plat Nomor</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                            <th>Durasi</th>
                            <th>Biaya</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="parkingHistory">
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payment Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Pembayaran</h5>
            </div>
            <div class="card-body">
                <h6>Saldo Anda: <span id="saldo">Rp {{ number_format(session('user') ? session('user')->SALDO : 0, 0, ',', '.') }}</span></h6>
                <button class="btn btn-info me-2" onclick="topUpSaldo()">Top-Up Saldo</button>
                <button class="btn btn-secondary" onclick="riwayatTransaksi()">Riwayat Transaksi</button>
            </div>
        </div>

        <!-- Notifications -->
        <div class="card">
            <div class="card-header">
                <h5>Notifikasi</h5>
            </div>
            <div class="card-body">
                <div id="notifications" class="alert-container">
                    <!-- Notifications will appear here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Top-Up -->
    <div class="modal fade" id="topUpModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Top-Up Saldo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="topUpForm">
                        <div class="mb-3">
                            <label for="topUpAmount" class="form-label">Jumlah Top-Up</label>
                            <input type="number" class="form-control" id="topUpAmount" required>
                        </div>
                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="paymentMethod" required>
                                <option value="saldo">Saldo</option>
                                <option value="ovo">OVO</option>
                                <option value="dana">DANA</option>
                                <option value="gopay">GoPay</option>
                                <option value="transfer">Transfer Bank</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Top-Up</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let userId = {{ session('user') ? session('user')->ID_PENGGUNA : 'null' }};
        let userSaldo = {{ session('user') ? session('user')->SALDO : 0 }};
        let qrCodeData = userId ? `USER_${userId}_${Date.now()}` : '';

        if (!userId) {
            window.location.href = '{{ route("login") }}';
        }

        function generateQR() {
            const qrcodeContainer = document.getElementById('qrcode');
            qrcodeContainer.innerHTML = '';
            QRCode.toCanvas(qrcodeContainer, qrCodeData, { width: 200 }, function (error) {
                if (error) console.error(error);
            });
        }

        function scanMasuk() {
            // Simulate scan masuk
            fetch('/user/parkir', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    ID_PARKIR: Date.now(),
                    NO_PLAT: 'B1234XYZ',
                    ID_PENGGUNA: userId,
                    WAKTU_MASUK: new Date().toISOString(),
                    WAKTU_KELUAR: new Date(Date.now() + 3600000).toISOString(), // 1 hour later
                    BIAYA: 5000,
                    STATUS_PARKIR: 'Sedang Parkir'
                })
            })
            .then(response => response.json())
            .then(data => {
                showNotification('Berhasil masuk parkir!', 'success');
                loadCurrentParkingStatus();
                loadParkingHistory();
            })
            .catch(error => console.error('Error:', error));
        }

        function scanKeluar() {
            // Simulate scan keluar and payment
            fetch('/user/pembayaran', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    ID_PEMBAYARAN: Date.now(),
                    ID_PARKIR: 1, // Should be actual parking ID
                    METODE: 'saldo',
                    STATUS: 'Berhasil',
                    JUMLAH: 5000,
                    WAKTU_BAYAR: new Date().toISOString()
                })
            })
            .then(response => response.json())
            .then(data => {
                showNotification('Berhasil keluar parkir dan pembayaran!', 'success');
                loadCurrentParkingStatus();
                loadParkingHistory();
            })
            .catch(error => console.error('Error:', error));
        }

        function topUpSaldo() {
            const modal = new bootstrap.Modal(document.getElementById('topUpModal'));
            modal.show();
        }

        document.getElementById('topUpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const amount = document.getElementById('topUpAmount').value;
            const method = document.getElementById('paymentMethod').value;

            // Simulate top-up
            showNotification(`Top-up sebesar Rp ${amount} berhasil via ${method}!`, 'success');
            updateSaldo(amount);
            bootstrap.Modal.getInstance(document.getElementById('topUpModal')).hide();
        });

        function loadCurrentParkingStatus() {
            // Simulate loading current parking status
            document.getElementById('currentParkingStatus').innerHTML = '<p>Kendaraan B1234XYZ sedang parkir sejak ' + new Date().toLocaleString() + '</p>';
        }

        function loadParkingHistory() {
            // Simulate loading parking history
            const historyHtml = `
                <tr>
                    <td>1</td>
                    <td>B1234XYZ</td>
                    <td>2026-05-06 10:00:00</td>
                    <td>2026-05-06 11:00:00</td>
                    <td>1 jam</td>
                    <td>Rp 5.000</td>
                    <td>Selesai</td>
                </tr>
            `;
            document.getElementById('parkingHistory').innerHTML = historyHtml;
        }

        function updateSaldo(amount) {
            const currentSaldo = userSaldo;
            userSaldo += parseInt(amount);
            document.getElementById('saldo').textContent = 'Rp ' + userSaldo.toLocaleString('id-ID');
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show`;
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.getElementById('notifications').appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        function riwayatTransaksi() {
            // Simulate showing transaction history
            showNotification('Riwayat transaksi akan ditampilkan di halaman terpisah.', 'info');
        }

        // Initialize
        generateQR();
        loadCurrentParkingStatus();
        loadParkingHistory();
    </script>
</body>
</html>