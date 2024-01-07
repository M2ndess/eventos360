<!-- remove_user.php -->
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}

include '../scripts/connection.php';

// Verificar se o event_id foi passado como parâmetro
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Obter usuários associados ao evento
    $sql_get_users_in_event = "SELECT user.user_id, user.username FROM user INNER JOIN event_users ON user.user_id = event_users.user_id WHERE event_users.event_id = ?";
    $stmt_get_users_in_event = $mysqli->prepare($sql_get_users_in_event);
    $stmt_get_users_in_event->bind_param("i", $event_id);
    $stmt_get_users_in_event->execute();
    $result_users_in_event = $stmt_get_users_in_event->get_result();
    $stmt_get_users_in_event->close();
} else {
    // Redirecionar para uma página de erro ou eventos
    header("Location: /eventos360/pages/error.php");
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

        <section class="remove-user-section">
            <div class="container">
                <div class="remove-user-container">
                    <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);">Remover Colaborador</h1>
                    <!-- Botão para voltar -->
                    <a href="/eventos360/pages/edit_event.php" class="btn btn-primary" style="margin-bottom: 2vh">Voltar</a>
                    <!-- Lista de usuários no evento -->
                    <ul class="list-group">
                        <?php while ($row = $result_users_in_event->fetch_assoc()) : ?>
                            <li class="list-group-item">
                                <?php echo $row['username']; ?>
                                <a href="/eventos360/scripts/process_remove_user.php?user_id=<?php echo $row['user_id']; ?>&event_id=<?php echo $event_id; ?>" class="btn btn-danger">Remover</a>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
