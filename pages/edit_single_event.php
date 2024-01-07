<!-- edit_single_event.php -->
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}

include '../scripts/connection.php';

// Verificar se o evento_id foi passado como parâmetro
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Obter dados do evento do banco de dados
    $sql_get_event = "SELECT * FROM event WHERE event_id = ?";
    $stmt_get_event = $mysqli->prepare($sql_get_event);
    $stmt_get_event->bind_param("i", $event_id);

    $stmt_get_event->execute();
    $result = $stmt_get_event->get_result();
    $event = $result->fetch_assoc();

    $stmt_get_event->close();

    // Verificar se o evento pertence ao usuário logado
    if ($event['user_id'] != $_SESSION['user_id']) {
        // O evento não pertence ao usuário logado, redirecionar para uma página de erro ou eventos
        header("Location: /eventos360/pages/error.php");
        exit();
    }
} else {
    // Parâmetro event_id ausente, redirecionar para uma página de erro ou eventos
    header("Location: /eventos360/pages/error.php");
    exit();
}

// Restante do código para a edição do evento...
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

        <section class="edit-event-section">
            <div class="container">
                <div class="edit-event-container">
                    <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);">Editar Evento</h1>
                    <!-- Botão para voltar -->
                    <a href="/eventos360/pages/edit_event.php" class="btn btn-primary" style="margin-bottom: 2vh">Voltar</a>
                    <!-- Formulário de edição de eventos -->
                    <form method="post" action="/eventos360/scripts/edit_event.php">
                        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

                        <!-- Botão para adicionar colaborador -->
                        <a href="/eventos360/pages/add_user.php?event_id=<?php echo $event_id; ?>" class="btn btn-primary">Adicionar Colaborador</a>

                        <!-- Botão para remover colaborador -->
                        <a href="/eventos360/pages/remove_user.php?event_id=<?php echo $event_id; ?>" class="btn btn-primary">Remover Colaborador</a>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold;" for="event_name">Nome</label>
                            <input type="text" id="event_name" name="event_name" class="form-control" value="<?php echo $event['name']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="event_description">Descrição</label>
                            <textarea id="event_description" name="event_description" class="form-control" rows="4" required><?php echo $event['description']; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="event_date">Data</label>
                            <input type="date" id="event_date" name="event_date" class="form-control" value="<?php echo $event['date']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="event_location">Localização</label>
                            <input type="text" id="event_location" name="event_location" class="form-control" value="<?php echo $event['location']; ?>" required>
                        </div>

                        <div class="form-group">
                            <button style="margin-top: 2vh;" type="submit" class="btn btn-primary">Guardar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
