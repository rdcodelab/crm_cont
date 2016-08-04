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
        if($this->session->userdata('clienteLogado') == false){
            
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
                'usuarios' => $this->clientes->lista_usuarios_cliente($this->session->userdata('id_cliente'))
            );
            
            $this->load->view('sys/inc/header_html');
            $this->load->view('sys/inc/header', $cabecalho);
            $this->load->view('sys/inc/sidebar', $menu);
            $this->load->view('sys/telas/relatorios_view', $dados);
            $this->load->view('sys/inc/footer');
            $this->load->view('sys/inc/footer_html');
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
        
        $logs = $this->protocolos->lista_logs_cliente($this->session->userdata('id_cliente'));
        
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
                
        $logs = $this->protocolos->lista_logs_cliente($this->session->userdata('id_cliente'), $d_inicial, $d_final);
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
                                $tipo = 'Documentos';
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
                        // seleciona nome do usuario com sua respectiva empresa
                        $usuario = '';
                        foreach($clientes as $cli):
                            if($cli->idclientes_usuarios == $ln->id_usuario):
                                $usuario = $cli->nome." (".$cli->razao_social.")";
                            endif;
                        endforeach;
                                                
                        $data1   = substr($ln->data_registro, 0, 10);
                        $data_br = implode("/", array_reverse(explode("-", $data1)));

                        // formata hora
                        $hora1 = substr($ln->data_registro, 11, 8);
                        $dataRegistro = $data_br.' '. $hora1;
                        
                        $lista .= '{
                                "datareg": "'.$dataRegistro.'",                                
                                "usuario": "'.$usuario.'",
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
        $usuario = $_GET['usuario'];
        $sessao = $_GET['sessao'];                
        
        $logs = $this->protocolos->lista_logs_filtros(null, $p_inicial, $p_final, null, $usuario, $sessao);
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
                                $tipo = 'Documentos';
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
                        // veririfica o tipo de registro
                        // 1= cliente
                        // 2= escritorio
                        if($ln->tipo_usuario == 1){                            
                            $user = $this->clientes->lista_cliente_usuario_id($ln->id_usuario);
                            $cliente = $this->clientes->lista_cliente_id($user->id_clientes);
                            $usuario = $user->nome." (".$cliente->razao_social.")";
                        }else{
                            $user = $this->usuarios->lista_usuario_id($ln->id_usuario);
                            $usuario = $user->nome." (Escritório)";
                        }
                        
                        $data1   = substr($ln->data_registro, 0, 10);
                        $data_br = implode("/", array_reverse(explode("-", $data1)));

                        // formata hora
                        $hora1 = substr($ln->data_registro, 11, 8);
                        $dataRegistro = $data_br.' '. $hora1;
                        
                        $lista .= '{
                                "datareg": "'.$dataRegistro.'",                                
                                "usuario": "'.$usuario.'",
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
    
    
}
