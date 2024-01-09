<!-- edit_single_event.php -->
<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}

include '../scripts/connection.php';

// Verificar se o evento_id foi passado como parâmetro
if (isset($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Obter dados do evento do banco de dados
    $sql_get_event = "SELECT * FROM event WHERE event_id = ?";
    $stmt_get_event = $mysqli->prepare($sql_get_event);
    $stmt_get_event->bind_param("i", $event_id);

    $stmt_get_event->execute();
    $result = $stmt_get_event->get_result();
    $event = $result->fetch_assoc();

    $stmt_get_event->close();

    // Obter dados do evento do banco de dados
    $sql_get_event = "SELECT event.*, images.url AS image_path 
                    FROM event 
                    LEFT JOIN images ON event.image_id = images.image_id 
                    WHERE event.event_id = ?";
    $stmt_get_event = $mysqli->prepare($sql_get_event);
    $stmt_get_event->bind_param("i", $event_id);

    $stmt_get_event->execute();
    $result = $stmt_get_event->get_result();
    $event = $result->fetch_assoc();

    $stmt_get_event->close();

    // Verificar se o evento pertence ao usuário logado
    if ($event['user_id'] != $_SESSION['user_id']) {
        // O evento não pertence ao usuário logado, redirecionar para uma página de erro ou eventos
        header("Location: /eventos360/pages/error.php");
        exit();
    }
} else {
    // Parâmetro event_id ausente, redirecionar para uma página de erro ou eventos
    header("Location: /eventos360/pages/error.php");
    exit();
}



// Restante do código para a edição do evento...
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

        <section class="edit-event-section">
            <div class="container">
                <div class="edit-event-container">
                    <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);">Editar Evento</h1>
                    <!-- Botão para voltar -->
                    <a href="/eventos360/pages/edit_event.php" class="btn btn-primary" style="margin-bottom: 2vh">Voltar</a>
                    <!-- Formulário de edição de eventos -->
                    <form method="post" action="/eventos360/scripts/edit_event.php" enctype="multipart/form-data">
                        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

                        <!-- Botão para adicionar colaborador -->
                        <a href="/eventos360/pages/add_user.php?event_id=<?php echo $event_id; ?>" class="btn btn-primary">Adicionar Colaborador</a>

                        <!-- Botão para remover colaborador -->
                        <a href="/eventos360/pages/remove_user.php?event_id=<?php echo $event_id; ?>" class="btn btn-primary">Remover Colaborador</a>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold;" for="event_name">Nome</label>
                            <input type="text" id="event_name" name="event_name" class="form-control" value="<?php echo $event['name']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="event_description">Descrição</label>
                            <textarea id="event_description" name="event_description" class="form-control" rows="4" required><?php echo $event['description']; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="event_date">Data</label>
                            <input type="date" id="event_date" name="event_date" class="form-control" value="<?php echo $event['date']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="event_location">Localização</label>
                            <input type="text" id="event_location" name="event_location" class="form-control" value="<?php echo $event['location']; ?>" required>
                        </div>

                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="event_category">Categoria</label>
                            <select id="event_category" name="event_category" class="form-control" required>
                                <?php
                                // Consultar categorias existentes na bd
                                $sql_select_categories = "SELECT category_id, name FROM category";
                                $result_categories = $mysqli->query($sql_select_categories);

                                // Adicionar as opções da dropdown
                                while ($row_category = $result_categories->fetch_assoc()) {
                                    $selected = ($row_category['category_id'] == $event['category_id']) ? 'selected' : '';
                                    echo '<option value="' . $row_category['category_id'] . '" ' . $selected . '>' . $row_category['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Adicionar campo de upload de imagem -->
                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;" for="event_image">Imagem do Evento</label>
                            <input type="file" id="event_image" name="event_image" accept="image/*" class="form-control">
                        </div>

                        <!-- Adicionar botão para remover imagem -->
                        <div class="form-group">
                            <label style="color: white; font-weight: bold; padding-top: 2vh;">Imagem Atual</label>
                            <?php
                            if (!empty($event['image_path'])) {
                                echo '<img src="/eventos360/uploads/' . $event['image_path'] . '" alt="Imagem Atual" style="max-width: 30%; height: auto; margin-left: 3vh; margin-right: 3vh;">';
                                echo '<button style="margin-top: 2vh;" type="button" class="btn btn-danger" id="remove_image_button">
                                Remover Imagem Atual
                            </button>';
                            } else {
                                echo '<p style="color: white; font-weight: bold;">Nenhuma imagem atualmente associada ao evento.</p>';
                            }
                            ?>
                        </div>

                        <div class="form-group">
                            <button style="margin-top: 2vh;" type="submit" class="btn btn-primary">Guardar Alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

<!-- Script para acionar a remoção da imagem -->
<script>
    document.getElementById('remove_image_button').addEventListener('click', function() {
        if (confirm('Tem certeza de que deseja remover a imagem atual?')) {
            // Adiciona um campo hidden para indicar a remoção da imagem
            var removeImageField = document.createElement('input');
            removeImageField.type = 'hidden';
            removeImageField.name = 'remove_image';
            removeImageField.value = '1';
            document.querySelector('form').appendChild(removeImageField);

            // Desativa o campo de seleção de nova imagem
            document.getElementById('event_image').disabled = true;

            // Opcional: Oculta o botão de remoção
            this.style.display = 'none';
            
            // Atualiza a tabela event para remover o image_id
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    // Opcional: Adicione lógica adicional após a remoção bem-sucedida
                    console.log("Imagem removida com sucesso");
                    location.reload();
                }
            };
            xhr.open("POST", "/eventos360/scripts/remove_event_image.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("event_id=<?php echo $event_id; ?>");
        }
    });
</script>

</html>
