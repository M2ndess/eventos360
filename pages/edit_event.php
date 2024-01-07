<!-- edit_event.php -->
<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}

include '../scripts/connection.php';
include '../scripts/participate_event.php';
include '../scripts/getParticipationStatus.php';

// Obter eventos do usuário logado
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM event WHERE user_id = $user_id";
$result = $mysqli->query($sql);
$userEvents = $result->fetch_all(MYSQLI_ASSOC);
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
                <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);">Meus Eventos</h1>

                <!-- Botão para voltar -->
                <a href="/eventos360/pages/event.php" class="btn btn-primary">Voltar</a>

                <!-- Mostrar lista de eventos do usuário -->
                <div class="event-list">
                    <?php if (!empty($userEvents)): ?>
                        <?php foreach ($userEvents as $event): ?>
                            <?php 
                                $event_id = $event['event_id'];
                                $status = getParticipationStatus($event_id, $user_id);
                            ?>
                            <div class="event">
                                <div class="event-details-box">
                                    <h3 class="text-white fw-bold"><?php echo "Nome: " . $event['name']; ?></h3>
                                    <p class="text-white fw-bold">Descrição: <?php echo $event['description']; ?></p>
                                    <p class="text-white fw-bold">Data: <?php echo $event['date']; ?></p>
                                    <p class="text-white fw-bold">Localização: <?php echo $event['location']; ?></p>
                                    
                                    <p class="text-white fw-bold">Participantes: <?php echo getParticipantsCount($event['event_id']); ?></p>

                                    <!-- Lista de colaboradores -->
                                    <?php
                                    // Consulta para obter colaboradores do evento
                                    $sql_collaborators = "SELECT user.username FROM user INNER JOIN event_users ON user.user_id = event_users.user_id WHERE event_users.event_id = ?";
                                    $stmt_collaborators = $mysqli->prepare($sql_collaborators);
                                    $stmt_collaborators->bind_param("i", $event_id);
                                    $stmt_collaborators->execute();
                                    $result_collaborators = $stmt_collaborators->get_result();

                                    // Array para armazenar os nomes dos colaboradores
                                    $collaborator_names = [];

                                    while ($collaborator = $result_collaborators->fetch_assoc()) {
                                        $collaborator_names[] = $collaborator['username'];
                                    }

                                    // Feche o statement de colaboradores
                                    $stmt_collaborators->close();

                                    // Exiba a lista de colaboradores apenas se houver algum
                                    if (!empty($collaborator_names)) {
                                        echo '<p class="text-white fw-bold">Colaboradores:</p>';
                                        $collaborators_string = implode(', ', $collaborator_names);
                                        echo '<p class="text-white">' . $collaborators_string . '</p>';
                                    }
                                    ?>

                                    <!-- Botão para participar ou cancelar participação no evento -->
                                    <form method="post" action="/eventos360/scripts/participate_event.php">
                                        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

                                        <a href="/eventos360/pages/edit_single_event.php?event_id=<?php echo $event_id; ?>" class="btn btn-primary">Editar</a>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-white fw-bold" style="text-align: center;">Você não possui eventos.</p>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
