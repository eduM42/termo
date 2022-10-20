<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>TERMO</title>
</head>
<body>
    <?php
        /*
        FUNÇÕES NECESSÁRIAS:

        1 - Inicia o jogo com uma nova palavra a partir de um array

        2 - Verifica o palpite contra a palavra do jogo a ser descoberta

        3 - Desenha o jogo na tela conforme as cores necessárias (normal, amarelo e verde)

        4 - Define as cores dos blocos

        */

        $palavras = array("cabos", "solda");

        exibe($v); // Chama a função que exibe o jogo, ela chama todas as outras em sua execução: exibe -> verifica - init_jogo


        /*--------------------------------- Definição das funções ---------------------------------*/

        function init_game($v = array()){ // Inicia o jogo recebendo ao array de palavras
            session_start(); // Inicia uma sessão para armazenar as variáveis SESSION
            if($_SESSION['status'] == false){ // Se o status do jogo for FALSE, inicia as variávies e sorteia uma palavra
                $seletor = rand(0,1); // Escolhe um número aleatório
                $palavra = $v[$seletor]; // A palavra do jogo vai ser correspondente ao vetor passado como parâmetro na posição do número armazenado em $seletor
                $_SESSION['linha'] = 0; // Inicia a variável de sessão que armazena a linha que o jogo está
                $_SESSION['palavra'] = $palavra; // Inicia a variável de sessão que armazena a palavra
                $_SESSION['status'] = true; // Muda o status do jogo para true, assim não irá resetar o jogo a cada palpite por conta do if na linha 34
                $color = array( // Cria a matriz que irá controlar as cores; contém vários campos para comportar qualquer palavra, independente do comprimento; array dentro de array = matriz
                    array(),
                    array(),
                    array(),
                    array(),
                    array(),
                    array(),
                    array(),
                    array(),
                    array(),
                    array(),
                    array(),
                    array(),
                    array()
                );
                for ($i=0; $i < 5; $i++) { // Para as 5 linhas do jogo;
                    for ($j=0; $j < strlen($palavra); $j++) { // E para todas as letras necessárias por linha
                        $color[$i][$j] = 0; // Preenche a matriz que controlas as cores com o valor incial 0, correspondente ao "normal"
                    }
                }
                $_SESSION['color'] = $color; // Armazena na variável de sessão a matriz das cores

            }
            $palavra = $_SESSION['palavra']; // // Recebe a variável da palavra
            return $palavra; // Retorna a palavra selecionada quando a função for chamada
        }



        /*--------------------------------- FINALIZA JOGO ---------------------------------*/
        function end_game($w){ // Função incompleta - Deveria finalizar o jogo
            if($w == false){
                echo ("<h1>FIM</h1>");
            }else{
                echo ("<h1>FIM</h1>");

            }
        }



        /*--------------------------------- VERIFICA JOGO ---------------------------------*/
        function verifica($v=array()){ // Faz todo o processo de verificação do palpite contra a palavra e do progresso do jogo
            $palavra = init_game($v); // Recebe a palavra do jogo através da função init_game (que contém um return)
            $palpite = array( // Inicia o array dos palpites, que recebe até 5 tentativas, por isso 5 campos com um caractere vazio
                "",
                "",
                "",
                "",
                ""
            );
            // $palavra = $_SESSION['palavra'];

            // $palpite = "armas";
            $palavra = "azingababa"; // PROVISORIAMENTE define a palavra do jogo independentemente daquela recebida na função init_game. Para propósitos de teste
            $_SESSION['palavra'] = $palavra; //  Armazena na variável de sessão a palavra da rodada
            $palpite = $_SESSION['palpite']; // Pega a matriz que contém todos os palpites dados na rodada do jogo, contém as linhas e as letras de cada linha
            $color = $_SESSION['color']; // Pega a matriz de cores que contém todas as definições da rodada
            $linha = $_SESSION['linha']; // Pega a linha atual do jogo
            $p = $_GET['palpite']; // Define que a variável p contém o palpite do jogador
            $palpite[$linha] = $p; // Atribui a matriz palpite, na linha atual da rodada, o palpite do jogador
            $elapsed = array(); // Define que a variável elapsed será um array simples (uma dimensão)

            if($linha == 6 && strcmp($palpite[$linha-1], $palavra) == 0){ // Se a linha for igual a 6, ou seja, o jogador já deu 5 palpites, e o último palpite estiver correto
                end_game(true); // Fala que o jogador ganhou
            }elseif($linha == 6 && strcmp($palpite[$linha-1], $palavra) != 0){ // Se a linha for igual a 6, ou seja, o jogador já deu 5 palpites, e o último palpite estiver errado
                end_game(false); // Fala que o jogador perdeu
            }
            if(strcmp($palpite[$linha], $palavra) == 0){ // Se o palpite estiver correto
                return end_game(true); // Fala que o jogador ganhou
            }elseif($linha == 6){ // REDUNDANTE
                return end_game(false); // REDUNDANTE
            }else{ // Caso nenhuma dessas condições sejam verdadeiras, o jogo continua
                for ($i=0; $i < strlen($palavra); $i++) { // Para cada letra do palpite
                    for ($j=0; $j < strlen($palavra); $j++) { // Olha para todas as letras da palavra
                        if($palpite[$linha][$i] == $palavra[$j] && $i == $j){ // Se a letra analisada do palpite e palavra forem iguais e estiverem na mesma posição
                            $color[$linha][$i] = 3; // Atribui a cor verde
                            $elapsed[$i] = 3; // Marca que a letra em questão já tem a cor verde (confere pela variável $i que aponta a letra do palpite)
                        }elseif($palpite[$linha][$i] == $palavra[$j] && $i != $j && $elapsed[$i] == 0){ // Se a letra do palpite e palavra forem iguais, mas em posições diferentes e apenas de a cor da letra já não tiver sido definida
                            $color[$linha][$i] = 2; // Atribui a cor amarela
                            $elapsed[$i] = 2; // Marca que a letra em questão já tem a cor verde (confere pela variável $i que aponta a letra do palpite)
                        }elseif($palpite[$linha][$i] != $palavra[$j] && $elapsed[$i] == 0 && $j >= strlen($palavra-1)){  // Confere se a letra do palpite não é igual a nenhuma da palavra e se está na última letra possível
                            $color[$linha][$i] = 1; // Atribui a cor preta
                            $elapsed[$i] = 1; // Marca que a letra em questão já tem a cor verde (confere pela variável $i que aponta a letra do palpite)
                        }
                    }
                }
            }
            $_SESSION['color'] = $color; // Armazena o vetor das cores na sessão
            $_SESSION['linha'] = $linha++; // Armazena a próxima linha do jogo na sessão
            $_SESSION['palpite'] = $palpite; // Armazena todos os palpites com o palpite anteiror dados até o momento no jogo
            return [$palpite, $color]; // Retorna o array de palpites e das cores
        }



        /*--------------------------------- CORES DOS BLOCOS ---------------------------------*/
        function green($palpite, $endlinha, $l){ // Exibe uma box verde - Recebe como parâmetros a letra atual sendo mostrada, a posição da letra, a quantidade de letras na palavra
            if($endlinha < ($l-1)){ // Se não for a última letra da palavra
                echo "<button name='genbtt' id='gbox'>$palpite</button>"; // Imprime uma box ao lado com o palpite
            }else{ // Se for a última letra
                echo "<button name='genbtt' id='gbox'>$palpite</button><br>"; // Imprime a box e faz uma quebra de linha
            }
        }

        function yellow($palpite, $endlinha, $l){ // Exibe uma box amarela - Recebe como parâmetros a letra atual sendo mostrada, a posição da letra, a quantidade de letras na palavra
            if($endlinha < ($l-1)){ // Se não for a última letra da palavra
                echo "<button name='genbtt' id='ybox'>$palpite</button>"; // Imprime uma box ao lado com o palpite
            }else{ // Se for a última letra
                echo "<button name='genbtt' id='ybox'>$palpite</button><br>"; // Imprime a box e faz uma quebra de linha
            }
        }

        function black($palpite, $endlinha, $l){ // Exibe uma box preta - Recebe como parâmetros a letra atual sendo mostrada, a posição da letra, a quantidade de letras na palavra
            if($endlinha < ($l-1)){ // Se não for a última letra da palavra
                echo "<button name='genbtt' id='bbox'>$palpite</button>"; // Imprime uma box ao lado com o palpite
            }else{ // Se for a última letra
                echo "<button name='genbtt' id='bbox'>$palpite</button><br>"; // Imprime a box e faz uma quebra de linha
            }
        }

        function normal($palpite, $endlinha, $l){ // Exibe uma box normal - Recebe como parâmetros a letra atual sendo mostrada, a posição da letra, a quantidade de letras na palavra
            if($endlinha < ($l-1)){ // Se não for a última letra da palavra
                echo "<button name='genbtt' id='nbox'>$palpite</button>"; // Imprime uma box ao lado com o palpite
            }else{ // Se for a última letra
                echo "<button name='genbtt' id='nbox'>$palpite</button><br>"; // Imprime a box e faz uma quebra de linha
            }
        }



        /*--------------------------------- EXIBE JOGO ---------------------------------*/
        function exibe($v=array()){ // Imprime tudo na tela
            $linha = 0; // Declara a variável linha, não importa o valor aqui
            $data = verifica($v); // Data de dados, já que o return de verifica retorna dois valores como uma lista -> [1,2] isso é uma lista (qualquer coisa entre colchetes)
            $palavra = $_SESSION['palavra']; // Busca a palavra na variável session
            echo $palavra; // PROVISÓRIO - Imprime a palavra da rodada na tela
            $linha = $_SESSION['linha']; // Busca em qual linha que está o jogo, ou seja, em qual tentativa
            if($linha == 5){ // Se a linha for 5;
                $_SESSION['status'] = false; // Muda o status do jogo para false para que o jogo seja reiniciado
            }
            
            $palpite = $_SESSION['palpite']; // Busca o array que contém todos os palpites até o momento
            $cor = $data[1]; // Busca na posição 1 (0 e 1) da variável data, que contém o retorno de verifica, a matiz que contém as cores das boxes

            echo "<h1>".$linha."</h1>"; // PROVISÓRIO - Exibe a linha atual

            for ($i=0; $i < ($linha); $i++) { // Para todas as linhas jogadas até agora
                echo "<div id='center'>"; // Centraliza o conteúdo
                for ($j=0; $j < strlen($palavra); $j++) {  // Para todas as letras da palavra
                    if($cor[$i][$j] == 3){ // Se o código da cor for 3
                        green($palpite[$i+1][$j], $j, strlen($palavra)); // Desenha uma box com a borda verde, com a letra atual do palpite, na posição atual conferida e com o comprimento máximo igual ao da palavra da rodada
                    }elseif($cor[$i][$j] == 2){ // Se o código da cor for 2
                        yellow($palpite[$i+1][$j], $j, strlen($palavra)); // Desenha uma box com a borda amarela, com a letra atual do palpite, na posição atual conferida e com o comprimento máximo igual ao da palavra da rodada
                    }elseif($cor[$i][$j] == 1){ // Se o código da cor for 1
                        black($palpite[$i+1][$j], $j, strlen($palavra)); // Desenha uma box com a borda preta, com a letra atual do palpite, na posição atual conferida e com o comprimento máximo igual ao da palavra da rodada
                    }elseif($cor[$i][$j] == 0){ // Se o código da cor for 0
                        normal($palpite[$i+1][$j], $j, strlen($palavra)); // Desenha uma box com a borda normal, com a letra atual do palpite, na posição atual conferida e com o comprimento máximo igual ao da palavra da rodada
                    }
                }
                echo "</div><br>"; // Finaliza a div que centraliza o conteúdo
            }
            for ($i=0; $i < (5-($linha)); $i++) { // Para cada linha que falta até chegar a 5 linhas
                echo "<div id='center'>"; // centraliza o conteúdo
                for ($j=0; $j < strlen($palavra); $j++) { // Para a quantidade de letras da palavra
                    normal("", $j, strlen($palavra)); // Desenha uma box do tipo normal vazia
                }
                echo "</div><br>"; // Finaliza a div que centraliza o conteúdo
            }
            $linha++; // Adiciona mais um a linha para a próxima jogada
            $_SESSION['linha'] = $linha; // Salva o número de linha
            echo "<h1>".$linha."</h1>"; // Imprime a linha da próxima jogada

        }
    ?>
    <br>
    <!--- Formulário que recebe o palpite --->
    <form method="GET" id="center">
        <label>PALPITE: </label>
        <input type="text" name="palpite" placeholder="palpite">
        <input type="submit" name="btnenviar" value="ENVIAR">
    </form>
</body>
</html>

