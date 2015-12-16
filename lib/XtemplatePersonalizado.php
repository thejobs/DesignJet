<?php
require_once 'XTemplate.php';
class XtemplatePersonalizado extends XTemplate {
	public function __construct($arquivo) {
		header ( 'Content-type: text/html; charset=utf-8' );
		
		if (! file_exists ( $arquivo ))
			throw new Exception ( "Arquivo de template não encontrado!" );
		
		parent::__construct ( $arquivo );
	}
}

?>