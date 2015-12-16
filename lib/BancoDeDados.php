<?php
/**
 * Fornece uma forma pratica de interagir com o banco de dados
 * @author Gustavo Lucena
 *
 */
class BancoDeDados {
	/**
	 * Guarda a conexão com o banco de dados
	 *
	 * @var PDO
	 */
	private $conexao;
	
	/**
	 * Gurda os resultados das consultas
	 *
	 * @var PDOStatement
	 */
	private $resultado;
	
	private $ultimoID;
	
	/**
	 * abre a conexão com o banco de dados
	 *
	 * @return true - conexão estabelecida | false - falha na conexão
	 */
	private function conectarNoBancoDeDados() {
		try {
			$dadosDeConexao = "mysql:host=localhost;dbname=MYANIMESLISTA;";
			$this->conexao = new PDO ( $dadosDeConexao, "root", "" );
			$this->conexao->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch ( Exception $erro ) {
			echo "Erro na conexão com o banco de dados";
			return false;
		}
		return true;
	}
	/**
	 * Fecha a conexão com o banco de dados
	 * 
	 * @return void
	 */
	private function desconectar() {
		$this->conexao = null;
	}
	
	/**
	 * Execulta sentenças sql do tipo SELECT
	 * 
	 * @param string $slq        	
	 * @return true - sucesso | false - falha
	 */
	public function executaSqlComRetorno($sql) {
		if (! $this->conectarNoBancoDeDados ()) {
			return false;
		}
		
		$this->resultado = null;
		
		try {
			$this->conexao->beginTransaction ();
			$this->resultado = $this->conexao->prepare ( $sql );
			$this->resultado->execute ();
		} catch ( PDOException $erro ) {
			$this->conexao->rollBack ();
			$this->desconectar ();
			return false;
		}
		
		$this->conexao->commit ();
		$this->desconectar ();
		return true;
	}
	
	
	
	
	/**
	 * Execulta sentenças sql do tipo UPDATE, DELETE, INSERT, CREATE TABLE, etc.
	 *
	 * @param string $slq
	 * @return true - sucesso | false - falha
	 */
	public function executaSqlSemRetorno($sql) {
		
		if (! $this->conectarNoBancoDeDados ()) {
			return false;
		}
	
		try {
			$this->conexao->beginTransaction ();
			$this->resultado = $this->conexao->prepare ( $sql );
			$this->resultado->execute ();
			
			if(!$this->resultado){
				$this->conexao->rollBack();
				$this->desconectar ();
				return false;
			}
			
		} catch ( PDOException $erro ) {
			$this->conexao->rollBack ();
			$this->desconectar ();
			return false;
		}
	
		$this->conexao->commit ();
		$this->desconectar ();
		return true;
	}
	
	/**
	 * Reculpera todos os resultados carregados do banco de dados
	 * @return array:
	 */
	public function recuperaResultados(){
		return $this->resultado->fetchAll();
	}
	
	/**
	 * Retorna a qtde de linhas reculperadas do banco de dados
	 * @return integer
	 */
	public function recuperaQtdeDeLinhaRetornadas(){
		return $this->resultado->rowCount();
	}
	
	public function recuperaUltimoID(){
		return $this->ultimoID;
	}
	
	/**
	 * Execulta sentenças sql do tipo UPDATE, DELETE, INSERT, CREATE TABLE, etc.
	 *
	 * @param string $slq
	 * @return true - sucesso | false - falha
	 */
	public function executaSqlRetornoID($sql) {
		$this->ultimoID = null;
		if (! $this->conectarNoBancoDeDados ()) {
			return false;
		}
	
		try {
			$this->conexao->beginTransaction ();
			$this->resultado = $this->conexao->prepare ( $sql );
			$this->resultado->execute ();
	
			if(!$this->resultado){
				$this->conexao->rollBack();
				$this->desconectar ();
				return false;
			}
	
		} catch ( PDOException $erro ) {
			$this->conexao->rollBack ();
			$this->desconectar ();
			return false;
		}
		$this->ultimoID = $this->conexao->lastInsertId();
		$this->conexao->commit ();
	
		$this->desconectar ();
		return true;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}

?>