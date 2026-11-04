<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f0f7ff;font-family:Arial,Helvetica,sans-serif;-webkit-font-smoothing:antialiased;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f0f7ff;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:12px;overflow:hidden;box-shadow:0 4px 18px rgba(0,42,92,.12);">
                    {{-- Aksen emas khas JANG BANTAH --}}
                    <tr>
                        <td style="background-color:#f0c040;font-size:0;line-height:0;height:6px;">&nbsp;</td>
                    </tr>
                    {{-- Header biru (senada hero halaman depan) + logo --}}
                    <tr>
                        <td bgcolor="#003d80" style="background:linear-gradient(135deg,#002a5c 0%,#004d99 100%);background-color:#003d80;padding:24px 28px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;">
                                <tr>
                                    <td width="72" style="vertical-align:middle;">
                                        <table role="presentation" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td bgcolor="#ffffff" style="background-color:#ffffff;padding:8px;border-radius:14px;">
                                                    <img src="{{ asset('favicon.png') }}" alt="Logo JANG BANTAH" width="52" height="52" style="display:block;width:52px;height:52px;border-radius:10px;border:0;">
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    <td style="vertical-align:middle;padding-left:16px;">
                                        <span style="color:#ffffff;font-size:19px;font-weight:bold;letter-spacing:.5px;">JANG BANTAH</span><br>
                                        <span style="color:#b3daff;font-size:12px;letter-spacing:.3px;">Jaringan Bank Tanah &mdash; Pemda Halmahera Barat</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    {{-- Body --}}
                    <tr>
                        <td style="padding:28px;">
                            <h2 style="margin:0 0 16px;font-size:18px;color:#002a5c;">{{ $title }}</h2>
                            <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;">
                                @foreach($lines as $line)
                                    @if(is_array($line))
                                        <tr>
                                            <td style="padding:6px 0;font-size:14px;color:#004d99;width:170px;font-weight:bold;">{{ $line['label'] ?? '' }}</td>
                                            <td style="padding:6px 0;font-size:14px;color:#111827;font-weight:bold;">: {{ $line['value'] ?? '' }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="2" style="padding:6px 0;font-size:14px;color:#374151;line-height:1.6;">{{ $line }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                            @if($actionUrl)
                                <div style="margin-top:26px;">
                                    <a href="{{ $actionUrl }}" style="display:inline-block;background-color:#f0c040;background:linear-gradient(135deg,#f0c040,#d4a020);color:#1a1a2e;text-decoration:none;font-size:14px;font-weight:bold;padding:13px 34px;border-radius:50px;">{{ $actionText }}</a>
                                </div>
                                <p style="margin:12px 0 0;font-size:12px;color:#6b7280;word-break:break-all;">{{ $actionUrl }}</p>
                            @endif
                        </td>
                    </tr>
                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#e6f2ff;padding:16px 28px;border-top:1px solid #b3daff;">
                            <p style="margin:0;font-size:12px;color:#4b6a92;line-height:1.6;">
                                Email ini dikirim otomatis oleh sistem <b style="color:#002a5c;">JANG BANTAH</b> &mdash; Jaringan Bank Tanah Pemda Halmahera Barat.<br>
                                Jika Anda merasa menerima email ini secara keliru, abaikan saja.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
