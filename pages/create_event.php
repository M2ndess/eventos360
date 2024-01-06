<!-- create_event.php -->
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}

include '../scripts/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenha os dados do formulário
    $eventName = $_POST["event_name"];
    $eventDescription = $_POST["event_description"];
    $eventDate = $_POST["event_date"];
    $eventLocation = $_POST["event_location"];

    // Verifique se a data do evento é posterior à data atual
    $currentDate = date("Y-m-d");

    if (strtotime($eventDate) <= strtotime($currentDate)) {
        $errorMessage = "A data do evento deve ser posterior à data atual.";
        // Adicione a mensagem de erro em uma variável de sessão
        $_SESSION['error_message'] = $errorMessage;
        
        // Redirecione de volta para a página de criação de evento
        header("Location: /eventos360/pages/create_event.php");
        exit();
    }

    // Inserir o evento no banco de dados
    $sql_insert_event = "INSERT INTO event (name, description, date, location, user_id) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert_event = $mysqli->prepare($sql_insert_event);
    $stmt_insert_event->bind_param("ssssi", $eventName, $eventDescription, $eventDate, $eventLocation, $_SESSION['user_id']);    

    if ($stmt_insert_event->execute()) {
        // Evento criado com sucesso, redirecionar para a página de eventos
        header("Location: /eventos360/pages/event.php");
        exit();
    } else {
        // Erro ao criar o evento
        echo "Erro ao criar o evento.";
    }

    // Feche o statement de inserção
    $stmt_insert_event->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Meta tags necessárias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- Linkar CSS File -->
    <style>
        <?php
        $css = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/eventos360/assets/css/style.css');
        echo $css;
        ?>
    </style>
</head>

<body class="body-content">
    <div class="container">
        <?php include '../includes/header_logado.php'; ?>

        <section class="create-event-section">
            <div class="container">
                <div class="create-event-container">
                    <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);">Criar Evento</h1>

                    <!-- Formulário de criação de eventos -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <?php
                            if (isset($_SESSION['error_message'])) {
                                echo '<p style="color: white; font-weight: bold;" class="error">' . $_SESSION['error_message'] . '</p>';
                                unset($_SESSION['error_message']); // Limpar a mensagem de erro após exibição
                            }
                        ?>
                        <div class="form-group">
                            <label style="color: white; font-weight: bold;" for="event_name">Nome do Evento</label>
                            <input type="text" id="event_name" name="event_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="event_description">Descrição do Evento</label>
                            <textarea id="event_description" name="event_description" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="event_date">Data do Evento</label>
                            <input type="date" id="event_date" name="event_date" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="event_location">Localização do Evento</label>
                            <input type="text" id="event_location" name="event_location" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <button style="margin-top: 2vh;" type="submit" class="btn btn-primary">Criar Evento</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
