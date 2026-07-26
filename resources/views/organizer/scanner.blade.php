@extends('layouts.app')

@section('title', 'Check-in QR Scanner Panitia Hari-H')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <!-- Header Scanner -->
    <div class="mb-6 text-center">
        <div class="inline-flex items-center justify-center p-3 bg-indigo-500/10 text-indigo-400 rounded-2xl border border-indigo-500/20 mb-3">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
            </svg>
        </div>
        <h2 class="text-2xl font-black text-white">Scanner Penjaga Pintu Registrasi</h2>
        <p class="text-zinc-400 text-xs mt-1">Arahkan kamera HP ke QR Code E-Ticket peserta untuk verifikasi kehadiran & cegah kecurangan double entry.</p>
    </div>

    <!-- Container Kamera & Scanner -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-2xl mb-6">
        <!-- Area Kamera QR Scanner -->
        <div id="reader" class="w-full rounded-2xl overflow-hidden bg-black border border-zinc-800 min-h-[280px]"></div>

        <!-- Tombol Kontrol Kamera -->
        <div class="flex gap-3 mt-4">
            <button id="start-btn" onclick="startScanner()" class="flex-1 py-3 px-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold text-xs transition flex items-center justify-center gap-2">
                📷 Buka Kamera
            </button>
            <button id="stop-btn" onclick="stopScanner()" class="flex-1 py-3 px-4 bg-zinc-800 hover:bg-zinc-700 text-zinc-300 rounded-xl font-bold text-xs transition flex items-center justify-center gap-2" disabled>
                ⏹️ Hentikan Kamera
            </button>
        </div>
    </div>

    <!-- Manual Input Order ID (Fallback) -->
    <div class="bg-zinc-900 border border-zinc-800 rounded-3xl p-6 shadow-lg mb-8">
        <h3 class="text-xs font-black text-zinc-400 uppercase tracking-wider mb-3">Atau Input Kode Tiket Manual</h3>
        <form onsubmit="handleManualSubmit(event)" class="flex gap-2">
            <input type="text" id="manual-order-id" placeholder="Masukkan Order ID (contoh: TRX-xxx)" class="flex-1 bg-zinc-950 border border-zinc-800 rounded-xl px-4 py-3 text-sm text-white font-mono placeholder:text-zinc-600 focus:outline-none focus:border-indigo-500 transition">
            <button type="submit" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold text-xs transition">
                Verifikasi
            </button>
        </form>
    </div>

    <!-- Modal Status Respon Result -->
    <div id="result-modal" class="fixed inset-0 bg-black/80 backdrop-blur-md hidden items-center justify-center p-4 z-50">
        <div id="result-card" class="bg-zinc-900 border border-zinc-800 rounded-3xl p-8 max-w-md w-full text-center shadow-2xl transform transition-all scale-95">
            <!-- Icon Dynamic -->
            <div id="modal-icon-bg" class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border shadow-lg">
                <span id="modal-icon" class="text-3xl"></span>
            </div>

            <h3 id="modal-title" class="text-xl font-black text-white mb-2"></h3>
            <p id="modal-message" class="text-zinc-300 text-sm mb-6"></p>

            <!-- Card Participant Details -->
            <div id="participant-details" class="hidden bg-zinc-950/80 border border-zinc-800 rounded-2xl p-4 text-left mb-6 space-y-2 font-mono text-xs">
                <div class="flex justify-between border-b border-zinc-800/80 pb-2">
                    <span class="text-zinc-500">Nama Peserta:</span>
                    <span id="detail-name" class="font-bold text-white"></span>
                </div>
                <div class="flex justify-between border-b border-zinc-800/80 pb-2">
                    <span class="text-zinc-500">Order ID:</span>
                    <span id="detail-order" class="font-bold text-indigo-400"></span>
                </div>
                <div class="flex justify-between border-b border-zinc-800/80 pb-2">
                    <span class="text-zinc-500">Acara:</span>
                    <span id="detail-event" class="font-bold text-zinc-300"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-zinc-500">Waktu Check-in:</span>
                    <span id="detail-time" class="font-bold text-emerald-400"></span>
                </div>
            </div>

            <button onclick="closeModal()" class="w-full py-3.5 bg-zinc-800 hover:bg-zinc-700 text-white rounded-xl font-black text-sm transition">
                Tutup & Lanjut Scan Berikutnya
            </button>
        </div>
    </div>
</div>

<!-- Script HTML5 QR Code Scanner -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let html5QrcodeScanner = null;
    let isProcessing = false;

    // Web Audio Synthesizer untuk Beep Sound
    function playBeep(type) {
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);

            if (type === 'success') {
                osc.frequency.setValueAtTime(880, ctx.currentTime); // Pitch A5 (High Chirp)
                gain.gain.setValueAtTime(0.3, ctx.currentTime);
                osc.start();
                osc.stop(ctx.currentTime + 0.2);
            } else {
                osc.type = 'sawtooth';
                osc.frequency.setValueAtTime(220, ctx.currentTime); // Low Buzzer (Warning)
                gain.gain.setValueAtTime(0.4, ctx.currentTime);
                osc.start();
                osc.stop(ctx.currentTime + 0.5);
            }
        } catch (e) {
            console.log('Audio not supported');
        }
    }

    function startScanner() {
        if (!html5QrcodeScanner) {
            html5QrcodeScanner = new Html5Qrcode("reader");
        }

        const config = { fps: 10, qrbox: { width: 220, height: 220 } };

        html5QrcodeScanner.start(
            { facingMode: "environment" }, // Kamera Belakang Smartphone
            config,
            onScanSuccess
        ).then(() => {
            document.getElementById('start-btn').disabled = true;
            document.getElementById('stop-btn').disabled = false;
        }).catch(err => {
            alert('Tidak dapat mengakses kamera: ' + err);
        });
    }

    function stopScanner() {
        if (html5QrcodeScanner) {
            html5QrcodeScanner.stop().then(() => {
                document.getElementById('start-btn').disabled = false;
                document.getElementById('stop-btn').disabled = true;
            });
        }
    }

    function onScanSuccess(decodedText) {
        if (isProcessing) return;
        isProcessing = true;

        verifyCheckIn(decodedText);
    }

    function handleManualSubmit(e) {
        e.preventDefault();
        const code = document.getElementById('manual-order-id').value.trim();
        if (code) {
            verifyCheckIn(code);
        }
    }

    function verifyCheckIn(qrCodeText) {
        fetch('{{ route("organizer.checkin.scan") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ qr_code: qrCodeText })
        })
        .then(response => response.json())
        .then(data => {
            showModal(data);
        })
        .catch(err => {
            showModal({
                status: 'error',
                title: 'ERROR SISTEM',
                message: 'Gagal terhubung ke server verifikasi: ' + err,
                alert_type: 'invalid'
            });
        });
    }

    function showModal(data) {
        const modal = document.getElementById('result-modal');
        const iconBg = document.getElementById('modal-icon-bg');
        const icon = document.getElementById('modal-icon');
        const title = document.getElementById('modal-title');
        const message = document.getElementById('modal-message');
        const details = document.getElementById('participant-details');

        title.innerText = data.title || 'Respon Check-in';
        message.innerText = data.message || '';

        if (data.alert_type === 'success') {
            playBeep('success');
            iconBg.className = "w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border bg-emerald-500/10 text-emerald-400 border-emerald-500/30 shadow-emerald-500/10";
            icon.innerText = "✓";
            
            document.getElementById('detail-name').innerText = data.customer_name || '-';
            document.getElementById('detail-order').innerText = data.order_id || '-';
            document.getElementById('detail-event').innerText = data.event_title || '-';
            document.getElementById('detail-time').innerText = data.attended_at || '-';
            details.classList.remove('hidden');
        } else if (data.alert_type === 'already_used') {
            playBeep('error');
            iconBg.className = "w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border bg-rose-500/20 text-rose-400 border-rose-500/40 shadow-rose-500/20 animate-pulse";
            icon.innerText = "🚨";
            
            document.getElementById('detail-name').innerText = data.customer_name || '-';
            document.getElementById('detail-order').innerText = data.order_id || '-';
            document.getElementById('detail-event').innerText = data.event_title || '-';
            document.getElementById('detail-time').innerText = "SUDAH DIGUNAKAN (" + (data.attended_at || '-') + ")";
            details.classList.remove('hidden');
        } else {
            playBeep('error');
            iconBg.className = "w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 border bg-amber-500/10 text-amber-400 border-amber-500/30";
            icon.innerText = "⚠️";
            details.classList.add('hidden');
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        const modal = document.getElementById('result-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.getElementById('manual-order-id').value = '';
        isProcessing = false;
    }
</script>
@endsection
