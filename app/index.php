<?php
require('fpdf/fpdf.php'); // Incluir a biblioteca FPDF

// Função para cadastrar um aluno com matrícula automática
function cadastrarAluno() {
    $matricula = gerarMatricula();
    echo "Matricula gerada automaticamente: $matricula\n";

    echo "Digite o nome do aluno: ";
    $nome = trim(fgets(STDIN)); // Lê o nome do aluno
    return ['nome' => $nome, 'matricula' => $matricula];
}

// Função para gerar matrícula automaticamente
function gerarMatricula() {
    $alunos = file('dados/alunos.txt');
    $ultimoAluno = end($alunos); // Pega o último aluno
    if ($ultimoAluno) {
        $dados = explode(";", $ultimoAluno);
        $matricula = $dados[1];
        return (intval($matricula) + 1); // Incrementa a matrícula
    }
    return 1; // Se não houver nenhum aluno, começa com matrícula 1
}

// Função para inserir 3 notas de um aluno com validação de ponto e vírgula
function inserirNotas() {
    $nota1 = obterNota();
    $nota2 = obterNota();
    $nota3 = obterNota();
    return [$nota1, $nota2, $nota3];
}

// Função para garantir que o número inserido seja válido
function obterNota() {
    do {
        echo "Digite a nota (exemplo 7.5 ou 7,5): ";
        $nota = trim(fgets(STDIN));

        // Substitui a vírgula por ponto
        $nota = str_replace(',', '.', $nota);

        // Verifica se a nota é um número válido
        if (is_numeric($nota)) {
            return floatval($nota); // Retorna como número de ponto flutuante
        } else {
            echo "Nota inválida. Por favor, insira um valor numérico.\n";
        }
    } while (true);
}

// Função para calcular a média
function calcularMedia($nota1, $nota2, $nota3) {
    return ($nota1 + $nota2 + $nota3) / 3;
}

// Função para verificar se o aluno foi aprovado
function verificarAprovacao($media) {
    return $media >= 7.0 ? "Aprovado" : "Reprovado";
}

// Função para salvar os dados no arquivo
function salvarAlunoArquivo($nome, $matricula, $nota1, $nota2, $nota3, $media, $status) {
    $dadosAluno = "$nome;$matricula;$nota1;$nota2;$nota3;".number_format($media, 1).";$status\n";
    file_put_contents('dados/alunos.txt', $dadosAluno, FILE_APPEND); // Escreve no arquivo
}

// Função para consultar todos os alunos cadastrados
function consultarAlunos() {
    if (file_exists('dados/alunos.txt')) {
        $alunos = file('dados/alunos.txt');
        if (empty($alunos)) {
            echo "Nenhum aluno cadastrado.\n";
        } else {
            echo "Lista de alunos cadastrados:\n";
            foreach ($alunos as $aluno) {
                $dados = explode(";", $aluno);
                echo "Nome: {$dados[0]}, Matrícula: {$dados[1]}, Notas: {$dados[2]}, {$dados[3]}, {$dados[4]}, Média: {$dados[5]}, Status: {$dados[6]}\n";
            }
        }
    } else {
        echo "Nenhum aluno cadastrado ainda.\n";
    }
}

// Função para gerar o certificado em PDF para alunos aprovados
function gerarCertificado($nome, $matricula) {
    // Cria uma nova instância do FPDF
    $pdf = new FPDF();
    $pdf->AddPage('L'); // Página no formato paisagem (landscape)
    
    // Definir fonte
    $pdf->SetFont('Arial', 'B', 16);
    
    // Título do certificado
    $pdf->Cell(0, 10, utf8_decode('CERTIFICADO DE APROVACAO'), 0, 1, 'C');
    
    // Quebra de linha
    $pdf->Ln(10);
    
    // Definir fonte para o corpo do certificado
    $pdf->SetFont('Arial', '', 12);
    
    // Texto do certificado com o nome e matrícula do aluno
    $pdf->MultiCell(0, 10, utf8_decode("Certificamos que o aluno(a) $nome, matrícula: $matricula, foi aprovado(a) com sucesso nas avaliações e concluiu o curso com aproveitamento."));
    
    // Quebra de linha
    $pdf->Ln(10);
    
    // Adicionar a data e hora de geração do certificado
    date_default_timezone_set('America/Sao_Paulo');
    $dataHora = date('d/m/Y H:i:s');
    $pdf->Cell(0, 10, utf8_decode("Certificado Gerado em: $dataHora"), 0, 1, 'C');
    
    // Quebra de linha
    $pdf->Ln(10);
    
    // Assinatura
    $pdf->Cell(0, 10, "____________________________", 0, 1, 'C');
    $pdf->Cell(0, 10, "Assinatura do Coordenador", 0, 1, 'C');
    
    // Caminho da imagem via URL
    $imagemPath = 'imagens/selo.png';

    // Insere a imagem diretamente via URL
    $pdf->Image($imagemPath, 135, 175, 30, 30); // Insere a imagem se ela estiver acessível pela URL

    // Caso a URL da imagem não seja acessível, o FPDF gerará um erro

    
    // Salvar o certificado
    $nomeCertificado = "certificados/{$matricula}_certificado.pdf";
    $pdf->Output('F', $nomeCertificado);
    echo "Certificado gerado com sucesso para o aluno $nome (Matrícula: $matricula)!\n";
}




// Função para editar um aluno
function editarAluno($matricula) {
    $alunos = file('dados/alunos.txt');
    $encontrado = false;
    $alunoEditado = [];
    foreach ($alunos as $index => $aluno) {
        $dados = explode(";", $aluno);
        if ($dados[1] == $matricula) {
            $encontrado = true;
            echo "Aluno encontrado: {$dados[0]}\n";

            // Editar nome
            echo "Digite o novo nome (atual: {$dados[0]}): ";
            $nome = trim(fgets(STDIN));

            // Editar notas
            list($nota1, $nota2, $nota3) = inserirNotas();
            $media = calcularMedia($nota1, $nota2, $nota3);
            $status = verificarAprovacao($media);

            // Atualiza os dados
            $alunoEditado[] = "$nome;$matricula;$nota1;$nota2;$nota3;".number_format($media, 1).";$status\n";
        } else {
            $alunoEditado[] = $aluno; // mantém o aluno caso não seja o que está sendo editado
        }
    }

    if ($encontrado) {
        // Sobrescrever o arquivo com os dados atualizados
        file_put_contents('dados/alunos.txt', implode("", $alunoEditado));
        echo "Dados do aluno atualizados com sucesso!\n";
    } else {
        echo "Aluno com matrícula $matricula não encontrado.\n";
    }
}

// Função para apagar um aluno
function apagarAluno($matricula) {
    $alunos = file('dados/alunos.txt');
    $encontrado = false;
    $alunoAtualizado = [];
    foreach ($alunos as $index => $aluno) {
        $dados = explode(";", $aluno);
        if ($dados[1] == $matricula) {
            $encontrado = true;
            echo "Aluno encontrado: {$dados[0]} - Apagando...\n";
        } else {
            $alunoAtualizado[] = $aluno; // mantém o aluno caso não seja o que está sendo apagado
        }
    }

    if ($encontrado) {
        // Sobrescrever o arquivo com os alunos restantes
        file_put_contents('dados/alunos.txt', implode("", $alunoAtualizado));
        echo "Aluno apagado com sucesso!\n";
    } else {
        echo "Aluno com matrícula $matricula não encontrado.\n";
    }
}

// Função para gerar certificado para alunos aprovados
function gerarCertificadoParaAprovados() {
    echo "Digite a matrícula do aluno para gerar o certificado: ";
    $matriculaCertificado = trim(fgets(STDIN));
    $alunos = file('dados/alunos.txt');
    $certificadoGerado = false;
    foreach ($alunos as $aluno) {
        $dados = explode(";", $aluno);
        
        // Verificar se o aluno tem a matrícula correta e está aprovado
        if (trim($dados[1]) == $matriculaCertificado && trim($dados[6]) == "Aprovado") {
            gerarCertificado(trim($dados[0]), trim($dados[1])); // Gerar o certificado
            $certificadoGerado = true;
            break;
        }
    }

    if (!$certificadoGerado) {
        echo "Aluno não encontrado ou não aprovado para receber o certificado.\n";
    }
}


// Função para login de usuário
function login() {
    echo "Digite o nome de usuário: ";
    $usuario = trim(fgets(STDIN));
    echo "Digite a senha: ";
    $senha = trim(fgets(STDIN));

    $usuarios = file('dados/usuarios.txt');
    foreach ($usuarios as $linha) {
        list($user, $pass) = explode(";", trim($linha));
        if ($user == $usuario && $pass == $senha) {
            return true;
        }
    }
    return false;
}

// Menu para o usuário escolher a ação
function menu() {
    // Autenticação de usuário
    echo "Bem-vindo! Por favor, faça login para continuar.\n";
    if (!login()) {
        echo "Usuário ou senha incorretos. Saindo...\n";
        return;
    }

    do {
        echo "\nEscolha uma opção:\n";
        echo "1. Cadastrar aluno\n";
        echo "2. Consultar alunos cadastrados\n";
        echo "3. Editar aluno\n";
        echo "4. Apagar aluno\n";
        echo "5. Gerar certificado de aprovado\n";
        echo "6. Sair\n";
        echo "Opção: ";
        $opcao = trim(fgets(STDIN));
        
        switch ($opcao) {
            case 1:
                // Cadastrar aluno
                echo "\nCadastro de novo aluno\n";
                $aluno = cadastrarAluno();
                list($nota1, $nota2, $nota3) = inserirNotas();
                $media = calcularMedia($nota1, $nota2, $nota3);
                $status = verificarAprovacao($media);
                salvarAlunoArquivo($aluno['nome'], $aluno['matricula'], $nota1, $nota2, $nota3, $media, $status);
                echo "Aluno cadastrado com sucesso!\n";
                break;
            case 2:
                // Consultar alunos cadastrados
                consultarAlunos();
                break;
            case 3:
                // Editar aluno
                echo "Digite a matrícula do aluno que deseja editar: ";
                $matriculaEditar = trim(fgets(STDIN));
                editarAluno($matriculaEditar);
                break;
            case 4:
                // Apagar aluno
                echo "Digite a matrícula do aluno que deseja apagar: ";
                $matriculaApagar = trim(fgets(STDIN));
                apagarAluno($matriculaApagar);
                break;
            case 5:
                // Gerar certificado para alunos aprovados
                gerarCertificadoParaAprovados();
                break;
            case 6:
                echo "Saindo...\n";
                break;
            default:
                echo "Opção inválida! Tente novamente.\n";
        }
    } while ($opcao != 6);
}

// Executa o menu
menu();
?>

