<?php
	session_start();
	require 'Exception.php';
	require 'PHPMailer.php';
	require 'SMTP.php';

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\SMTP;
	use PHPMailer\PHPMailer\Exception;

	interface InterfaceMensagem{
		public function enviarMensagem();
		public function retornaOpc();
	}

	/**
	 * Responsavel por passar um erro Customizado.
	 */
	class AvisoError extends Exception
	{	
		//Atributo
		private $error = '';
		
		//Construtor
		public function __construct($erro){
			try{
				strlen($erro) === 0 ? throw new Error('Não tem Mensagem de Erro para ser enviada.') : '';
				$this->error = $erro;//Ele guarda os valores da variavel
			}catch(Error $e){
				echo $e;
				exit(0);
			}
		}

		//Métodos
		public function mostraError(){
			return print "<div style='border: 2px solid black; background-color: red; color: white; font-weight: bold; text-align: center;'>$this->error</div>";
		}
	}

	/**
	 * Responsavel por Enviar O email para Destino Final.
	 */
	class Mensagem extends AvisoError implements InterfaceMensagem
	{
		//Atributos
		private $Email = null;
		private $Assunto = null;
		private $Mensagem = null;
		private $Error = null;
		private $Ret = null;
		protected $mail = null;

		//Construtor
		public function __construct()
		{
			$args = func_get_args();//Recupera os Argumentos da Função

			try{
				count($args) < 3 ? throw new AvisoError('Falta Argumentos para construir a Classe.') : '';
				//Guarda Valores nos Atributos
				$this->Email = $args[0];
				$this->Assunto = $args[1];
				$this->Mensagem = $args[2];
			}catch(AvisoError $e){
				echo $e->mostraError();//Exibe o Erro caso Existir um.
			}
		}

		//Get e Set
		public function __get($attr){
			return $this->$attr;
		}

		public function __set($attr, $vlr){
			$this->$attr = $vlr;
		}

		public function retornaOpc(){
			return $this->Ret;
		}
		//Métodos 
		//Envia o Email
		public function enviarMensagem(){
			try{
				if(!$this->emailValido()){
					throw new AvisoError($this->Error);
				}
			}catch(AvisoError $e){
				echo $e->mostraError();
				exit(0);
			}
			
			//Lógica de Envio de Mensagem
			$this->mail = new PHPMailer(true);
			$this->configuraServer();//Responsavel por Configurar o Servidor em questão
			$this->configuraDisparo();//Responsavel por Configurar o disparo de Email
			$this->dadosEmail();//Responsavel por passar os dados para envio do Email.
			$this->Ret = false;
			try{
				 $this->mail->send();
				 $this->Ret = true;
			}catch(Exception $e){
				echo $e;
			}

			return $this->retornaOpc();
		}
		//Valida todos os atributos se eles são validos
		protected function emailValido(){
			$ret = false;

			if(strpos($this->Email, '@gmail.com') and !empty($this->Mensagem) and !empty($this->Assunto)){
				$ret = true;
			}else if(empty($this->Mensagem)){
				$this->Error = 'Mensagem não pode está vazio!';
			}else if(empty($this->Assunto)){
				$this->Error = 'Assunto não pode está vazio!';
			}else{
				$this->Error = 'Email com Formato Errado!';
			}

			return $ret;
		}
		protected function configuraServer(){
		    //Server settings
		    $this->mail->SMTPDebug = false;                      //Enable verbose debug output
		    $this->mail->isSMTP();                                            //Send using SMTP
		    $this->mail->SMTPAuth   = true;                                   //Enable SMTP authentication
		    $this->mail->Username   = 'seuemail@gmail.com';                     //SMTP username
		    $this->mail->Password   = 'suasenhageradanoappsenhas';                               //SMTP password
		    $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
			$this->mail->SMTPOptions = array(
			    'ssl' => array(
			        'verify_peer' => false,
			        'verify_peer_name' => false,
			        'allow_self_signed' => true
			    )
			);
			$this->mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
		    $this->mail->Port       = 587;      
		}

		protected function configuraDisparo(){
		    //Recipients
		    $this->mail->setFrom('seuemail@gmail.com', 'Seu Nome');
		    $this->mail->addAddress("$this->Email", 'Usuário Final');     //Add a recipient
		    //$this->mail->addAddress('ellen@example.com');               //Name is optional
		    //$this->mail->addReplyTo('info@example.com', 'Information');
		    //$this->mail->addCC('cc@example.com');
		    //$this->mail->addBCC('bcc@example.com');    
		}

		protected function dadosEmail(){  
		    //Attachments
		    //$this->mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
		    //$this->mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name 

		    //Content
		    $this->mail->isHTML(true);                                  //Set email format to HTML
		    $this->mail->Subject = $this->Assunto;
		    $this->mail->Body    = "<div>$this->Mensagem</div>";
		    $this->mail->AltBody = $this->Mensagem;
		}
	}
?>