<!-- login.php -->

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

        <section class="login-section">
            <div class="container">
                <div class="login-container">
                    <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);">Login</h1>
                    
                    <?php
                    include '../scripts/connection.php';

                    function verificaLogin($mysqli, $username, $password) {
                        // Evitar SQL injection
                        $username = mysqli_real_escape_string($mysqli, $username);
                        $password = mysqli_real_escape_string($mysqli, $password);

                        $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
                        $result = $mysqli->query($sql);

                        if ($result->num_rows > 0) {
                            return true; // Credenciais válidas
                        } else {
                            return false; // Credenciais inválidas
                        }
                    }

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $username = $_POST["username"];
                        $password = $_POST["password"];

                        if (verificaLogin($mysqli, $username, $password)) {
                            // Login bem-sucedido, redireciona para a página principal
                            $user_logado = true;
                            echo '<script>window.location.replace("/eventos360/pages/home.php");</script>';
                        } else {
                            echo '<p style="color: white; font-weight: bold;" class="error">Credenciais inválidas. Tente novamente.</p>';
                        }
                    }
                    ?>

                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="username" style="color: white; font-weight: bold;">Nome de Utilizador</label>
                            <input type="text" id="username" name="username" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="password" style="color: white; font-weight: bold;">Palavra-Passe</label>
                            <input type="password" id="password" name="password" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" style="color: black; font-weight: bold; padding: 1vh 3vh; margin-top: 2vh;">Login</button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
