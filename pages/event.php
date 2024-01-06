<!-- events.php -->
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
                
                <!-- Botão para criar um novo evento -->
                <a href="/eventos360/pages/create_event.php" class="btn btn-primary">Criar Evento</a>

                <!-- Botão para criar editar evento -->
                <a href="/eventos360/pages/edit_event.php" class="btn btn-primary">Editar Eventos</a>

                <!-- Mostrar lista de eventos -->
                <div class="event-list">
                    <?php foreach ($events as $event): ?>
                        <?php 
                            $event_id = $event['event_id'];
                            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
                            $status = getParticipationStatus($event_id, $user_id);
                        ?>
                        <div class="event">
                            <div class="event-details-box">
                                <h3 class="text-white fw-bold"><?php echo "Nome: " . $event['name']; ?></h3>
                                <p class="text-white fw-bold">Descrição: <?php echo $event['description']; ?></p>
                                <p class="text-white fw-bold">Data: <?php echo $event['date']; ?></p>
                                <p class="text-white fw-bold">Localização: <?php echo $event['location']; ?></p>
                                
                                <p class="text-white fw-bold">Participantes: <?php echo getParticipantsCount($event['event_id']); ?></p>

                                <!-- Botão para participar ou cancelar participação no evento -->
                                <form method="post" action="/eventos360/scripts/participate_event.php">
                                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

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
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
