<?php
// scripts/getParticipationStatus.php

function getParticipationStatus($event_id, $user_id) {
    include 'connection.php';

    // Verificar se o usuário já participou do evento
    $checkParticipation = "SELECT status FROM attendance WHERE event_id = $event_id AND user_id = $user_id";
    $result = $mysqli->query($checkParticipation);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['status']; // Return the actual status from the database
    } else {
        return null; // If no row is found, assume the user is not participating
    }
}
?>
