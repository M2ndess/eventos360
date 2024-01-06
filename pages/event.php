<!-- events.php -->
<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}

include '../scripts/connection.php';

// Obter eventos do banco de dados
$sql = "SELECT * FROM event";
$result = $mysqli->query($sql);
$events = $result->fetch_all(MYSQLI_ASSOC);
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

        <section class="events-section">
            <div class="container">
                <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);">Eventos</h1>

                <!-- Mostrar lista de eventos -->
                <div class="event-list">
                    <?php foreach ($events as $event): ?>
                        <div class="event">
                            <h3><?php echo $event['title']; ?></h3>
                            <p><?php echo $event['description']; ?></p>
                            <p>Data: <?php echo $event['date']; ?></p>
                            <!-- Adicione mais detalhes conforme necessário -->

                            <!-- Adicione um botão para participar do evento, se necessário -->
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Botão para criar um novo evento -->
                <a href="/eventos360/pages/create_event.php" class="btn btn-primary">Criar Evento</a>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
