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

// Consulta para obter o número de pessoas com interesse
$sql_interest_count = "SELECT COUNT(*) AS interest_count FROM attendance WHERE event_id = ? AND status = 'com_interesse'";
$stmt_interest_count = $mysqli->prepare($sql_interest_count);
$stmt_interest_count->bind_param("i", $event_id);
$stmt_interest_count->execute();
$result_interest_count = $stmt_interest_count->get_result();
$interest_count_data = $result_interest_count->fetch_assoc();

// Consulta para obter o número de pessoas que vão
$sql_going_count = "SELECT COUNT(*) AS going_count FROM attendance WHERE event_id = ? AND status = 'vou'";
$stmt_going_count = $mysqli->prepare($sql_going_count);
$stmt_going_count->bind_param("i", $event_id);
$stmt_going_count->execute();
$result_going_count = $stmt_going_count->get_result();
$going_count_data = $result_going_count->fetch_assoc();

// Consulta para obter o número de pessoas que vão
$sql_not_going_count = "SELECT COUNT(*) AS not_going_count FROM attendance WHERE event_id = ? AND status = 'nao_vou'";
$stmt_not_going_count = $mysqli->prepare($sql_not_going_count);
$stmt_not_going_count->bind_param("i", $event_id);
$stmt_not_going_count->execute();
$result_not_going_count = $stmt_not_going_count->get_result();
$not_going_count_data = $result_not_going_count->fetch_assoc();

$stmt_get_event_info->close();
$stmt_interest_count->close();
$stmt_going_count->close();
$stmt_not_going_count->close();
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

        <section class="event-details-section">
            <div class="container">
                <div class="event-details-container">
                    <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);"><?php echo $event_data['name']; ?></h1>
                    <!-- Botão para voltar -->
                    <a href="/eventos360/pages/edit_event.php" class="btn btn-primary" style="margin-bottom: 2vh">Voltar</a>
                    <p class="text-white">Descrição: <?php echo $event_data['description']; ?></p>
                    <p class="text-white">Data: <?php echo $event_data['date']; ?></p>
                    <p class="text-white">Localização: <?php echo $event_data['location']; ?></p>
                    <p class="text-white fw-bold">Vão: <?php echo $going_count_data['going_count']; ?></p>
                    <p class="text-white fw-bold">Não Vão: <?php echo $not_going_count_data['not_going_count']; ?></p>
                    <p class="text-white fw-bold">Com Interesse: <?php echo $interest_count_data['interest_count']; ?></p>

                    <a href="/eventos360/pages/view_attendees.php?event_id=<?php echo $event_id; ?>" class="btn btn-primary">Visualizar Participantes</a>
                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
