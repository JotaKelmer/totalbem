<?php
// process_form.php

// Define o email para onde os dados serão enviados
$destinatario = "contato@totalbem.com.br";

// Função para sanitizar entradas
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Verifica se o formulário foi submetido via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coleta e sanitiza os dados do formulário
    $nome = isset($_POST['nome']) ? sanitize_input($_POST['nome']) : '';
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $cargo = isset($_POST['cargo']) ? sanitize_input($_POST['cargo']) : '';
    $telefone = isset($_POST['telefone']) ? sanitize_input($_POST['telefone']) : '';
    $regiao = isset($_POST['regiao']) ? sanitize_input($_POST['regiao']) : '';

    // Validação dos campos obrigatórios
    $erros = [];

    if (empty($nome)) {
        $erros[] = "O nome é obrigatório.";
    }

    if (empty($email)) {
        $erros[] = "O email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email inserido é inválido.";
    }

    if (empty($cargo)) {
        $erros[] = "O cargo é obrigatório.";
    }

    if (empty($regiao)) {
        $erros[] = "A região é obrigatória.";
    }

    if (count($erros) > 0) {
        // Se houver erros, exibe-os para o usuário
        echo "<div class='mensagem erro'>";
        foreach ($erros as $erro) {
            echo "<p>" . $erro . "</p>";
        }
        echo "</div>";
    } else {
        // Cria o conteúdo do email
        $assunto = "Nova Solicitação de Benefício - Totalbem";
        $mensagem = "Você recebeu uma nova solicitação de benefício corporativo.\n\n";
        $mensagem .= "Nome: " . $nome . "\n";
        $mensagem .= "Email: " . $email . "\n";
        $mensagem .= "Cargo: " . $cargo . "\n";
        $mensagem .= "Telefone: " . ($telefone ? $telefone : 'Não informado') . "\n";
        $mensagem .= "Região: " . $regiao . "\n";

        // Cabeçalhos do email
        $headers = "From: " . $nome . " <" . $email . ">\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Tenta enviar o email
        if (mail($destinatario, $assunto, $mensagem, $headers)) {
            echo "<div class='mensagem sucesso'>";
            echo "<p>Obrigado, sua solicitação foi enviada com sucesso!</p>";
            echo "</div>";
        } else {
            echo "<div class='mensagem erro'>";
            echo "<p>Desculpe, houve um erro ao enviar sua solicitação. Por favor, tente novamente mais tarde.</p>";
            echo "</div>";
        }
    }
} else {
    // Se o acesso não for via POST, redireciona para a página inicial
    header("Location: index.html");
    exit();
}
?>
