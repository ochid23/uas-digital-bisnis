<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Certificate Kehadiran - {{ $event->title }}</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #09090b; color: #f4f4f5; margin: 0; padding: 40px 20px;">
    <div style="max-w-600px; margin: 0 auto; background-color: #18181b; border: 1px solid #27272a; border-radius: 20px; padding: 40px; text-align: center;">
        
        <div style="display: inline-block; background-color: rgba(79, 70, 229, 0.2); border: 1px solid rgba(99, 102, 241, 0.4); width: 60px; height: 60px; border-radius: 16px; line-height: 60px; font-size: 24px; color: #818cf8; font-weight: bold; margin-bottom: 20px;">
            🎓
        </div>

        <h1 style="color: #ffffff; font-size: 24px; margin-bottom: 10px; font-weight: 800;">E-Certificate Kehadiran Resmi</h1>
        <p style="color: #a1a1aa; font-size: 14px; margin-top: 0; line-height: 1.6;">
            Selamat <strong>{{ $transaction->customer_name }}</strong>! Kehadiran Anda pada event <strong>{{ $event->title }}</strong> telah berhasil divalidasi.
        </p>

        <div style="background-color: #09090b; border: 1px solid #27272a; border-radius: 16px; padding: 24px; margin: 30px 0; text-align: left;">
            <p style="color: #818cf8; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; margin-top: 0; margin-bottom: 10px;">Detail Sertifikat</p>
            <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                <tr>
                    <td style="color: #71717a; padding: 6px 0;">Peserta:</td>
                    <td style="color: #ffffff; font-weight: bold; text-align: right;">{{ $transaction->customer_name }}</td>
                </tr>
                <tr>
                    <td style="color: #71717a; padding: 6px 0;">Event:</td>
                    <td style="color: #ffffff; font-weight: bold; text-align: right;">{{ $event->title }}</td>
                </tr>
                <tr>
                    <td style="color: #71717a; padding: 6px 0;">Tanggal Hadir:</td>
                    <td style="color: #ffffff; font-weight: bold; text-align: right;">{{ $transaction->attended_at ? $transaction->attended_at->format('d M Y, H:i') : now()->format('d M Y') }} WIB</td>
                </tr>
                <tr>
                    <td style="color: #71717a; padding: 6px 0;">No. Verifikasi:</td>
                    <td style="color: #fbbf24; font-family: monospace; font-weight: bold; text-align: right;">{{ $transaction->certificate_code }}</td>
                </tr>
            </table>
        </div>

        <a href="{{ $certificateUrl }}" style="display: inline-block; background-color: #4f46e5; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-weight: bold; font-size: 14px; shadow: 0 4px 14px rgba(79, 70, 229, 0.4);">
            📜 Lihat & Unduh E-Certificate
        </a>

        <p style="color: #71717a; font-size: 12px; margin-top: 30px;">
            Jika tombol tidak bekerja, buka tautan berikut di browser Anda:<br>
            <a href="{{ $certificateUrl }}" style="color: #818cf8; word-break: break-all;">{{ $certificateUrl }}</a>
        </p>

        <div style="border-top: 1px solid #27272a; margin-top: 40px; padding-top: 20px; font-size: 12px; color: #52525b;">
            &copy; 2026 AmikomEventHub • E-Certificate System. All rights reserved.
        </div>
    </div>
</body>
</html>
