<?php
class Home extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Clientes_model', 'clientes');
        $this->load->model('Arquivos_model', 'arquivos');
        $this->load->model('Configuracoes_model', 'configuracoes');
        $this->load->model('Chamados_model', 'chamados');
        $this->load->model('Servicos_model', 'servicos');
        $this->load->model('Tarefas_model', 'tarefas');
        
        $this->load->helper('encode');
        
        // realiza controle de status de arquivos.
        $this->arquivos->controle_status();                
        
        /***********************************************************************
         * DISPARA NOTIFICAÇÕES TODOS OS DIAS AS 10:00h
         **********************************************************************/
        if(date('H:i') == '10:00'):
            $dataExpiracao = SomarData(date('Y-m-d'), 5, 0, 0);
            arquivos_a_vencer($dataExpiracao);
        endif;
        
    }
    
    public function index(){
         if($this->session->userdata('clienteLogado') == false){            
            $this->load->view('sys/telas/login_view');
        }else{         
            
            $cabecalho = array(
                'config' => $this->configuracoes->lista_configs(1),
                'mensagens' => $this->chamados->lista_chamados_cliente_usuario($this->session->userdata('id_cliente'), $this->session->userdata('id'), 1),
                'tarefas' => $this->tarefas->getDemandaTarefaUsuarioCliente($this->session->userdata('id'))
            );
            
            $dados = array(
                'arquivos' => $this->arquivos->lista_arquivos_cliente($this->session->userdata('id_cliente')),
                'chamados' => $this->chamados->lista_todoschamados_cliente($this->session->userdata('id_cliente'), 10),
                'arquivos_vcto' => $this->arquivos->getArquivoVcto($this->session->userdata('id_cliente'), 1, date('Y-m-d')),
                'arquivos_novos' => $this->arquivos->lista_arquivos_cliente_limite_ordem($this->session->userdata('id_cliente'), 'desc'),
                'tipos' => $this->arquivos->lista_tipos(),                                
                'cl_serv' => $this->clientes->lista_servicos_id($this->session->userdata('id_cliente')),
                'tarefas_clientes' => $this->tarefas->getTarefasClientes($this->session->userdata('id_cliente'))
            );
            
            $this->load->view('/sys/inc/header_html');
            $this->load->view('/sys/inc/header', $cabecalho);
            $this->load->view('/sys/inc/sidebar');
            $this->load->view('/sys/telas/home/index', $dados);
            $this->load->view('/sys/inc/footer');
            $this->load->view('/sys/inc/footer_html');
        }
    }
    
    public function login(){
        $this->form_validation->set_rules('usuario', 'USUÁRIO', 'trim|required');
        $this->form_validation->set_rules('senha', 'SENHA', 'trim|required');
        
        if($this->form_validation->run() == true){
            $dados = array(
                'usuario' => $this->input->post('usuario'),
                'senha' => md5($this->input->post('senha')),
            );
            
            if($data = $this->clientes->login($dados)){
                // verifica se o cliente esta bloqueado
                //print_r($data);
                if($data->cliente_status == 0){
                    $this->session->set_flashdata('error', 'Cadastro bloqueado, entre em contato com o escritório.');
                    redirect('/home/');
                }elseif($data->user_status == 0){
                    $this->session->set_flashdata('error', 'Seu cadastro esta bloqueado, entre em contato com o administrador da sua conta.');
                    redirect('/home/');
                }else{
                    //foreach($data as $ln): endforeach;
                    $sessao = array(
                        'id' => $data->idclientes_usuarios,
                        'nome' => $data->nome,
                        'foto' => $data->foto,
                        'email' => $data->email,
                        'tipo' => $data->tipo_usuario,
                        'id_cliente' => $data->id_clientes,
                        'nome_cliente' => $data->razao_social,
                        'cnpj_cliente' => $data->cnpj,
                        'clienteLogado' => true
                    );
                    $this->session->set_userdata($sessao);

                    // registra log de acesso
                    logs_acao(2, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), null, null, '<div class="text-primary">Acessou o sistema.</div>');
                    redirect('/home');
                }
            }else{
                $this->session->set_flashdata('error', 'Clientes ou senha incorretos');
                redirect('/home/');
            }            
            
        }else{
            $this->session->set_flashdata('error', 'Clientes ou senha incorretos');
            redirect('/home/');
        }
    }
    
    public function logout(){        
        
        // registra log de acesso
        logs_acao(2, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), null, null, '<div class="text-primary">Saiu do sistema.</div>');
        
        $this->session->sess_destroy();
        $this->session->set_flashdata('sucesso', 'Obrigao por acessar nosso sistema!');
        redirect('/home/');
    }
    
    // recuperação de senha
    public function recupera_senha(){
        
        $dados = $this->clientes->valida_usuario($this->input->post('usuario'));
        
        if(count($dados) > 0){
            
            // registra log de acesso
            logs_acao(2, 1, $dados->id_clientes, $dados->idclientes_usuarios, null, null, '<div class="text-warning">Solicitou recuperação de senha.</div>');           
            
            // dispara e-mail com o link de redefinição
            $id_cripto = url_base64_encode($dados->idclientes_usuarios);

            $msg  = "Olá ".$dados->nome.", <br />";
            $msg .= "Foi solicitado em nosso sistema a redefinição de sua senha de acesso ao mesmo, para redefini-lá acesse o link abaixo: <br />";
            $msg .= anchor('/home/recover/'.$id_cripto, 'Clique aqui', 'target="_blank"')." para redefinir. <br />";
            $msg .= "Caso não tenha sido você, por favor ignore esta mensagem e informe os administradores do sistema.";
            
            // envia email
            envia_email($dados->email, 'Redefinição de senha', $msg);            
            
            // configura mensagem
            echo '<div class="alert alert-success">Enviamos ao seu e-mail, o link de redefinição de senha. Caso não tenha recebido, verifique em sua caixa de span.</div>';
           
        }else{
            echo '<div class="alert alert-warning">Não localizamos seu e-mail em nosso banco de dados, verifique se o mesmo esta correto e tente novamente.</div>';
        }
    }
    
    // recover de atualização de senha
    public function recover(){        
        
        $this->form_validation->set_rules('senha', 'SENHA', 'trim|required|strtolower');
        $this->form_validation->set_message('matches', 'O campo %s está diferente do campo %s');// mensagem personalizada
        $this->form_validation->set_rules('senha2', 'CONFIRME A SENHA', 'trim|required|strtolower|matches[senha]');
        
        if($this->form_validation->run() == TRUE){ 
            
            $iduser = $this->input->post('usuario');
           
            $dados = array(
                'id_usuario' => $iduser,
                'senha' => md5($this->input->post('senha'))
            );
            
                        
            if($this->clientes->editar_usuario($dados)){
                // resgata id do cliente
                $cliente = $this->clientes->lista_cliente_usuario_id($iduser);
                
                logs_acao(2, 1, $cliente->id_clientes, $iduser, null, null, '<div class="text-info">Atualizou sua senha, pelo processo de recuperação de senha.</div>');
                
                $this->session->set_flashdata('sucesso', 'Senha atualizada com sucesso, faça seu login abaixo!');
                redirect('/home');      
               
            }else{
                $this->session->set_flashdata('error', 'Erro ao atualizar senha, tente novamente!');
                redirect('/home/recover/'.$this->uri->segment(3));                                               
            }
            
        }else{                           
            
            $iduser = @url_base64_decode($this->uri->segment(3));
            
            $dados = array(
                'usuario' => $this->clientes->lista_cliente_usuario_id($iduser)                
            );
            
            $this->load->view('sys/telas/recuperasenha_view', $dados);
            
        }
        
      
    }
    
}