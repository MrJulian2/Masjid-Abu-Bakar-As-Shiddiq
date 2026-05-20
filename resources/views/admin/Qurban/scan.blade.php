@extends('admin.index')

@section('content')
    <div class="container">

        <h3>Scan QR Kupon</h3>

        <div id="reader" style="width: 300px;"></div>

        <form id="form-scan" method="POST" action="{{ route('qurban.scan.store') }}">
            @csrf
            <input type="hidden" name="qr_code" id="qr_code">
        </form>

    </div>
@section('script')
    <script src="https://unpkg.com/html5-qrcode"></script>
    {{-- sweet ALert Github --}}
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <script>
        let html5QrcodeScanner;

        function onScanSuccess(decodedText) {

            // ⛔ langsung STOP scanner begitu dapat QR
            html5QrcodeScanner.clear();

            fetch("{{ route('qurban.scan.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        qr_code: decodedText
                    })
                })
                .then(res => res.json())
                .then(data => {

                    console.log(data.status);

                    if (data.status === 'success') {

                        playBeep();

                        Swal.fire({
                            icon: "success",
                            title: "Kupon berhasil diambil!",
                            html: `
                                    <b>Nama:</b> ${data.nama}<br>
                                    <b>RT/RW:</b> ${data.rt}/${data.rw}<br>
                                    <b>Kode:</b> ${data.qr_code}
                                `,
                            timer: 3000,
                            showConfirmButton: false,
                        });
                    } else if (data.status === 'warning') {
                        playBeep();
                        Swal.fire({
                            icon: "warning",
                            title: "Kupon sudah diambil!",
                            timer: 1500,
                            showConfirmButton: false,
                            position: "center"
                        });
                    } else {
                        playBeep();
                        Swal.fire({
                            icon: "error",
                            title: "Kupon tidak valid!",
                            timer: 1500,
                            showConfirmButton: false,
                            position: "center"
                        });
                    }

                    // 🔥 restart scanner setelah 2 detik
                    setTimeout(() => {
                        startScanner();
                    }, 2000);

                })
                .catch(() => {

                    Swal.fire({
                        icon: "error",
                        title: "Server error!",
                        timer: 1500,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        startScanner();
                    }, 2000);
                });
        }

        function startScanner() {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", {
                    fps: 10,
                    qrbox: 250,
                    facingMode: "environment"
                }
            );

            html5QrcodeScanner.render(onScanSuccess);
        }

        // start pertama kali
        startScanner();
    </script>
    <script>
        let audioCtx = null;
        document.addEventListener('click', () => {
            if (!audioCtx) {
                audioCtx = new(window.AudioContext || window.webkitAudioContext)();
                audioCtx.resume();
            }
        });

        function playBeep() {

            if (!audioCtx) return;

            let oscillator = audioCtx.createOscillator();
            let gainNode = audioCtx.createGain();

            oscillator.connect(gainNode);
            gainNode.connect(audioCtx.destination);

            oscillator.type = "sine";
            oscillator.frequency.value = 1000;

            gainNode.gain.setValueAtTime(0.2, audioCtx.currentTime);

            oscillator.start();
            oscillator.stop(audioCtx.currentTime + 0.15);
        }
    </script>
@endsection
@endsection
