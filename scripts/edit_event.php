<!-- edit_event.php -->
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}

include '../scripts/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenha os dados do formulário
    $event_id = $_POST["event_id"];
    $eventName = $_POST["event_name"];
    $eventDescription = $_POST["event_description"];
    $eventDate = $_POST["event_date"];
    $eventLocation = $_POST["event_location"];
    $eventCategory = $_POST["event_category"];

    // Verificar se a data do evento é posterior à data atual
    $currentDate = date("Y-m-d");

    if (strtotime($eventDate) <= strtotime($currentDate)) {
        $errorMessage = "A data do evento deve ser posterior à data atual.";
        $_SESSION['error_message'] = $errorMessage;
        
        // Redirecione de volta para a página de edição de evento
        header("Location: /eventos360/pages/edit_single_event.php?event_id=$event_id");
        exit();
    }

    // Atualizar o evento no banco de dados
    $sql_update_event = "UPDATE event SET name = ?, description = ?, date = ?, location = ?, category_id = ? WHERE event_id = ?";
    $stmt_update_event = $mysqli->prepare($sql_update_event);
    $stmt_update_event->bind_param("ssssii", $eventName, $eventDescription, $eventDate, $eventLocation, $eventCategory, $event_id);

    if ($stmt_update_event->execute()) {
        // Evento atualizado com sucesso, redirecionar para a página de eventos
        header("Location: /eventos360/pages/edit_event.php");
        exit();
    } else {
        // Erro ao atualizar o evento
        echo "Erro ao atualizar o evento.";
    }

    // Feche o statement de atualização
    $stmt_update_event->close();
}
?>
