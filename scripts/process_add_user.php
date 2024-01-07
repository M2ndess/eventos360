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

    // Verificar se o usuário já está associado ao evento
    $sql_check_association = "SELECT * FROM event_users WHERE user_id = ? AND event_id = ?";
    $stmt_check_association = $mysqli->prepare($sql_check_association);
    $stmt_check_association->bind_param("ii", $userId, $eventId);
    $stmt_check_association->execute();
    $result_check_association = $stmt_check_association->get_result();

    if ($result_check_association->num_rows == 0) {
        // Verificar se o usuário e o evento existem
        $sql_check_user = "SELECT * FROM user WHERE user_id = ?";
        $stmt_check_user = $mysqli->prepare($sql_check_user);
        $stmt_check_user->bind_param("i", $userId);
        $stmt_check_user->execute();
        $result_check_user = $stmt_check_user->get_result();

        $sql_check_event = "SELECT * FROM event WHERE event_id = ?";
        $stmt_check_event = $mysqli->prepare($sql_check_event);
        $stmt_check_event->bind_param("i", $eventId);
        $stmt_check_event->execute();
        $result_check_event = $stmt_check_event->get_result();

        if ($result_check_user->num_rows > 0 && $result_check_event->num_rows > 0) {
            // O usuário ainda não está associado ao evento, então podemos adicioná-lo

            // Adicionar o usuário ao evento na tabela de associação
            $sql_add_user_to_event = "INSERT INTO event_users (user_id, event_id) VALUES (?, ?)";
            $stmt_add_user_to_event = $mysqli->prepare($sql_add_user_to_event);
            $stmt_add_user_to_event->bind_param("ii", $userId, $eventId);

            if ($stmt_add_user_to_event->execute()) {
                // Usuário adicionado com sucesso ao evento
                // Redirecionar de volta à página de criação de evento ou outra página apropriada
                header("Location: /eventos360/pages/add_user.php?event_id=$eventId");
                exit();
            } else {
                // Erro ao adicionar usuário ao evento
                $_SESSION['error_message'] = "Erro ao adicionar user ao evento.";
            }

            // Feche o statement de adição de utilizador ao evento
            $stmt_add_user_to_event->close();
        } else {
            // Usuário ou evento não encontrado
            $_SESSION['error_message'] = "Utilizador ou evento não encontrado.";
        }

        // Feche os statements de verificação de usuário e evento
        $stmt_check_user->close();
        $stmt_check_event->close();
    } else {
        // Usuário já está associado ao evento
        $_SESSION['error_message'] = "Utilizador já está associado a este evento.";
    }

    // Feche o statement de verificação de associação
    $stmt_check_association->close();
} else {
    // Redirecionar para uma página de erro, se necessário
    echo "erro";
    exit();
}
?>
