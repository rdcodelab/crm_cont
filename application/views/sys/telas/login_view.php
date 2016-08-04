<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Dashboard">
    <meta name="keyword" content="Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <meta http-equiv="refresh" content="300">
    <title>RDCont - Sistema de Gestão - Acesso Restrito</title>

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
	  	
                    <form class="form-login" action="<?=base_url('/home/login'); ?>" method="post">
		        <h2 class="form-login-heading">Área restrita</h2>
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
                            ?>
                            <input type="text" class="form-control" name="usuario" placeholder="Usuário" autofocus>
		            <br>
                            <input type="password" class="form-control" name="senha" placeholder="Senha">
		            <label class="checkbox">
		                <span class="pull-right">
		                    <a data-toggle="modal" href="#myModal"> Esqueceu sua senha?</a>		
		                </span>
		            </label>
		            <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i> Logar</button>		           
		        </div>
		   </form>
		          <!-- Modal -->
		          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
		              <div class="modal-dialog">
		                  <div class="modal-content">
		                      <div class="modal-header">
		                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		                          <h4 class="modal-title">Esqueceu sua senha ?</h4>
		                      </div>
		                      <div class="modal-body">
                                          <div class="msg_dados"></div>
		                          <p>Informe abaixo seu e-mail cadastrado no sistema.</p>
                                          <input type="email" name="email" id="email" placeholder="E-mail" autocomplete="off" class="form-control placeholder-no-fix">		
		                      </div>
		                      <div class="modal-footer">
		                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancelar</button>
                                          <button class="btn btn-theme" id="btnSenha">Redefinir senha</button>
		                      </div>
		                  </div>
		              </div>
		          </div>
		          <!-- modal -->
		
		   	  	
	  	
	  	</div>
	  </div>

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url('layout/admin/js/jquery.js'); ?>"></script>
    <script src="<?php echo base_url('layout/admin/js/bootstrap.min.js'); ?>"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="<?php echo base_url('layout/admin/js/jquery.backstretch.min.js'); ?>"></script>
    <script>
        $.backstretch("<?php echo base_url('layout/admin/img/login2-bg.jpg'); ?>", {speed: 500});
        
        $(document).ready(function(){
            
            $('#btnSenha').on('click', function(){
                
                var email = $('#email').val();
                
                $.ajax({
                    type: 'POST',
                    data: {'usuario': email},
                    url: '<?=base_url('/home/recupera_senha'); ?>',
                    beforeSend: function(){
                        $('.msg_dados').html('<div class="alert alert-warning">Aguarde, verificando dados...</div>');
                    },
                    success: function(info){
                        $('.msg_dados').html(info);                        
                    },
                    error: function(){
                        $('.msg_dados').html('<div class="alert alert-danger">Erro ao buscar informações, atualize a página e tente novamente.</div>');
                    }
                });
                
            });
            
        });
        
    </script>


  </body>
</html>
