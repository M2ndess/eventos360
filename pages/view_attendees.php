<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}

include '../scripts/connection.php';

// Verificar se o event_id foi passado como parâmetro na URL
if (!isset($_GET['event_id'])) {
    // Se não foi, redirecionar para uma página de erro ou eventos
    header("Location: /eventos360/pages/error.php");
    exit();
}

// Obter o event_id da URL
$event_id = $_GET['event_id'];

// Consulta para obter informações do evento
$sql_get_event_info = "SELECT * FROM event WHERE event_id = ?";
$stmt_get_event_info = $mysqli->prepare($sql_get_event_info);
$stmt_get_event_info->bind_param("i", $event_id);
$stmt_get_event_info->execute();
$result_event_info = $stmt_get_event_info->get_result();

if ($result_event_info->num_rows > 0) {
    $event_data = $result_event_info->fetch_assoc();
} else {
    // Se o evento não existir, redirecionar para uma página de erro ou eventos
    header("Location: /eventos360/pages/error.php");
    exit();
}

// Consulta para obter a lista de participantes
$sql_get_attendees = "SELECT user.username, attendance.status FROM user
                      INNER JOIN attendance ON user.user_id = attendance.user_id
                      WHERE attendance.event_id = ?";
$stmt_get_attendees = $mysqli->prepare($sql_get_attendees);
$stmt_get_attendees->bind_param("i", $event_id);
$stmt_get_attendees->execute();

if ($stmt_get_attendees->error) {
    // Exibir mensagem de erro em caso de falha na execução da consulta
    echo "Erro na consulta: " . $stmt_get_attendees->error;
} else {
    // Resultado da consulta
    $result_attendees = $stmt_get_attendees->get_result();

    // Lista de participantes
    $attendees = [];
    while ($row = $result_attendees->fetch_assoc()) {
        $attendees[] = $row;
    }

    $stmt_get_attendees->close();
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

        <section class="attendees-section">
            <div class="container">
                <div class="attendees-container">
                    <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);"><?php echo $event_data['name']; ?> - Participantes</h1>
                    <!-- Botão para voltar -->
                    <a href="/eventos360/pages/details.php?event_id=<?php echo $event_id; ?>" class="btn btn-primary" style="margin-bottom: 2vh">Voltar</a>
                    <!-- Lista de participantes -->
                    <ul>
                        <?php foreach ($attendees as $row) : ?>
                            <li class="list-group-item">
                                <span class="text-white"><?php echo $row['username']; ?></span>
                                <span class="text-white"><?php echo " - " . ucfirst($row['status']); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
