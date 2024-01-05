<!-- register.php -->

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

        <section class="register-section">
            <div class="container">
                <div class="register-container">
                    <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);">Registo</h1>

                    <?php
                    include '../scripts/connection.php';

                    function verificaExistencia($mysqli, $username, $email) {
                        $username = mysqli_real_escape_string($mysqli, $username);
                        $email = mysqli_real_escape_string($mysqli, $email);

                        $sql = "SELECT * FROM user WHERE username = '$username' OR email = '$email'";
                        $result = $mysqli->query($sql);

                        return $result->num_rows > 0; // Retorna true se já existir um registro com o mesmo username ou email
                    }

                    function realizaRegistro($mysqli, $username, $password, $name, $email) {
                        // Evitar SQL injection
                        $username = mysqli_real_escape_string($mysqli, $username);
                        $email = mysqli_real_escape_string($mysqli, $email);

                        // Verificar se o username ou email já existem
                        if (verificaExistencia($mysqli, $username, $email)) {
                            $error = array();

                            if (verificaExistencia($mysqli, $username, '')) {
                                $error[] = 'username';
                            }

                            if (verificaExistencia($mysqli, '', $email)) {
                                $error[] = 'email';
                            }

                            return $error; // Retorna os campos (username, email ou ambos) que já estão em uso
                        }

                        $password = mysqli_real_escape_string($mysqli, $password);
                        $name = mysqli_real_escape_string($mysqli, $name);

                        // Hash da senha
                        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                        $sql = "INSERT INTO user (username, password, name, email) VALUES ('$username', '$hashedPassword', '$name', '$email')";
                        $result = $mysqli->query($sql);

                        return $result; // Retorna true se o registro foi bem-sucedido
                    }

                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $username = $_POST["username"];
                        $password = $_POST["password"];
                        $name = $_POST["name"];
                        $email = $_POST["email"];

                        $result = realizaRegistro($mysqli, $username, $password, $name, $email);

                        if (is_array($result)) {
                            // Erro no registro, indicar quais campos já estão em uso
                            if (in_array('username', $result) && in_array('email', $result)) {
                                echo '<p style="color: white; font-weight: bold;" class="error">Erro no registro. O username e o email já estão em uso. Tente novamente.</p>';
                            } elseif (in_array('username', $result)) {
                                echo '<p style="color: white; font-weight: bold;" class="error">Erro no registro. O username já está em uso. Tente novamente.</p>';
                            } elseif (in_array('email', $result)) {
                                echo '<p style="color: white; font-weight: bold;" class="error">Erro no registro. O email já está em uso. Tente novamente.</p>';
                            }
                        } else {
                            // Registo bem-sucedido, redireciona para a home page
                            echo '<script>window.location.replace("/eventos360/");</script>';
                            exit();
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
                            <label for="name" style="color: white; font-weight: bold;">Nome</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="email" style="color: white; font-weight: bold;">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <button type="submit" style="color: black; font-weight: bold; padding: 1vh 3vh; margin-top: 2vh;">Registar</button>
                        </div>
                    </form>

                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
