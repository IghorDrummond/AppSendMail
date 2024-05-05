<?php
	include_once 'bibliotecas/Envio_Mail.php';

	$Enviar = new Mensagem($_POST['Email'], $_POST['Assunto'], 'wdwdwdd');
	//$Enviar->__set('Email', 'tavaresgiulliana@gmail.com');
	//echo $Enviar->__get('Email');
	$Ret = $Enviar->enviarMensagem();//Envia Mensagem
?>
<html>
	<head>
		<meta charset="utf-8" />
    	<title>App Mail Send - Enviado</title>

    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

	</head>

	<body>

		<div class="container">  
			<div class="py-3 text-center">
				<img class="d-block mx-auto mb-2" src="../img/logo.png" alt="" width="72" height="72">
				<h2>Send Mail</h2>
				<p class="lead">Seu app de envio de e-mails particular!</p>
			</div>
			<?php
				if($Ret){
			?>
			<div class="w-90 text-center mt-5">
				<h2 class="text-success">Mensagem enviada com sucesso para o email (<? echo($_POST['Email']) ?>)!</h2>
				<p>
					Mensagem Enviada: "<? echo($_POST['Mensagem']) ?>"
				</p>
				<a class="btn btn-info" href="../index.php">Voltar</a>
			</div>
			<?php
				}else{
			?>
			<div class="w-90 text-center mt-5">
				<h2 class="text-warning">Não foi possivel enviar a mensagem para o email (<? echo($_POST['Email']) ?>)!</h2>
				<p>
					Nossos programadores foram avisado sobre esse erro e em breve iremos concertar, peço perdão pelo problema.
				</p>
				<a class="btn btn-info" href="../index.php">Voltar</a>
			</div>
			<?php
				}
			?>
			<h6 class="fixed-bottom text-center">Desenvolvido por Ighor Drummond</h6>
	</body>
</html>