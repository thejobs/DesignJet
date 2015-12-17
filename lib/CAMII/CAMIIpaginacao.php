<?php


/**
 * CAMII sistema de Paginação
 * @author GustavoLucena
 */
class CAMIIpaginacao{
	/**
	 * 
	 * @var BancoDeDados;
	 */
	private $bd;
	
	/**
	 * Guarda o valor da quantidade que serão exibidas por paginas 
	 * @var CAMII
	 */
	private $qtdPorPaginas;
	
	/**
	 * Guarda o nome da tabela do banco de dados
	 * @var CAMII
	 */
	private $NomeDaTabela;
	
	/**
	 * Guarda os campos do SELECT
	 * @var CAMII
	 */
	private $LimitaCampoTabela;
	
	/**
	 * Guarda JOIN que ser�o usado no SELECT
	 * @var CAMII
	 */
	private $join = "";
	
	/**
	 *Guarda WHERE que serão usado no SELECT
	 * @var CAMII
	 */
	private $where = "";
	
	public $order = "BBLCOD" ; 
	
	/**
	 * Defini o valor da quantidade que sera exibido por paginas 
	 * @return qtd_paginas
	 */
	public function ExibicaoPorPagina($qtd){
		$this->qtdPorPaginas = $qtd;
	}
	
	/**
	 * Defini o nome para a tabela do banco de dados
	 * @return $NomeDaTabela
	 */
	public function NomeDaTabelaBD($nomeTabela, $campoTabela){
		$this->NomeDaTabela = $nomeTabela;
		$this->LimitaCampoTabela = $campoTabela;
	}
	
	/**
	 * Definir um INNER JOIN ou LEFT JOIN ou RIGHT JOIN
	 * 
	 * @param string $join
	 * deve ser passado a Intru��o JOIN no come�o
	 * 
	 * ex: INNER JOIN tabela1 ON cod = cod
	 */
	public function criarJoin($join){
		$this->join = $join;
	}
	
	/**
	 * Definir o WHERE do SELECT
	 * 
	 * @param string $where
	 * ex: WHERE cod = 1
	 */
	public function criarWhere($where){
		$this->where = $where;
	}
	
	
	
	public function __construct(){
		$this->bd = new BancoDeDados();
	}
	
	/**
	 * Cria a do que ser� exibidos na pagina
	 * @param boolean $conexao
	 */
	public function conteudoPaginacao(){
		
		//indica em qual pagina est�
		$paginaAtual = 1;
		if(isset($_GET['id'])){
			$paginaAtual = $_GET['id'];
		}
		//faz uma conta para onde limit devera come�ar do codigo a seguir
		$posicao = ($paginaAtual -1) * $this->qtdPorPaginas;
		

		//aqui e para fazer um select de todas as psotagens  
		$sql = "SELECT ".$this->LimitaCampoTabela." FROM ".$this->NomeDaTabela." ".$this->join." ".$this->where." limit ".$posicao.",". $this->qtdPorPaginas;
		
		if($this->bd->executaSqlComRetorno($sql)){
			return $this->bd->recuperaResultados();;
		}
		return false;
	}
	
	public function conteudoPaginacaoDecrescente(){
	
		//indica em qual pagina est�
		$paginaAtual = 1;
		if(isset($_GET['id'])){
			$paginaAtual = $_GET['id'];
		}
		//faz uma conta para onde limit devera come�ar do codigo a seguir
		$posicao = ($paginaAtual -1) * $this->qtdPorPaginas;
	
	
		//aqui e para fazer um select de todas as psotagens
		$sql = "SELECT ".$this->LimitaCampoTabela." FROM ".$this->NomeDaTabela." ".$this->join." ".$this->where." order by ".$this->order." desc limit ".$posicao.",". $this->qtdPorPaginas;
		$this->bd->executaSqlComRetorno($sql);
		return $this->bd->recuperaResultados();
	}
	
	
	/**
	 * Cria a parte de numera��o do sistema de paginação CAMII
	 * @param string $codTabelaBD
	 * Nome do campo Codigo da Tabela do Banco de Dadoa
	 * @return numero 
	 */
	public function numeroPagina($codTabelaBD){
		
		//item que aparecerão por pagina pra montar menu
		$porPagina = $this->qtdPorPaginas;

	
		$sql = "select $codTabelaBD from ".$this->NomeDaTabela." ".$this->join." ".$this->where;
		$this->bd->execultaSqlComRetorno($sql);
		
		$cont = $this->bd->recuperaQtdeDeLinhaRetornadas();
		//ceil aredonda 
		//$cont o numero total de regitro do select realizado
		//$porPagina divide a $cont para estabelecer o total de paginas
		$numpagina = ceil($cont/$porPagina);
	
	
	//	return  $this->numeroGeradoDeLink($numpagina);
		
		
		return $numpagina;	
	}
	
	public function menuEcommerce(){
		
	}
	
	
	
	/*EXEMPLO DE NUMERO DE PAGINAS
	private function numeroGeradoDeLink(){
		$numpagina =  $this->paginacao->numeroPagina("BBLCOD");		
		$n = "";
		if(!isset($_GET['busca'])){
			for($i=1;$i<=$numpagina;$i++){
				if(isset($_GET['id'])&& $_GET['id']==$i){
					$n .= "<a style='color:red' href='?id=".$i."'>".$i."</a>  ";
				}
				else{
					$n .= "<a href='?id=".$i."'>".$i."</a>  ";
				}
			}
			return  $n;
		}
		
		for($i=1;$i<=$numpagina;$i++){
			if(isset($_GET['id'])&& $_GET['id']==$i){
				$n .= "<a style='color:red' href='?id=".$i."&&busca=".$_GET['busca']."&&filtro=".$_GET['filtro']."'>".$i."</a>  ";
			}
			else{
				$n .= "<a href='?id=".$i."&&busca=".$_GET['busca']."&&filtro=".$_GET['filtro']."'>".$i."</a>  ";
			}
		}
		return  $n;
	}
	
	*/
}
?>