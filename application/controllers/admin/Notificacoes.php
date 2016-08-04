<?php
/* 
 * Controller de envio de noti
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Notificacoes extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->helper('notificacoes');
        $this->load->model('Protocolos_model', 'protocolos');
        $this->load->model('Clientes_model', 'clientes');
        
        // controle de nível de acesso
        //controle_acesso($this->session->userdata('id'), 'notificacoes');
        
    }
    
    public function index(){              
        echo '<h1>Acesso negado!</h1>';        
    }
    
    // mostra notificações no topo da página
    public function header_notification(){
        
        $setor = $this->uri->segment(4);
        
        $notificacoes = $this->protocolos->getNotificacaoSetor($this->session->userdata('servico'));
        
        echo '<a data-toggle="dropdown" class="dropdown-toggle" href="javascript:void()">';
        echo '<i class="fa fa-bell-o"></i>';
        echo (count($notificacoes) > 0) ? '<span class="badge bg-theme">'.count($notificacoes).'</span>' : '';
        echo '</a>';
        echo '<ul class="dropdown-menu extended inbox pull-left">';
        echo '<div class="notify-arrow notify-arrow-green"></div>';
        echo '<li>';                    
        
        // define mensagem
        if(count($notificacoes) == 0){
            $msg_chamada = 'Você não possui novas notificações.';
        }elseif(count($notificacoes) == 1){
            $msg_chamada = 'Você possui 1 nova notificação.';                                
        }else{
            $msg_chamada = "Você possui ". count($notificacoes)." notificações novas.";
        }
        
        echo '<p class="green">'.$msg_chamada.'</p>';
        echo '</li>';
        if(count($notificacoes) > 0){
            foreach($notificacoes as $not):

                $cliente = $this->clientes->lista_cliente_id($not->id_cliente);

                // verifica o tipo de notificação
                switch($not->tipo){
                    // arquivos
                    case 1:
                        $rotaLink = '/admin/clientes/detalhes/'.$not->id_cliente.'/';
                        $labelNotificacao = '<span class="label label-info">Arquivos</span>';
                    break;
                    // chamados
                    case 4:
                        $rotaLink = '/admin/chamados/';
                        $labelNotificacao = '<span class="label label-primary">Chamados</span>';
                    break;
                    // tarefas
                    case 8:
                        $rotaLink = '/admin/tarefas';
                        $labelNotificacao = '<span class="label label-info">Serviços</span>';
                    break;
                    default :
                        $rotaLink = '/admin';
                        $labelNotificacao = '<span class="label label-default">N/d</span>';
                    break;

                }

                echo '<li class="box-notificacao">';
                echo '<a href="'.base_url($rotaLink).'" title="Ver mensagem" onclick="visualiza_notificacao('.$not->id_notificacoes.')">';
                echo $labelNotificacao;
                echo '<span class="assunto">';
                echo '<span class="cliente">'.$cliente->razao_social.'</span><br />';            
                echo '</span>';
                echo '<span class="titulo-notificacao">';
                echo $not->titulo_notificacao;
                echo '</span>';
                echo '<span class="data-notificacao">';
                echo dataHora_BR($not->data_cadastro);
                echo '</span><br />';
                echo '</a>';
                echo '</li>';
            endforeach;
        }
                      
        echo '</ul>';        
    }
    
    // muda status da notificação como liddo
    public function rotalink(){
        $notificacao = $this->uri->segment(4);
        $dados = array(
            'id_notificacoes' => $notificacao,
            'status' => 0
        );
        $this->protocolos->atualiza_notificacao($dados);            
    }
    
}
