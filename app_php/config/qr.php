<?php

function booking_qr_code(int $id_prenotazione): string
{
    return "EVT-" . $id_prenotazione;
}

function booking_qr_url(int $id_prenotazione): string
{
    return "http://localhost:5000/static/qrcodes/" . booking_qr_code($id_prenotazione) . ".png";
}

function request_booking_qr(int $id_prenotazione, int $id_utente, int $id_evento): void
{
    $payload = [
        "user_id" => $id_utente,
        "event_id" => $id_evento,
        "booking_id" => $id_prenotazione,
        "booking_code" => booking_qr_code($id_prenotazione)
    ];

    $context = stream_context_create([
        "http" => [
            "method" => "POST",
            "header" => "Content-Type: application/json\r\n",
            "content" => json_encode($payload),
            "timeout" => 2
        ]
    ]);

    @file_get_contents("http://localhost:5000/api/qrcode", false, $context);
}

?>
