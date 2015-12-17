<?php
require_once './lib/BancoDeDados.php';
require_once './lib/CAMII/CAMIIpaginacao.php';

class Lista{
	/**
	 * Gerencia o banco de dados
	 * @var BancoDeDados
	 */
	private $bd;
	
	/**
	 * gerencia a paginação
	 * @var CAMIIpaginacao
	 */
	private $paginacao;
	
	public function __construct(){
		$this->paginacao = new CAMIIpaginacao();
		
		$this->exibirProdutos();
	}
	
	private function exibirProdutos(){
		$this->paginacao->NomeDaTabelaBD("PRODUTOS", "PRDCOD,PRDNOME,SCATCOD,SCATDESC,CATCOD,CATDESC");
		$this->paginacao->criarJoin("LEFT JOIN PRDCAT ON PRDCOD = PCATPRODUTO LEFT JOIN SUBCAT ON PCATSUBCAT = SCATCOD LEFT JOIN CATEGORIAS ON SCATCATEGORIA = CATCOD");
		$this->paginacao->criarWhere("where PRDNOME = 'camisa'");
		$this->paginacao->ExibicaoPorPagina(10);
		$produtos = $this->paginacao->conteudoPaginacao();
		
		$categorias = "";
		echo "PRODUTOS<br><br>";
		foreach ($produtos as $lista){
			echo $lista['PRDNOME']."<br>";
			
			$categorias .= $lista['CATCOD'].",";
			
			
		}
		$categorias = rtrim($categorias, ",");
		
		
		
		$sql = "SELECT * FROM CATEGORIAS "
				."LEFT JOIN SUBCAT ON SCATCATEGORIA = CATCOD where CATCOD IN ($categorias)";
				$this->bd = new BancoDeDados();
				$this->bd->executaSqlRetornoID($sql);
				
				$categorias = [];
				echo "<br><br>MENU";
				
				foreach ($this->bd->recuperaResultados() as $menu){
					
					if(!in_array($menu['CATDESC'], $categorias) ){
						echo "<hr>".$menu['CATDESC']."<hr>";
					}
					$categorias[] = $menu['CATDESC'];
					echo $menu['SCATDESC']."<br>";
				}
	}

}

new Lista();

?>