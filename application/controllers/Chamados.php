<?php
class Chamados extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Servicos_model', 'servicos');
        $this->load->model('Configuracoes_model', 'configuracoes');        
        $this->load->model('Clientes_model', 'clientes');
        $this->load->model('Chamados_model', 'chamados');        
        $this->load->model('Usuarios_model', 'usuarios');        
        $this->load->model('Arquivos_model', 'arquivos');
        // realiza controle de status de arquivos.
        $this->arquivos->controle_status();
        
        if($this->session->userdata('clienteLogado') == false){            
            redirect('/home/login');
        }
    }
    
    public function index(){
        if($this->session->userdata('clienteLogado') == false){            
            $this->load->view('sys/telas/login_view');
        }else{    
            $cabecalho = array(
                'config' => $this->configuracoes->lista_configs(1),
                'mensagens' => $this->chamados->lista_chamados_cliente_usuario($this->session->userdata('id_cliente'), $this->session->userdata('id'), 1)
            );
            
            $dados = array(
                'chamados' => $this->chamados->lista_chamado_cliente($this->session->userdata('id_cliente'), $this->session->userdata('id')),
                'servicos' => $this->clientes->lista_servico_cliente($this->session->userdata('id_cliente'))
            );
            
            $this->load->view('sys/inc/header_html');
            $this->load->view('sys/inc/header', $cabecalho);
            $this->load->view('sys/inc/sidebar');
            $this->load->view('sys/telas/chamados_view', $dados);
            $this->load->view('sys/inc/footer');
            $this->load->view('sys/inc/footer_html');
        }
    }
    
    // abre chamado
    public function add(){
        $this->form_validation->set_rules('servico', 'SERVIÇO', 'trim|required|numeric');
        $this->form_validation->set_rules('assunto', 'ASSUNTO', 'trim|required|max_length[140]');                
        $this->form_validation->set_rules('mensagem', 'MENSAGEM', 'trim|required');                

        if($this->form_validation->run() == TRUE){           
           
            $dados = array(
                'id_clientes' => $this->session->userdata('id_cliente'),
                'idclientes_usuarios' => $this->session->userdata('id'),                
                'id_servicos' => $this->input->post('servico'),
                'assunto' => $this->input->post('assunto'),
                'nivel_urgencia' => $this->input->post('urgencia'),
                'status_chamado' => 0                
            );
            
            $msg = array(
                'mensagem' => $this->input->post('mensagem'),
                'status_mensagem' => 0
            );
                        
            if($this->chamados->add_chamado($dados, $msg)){
                
                // registra log de visualização
                logs_acao(4, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), $this->input->post('servico'), null, '<div class="text-sucess">Abriu o chamado '.$this->input->post('assunto').'</div>');
                               
                
                // dispara aviso por e-mail aos gestores do sistema
                $gestores = $this->usuarios->lista_usuarios();
                
                // resgata nome do serviço
                $servico = $this->servicos->lista_servico_id($this->input->post('servico'));
                
                // formata nível de urgência
                switch ($this->input->post('urgencia')){
                    case 0:
                        $urgencia = 'Baixa';
                        $urgenciaAssunto = '[BAIXA]';
                    break;
                    case 1:
                        $urgencia = 'Normal';
                        $urgenciaAssunto = '[NORMAL]';
                    break;
                    case 2:
                        $urgencia = 'Alta';
                        $urgenciaAssunto = '[ALTA]';
                    break;
                }
                
                // registra notificação
                notificacoes($this->session->userdata('id_cliente'), $this->session->userdata('id'), 4, $servico->id, 'Abriu o chamado '.$urgenciaAssunto.$this->input->post('assunto'));
                
                if(count($gestores) > 0):                                                                                
                    foreach($gestores as $user):
                    
                        // monta mensagem
                        $mensagem  = "Olá ".$user->nome.", <br />";
                        $mensagem .= "Um chamado acaba de ser aberto, segue abaixo os detalhes: <br />";
                        $mensagem .= "<hr />";
                        $mensagem .= "<b>Cliente:</b> ".$this->session->userdata('nome_cliente')."<br />";
                        $mensagem .= "<b>Funcionário(a):</b> ".$this->session->userdata('nome')."<br />";
                        $mensagem .= "<b>Assunto:</b> ".$this->input->post('assunto')."<br />";
                        $mensagem .= "<b>Serviço:</b> ".$servico->nome."<br />";
                        $mensagem .= "<b>Urgência:</b> ".$urgencia."<br />";
                        $mensagem .= "<b>Mensagem:</b> ".$this->input->post('mensagem')."<br />";
                        $mensagem .= "<b>Data de abertura:</b> ".date('d/m/Y H:i:s')."<br />";                        
                        $mensagem .= "<hr />";
                    
                        envia_email($user->email, 'Chamado - '.$urgenciaAssunto." ".$this->input->post('assunto'), $mensagem);
                    endforeach;
                endif;
                
                
                $this->session->set_flashdata('sucesso', 'Chamado aberto com sucesso, em breve estaremos respondendo!');
                redirect('/chamados/');      
               
            }else{
                $this->session->set_flashdata('error', 'Erro ao abrir chamado, tente novamente!');
                redirect('/chamados');                                               
            }
            
        }else{
            $this->session->set_flashdata('error', 'Erro ao abrir chamado, atualize a página e tente novamente!');
            redirect('/chamados/');                                               
        }
    }
    
    // adiciona mensagem ao chamado
    public function add_mensagem(){
        $this->form_validation->set_rules('mensagem', 'MENSAGEM', 'trim|required');                

        if($this->form_validation->run() == TRUE){           
           
            $msg = array(
                'id_chamados' => $this->input->post('chamado'),
                'referencia' => 1,
                'mensagem' => $this->input->post('mensagem'),
                'status_mensagem' => 0
            );
                        
            if($this->chamados->add_mensagem($msg)){
                
                $id_user = $this->chamados->lista_dados_chamado($this->input->post('chamado'));
            
                // registra log de visualização
                logs_acao(4, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), $id_user->id_servicos, null, '<div class="text-info">Enviou uma mensagem no chamado #'.$id_user->id_chamados.' - '.$id_user->assunto.'</div>');
                
                /***************************************************************
                 * DISPARA E-MAIL DE NOTIFICAÇÃO
                 **************************************************************/
                // verifica qual o usuário que esta atendendo o chamado
                $usuario = $this->usuarios->lista_usuario_id($id_user->id_usuarios);
                // formata nível de urgência
                switch ($id_user->nivel_urgencia){
                    case 0:
                        $urgencia = 'Baixa';
                        $urgenciaAssunto = '[BAIXA]';
                    break;
                    case 1:
                        $urgencia = 'Normal';
                        $urgenciaAssunto = '[NORMAL]';
                    break;
                    case 2:
                        $urgencia = 'Alta';
                        $urgenciaAssunto = '[ALTA]';
                    break;
                }
                
                // resgata nome do serviço
                $servico = $this->servicos->lista_servico_id($id_user->id_servicos);

                // registra notificação
                notificacoes($this->session->userdata('id_cliente'), $this->session->userdata('id'), 4, $servico->id, 'Enviou uma mensagem no chamado #'.$id_user->id_chamados.' - '.$urgenciaAssunto.$this->input->post('assunto'));
                
                // monta mensagem
                $mensagem  = "Olá ".$usuario->nome.", <br />";
                $mensagem .= "Uma mensagem acaba de ser enviada, segue abaixo os detalhes: <br />";
                $mensagem .= "<hr />";
                $mensagem .= "<b>Cliente:</b> ".$this->session->userdata('nome_cliente')."<br />";
                $mensagem .= "<b>Funcionário(a):</b> ".$this->session->userdata('nome')."<br />";
                $mensagem .= "<b>Assunto:</b> ".$id_user->assunto."<br />";
                $mensagem .= "<b>Serviço:</b> ".$servico->nome."<br />";
                $mensagem .= "<b>Urgência:</b> ".$urgencia."<br />";
                $mensagem .= "<b>Mensagem:</b> ".$this->input->post('mensagem')."<br />";
                $mensagem .= "<b>Data de abertura:</b> ".date('d/m/Y H:i:s')."<br />";                        
                $mensagem .= "<hr />";
                $mensagem .= "Para visualizar ".anchor('/admin/chamados/ver/'.$this->input->post('chamado'), 'Clique aqui', 'target="_blank"');
                
                envia_email($usuario->email, 'Chamado - '.$urgenciaAssunto." #".$this->input->post('chamado')." ".$id_user->assunto, $mensagem);
                                
                $this->session->set_flashdata('sucesso', 'Mensagem enviada com sucesso, em breve estaremos respondendo!');
                redirect('/chamados/ver/'.$this->input->post('chamado'));      
               
            }else{
                $this->session->set_flashdata('error', 'Erro ao enviar mensagem, tente novamente!');
                redirect('/chamados/ver/'.$this->input->post('chamado'));                                               
            }
            
        }else{
            $this->session->set_flashdata('error', 'Erro ao enviar mensagem, atualize a página e tente novamente!');
            redirect('/chamados/ver/'.$this->input->post('chamado'));                                               
        }
    }
    
    // visualização de chamado
    public function ver(){
        if($this->session->userdata('clienteLogado') == false){            
            $this->load->view('sys/telas/login_view');
        }else{    
            $cabecalho = array(
                'config' => $this->configuracoes->lista_configs(1),
                'mensagens' => $this->chamados->lista_chamados_cliente_usuario($this->session->userdata('id_cliente'), $this->session->userdata('id'), 1)
            );
            
            $id_user = $this->chamados->lista_dados_chamado($this->uri->segment(3));
            
            $dados = array(
                'dados' => $this->chamados->lista_dados_chamado($this->uri->segment(3)),
                'mensagens' => $this->chamados->lista_mensagens_chamado($this->uri->segment(3)),
                'servicos' => $this->clientes->lista_servico_cliente($this->session->userdata('id_cliente')),
                'usuario' => $this->clientes->lista_cliente_usuario_id($id_user->idclientes_usuarios),
                'funcionario' => $this->usuarios->lista_usuario_id($id_user->id_usuarios),
                'lista_funcionario' => $this->usuarios->lista_usuarios()
            );
            
            // registra log de visualização
            logs_acao(4, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), $id_user->id_servicos, null, '<div class="text-info">Visualizou o chamado #'.$id_user->id_chamados.' - '.$id_user->assunto.'</div>');
            
            
            $this->load->view('sys/inc/header_html');
            $this->load->view('sys/inc/header', $cabecalho);
            $this->load->view('sys/inc/sidebar');
            $this->load->view('sys/telas/chamados_mensagens_view', $dados);
            $this->load->view('sys/inc/footer');
            $this->load->view('sys/inc/footer_html');
        }
    }
    
     // atualiza status de mensagens lidas
    public function leitura_mensagem(){
        $this->chamados->atualiza_mensagens($this->uri->segment(3));
        
        $id_user = $this->chamados->lista_dados_chamado($this->uri->segment(3));

        // registra log de visualização
        logs_acao(4, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), $id_user->id_servicos, null, '<div class="text-info">Visualizou uma mensagem no chamado #'.$id_user->id_chamados.' - '.$id_user->assunto.'</div>');

    }
    
    // fecha chamado
    public function fechar_chamado(){
        $dados = array(
            'id_chamados' => $this->input->post('chamado'),
            'status_chamado' => 2,
            'status_mensagem' => 1
        );
        
        if($this->chamados->atualiza_chamado($dados)){    
            
            $id_user = $this->chamados->lista_dados_chamado($this->input->post('chamado'));

            // registra log de visualização
            logs_acao(4, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), $id_user->id_servicos, null, '<div class="text-info">Finalizou atendimento do chamado #'.$id_user->id_chamados.' - '.$id_user->assunto.'</div>');

            // registra notificação
            notificacoes($this->session->userdata('id_cliente'), $this->session->userdata('id'), 4,  $id_user->id_servicos, 'Finalizou atendimento do chamado #'.$id_user->id_chamados.' - '.$id_user->assunto);
            
            $this->session->set_flashdata('sucesso', 'O chamado #'.$this->input->post('chamado').' foi fechado com sucesso.');
            redirect('/chamados/');
        }else{
            $this->session->set_flashdata('error', 'Erro ao fechar o chamado, atualize a página e tente novamente.');
            redirect('/chamados/ver/'.$this->input->post('chamado'));
        }
    }
        
}