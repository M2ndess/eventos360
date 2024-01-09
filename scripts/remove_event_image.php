<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["event_id"])) {
        $event_id = $_POST["event_id"];

        include 'connection.php';

        // Obtém o image_id associado ao evento
        $sql_get_image_id = "SELECT image_id FROM event WHERE event_id = ?";
        $stmt_get_image_id = $mysqli->prepare($sql_get_image_id);
        $stmt_get_image_id->bind_param("i", $event_id);
        $stmt_get_image_id->execute();
        $stmt_get_image_id->bind_result($image_id);
        $stmt_get_image_id->fetch();
        $stmt_get_image_id->close();

        // Remove o image_id da tabela event
        $sql_remove_image_id = "UPDATE event SET image_id = NULL WHERE event_id = ?";
        $stmt_remove_image_id = $mysqli->prepare($sql_remove_image_id);
        $stmt_remove_image_id->bind_param("i", $event_id);
        $stmt_remove_image_id->execute();
        $stmt_remove_image_id->close();

        // Remove a imagem da tabela images
        if (!empty($image_id)) {
            $sql_remove_image = "DELETE FROM images WHERE image_id = ?";
            $stmt_remove_image = $mysqli->prepare($sql_remove_image);
            $stmt_remove_image->bind_param("i", $image_id);
            $stmt_remove_image->execute();
            $stmt_remove_image->close();
        }

        // Opcional: Adicione lógica adicional após a remoção bem-sucedida
        echo "Imagem removida com sucesso";
    }
}
?>
