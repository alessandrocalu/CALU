<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe para geração de gráfico de barras
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 22/03/2007 Atualizado: 22/03/2007
*/
class graficoBarras {
  
  //Configurações do gráfico
  var $titulo;
  var $largura;
  var $altura;
  var $largura_eixo_x;
  var $largura_eixo_y;
  var $inicio_grafico_x;
  var $inicio_grafico_y;
  
  //Configurações de Legenda
  var $exibir_legenda;
  var $fonte;
  var $largura_fonte;
  var $altura_fonte;
  var $espaco_entre_linhas;
  var $margem_vertical;
  
  // Canto superior direito da legenda
  var $lx;
  var $ly;
  
  //Definição de Dados
  var $linhas;
  var $colunas;
  var $valores;
  var $cores;


  /**
   * @desc Contrutor de Classe de Gráfico de Barras 
  */
  function graficoBarras (){
    //configurações de gráfico
    $this->titulo = "Gráfico de Colunas";
    $this->largura = 700;
    $this->altura = 400;
    $this->largura_eixo_x = 450;
    $this->largura_eixo_y = 300;
    $this->inicio_grafico_x = 70;
    $this->inicio_grafico_y = 360;
    //Configurações da Legenda
    $this->exibir_legenda = "sim";
    $this->fonte = 3;
    $this->largura_fonte = 8;
    $this->altura_fonte = 10;
    $this->espaco_entre_linhas = 10;
    $this->margem_vertical = 5;
    //Canto superior direito da Legenda
    $this->lx = 650;
    $this->ly = 30;
  }

  /**
   * @desc Define Legenda de Gráfico
  */
  function defineLegenda($exibir_legenda,$fonte = 8,$largura_fonte = 8,$altura_fonte = 10,$espaco_entre_linhas = 10, $margem_vertical = 5, $lx= 650, $ly = 30){
    $this->exibir_legenda = $exibir_legenda;
    $this->fonte = $fonte;
    $this->largura_fonte = $largura_fonte;
    $this->altura_fonte = $altura_fonte;
    $this->espaco_entre_linhas = $espaco_entre_linhas;
    $this->margem_vertical = $margem_vertical;
    $this->lx = $lx;
    $this->ly = $ly;
  }

  /**
   * @desc Define Valores de Gráfico
  */
  function defineGrafico($titulo,$largura,$altura,$largura_eixo_x,$largura_eixo_y,$inicio_grafico_x,$inicio_grafico_y){
    $this->titulo = $titulo;
    $this->largura = $largura;
    $this->altura = $altura;
    $this->largura_eixo_x = $largura_eixo_x;
    $this->largura_eixo_y = $largura_eixo_y;
    $this->inicio_grafico_x = $inicio_grafico_x;
    $this->inicio_grafico_y = $inicio_grafico_y;
  }

  /**
   * @desc Define dados de Gráfico
  */
  function defineDados($linhas,$colunas,$valores){
    $this->linhas  = $linhas;
    $this->colunas = $colunas;
    $this->valores = $valores;
  }
  
  /**
   * @desc Desenha Gráfico
  */
  function desenhaGrafico(){
    header("Content-type: image/png");
    $imagem = ImageCreate($this->largura,$this->altura);
    $fundo = ImageColorAllocate($imagem,236,226,226);
    $preto = ImageColorAllocate($imagem,0,0,0);
    $azul = ImageColorAllocate($imagem,0,0,255);
    $verde = ImageColorAllocate($imagem,0,255,0);
    $vermelho = ImageColorAllocate($imagem,255,0,0);
    $amarelo = ImageColorAllocate($imagem,255,255,0);
    $azulVerde = ImageColorAllocate($imagem,0,255,255);
    $laranjado = ImageColorAllocate($imagem,255,126,0);
    $grafite = ImageColorAllocate($imagem,126,126,126);

    //Titulo
    //ImageString($imagem,$this->fonte,3,3,$this->titulo,$preto);

    $this->cores = array($azul,$verde,$vermelho,$amarelo,$azulVerde,$laranjado,$grafite);
    
    $numero_linhas = sizeof($this->linhas);
    $numero_colunas = sizeof($this->colunas);
    $numero_valores = sizeof($this->valores);

    // obtém o valor máximo de y
    $y_maximo = 0;
    for($i=0 ; $i < $numero_valores; $i++)
      if($this->valores[$i] > $y_maximo)
         $y_maximo = $this->valores[$i];

    // calcula o intervalo de variação entre os pontos de y
    $fator = pow(10, strlen(intval($y_maximo))-1);
    
    if ($y_maximo < 1)
      $variacao = 0.1;
    elseif ($y_maximo < 10)
      $variacao = 1;
    elseif ($y_maximo < 2 * $fator)
      $variacao = $fator/5;
    elseif ($y_maximo < 5 * $fator)
      $variacao = $fator/2;
    elseif($y_maximo < 10 * $fator)
      $variacao = $fator;

    //calcula o número de pontos no eixo y
    $num_pontos_eixo_y = 0;
    $valor = 0;
    while ($y_maximo >= $valor){
      $valor += $variacao;
      $num_pontos_eixo_y++;
    }
    $valor_topo = $valor;
    $dist_entre_pontos = $this->largura_eixo_y / $num_pontos_eixo_y;

    //Titulo
    ImageString($imagem,$this->fonte,3,3,$this->titulo,$preto);

    //Eixos x e y
    ImageLine($imagem, $this->inicio_grafico_x, $this->inicio_grafico_y, $this->inicio_grafico_x + $this->largura_eixo_x, $this->inicio_grafico_y, $preto);
    ImageLine($imagem, $this->inicio_grafico_x, $this->inicio_grafico_y, $this->inicio_grafico_x, $this->inicio_grafico_y - $this->largura_eixo_y, $preto);

    //Pontos no eixo y
    $posy = $this->inicio_grafico_y;
    $valor = 0;
    for ($i=0; $i <= $num_pontos_eixo_y; $i++) {
      $posx = $this->inicio_grafico_x - (strlen($valor)+4)*6;
      ImageString($imagem, 2, $posx, $posy-7, $valor, $preto);
      ImageLine($imagem, $this->inicio_grafico_x-6, $posy, $this->inicio_grafico_x + $this->largura_eixo_x, $posy, $preto);
      $valor += $variacao;
      $posy -= $dist_entre_pontos;
    }
    
    //Colunas no eixo x
    $num_barras = $numero_linhas * $numero_colunas;
    $largura_barra = floor($this->largura_eixo_x / ($num_barras+$numero_colunas+1));
    $posx = $this->inicio_grafico_x + $largura_barra;

    for($i = 0; $i < $numero_colunas; $i++) {
      $pos_label_x = $posx + ($largura_barra * $numero_linhas/2) - (strlen($this->colunas[$i])*6/2);
      $pos_label_y = $this->inicio_grafico_y+10;

      ImageString($imagem, 2, $pos_label_x, $pos_label_y, $this->colunas[$i] , $preto);

      // imprime as barras
      for ($j = $i; $j < $numero_valores; $j += $numero_colunas) {
        $altura_barra = $this->valores[$j]/$valor_topo * $this->largura_eixo_y;
        $indice_cor = intval( $j / $numero_colunas);
        
        ImageFilledRectangle($imagem, $posx, $this->inicio_grafico_y - $altura_barra, $posx + $largura_barra, $this->inicio_grafico_y, $this->cores[$indice_cor]);
        ImageRectangle($imagem, $posx, $this->inicio_grafico_y - $altura_barra, $posx + $largura_barra, $this->inicio_grafico_y, $preto);
        $posx += $largura_barra;
      }
      $posx += $largura_barra;
    }


    //Criação de Legenda
    if ($this->exibir_legenda == "sim") {
      //acha a maior string
      $maior_tamanho = 0;
      for ($i=0;$i<$numero_linhas;$i++)
        if (strlen($this->linhas[$i])>$maior_tamanho)
          $maior_tamanho = strlen($this->linhas[$i]);

      // calcula os pontos de início e fim do quadrado
      $x_inicio_legenda = $this->lx - $this->largura_fonte * ($maior_tamanho+4);
      $y_inicio_legenda = $this->ly;

      $x_fim_legenda = $this->lx;
      $y_fim_legenda = $this->ly + $numero_linhas * ($this->altura_fonte + $this->espaco_entre_linhas) + 2* $this->margem_vertical;

      ImageRectangle($imagem, $x_inicio_legenda, $y_inicio_legenda, $x_fim_legenda, $y_fim_legenda, $preto);

      // começa a desenhar os dados
      for($i=0; $i<$numero_linhas; $i++) {
        $x_pos = $x_inicio_legenda + $this->largura_fonte*3;
        $y_pos = $y_inicio_legenda + $i * ($this->altura_fonte + $this->espaco_entre_linhas) + $this->margem_vertical;
        ImageString($imagem, $this->fonte, $x_pos, $y_pos, $this->linhas[$i] , $preto);
        ImageFilledRectangle($imagem, $x_pos - 2*$this->largura_fonte, $y_pos, $x_pos - $this->largura_fonte, $y_pos + $this->altura_fonte, $this->cores[$i]);
        ImageRectangle($imagem, $x_pos - 2 * $this->largura_fonte, $y_pos, $x_pos - $this->largura_fonte, $y_pos + $this->altura_fonte, $preto);
      }
    }
    ImagePng($imagem);
    ImageDestroy($imagem);
  }
}

?>
