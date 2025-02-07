# app_matricula PHP 8.1
App de matrícula escolar para treinar o PHP Shell.

Este aplicativo permite realizar o cadastro de alunos, calcular suas médias, verificar se estão aprovados ou reprovados, e gerar certificados para alunos aprovados.

## Funcionalidades:
- **Cadastro de alunos**: Permite cadastrar alunos com suas respectivas notas.
- **Cálculo de média**: A média do aluno é calculada com base em 3 notas.
- **Aprovação/Reprovação**: Alunos com média maior ou igual a 7.0 são aprovados, os demais são reprovados.
- **Certificado**: Alunos aprovados podem gerar um certificado em PDF, com selo de aprovação e data de geração.
- **CRUD de alunos**: Permite editar e excluir alunos.
- **Autenticação de usuários**: Um sistema simples de login para acessar o app e suas funcionalidades.
  
## Como Configurar:
1. **Estrutura de Arquivos**:
   - O aplicativo utiliza arquivos `.txt` para armazenar dados dos alunos e usuários. A estrutura de diretórios do app é a seguinte:
     ```
     /app
         /certificados      # Diretório onde os certificados PDF serão salvos.
         /dados
             /usuarios.txt  # Contém os usuários para login (login;senha).
             /alunos.txt    # Contém os dados dos alunos (nome;matricula;nota1;nota2;nota3;status).
         /fpdf               # Biblioteca FPDF usada para gerar os certificados PDF.
         /imagens            # Contém imagens (ex: selo de aprovação).
     ```

2. **Arquivo `usuarios.txt`**:
   - Este arquivo contém os dados dos usuários para autenticação. Cada linha tem o formato: `login;senha`.
   - Exemplo:
     ```
     admin;1234
     user1;senha123
     ```

3. **Arquivo `alunos.txt`**:
   - Este arquivo contém os dados dos alunos. Cada linha tem o formato: `nome;matricula;nota1;nota2;nota3;status`.
   - Exemplo:
     ```
     Ricardo Silva;2025;8.5;7.6;9.0;Aprovado
     Maria Souza;2026;6.5;7.2;5.8;Reprovado
     ```

4. **Imagem do Selo de Aprovação**:
   - Coloque a imagem do selo de aprovação no diretório `/imagens/` (por exemplo, `selo-aprovado.png`).
   - A imagem será inserida no rodapé do certificado gerado.

## Como Usar:
1. **Abrir o terminal ou prompt de comando**:
   - Navegue até o diretório onde o aplicativo está salvo. 
   - Use o seguinte comando para rodar o aplicativo:
     ```bash
     php -f index.php
     ```

2. **Login**:
   - O sistema solicita o login e a senha. Use as credenciais definidas no arquivo `usuarios.txt`.

3. **Cadastro de Aluno**:
   - Após o login, você pode cadastrar um aluno, fornecendo seu nome, matrícula e as notas das 3 avaliações.
   - O sistema calcula a média e determina se o aluno foi aprovado ou reprovado.

4. **Emissão de Certificado**:
   - Alunos aprovados podem gerar um certificado em PDF. O certificado contém o nome do aluno, matrícula, status e um selo de aprovação.

5. **Editar e Excluir Alunos**:
   - O sistema também permite editar notas de alunos e excluir alunos cadastrados.

## Estrutura de Dados:
### `alunos.txt`
O arquivo `alunos.txt` contém os seguintes dados, separados por ponto e vírgula:
- **nome**: Nome completo do aluno.
- **matricula**: Matrícula única do aluno.
- **nota1, nota2, nota3**: As 3 notas obtidas pelo aluno.
- **status**: "Aprovado" ou "Reprovado", dependendo da média do aluno.

### `usuarios.txt`
O arquivo `usuarios.txt` contém os dados de login dos usuários para acessar o aplicativo:
- **login**: Nome de usuário.
- **senha**: Senha para login.

## Como Contribuir:
1. **Fork do Repositório**: Faça um fork deste repositório.
2. **Crie uma Branch**: Crie uma branch com um nome relevante.
3. **Faça as alterações**: Implemente as melhorias ou correções desejadas.
4. **Faça um Pull Request**: Envie um pull request com suas modificações.

## Licença:
Este projeto é licenciado sob a MIT License - consulte o arquivo [LICENSE](LICENSE) para mais detalhes.
