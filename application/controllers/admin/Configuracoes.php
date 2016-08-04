<?php
class Configuracoes extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Usuarios_model', 'usuarios');
        $this->load->model('Configuracoes_model', 'configuracoes');
        $this->load->model('Chamados_model', 'chamados');
        $this->load->model('Tarefas_model', 'tarefas');
        $this->load->model('Arquivos_model', 'arquivos');
        $this->load->model('Servicos_model', 'servicos');
        
       
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
                'config' => $this->configuracoes->lista_configs(1),                
            );
            
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/configuracoes_view', $dados);
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    
    public function pastas_documentos(){
        
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
                    'tipos' => $this->arquivos->lista_tipos(),
                    'servicos' => $this->servicos->lista_servicos()
                );
            
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/arquivos_pastas_view', $dados);
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    
    public function editar(){
        $dados = array(
            'razao' => $this->input->post('razao'),
            'fantasia' => $this->input->post('fantasia'),
            'cnpj' => $this->input->post('cnpj'),
            'endereco' => $this->input->post('endereco'),
            'bairro' => $this->input->post('bairro'),
            'cep' => $this->input->post('cep'),
            'telefone' => $this->input->post('fone'),
            'email' => $this->input->post('email'),
            'site' => $this->input->post('site'),
            'vcto' => $this->input->post('vcto'),
        );
        
        if($this->configuracoes->editar($dados)){
            // registro de logs
            logs_acao(6, 2, null, $this->session->userdata('id'), null, '<div class="text-info">Atualizou as informações da empresa no sistema.</div>');
            
            $this->session->set_flashdata('sucesso', 'Dados atualizados com sucesso!');
            redirect('/admin/configuracoes');
        }else{
            $this->session->set_flashdata('error', 'Erro ao atualizar dados, por favor tente novamente!');
            redirect('/admin/configuracoes');
        }
    }
    
    public function atualizaImg(){
        
            // faz upload
            $config['upload_path'] = './uploads/imagens/';
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size'] = '1024';           
            $config['encrypt_name'] = false;
            $this->load->library('upload', $config);
            
            if(! $this->upload->do_upload()){
                $this->session->set_flashdata('error', 'Erro ao upar imagem. '.$this->upload->display_errors());
                redirect('admin/configuracoes/'); 
            }else{                            
                $arquivo_upado = $this->upload->data();
                $dados = array(
                    'id'=> 1,
                    'logo' => $arquivo_upado['file_name']                    
                );
                        
                if($this->configuracoes->editar($dados)){
                    
                    // registro de logs
                    logs_acao(6, 2, null, $this->session->userdata('id'), null, '<div class="text-info">Atualizou a logo da empresa no sistema.</div>');
                    
                    // configura mensagem
                    $this->session->set_flashdata('sucesso', 'Logo salva com sucesso!!!');
                    redirect('admin/configuracoes/');                
                }else{
                    // configura mensagem
                    $this->session->set_flashdata('error', 'Erro ao salvar logo!!!');
                    redirect('admin/configuracoes/');
                }
            }
    }
    
}

