<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Estaleiro Digital">
    <meta name="keyword" content="">

    <title>Terceiriza</title>

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

  <body onload="getTime()">

      <!-- **********************************************************************************************************************************************************
      MAIN CONTENT
      *********************************************************************************************************************************************************** -->

	  	<div class="container">
	  	
	  		
	  			<div class="col-lg-4 col-lg-offset-4">
	  				<div class="lock-screen">
                                            <h2 style='margin-top: 100px'>
                                                <a href="<?=base_url('admin/home'); ?>" title="Desbloquear">
                                                <?php
                                                    if($this->session->userdata('foto') != ''):
                                                        echo '<img src="'.base_url('/uploads/img_usuarios/'.$this->session->userdata('foto')).'" class="img-circle" width="60">';
                                                    else:
                                                        echo '<img src="'.base_url('/layout/adm/img/user-default.png').'" class="img-circle" width="60">';
                                                    endif;   
                                                    echo '<br />'.$this->session->userdata('nome');                                                       
                                                ?>                                                    
                                                </a>
                                            </h2>
		  				<p>TELA BLOQUEADA</p>
		  				
                                                
                                                <p class="centered">
                                            
                                                </p>  
                                                
                                                <div id="showtime"></div>
		  				
	  				</div><! --/lock-screen -->
	  			</div><!-- /col-lg-4 -->
	  	
	  	</div><!-- /container -->

    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url('layout/admin/js/jquery.js'); ?>"></script>
    <script src="<?php echo base_url('layout/admin/js/bootstrap.min.js'); ?>"></script>

    <!--BACKSTRETCH-->
    <!-- You can use an image of whatever size. This script will stretch to fit in any screen size.-->
    <script type="text/javascript" src="<?php echo base_url('layout/admin/js/jquery.backstretch.min.js'); ?>"></script>
    <script>
        $.backstretch("<?php echo base_url('layout/admin/img/login-bg.jpg'); ?>", {speed: 500});
    </script>

    <script>
        function getTime()
        {
            var today=new Date();
            var h=today.getHours();
            var m=today.getMinutes();
            var s=today.getSeconds();
            // add a zero in front of numbers<10
            m=checkTime(m);
            s=checkTime(s);
            document.getElementById('showtime').innerHTML=h+":"+m+":"+s;
            t=setTimeout(function(){getTime()},500);
        }

        function checkTime(i)
        {
            if (i<10)
            {
                i="0" + i;
            }
            return i;
        }
    </script>

  </body>
</html>
