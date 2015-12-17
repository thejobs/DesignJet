<?php
/**
 * Fornece uma maneira facil de fazer upload 
 * 
 * @author GustavoLucena
 *
 */
class CAMIIUpload{
	
	private $nome;
	private $pasta;
	private $nomeSubstituto;
	
	/**
	 *  Guarda os tipos de imagem permitidas
	 * @var Upload
	 */
	private $permite; //png,jpg,gif,pjpeg,jpeg
	
	/**
	 * 
	 * O nome da imagem depois de ser renomeada.
	 * Exempo de utiliza��o: Deletar a imagem em caso de erro no banco de dados
	 * 
	 * @var CAMIIUpload
	 */
	public $arquivoFinal;
	
	/**
	 * 
	 * @param string $name
	 * nome do campo "name" do campo input type='file'
	 * 
	 * @param string $diretorio
	 * O caminho do diretorio ex: ./pasta/
	 * @return 1 - Sucesso | 2 Erro de upload | 3 - Exten��o n�o suportada | 4 - Diretorio n�o existente
	 */
	public function enviarImagem($name, $diretorio){
	
		if( $novoNome = $this->geradorDeNomeDeImagem($diretorio)){
			$extencoes = "image/png,image/jpg,image/gif,image/pjpeg,image/jpeg";
			$diretorio = $diretorio;
			
			$verificar = $this->uploadDeImagem($name, $diretorio, $novoNome, $extencoes);
			switch ($verificar){
				case 1:
					return 1;
					break;
				case 2:
					//erro de enviar imagem
					return 2;
					break;
				case 3:
					//"formato imagem n�o aceito pelo sistema"
					return 3;
					break;
						
			}
		}
		//"Diretorio n�o existente!";
		return 4;
	}
	
	
	
	/** 
	 * @param string $nomeDaImagem
	 * nome do campo "name" do campo input type='file'
	 * 
	 * @param string $pastaDeDestino
	 * nome da pasta onde ser� envia
	 * 
	 * @param string $renomear
	 * Renomear a imagem
	 * 
	 * @param string $tipoDaImagem
	 * passa os tipo de imagen como:
	 * image/png, image/jpg, image/gif, image/pjpeg, image/jpeg
	 * 
	 * @return 1 - upload com sucesso | false - n�o foi possivel fazer o upload | string - se a exten��o n�o for permitida
	 *  
	 */
	private function uploadDeImagem($nomeDaImagem, $pastaDeDestino, $renomear, $tipoDaImagem){
		if($this->verificaSeUploadExiste($nomeDaImagem)){
			
			$tipoPermitido = explode(",", $tipoDaImagem);
			
			$this->nome = $_FILES[$nomeDaImagem];
			$this->pasta = $pastaDeDestino;
			$nome = $this->nome['name'];
			
			$extencao = explode(".", $this->nome['name']);
			$extencao = end($extencao);
			
			
			
			$this->nomeSubstituto = $renomear;
			
			
			$uploadArquivo = $this->pasta."/".$this->nomeSubstituto.".".$extencao;
			
			$this->tipoPermitido($tipoPermitido);
			
			
			if(!empty($nome) and in_array($this->nome['type'], $this->permite))
			{ 
				
				if(move_uploaded_file($this->nome['tmp_name'], $uploadArquivo)){
					//echo "imagem enviada";
					$this->arquivoFinal = $this->nomeSubstituto.".".$extencao;
					return 1; 
				}else { 
				//	echo "erro ao enviar a imagem"; 
				} 
					return 2;
			}else{ 
				//fa�a algo caso n�o seja a extens�o permitida 
				return  3; 
			}		
		}
	}
	
	/**
	 * 
	 * verifica SE o upload existe
	 * @param strig $nomeImagem
	 * 
	 * @return true - se existir | false - se n�o existir
	 */
	private  function verificaSeUploadExiste($nomeImagem){
		if(!empty($_FILES[$nomeImagem]['tmp_name'])){
			
			return true;
		}
		
		return false;
	}
	
	/**
	 * grava a lista de exten��es permitidas em um array
	 * @param array $tipoPermitido
	 */
	private function tipoPermitido($tipoPermitido){
		foreach ($tipoPermitido as $key => $tipo){
			$this->permite[] = $tipo;
			
		}
	}
	
	/**
	 * Gera um nome para a imagem baseando se nos arquivos que est�o na pasta.
	 * O nome gerado ser� um numero
	 *
	 *
	 * @param string $Pasta
	 * O caminho para a pasta onde ser� gravada a imagem
	 * @return number - O nome de um arquivo em formato numerico | false se o Diretorio n�o existir
	 */
	public function geradorDeNomeDeImagem($Pasta){
		
		$path = $Pasta;
		if( is_dir($path)){
			$diretorio = dir($path);
			
			if($diretorio == false){
				return ;
			}
			
			while($arquivo = $diretorio -> read()){
				$arquivo = explode(".", $arquivo);
				$ar[] = $arquivo[0];
			}
			
			$diretorio -> close();
			$contador =  1 + count($ar);
			 
			for( $i = 1; $i<=$contador;$i++){
				if (!in_array($i, $ar)){
					return $i;
				}
			}
		}
		return false;
	}	
}

?>