<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Arquivos TXT
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 19/03/2007 Atualizado: 21/04/2010
*/
class arquivoTxt {
  var $nome;
  var $ponteiro;
   
  /**
   * @desc Constrói objeto da Classe arqTxt
   * @param string $arquivo de Nome de Arquivo
  */
  function arquivoTxt($arquivo) {
    $this->nome = $arquivo;
  }
  
  /**
   * @desc Abre arquivo TXT para escrita
   * @param string $mode de Modo de abertura de arquivo ('w','r')
  */
  function abreTxt($mode){
    $this->ponteiro = fopen($this->nome,$mode);
  }
  
  /**
   * @desc Fecha Arquivo Txt
  */
  function fechaTxt(){
    fclose($this->ponteiro);
  }

  /**
   * @desc Escreve em Arquivo Txt
   * @param string $texto de Linha de Arquivo
  */
  function escreveLinha($texto){
    fwrite($this->ponteiro,$texto);
  }
  
  /**
   * @desc ler o conteúdo do arquivo para uma string
   * @return string de conteúdo de arquivo
  */
  function leConteudo(){  
    $conteudo = fread ($this->ponteiro, filesize ($this->nome));
    return $conteudo;
  } 
  
  /**
   * @desc ler o conteúdo do link Web para uma string
   * @param string $link de link Web
   * @return string de conteudo de link Web
  */
  function leConteudoWeb($link){  
    $conteudo = file_get_contents(trim ($link));
    return $conteudo;
  }
  
  /**
   * @desc ler o conteúdo do link Web em blocos para uma string
   * Aberto como "rb"
   * @return string de conteudo
  */
  function leConteudoWebemBlocos(){
  	$conteudo = "";
    do {
      $data = fread($this->ponteiro, 8192);
      if (strlen($data) == 0) {
        break;
      }
      $conteudo .= $data;
    } while(true);
    return $conteudo;
  } 
  
  /**
   * @desc Procura por links em link
   * @param string $link de Nome de Endereço
   * @return array de links encontrados
  */
  function procuraLinks($link){
    $links = array();
    $conteudo = $this->leConteudoWeb($link);
    $auxStr = $conteudo;
    while ($auxStr) {
      $posicao1 = strpos($auxStr,'href');
      $posicao2 = strpos($auxStr,'onClick');
      $posicao3 = strpos($auxStr,'location');
      $posicao = 0;
      if ($posicao1 > 0) {
        $posicao = $posicao1;
      } 
      if ((($posicao2 < $posicao) or ($posicao == 0) ) and ($posicao2 > 0))  {
        $posicao = $posicao2;
      } 
      if ((($posicao3 < $posicao) or ($posicao == 0)) and ($posicao3 > 0))  {
        $posicao = $posicao3;
      } 
      if ($posicao > 0) {
        $auxStr = substr($auxStr,$posicao+1,strlen($auxStr));
        $posicaoAspas = strpos($auxStr,"'");
        $posicaoAspasDuplas = strpos($auxStr,'"');
        $posicaoInicial = 0; 
        if ((($posicaoAspas < $posicaoInicial) or ($posicaoInicial == 0)) and ($posicaoAspas > 0))  {
          $posicaoInicial = $posicaoAspas;
          $tipo = "Aspas";
        }
        if ((($posicaoAspasDuplas < $posicaoInicial) or ($posicaoInicial == 0)) and ($posicaoAspasDuplas > 0))  {
          $posicaoInicial = $posicaoAspasDuplas;
          $tipo = "AspasDuplas";
        }
        if ($tipo == "Aspas"){
          $auxStr2 = substr($auxStr,$posicaoInicial+1,strlen($auxStr));
          $posicaoFinal = strpos($auxStr2,"'");	
        }
        if ($tipo == "AspasDuplas"){
          $auxStr2 = substr($auxStr,$posicaoInicial+1,strlen($auxStr));	
          $posicaoFinal = strpos($auxStr2,'"');	
        }	
        	
        $enderecoLink = substr($auxStr,$posicaoInicial+1,$posicaoFinal);
        if (!strpos($enderecoLink,"ttp:")) {
          $posicaoBarra = 0;
          $posicaoBarra = strrpos($link,"/");
          if (!$posicaoBarra){
          	$posicaoBarra = strrpos($link,"?");
          } 
          if (!$posicaoBarra){
          	$posicaoBarra = strlen($link);
          } 		
          $enderecoLink = substr($link,0,$posicaoBarra)."/".$enderecoLink;	
        }	
        $links[] = $this->unhtmlentities($enderecoLink);
      } 
    
      if ($posicao == 0) {
        $auxStr = "";
      }  
    }
    return $links;
  } 
  
  /**
   * @desc Procura por Conteúdo em link passado
   * @param string $link de Link para pesquisa
   * @param string $conteudo de Conteúdo procurado
   * @return integer de posicao de conteudo em link
  */
  function procuraConteudo($link,$conteudo){
    $site = $this->leConteudoWeb($link);
    return strpos($site,$conteudo);   
  }
  
  /**
   * @desc Procura por Conteúdo em lista de links 
   * @return array $linksOk de Lista de Endereços com conteúdo requerido
   * @param array $links de Lista de Endereços
   * @param string $conteudo de Nome de Endereço 
  */
  function procuraConteudoLinks($links,$conteudo){
  	$linksOk = array();
  	for ($i=0; $i< sizeOf($links) ;$i++) {
  	  if ($this->procuraConteudo($links[$i],$conteudo)) { 	
  	    $linksOk[] = $links[$i];
  	  }    	
  	}	
    return $linksOk;   
  }
  
  /**
   * @desc Trata upload de arquivo 
  */
  function carregaArqivoUpload(){
    $uploaddir = './uploads/';
    $uploadfile = $uploaddir . $_FILES['userfile']['name'];
    echo "<pre>";
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploaddir . $_FILES['userfile']['name'])) {
      echo "O arquivo é valido e foi carregado com sucesso. Aqui esta alguma informação:\n";
      print_r($_FILES);
    } else {
      echo "Possivel ataque de upload! Aqui esta alguma informação:\n";
      print_r($_FILES);
    }
    echo "</pre>";
  }  
  
  /**
   * @desc Preenche array com Linhas a partir de linhas de arquivo 
   * @return array $linhas de Linhas de arquivo
  */
  function arquivoLinhas(){
  	$linhas = array();
  	while (!feof($this->ponteiro)) {
  	  $linhas[] = fgets($this->ponteiro);
 	}  
    return $linhas;	
  }	
  
  /**
   * @desc Retira HTML entities
   * @param string $string de Texto para retirada de HTML entities
   * @return string de Texto sem HTML entities
  */
  function unhtmlentities ($string) {
    $trans_tbl1 = get_html_translation_table (HTML_ENTITIES);
    foreach ( $trans_tbl1 as $ascii => $htmlentitie ) {
      $trans_tbl2[$ascii] = '&#'.ord($ascii).';';
    }
    $trans_tbl1 = array_flip ($trans_tbl1);
    $trans_tbl2 = array_flip ($trans_tbl2);
    return strtr (strtr ($string, $trans_tbl1), $trans_tbl2);
  }
    
  /**
   * @desc Preenche array com Linhas e Colunas a partir de linhas de arquivo CSV
   * @return array $linhas de Linhas
  */
  function arquivoCSV(){
  	$linhas = $this->arquivoLinhas();
  	$dados = array();
  	for ($linha = 0; $linha < count($linhas); $linhas++) {
  	  $dados[] = explode(";",$linhas);
 	}  
 	return $dados;
  } 	
  
  /**
   * @desc Gera linha CSV a partir de Array
   * @return boolean de sucesso
  */
  function escreveCSV($dados){
  	for ($i = 0; $i < count($dados); $i++) {
  	  $linha = implode(";",$dados[$i]);
	  echo $linha."\n";
 	}  
 	return true;
  } 	
   	
}