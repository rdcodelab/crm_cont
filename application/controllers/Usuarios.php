<?php
class Usuarios extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Clientes_model', 'clientes');
        $this->load->model('Configuracoes_model', 'configuracoes');
        $this->load->model('Arquivos_model', 'arquivos');        
        $this->load->model('Chamados_model', 'chamados');        
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
                'usuarios' => $this->clientes->lista_usuarios_cliente($this->session->userdata('id_cliente')), 
                'tipo_arquivo' => $this->clientes->lista_tipoArquivo_cliente($this->session->userdata('id_cliente'))
            );
            
            $this->load->view('sys/inc/header_html');
            $this->load->view('sys/inc/header', $cabecalho);
            $this->load->view('sys/inc/sidebar');
            $this->load->view('sys/telas/usuarios_view', $dados);
            $this->load->view('sys/inc/footer');
            $this->load->view('sys/inc/footer_html');
        }
    }
    
    // cadastro de usuário
    public function add(){                  
        
        $dados = array(
            'id_clientes' => $this->session->userdata('id_cliente'),
            'nome' => $this->input->post('nome'),            
            'email' => $this->input->post('email'),
            'senha' => md5($this->input->post('senha')),
            'tipo_usuario' => $this->input->post('tipo'),
            'status' => $this->input->post('status')
        );     
        
        $permissao = $this->input->post('tipo_arquivo');
        
        if($this->clientes->add_usuario($dados, $permissao)){
            
            logs_acao(3, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), null, null, '<div class="text-success">Cadastrou o usuário '.$this->input->post('nome').'</div>');
            
            $this->session->set_flashdata('sucesso', 'Usuário cadastrado com sucesso!');
            redirect('/usuarios/');
        }else{
            $this->session->set_flashdata('error', 'Erro ao cadastrar usuário!');
            redirect('/usuarios/');
        }
        
    }    

    
    public function editar(){        
        
        $this->form_validation->set_rules('nome', 'NOME', 'trim|required|max_length[90]|ucwords');
        $this->form_validation->set_rules('email', 'E-MAIL', 'trim|required|strtolower|valid_email');                
        if($this->form_validation->run() == TRUE){           
           
            $dados = array(
                'id_usuario' => $this->input->post('usuario'),
                'nome' => $this->input->post('nome'),
                'email' => $this->input->post('email'),
                'senha' => md5($this->input->post('senha')),
                'tipo_usuario' => $this->input->post('tipo'),
                'status' => $this->input->post('status')
            );                        
                        
            $permissao = $this->input->post('tipo_arquivo');
            
            if($this->clientes->editar_usuario($dados, $permissao)){
                
                logs_acao(3, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), null, null, '<div class="text-info">Atualizou o cadastro do usuário '.$this->input->post('nome').'</div>');
                
                $this->session->set_flashdata('sucesso', 'Usuário atualizado com sucesso!');
                redirect('/usuarios/editar/'.$this->input->post('usuario'));      
               
            }else{
                $this->session->set_flashdata('error', 'Erro ao atualizar dados do usuário, tente novamente!');
                redirect('/usuarios/editar/'.$this->input->post('usuario'));                                               
            }
            
        }else{   
            
            $cabecalho = array(
                'config' => $this->configuracoes->lista_configs(1),
                'mensagens' => $this->chamados->lista_chamados_cliente_usuario($this->session->userdata('id_cliente'), $this->session->userdata('id'), 1)
            );
            
            $dados = array(
                'usuario' => $this->clientes->lista_cliente_usuario_id($this->uri->segment(3)),
                'tipo_arquivo' => $this->clientes->lista_tipoArquivo_cliente($this->session->userdata('id_cliente')),
                'tipo_arquivo_per' => $this->clientes->lista_tipoArquivo_usuario($this->uri->segment(3))
            );
            
            $this->load->view('sys/inc/header_html');
            $this->load->view('sys/inc/header', $cabecalho);
            $this->load->view('sys/inc/sidebar');
            $this->load->view('sys/telas/usuarios_editar_view', $dados);
            $this->load->view('sys/inc/footer');
            $this->load->view('sys/inc/footer_html');
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
                redirect('usuarios/editar/'.$this->input->post('usuario')); 
            }else{                            
                $arquivo_upado = $this->upload->data();
                $dados = array(
                    'id_usuario'=> $this->input->post('usuario'),
                    'foto' => $arquivo_upado['file_name']                    
                );
                        
                if($this->clientes->editar_usuario($dados)){
                    
                    // resgata o nome do cliente para registro do log
                    $cliente = $this->clientes->lista_cliente_usuario_id($this->input->post('usuario'));
                    $nome = $cliente->nome;
                    
                    logs_acao(3, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), null, null, '<div class="text-info">Atualizou a imagem do usuário '.$nome.'</div>');
                    
                    // configura mensagem
                    $this->session->set_flashdata('sucesso', 'Imagem salva com sucesso!!!');
                    redirect('usuarios/editar/'.$this->input->post('usuario'));                
                }else{
                    // configura mensagem
                    $this->session->set_flashdata('error', 'Erro ao adicionar imagem!!!');
                    redirect('usuarios/editar/'.$this->input->post('usuario'));
                }
            }
    }
    
    public function redefine_senha(){
        $dados = array(
            'id_usuario' => $this->input->post('usuario'),
            'senha' => md5($this->input->post('senha')),
        );                    

        $permissao = $this->input->post('tipo_arquivo');                  
         
        if($this->clientes->editar_usuario($dados, $permissao)){ 
            
            // resgata o nome do cliente para registro do log
            $cliente = $this->clientes->lista_cliente_usuario_id($this->input->post('usuario'));
            $nome = $cliente->nome;

            logs_acao(3, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), null, null, '<div class="text-info">Atualizou a senha do usuário '.$nome.'</div>');
            
            $this->session->set_flashdata('sucesso', 'Usuário atualizado com sucesso!');
            redirect('/usuarios/editar/'.$this->input->post('usuario'));      

        }else{
            $this->session->set_flashdata('error', 'Erro ao atualizar dados do usuário, tente novamente!');
            redirect('/usuarios/editar/'.$this->input->post('usuario'));                                               
        }
    }
    
    public function excluir(){
        
        // resgata o nome do cliente para registro do log
        $cliente = $this->clientes->lista_cliente_usuario_id($this->input->post('usuario'));
        $nome = $cliente->nome;
        
        if($this->clientes->del_usuario($this->input->post('usuario'))){
            
            logs_acao(3, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), null, null, '<div class="text-danger">Deletou o cadastro do usuário '.$nome.'</div>');
            
            $this->session->set_flashdata('sucesso', 'Usuário excluído com sucesso!');
            redirect('usuarios/');
        }else{
            $this->session->set_flashdata('error', 'Erro ao excluir usuário, tente novamente!');
            redirect('usuarios/');
        }
    }
    
}
