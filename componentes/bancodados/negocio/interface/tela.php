<?php
/**
 * @desc Sistema: CALU at link unlimited
 * @desc Classe de Tratamento de Interfaces com usuário
 * @author Alessandro Calu <alessandrocalu@yahoo.com.br>
 * @copyright Alessandro Calu
 * @version 1.0
 * @since Criado: 18/01/2008 Atualizado: 18/01/2008
*/
class tela {
        
        var $id_tela; //Código de identificação de tela
        
        var $fk_consulta; //Código de consulta relacionada a tela (ligação com objeto consulta)    
        
        var $tipo; //Tipo de tela (tratamento específico para tipo de tela) 
        
        var $nome_tipo; //Nome de Tipo de tela (tratamento específico para tipo de tela) 
        
        var $nome; //Nome da tela
        
        var $descricao; //Descrição da tela
        
        var $interface_tela; //Nome da interface(template) utilizada
        
        var $paginacao; //flag se utiliza paginacao
        
        var $sessao; // flag se utiliza sessão 
        
		var $xajax; //flag se tela tem xajax
        
        var $acoes; // Lista de acoes de tela
        
        var $campos; //Lista de campos de tela
        
        var $tipos; //Tipos de usuário que tem acesso a tela
        
        var $erros; //Contém vetor com lista de erros ocorridos 
        
        
        /**
         * @desc Limpa lista de Erros ocorridos 
        */  
        function limpaErros(){
                $this->erros = array();         
        }
  
        /**
         * @desc Retorna lista de erros ocorridos 
         * @return array de Lista de Erros
        */
        function getErros(){
                return $this->erros;
        }        
        
        
        /**
     * @desc Contrutor de Classe tela
     * @param $nome_tela de Nome de Tela que se deseja
    */
        function tela($nome_tela){
                global $negocio, $funcoes;
                $dados = $negocio->buscaDadosTela($nome_tela); 
                if ($dados){  
                        $this->id_tela = $dados[0]["id_tela"];
                        $this->interface_tela = $dados[0]["interface"];
                        $this->nome = $dados[0]["nome"];
                        $this->tipo = $dados[0]["tipo"];
                        $this->paginacao = $dados[0]["paginacao"];
                        $this->sessao = $dados[0]["sessao"];
						$this->xajax = $dados[0]["xajax"];
                        $this->descricao = $dados[0]["descricao"];
                        $this->tipos = $dados[0]["tipos"];
                        $this->fk_consulta = $dados[0]["consulta"];
                        
                        //Tipo de Tela
                        $dados = $negocio->buscaDadosTipoTela($this->tipo);
                        if ($dados){ 
                                $this->nome_tipo = $dados[0]["tipo"];
                        }
                        
                        //Ações de tela
                        $dados = $negocio->buscaDadosAcoesTela($this->id_tela);
                        if ($dados){ 
                                $this->acoes = $dados;
                                for ($i = 0; $i < count($this->acoes) ;$i++){
                                        $dados = $negocio->buscaDadosTipoAcao($this->acoes[$i]["tipo"]);
                                        if ($dados){ 
                                                $this->acoes[$i]["nome_tipo"] = $dados[0]["tipo"];
                                        }
                                }       
                        }
                        
                        //Campos de tela
                        $dados = $negocio->buscaDadosCamposTela($this->id_tela); 
                        if ($dados){ 
                                $this->campos = $dados;
                                for ($i = 0; $i < count($this->campos) ;$i++){ 
                                        $dados = $negocio->buscaDadosComportamentoCampoTela($this->campos[$i]["comportamento"]);
                                        if ($dados){ 
                                                $this->campos[$i]["nome_comportamento"] = $dados[0]["tipo"];
                                                $this->campos[$i]["acao_comportamento"] = $dados[0]["comportamento"];
                                        }

                                        $dados = $negocio->buscaDadosLigacoesCampoTela($this->campos[$i]["id_tela_campo"]);
                                        if ($dados){ 
                                                $this->campos[$i]["ligacoes"] = $dados;
                                                for ($j = 0; $j < count($this->campos[$i]["ligacoes"]) ;$j++){
                                                        $dados_ligacao = $negocio->buscaDadosCampoTabela($this->campos[$i]["ligacoes"][$j]["campo_tabela"]);
                                                        if ($dados_ligacao){ 
                                                                $this->campos[$i]["ligacoes"][$j]["nome"] = $dados_ligacao[0]["nome"];
                                                                $this->campos[$i]["ligacoes"][$j]["tabela"] = $dados_ligacao[0]["tabela"];
                                                        }
                                                } 
                                        }
                                }       
                        }

                }
                else
                {
                        if ($erros = $negocio->getErros()) {
                                $this->erros = $erros;
                                return 0;       
                        } 
                }       
                return 1;
        }       
        
        /**
         * @desc Retorna dados de tela
         * @return array de Dados
        */
        function consultaDados(){
                global $negocio,$funcoes;
                //Monta Campos
                if ($dados_consulta = $negocio->getDadosConsulta()){
                        $dados_campos = array();
                        for ($j = 0; $j < count($dados_consulta) ;$j++){ 
                                $contador = 0;
                                for ($i = 0; $i < count($this->campos) ;$i++){ 
                                        if ($this->campos[$i]["ligacoes"]){
                                                $dados_campos[$j][$contador] = $this->campos[$i];
                                                for ($m = 0; $m < count($this->campos[$i]["ligacoes"]) ;$m++){ 
                                                        $valor = $dados_consulta[$j][$this->campos[$i]["ligacoes"][$m]["tabela"]."_".$this->campos[$i]["ligacoes"][$m]["nome"]];
                                                        
                                                        //Trata Formatação de campo
                                                        if ($formatacao = $this->campos[$i]["ligacoes"][$m]["formatacao"])
                                                        {
                                                                $ancoras = $funcoes->procuraAncoras($formatacao);
                                                                $valores = array();
                                                                for ($k = 0; $k < count($ancoras); $k++){
                                                                        if ($ancoras[$k] == "valor")
                                                                        {
                                                                                $valores[$k] = $valor; 
                                                                        }
                                                                }
                                                                if ($valores[$k]){
                                                                        $formatacao = $funcoes->substituiAncoras($formatacao,$ancoras,$valores);
                                                                }       
                                                                eval('$valor = '.$formatacao);
                                                        }
                                                        $dados_campos[$j][$contador]["valor"] .= $valor;        
                                                }
                                                $contador++;
                                        }
                                }
                        }       
                        return $dados_campos;
                }
                
                if ($erros = $negocio->getErros()) {
                        $this->erros = $erros;
                        return 0;       
                } 
                return 0;
        }       
        
        
        /**
         * @desc Retorna o id_tela da tela 
     * @return integer de id_tela de Tela
    */
        function getTela(){
                return $this->id_tela; 
        }
        
        /**
         * @desc Retorna o nome da tela 
     * @return string de Nome de Tela
    */
        function getNome(){
                return $this->nome; 
        }
        
    /**
     * @desc Retorna o descricao da tela 
     * @return string de Descrição de Tela
    */
        function getDescricao(){
                return $this->descricao; 
        }
        
    /**
     * @desc Retorna o Flag Sessão de Tela
     * @return integer de Flag Sessão de Tela
    */
        function getSessao(){
			return $this->sessao; 
        }
	
	/**
     * @desc Retorna o Flag Xajax de Tela
     * @return integer de Flag Xajax de Tela
    */
        function getXajax(){
			return $this->xajax; 
        }
        
    /**
     * @desc Retorna a interface da tela 
     * @return string de Interface de Tela
    */
        function getInterface(){
            return $this->interface_tela; 
        }
        
    /**
     * @desc Retorna a Nome do Tipo da tela 
     * @return string de Nome do Tipo de Tela
    */
        function getNomeTipo(){
            return $this->nome_tipo; 
        }
		
	/**
     * @desc Retorna o Tipo da tela 
     * @return  Tipo de Tela
    */
        function getTipo(){
            return $this->tipo; 
        }		
        
    /**
     * @desc Retorna a Lista de ações da tela 
     * @return array de Lista de Ações
    */
        function getAcoes(){
                return $this->acoes; 
        }
        
        /**
         * @desc Retorna a Lista de campos da tela 
     * @return array de Lista de Campos
    */
        function getCampos($grupo = ""){
                if ($grupo){
                        $campos_retorno = array();
                        for ($i = 0; $i < count($this->campos); $i++){
                                if ($this->campos[$i]["grupo"] == $grupo)
                                {
                                        $campos_retorno[] = $this->campos[$i];
                                }
                        }
                }
                else
                {
                        $campos_retorno = $this->campos;
                }
                return $campos_retorno;

        }
        
        /**
         * @desc Configura lista de campos de tela
         * @param array de Lista de Campos
        */
        function setCampos($campos){
                $this->campos = $campos;
        }
        
        
        /**
         * @desc Retorna a Consulta da Tela
     * @return integer de Identificacor de Consulta
    */
        function getConsulta(){
                return $this->fk_consulta; 
        }
}