<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Parkir Pintar - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <style>
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .btn-custom {
            border-radius: 25px;
            padding: 10px 20px;
            font-weight: 600;
        }
        .status-active {
            color: #28a745;
            font-weight: bold;
        }
        .status-inactive {
            color: #6c757d;
        }
        .loading {
            display: none;
        }
        .qr-container {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        .balance-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #dc3545;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .history-table th,
        .history-table td {
            vertical-align: middle;
            white-space: nowrap;
        }
        .history-table tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }
        .history-badge {
            border-radius: 999px;
            font-size: 0.82rem;
            padding: 0.35rem 0.75rem;
        }
        .history-no-data {
            min-height: 120px;
        }
        .history-col-duration {
            min-width: 120px;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-car"></i> Sistem Parkir Pintar
            </a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">
                    <i class="fas fa-user"></i> {{ session('user')['NAMA'] ?? 'Guest' }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <!-- Balance Card -->
            <div class="col-md-4 mb-4">
                <div class="card balance-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-wallet fa-3x mb-3"></i>
                        <h5>Saldo Anda</h5>
                        <h3 id="saldo">Rp {{ number_format(session('user')['SALDO'] ?? 0, 0, ',', '.') }}</h3>
                        <button class="btn btn-light btn-custom" onclick="topUpSaldo()">
                            <i class="fas fa-plus"></i> Top-Up
                        </button>
                    </div>
                </div>
            </div>

            <!-- QR Code Section -->
            <div class="col-md-8 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-qrcode"></i> QR Code Parkir</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="qr-container mb-3">
                            <div id="qrcode"></div>
                        </div>
                        <p class="text-muted mb-3">Gunakan QR Code ini untuk scan masuk/keluar parkir</p>
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-primary btn-custom" onclick="generateQR()">
                                <i class="fas fa-sync"></i> Generate QR
                            </button>
                            <button class="btn btn-success btn-custom" onclick="scanMasuk()">
                                <i class="fas fa-sign-in-alt"></i> Scan Masuk
                            </button>
                            <button class="btn btn-warning btn-custom" onclick="scanKeluar()">
                                <i class="fas fa-sign-out-alt"></i> Scan Keluar
                            </button>
                        </div>
                        <div id="qrStatus" class="mt-3"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Current Parking Status -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-car"></i> Status Parkir Saat Ini</h5>
                    </div>
                    <div class="card-body">
                        <div id="currentParkingStatus" class="text-center">
                            <div class="loading">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Memuat status parkir...</p>
                            </div>
                            <div id="parkingContent">
                                <i class="fas fa-parking fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Tidak ada kendaraan yang sedang parkir</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicles -->
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="fas fa-car-side"></i> Kendaraan Anda</h5>
                    </div>
                    <div class="card-body">
                        <div id="vehiclesList">
                            <div class="loading">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Memuat kendaraan...</p>
                            </div>
                        </div>
                        <button class="btn btn-primary btn-custom w-100 mt-3" onclick="addVehicle()">
                            <i class="fas fa-plus"></i> Tambah Kendaraan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Parking History -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-history"></i> Riwayat Parkir</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm history-table">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center"><i class="fas fa-hashtag"></i></th>
                                <th><i class="fas fa-car"></i> Plat Nomor</th>
                                <th><i class="fas fa-clock"></i> Masuk</th>
                                <th><i class="fas fa-clock"></i> Keluar</th>
                                <th class="history-col-duration"><i class="fas fa-hourglass-half"></i> Durasi</th>
                                <th><i class="fas fa-money-bill"></i> Biaya</th>
                                <th class="text-center"><i class="fas fa-info-circle"></i> Status</th>
                            </tr>
                        </thead>
                        <tbody id="parkingHistory">
                            <tr class="history-no-data">
                                <td colspan="7" class="text-center text-muted">
                                    <div class="loading">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2 mb-0">Memuat riwayat parkir...</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="card">
            <div class="card-header bg-warning text-dark position-relative">
                <h5 class="mb-0"><i class="fas fa-bell"></i> Notifikasi</h5>
                <span id="notificationBadge" class="notification-badge" style="display: none;">0</span>
            </div>
            <div class="card-body">
                <div id="notifications" class="alert-container">
                    <p class="text-muted mb-0">Belum ada notifikasi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Top-Up -->
    <div class="modal fade" id="topUpModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Top-Up Saldo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="topUpForm">
                        <div class="mb-3">
                            <label for="topUpAmount" class="form-label">Jumlah Top-Up (Rp)</label>
                            <input type="number" class="form-control" id="topUpAmount" min="10000" step="5000" required>
                            <div class="form-text">Minimum Rp 10.000</div>
                        </div>
                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label">Metode Pembayaran</label>
                            <select class="form-select" id="paymentMethod" required>
                                <option value="ovo">OVO</option>
                                <option value="dana">DANA</option>
                                <option value="gopay">GoPay</option>
                                <option value="transfer">Transfer Bank</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-credit-card"></i> Top-Up Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Add Vehicle -->
    <div class="modal fade" id="vehicleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-car"></i> Tambah Kendaraan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="vehicleForm">
                        <div class="mb-3">
                            <label for="noPlat" class="form-label">Nomor Plat</label>
                            <input type="text" class="form-control" id="noPlat" placeholder="B 1234 XYZ" required>
                        </div>
                        <div class="mb-3">
                            <label for="jenisKendaraan" class="form-label">Jenis Kendaraan</label>
                            <select class="form-select" id="jenisKendaraan" required>
                                <option value="Mobil">Mobil</option>
                                <option value="Motor">Motor</option>
                                <option value="Truk">Truk</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Simpan Kendaraan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let userId = {{ session('user')['ID_PENGGUNA'] ?? 'null' }};
        let userSaldo = {{ session('user')['SALDO'] ?? 0 }};
        let qrCodeData = '';
        let currentParkingId = null;
        let notifications = [];

        if (!userId) {
            window.location.href = '{{ route("login") }}';
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            generateQR();
            loadCurrentParkingStatus();
            loadParkingHistory();
            loadVehicles();
            loadBalance();
        });

        function generateQR() {
            const timestamp = Date.now();
            qrCodeData = `USER_${userId}_${timestamp}`;

            const qrcodeContainer = document.getElementById('qrcode');
            qrcodeContainer.innerHTML = '';

            QRCode.toCanvas(qrcodeContainer, qrCodeData, {
                width: 200,
                color: {
                    dark: '#000000',
                    light: '#FFFFFF'
                }
            }, function (error) {
                if (error) {
                    console.error('QR Code generation error:', error);
                    showNotification('Gagal generate QR Code', 'danger');
                } else {
                    document.getElementById('qrStatus').innerHTML =
                        '<small class="text-success"><i class="fas fa-check"></i> QR Code berhasil dibuat</small>';
                    showNotification('QR Code berhasil dibuat', 'success');
                }
            });
        }

        function scanMasuk() {
            if (!qrCodeData) {
                showNotification('Generate QR Code terlebih dahulu', 'warning');
                return;
            }

            showLoading('currentParkingStatus');

            fetch('{{ route("scan.masuk", ":qr_code") }}'.replace(':qr_code', qrCodeData), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    user_id: userId,
                    qr_data: qrCodeData
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentParkingId = data.parking_id;
                    showNotification('Berhasil masuk parkir!', 'success');
                    loadCurrentParkingStatus();
                    loadParkingHistory();
                } else {
                    showNotification(data.message || 'Gagal masuk parkir', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat scan masuk', 'danger');
            })
            .finally(() => {
                hideLoading('currentParkingStatus');
            });
        }

        function scanKeluar() {
            if (!currentParkingId) {
                showNotification('Tidak ada kendaraan yang sedang parkir', 'warning');
                return;
            }

            showLoading('currentParkingStatus');

            fetch('{{ route("scan.keluar", ":qr_code") }}'.replace(':qr_code', qrCodeData), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    parking_id: currentParkingId,
                    user_id: userId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(`Berhasil keluar parkir! Biaya: Rp ${data.cost.toLocaleString('id-ID')}`, 'success');
                    currentParkingId = null;
                    loadCurrentParkingStatus();
                    loadParkingHistory();
                    loadBalance();
                } else {
                    showNotification(data.message || 'Gagal keluar parkir', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan saat scan keluar', 'danger');
            })
            .finally(() => {
                hideLoading('currentParkingStatus');
            });
        }

        function loadCurrentParkingStatus() {
            showLoading('currentParkingStatus');

            fetch('/user/parkir')
            .then(response => response.json())
            .then(data => {
                const content = document.getElementById('parkingContent');
                const activeParking = data.find(p => p.ID_PENGGUNA === userId && p.STATUS_PARKIR === 'Sedang Parkir');

                if (activeParking) {
                    currentParkingId = activeParking.ID_PARKIR;
                    content.innerHTML = `
                        <i class="fas fa-car fa-3x text-success mb-3"></i>
                        <h5 class="status-active">Sedang Parkir</h5>
                        <p><strong>Plat Nomor:</strong> ${activeParking.NO_PLAT || 'N/A'}</p>
                        <p><strong>Waktu Masuk:</strong> ${new Date(activeParking.created_at).toLocaleString('id-ID')}</p>
                        <p><strong>Biaya Saat Ini:</strong> Rp ${calculateCurrentCost(activeParking.created_at).toLocaleString('id-ID')}</p>
                    `;
                } else {
                    currentParkingId = null;
                    content.innerHTML = `
                        <i class="fas fa-parking fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Tidak ada kendaraan yang sedang parkir</p>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading parking status:', error);
                document.getElementById('parkingContent').innerHTML = `
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <p class="text-muted">Gagal memuat status parkir</p>
                `;
            })
            .finally(() => {
                hideLoading('currentParkingStatus');
            });
        }

        function loadParkingHistory() {
            showLoading('parkingHistory');

            fetch('/user/parking-history')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('parkingHistory');

                if (data.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">Belum ada riwayat parkir</td></tr>';
                    return;
                }

                const historyHtml = data.map(parkir => {
                    const waktuMasuk = new Date(parkir.WAKTU_MASUK || parkir.created_at);
                    const waktuKeluar = parkir.WAKTU_KELUAR ? new Date(parkir.WAKTU_KELUAR) : null;
                    const durasi = waktuKeluar ? calculateDuration(waktuMasuk, waktuKeluar) : '-';
                    const statusClass = parkir.STATUS_PARKIR === 'Selesai' ? 'success' : 'warning';

                    return `
                        <tr>
                            <td>${parkir.ID_PARKIR}</td>
                            <td>${parkir.NO_PLAT || 'N/A'}</td>
                            <td>${waktuMasuk.toLocaleString('id-ID')}</td>
                            <td>${waktuKeluar ? waktuKeluar.toLocaleString('id-ID') : '-'}</td>
                            <td>${durasi}</td>
                            <td>Rp ${parkir.BIAYA ? parkir.BIAYA.toLocaleString('id-ID') : '0'}</td>
                            <td><span class="badge bg-${statusClass}">${parkir.STATUS_PARKIR}</span></td>
                        </tr>
                    `;
                }).join('');

                tbody.innerHTML = historyHtml;
            })
            .catch(error => {
                console.error('Error loading parking history:', error);
                document.getElementById('parkingHistory').innerHTML =
                    '<tr><td colspan="7" class="text-center text-danger">Gagal memuat riwayat parkir</td></tr>';
            })
            .finally(() => {
                hideLoading('parkingHistory');
            });
        }

        function loadVehicles() {
            showLoading('vehiclesList');

            fetch('/user/kendaraan')
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('vehiclesList');

                if (data.length === 0) {
                    container.innerHTML = '<p class="text-muted text-center">Belum ada kendaraan terdaftar</p>';
                    return;
                }

                const vehiclesHtml = data.map(vehicle => `
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <strong>${vehicle.NO_PLAT}</strong>
                            <br>
                            <small class="text-muted">${vehicle.JENIS_KENDARAAN}</small>
                        </div>
                        <span class="badge bg-${vehicle.STATUS_KENDARAAN === 'Aktif' ? 'success' : 'secondary'}">
                            ${vehicle.STATUS_KENDARAAN}
                        </span>
                    </div>
                `).join('');

                container.innerHTML = vehiclesHtml;
            })
            .catch(error => {
                console.error('Error loading vehicles:', error);
                document.getElementById('vehiclesList').innerHTML =
                    '<p class="text-danger text-center">Gagal memuat kendaraan</p>';
            })
            .finally(() => {
                hideLoading('vehiclesList');
            });
        }

        function loadBalance() {
            fetch('{{ route("api.balance") }}')
            .then(response => response.json())
            .then(data => {
                userSaldo = data.saldo;
                document.getElementById('saldo').textContent = 'Rp ' + userSaldo.toLocaleString('id-ID');
            })
            .catch(error => {
                console.error('Error loading balance:', error);
            });
        }

        function topUpSaldo() {
            const modal = new bootstrap.Modal(document.getElementById('topUpModal'));
            modal.show();
        }

        document.getElementById('topUpForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const amount = parseInt(document.getElementById('topUpAmount').value);
            const method = document.getElementById('paymentMethod').value;

            if (amount < 10000) {
                showNotification('Minimum top-up Rp 10.000', 'warning');
                return;
            }

            // Simulate top-up process
            showNotification(`Memproses top-up Rp ${amount.toLocaleString('id-ID')} via ${method}...`, 'info');

            setTimeout(() => {
                updateSaldo(amount);
                showNotification(`Top-up sebesar Rp ${amount.toLocaleString('id-ID')} berhasil via ${method}!`, 'success');
                bootstrap.Modal.getInstance(document.getElementById('topUpModal')).hide();
                document.getElementById('topUpForm').reset();
            }, 2000);
        });

        function addVehicle() {
            const modal = new bootstrap.Modal(document.getElementById('vehicleModal'));
            modal.show();
        }

        document.getElementById('vehicleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const noPlat = document.getElementById('noPlat').value.toUpperCase();
            const jenisKendaraan = document.getElementById('jenisKendaraan').value;

            fetch('/user/kendaraan', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    NO_PLAT: noPlat,
                    ID_PENGGUNA: userId,
                    JENIS_KENDARAAN: jenisKendaraan,
                    STATUS_KENDARAAN: 'Aktif'
                })
            })
            .then(response => response.json())
            .then(data => {
                showNotification('Kendaraan berhasil ditambahkan!', 'success');
                loadVehicles();
                bootstrap.Modal.getInstance(document.getElementById('vehicleModal')).hide();
                document.getElementById('vehicleForm').reset();
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Gagal menambahkan kendaraan', 'danger');
            });
        });

        function updateSaldo(amount) {
            userSaldo += amount;
            document.getElementById('saldo').textContent = 'Rp ' + userSaldo.toLocaleString('id-ID');
        }

        function calculateCurrentCost(startTime) {
            const start = new Date(startTime);
            const now = new Date();
            const hours = Math.max(1, Math.ceil((now - start) / (1000 * 60 * 60)));
            return hours * 5000; // Rp 5.000 per hour
        }

        function calculateDuration(startTime, endTime) {
            const diff = endTime - startTime;
            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            return `${hours}j ${minutes}m`;
        }

        function showLoading(elementId) {
            const element = document.getElementById(elementId);
            const loading = element.querySelector('.loading');
            const content = element.querySelector('#parkingContent') || element;
            if (loading) loading.style.display = 'block';
            if (content && content !== loading) content.style.display = 'none';
        }

        function hideLoading(elementId) {
            const element = document.getElementById(elementId);
            const loading = element.querySelector('.loading');
            const content = element.querySelector('#parkingContent') || element;
            if (loading) loading.style.display = 'none';
            if (content && content !== loading) content.style.display = 'block';
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show`;
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'danger' ? 'exclamation-triangle' : 'info-circle'}"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.getElementById('notifications').prepend(notification);

            // Update notification badge
            notifications.push({message, type, time: new Date()});
            updateNotificationBadge();

            setTimeout(() => {
                notification.remove();
                notifications = notifications.filter(n => n.time !== notification.time);
                updateNotificationBadge();
            }, 5000);
        }

        function updateNotificationBadge() {
            const badge = document.getElementById('notificationBadge');
            const count = notifications.length;
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        // Auto refresh data every 30 seconds
        setInterval(() => {
            loadCurrentParkingStatus();
            loadBalance();
        }, 30000);
    </script>
</body>
</html>