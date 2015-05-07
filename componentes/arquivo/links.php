<?
$local = $_GET['local'];
$conteudo = $_GET['conteudo'];
$nomeArquivo = $_GET['arquivo'];
$acao = $_GET['acao'];
if ($_POST['acao']) {
  $local = $_POST['local'];
  $conteudo = $_POST['conteudo'];
  $nomeArquivo = $_POST['arquivo'];
  $acao = $_POST['acao'];
} 

include "arquivoTxt.php";

if ($acao == "upload") {
  $arquivo = new arquivoTxt("vazio");	
  $arquivo->carregaArqivoUpload();	
}

if ($acao == "links") {
  $arquivo = new arquivoTxt("uploads/".$nomeArquivo);
  $arquivoGrava = new arquivoTxt("uploads/resultados.txt");
  $arquivo->abreTxt("rt");
  $arquivoGrava->abreTxt("wt");
  $links = $arquivo->arquivoLinhas();
  $linksAchados = array();
  echo "Links pesquisas:<br><br>";
  for ($i=0;$i< sizeOf($links) ;$i++) {
    echo $links[$i]."<br>";
    $linksAuxiliar = $arquivo->procuraLinks($links[$i]);
    for ($j=0; $j < sizeOf($linksAuxiliar) ;$j++) {
      $arquivoGrava->escreveLinha($linksAuxiliar[$j]."\n");	
      $linksAchados[] = $linksAuxiliar[$j]; 	
    }	
  }
  echo "Links achados:<br><br>";
  for ($i=0;$i< sizeOf($linksAchados) ;$i++) {
  	echo $linksAchados[$i]."<br>";
  }	
  $arquivo->fechaTxt();	
  $arquivoGrava->fechaTxt();
  echo "<br>";
  echo "<a href='uploads/resultados.txt'>resultados.txt</a><br>";
}	    
	

if ($acao == "conteudo") {
  $arquivo = new arquivoTxt("uploads/resultados.txt");
  $arquivoGrava = new arquivoTxt("uploads/resultados2.txt");
  $arquivo->abreTxt("rt");
  $arquivoGrava->abreTxt("wt");		
  $links = $arquivo->arquivoLinhas();
  $linksOk = $arquivo->procuraConteudoLinks($links,$conteudo);
  echo "Links pesquisas:<br><br>";
  for ($i=0;$i< sizeOf($links) ;$i++) {
    echo $links[$i]."<br>";
  }
  echo "<br><br>Links com conteúdo: <br><br>";
  for ($i=0; $i < sizeOf($linksOk) ;$i++) {
  	$arquivoGrava->escreveLinha($linksOk[$i]."\n");	
    echo "<a href='".$linksOk[$i]."'>".$linksOk[$i]."</a><br>";
  }
  $arquivo->fechaTxt();	
  $arquivoGrava->fechaTxt();
  echo "<br>";
  echo "<a href='uploads/resultados.txt'>resultados.txt</a><br>";
  echo "<a href='uploads/resultados2.txt'>resultados2.txt</a><br>";

}
?>
<br>
<br>
<h3>Nova consulta:</h3>
<br>
<br>
<h4>Passo 1:</h4>
<h5>Carrega Arquivo:</h5>
<br>
<form enctype="multipart/form-data" action='links.php' method="post">
  <input type="hidden" name="MAX_FILE_SIZE" value="30000" >
  <input type="hidden" name="acao" value="upload" >
  Carrega Arquivo: <input name="userfile" type="file" >
  <input type="submit" value="Envia Arquivo" >
</form>
<br>
<h5>Pesquisa Links:</h5>
<br>
<form action='links.php' method="post">
  <input type="hidden" name="acao" value="links" >
  Nome Arquivo: <input type='text' name='arquivo' value="links.txt" size=40>
  <input type="submit" value="Pesquisa Links" >
</form>
<br>
<h4>Passo 2:</h4>
<br>
<form action='links.php' method='POST' >
  <input type="hidden" name="acao" value="conteudo" >
  Conteúdo: <input type='text' name='conteudo' value="" size=40>
  <input type='submit'  value="Pesquisar" >
</form>

