<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Lista Clientes)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_lista_vendedor extends negocio_custom {
	/**
	 * @desc Realiza customização de Insert de Vendedor
	*/
    function insert_lista_vendedor(){
        if ($_GET["insert"] == "novo")
        {
        	if ($_POST["nome"] && $_POST["login"] && $_POST["dominio_grupo"]){
        		
        		$nome_grupo = $this->nomeDominio(31, $_POST["dominio_grupo"]);
        		
        		$codigo = 1;
        		if ($dados = $this->carregaDados("max(codigo)","vendedor")){
                	$codigo = $dados[0][0]+1;
        		} 
        		
				$campos = array("codigo", "nome", "login", "dominio_grupo", "grupo", "supervisor", "suspenso", "operador", "operador_2");

				$valores = array($codigo,"'".$_POST["nome"]."'","'".$_POST["login"]."'",$_POST["dominio_grupo"], "'".$nome_grupo."'", "0", "1", "41", "41");
                $this->consulta->consultaInsert($campos,$valores); 
                
                $_POST["tipo_consultor"] = '0';
                $_POST["suspenso"] = '1';
                $_POST["se_operador"] = '0';
                $_POST["se_nome"] = $_POST["nome"];
                $_POST["se_login"] = $_POST["login"];
                $_POST["se_grupo"] = $nome_grupo;
				
				$this->mensagem = "Consultor adicionado, escolha o Tipo, o Operador e ative o consultor (Suspenso = Não)! ";
			}
			else
			{
				$this->mensagem = "Favor preencher Nome, Grupo e Login do Consultor! ";
				return 0;
			}		
        }       
        return 1;
    }
}