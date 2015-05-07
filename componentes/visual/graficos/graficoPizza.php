<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe para geração de gráfico de pizza
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 22/03/2007 Atualizado: 22/03/2007
*/
class graficoPizza {
  
  //configurações de gráfico
  var $largura;
  var $altura;
  
  //configurações de círculo
  var $centrox;
  var $centroy;
  var $diametro;
  var $angulo;
  
  //Configurações da Legenda
  var $exibir_legenda;
  var $fonte;
  var $largura_fonte;
  var $altura_fonte;
  var $espaco_entre_linhas;
  var $margem_vertical;
  
  //Canto superior direito da Legenda
  var $lx;
  var $ly;
  
  //Definição de Dados
  var $linhas;
  var $valores;
  var $cores;
  
  /**
   * @desc Contrutor de Classe de Gráfico de Pizza 
  */
  function graficoPizza (){
    //configurações de gráfico
    $this->largura = 600;
    $this->altura = 400;
    //configurações de círculo
    $this->centrox = 200;
    $this->centroy = 200;
    $this->diametro = 280;
    $this->angulo = 0;
    //Configurações da Legenda
    $this->exibir_legenda = "sim";
    $this->fonte = 3;
    $this->largura_fonte = 8;
    $this->altura_fonte = 10;
    $this->espaco_entre_linhas = 10;
    $this->margem_vertical = 5;
    //Canto superior direito da Legenda
    $this->lx = 540;
    $this->ly = 30;
  }

  /**
   * @desc Define Legenda de Gráfico
  */
  function defineLegenda($exibir_legenda,$fonte = 8,$largura_fonte = 8,$altura_fonte = 10,$espaco_entre_linhas = 10, $margem_vertical = 5, $lx= 540, $ly = 30){
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
   * @desc Define Valores de Círculo de Pizza 
  */
  function defineCirculo($centrox,$centroy,$diametro,$angulo){
    $this->centrox = $centrox;
    $this->centroy = $centroy;
    $this->diametro = $diametro;
    $this->angulo = $angulo;
  }

  /**
   * @desc Define dados de Gráfico
  */
  function defineGrafico($largura,$altura){
    $this->largura = $largura;
    $this->altura = $altura;
  }
  
  /**
   * @desc Define dados de Gráfico
  */
  function defineDados($linhas,$valores){
    $this->linhas  = $linhas;
    $this->valores = $valores;
  }
  
  /**
   * @desc Desenha Gráfico
  */
  function desenhaGrafico(){
    header("Content-type: image/png");
    //Cria imagem e define cores
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
    
    $this->cores = array($azul,$verde,$vermelho,$amarelo,$azulVerde,$laranjado,$grafite);
    
    //Cálculo do Total
    $total = 0;
    $num_linhas = sizeof($this->linhas);
    for ($i=0;$i<$num_linhas;$i++)
      $total += $this->valores[$i];
      
    //Desenha o Gráfico
    ImageEllipse($imagem,$this->centrox,$this->centroy,$this->diametro,$this->diametro,$preto);
    ImageString($imagem,$this->fonte,3,3,"Total: ".$total,$preto);
    
    $raio = $this->diametro/2;
    for ($i=0;$i<$num_linhas;$i++) {
      $percentual = ($this->valores[$i]/ $total)*100;
      $percentual = number_format($percentual,2);
      $percentual .= "%";
      
      $val = 360 * ($this->valores[$i]/ $total);
      $angulo += $val;
      $angulo_meio = $angulo - ($val/2);
      
      $x_final = $this->centrox + $raio * cos(deg2rad($angulo));
      $y_final = $this->centroy + (- $raio * sin(deg2rad($angulo)));
      
      $x_meio = $this->centrox + ($raio/2 * cos(deg2rad($angulo_meio)));
      $y_meio = $this->centroy + (- $raio/2 * sin(deg2rad($angulo_meio)));
      
      $x_texto = $this->centrox + ($raio * cos(deg2rad($angulo_meio))) * 1.2;
      $y_texto = $this->centroy + (- $raio * sin(deg2rad($angulo_meio))) * 1.2;
      
      ImageLine($imagem, $this->centrox, $this->centroy, $x_final, $y_final, $preto);
      ImageFillToBorder($imagem, $x_meio, $y_meio, $preto, $this->cores[$i]);
      ImageString($imagem,2,$x_texto,$y_texto, $percentual, $preto);
    }
    
    //Criação de Legenda
    if ($this->exibir_legenda == "sim") {
      //acha a maior string
      $maior_tamanho = 0;
      for ($i=0;$i<$num_linhas;$i++)
        if (strlen($this->linhas[$i])>$maior_tamanho)
          $maior_tamanho = strlen($this->linhas[$i]);
          
      // calcula os pontos de início e fim do quadrado
      $x_inicio_legenda = $this->lx - $this->largura_fonte * ($maior_tamanho+4);
      $y_inicio_legenda = $this->ly;

      $x_fim_legenda = $this->lx;
      $y_fim_legenda = $this->ly + $num_linhas * ($this->altura_fonte + $this->espaco_entre_linhas) + 2* $this->margem_vertical;

      ImageRectangle($imagem, $x_inicio_legenda, $y_inicio_legenda, $x_fim_legenda, $y_fim_legenda, $preto);

      // começa a desenhar os dados
      for($i=0; $i<$num_linhas; $i++) {
        $x_pos = $x_inicio_legenda + $this->largura_fonte*3;
        $y_pos = $y_inicio_legenda + $i * ($this->altura_fonte + $this->espaco_entre_linhas) + $this->margem_vertical;

        ImageString($imagem, $this->fonte, $x_pos, $y_pos, $this->linhas[$i] , $preto);
        ImageFilledRectangle ($imagem, $x_pos - 2*$this->largura_fonte, $y_pos, $x_pos - $this->largura_fonte, $y_pos + $this->altura_fonte, $this->cores[$i]);
        ImageRectangle ($imagem, $x_pos - 2 * $this->largura_fonte, $y_pos, $x_pos - $this->largura_fonte, $y_pos + $this->altura_fonte, $preto);
      }
    }
    ImagePng($imagem);
    ImageDestroy($imagem);
  }
}
?>
