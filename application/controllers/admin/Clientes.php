<?php
class Clientes extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        
        $this->load->model('Clientes_model', 'clientes');
        $this->load->model('Servicos_model', 'servicos');
        $this->load->model('Configuracoes_model', 'configuracoes');
        $this->load->model('Usuarios_model', 'usuarios');
        $this->load->model('Chamados_model', 'chamados');
        $this->load->model('Arquivos_model', 'arquivos');
        $this->load->model('Protocolos_model', 'protocolos');
        $this->load->model('Usuarios_model', 'usuarios');
        $this->load->model('Tarefas_model', 'tarefas');
        
        // controle de nível de acesso
        $this->load->helper('Permissoes');
        controle_acesso($this->session->userdata('id'), 'clientes');
        
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
                'clientes' => $this->clientes->lista_clientes(),
                'clientes_servicos' => $this->clientes->lista_clientes_servicos(),
                'servicos' => $this->servicos->lista_servicos()
            );
            
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/clientes/index', $dados);
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    
    public function add(){
        $this->form_validation->set_rules('razao', 'RAZÃO SOCIAL', 'trim|required|max_length[90]|ucwords');
        $this->form_validation->set_rules('cnpj', 'CNPJ', 'trim|required');                
        $this->form_validation->set_rules('responsavel', 'RESPONSÁVEL PELA EMPRESA', 'trim|required|max_length[60]');                
        $this->form_validation->set_rules('email', 'E-MAIL', 'trim|required|strtolower|valid_email');                
        if($this->form_validation->run() == TRUE){           
           
            $dados = array(
                'cnpj' => $this->input->post('cnpj'),
                'insc_estadual' => $this->input->post('ie'),
                'insc_municipal' => $this->input->post('im'),
                'razao_social' => $this->input->post('razao'),
                'nome_fantasia' => $this->input->post('fantasia'),
                'endereco' => $this->input->post('endereco'),
                'bairro' => $this->input->post('bairro'),
                'cep' => $this->input->post('cep'),
                'cidade' => $this->input->post('cidade'),
                'estado' => $this->input->post('estado'),
                'responsavel' => $this->input->post('responsavel'),
                'email' => $this->input->post('email'),
                'telefone' => $this->input->post('telefone'),
                'celular' => $this->input->post('celular'),
                'celular_sms' => $this->input->post('celularsms'),
                'status' => $this->input->post('slc_status'),
                'id_tipo' => $this->input->post('slc_categoria'),
                'data_cadastro' => date('Y-m-d H:i:s')
            );
            
            $usuarios = array(
                'tipo_usuario' => $this->input->post('tipo_usuario'),
                'nome' => $this->input->post('nome_usuario'),
                'email' => $this->input->post('email_usuario'),
                'senha' => md5($this->input->post('senha_usuario')),  
                'status' => '1'
            );
            
            $servicos = $this->input->post('servicos');
                        
            if($this->clientes->add_clientes($dados, $usuarios, $servicos)){
                
                
                // registra ação de logs
                logs_acao(3, 2, null, $this->session->userdata('id'), null, null, '<div class="text-success">Cadastrou o cliente '.$this->input->post('razao').'</div>');
                
                // informa usuário por e-mail caso opção esteja selecionada
                if($this->input->post('notificacao') == true){
                    
                    $mensagem = "Olá ".$this->input->post('nome_usuario')."<br />";
                    $mensagem .= "A empresa <b>".$this->input->post('razao')."</b>, acabou de ser cadastrada em nosso sistema e você foi adicionado como gestor da conta,";
                    $mensagem .= " segue abaixo detalhes de acesso a sua conta: <br />";
                    $mensagem .= " <hr />";
                    $mensagem .= " <b>URL de acesso: </b>".base_url('/')."<br />";
                    $mensagem .= " <b>Usuário de acesso: </b>".$this->input->post('email_usuario')."<br />";
                    $mensagem .= " <b>Senha de acesso*: </b>".$this->input->post('senha_usuario')."<br />";
                    $mensagem .= " <hr />";
                    $mensagem .= " <span>*Recomendamos a alteração da sua senha após acessar sua área de cliente.</span>";
                    
                    $assunto = "Bem-vindo ao nosso Escritório Virtual!";
                    
                    
                    envia_email($this->input->post('email_usuario'), $assunto, $mensagem);
                }
                
                
                $this->session->set_flashdata('sucesso', 'Cliente cadastrado com sucesso!');
                redirect('/admin/clientes/');      
               
            }else{
                $this->session->set_flashdata('error', 'Erro ao cadastrar cliente, tente novamente!');
                redirect('/admin/clientes/add');                                               
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
                'tipos_usuario' => $this->clientes->lista_tipos(),  
                'servicos' => $this->servicos->lista_servicos()
            );
            
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/clientes_add_view', $dados);
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    
    public function editar(){
        
        if($this->session->userdata('usuarioLogado') == false){            
            $this->load->view('sysadmin/telas/login_view');
        }else{
            $this->form_validation->set_rules('razao', 'RAZÃO SOCIAL', 'trim|required|max_length[90]|ucwords');
            $this->form_validation->set_rules('cnpjcei', 'CNPJ', 'trim|required');                                                                     
            if($this->form_validation->run() == TRUE){           

                $dados = array(
                    'id_cliente' => $this->input->post('cliente'),
                    'cnpj' => $this->input->post('cnpjcei'),
                    'insc_estadual' => $this->input->post('ie'),
                    'insc_municipal' => $this->input->post('ins_municipal'),
                    'razao_social' => $this->input->post('razao'),
                    'nome_fantasia' => $this->input->post('fantasia'),
                    'endereco' => $this->input->post('endereco'),
                    'bairro' => $this->input->post('bairro'),
                    'cep' => $this->input->post('cep'),
                    'cidade' => $this->input->post('cidade'),
                    'estado' => $this->input->post('estado'),
                    'responsavel' => $this->input->post('responsavel'),
                    'email' => $this->input->post('email'),
                    'telefone' => $this->input->post('telefone'),
                    'celular' => $this->input->post('celular'),
                    'celular_sms' => $this->input->post('celularsms'),
                    'status' => $this->input->post('slc_status'),
                    'id_tipo' => $this->input->post('slc_categoria')                    
                );
               
                $servicos = $this->input->post('servicos');

                if($this->clientes->editar_clientes($dados, $servicos)){
                    
                    // registra ação de logs
                    logs_acao(3, 2, $this->input->post('cliente'), $this->session->userdata('id'), null, null, '<div class="text-info">Atualizou o cadastro do cliente '.$this->input->post('razao').'</div>');
                    
                    $this->session->set_flashdata('sucesso', 'Cliente atualizado com sucesso!');
                    redirect('/admin/clientes/editar/'.$this->input->post('cliente'));      

                }else{
                    $this->session->set_flashdata('error', 'Erro ao atualizar cliente, tente novamente!');
                    redirect('/admin/clientes/editar/'.$this->input->post('cliente'));                                               
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
                    'cliente' => $this->clientes->lista_cliente_id($this->uri->segment(4)),
                    'usuarios' => $this->clientes->lista_usuarios_cliente($this->uri->segment(4)),
                    'tipos_usuario' => $this->clientes->lista_tipos(),
                    'servicos' => $this->servicos->lista_servicos(),
                    'servicos_clientes' => $this->clientes->lista_clientes_servicos()
                );

                $this->load->view('sysadmin/inc/header_html');
                $this->load->view('sysadmin/inc/header', $cabecalho);
                $this->load->view('sysadmin/inc/sidebar', $menu);
                $this->load->view('sysadmin/telas/clientes/editar_view', $dados);
                $this->load->view('sysadmin/inc/footer');
                $this->load->view('sysadmin/inc/footer_html');
            }
        }
    }
    
    // detalhamento do cadastro de clientes
    public function detalhes(){
        
        $cabecalho = array(
                    'config' => $this->configuracoes->lista_configs(1),
                    'mensagens' => $this->chamados->lista_chamados_usuario($this->session->userdata('id')),
                    'tarefas' => $this->tarefas->getTarefasStatusResp($this->session->userdata('id'), 1)
                );

                $menu = array(
                    'menu' => $this->usuarios->nivel_acesso($this->session->userdata('id'))                    
                );            

                $cliente = $this->uri->segment(4);
                
                $dados = ['cliente' => $this->clientes->lista_cliente_id($cliente),
                    'arquivos_enviados' => $this->arquivos->lista_arquivos_cliente_limite_ordem($cliente, 'desc'),
                    'arquivos_recebidos' => $this->arquivos->lista_arq_recebidos_cliente_limite_ordem($cliente, 10000, 'desc'),
                    'tipos' => $this->arquivos->lista_tipos_servicos_clientes($cliente),
                    'usuarios' => $this->clientes->lista_usuarios_cliente($cliente),
                    'tipos_usuario' => $this->clientes->lista_tipos(),
                    'servicos' => $this->servicos->lista_servicos(),
                    'servicos_clientes' => $this->clientes->lista_servico_cliente($cliente),
                    'chamados' => $this->chamados->lista_todoschamados_cliente($cliente, 10000),
                    'protocolos' => $this->protocolos->lista_logs_cliente($cliente),
                    'funcionarios' => $this->usuarios->lista_usuarios(),
                    'tipo_arquivo' => $this->clientes->lista_tipoArquivo_cliente($cliente)
                    ];

                $this->load->view('sysadmin/inc/header_html');
                $this->load->view('sysadmin/inc/header', $cabecalho);
                $this->load->view('sysadmin/inc/sidebar', $menu);
                $this->load->view('sysadmin/telas/clientes/detalhes_view', $dados);
                $this->load->view('sysadmin/inc/footer');
                $this->load->view('sysadmin/inc/footer_html'); 
        
    }
    
    // excluir cliente
    public function excluir(){
        
        // resgata o nome do cliente para registro do log
        $cliente = $this->clientes->lista_cliente_id($this->input->post('cliente'));
        $nome = $cliente->razao_social;
        
        if($this->clientes->excluir_cliente($this->input->post('cliente'))){
            // registra ação de logs
            logs_acao(3, 2, $this->input->post('cliente'), $this->session->userdata('id'), null, null, '<div class="text-danger">Deletou o cadastro do cliente '.$nome.'</div>');
                    
            $this->session->set_flashdata('sucesso', 'Registro deletado com sucesso!');
            redirect('/admin/clientes/');
        }else{
            // registra ação de logs
            logs_acao(3, 2, $this->input->post('cliente'), $this->session->userdata('id'), null, null, '<div class="text-warning">Tentou deletar o cadastro do cliente '.$nome.', porém o sistema não permitiu por haver informações vinculadas ao mesmo.</div>');
            
            $this->session->set_flashdata('error', 'Erro ao atualizar registro, tente novamente.!');
            redirect('/admin/clientes/');
        }
    }
    
/*******************************************************************************
 * USUÁRIOS DE CLIENTES
 ******************************************************************************/    
    public function add_usuario(){
        
        $dados = array(
            'id_clientes' => $this->input->post('cliente'),
            'tipo_usuario' => $this->input->post('tipo'),
            'nome' => $this->input->post('nome'),
            'email' => $this->input->post('email'),
            'senha' => md5($this->input->post('senha')),
            'status' => $this->input->post('status'),
            'data_cadastro' => date('Y-m-d H:i:s')
        );
        
        $permissao = $this->input->post('tipo_arquivo');
        
        // resgata o nome do cliente para registro do log
        $cliente = $this->clientes->lista_cliente_id($this->input->post('cliente'));
        $nome = $cliente->razao_social;
        
        if($this->clientes->add_usuario($dados, $permissao)){
                                   
            // registra ação de logs
            logs_acao(3, 2, $this->input->post('cliente'), $this->session->userdata('id'), null, null, '<div class="text-success">Adicionou um novo usuário ao cliente '.$nome.'</div>');
            
            if($this->input->post('rota') == 1){
                $this->session->set_flashdata('sucesso', 'Usuário adicionado com sucesso!');
                redirect('/admin/clientes/detalhes/'.$this->input->post('cliente'));
            }else{
                $this->session->set_flashdata('sucesso', 'Usuário adicionado com sucesso!');
                redirect('/admin/clientes/editar/'.$this->input->post('cliente').'/funcionarios');
            }
            
        }else{
            
            // registra ação de logs
            //logs_acao(3, 2, $this->session->userdata('id'), null, null, '<div class="text-warning">Houve um erro ao adicionar novo usuário ao cliente '.$nome.'</div>');            
            
            if($this->input->post('rota') == 1){
                $this->session->set_flashdata('sucesso', 'Usuário adicionado com sucesso!');
                redirect('/admin/clientes/detalhes/'.$this->input->post('cliente'));
            }else{
                $this->session->set_flashdata('sucesso', 'Usuário adicionado com sucesso!');
                redirect('/admin/clientes/editar/'.$this->input->post('cliente').'/funcionarios');
            }
        }
        
    }
    
    // carrega modal de atualização do usuário
    public function modal_usuario(){
        $cliente = $this->clientes->lista_cliente_usuario_id($this->input->post('usuario'));
        $dados = array(
                    'user' => $this->clientes->lista_cliente_usuario_id($this->input->post('usuario')),
                    'tipo_arquivo' => $this->clientes->lista_tipoArquivo_cliente($cliente->id_clientes),
                    'tipo_arquivo_per' => $this->clientes->lista_tipoArquivo_usuario($this->input->post('usuario'))
                );
        
        $this->load->view('/sysadmin/telas/clientes_modal_editausuario_view', $dados);
    } 
    
    
    // editar dados de usuário
    public function edita_usuario(){
        
        if($this->input->post('senha_usuario') != ""){
            $dados = array(
                'id_usuario' => $this->input->post('usuario'),
                'tipo' => $this->input->post('tipo_usuario'),
                'nome' => $this->input->post('nome_usuario'),
                'email' => $this->input->post('email_usuario'),
                'senha' => md5($this->input->post('senha_usuario')),
                'status' => $this->input->post('status_usuario')
            );
        }else{
            $dados = array(
                'id_usuario' => $this->input->post('usuario'),
                'tipo' => $this->input->post('tipo_usuario'),
                'nome' => $this->input->post('nome_usuario'),
                'email' => $this->input->post('email_usuario'),          
                'status' => $this->input->post('status_usuario')
            );
        }
        
        $permissao = $this->input->post('tipo_arquivo');
        
        if($this->clientes->editar_usuario($dados, $permissao)){
            $cliente = $this->clientes->lista_cliente_usuario_id($this->input->post('usuario'));
            // registra ação de logs
            logs_acao(3, 2, $cliente->id_clientes, $this->session->userdata('id'), null, null, '<div class="text-info">Atualizou o cadastro do usuário '.$this->input->post('nome_usuario').'.</div>');
            
            $this->session->set_flashdata('sucesso', 'Registro atualizado com sucesso!');
            redirect('/admin/clientes/detalhes/'.$this->input->post('cliente'));
        }else{
            // registra ação de logs
            //logs_acao(3, 2, null, $this->session->userdata('id'), null, null, '<div class="text-warning">Erro ao atualizar o cadastro do usuário '.$this->input->post('nome_usuario').'.</div>');
            
            $this->session->set_flashdata('error', 'Erro ao atualizar registro, tente novamente.!');
            redirect('/admin/clientes/detalhes/'.$this->input->post('cliente'));
        }
        
    }
    
    public function excluir_usuario(){
        
        // resgata o nome do cliente para registro do log
        $cliente = $this->clientes->lista_cliente_usuario_id($this->input->post('usuario'));
        $nome = $cliente->nome;
        
        $dados = array(
            'id_usuario' => $this->input->post('usuario'),
            'status' => 2            
        );
        
        if($this->clientes->editar_usuario($dados)){
            
            // registra ação de logs
            logs_acao(3, 2, $cliente->id_clientes, $this->session->userdata('id'), null, null, '<div class="text-danger">Deletou o cadastro do usuário '.$nome.'.</div>');
            
            $this->session->set_flashdata('sucesso', 'Registro deletado com sucesso!');
            if($this->input->post('rota') == 1){
                redirect('/admin/clientes/detalhes/'.$this->input->post('cliente'));
            }else{
                redirect('/admin/clientes/editar/'.$this->input->post('cliente').'/funcionarios');
            }
            
        }else{
            
            // registra ação de logs
            //logs_acao(3, 2, $this->session->userdata('id'), null, null, '<div class="text-warning">Erro ao deletar o cadastro do usuário '.$nome.'.</div>');
            
            $this->session->set_flashdata('error', 'Erro ao atualizar registro, tente novamente.!');
            redirect('/admin/clientes/editar/'.$this->input->post('cliente').'/funcionarios');
        }
    }
/*******************************************************************************
 * TIPOS DE CLIENTES
 ******************************************************************************/    
    public function tipos_clientes(){
        $dados = array('tipos' => $this->clientes->lista_tipos());
        
        $this->load->view('sysadmin/telas/clientes_tipos_view', $dados);
    }
    
    public function addtipo(){
        $dados = array('nome_tipo'=>$this->input->post('tipo'));
        
        if($this->clientes->add_tipo($dados)){
            
            // registra ação de logs
            logs_acao(3, 2, null, $this->session->userdata('id'), null, null, '<div class="text-success">Cadastrou o tipo de cliente '.$this->input->post('tipo').'</div>');
            
            echo '<div class="alert alert-success">Tipo de cliente cadastrado com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger">Erro ao cadastrar tipo de cliente, tente novamente.</div>';
        }
    }
    
    public function edita_tipo(){
        
        $dados = array(
            'id_tipo' => $this->input->post('id_tipo'),
            'nome_tipo' => $this->input->post('tipo')
        );
        
        if($this->clientes->atualiza_tipo($dados)){
            
            // registra ação de logs
            logs_acao(3, 2, null, $this->session->userdata('id'), null, null, '<div class="text-info">Atualizou o tipo de cliente '.$this->input->post('tipo').'</div>');
            
            echo '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Dados atualizados com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Erro ao atualizar dados.</div>';
        }
        
    }
    
    
    public function excluir_tipo(){
        
        $tipo = $this->clientes->lista_tipo_id($this->input->post('id_tipo'));
        $nome = $tipo->nome;
        
        if($this->clientes->del_tipo($this->input->post('id_tipo'))){
            
            // registra ação de logs
            logs_acao(3, 2, null, $this->session->userdata('id'), null, null, '<div class="text-danger">Deletou o tipo de cliente '.$nome.'</div>');
            
            echo '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Dados atualizados com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Erro ao atualizar dados.</div>';
        }
    } 
     
}