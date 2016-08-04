<?php
class Usuarios extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Usuarios_model', 'usuarios');
        $this->load->model('Configuracoes_model', 'configuracoes');
        $this->load->model('Chamados_model', 'chamados');
        $this->load->model('Protocolos_model', 'protocolos');
        $this->load->model('Servicos_model', 'servicos');
        $this->load->model('Tarefas_model', 'tarefas');
        
        
        
        if($this->session->userdata('usuarioLogado') == false){            
            redirect('/admin');
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
                'usuarios' => $this->usuarios->lista_usuarios(),
                'modulos' => $this->usuarios->lista_modulos(),
                'mod_user' => $this->usuarios->lista_modulos_usuario(),
                'servicos' => $this->servicos->lista_servicos()
            );
            
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/usuarios/index', $dados);
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    
    // cadastro de usuário
    public function add(){                   
        
        $dados = array(
            'nome' => $this->input->post('nome'),            
            'email' => $this->input->post('email'),
            'senha' => md5($this->input->post('senha')),
            'tipo_usuario' => $this->input->post('tipo'),
            'id_servico' => $this->input->post('servico'),
            'status' => $this->input->post('status')
        );    
        
        $permissoes = $this->input->post('perm_acesso');
        
        if($this->usuarios->adicionar($dados, $permissoes)){
            
            logs_acao(2, 2, null, $this->session->userdata('id'), $this->input->post('servico'), null, '<div class="text-success">Cadastrou o usuário '.$this->input->post('nome').'</div>');
            
            $this->session->set_flashdata('sucesso', 'Usuário cadastrado com sucesso!');
            redirect('/admin/usuarios/');
        }else{
            $this->session->set_flashdata('error', 'Erro ao cadastrar usuário!');
            redirect('/admin/usuarios/');
        }
        
    }   

    
    public function editar(){        
        
        $this->form_validation->set_rules('nome', 'NOME', 'trim|required|max_length[90]|ucwords');
        $this->form_validation->set_rules('email', 'E-MAIL', 'trim|required|strtolower|valid_email');                
        if($this->form_validation->run() == TRUE){         
            
            // verifica tipo de usuario
            if($this->input->post('tipo') == 1){
                $servico = 0;
            }else{
                $servico = $this->input->post('servico');
            }
           
            $dados = array(
                'id_usuario' => $this->input->post('usuario'),
                'nome' => $this->input->post('nome'),
                'email' => $this->input->post('email'),
                'tipo' => $this->input->post('tipo'),
                'id_servico' => $servico,
                'status' => $this->input->post('status'),
            );
            
            $permissoes = $this->input->post('perm_acesso');
                        
            if($this->usuarios->editar_usuario($dados, $permissoes)){
                
                logs_acao(2, 2, null, $this->session->userdata('id'), $servico, null, '<div class="text-info">Atualizou o cadastro do usuário '.$this->input->post('nome').'</div>');
                
                $this->session->set_flashdata('sucesso', 'Usuário atualizado com sucesso!');
                redirect('/admin/usuarios/editar/'.$this->input->post('usuario'));      
               
            }else{
                $this->session->set_flashdata('error', 'Erro ao atualizar dados do usuário, tente novamente!');
                redirect('/admin/usuarios/editar/'.$this->input->post('usuario'));                                               
            }
            
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
                'usuario' => $this->usuarios->lista_usuario_id($this->uri->segment(4)),
                'modulos' => $this->usuarios->lista_modulos(),
                'mod_user' => $this->usuarios->nivel_acesso($this->uri->segment(4)),
                'servicos' => $this->servicos->lista_servicos()
            );
            
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/usuarios/editar_view', $dados);
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    
    public function atualizaImg(){
        
            // faz upload
            $config['upload_path'] = './uploads/img_usuarios/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '1024';           
            $config['encrypt_name'] = true;
            $this->load->library('upload', $config);
            
            if(! $this->upload->do_upload()){
                $this->session->set_flashdata('error', 'Erro ao upar imagem. '.$this->upload->display_errors());
                redirect('admin/usuarios/editar/'.$this->input->post('usuario')); 
            }else{                            
                $arquivo_upado = $this->upload->data();
                $dados = array(
                    'id_usuario'=> $this->input->post('usuario'),
                    'foto' => $arquivo_upado['file_name']                    
                );
                        
                if($this->usuarios->editar_usuario($dados)){
                    
                    $usuario = $this->usuarios->lista_usuario_id($this->input->post('usuario'));
                    $nome = $usuario->nome;
                    
                    logs_acao(2, 2, null, $this->session->userdata('id'), $usuario->id_servico, null, '<div class="text-info">Atualizou a imagem do usuário '.$nome.'</div>');
                    
                    // configura mensagem
                    $this->session->set_flashdata('sucesso', 'Imagem salva com sucesso!!!');
                    redirect('admin/usuarios/editar/'.$this->input->post('usuario'));                
                }else{
                    // configura mensagem
                    $this->session->set_flashdata('error', 'Erro ao adicionar imagem!!!');
                    redirect('admin/usuarios/editar/'.$this->input->post('usuario'));
                }
            }
    }
    
    public function redefine_senha(){
         $dados = array(
                'id_usuario' => $this->input->post('usuario'),
                'senha' => md5($this->input->post('senha')),
            );
            
        $permissoes = null;

        if($this->usuarios->editar_usuario($dados, $permissoes)){
            
            $usuario = $this->usuarios->lista_usuario_id($this->input->post('usuario'));
            $nome = $usuario->nome;

            logs_acao(2, 2, null, $this->session->userdata('id'), $usuario->id_servico, null, '<div class="text-info">Atualizou a senha do usuário '.$nome.'</div>');
            
            $this->session->set_flashdata('sucesso', 'Usuário atualizado com sucesso!');
            redirect('/admin/usuarios/editar/'.$this->input->post('usuario'));      

        }else{
            $this->session->set_flashdata('error', 'Erro ao atualizar dados do usuário, tente novamente!');
            redirect('/admin/usuarios/editar/'.$this->input->post('usuario'));                                               
        }
    }
    
    public function excluir(){
        
        $usuario = $this->usuarios->lista_usuario_id($this->input->post('usuario'));
        $nome = $usuario->nome;
        
        if($this->usuarios->excluir($this->input->post('usuario'))){                        

            logs_acao(2, 2, null, $this->session->userdata('id'), $usuario->id_servico,  null, '<div class="text-danger">Deletou o cadastro do usuário '.$nome.'</div>');
            
            $this->session->set_flashdata('sucesso', 'Usuário excluído com sucesso!');
            redirect('admin/usuarios/');
        }else{
            $this->session->set_flashdata('error', 'Erro ao excluir usuário, tente novamente!');
            redirect('admin/usuarios/');
        }
    }
    
    public function protocolos(){
        
        $cabecalho = array(
            'config' => $this->configuracoes->lista_configs(1),
            'mensagens' => $this->chamados->lista_chamados_usuario($this->session->userdata('id')),
            'tarefas' => $this->tarefas->getTarefasStatusResp($this->session->userdata('id'), 1)
        );
            
        $menu = array(
            'menu' => $this->usuarios->nivel_acesso($this->session->userdata('id'))
        );            
            
        $dados = array(
            'usuario' => $this->usuarios->lista_usuario_id($this->uri->segment(4)),
            'protocolos' => $this->protocolos->lista_logs_usuarios($this->uri->segment(4))
        );
            
        $this->load->view('sysadmin/inc/header_html');
        $this->load->view('sysadmin/inc/header', $cabecalho);
        $this->load->view('sysadmin/inc/sidebar', $menu);
        $this->load->view('sysadmin/telas/usuarios/protocolos_view', $dados);
        $this->load->view('sysadmin/inc/footer');
        $this->load->view('sysadmin/inc/footer_html');
        
    }
    
}
