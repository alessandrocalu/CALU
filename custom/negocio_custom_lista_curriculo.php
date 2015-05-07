<?
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Camada de Regras de Negócio (Lista Currículo)
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 21/03/2007 Atualizado: 21/03/2007
*/

class negocio_custom_lista_curriculo extends negocio_custom {
	
	/**
	 * @desc Realiza customização de Delete de Curriculo
	*/
    function delete_lista_curriculo(){
		global $pathservidor;
		if ($_GET["delete"] == 'ocultar')
        {
            $vars = $_POST;
            $camposFiltros = array();
            $valoresFiltros = array();
            foreach ($vars as $key => $value) {
                if (($value == '1') && strpos("_".$key,"codigo_") && ($key != "codigo_todos") )
                {
                    $camposFiltros[] = "codigo";
                    $valoresFiltros[] = substr($key,7);

					$camposFiltros = array();
					$camposValores = array();
					$codigo = substr($key,7);
					$campos = array("oculto");
					$valores = array("1");
					
					$camposFiltros[] = "codigo";
					$camposValores[] = $codigo;
					$logico = " and ";
					$this->consulta->consultaUpdate($campos,$valores,$camposFiltros,$camposValores,$logico,0); 
					//apaga curriculo, testar se já existe 
					$local_arquivo_pdf = $pathservidor."rh/curriculos/curriculo_".$codigo.".pdf";
					if (is_file($local_arquivo_pdf)){
						unlink($local_arquivo_pdf);
					}
					$local_arquivo_doc = $pathservidor."rh/curriculos/curriculo_".$codigo.".doc";
					if (is_file($local_arquivo_doc)){
						unlink($local_arquivo_doc);
					}
					$this->mensagem = "Currículo(s) ocultados!";
                }
            }                       
			unset($_GET["delete"]);	
        }       
        return 1;
    }
    
/**
     * @desc Retorna botões de açoes de Lista de Currículos 
    */
    function botoesAcoesCartaCurriculo($carta,$codigo){
		global $visual;
		//Carta de Apresentação
		$botoes .= $visual->exibeBoxTextHtml("carta_".$codigo, "Carta de Apresentação" ,$carta,"edit_box","","Visualizar Carta de Apresentação");
		return $botoes;
	}

	/**
     * @desc Retorna botões de açoes de Lista de Currículos 
    */
    function botoesAcoesEmailCurriculo($email,$codigo){
		global $visual;
		//Carta de Apresentação
		$botoes .= $visual->exibeBoxTextHtml("email_".$codigo,"Email",$email,"edit_box","","Visualizar Email");
		return $botoes;
	}

	/**
     * @desc Retorna botões de açoes de Lista de Currículos 
    */
    function botoesAcoesListaCurriculo($arquivo,$codigo){
		//Currículo
		$botoes .= "&nbsp<a href=\"".$arquivo."\"><img src=\"./images/form_search.png\"   title='Baixar currículo anexo' /></a>";  
		return $botoes;
	}
}