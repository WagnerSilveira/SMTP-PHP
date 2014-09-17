<!DOCTYPE html>
<!--
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
-->
<html>
     <head>
	<meta charset='UTF-8'>
	<link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
	<style> 
		

		
	</style>	
     </head>
     <body>
          <form action="smtp.controle.php" method='POST' enctype="multipart/form-data" >
			  <div style='margin:auto;width:655px'>
				   <h3 style='text-align:center;'>SMTP autenticado</h3>
				   
				   <br/>
				   <table class='table-responsive'>
						 <!-- -->
						 <tbody>
							<tr>
								<td><label>Servidor SMTP:</label></td>
								<td><input class='form-control' style='width:400px'  type='text' name='servidor' placeholder="smtp.exemplo.com.br"/><br/></td>
							</tr>
							<!-- -->
							<tr>
								<td><label>Porta SMTP:</label></td>
								<td><input class='form-control' style='width:60px'  type='text' name='porta' placeholder="587"/><br/></td>
							</tr>
							<!-- -->
							<tr>
								<td><label>Remetente:</label></td>
								<td><input class='form-control' type='text' name='rementente' placeholder="email@exemplo.com.br"/> <br/></td>

							</tr>
							<!-- -->
							<tr>
								<td><label>Senha Remetente:</label></td>
								<td><input class='form-control' type='password' name='senha' placeholder="Senha de acesso"/> <br/></td>
							</tr>
							<!-- -->
							<tr>
								<td><label>Identidade:</label></td>
								<td><input class='form-control' type='text' name='identidade' placeholder="Fulano | Empresa S.A"/> <br/></td>
							</tr>
							<!-- -->
							<tr>
								<td><label>Destinatário:</label></td>
								<td><input class='form-control' type='text' name='destinatario' placeholder="email-destino@dominiodestino.com.br"/> <br/></td>
							</tr>
							<!-- -->
							<tr>
								<td><label>Assunto:</label></td>
								<td><input class='form-control' type='text' name='assunto' placeholder="Assunto da Mensagem"/> <br/></td>
							</tr>
							<tr>
							<tr>
								<td><label>Mensagem:</label> </td>
								<td><textarea class='form-control' name='mensagem' style='width:100%' placeholder="Insira sua Mensagem aqui"></textarea> <br/></td>                  
							</tr>

							<tr> 
								<td colspan='3'><input type='submit' name='btenvia' class='btn btn-primary btn-block' style='width:100%;height:50px' value='Enviar'/></td>
							</tr>
						</tbody>	
				   </table>
			  </div>
          </form>
     </body>
</html>
<?php
if(isset($_POST['btenvia'])){

	include 'Smtp.class.php';
	### Dados para o envio #####
	$servidor =$_POST['servidor'];
	$rementente= $_POST['rementente']; 
	$identidade= $_POST['identidade'];
	$senha=$_POST['senha'];
	$destinatario=$_POST['destinatario']; 
	$assunto=$_POST['assunto'];
	$mensagem=$_POST['mensagem'];
	$porta=$_POST['porta'];


	### Envia a mensagem ###
	if(fsockopen($servidor,$porta)){
		 $smtp = new Smtp($servidor,$rementente,$senha,$porta ,true);
		 $smtp->enviar($destinatario ,$rementente, $assunto, $mensagem,$identidade);
	}else{
		 echo "Não foi possivel conectar no servidor SMTP: $servidor e na porta $porta";
	}
}
?>
