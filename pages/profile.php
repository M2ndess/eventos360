<!-- profile.php -->
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}
?>

<?php
include '../scripts/connection.php';

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
        <?php include '../includes/header_logado.php'; ?>

        <section class="profile-section">
            <div class="container">
                <div class="profile-container">
                    <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);">Perfil</h1>

                    <!-- Formulário de edição de dados -->
                    <form method="post" action="../scripts/update_profile.php">

                        <div class="form-group">
                            <label style="color: white; font-weight: bold;" for="new_username">Novo Nome de Utilizador</label>
                            <input type="text" id="new_username" name="new_username" class="form-control" value="<?php echo $userData['username']; ?>">
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="new_name">Novo Nome</label>
                            <input type="text" id="new_name" name="new_name" class="form-control" value="<?php echo $userData['name']; ?>">
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="new_email">Novo Email</label>
                            <input type="email" id="new_email" name="new_email" class="form-control" value="<?php echo $userData['email']; ?>">
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="current_password">Senha Atual</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="new_password">Nova Palavra-Passe</label>
                            <input type="password" id="new_password" name="new_password" class="form-control">
                        </div>

                        <div class="form-group">
                            <button style="margin-top: 2vh;" type="submit" class="btn btn-primary">Atualizar Perfil</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
