<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}

include '../scripts/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtenha os dados do formulário
    $categoryName = $_POST["name"];
    $categoryDescription = $_POST["description"];


    // Inserir a nova categoria no banco de dados
    $sql_insert_category = "INSERT INTO category (name, description) VALUES (?, ?)";
    $stmt_insert_category = $mysqli->prepare($sql_insert_category);
    $stmt_insert_category->bind_param("ss", $categoryName, $categoryDescription);

    if ($stmt_insert_category->execute()) {
        // Categoria criada com sucesso, redirecionar para a página de criação de evento
        header("Location: /eventos360/pages/create_event.php");
        exit();
    } else {
        // Erro ao criar a categoria
        echo "Erro ao criar a categoria.";
    }

    // Feche o statement de inserção
    $stmt_insert_category->close();
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

        <section class="create-category-section">
            <div class="container">
                <div class="create-category-container">
                    <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);">Criar Categoria</h1>
                    <!-- Botão para voltar -->
                    <a href="/eventos360/pages/create_event.php" class="btn btn-primary" style="margin-bottom: 2vh">Voltar</a>
                    <!-- Formulário de criação de categoria -->
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label style="color: white; font-weight: bold;" for="category_name">Nome</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold;" for="description">Descrição</label>
                            <input type="text" id="description" name="description" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <button style="margin-top: 2vh;" type="submit" class="btn btn-success">Criar Categoria</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

</html>
