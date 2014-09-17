<?php
/*
 * Direitos Autorais (C) 2014 Wagner Hahn Silveira.
 *
 * Autor:
 *      Wagner Hahn Silveira <wagnerhsilveira@gmail.com>
 *
 * Este software é licenciado sob os termos da Licença Pública Geral GNU
 * License versão 2, como publicada pela Fundação de Software Livre, e
 * pode ser copiada, distribuida, e modificada sob estes termos.
 *
 * Este programa é distribuido na esperança que será util,
 * mas SEM NENHUMA GARANTIA; sem mesmo a garantia implícita de
 * COMERCIALIZAÇÃO ou de ADEQUAÇÃO A UM DETERMINADO FIM. veja o
 * Licença Pública Geral GNU para obter mais detalhes.
 *
 */

class Smtp{
	private $conn;
	private $usuario;
	private $senha;
	private $porta;
	private $debug;

	public function Smtp($servidor_smtp,$usuario,$senha,$porta,$debug){
		$this->conn = fsockopen($servidor_smtp,$porta,$errno, $errstr, 30);
		$this->usuario=$usuario;
		$this->senha=$senha;
		$this->debug=$debug;
		$this->porta=$porta;
		$this->adicionarDadosSMTP("HELO $servidor_smtp");
		
	}
	public function __set($atributo,$valor){
		$this->$atributo = $valor;
	}
	public function __get($atributo){
		return $this->$atributo;
	}
	
		
	public function autenticar(){
		$this->adicionarDadosSMTP("AUTH LOGIN");
		$this->adicionarDadosSMTP(base64_encode($this->usuario));
		$this->adicionarDadosSMTP(base64_encode($this->senha));
	}

	public function enviar($para, $de, $assunto, $mensagem,$identidade=""){
		$this->autenticar();
		$this->adicionarDadosSMTP("MAIL FROM: " . $de);
		$this->adicionarDadosSMTP("RCPT TO: " . $para);
		$this->adicionarDadosSMTP("DATA");
		$this->adicionarDadosSMTP($this->cabecTO($para, $de, $assunto,$identidade));
		$this->adicionarDadosSMTP("\r\n");
		$this->adicionarDadosSMTP($mensagem);
		$this->adicionarDadosSMTP(".");
		$this->close();
		if(isset($this->conn)){
		return true;
		}else{
			return false;
		}
	}

	public function adicionarDadosSMTP($valor){
		return fputs($this->conn, $valor . "\r\n");
	}

	 public function cabecTO($para, $de, $assunto,$identidade=""){
		$header = "Message-Id: <". date('d/m/Y-His').".". md5(microtime()).".". strtoupper($de) ."> \r\n";
		$header .= "From: ".$identidade." <".$de."> \r\n";
		$header .= "To: <".$para."> \r\n";
		$header .= "Subject: ".$assunto." \r\n";
		$header .= "Date: ". date('D, d M Y H:i:s O') ." \r\n"; 
		$header .= "X-MSMail-Priority: High \r\n";
		$header .= "Content-Type: text/html;charset=UTF-8; format=flowed";
		return $header;
	}

	public function close(){
		$this->adicionarDadosSMTP("QUIT");
		if($this->debug == true){
			while (!feof ($this->conn)) {
				fgets($this->conn) . "<br>\n";
			}
		}
		return fclose($this->conn);
	}
}
?>
