<?php
// scripts/getParticipationStatus.php

function getParticipationStatus($event_id, $user_id) {
    include 'connection.php';

    // Verificar se o usuário já participou do evento
    $checkParticipation = "SELECT * FROM attendance WHERE event_id = $event_id AND user_id = $user_id";
    $result = $mysqli->query($checkParticipation);

    return ($result->num_rows > 0) ? 'vou' : 'nao_vou';
}
?>
