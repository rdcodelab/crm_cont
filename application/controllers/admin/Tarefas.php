<?php
class Tarefas extends CI_Controller{
    
    public function __construct(){
        parent::__construct();     
                
        $this->load->model('Clientes_model', 'clientes');
        $this->load->model('Servicos_model', 'servicos');
        $this->load->model('Configuracoes_model', 'configuracoes');
        $this->load->model('Usuarios_model', 'usuarios');       
        $this->load->model('Chamados_model', 'chamados');       
        $this->load->model('Tarefas_model', 'tarefas');
        
        // controle de nível de acesso        
        $this->load->helper('Permissoes');
        controle_acesso($this->session->userdata('id'), 'tarefas');
        
        
        //$this->load->library('MY_Upload');
    }
    
    public function index(){
        
        if($this->session->userdata('usuarioLogado') == false){            
            $this->load->view('sysadmin/telas/login_view');
        }else{    
            $cabecalho = array(
                'config' => $this->configuracoes->lista_configs(1),
                'mensagens' => $this->chamados->lista_chamados_usuario($this->session->userdata('id')),
                'tarefas' => $this->tarefas->getTarefasStatusResp($this->session->userdata('id'), 1),                                
            );
            
            $menu = array(
                'menu' => $this->usuarios->nivel_acesso($this->session->userdata('id'))
            );
            
            $dados = array(
                'usuarios' => $this->usuarios->lista_usuarios(),
                'tipos' => $this->tarefas->lista_tipos(),
                'clientes' => $this->clientes->lista_clientes(),                
                'servicos' => $this->servicos->getServicosDemandas($this->session->userdata('servico'))
            );
                      
            
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/tarefas/index', $dados); 
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
        
    }
    
    // lista tarefas de acordo com o status selecionado
    public function tarefas_status(){
        $dados = array(
            'tarefas' => $this->tarefas->getTarefasSetorStatus($this->session->userdata('id'), $this->input->post('setor'), $this->input->post('status')),
            'clientes' => $this->clientes->lista_clientes(), 
            'tipos' => $this->tarefas->lista_tipos()            
        );
        
        $this->load->view('sysadmin/telas/tarefas/lista_view', $dados);
    }
        
    
    // lista todas as tarefas
    public function lista_tarefas(){
        $dados = array(
            'tarefas' => $this->tarefas->getTarefasSetor($this->session->userdata('id'), $this->input->post('setor')),
            'clientes' => $this->clientes->lista_clientes(),
            'tipos' => $this->tarefas->lista_tipos(),
            'setor' => $this->input->post('setor')
        );
        
        $this->load->view('sysadmin/telas/tarefas/lista_view', $dados);
    }
    
    // lista tarefas filtradas
    public function filtratarefa(){
        
        $filtros = array(
            'responsavel' => $this->input->post('f_responsavel'),
            'setor' => $this->input->post('setor'),
            'data_inicial' => implode("-", array_reverse(explode("/", $this->input->post('p_inicial')))),
            'data_final' => implode("-", array_reverse(explode("/", $this->input->post('p_final'))))
        );        
        
        $dados = array(            
            'clientes' => $this->clientes->lista_clientes(),
            'tipos' => $this->tarefas->lista_tipos(),
            'setor' => $this->input->post('setor'),
            'tarefas' => $this->tarefas->getFiltraTarefa($filtros)
        );
        
        $this->load->view('sysadmin/telas/tarefas_lista_view', $dados);
        
    }
            
    // abre tarefa
    public function abre_tarefa(){
        $dados = array(
            'tarefa' => $this->tarefas->lista_tarefa_id($this->input->post('tarefa')),
            'tarefas_msg' => $this->tarefas->lista_msg_tarefa($this->input->post('tarefa')),
            'clientes' => $this->clientes->lista_clientes(),
            'tipos' => $this->tarefas->lista_tipos(),
            'usuarios' => $this->usuarios->lista_usuarios(),
            'logs' => $this->tarefas->lista_logs($this->input->post('tarefa'))
        );
        
        // registra log de visualização
        $tarefa = $this->tarefas->lista_tarefa_id($this->input->post('tarefa'));
        $categoria = $this->tarefas->lista_tipo_id($tarefa->tarefas_categorias_id_categoria);
        
        logs_acao(8, 2, $tarefa->id_cliente, $this->session->userdata('id'), $categoria->id_servico, null, '<div class="text-info">Visualizou a tarefa #'.$this->input->post('tarefa').' - '.$tarefa->titulo.'</div>');       
        $this->load->view('sysadmin/telas/tarefas/abertura_view', $dados);
    }
    
    public function add(){
        
        $this->form_validation->set_rules('titulo', 'TÍTULO DA TAREFA', 'trim|required');                

        if($this->form_validation->run() == TRUE){           
            
            $d_inicio = dataUS($this->input->post('data_inicial'));
            $d_final = dataUS($this->input->post('data_final'));
            $dados = array(
                'tarefas_categorias_id_categoria' => $this->input->post('tipo'),
                'usuarios_autor' => $this->session->userdata('id'),
                'usuarios_responsaveis' => $this->input->post('responsavel'),
                'id_cliente' => $this->input->post('cliente'),
                'titulo' => $this->input->post('titulo'),
                'descricao' => $this->input->post('descricao'), 
                'data_inicio' => $d_inicio,
                'data_validade' => $d_final,
                'status' => 0
            );
            
            if($this->tarefas->add_tarefa($dados)){
                                                
                $categoria = $this->tarefas->lista_tipo_id($this->input->post('tipo'));
                logs_acao(8, 2,$this->input->post('cliente'), $this->session->userdata('id'), $categoria->id_servico, null, '<div class="text-success">Criou a tarefa - '.$this->input->post('titulo').'</div>');       
                
                $this->session->set_flashdata('sucesso', 'Tarefa criada com sucesso.');
                redirect('/admin/tarefas');
                
            }else{
                $this->session->set_flashdata('erro', 'Erro ao adicionar tarefa, tente novamente.');
                redirect('/admin/tarefas');
            }
            
        }else{            
            $this->session->set_flashdata('erro', 'Erro ao adicionar tarefa, tente novamente.');
            redirect('/admin/tarefas');
        }                
    }
    
    public function excluir_tarefa(){
        $tarefa = $this->tarefas->lista_tarefa_id($this->uri->segment(4));
        $categoria = $this->tarefas->lista_tipo_id($tarefa->tarefas_categorias_id_categoria);
        $titulo = $tarefa->titulo;
        if($this->tarefas->excluir_tarefa($this->uri->segment(4))){
            logs_acao(8, 2, $tarefa->id_cliente, $this->session->userdata('id'), $categoria->id_servico, null, '<div class="text-success">Deletou a tarefa - #'.$this->uri->segment(4).' - '.$titulo.'</div>');       
            echo '<div class="alert alert-success">Tarefa excluída com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger">Erro ao execluir tarefa, atualize sua página e tente novamente.</div>';
        }
        
    }
    
    // starta tarefa
    public function status_tarefa(){
        
        $id_tarefa = $this->input->post('tarefa');
        $status_tarefa = $this->input->post('status');
                
        $dados = array(
            'id_tarefa' => $id_tarefa,
            'status_tarefa' => $status_tarefa,            
        );
        
        
        
        if($this->tarefas->editar($dados) && $this->tarefas->add_log_tarefa($dados)){
            // resgata dados da tarefa
            $tarefa = $this->tarefas->lista_tarefa_id($this->uri->segment(4));
            $categoria = $this->tarefas->lista_tipo_id($tarefa->tarefas_categorias_id_categoria);
            logs_acao(8, 2, $tarefa->id_cliente, $this->session->userdata('id'), $categoria->id_servico, null, '<div class="text-info">Alterou o status da tarefa - #'.$this->uri->segment(4).' - '.$tarefa->titulo.'</div>');       
            // monta botão de status de acordo com o status da tarefa
            // 0 = mostra botão de iniciar tarefa
            // 1 = Mostra botão de pausar tarefa
            // 2 = Mostra o botão de avaliando
            // 3 = Mostra botão de entregue e desabilita demais
            // 4 = Mostra botão de tarefa pausada
            switch ($tarefa->status){
                case 0:
                    $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$id_tarefa.', 1)" title="Trabalhar Tarefa" id="btnAcao" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-play"></i> Trabalhar</a>';
                break;
                case 1:
                    $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$id_tarefa.', 4)" title="Pausar Tarefa" id="btnAcao" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-pause"></i> Pausar</a>';
                break;
                case 2:
                    $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$id_tarefa.', 1)" title="Avaliando Tarefa" id="btnAcao" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-unchecked"></i> Avaliando</a>';
                break;
                case 3:
                    $btnStatus = '<a href="javascript:void()" title="Tarefa entregue" id="btnAcao" class="btn btn-sm btn-success" disabled><i class="glyphicon glyphicon-check"></i> Entregue</a>';
                break;
                case 4:
                    $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$id_tarefa.', 1)" title="Re-iniciar tarefa" id="btnAcao" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-play"></i> Trabalhar</a>';
                break;
            }
            echo $btnStatus;
            
        }else{
            
            // monta botão de status de acordo com o status da tarefa
            // 0 = mostra botão de iniciar tarefa
            // 1 = Mostra botão de pausar tarefa
            // 2 = Mostra o botão de avaliando
            // 3 = Mostra botão de entregue e desabilita demais
            // 4 = Mostra botão de tarefa pausada
            switch ($status_tarefa){
                case 0:
                    $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$id_tarefa.', 1)" title="Trabalhar Tarefa" id="btnAcao" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-play"></i> Trabalhar</a>';
                break;
                case 1:
                    $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$id_tarefa.', 4)" title="Pausar Tarefa" id="btnAcao" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-pause"></i> Pausar</a>';
                break;
                case 2:
                    $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$id_tarefa.', 1)" title="Avaliando Tarefa" id="btnAcao" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-unchecked"></i> Avaliando</a>';
                break;
                case 3:
                    $btnStatus = '<a href="javascript:void()" title="Tarefa entregue" id="btnAcao" class="btn btn-sm btn-success" disabled><i class="glyphicon glyphicon-check"></i> Entregue</a>';
                break;
                case 4:
                    $btnStatus = '<a href="javascript:void()" onclick="mudaBtnTarefa('.$id_tarefa.', 1)" title="Re-iniciar tarefa" id="btnAcao" class="btn btn-sm btn-info"><i class="glyphicon glyphicon-play"></i> Trabalhar</a>';
                break;
            }
            
            echo $btnStatus;
            
        }
        
    }
    
    // atualiza progresso da tarefa
    public function atualizaprogresso(){
        $dados = array(
            'id_tarefa' => $this->input->post('tarefa'),
            'progresso' => $this->input->post('progresso')
        );
        
        if($this->tarefas->editar($dados)){
            // resgata dados da tarefa
            $tarefa = $this->tarefas->lista_tarefa_id($this->input->post('tarefa'));
            $categoria = $this->tarefas->lista_tipo_id($tarefa->tarefas_categorias_id_categoria);
            logs_acao(8, 2, $tarefa->id_cliente, $this->session->userdata('id'), $categoria->id_servico, null, '<div class="text-info">Atualizou o progresso da tarefa - #'.$this->input->post('tarefa').' - '.$tarefa->titulo.' em '.$this->input->post('progresso').'%</div>');       
            echo '<div class="alert alert-success">Progresso da tarefa salvo com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger">Erro ao salvar progresso, atualize sua página e tente novamente.</div>';
        }
    }
    
    
    // Assume solicitação de serviço
    public function assumeSolicitacao(){
        $dados = array(
            'id_tarefa' => $this->input->post('tarefa'),
            'responsavel' => $this->input->post('usuario'),
            'data_validade' => implode("-", array_reverse(explode("/", $this->input->post('dataEntrega')))),
            'status_tarefa' => 1
        );
        
        $dados2 = array(
            'id_tarefa' => $this->input->post('tarefa'),            
            'status_tarefa' => 1
        );
        
        if($this->tarefas->editar($dados)  && $this->tarefas->add_log_tarefa($dados2)){                                    
            // resgata dados da tarefa
            $tarefa = $this->tarefas->lista_tarefa_id($this->uri->segment(4));
            $categoria = $this->tarefas->lista_tipo_id($tarefa->tarefas_categorias_id_categoria);
            logs_acao(8, 2, $tarefa->id_cliente, $this->session->userdata('id'), $categoria->id_servico, null, '<div class="text-info">Assumiu a tarefa - #'.$this->uri->segment(4).' - '.$tarefa->titulo.'</div>');       
            $this->session->set_flashdata('sucesso', 'Solicitação atribuída com sucesso!');
            redirect('admin/tarefas');
        }else{
            $this->session->set_flashdata('error', 'Erro ao atribuir solicitação, tente novamente!');
            redirect('admin/tarefas');
        }
    }
    
    public function tarefasHome(){
        
        // filtra apenas por responsável
        if($this->uri->segment(4) == ""){
            $filtros = array(
                'responsavel' => $this->session->userdata('id')                
            );        
        // filtra por responsável e status    
        }else{
            $filtros = array(
                'responsavel' => $this->session->userdata('id'),
                'status' => $this->uri->segment(4)
            );        
        }                
        
        $dados = array(            
            'clientes' => $this->clientes->lista_clientes(),
            'tipos' => $this->tarefas->lista_tipos(),
            'setor' => 0,
            'tarefas' => $this->tarefas->getFiltraTarefa($filtros, 0)
        );
        
        $this->load->view('sysadmin/telas/tarefas/lista_view', $dados);
    }
    
/*******************************************************************************
 * MENSAGENS DE TAREFAS
 ******************************************************************************/    
    public function addmensagem(){
        $dados = array(
            'id_tarefa' => $this->input->post('tarefa'),
            'origem_mensagem' => $this->input->post('origem'),
            'id_autor' => $this->input->post('usuario'),
            'mensagem' => $this->input->post('mensagem'),
            'status_leitura' => 0
        );
        
        if($this->tarefas->add_msg($dados)){
            $tarefa = $this->tarefas->lista_tarefa_id($this->input->post('tarefa'));
            $categoria = $this->tarefas->lista_tipo_id($tarefa->tarefas_categorias_id_categoria);
            logs_acao(8, 2, $tarefa->id_cliente, $this->session->userdata('id'), $categoria->id_servico, null, '<div class="text-info">Enviou uma mensagem na tarefa - #'.$this->input->post('tarefa').' - '.$tarefa->titulo.' - segue mensagem: <br /> '.$this->input->post('mensagem').'</div>');       
            
            // registra notificação
            if($this->input->post('origem') == 1){
                notificacoes($tarefa->id_cliente, $this->input->post('usuario'), 8, $categoria->id_servico, 'Enviou uma mensagem na tarefa - #'.$this->input->post('tarefa').' - '.$tarefa->titulo);
            }
            echo '<div class="alert alert-success">Mensagem enviada com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger">Erro ao enviar mensagem, atualize sua página e tente novamente.</div>';
        }
    }
    
    public function listamensagens(){
        $msgs = $this->tarefas->lista_msg_tarefa($this->input->post('tarefa'));        
        if(count($msgs) == 0){
            echo '<pre>Esta tarefa não possui mensagem vinculada.</pre>';
        }else{
            echo '<table class="table table-striped table-hover">'; 
                echo '<tr>';
                    echo '<th style="width: 15%">Data</th>';
                    echo '<th style="width: 15%">Responsável</th>';
                    echo '<th style="width: 65%">Evento</th>';
                echo '</tr>';
                foreach ($msgs as $msg):
                    echo '<tr>';        
                    // verifica a origem da mensagem
                    switch($msg->origem_mensagem){
                        case 1:
                            $classOrigem = 'box-mensagem-cliente';                        
                            // resgata nome do usuário
                            $us = $this->clientes->lista_cliente_usuario_id($msg->id_autor);
                            $n_usuario = $us->nome;
                        break;
                        case 2:
                            $classOrigem = 'box-mensagem-funcionario';
                            // resgata nome do usuario que enviou a mensagem
                            $us = $this->usuarios->lista_usuario_id($msg->id_autor);
                            $n_usuario = $us->nome;
                        break;
                    }                                        
                    
                    echo '<td>';
                        dataHora_BR($msg->data_cadastro);
                    echo '</td>';
                    echo '<td>'.$n_usuario.'</td>';
                    echo '<td>'.$msg->mensagem.'</td>';                                                            
                    echo '</tr>';
                endforeach;            
            echo '</table>';
        }        
    }
/*******************************************************************************
 * TIPOS DE TAREFAS POR SETORES (SERVIÇOS)
 ******************************************************************************/    
    public function tipos_tarefas(){
        $dados = array('tipos' => $this->tarefas->lista_tipos());
        
        $this->load->view('sysadmin/telas/tarefas_tipos_view', $dados);
    }
    
    public function tarefas_clientes(){ 
        
        $dados = $this->tarefas->getTipoTarefaCliente($this->input->post('cliente'), null);
        
        if(count($dados) == 0){
            echo '<div class="alert alert-warning">Nã há tipos de arquivos vinculados a este cliente.</div>';
        }else{
            //echo '<label>Serviços</label>';
            echo '<select name="tipo" class="form-control servicos_cliente">';
            echo '<option value="">Selecione um tipo</option>';
            foreach($dados as $ln):
                echo '<option value="'.$ln->id_categoria.'">'.$ln->categoria.'</option>';
            endforeach;
            echo '</select>';
        }
    }
    
    // lista tarefa por id
    public function getTipoTarefaId($id){
        $this->db->where('id_categoria', $id);
        return $this->db->get('tarefas_categorias')->row(0);
    }
    
    public function addtipo(){
        $dados = array('id_servico' => $this->input->post('servico'), 'nome'=>$this->input->post('tipo'));
        
        if($this->tarefas->add_tipo($dados)){
            
            // registra ação de logs
            logs_acao(8, 2, null, $this->session->userdata('id'), $this->input->post('servico'), null, '<div class="text-success">Cadastrou o tipo de tarefa '.$this->input->post('tipo').'</div>');
            
            echo '<div class="alert alert-success">Tipo de tarefa cadastrado com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger">Erro ao cadastrar tipo de tarefa, tente novamente.</div>';
        }
    }
    
    public function edita_tipo(){
        
        $dados = array(
            'id_categoria' => $this->input->post('id_tipo'),
            'id_servico' => $this->input->post('servico'),
            'nome' => $this->input->post('tipo')
        );
        
        if($this->tarefas->atualiza_tipo($dados)){
            
            // registra ação de logs
            logs_acao(8, 2, null, $this->session->userdata('id'), $this->input->post('servico'), null, '<div class="text-info">Atualizou o tipo de tarefa '.$this->input->post('tipo').'</div>');
            
            echo '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Dados atualizados com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Erro ao atualizar dados.</div>';
        }
        
    }
    
    public function lista_setor_usuario(){
        $tipos = $this->tarefas->getTipoUsuario($this->input->post('usuario'));
        
        if(count($tipos) > 0){
            echo '<select name="tipo" class="form-control">';
            foreach($tipos as $tp){               
                echo '<option value="'.$tp->id_categoria.'">'.$tp->nome.'</option>';
            }
            echo '</select>';
        }else{
            $tipos = $this->tarefas->lista_tipos();
            echo '<select name="servico" class="form-control">';
            foreach($tipos as $tp){               
                echo '<option value="'.$tp->id_categoria.'">'.$tp->nome.'</option>';
            }
            echo '</select>';
        }
    }
    
    
    public function excluir_tipo(){
        
        $tipo = $this->tarefas->lista_tipo_id($this->input->post('id_tipo'));
        $nome = $tipo->nome;
        $servico = $tipo->id_servico;
        
        if($this->tarefas->del_tipo($this->input->post('id_tipo'))){
            
            // registra ação de logs
            logs_acao(8, 2, null, $this->session->userdata('id'), $servico, null, '<div class="text-danger">Deletou o tipo de tarefa '.$nome.'</div>');
            
            echo '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Dados atualizados com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Erro ao atualizar dados.</div>';
        }
    }    
    
    
}