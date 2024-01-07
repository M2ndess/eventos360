<!-- add_user.php -->
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}

// Verificar se o event_id foi passado como parâmetro na URL
if (!isset($_GET['event_id'])) {
    // Se não foi, redirecionar para uma página de erro ou eventos
    header("Location: /eventos360/pages/error.php");
    exit();
}

// Obter o event_id da URL
$event_id = $_GET['event_id'];

include '../scripts/connection.php';

// Consulta para obter todos os usuários que NÃO estão associados ao evento
$sql_get_users = "SELECT user_id, username FROM user WHERE user_id <> ? AND user_id NOT IN (SELECT user_id FROM event_users WHERE event_id = ?)";
$stmt_get_users = $mysqli->prepare($sql_get_users);
$stmt_get_users->bind_param("ii", $_SESSION['user_id'], $event_id);
$stmt_get_users->execute();
$result_users = $stmt_get_users->get_result();
$stmt_get_users->close();
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

        <section class="add-user-section">
            <div class="container">
                <div class="add-user-container">
                    <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);">Adicionar Colaborador</h1>
                    <!-- Botão para voltar -->
                    <a href="/eventos360/pages/edit_event.php" class="btn btn-primary" style="margin-bottom: 2vh">Voltar</a>
                    
                    <!-- Lista de usuários -->
                    <ul class="list-group">
                        <?php while ($row = $result_users->fetch_assoc()) : ?>
                            <li class="list-group-item">
                                <?php echo $row['username']; ?>
                                <a href="/eventos360/scripts/process_add_user.php?user_id=<?php echo $row['user_id']; ?>&event_id=<?php echo $event_id; ?>" class="btn btn-success">Adicionar</a>
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
