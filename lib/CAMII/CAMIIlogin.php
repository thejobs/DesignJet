<?php
require_once "CAMIIshield.php";
require_once "./lib/BancoDeDados.php";
require_once "CAMIIemail.php";

/**
 * 
 * @author GustavoLucena
 *
 */
class CAMIIlogin{
	/**
	 * Guarda o login
	 * @var CAMIIlogin
	 */
	public $login;
	
	/**
	 * Guarda a senha
	 * @var CAMIIlogin
	 */
	private $senha;
	
	/**
	 * Gerencia o banco
	 *
	 * @var bancoDeDados
	 */
	private $bd;
	
	/**
	 * gerencia o proteção
	 * @var CAMIIshield
	 */
	private $shield;
	
	/**
	 * gerencia envio de email
	 * @var CAMIIemail
	 */
	private $email;
	
	/**
	 * 
	 * @param string $login
	 * @param string $senha
	 * @param string $paglogado
	 * @return true - Logado com sucesso | false - erro ao fazer login
	 */
	public function __construct(){
		session_start();
	}
	public function logar($login, $senha, $paglogado){
		$this->bd = new BancoDeDados();
		$this->shield = new CAMIIshield();
		
		$login = $this->shield->proteger($login);
		$senha = $this->shield->proteger($senha);
		
		$this->login = $login;
		$this->senha = $senha;// md5($senha);
		
		$sql = "SELECT * FROM CLIENTE WHERE CLINOME='$this->login' AND CLISENHA='$this->senha'";
		$this->bd->executaSqlComRetorno($sql);
		
		if ($this->bd->recuperaQtdeDeLinhaRetornadas() == 1){
			
			$usuario = $this->bd->recuperaResultados();
			$_SESSION['login'] = $usuario[0][1];
			$_SESSION['cod'] = $usuario[0][0];
			echo "<script> window.location='$paglogado';</script>";
			return true;
		}
		return false;
	}
	
	/**
	 * Realiza logout
	 */
	public function logout($PagRedirecionar){
		session_destroy();
		echo "<script> window.location='$PagRedirecionar';</script>";
	}
	
	/**
	 * verifica se estar logado
	 * @param string $PagRedirecionar
	 * Nome da pagina de Login
	 */
	public function logado($PagRedirecionar){
		
		if (isset($_SESSION['login'])){
			return true;
		}
		echo "<script> window.location='$PagRedirecionar';</script>";
		return false;
	}
	
	
	
	/**
	 * 
	 * @param string $email
	 * verifica se tem o e-mail cadastrado
	 */
	public function recuperarSenha($email){	
		$this->bd = new BancoDeDados();
		
		$sql = "SELECT LOGCOD FROM LOGIN WHERE LOGEMAIL = '$email' ";
		$this->bd->execultaSqlComRetorno($sql);
		if ($this->bd->recuperaQtdeDeLinhaRetornadas() == 1){
			$this->email = new CAMIIemail();
			$codigo = $this->bd->recuperaResultados()[0][0];
			
			
			$this->email->destinatario('ETEC', $email);
			//pega um modelo de email html e substiui o link pra redefinir a senha
			$texto = str_replace('linkdasenha', 'http://localhost/TCC/adm/redefinirSenha.php?alt='.$codigo, file_get_contents('../lib/CAMII/modeloEmailRedefinirSenha.html'));
			$this->email->textoDoEmail($texto);
			return $this->email->enviarEmail();
		}
	}
	
	
	/**
	 * verificar se administrador esta logado no TCC
	 * @return true - se estiver logado | false - se não estiver logado
	 */
	public function TCCLogin(){
		if($this->logado("index.php")){
			if (isset($_GET['logout'])){
				$this->logout("index.php");
			}
			return true;	
		}
		return false;
	}
	 public function logadoIndex(){
	 	
	 	if (isset($_SESSION['login'])){
	 		return true;
	 	}
	 }
	
}
?>