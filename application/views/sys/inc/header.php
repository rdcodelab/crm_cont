<!--header start-->
<header class="header black-bg">
        <div class="sidebar-toggle-box">
            <div class="fa fa-bars tooltips" data-placement="right" data-original-title="Toggle Navigation"></div>
        </div>
      <!--logo start-->
      <a href="<?=base_url('/home'); ?>" class="logo">
          <?php
            if($config->logo_escritorio != ''){
                echo '<img src="'.base_url('/uploads/imagens/'.$config->logo_escritorio).'" style="height: 40px" />';
            }else{
                echo $config->nome_fantasia_escritorio;
            }
          ?>
      </a>
      <!--logo end-->
      <div class="nav notify-row" id="top_menu">
          <!--  notification start -->
          <ul class="nav top-menu">              
              <!-- inbox dropdown start-->
              <li id="header_inbox_bar" class="dropdown">
                  <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                      <i class="fa fa-envelope-o"></i>
                      <?=(count($mensagens) > 0) ? '<span class="badge bg-theme">'.count($mensagens).'</span>' : ''; ?>
                  </a>
                  <ul class="dropdown-menu extended inbox">
                      <div class="notify-arrow notify-arrow-green"></div>
                      <li>
                          <?php
                                // define mensagem
                                if(count($mensagens) == 0){
                                    $msg_chamada = 'Você não possui mensagens novas.';
                                }elseif(count($mensagens) == 1){
                                    $msg_chamada = 'Você possui 1 nova mensagem.';                                
                                }else{
                                    $msg_chamada = "Você possui ". count($mensagens)." novas mensagens.";
                                }
                              ?>
                          <p class="green"><?=$msg_chamada; ?></p>
                      </li>
                      <?php
                        foreach($mensagens as $msg):
                      ?>
                      <li>
                          <a href="<?=base_url('chamados/ver/'.$msg->id_chamados); ?>" title="Ver mensagem">                              
                              <span class="subject"> 
                              <span class="from"><?=$msg->razao_social; ?></span><br />
                              <span class="time"><?=dataHora_BR($msg->data_cadastro); ?> </span><br />
                              </span>
                              <span class="message">
                                <?=$msg->assunto; ?>
                              </span>
                          </a>
                      </li>                                            
                      <?php endforeach; ?>                                            
                                            
                      
                      <li>
                          <a href="<?=base_url('/chamados'); ?>">Ver todas</a>
                      </li>
                  </ul>
              </li>
               <li id="header_inbox_bar" class="dropdown">
                  <a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void()">
                      <i class="fa fa-clock-o"></i>
                      <?=(count($tarefas) > 0) ? '<span class="badge bg-theme">'.count($tarefas).'</span>' : ''; ?>
                  </a>
                  <ul class="dropdown-menu extended inbox">
                      <div class="notify-arrow notify-arrow-green"></div>
                      <li>
                          <?php
                            // define mensagem
                            if(count($tarefas) == 0){
                                $msg_chamada = 'Você não possui nenhuma solicitação sendo atendida.';
                            }elseif(count($tarefas) == 1){
                                $msg_chamada = 'Você possui 1 solicitação sendo atendida.';                                
                            }else{
                                $msg_chamada = "Você possui ". count($tarefas)." solicitações sendo atendidas.";
                            }
                          ?>
                          <p class="green"><?=$msg_chamada; ?></p>
                      </li>
                      <?php
                        foreach($tarefas as $trf):
                      ?>
                      <li>
                          <a href="javascript:void()" title="Ver mensagem" onclick="abre_tarefa(<?=$trf->id_tarefa; ?>)">                              
                              <span class="subject">
                              <span class="from"><?=$trf->titulo; ?></span><br />
                              <span class="time">
                                <?php
                                    switch ($trf->status){
                                        case 0:
                                            $status_tarefa = '<div class="label label-default">Nova</div>';
                                        break;
                                        case 1:
                                            $status_tarefa = '<div class="label label-info">Trabalhando</div>';
                                        break;
                                        case 2:
                                            $status_tarefa = '<div class="label label-primary">Avaliando</div>';
                                        break;
                                        case 3:
                                            $status_tarefa = '<div class="label label-success">Entregue</div>';
                                        break;
                                        case 4:
                                            $status_tarefa = '<div class="label label-warning">Pausada</div>';
                                        break;
                                        default :
                                            $status_tarefa = '<div class="label label-danger">n/d</div>';
                                        break;

                                        echo $status_tarefa;
                                    }    
                                ?>
                              </span><br />
                              </span>
                              <span class="message">
                                Ínicio em <?=dataBR($trf->data_inicio);  ?><br />
                                Finaliza em <?=dataBR($trf->data_validade);  ?><br />
                              </span>
                          </a>
                      </li>                                            
                      <?php endforeach; ?>
                      
                      <li>
                          <a href="<?=base_url('/tarefas/'); ?>" title="Ver todas as solicitações">Ver solicitações</a>
                      </li>
                  </ul>
              </li>
              <!-- inbox dropdown end -->
              <li><a href="<?=base_url('/usuarios/editar/'.$this->session->userdata('id')); ?>"><i class="fa fa-user"></i></a></li>
              <li style="color: #fff;">
                  Empresa: <?=$this->session->userdata('nome_cliente'); ?><br />
                  CNPJ/ CEI: <?=$this->session->userdata('cnpj_cliente'); ?>
              </li>
          </ul>
          <!--  notification end -->
      </div>
      <div class="top-menu">
          <ul class="nav pull-right top-menu">              
              <li><a class="logout" href="<?=base_url('/home/logout'); ?>"><i class="glyphicon glyphicon-log-out"></i> Sair</a></li>
          </ul>
      </div>
  </header>
<!--header end-->