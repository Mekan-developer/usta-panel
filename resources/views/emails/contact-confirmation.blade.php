<!DOCTYPE html>
<html lang="{{ $contactMessage->locale }}">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; background: #f9fafb; margin: 0; padding: 20px; }
        .wrap { max-width: 560px; margin: 0 auto; background: #fff; border-radius: 12px; padding: 32px; border: 1px solid #e5e7eb; }
        .greeting { font-size: 20px; font-weight: bold; margin-bottom: 16px; }
        .text { font-size: 15px; line-height: 1.7; color: #374151; }
        .highlight { display: inline-block; margin-top: 20px; background: #eef2ff; color: #4f46e5; border-radius: 8px; padding: 12px 18px; font-size: 15px; font-weight: 600; }
        .footer { margin-top: 28px; font-size: 12px; color: #9ca3af; border-top: 1px solid #f3f4f6; padding-top: 16px; }
    </style>
</head>
<body>
    <div class="wrap">

        @if($contactMessage->locale === 'tk')
            <div class="greeting">Salam, {{ $contactMessage->name }}!</div>
            <p class="text">
                Hatyňyz üstünlikli alyndy. Ähli maglumatlaryňyz hasaba alyndy.
            </p>
            <div class="highlight">Men siziň bilen ýakyn günlerde habarlaşaryn!</div>
            <p class="text" style="margin-top:16px;">Minnetdarlyk!</p>

        @elseif($contactMessage->locale === 'en')
            <div class="greeting">Hello, {{ $contactMessage->name }}!</div>
            <p class="text">
                Your message has been successfully received. All your details have been noted.
            </p>
            <div class="highlight">I will contact you in the coming days!</div>
            <p class="text" style="margin-top:16px;">Thank you!</p>

        @else
            <div class="greeting">Привет, {{ $contactMessage->name }}!</div>
            <p class="text">
                Ваше сообщение успешно получено. Все ваши данные зафиксированы.
            </p>
            <div class="highlight">Я свяжусь с вами в ближайшие дни!</div>
            <p class="text" style="margin-top:16px;">Спасибо!</p>
        @endif

        <div class="footer">
            {{ config('app.url') }}
        </div>
    </div>
</body>
</html>
