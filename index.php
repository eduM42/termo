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
        */

        $palavras = array("cabos", "solda");

        exibe($v);


        /*--------------------------------- Definição das funções ---------------------------------*/

        function init_game($v = array()){
            session_start();
            if($_SESSION['status'] == false){
                $seletor = rand(0,1);
                $palavra = $v[$seletor];
                $_SESSION['tentativas'] = array();
                $_SESSION['linha'] = 0;
                $_SESSION['palavra'] = $palavra;
                $_SESSION['status'] = true;
                $_SESSION['nc'] = strlen($palavra);
                $color = array(
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
                for ($i=0; $i < 5; $i++) { 
                    for ($j=0; $j < strlen($palavra); $j++) { 
                        $color[$i][$j] = 0;
                    }
                }
                $_SESSION['color'] = $color;

            }
            $palavra = $_SESSION['palavra'];
            return $palavra;
        }



        /*--------------------------------- FINALIZA JOGO ---------------------------------*/
        function end_game($w){
            if($w == false){
                echo ("<h1>FIM</h1>");
            }else{
                echo ("<h1>FIM</h1>");

            }
        }



        /*--------------------------------- VERIFICA JOGO ---------------------------------*/
        function verifica($v=array()){
            $palavra = init_game($v);
            $palpite = array(
                "",
                "",
                "",
                "",
                ""
            );
            // $palavra = $_SESSION['palavra'];

            // $palpite = "armas";
            $palavra = "azingababa";
            $_SESSION['palavra'] = $palavra;
            $palpite = $_SESSION['palpite'];
            $color = $_SESSION['color'];
            $linha = $_SESSION['linha'];
            $p = $_GET['palpite'];
            $palpite[$linha] = $p;
            $elapsed = array();

            if($linha == 6 && strcmp($palpite[$linha-1], $palavra) == 0){
                end_game(true);
            }elseif($linha == 6 && strcmp($palpite[$linha-1], $palavra) != 0){
                end_game(false);
            }

            if(strcmp($palpite[$linha], $palavra) == 0){
                return end_game(true);
            }elseif($linha == 6){
                return end_game(false);
            }else{
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
            $_SESSION['color'] = $color;
            $_SESSION['linha'] = $linha++;
            $_SESSION['palpite'] = $palpite;
            return [$palpite, $color];
        }



        /*--------------------------------- CORES DOS BLOCOS ---------------------------------*/
        function green($palpite, $endlinha, $l){
            if($endlinha < ($l-1)){
                echo "<button name='genbtt' id='gbox'>$palpite</button>";
            }else{
                echo "<button name='genbtt' id='gbox'>$palpite</button><br>";
            }
        }

        function yellow($palpite, $endlinha, $l){
            if($endlinha < ($l-1)){
                echo "<button name='genbtt' id='ybox'>$palpite</button>";
            }else{
                echo "<button name='genbtt' id='ybox'>$palpite</button><br>";
            }
        }

        function black($palpite, $endlinha, $l){
            if($endlinha < ($l-1)){
                echo "<button name='genbtt' id='bbox'>$palpite</button>";
            }else{
                echo "<button name='genbtt' id='bbox'>$palpite</button><br>";
            }
        }

        function normal($palpite, $endlinha, $l){
            if($endlinha < ($l-1)){
                echo "<button name='genbtt' id='nbox'>$palpite</button>";
            }else{
                echo "<button name='genbtt' id='nbox'>$palpite</button><br>";
            }
        }



        /*--------------------------------- EXIBE JOGO ---------------------------------*/
        function exibe($v=array()){
            $linha = 0;
            $data = verifica($v);
            $palavra = $_SESSION['palavra'];
            echo $palavra;
            $linha = $_SESSION['linha'];
            if($linha == 5){
                $_SESSION['status'] = false;
            }elseif($linha == 0){
                for ($i=0; $i < 5; $i++) { 
                    for ($j=0; $j < strlen($_SESSION['palavra']); $j++) { 
                        //echo 
                    }
                }
            }
            $palpite = $_SESSION['palpite'];
            $cor = $data[1];

            echo "<h1>".$linha."</h1>";
            for ($i=0; $i < 5; $i++) { 
                for ($j=0; $j < 5; $j++) { 
                    echo $cor[$i][$j]."  ";
                }
                echo "<br>";
            }

            for ($i=0; $i < ($linha); $i++) { 
                echo "<div id='center'>";
                for ($j=0; $j < strlen($palavra); $j++) { 
                    if($cor[$i][$j] == 3){
                        green($palpite[$i+1][$j], $j, strlen($palavra));
                    }elseif($cor[$i][$j] == 2){
                        yellow($palpite[$i+1][$j], $j, strlen($palavra));
                    }elseif($cor[$i][$j] == 1){
                        black($palpite[$i+1][$j], $j, strlen($palavra));
                    }elseif($cor[$i][$j] == 0){
                        normal($palpite[$i+1][$j], $j, strlen($palavra));
                    }
                }
                echo "</div><br>";
            }
            for ($i=0; $i < (5-($linha)); $i++) { 
                echo "<div id='center'>";
                for ($j=0; $j < strlen($palavra); $j++) {
                    normal("", $j, strlen($palavra));
                }
                echo "</div><br>";
            }
            $linha++;
            $_SESSION['linha'] = $linha;
            echo "<h1>".$linha."</h1>";

        }
    ?>
    <br>
    <form method="GET" id="center">
        <label>PALPITE: </label>
        <input type="text" name="palpite" placeholder="palpite">
        <input type="submit" name="btnenviar" value="ENVIAR">
    </form>
</body>
</html>

