<!-- shared_event.php -->
<?php
session_start();

include '../scripts/connection.php';
include '../scripts/participate_event.php';
include '../scripts/getParticipationStatus.php';

// Recuperar o ID do evento da consulta GET
$eventId = isset($_GET['event_id']) ? $_GET['event_id'] : null;

// Verificar se o ID do evento é válido (pode incluir validações adicionais conforme necessário)
if (!$eventId) {
    // Redirecionar se o ID do evento não estiver presente
    header("Location: /eventos360/pages/event.php");
    exit();
}

// Consulta SQL para obter detalhes do evento compartilhado
$sql_event_details = "SELECT * FROM event WHERE event_id = ?";
$stmt_event_details = $mysqli->prepare($sql_event_details);
$stmt_event_details->bind_param("i", $eventId);
$stmt_event_details->execute();
$result_event_details = $stmt_event_details->get_result();

// Verificar se o evento existe
if ($result_event_details->num_rows > 0) {
    // Extrair detalhes do evento
    $eventDetails = $result_event_details->fetch_assoc();
    $categoryId = $eventDetails['category_id'];

    // Consulta SQL para obter o nome da categoria
    $sql_category_name = "SELECT name FROM category WHERE category_id = ?";
    $stmt_category_name = $mysqli->prepare($sql_category_name);
    $stmt_category_name->bind_param("i", $categoryId);
    $stmt_category_name->execute();
    $stmt_category_name->bind_result($categoryName);
    $stmt_category_name->fetch();
    $stmt_category_name->close();
} else {
    // Redirecionar se o evento não for encontrado
    header("Location: /eventos360/pages/events.php");
    exit();
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

        <section class="events-section">
            <div class="container">
                <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);">Detalhes do Evento Compartilhado</h1>

                <!-- Mostrar detalhes do evento compartilhado -->
                <div class="event-details">
                    <div class="event-details-box">
                        <h3 class="text-white fw-bold"><?php echo "Nome: " . $eventDetails['name']; ?></h3>
                        <p class="text-white fw-bold">Descrição: <?php echo $eventDetails['description']; ?></p>
                        <p class="text-white fw-bold">Data: <?php echo $eventDetails['date']; ?></p>
                        <p class="text-white fw-bold">Localização: <?php echo $eventDetails['location']; ?></p>
                        <p class="text-white fw-bold">Categoria: <?php echo $categoryName; ?></p>
                        <p class="text-white fw-bold">Participantes: <?php echo getParticipantsCount($eventId); ?></p>

                        <!-- Lista de colaboradores -->
                        <?php
                        // Consulta para obter colaboradores do evento compartilhado
                        $sql_collaborators = "SELECT user.username FROM user INNER JOIN event_users ON user.user_id = event_users.user_id WHERE event_users.event_id = ?";
                        $stmt_collaborators = $mysqli->prepare($sql_collaborators);
                        $stmt_collaborators->bind_param("i", $eventId);
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
                            echo '<p class="text-white fw-bold">Colaboradores: ' . implode(', ', $collaborator_names) . '</p>';
                        }
                        ?>

                        <!-- Botões para participar ou cancelar participação no evento -->
                        <form method="post" action="/eventos360/scripts/participate_event.php">
                            <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">

                            <?php
                            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
                            $status = getParticipationStatus($eventId, $user_id);
                            ?>

                            <?php if ($status === 'vou'): ?>
                                <button type="submit" name="action" value="nao_vou" class="btn btn-danger">Não vou</button>
                                <button type="submit" name="action" value="com_interesse" class="btn btn-info" <?php echo ($status === 'com_interesse') ? 'disabled' : ''; ?>>Com interesse</button>
                            <?php elseif ($status === 'com_interesse'): ?>
                                <button type="submit" name="action" value="vou" class="btn btn-success">Eu vou</button>
                                <button type="submit" name="action" value="nao_vou" class="btn btn-danger">Não vou</button>
                                <button type="submit" name="action" value="com_interesse" class="btn btn-info" <?php echo ($status === 'com_interesse') ? 'disabled' : ''; ?>>Com interesse</button>
                            <?php  elseif ($status === 'nao_vou'): ?>
                                <button type="submit" name="action" value="vou" class="btn btn-success">Eu vou</button>
                                <button type="submit" name="action" value="com_interesse" class="btn btn-info" <?php echo ($status === 'com_interesse') ? 'disabled' : ''; ?>>Com interesse</button>
                            <?php  else: ?>
                                <button type="submit" name="action" value="vou" class="btn btn-success">Eu vou</button>
                                <button type="submit" name="action" value="com_interesse" class="btn btn-info" <?php echo ($status === 'com_interesse') ? 'disabled' : ''; ?>>Com interesse</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
