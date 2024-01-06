<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connection.php';

    $event_id = $_POST["event_id"];

    // Certifique-se de verificar se $_SESSION['user_id'] está definida antes de usá-la
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if ($user_id !== null) {
        // Verificar se o usuário já participou do evento
        $checkParticipation = "SELECT * FROM attendance WHERE event_id = $event_id AND user_id = $user_id";
        $result = $mysqli->query($checkParticipation);
        $status = "vou";

        if ($result->num_rows == 0) {
            // Adicionar participação do usuário
            $addParticipation = "INSERT INTO attendance (event_id, user_id, status) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($addParticipation);
            $stmt->bind_param("iss", $event_id, $user_id, $status);

            $stmt->execute();
            $stmt->close();
        } else {
            // Remover participação do usuário
            $removeParticipation = "DELETE FROM attendance WHERE event_id = ? AND user_id = ?";
            $stmt = $mysqli->prepare($removeParticipation);
            $stmt->bind_param("ii", $event_id, $user_id);

            $stmt->execute();
            $stmt->close();
        }

        header("Location: /eventos360/pages/event.php");
        exit();
    } else {
        // Lógica de tratamento se $_SESSION['user_id'] não estiver definida
        echo "Erro: User not found.";
        // Pode redirecionar para a página de login ou fazer outra coisa conforme necessário
    }
}

function getParticipantsCount($event_id) {
    include 'connection.php';

    $countParticipants = "SELECT COUNT(*) as count FROM attendance WHERE event_id = $event_id";
    $result = $mysqli->query($countParticipants);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    return 0;
}
?>
