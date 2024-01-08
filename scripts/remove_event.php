<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}

include 'connection.php';

// Check if a POST request was made
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Iterate through all POST variables
    foreach ($_POST as $key => $value) {
        // Check if the key starts with "remove_event_"
        if (strpos($key, "remove_event_") === 0) {
            // Extract the event ID from the key
            $event_id = substr($key, strlen("remove_event_"));

            // Additional validation if needed

            // Proceed with removing the event
            $user_id = $_SESSION['user_id'];
            $checkOwnership = "SELECT * FROM event WHERE event_id = $event_id AND user_id = $user_id";
            $result = $mysqli->query($checkOwnership);

            if ($result->num_rows > 0) {
                // Delete related records from event_users table
                $deleteEventUsers = "DELETE FROM event_users WHERE event_id = $event_id";
                if ($mysqli->query($deleteEventUsers)) {
                    // Remove the event
                    $deleteEvent = "DELETE FROM event WHERE event_id = $event_id";
                    if ($mysqli->query($deleteEvent)) {
                        // Event removed successfully, redirect to the events page
                        header("Location: /eventos360/pages/edit_event.php");
                        exit();
                    } else {
                        // Error removing the event
                        echo "Erro ao remover o evento.";
                    }
                } else {
                    // Error removing related records from event_users table
                    echo "Erro ao remover registros relacionados na tabela event_users.";
                }
            } else {
                // User is not the owner of the event
                echo "Você não tem permissão para remover este evento.";
            }
        }
    }
} else {
    // Invalid request method
    echo "Método de requisição inválido.";
}
?>
