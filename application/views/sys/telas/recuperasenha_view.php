<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">

    <title>Terceiriza - Sistema de Gestão - Acesso Restrito</title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url('layout/admin/css/bootstrap.css'); ?>" rel="stylesheet">
    <!--external css-->
    <link href="<?php echo base_url('layout/admin/font-awesome/css/font-awesome.css'); ?>" rel="stylesheet" />
        
    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('layout/admin/css/estilos.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('layout/admin/css/estilo-responsive.css'); ?>" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->

	  <div id="login-page">
	  	<div class="container">
	  	
                    <form class="form-login" action="<?=base_url('/home/recover/'.$this->uri->segment(3)); ?>" method="post">
		        <h2 class="form-login-heading">Recuperação de senha</h2>
		        <div class="login-wrap">
                            <?php
                                if($this->session->flashdata('error')){
                                    echo '<div class="alert alert-danger">
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>                                            
                                            '.$this->session->flashdata('error').'
                                          </div>';
                                }
                                if($this->session->flashdata('sucesso')){
                                    echo '<div class="alert alert-success">
                                            <button type="button" class="close" data-dismiss="alert">&times;</button>                                            
                                            '.$this->session->flashdata('sucesso').'
                                          </div>';
                                }   
                                
                                if(validation_errors() == true){
                                    echo '<div class="alert alert-danger"> '.  validation_errors().' </div>'; 
                                }                                                                  
                            ?>             
                            <div class="msg_senha"></div>
                            <p>Olá <?=$usuario->nome;?>,</p>
                            <p>Digite abaixo sua nova senha.</p>
                            
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Nova Senha" autofocus>
                            <br>
                            <input type="password" class="form-control" id="senha2" name="senha2" placeholder="Confirmar nova senha" autofocus>
                            <input type="hidden" name="usuario" value="<?=@url_base64_decode($this->uri->segment(3)); ?>" />
		            <br>
                            <button class="btn btn-theme btn-block" type="submit" id="btnRedefinir" disabled><i class="fa fa-lock"></i> Redefinir</button>		           
		        </div>
		   </form>
		          				   	  	
	  	
	  	</div>
	  </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url('layout/admin/js/jquery.js'); ?>"></script>
    <script src="<?php echo base_url('layout/admin/js/bootstrap.min.js'); ?>"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="<?php echo base_url('layout/admin/js/jquery.backstretch.min.js'); ?>"></script>
    <script>
        $.backstretch("<?php echo base_url('layout/admin/img/login-bg.jpg'); ?>", {speed: 500});        
        
        $(document).ready(function(){
            $('#senha2').on('change', function(){
                                
                var senha1 = $('#senha1').val();
                var senha2 = $(this).val();
                
                if(senha1 != senha2){
                    $('#btnRedefinir').removeAttr('disabled');
                    $('#btnRedefinir').attr('enabled');
                }else{
                    $('.msg_senha').html('<div class="alert alert-warning">As senhas não coincidem, tente novamente.</div>');
                }
                
            });            
            
        });
    </script>


  </body>
</html>
