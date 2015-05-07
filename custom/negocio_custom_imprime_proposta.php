<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Imprime Proposta)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_imprime_proposta extends negocio_custom {

	/**
	 * @desc Realiza customização de Select de Impressão de Proposta
	*/
    function select_imprime_proposta(){
		global $tipo, $nome_vendedor,$email_cliente;
		
		if ($_GET["chave"]){
			//Procura por Consultor
			$id_vendedor = 0;
			$bairro = 0;
			if ($dados = $this->carregaDados("vendedor,bairro,email","TMKT"," (codigo = ".$_GET["chave"].") ")){
				if ($dados[0][0]) {
					$id_vendedor = $dados[0][0];
					$bairro = $dados[0][1];
					if (strpos($dados[0][2],'@')){
						$email_cliente = $dados[0][2];
					}	
				} 
			}
			
			//Caso seja envio de proposta por email, não assina como vendedor e sim como Telemarketing
			if (!$_GET["email"]){
				//Nome Vendedor 
				if ($id_vendedor){
					if ($dados = $this->carregaDados("nome","VENDEDOR"," (codigo = ".$id_vendedor.") ")){
						if ($dados[0][0]) {
							$nome_vendedor = $dados[0][0];
							$_SESSION['nome'] = $nome_vendedor;
						} 
					}
				}
				$valores = array("getdate()","getdate()+15",$_GET["chave"],$id_vendedor);
			}
			else
			{
				$valores = array("getdate()","getdate()+15",$_GET["chave"],$_SESSION["navegador_ator"]);
			}
			$campos = array("data_criacao","prim_vencto","id_cliente","id_vendedor");	
			
			$camposFiltros = array("id_proposta");
			$tipo = 1948;
	        if ($_GET["select"] == 'basic'){
				$tipo = 1948;
			}
	        if ($_GET["select"] == 'classic'){
				$tipo = 1947;
			}
			if ($_GET["select"] == 'premium'){
				$tipo = 2194;
			}
			if ($_GET["select"] == 'search'){
				$tipo_proposta = 'VAZIO';
				if ($dados = $this->carregaDados("tipo","DOMINIO"," (codigo = ".$bairro.") and (id_dominio = 3) ")){
					if ($dados[0][0]) {
						$tipo_proposta = $dados[0][0];
					} 
				}
				$tipo = 0; 

				if ($tipo_proposta == 'CLASSIC'){
					$tipo = 1947;
				}
				elseif ($tipo_proposta == 'BASIC')
				{
					$tipo = 1948;
				}
				else
				{
					$tipo = 0;
					$this->mensagem = "Bairro não classificado! ";
					return false;
				}
			}
			$camposValores = array($tipo);
			$logico = " and ";
			$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
			
			return true;
		}	
		else
		{
			$this->mensagem = "Cliente não existe! ";
			return false;
		}
    }
}