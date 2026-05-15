<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; background: #f9fafb; margin: 0; padding: 20px; }
        .wrap { max-width: 560px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 32px; border: 1px solid #e5e7eb; }
        h2 { font-size: 18px; color: #4f46e5; margin-top: 0; }
        .field { margin-bottom: 16px; }
        .label { font-size: 12px; text-transform: uppercase; color: #6b7280; margin-bottom: 4px; }
        .value { font-size: 15px; }
        .msg { background: #f3f4f6; border-radius: 8px; padding: 14px; font-size: 15px; line-height: 1.6; white-space: pre-wrap; }
        .footer { margin-top: 24px; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="wrap">
        <h2>Новое сообщение с сайта</h2>

        <div class="field">
            <div class="label">Имя</div>
            <div class="value">{{ $contactMessage->name }}</div>
        </div>

        <div class="field">
            <div class="label">Email</div>
            <div class="value"><a href="mailto:{{ $contactMessage->email }}">{{ $contactMessage->email }}</a></div>
        </div>

        <div class="field">
            <div class="label">Сообщение</div>
            <div class="msg">{{ $contactMessage->message }}</div>
        </div>

        <div class="footer">
            Отправлено {{ $contactMessage->created_at->format('d.m.Y H:i') }} · {{ config('app.url') }}
        </div>
    </div>
</body>
</html>
