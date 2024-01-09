<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'connection.php';

    $event_id = $_POST["event_id"];
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

    // Certifique-se de verificar se $_SESSION['user_id'] está definida antes de usá-la
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if ($user_id !== null) {
        // Verificar se o usuário já participou do evento
        $checkParticipation = "SELECT * FROM attendance WHERE event_id = $event_id AND user_id = $user_id";
        $result = $mysqli->query($checkParticipation);
    
        if ($result->num_rows == 0) {
            // Adicionar participação do usuário
            $status = $_POST["action"]; // Obtém o valor do botão pressionado
    
            $addParticipation = "INSERT INTO attendance (event_id, user_id, status) VALUES (?, ?, ?)";
            $stmt = $mysqli->prepare($addParticipation);
            $stmt->bind_param("iis", $event_id, $user_id, $status);
    
            $stmt->execute();
            $stmt->close();
        } else {
            // Usuário já participou, verificar se o status é diferente
            $existingStatus = $result->fetch_assoc()["status"];
            
            $newStatus = $_POST["action"]; // Obtém o valor do botão pressionado
    
            if ($existingStatus != $newStatus) {
                // Atualizar o status
                $updateStatus = "UPDATE attendance SET status = ? WHERE event_id = ? AND user_id = ?";
                $stmt = $mysqli->prepare($updateStatus);
                $stmt->bind_param("sii", $newStatus, $event_id, $user_id);
    
                $stmt->execute();
                $stmt->close();
            }
        }

        if (isset($referer)) {
            if (stripos($referer, 'shared_event.php') !== false) {
                // Redireciona para shared_event.php com o event_id
                header("Location: /eventos360/pages/shared_event.php?event_id=$event_id");
                exit();
            } elseif (stripos($referer, 'event.php') !== false) {
                header("Location: /eventos360/pages/event.php");
                exit();
            } else {
                // Referência desconhecida, redirecionar para uma página padrão ou exibir mensagem de erro
                header("Location: /eventos360/pages/error_page.php");
                exit();
            }
        } else {
            // Referência não está definida, redirecionar para uma página padrão ou exibir mensagem de erro
            header("Location: /eventos360/pages/error_page.php");
            exit();
        }
    } else {
        // Lógica de tratamento se $_SESSION['user_id'] não estiver definida
        echo "Erro: User not found.";
        // Pode redirecionar para a página de login ou fazer outra coisa conforme necessário
    }       
}

function getParticipantsCount($event_id) {
    include 'connection.php';

    $countParticipants = "SELECT COUNT(*) as count FROM attendance WHERE event_id = $event_id AND status = 'vou'";
    $result = $mysqli->query($countParticipants);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    return 0;
}
?>
