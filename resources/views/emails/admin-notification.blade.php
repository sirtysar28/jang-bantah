<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f6f9;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f9;padding:24px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px;width:100%;background-color:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.08);">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#1a5c31;padding:20px 28px;">
                            <span style="color:#ffffff;font-size:18px;font-weight:bold;">&#127968; JANG BANTAH</span><br>
                            <span style="color:#c8e6c9;font-size:12px;">Jaringan Bank Tanah &mdash; Pemda Halmahera Barat</span>
                        </td>
                    </tr>
                    {{-- Body --}}
                    <tr>
                        <td style="padding:28px;">
                            <h2 style="margin:0 0 16px;font-size:18px;color:#1f2937;">{{ $title }}</h2>
                            <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;">
                                @foreach($lines as $line)
                                    @if(is_array($line))
                                        <tr>
                                            <td style="padding:6px 0;font-size:14px;color:#374151;width:170px;">{{ $line['label'] ?? '' }}</td>
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
                                <div style="margin-top:24px;">
                                    <a href="{{ $actionUrl }}" style="display:inline-block;background-color:#1a5c31;color:#ffffff;text-decoration:none;font-size:14px;font-weight:bold;padding:12px 24px;border-radius:6px;">{{ $actionText }}</a>
                                </div>
                                <p style="margin:12px 0 0;font-size:12px;color:#6b7280;word-break:break-all;">{{ $actionUrl }}</p>
                            @endif
                        </td>
                    </tr>
                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#f9fafb;padding:16px 28px;border-top:1px solid #e5e7eb;">
                            <p style="margin:0;font-size:12px;color:#6b7280;">
                                Email ini dikirim otomatis oleh sistem JANG BANTAH. Jika Anda merasa menerima email ini secara keliru, abaikan saja.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
