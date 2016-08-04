<?php
class Chamados extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Servicos_model', 'servicos');
        $this->load->model('Configuracoes_model', 'configuracoes');        
        $this->load->model('Usuarios_model', 'usuarios');        
        $this->load->model('Clientes_model', 'clientes');
        $this->load->model('Chamados_model', 'chamados');        
        $this->load->model('Tarefas_model', 'tarefas'); 
        
        // controle de nível de acesso
        $this->load->helper('Permissoes');
        controle_acesso($this->session->userdata('id'), 'chamados');
        
        
        if($this->session->userdata('usuarioLogado') == false){            
            redirect('/admin/home/login');
        }
    }
    
    public function index(){
        if($this->session->userdata('usuarioLogado') == false){            
            $this->load->view('sysadmin/telas/login_view');
        }else{    
            $cabecalho = array(
                'config' => $this->configuracoes->lista_configs(1),
                'mensagens' => $this->chamados->lista_chamados_usuario($this->session->userdata('id')),
                'tarefas' => $this->tarefas->getTarefasStatusResp($this->session->userdata('id'), 1)
            );
            
            $menu = array(
                'menu' => $this->usuarios->nivel_acesso($this->session->userdata('id'))
            );
            
            $dados = array(
                'chamados_novos' => $this->chamados->lista_chamados_status(0, $this->session->userdata('servico')),
                'chamados_pendentes' => $this->chamados->lista_chamados_status(1, $this->session->userdata('servico')),
                'chamados_fechados' => $this->chamados->lista_chamados_status(2, $this->session->userdata('servico')),
                'servicos' => $this->servicos->lista_servicos(),
                'clientes' => $this->clientes->lista_clientes(),
                'clientes_usuarios' => $this->clientes->lista_todos_usuarios(),
            );
            
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/chamados/index', $dados);
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    
    public function listachamados(){
        
        $chamados = $this->chamados->lista_todos_chamados();
        
        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/json");
        
        echo '{              
                "data": [';
                    $lista = "";
                    foreach($chamados as $ln):
                        // data de envio
                        $data1   = substr($ln->data_cadastro, 0, 10);
                        $data_br = implode("/", array_reverse(explode("-", $data1)));
                        // formata hora
                        $hora1 = substr($ln->data_cadastro, 11, 8);
                        $dataRegistro = $data_br.' '. $hora1;
                        
                        // resgata nome do cliente
                        $cliente = $this->clientes->lista_cliente_id($ln->id_clientes);
                        
                        // resgata setor e pasta
                        $setor = $this->servicos->lista_servico_id($ln->id_servicos);                        
                        $n_setor = $setor->nome;
                        
                        // resgata nome do responsável
                        $user = $this->clientes->lista_cliente_usuario_id($ln->idclientes_usuarios);
                        
                        // formata status do arquivo                        
                        switch ($ln->status_chamado){
                            case 0:
                                $status = '<div class=\"label label-default\">Novo</div>';
                            break;
                            case 1:
                                $status = '<div class=\"label label-info\">Pendente</div>';
                            break;
                            case 2:
                                $status = '<div class=\"label label-warning\">Fechado</div>';
                            break;                            
                            default :
                                $status = '<div class=\"label label-default\">n/d</div>';
                            break;
                        } 
                        
                        // formata status de prioridade
                        switch ($ln->nivel_urgencia){
                            case 0:
                                $prioridade = '[BAIXA]';
                            break;
                            case 1:
                                $prioridade = '[NORMAL]';
                            break;
                            case 2:
                                $prioridade = '[ALTA]';
                            break;        
                            default :
                                $prioridade = '<div class="label label-default">n/d</div>';
                            break;
                        }
                        
                        $lista .= '{
                                "numero": "'.$ln->id_chamados.'",                                
                                "cliente": "'.$cliente->razao_social.'",
                                "usuario": "'.$user->nome.'",
                                "setor": "'.$n_setor.'",
                                "assunto": "'.$ln->assunto.'",
                                "prioridade": "'.$prioridade.'",                               
                                "data_abertura": "'.$dataRegistro.'",                               
                                "status": "'.$status.'",                               
                                "opc": "<a href=\"'.base_url('/admin/chamados/ver/'.$cn->id_chamados).'\" title=\"Editar dados\" class=\"btn btn-info btn-sm\"><i class=\"fa fa-edit\"></i></a>"
                              },';
                    endforeach;
                    
                    echo substr($lista, 0, -1);
            echo ']

            }';                        
    }
    
    public function listachamados_filtro(){
        
        $filtros = array(
            'cliente' => $this->input->get('cliente'),
            'assunto' => $this->input->get('chamado'),
            'setor' => $this->input->get('tipo'),
            'status' => $this->input->get('status'),
            'prioridade' => $this->input->get('prioridade'),
            'envio_inicial' => implode("-", array_reverse(explode("/", $this->input->get('envio_inicial')))),
            'envio_final' => implode("-", array_reverse(explode("/", $this->input->get('envio_final')))),
        );
        
        $chamados = $this->chamados->lista_filtro_chamados($filtros);
        
        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/json");
        
        echo '{              
                "data": [';
                    $lista = "";
                    foreach($chamados as $ln):
                        // data de envio
                        $data1   = substr($ln->data_cadastro, 0, 10);
                        $data_br = implode("/", array_reverse(explode("-", $data1)));
                        // formata hora
                        $hora1 = substr($ln->data_cadastro, 11, 8);
                        $dataRegistro = $data_br.' '. $hora1;
                        
                        // resgata nome do cliente
                        $cliente = $this->clientes->lista_cliente_id($ln->id_clientes);
                        
                        // resgata setor e pasta
                        $setor = $this->servicos->lista_servico_id($ln->id_servicos);                        
                        $n_setor = $setor->nome;
                        
                        // resgata nome do responsável
                        $user = $this->clientes->lista_cliente_usuario_id($ln->idclientes_usuarios);
                        
                        // formata status do arquivo                        
                        switch ($ln->status_chamado){
                            case 0:
                                $status = '<div class=\"label label-default\">Novo</div>';
                            break;
                            case 1:
                                $status = '<div class=\"label label-info\">Pendente</div>';
                            break;
                            case 2:
                                $status = '<div class=\"label label-warning\">Fechado</div>';
                            break;                            
                            default :
                                $status = '<div class=\"label label-default\">n/d</div>';
                            break;
                        } 
                        
                        // formata status de prioridade
                        switch ($ln->nivel_urgencia){
                            case 0:
                                $prioridade = '[BAIXA]';
                            break;
                            case 1:
                                $prioridade = '[NORMAL]';
                            break;
                            case 2:
                                $prioridade = '[ALTA]';
                            break;        
                            default :
                                $prioridade = '<div class="label label-default">n/d</div>';
                            break;
                        }
                        
                        $lista .= '{
                                "numero": "'.$ln->id_chamados.'",                                
                                "cliente": "'.$cliente->razao_social.'",
                                "usuario": "'.$user->nome.'",
                                "setor": "'.$n_setor.'",
                                "assunto": "'.$ln->assunto.'",
                                "prioridade": "'.$prioridade.'",                               
                                "data_abertura": "'.$dataRegistro.'",                               
                                "status": "'.$status.'",                               
                                "opc": "<a href=\"'.base_url('/admin/chamados/ver/'.$cn->id_chamados).'\" title=\"Editar dados\" class=\"btn btn-info btn-sm\"><i class=\"fa fa-edit\"></i></a>"
                              },';
                    endforeach;
                    
                    echo substr($lista, 0, -1);
            echo ']

            }';                        
    }
        
    // visualização de chamado
    public function ver(){
        if($this->session->userdata('usuarioLogado') == false){            
            $this->load->view('sysadmin/telas/login_view');
        }else{    
            $cabecalho = array(
                'config' => $this->configuracoes->lista_configs(1),
                'mensagens' => $this->chamados->lista_chamados_usuario($this->session->userdata('id')),
                'tarefas' => $this->tarefas->getTarefasStatusResp($this->session->userdata('id'), 1)
            );
            
            $menu = array(
                'menu' => $this->usuarios->nivel_acesso($this->session->userdata('id'))
            );
                        
            $id_user = $this->chamados->lista_dados_chamado($this->uri->segment(4));
            
            $dados = array(
                'dados' => $this->chamados->lista_dados_chamado($this->uri->segment(4)),
                'mensagens' => $this->chamados->lista_mensagens_chamado($this->uri->segment(4)),
                'servicos' => $this->clientes->lista_servico_cliente($id_user->id_clientes),
                'usuario' => $this->clientes->lista_cliente_usuario_id($id_user->idclientes_usuarios),
                'cliente' => $this->clientes->lista_cliente_id($id_user->id_clientes),
                'funcionario' => $this->usuarios->lista_usuario_id($id_user->id_usuarios),
                'lista_funcionario' => $this->usuarios->lista_usuarios()
            );
            
            
            
            // registra log de visualização
            logs_acao(4, 2, $id_user->id_clientes, $this->session->userdata('id'), $id_user->id_servicos,null, '<div class="text-info">Visualizou o chamado #'.$id_user->id_chamados.' - '.$id_user->assunto.'</div>');
            
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/chamados_mensagens_view', $dados);
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
                                
        }
    }
    
    // o usuário assume o atendimento
    public function usuario_assume(){
        
        $dados = array(
            'id_chamados' => $this->uri->segment(4),
            'id_usuarios' => $this->uri->segment(5),
            'status_chamado' => 1,
            'status_mensagem' => 1
        );
        
        if($this->chamados->atualiza_chamado($dados)){   
            
            $id_user = $this->chamados->lista_dados_chamado($this->uri->segment(4));
            
            // registra log de visualização
            logs_acao(4, 2, $id_user->id_clientes, $this->session->userdata('id'), $id_user->id_servicos, null, '<div class="text-success">Assumiu o chamado #'.$id_user->id_chamados.' - '.$id_user->assunto.'</div>');
            
            $this->session->set_flashdata('sucesso', 'Você acaba de assumir este chamado, clique em "Enviar Mensagem" para interagir com o cliente e esclarecer suas dúvidas.');
            redirect('/admin/chamados/ver/'.$this->uri->segment(4));
        }else{
            $this->session->set_flashdata('error', 'Erro ao assumir o chamado, atualize a página e tente novamente.');
            redirect('/admin/chamados/ver/'.$this->uri->segment(4));
        }
        
    }
    
    // o usuário envia resposta ao cliente
    public function add_resposta(){
        $this->form_validation->set_rules('mensagem', 'MENSAGEM', 'trim|required');                

        if($this->form_validation->run() == TRUE){           
           
            $msg = array(
                'id_chamados' => $this->input->post('chamado'),
                'id_usuario' => $this->input->post('usuario'),
                'referencia' => 2,
                'mensagem' => $this->input->post('mensagem'),
                'status_mensagem' => 0
            );
                        
            if($this->chamados->add_mensagem($msg)){
                
                $id_user = $this->chamados->lista_dados_chamado($this->input->post('chamado'));
            
                // registra log de visualização
                logs_acao(4, 2, $id_user->id_clientes, $this->session->userdata('id'), $id_user->id_servicos, null, '<div class="text-info">Enviou uma mensagem no chamado #'.$id_user->id_chamados.' - '.$id_user->assunto.'</div>');
                
                /***************************************************************
                * DISPARA E-MAIL DE NOTIFICAÇÃO
                **************************************************************/
                // verifica qual o usuário que esta atendendo o chamado
                $usuario = $this->clientes->lista_cliente_usuario_id($id_user->idclientes_usuarios);
                
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
                
                // monta mensagem
                $mensagem  = "Olá ".$usuario->nome.", <br />";
                $mensagem .= "Uma mensagem acaba de ser enviada, segue abaixo os detalhes: <br />";
                $mensagem .= "<hr />";
                $mensagem .= "<b>Assunto:</b> ".$id_user->assunto."<br />";
                $mensagem .= "<b>Serviço:</b> ".$servico->nome."<br />";
                $mensagem .= "<b>Urgência:</b> ".$urgencia."<br />";
                $mensagem .= "<b>Mensagem:</b> ".$this->input->post('mensagem')."<br />";
                $mensagem .= "<b>Data de abertura:</b> ".date('d/m/Y H:i:s')."<br />";                        
                $mensagem .= "<hr />";
                $mensagem .= "Para visualizar ".anchor('/chamados/ver/'.$this->input->post('chamado'), 'Clique aqui', 'target="_blank"');
                
                envia_email($usuario->email, 'Resposta de Suporte via Chamado - '.$urgenciaAssunto." #".$this->input->post('chamado')." ".$id_user->assunto, $mensagem);                                
                
                $this->session->set_flashdata('sucesso', 'Mensagem enviada com sucesso!');
                redirect('/admin/chamados/ver/'.$this->input->post('chamado'));      
               
            }else{
                $this->session->set_flashdata('error', 'Erro ao enviar mensagem, tente novamente!');
                redirect('/admin/chamados/ver/'.$this->input->post('chamado'));                                               
            }
            
        }else{
            $this->session->set_flashdata('error', 'Erro ao enviar mensagem, atualize a página e tente novamente!');
            redirect('/admin/chamados/ver/'.$this->input->post('chamado'));                                               
        }
    }
    
    // atualiza status de mensagens lidas
    public function leitura_mensagem(){                
        
        $this->chamados->atualiza_mensagens($this->uri->segment(4));
        
        $id_user = $this->chamados->lista_dados_chamado($this->uri->segment(4));

        // registra log de visualização
        logs_acao(4, 2, $id_user->id_clientes, $this->session->userdata('id'), $id_user->id_servicos, null, '<div class="text-info">Visualizou uma mensagem no chamado #'.$id_user->id_chamados.' - '.$id_user->assunto.'</div>');

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
            logs_acao(4, 2, $id_user->id_clientes, $this->session->userdata('id'), $id_user->id_servicos, null, '<div class="text-info">Finalizou atendimento do chamado #'.$id_user->id_chamados.' - '.$id_user->assunto.'</div>');

            
            $this->session->set_flashdata('sucesso', 'O chamado #'.$this->input->post('chamado').' foi fechado com sucesso.');
            redirect('/admin/chamados/');
        }else{
            $this->session->set_flashdata('error', 'Erro ao fechar o chamado, atualize a página e tente novamente.');
            redirect('/admin/chamados/ver/'.$this->input->post('chamado'));
        }
    }
    
}
