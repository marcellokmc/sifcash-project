<?php

return [
    // Envoi d'email d'anniversaire (en plus de la notification in‑app)
    'email' => env('BIRTHDAY_EMAIL_ENABLED', true),

    // Heure d'envoi quotidienne (HH:MM)
    'send_time' => env('BIRTHDAY_SEND_TIME', '06:00'),
];
