<?php
// update_profile.php

include '../scripts/connection.php';

session_start();
$user_id = $_SESSION['user_id'];
$current_password = $_POST['current_password'];
$new_password = $_POST['new_password'];

// Verifique se a senha atual do usuário está correta
$sql = "SELECT password FROM user WHERE user_id = ?";
$stmt_check_password = $mysqli->prepare($sql);
$stmt_check_password->bind_param("i", $user_id);
$stmt_check_password->execute();
$result_check_password = $stmt_check_password->get_result();

if ($result_check_password->num_rows > 0) {
    $row = $result_check_password->fetch_assoc();
    $rawPassword = $row['password'];

    // Comparar a senha diretamente (sem usar password_verify)
    if (!password_verify($current_password, $rawPassword)) {
        // Senha atual incorreta
        echo "Senha atual incorreta.";
        echo "$rawPassword";
        echo "///";
        echo "$current_password";
    } else {
        // Senha atual está correta, proceda com a atualização do perfil

        $sql_update_profile = "UPDATE user SET ";
        $sql_update_profile .= "username = ?, ";
        $sql_update_profile .= "name = ?, ";
        $sql_update_profile .= "email = ?";

        // Verifique se a nova senha está sendo alterada
        if (!empty($new_password)) {
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $sql_update_profile .= ", password = ?";
        }

        $sql_update_profile .= " WHERE user_id = ?";

        // Use um prepared statement para a atualização
        $stmt_update_profile = $mysqli->prepare($sql_update_profile);
        $stmt_update_profile->bind_param("sss", $_POST['new_username'], $_POST['new_name'], $_POST['new_email']);

        if (!empty($new_password)) {
            // Se a nova senha estiver sendo alterada, use a versão hash da nova senha
            $stmt_update_profile->bind_param("s", $new_password_hash);
        }

        $stmt_update_profile->bind_param("i", $user_id);

        // Execute a atualização
        $result_update_profile = $stmt_update_profile->execute();

        if ($result_update_profile) {
            // Atualização bem-sucedida
            header("Location: /eventos360/profile.php");
            exit();
        } else {
            // Erro na atualização
            echo "Erro na atualização do perfil.";
        }

        // Feche o statement de atualização
        $stmt_update_profile->close();
    }
} else {
    // Usuário não encontrado ou erro ao executar a consulta
    echo "Erro ao verificar a senha atual.";
}

// Feche o statement de verificação de senha
$stmt_check_password->close();
?>
