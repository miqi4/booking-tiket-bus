<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scanner Boarding - PO. AKAS</title>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Work Sans', sans-serif; }
        #reader { width: 100% !important; border: none !important; }
        #reader__dashboard_section_csr button {
            background-color: #004782 !important;
            color: white !important;
            padding: 8px 16px !important;
            border-radius: 8px !important;
            font-size: 14px !important;
        }
    </style>
</head>
<body class="bg-[#fcf9f3] min-h-screen">
    <header class="bg-[#004782] text-white p-4 shadow-md sticky top-0 z-10">
        <div class="max-w-md mx-auto flex justify-between items-center">
            <h1 class="font-bold text-lg">AKAS Boarding</h1>
            <span class="text-sm bg-white/20 px-2 py-1 rounded">{{ Auth::user()->name }}</span>
        </div>
    </header>

    <main class="max-w-md mx-auto p-4">
        <div id="loading-overlay" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center text-white">
            <div class="text-center">
                <div class="animate-spin rounded-full h-12 w-12 border-4 border-white border-t-transparent mx-auto mb-2"></div>
                <p>Memproses Tiket...</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-[#c2c6d2] overflow-hidden mb-4 relative">
            <div id="reader"></div>
        </div>

        <div id="result-container" class="hidden">
            <div id="result-status" class="p-4 rounded-xl mb-4 text-center font-bold"></div>
            <div class="bg-white rounded-xl shadow-sm border border-[#c2c6d2] p-4">
                <h2 class="text-[#424751] text-xs uppercase tracking-wider font-semibold mb-3">Informasi Penumpang</h2>
                <div class="space-y-3">
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500 text-sm">Nama</span>
                        <span id="p-name" class="font-semibold"></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500 text-sm">Kursi</span>
                        <span id="p-seat" class="font-bold text-[#004782]"></span>
                    </div>
                    <div class="flex justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500 text-sm">Rute</span>
                        <span id="p-route" class="text-sm text-right"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 text-sm">Waktu Scan</span>
                        <span id="p-time" class="text-sm"></span>
                    </div>
                </div>
            </div>
            <button onclick="resetScanner()" class="w-full mt-4 bg-[#004782] text-white py-3 rounded-xl font-semibold shadow-lg active:scale-95 transition-transform">Scan Lagi</button>
        </div>

        <div id="placeholder-info" class="text-center py-10 px-6">
            <div class="bg-gray-200 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                </svg>
            </div>
            <h3 class="text-gray-600 font-medium">Siap Memindai</h3>
            <p class="text-gray-400 text-sm mt-1">Arahkan kamera ke QR code tiket penumpang</p>
        </div>
    </main>

    <script>
        let html5QrCode = new Html5Qrcode("reader");
        const qrConfig = { fps: 15, qrbox: { width: 250, height: 250 } };
        let isProcessing = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;
            
            document.getElementById('loading-overlay').classList.remove('hidden');
            
            // Berikan getaran singkat jika perangkat mendukung
            if (navigator.vibrate) navigator.vibrate(100);

            html5QrCode.stop().then(() => {
                console.log("Scan Berhasil:", decodedText);
                processTicket(decodedText);
            }).catch(err => {
                console.warn("Gagal menghentikan scanner:", err);
                processTicket(decodedText);
            });
        }

        function processTicket(code) {
            fetch('{{ route("operator.boarding.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ ticket_code: code })
            })
            .then(async response => {
                const data = await response.json();
                if (!response.ok) {
                    throw data;
                }
                return data;
            })
            .then(data => {
                showResult(data);
            })
            .catch(error => {
                console.error("Error Processing Ticket:", error);
                showResult({ 
                    success: false, 
                    message: error.message || 'Tiket tidak valid atau masalah jaringan.',
                    passenger: error.passenger
                });
            })
            .finally(() => {
                isProcessing = false;
                document.getElementById('loading-overlay').classList.add('hidden');
            });
        }

        function showResult(response) {
            document.getElementById('placeholder-info').classList.add('hidden');
            const container = document.getElementById('result-container');
            const statusDiv = document.getElementById('result-status');
            
            container.classList.remove('hidden');
            
            if (response.success) {
                statusDiv.innerText = response.message;
                statusDiv.className = 'p-4 rounded-xl mb-4 text-center font-bold bg-[#bef19a] text-[#0a2100]';
                
                document.getElementById('p-name').innerText = response.data.passenger_name;
                document.getElementById('p-seat').innerText = response.data.seat_number;
                document.getElementById('p-route').innerText = response.data.route;
                document.getElementById('p-time').innerText = response.data.time;
                
                // Play success sound if needed
            } else {
                statusDiv.innerText = response.message;
                statusDiv.className = 'p-4 rounded-xl mb-4 text-center font-bold bg-[#ffdad6] text-[#93000a]';
                
                if (response.passenger) {
                    document.getElementById('p-name').innerText = response.passenger.name;
                    document.getElementById('p-seat').innerText = response.passenger.seat;
                } else {
                    document.getElementById('p-name').innerText = '-';
                    document.getElementById('p-seat').innerText = '-';
                }
                document.getElementById('p-route').innerText = '-';
                document.getElementById('p-time').innerText = '-';
            }
        }

        function resetScanner() {
            document.getElementById('result-container').classList.add('hidden');
            document.getElementById('placeholder-info').classList.remove('hidden');
            startScanner();
        }

        function startScanner() {
            html5QrCode.start({ facingMode: "environment" }, qrConfig, onScanSuccess);
        }

        startScanner();
    </script>
</body>
</html>
