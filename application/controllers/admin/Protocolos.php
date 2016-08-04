<?php
class Protocolos extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
                
        $this->load->model('Protocolos_model', 'protocolos');
        $this->load->model('Usuarios_model', 'usuarios');
        $this->load->model('Clientes_model', 'clientes');
        $this->load->model('Arquivos_model', 'arquivos');
        $this->load->model('Configuracoes_model', 'configuracoes');
        $this->load->model('Chamados_model', 'chamados');
        $this->load->model('Servicos_model', 'servicos');
        $this->load->model('Tarefas_model', 'tarefas');                
        
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
                'menu' => $this->usuarios->nivel_acesso($this->session->userdata('id')),                
            );
            
            // verifica data dos próximos arquivos a vencer em 5 dias
            $dataExpiracao = SomarData(date('Y-m-d'), 5, 0, 0);
            
            $dados = array(
                'protocolos' => $this->protocolos->lista_logs(),
                'clientes' => $this->clientes->lista_clientes(),
                'modulos' => $this->usuarios->nivel_acesso($this->session->userdata('id')),
                'servicos' => $this->servicos->lista_servicos()
            );
            
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/relatorios_view', $dados);
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    
    public function add_registro(){
        
        $dados = array(
            'tipo_registro' => $this->input->post('tipo_registro'),
            'tipo_usuario' => 2,
            'id_usuario' => $this->session->userdata('id'),
            'id_arquivo' => $this->input->post('arquivo'),
            'acao_protocolo' => $this->input->post('acao'),
            'ip_acesso' => $_SERVER['SERVER_ADDR'],
            'session_id' => session_id()
        );
        
        $this->protocolos->add_registro($dados);                
    }
    
    // lista registro de um cliente
    public function listalogs_cliente(){
        
        $logs = $this->protocolos->lista_logs_cliente($this->uri->segment(4));
        
        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/json");
        
        echo '{              
                "data": [';
                    $lista = "";
                    foreach($logs as $ln):
                        $tipo = '';
                        // nomeia competencia
                        switch ($ln->tipo_registro){
                            case 1:
                                $tipo = 'Arquivos';
                            break;
                            case 2:
                                $tipo = 'Usuários';
                            break;
                            case 3:
                                $tipo = 'Clientes';
                            break;
                            case 4:
                                $tipo = 'Chamados';
                            break;
                            case 5:
                                $tipo = 'Notificações';
                            break;
                            case 6:
                                $tipo = 'Configurações';
                            break;
                            case 7:
                                $tipo = 'Serviços';
                            break;
                        
                        }
                        
                        $data1   = substr($ln->data_registro, 0, 10);
                        $data_br = implode("/", array_reverse(explode("-", $data1)));

                        // formata hora
                        $hora1 = substr($ln->data_registro, 11, 8);
                        $dataRegistro = $data_br.' '. $hora1;
                        
                        $lista .= '{
                                "datareg": "'.$dataRegistro.'",                                
                                "usuario": "'.$ln->nome.'",
                                "sessao": "'.$tipo.'",
                                "acao": "'.addslashes($ln->acao_protocolo).'",
                                "ip": "'.$ln->ip_acesso.'"                               
                              },';
                    endforeach;
                    
                    echo substr($lista, 0, -1);
        echo ']

            }';        
    }
    
    // lista todos os logs
    public function listalogs(){                
        
        $d_inicial = SubData(date('Y-m-d'), 30, 0, 0)." 00:00:00";
        $d_final   = date('Y-m-d')." 23:59:59";
        $tipo = 1;
        $logs = $this->protocolos->lista_logs_filtros($tipo, $d_inicial, $d_final);
        $clientes = $this->clientes->lista_todosusuarios_cliente();
        
        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/json");
        
        echo '{              
                "data": [';
                    $lista = "";
                    foreach($logs as $ln):
                        $tipo = '';
                        // nomeia competencia
                        switch ($ln->tipo_registro){
                            case 1:
                                $tipo = 'Arquivos';
                            break;
                            case 2:
                                $tipo = 'Usuários';
                            break;
                            case 3:
                                $tipo = 'Clientes';
                            break;
                            case 4:
                                $tipo = 'Chamados';
                            break;
                            case 5:
                                $tipo = 'Notificações';
                            break;
                            case 6:
                                $tipo = 'Configurações';
                            break;
                            case 7:
                                $tipo = 'Serviços';
                            break;
                            case 8:
                                $tipo = 'Tarefas';
                            break;
                        
                        }
                        
                        // verifica tipo de relatporio
                        if($ln->tipo_usuario == 1){
                            // seleciona nome do usuario com sua respectiva empresa
                            $cli = $this->clientes->getUsuarioCliente($ln->id_usuario);
                            if($cli->nome == "" || $cli->razao_social == ""){
                                $usuario = "Registro excluído ";
                            }else{
                                $usuario = $cli->nome." (".$cli->razao_social.")";                                        
                            }                            
                        }elseif($ln->tipo_usuario == 2){
                            
                            // caso seja igual a 0(zero) o usuário é o sistema
                            if($ln->id_usuario == 0){
                                $usuario = 'Sistema';
                            }else{
                                $user = $this->usuarios->lista_usuario_id($ln->id_usuario);
                                if($user->nome == ""){
                                    $usuario = "Registro excluído";
                                }else{
                                    $usuario = $user->nome;                                        
                                }
                            }
                                            
                        }else{
                            $usuario = 'Não localizado';                                                        
                        }                        
                                                
                        $data1   = substr($ln->data_registro, 0, 10);
                        $data_br = implode("/", array_reverse(explode("-", $data1)));

                        // formata hora
                        $hora1 = substr($ln->data_registro, 11, 8);
                        $dataRegistro = $data_br.' '. $hora1;
                        
                        // resgata setor
                        if($ln->id_setor == 0){
                            $setor = 'Não registrado';
                        }else{
                            $st = $this->servicos->lista_servico_id($ln->id_setor);
                            $setor = $st->nome;
                        }
                        
                        $lista .= '{
                                "datareg": "'.$dataRegistro.'",                                
                                "usuario": "'.$usuario.'",
                                "setor": "'.$setor.'",
                                "sessao": "'.$tipo.'",
                                "acao": "'.addslashes($ln->acao_protocolo).'",
                                "ip": "'.$ln->ip_acesso.'"                               
                              },';
                    endforeach;
                    
                    echo substr($lista, 0, -1);
        echo ']

            }'; 
    }
    
// relatorios filtrados
    public function listalogs_filtro(){
        /*
        $p_inicial = $this->input->post('periodoInicial');
        $p_final = $this->input->post('periodoFinal');
        $cliente = $this->input->post('cliente');
        $sessao = $this->input->post('sessao');                
        */
        $p_inicial = implode("-", array_reverse(explode("/", $_GET['periodoInicial'])))." 00:00:00";
        $p_final = implode("-", array_reverse(explode("/", $_GET['periodoFinal'])))." 23:59:59";
        $cliente = $_GET['cliente'];
        $sessao = $_GET['sessao'];  
        $tipo = $_GET['tipoRelatorio'];
        $setores = $this->input->get('setores');
        
        $logs = $this->protocolos->lista_logs_filtros($tipo, $p_inicial, $p_final, $cliente, null, $sessao, $setores);
        $clientes = $this->clientes->lista_todosusuarios_cliente();
        
        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/json");
        
        echo '{              
                "data": [';
                    $lista = "";
                    foreach($logs as $ln):
                        $tipo = '';
                        // nomeia competencia
                        switch ($ln->tipo_registro){
                            case 1:
                                $tipo = 'Arquivos';
                            break;
                            case 2:
                                $tipo = 'Usuários';
                            break;
                            case 3:
                                $tipo = 'Clientes';
                            break;
                            case 4:
                                $tipo = 'Chamados';
                            break;
                            case 5:
                                $tipo = 'Notificações';
                            break;
                            case 6:
                                $tipo = 'Configurações';
                            break;
                            case 7:
                                $tipo = 'Serviços';
                            break;
                            case 8:
                                $tipo = 'Tarefas';
                            break;
                        
                        }
                        // verifica tipo de relatporio
                        if($ln->tipo_usuario == 1){
                            // seleciona nome do usuario com sua respectiva empresa
                            $cli = $this->clientes->getUsuarioCliente($ln->id_usuario);
                            $usuario = $cli->nome." (".$cli->razao_social.")";                                                        
                        }elseif($ln->tipo_usuario == 2){
                            // caso seja igual a 0(zero) o usuário é o sistema
                            if($ln->id_usuario == 0){
                                $usuario = 'Sistema';
                            }else{
                                $user = $this->usuarios->lista_usuario_id($ln->id_usuario);
                                $usuario = $user->nome;                                        
                            }
                        }else{
                            $usuario = 'Não localizado';                                                        
                        } 
                        
                        $data1   = substr($ln->data_registro, 0, 10);
                        $data_br = implode("/", array_reverse(explode("-", $data1)));

                        // formata hora
                        $hora1 = substr($ln->data_registro, 11, 8);
                        $dataRegistro = $data_br.' '. $hora1;
                        
                        // resgata setor
                        if($ln->id_setor == 0){
                            $setor = 'Não registrado';
                        }else{
                            $st = $this->servicos->lista_servico_id($ln->id_setor);
                            $setor = $st->nome;
                        }
                        
                        $lista .= '{
                                "datareg": "'.$dataRegistro.'",                                
                                "usuario": "'.$usuario.'",
                                "setor": "'.$setor.'",
                                "sessao": "'.$tipo.'",
                                "acao": "'.addslashes($ln->acao_protocolo).'",
                                "ip": "'.$ln->ip_acesso.'"                               
                              },';
                    endforeach;
                    
                    echo substr($lista, 0, -1);
        echo ']

            }'; 
    }
    
    // lista registro de um cliente
    public function listalogs_usuarios(){
        
        $logs = $this->protocolos->lista_logs_usuarios($this->uri->segment(4));
        
        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/json");
        
        echo '{              
                "data": [';
                    $lista = "";
                    foreach($logs as $ln):
                        $tipo = '';
                        // nomeia competencia
                        switch ($ln->tipo_registro){
                            case 1:
                                $tipo = 'Arquivos';
                            break;
                            case 2:
                                $tipo = 'Usuários';
                            break;
                            case 3:
                                $tipo = 'Clientes';
                            break;
                            case 4:
                                $tipo = 'Chamados';
                            break;
                            case 5:
                                $tipo = 'Notificações';
                            break;
                            case 6:
                                $tipo = 'Configurações';
                            break;
                            case 7:
                                $tipo = 'Serviços';
                            break;
                            case 8:
                                $tipo = 'Tarefas';
                            break;
                        
                        }
                        
                        $data1   = substr($ln->data_registro, 0, 10);
                        $data_br = implode("/", array_reverse(explode("-", $data1)));

                        // formata hora
                        $hora1 = substr($ln->data_registro, 11, 8);
                        $dataRegistro = $data_br.' '. $hora1;
                        
                        $lista .= '{
                                "datareg": "'.$dataRegistro.'",                                                                
                                "sessao": "'.$tipo.'",
                                "acao": "'.addslashes($ln->acao_protocolo).'",
                                "ip": "'.$ln->ip_acesso.'"                               
                              },';
                    endforeach;
                    
                    echo substr($lista, 0, -1);
        echo ']

            }';        
    }
    
/*******************************************************************************
 * NOTIFICAÇÕES
 ******************************************************************************/    
    public function notificacoes(){
        
        $notificacao = $this->protocolos->getNotificacaoUsuario($this->session->userdata('id'));
        
        $this->load->view('sysadmin/telas/notificacoes_view', $notificacao);
        
    }
    
    
}
