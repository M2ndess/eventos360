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
        // Obter o ID do evento recém-inserido
        $eventId = $stmt_update_event->insert_id;

        // Processar e salvar a imagem
        if ($_FILES['event_image']['error'] == UPLOAD_ERR_OK) {
            $imageTmpName = $_FILES['event_image']['tmp_name'];
            $imageName = uniqid('event_image_') . '.' . pathinfo($_FILES['event_image']['name'], PATHINFO_EXTENSION);
            $imageDestination = $_SERVER['DOCUMENT_ROOT'] . '/eventos360/uploads/' . $imageName;

            if (move_uploaded_file($imageTmpName, $imageDestination)) {
                // Inserir a informação da imagem no banco de dados
                $sql_insert_image = "INSERT INTO images (url) VALUES (?)";
                $stmt_insert_image = $mysqli->prepare($sql_insert_image);
                $stmt_insert_image->bind_param("s", $imageName);
                $stmt_insert_image->execute();

                // Obter o ID da imagem recém-inserida
                $imageId = $stmt_insert_image->insert_id;

                // Atualizar a entrada do evento com o ID da imagem
                $sql_update_event = "UPDATE event SET image_id = ? WHERE event_id = ?";
                $stmt_update_event = $mysqli->prepare($sql_update_event);
                $stmt_update_event->bind_param("ii", $imageId, $eventId);

                // Execute a atualização do evento
                $stmt_update_event->execute();
                
                // Fechar o statement de atualização
                $stmt_update_event->close();

                // Fechar o statement de inserção da imagem
                $stmt_insert_image->close();
            } else {
                // Tratar erro no upload da imagem
                $errorMessage = "Erro ao fazer upload da imagem.";
                $_SESSION['error_message'] = $errorMessage;
                header("Location: /eventos360/pages/edit_event.php");
                exit();
            }
        }

        // Evento criado com sucesso, redirecionar para a página de eventos
        header("Location: /eventos360/pages/event.php");
        exit();
    } else {
        // Erro ao criar o evento
        echo "Erro ao criar o evento.";
    }

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
