<!-- remove_user.php -->
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}

include '../scripts/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['user_id']) && isset($_GET['event_id'])) {
    $userId = $_GET['user_id'];
    $eventId = $_GET['event_id'];

    // Remova o colaborador do evento na tabela de associação
    $sql_remove_user_from_event = "DELETE FROM event_users WHERE user_id = ? AND event_id = ?";
    $stmt_remove_user_from_event = $mysqli->prepare($sql_remove_user_from_event);
    $stmt_remove_user_from_event->bind_param("ii", $userId, $eventId);

    if ($stmt_remove_user_from_event->execute()) {
        // Colaborador removido com sucesso do evento
        header("Location: /eventos360/pages/remove_user.php?event_id=$eventId");
        exit();
    } else {
        // Erro ao remover colaborador do evento
        $_SESSION['error_message'] = "Erro ao remover colaborador do evento.";
    }

    // Feche o statement de remoção de colaborador do evento
    $stmt_remove_user_from_event->close();
} else {
    // Redirecionar para uma página de erro, se necessário
    header("Location: /eventos360/pages/error.php");
    exit();
}
?>
