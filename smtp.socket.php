<?php
#smtp Via Socket

$servidor_smtp="";//ou smtp-web.uni5.net para revendas
$porta='25'; // 587 para smtp.dominio.com.br
$remetente=''; // email responsavel pelo envio, deve ser um do domínio
$senha=''; // senha do caso de ser envio autenticado
$exemploFrom=''; //email que irá ficar no from da mensagem e reply to

$destinatario='';
$assunto='Teste de email';
$mensagem='Demonstracao do cabecalho SENDER';

#Porque a cada final de linha deve usar \r\n no email -> Vide recomendacao(Request for comments ->  RFC 2822)


/*	Estrutura da comunicação entre sockets
	http://upload.wikimedia.org/wikipedia/commons/a/a1/InternetSocketBasicDiagram_zhtw.png
	Quando um socket está aberto no servidor é como se estivesse escrevendo nele, pois um socket não é nada mais que um arquivo no servidor.Esses   ao qual um Daemon ou servidor fica escutando conexões.
*/
/* 
	Caso seja encontrado uma sigla <CRLF> significa: 
	CR = Carriage Return  -> pode ser representado por \r
	LF= Line Feed	-> pode ser representado por \n
	
	Devem ser usados juntos para iniciar uma nova linha
*/
/*
A lista de comando a baixo utilizada no Script são conhecidos como Raw commands do Servidor SMTP
A utilização dos mesmos se iguala a utilização do SMTP via Telnet

http://www.ilkda.com/sendmail/SMTP_Commands.htm
http://penta2.ufrgs.br/rc952/trab1/smtpcomd.html


HELO SERVIDOR_SMTP

AUTH LOGIN 
USUARIO <- Deve ser codificado via base64
SENHA <- Deve ser codificado via base64

MAIL FROM:
RCPT TO:
DATA

PONTO FINAL (.)
QUIT
*/

/*
Cabecalhos Minimos  para enviar uma Menssagem(Deve ser conforme abaixo)
From: <remetente>\r\n
To: <destinatario>\r\n
Subject: Assunto\r\n
\r\n <- Quebra de linha para separarar cabecalho e corpo do email

*/

#abre conexao com o servidor via socket na porta informada
$stream = @fsockopen($servidor_smtp,$porta,$error_number,$error_string,15);
# testa conexao ao servidor 
if (!$stream) {
    echo "$error_string ($error_number)<br />\n";
	exit();
}else{

	#Identifica o Emissor da mensagem para o Receptor.
	fputs($stream,"HELO $servidor_smtp"."\r\n"); 
		fgets($stream);
	#Autenticacao do Usuario
	fputs($stream,"AUTH LOGIN"."\r\n"); 
		fgets($stream);
	fputs($stream,base64_encode($remetente)."\r\n");
	fputs($stream,base64_encode($senha)."\r\n");
		fgets($stream);
	#Configuracao de rementente e destinatario 
	fputs($stream,"MAIL FROM: $remetente"."\r\n");
	fputs($stream,"RCPT TO: $destinatario"."\r\n");
	fputs($stream,"DATA"."\r\n");
		fgets($stream);
	#Cabecalho
	fputs($stream,"From: <$exemploFrom>"."\r\n");
	fputs($stream,"To: <$destinatario>"."\r\n");
	fputs($stream,"Sender: <$remetente>"."\r\n");
	fputs($stream,"Subject: $assunto "."\r\n");
	fputs($stream,"Content-type: text/html; charset=utf-8"."\r\n");
	fputs($stream,"\r\n");
		fgets($stream);
	#Mensagem
	fputs($stream,"$mensagem"."\r\n");
		fgets($stream);

	# O  ponto final(.) envia a mensagem
	fputs($stream,"."."\r\n");
		fgets($stream);
	# O  comando QUIT finaliza a conexao com o servidor SMTP, mas não se finaliza com o Socket
	fputs($stream,"QUIT"."\r\n");
		
		# Os comandos fgets lêen a resposta dos Sockets
		fgets($stream);
		
	# O  comando fclose finaliza a comunicacao via Socket
	fclose($stream);

}
?>
