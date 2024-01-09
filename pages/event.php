<!-- events.php -->
<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: /eventos360/pages/login.php");
    exit();
}

include '../scripts/connection.php';
include '../scripts/participate_event.php';
include '../scripts/getParticipationStatus.php';

// Obter eventos do banco de dados
$sql = "SELECT * FROM event";
$result = $mysqli->query($sql);
$events = $result->fetch_all(MYSQLI_ASSOC);
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

        <section class="events-section">
            <div class="container">
                <h1 class="text-white fw-bold display-3" style="text-align: center; margin-top: 20vh; transform: translateY(-50%);">Eventos</h1>

                <form action="" method="GET">
                    <button style="margin-bottom: 1vh;" type="button" class="btn btn-primary" onclick="toggleFilters()">Mostrar Filtros</button>
                    
                    <div id="filter-box" style="display: none;">
                        <div class="mb-3">
                            <label for="search" class="form-label text-white fw-bold">Pesquisar por nome:</label>
                            <input type="text" class="form-control" id="search" name="search">
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label text-white fw-bold">Filtrar por categoria:</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">Todas as categorias</option>
                                <?php
                                    // Consulta SQL para obter as categorias
                                    $sql_categories = "SELECT * FROM category";
                                    $result_categories = $mysqli->query($sql_categories);
                                    $categories = $result_categories->fetch_all(MYSQLI_ASSOC);

                                    foreach ($categories as $category) {
                                        echo '<option value="' . $category['category_id'] . '">' . $category['name'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="region" class="form-label text-white fw-bold">Filtrar por região:</label>
                            <input type="text" class="form-control" id="region" name="region">
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label text-white fw-bold">Filtrar por data:</label>
                            <input type="date" class="form-control" id="date" name="date">
                        </div>
                        
                        <button style="margin-bottom: 1vh;" type="submit" class="btn btn-primary">Filtrar</button>
                    </div>

                    <!-- Botão para resetar os filtros -->
                    <a href="/eventos360/pages/event.php" style="margin-bottom: 1vh;" class="btn btn-secondary">Limpar Filtros</a>
                </form>

                <!-- Botão para criar um novo evento -->
                <a href="/eventos360/pages/create_event.php" class="btn btn-primary">Criar Evento</a>

                <!-- Botão para criar editar evento -->
                <a href="/eventos360/pages/edit_event.php" class="btn btn-primary">Editar Eventos</a>
                
                <!-- Mostrar lista de eventos -->
                <div class="event-list">
                <?php
                        // Construir a query SQL baseada nos filtros
                        $sql = "SELECT * FROM event WHERE 1";
                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                            $search = $mysqli->real_escape_string($_GET['search']);
                            $sql .= " AND name LIKE '%$search%'";
                        }
                        if (isset($_GET['category']) && !empty($_GET['category'])) {
                            $categoryFilter = $mysqli->real_escape_string($_GET['category']);
                            $sql .= " AND category_id = '$categoryFilter'";
                        }
                        if (isset($_GET['region']) && !empty($_GET['region'])) {
                            $region = $mysqli->real_escape_string($_GET['region']);
                            $sql .= " AND location LIKE '%$region%'";
                        }
                        if (isset($_GET['date']) && !empty($_GET['date'])) {
                            $date = $mysqli->real_escape_string($_GET['date']);
                            $sql .= " AND date = '$date'";
                        }

                        $result = $mysqli->query($sql);
                        $events = $result->fetch_all(MYSQLI_ASSOC);
                    ?>

                    <?php foreach ($events as $event): ?>
                        <?php 
                            $event_id = $event['event_id'];
                            $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
                            $status = getParticipationStatus($event_id, $user_id);
                            $categoryId = $event['category_id'];

                            // Consulta SQL para obter o nome da categoria
                            $sql_category_name = "SELECT name FROM category WHERE category_id = ?";
                            $stmt_category_name = $mysqli->prepare($sql_category_name);
                            $stmt_category_name->bind_param("i", $categoryId); 
                            $stmt_category_name->execute();
                            $stmt_category_name->bind_result($categoryName);
                            $stmt_category_name->fetch();
                            $stmt_category_name->close();
                        ?>
                        <div class="event">
                            <div class="event-details-box">
                                <h3 class="text-white fw-bold"><?php echo "Nome: " . $event['name']; ?></h3>
                                <p class="text-white fw-bold">Descrição: <?php echo $event['description']; ?></p>
                                <p class="text-white fw-bold">Data: <?php echo $event['date']; ?></p>
                                <p class="text-white fw-bold">Localização: <?php echo $event['location']; ?></p>
                                <p class="text-white fw-bold">Categoria: <?php echo $categoryName; ?></p>
                                
                                <p class="text-white fw-bold">Participantes: <?php echo getParticipantsCount($event_id); ?></p>

                                <!-- Lista de colaboradores -->
                                <?php
                                // Consulta para obter colaboradores do evento
                                $sql_collaborators = "SELECT user.username FROM user INNER JOIN event_users ON user.user_id = event_users.user_id WHERE event_users.event_id = ?";
                                $stmt_collaborators = $mysqli->prepare($sql_collaborators);
                                $stmt_collaborators->bind_param("i", $event_id);
                                $stmt_collaborators->execute();
                                $result_collaborators = $stmt_collaborators->get_result();

                                // Array para armazenar os nomes dos colaboradores
                                $collaborator_names = [];

                                while ($collaborator = $result_collaborators->fetch_assoc()) {
                                    $collaborator_names[] = $collaborator['username'];
                                }

                                // Feche o statement de colaboradores
                                $stmt_collaborators->close();

                                // Exiba a lista de colaboradores apenas se houver algum
                                if (!empty($collaborator_names)) {
                                    echo '<p class="text-white fw-bold">Colaboradores: ' . implode(', ', $collaborator_names) . '</p>';
                                }
                                ?>

                                <!-- Botão para participar ou cancelar participação no evento -->
                                <form method="post" action="/eventos360/scripts/participate_event.php">
                                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

                                    <?php if ($status === 'vou'): ?>
                                        <button type="submit" name="action" value="nao_vou" class="btn btn-danger">Não vou</button>
                                        <button type="submit" name="action" value="com_interesse" class="btn btn-info" <?php echo ($status === 'com_interesse') ? 'disabled' : ''; ?>>Com interesse</button>
                                    <?php elseif ($status === 'com_interesse'): ?>
                                        <button type="submit" name="action" value="vou" class="btn btn-success">Eu vou</button>
                                        <button type="submit" name="action" value="nao_vou" class="btn btn-danger">Não vou</button>
                                        <button type="submit" name="action" value="com_interesse" class="btn btn-info" <?php echo ($status === 'com_interesse') ? 'disabled' : ''; ?>>Com interesse</button>
                                    <?php  elseif ($status === 'nao_vou'): ?>
                                        <button type="submit" name="action" value="vou" class="btn btn-success">Eu vou</button>
                                        <button type="submit" name="action" value="com_interesse" class="btn btn-info" <?php echo ($status === 'com_interesse') ? 'disabled' : ''; ?>>Com interesse</button>
                                    <?php  else: ?>
                                        <button type="submit" name="action" value="vou" class="btn btn-success">Eu vou</button>
                                        <button type="submit" name="action" value="com_interesse" class="btn btn-info" <?php echo ($status === 'com_interesse') ? 'disabled' : ''; ?>>Com interesse</button>
                                    <?php endif; ?>
                                </form>
                                <!-- Botão de Partilha usando o ID do Evento -->
                                <button style="margin-top: 1vh;" class="btn btn-secondary" onclick="shareEvent(<?php echo $event['event_id']; ?>)">Partilhar</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>

    <?php include '../includes/footer.php'; ?>
</body>

<!-- JavaScript para partilha -->
<script>
    function shareEvent(eventId) {
        var shareURL = 'http://localhost/eventos360/pages/shared_event.php?event_id=' + encodeURIComponent(eventId);
        
        window.open(shareURL, '_blank');
    }
</script>

<!-- JavaScript para mostrar/ocultar os filtros -->
<script>
    function toggleFilters() {
        var filterBox = document.getElementById('filter-box');
        var filterButton = document.querySelector('.btn-primary');

        if (filterBox.style.display === 'none') {
            filterBox.style.display = 'block';
            filterButton.innerText = 'Ocultar Filtros';
        } else {
            filterBox.style.display = 'none';
            filterButton.innerText = 'Mostrar Filtros';
        }
    }
</script>

</html>
