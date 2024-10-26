    <?php
session_start();

// Função para verificar se o e-mail já está cadastrado
function isEmailExists($email, $conexao) {
    $query = "SELECT COUNT(*) FROM tb_usuario WHERE ds_email = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $count > 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Exibir todos os dados recebidos
    var_dump($_POST); // Para depuração

    // Capturando os dados do formulário
    $nome = $_POST['nome'];
    $senha = $_POST['senha'];
    $email = $_POST['usuario'];

    // Validar se as senhas coincidem
    // (Adicione sua lógica de confirmação de senha aqui)

    // Estabelecer conexão
    $conexao = mysqli_connect("localhost", "root", "", "bd_glark", "3306");

    if (!$conexao) {
        die("Conexão falhou: " . mysqli_connect_error());
    }

    // Verificar se o e-mail já está cadastrado
    if (isEmailExists($email, $conexao)) {
        mysqli_close($conexao);
        header("Location: cadastro.php?erro=Este e-mail já está cadastrado");
        exit();
    }

    // Hash da senha para segurança
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir o novo usuário
    $operacao = "INSERT INTO tb_usuario (ds_email, ds_senha, nm_user) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $operacao);
    mysqli_stmt_bind_param($stmt, "sss", $email, $senhaHash, $nome);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: cadastro_sucesso.php");  
        exit();
    } else {
        echo "Erro ao cadastrar: " . mysqli_stmt_error($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conexao);
}
?>
