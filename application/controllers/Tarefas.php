<?php
class Tarefas extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Servicos_model', 'servicos');
        $this->load->model('Configuracoes_model', 'configuracoes');        
        $this->load->model('Clientes_model', 'clientes');
        $this->load->model('Arquivos_model', 'arquivos');
        $this->load->model('Chamados_model', 'chamados');
        $this->load->model('Tarefas_model', 'tarefas');
        $this->load->model('Usuarios_model', 'usuarios');
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
                'usuarios' => $this->usuarios->lista_usuarios(),
                'tipos' => $this->tarefas->getTipoTarefaCliente($this->session->userdata('id_cliente'))
            );
            
            $this->load->view('sys/inc/header_html');
            $this->load->view('sys/inc/header', $cabecalho);
            $this->load->view('sys/inc/sidebar');
            $this->load->view('sys/telas/tarefas_view', $dados);
            $this->load->view('sys/inc/footer');
            $this->load->view('sys/inc/footer_html');
        }
    }    
    
    public function listatarefas(){
        $tarefas = $this->tarefas->getTarefasClientes($this->session->userdata('id_cliente'));
        
        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/json");
        
        echo '{              
                "data": [';
                    $lista = "";
                    foreach($tarefas as $ln):
                        
                        $tipo = $this->tarefas->lista_tipo_id($ln->tarefas_categorias_id_categoria);
                        $setor = $this->servicos->lista_servico_id($tipo->id_servico);
                        
                        if($ln->status == 3){
                            $status = '<label class=\"label label-success\">Realizado</label>';
                        }else{
                            $status = '<label class=\"label label-info\">Em andamento</label>';
                        }
                    
                        $lista .= '{
                                "cod": "'.$ln->id_tarefa.'",                                
                                "titulo": "<a href=\"javascript:void()\" title=\"Abrir tarefa\" onclick=\"abre_tarefa('.$ln->id_tarefa.')\">'.$ln->titulo.'</a>",                                
                                "setor": "'.$setor->nome.'",
                                "tipo": "'.$tipo->nome.'",
                                "data": "'.implode("/", array_reverse(explode("-", $ln->data_inicio))).'",
                                "status": "'.$status.'",
                                "progresso": "<div class=\"progress\"><div class=\"progress-bar\" role=\"progressbar\" aria-valuenow=\"'.$ln->progresso.'\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: '.$ln->progresso.'%;\">'.$ln->progresso.'%</div></div>"
                              },';
                    endforeach;
                    
                    echo substr($lista, 0, -1);
        echo ']

            }'; 
    }
    
    // adiciona tarefa
    public function add(){
        
        $this->form_validation->set_rules('titulo', 'TÍTULO DA TAREFA', 'trim|required');                

        if($this->form_validation->run() == TRUE){           
            
            $d_inicio = dataUS($this->input->post('data_inicial'));
            $d_final = dataUS($this->input->post('data_final'));
            $dados = array(
                'origem_tarefa' => $this->input->post('origem'),
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
                logs_acao(8, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), $categoria->id_servico, null, '<div class="text-success">Abriu a solicitação -  - '.$this->input->post('titulo').'</div>');       
                
                // registra notificação
                notificacoes($this->session->userdata('id_cliente'), $this->session->userdata('id'), 8, $categoria->id_servico, 'Abriu a solicitação - '.$this->input->post('titulo'));
                
                $this->session->set_flashdata('sucesso', 'Tarefa criada com sucesso.');
                redirect('/tarefas');
                
            }else{
                $this->session->set_flashdata('erro', 'Erro ao adicionar tarefa, tente novamente.');
                redirect('/admin/tarefas');
            }
            
        }else{            
            $this->session->set_flashdata('erro', 'Erro ao adicionar tarefa, tente novamente.');
            redirect('/admin/tarefas');
        }                
    }
    
    // abre tarefa
    public function abre_tarefa(){
        $dados = array(
            'tarefa' => $this->tarefas->lista_tarefa_id($this->input->post('tarefa')),
            'clientes' => $this->clientes->lista_clientes(),
            'tipos' => $this->tarefas->lista_tipos(),
            'usuarios' => $this->usuarios->lista_usuarios(),
            'logs' => $this->tarefas->lista_logs($this->input->post('tarefa'))
        );
        
        $this->load->view('sys/telas/tarefas_abertura_view', $dados);
    }
    
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
            logs_acao(8, 1, $tarefa->id_cliente, $this->session->userdata('id'), $categoria->id_servico, null, '<div class="text-info">Enviou uma mensagem na tarefa - #'.$this->input->post('tarefa').' - '.$tarefa->titulo.' - segue mensagem: <br /> '.$this->input->post('mensagem').'</div>');       
            
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
    
    public function lista_setor_usuario(){
        $tipos = $this->tarefas->getTipoUsuario($this->input->post('usuario'));
        
        if(count($tipos) > 0){
            echo '<select name="servico" class="form-control">';
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
    
    
        
}