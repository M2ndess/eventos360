<!-- profile.php -->

<?php
include '../scripts/connection.php';

// Verificar se o usuário está logado
// Coloque a lógica adequada para verificar se o usuário está autenticado

// Exemplo: Verifica se a sessão está ativa
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/login.php"); // Redireciona para a página de login
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM user WHERE user_id = $user_id";
$result = $mysqli->query($sql);
$userData = $result->fetch_assoc();
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
        <?php include '../includes/header.php'; ?>

        <section class="profile-section">
            <div class="container">
                <div class="profile-container">
                    <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);">Perfil</h1>

                    <!-- Exibir informações do perfil -->
                    <p>Nome de Utilizador: <?php echo $userData['username']; ?></p>
                    <p>Nome: <?php echo $userData['name']; ?></p>
                    <p>Email: <?php echo $userData['email']; ?></p>

                    <!-- Formulário de edição de dados -->
                    <form method="post" action="update_profile.php">
                        <div class="form-group">
                            <label for="new_password">Nova Palavra-Passe</label>
                            <input type="password" id="new_password" name="new_password" class="form-control">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Atualizar Perfil</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
