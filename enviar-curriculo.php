<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $cargo = $_POST['cargo'];

    $to = "sc.rh@gmail.com";
    $subject = "Candidatura para: " . $cargo;
    $message = "Nome: $nome\nTelefone: $telefone\nEndereço: $endereco\nCargo Pretendido: $cargo";

    $file_tmp = $_FILES['curriculo']['tmp_name'];
    $file_name = $_FILES['curriculo']['name'];
    $file_size = $_FILES['curriculo']['size'];
    $file_type = $_FILES['curriculo']['type'];

    $boundary = md5(uniqid());
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "From: $nome <$to>\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"\r\n";

    $body = "--$boundary\r\n";
    $body .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
    $body .= "$message\r\n";

    if (file_exists($file_tmp)) {
        $file_content = chunk_split(base64_encode(file_get_contents($file_tmp)));
        $body .= "--$boundary\r\n";
        $body .= "Content-Type: $file_type; name=\"$file_name\"\r\n";
        $body .= "Content-Disposition: attachment; filename=\"$file_name\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= "$file_content\r\n";
        $body .= "--$boundary--";
    }

    $sent = mail($to, $subject, $body, $headers);

    if ($sent) {
        echo "<script>alert('Currículo enviado com sucesso!');window.location.href='trabalhe.html';</script>";
    } else {
        echo "<script>alert('Erro ao enviar currículo. Tente novamente.');window.history.back();</script>";
    }
}
?>
