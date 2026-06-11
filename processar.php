<?php
/**
 * ════════════════════════════════════════════════════════════
 * FORMULÁRIO — PROCESSAMENTO PHP
 * ════════════════════════════════════════════════════════════
 * 
 * Este arquivo recebe os dados do formulário HTML+JS
 * e processa no lado do servidor (segurança, validação, etc.)
 */

// ─── VALIDAÇÃO NO SERVIDOR (Segurança) ─────────────────────
// IMPORTANTE: Nunca confie APENAS em validação JavaScript!
// O usuário pode desabilitar JS ou enviar dados maliciosos direto.

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  // Obter os dados (com proteção básica)
  $nome = isset($_POST['nome']) ? trim($_POST['nome']) : '';
  $email = isset($_POST['email']) ? trim($_POST['email']) : '';
  $idade = isset($_POST['idade']) ? intval($_POST['idade']) : 0;
  $cidade = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
  $estado = isset($_POST['estado']) ? trim($_POST['estado']) : '';
  $mensagem = isset($_POST['mensagem']) ? trim($_POST['mensagem']) : '';
  $aceita = isset($_POST['aceita']) ? 1 : 0;

  // ─── VALIDAÇÃO PHP ─────────────────────────────────────────

  // Array para armazenar erros
  $erros = array();

  // 1. Validar nome
  if (strlen($nome) < 3) {
    $erros[] = "Nome deve ter pelo menos 3 caracteres";
  }

  // 2. Validar email (usando função PHP)
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $erros[] = "E-mail inválido";
  }

  // 3. Validar idade
  if ($idade < 1 || $idade > 120) {
    $erros[] = "Idade inválida (deve estar entre 1 e 120)";
  }

  // 4. Validar cidade
  if (strlen($cidade) < 2) {
    $erros[] = "Cidade deve ter pelo menos 2 caracteres";
  }

  // 5. Validar estado
  $estados_validos = array('SP', 'RJ', 'MG', 'RS', 'BA', 'PR', 'Outro');
  if (!in_array($estado, $estados_validos)) {
    $erros[] = "Estado inválido";
  }

  // 6. Validar checkbox
  if (!$aceita) {
    $erros[] = "Você deve concordar com os termos";
  }

  // ─── SE HOUVER ERROS, EXIBIR E PARAR ────────────────────
  if (!empty($erros)) {
    http_response_code(400);
    echo "Erro: " . implode(" | ", $erros);
    exit;
  }

  // ─── DADOS VÁLIDOS — PROCESSAR ──────────────────────────
  // Aqui você pode:
  // - Salvar em banco de dados
  // - Salvar em arquivo
  // - Enviar e-mail
  // - Etc.

  // EXEMPLO 1: Salvar em arquivo (dados.txt)
  $dados_txt = "Nome: $nome\n";
  $dados_txt .= "Email: $email\n";
  $dados_txt .= "Idade: $idade\n";
  $dados_txt .= "Cidade: $cidade\n";
  $dados_txt .= "Estado: $estado\n";
  $dados_txt .= "Mensagem: $mensagem\n";
  $dados_txt .= "Data/Hora: " . date('d/m/Y H:i:s') . "\n";
  $dados_txt .= "─────────────────────────────────────\n\n";

  // Salvar no arquivo (ou criar se não existir)
  file_put_contents('dados.txt', $dados_txt, FILE_APPEND);

  // EXEMPLO 2: Salvaria em banco de dados (comentado)
  /*
  $servidor = "localhost";
  $usuario = "root";
  $senha = "";
  $banco = "formulario_db";

  $conexao = new mysqli($servidor, $usuario, $senha, $banco);

  if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
  }

  $sql = "INSERT INTO usuarios (nome, email, idade, cidade, estado, mensagem, data)
          VALUES ('$nome', '$email', $idade, '$cidade', '$estado', '$mensagem', NOW())";

  if ($conexao->query($sql) === TRUE) {
    echo "Cadastro realizado com sucesso!";
  } else {
    echo "Erro ao cadastrar: " . $conexao->error;
  }

  $conexao->close();
  */

  // EXEMPLO 3: Enviar e-mail (comentado)
  /*
  $para = "seu@email.com";
  $assunto = "Novo formulário preenchido";
  $corpo = "Nome: $nome\nEmail: $email\nIdade: $idade\nCidade: $cidade\nEstado: $estado\n\nMensagem:\n$mensagem";

  mail($para, $assunto, $corpo);
  */

  // ─── RESPOSTA DE SUCESSO ────────────────────────────────
  http_response_code(200);
  echo "Formulário recebido com sucesso! Seu cadastro foi salvo.";

} else {
  // Se não for POST, retornar erro
  http_response_code(405);
  echo "Método não permitido (use POST)";
}

?>
