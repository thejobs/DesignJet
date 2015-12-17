<?php
/**
 * proteзгo para campos vindo do formulario
 * @author GustavoLucena
 */
class CAMIIshield{
	
	public function proteger($Varisvelpost) {
		$post = $Varisvelpost;
		
		$post = strip_tags ( $post );
		$post = stripslashes ( $post );
		$post = stripcslashes ( $post );
		$post = str_replace("'", "", $post);
		$post = str_replace('"', "", $post);
		$post = trim ( $post );
		
		return $post;
	}	
}





?>