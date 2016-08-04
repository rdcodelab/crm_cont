<?php
class Arquivos extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        $this->load->model('Arquivos_model', 'arquivos');
        $this->load->model('Clientes_model', 'clientes');
        $this->load->model('Servicos_model', 'servicos');
        $this->load->model('Configuracoes_model', 'configuracoes');
        $this->load->model('Usuarios_model', 'usuarios');
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
            //'tipos' => $this->arquivos->lista_tipos_servicos_clientes($this->session->userdata('id_cliente')),
            $dados = array(
                'arquivos_novos' => $this->arquivos->lista_arquivos_status(0, 1),
                'arquivos_abertos' => $this->arquivos->lista_arquivos_status(1, 1),
                'arquivos_vencidos' => $this->arquivos->lista_arquivos_status(2, 1),
                'arquivos_excluidos' => $this->arquivos->lista_arquivos_status(3, 1),
                'arquivos_clientes_novos' => $this->arquivos->lista_arquivos_status(0, 2),
                'arquivos_clientes_abertos' => $this->arquivos->lista_arquivos_status(1, 2),
                'arquivos_clientes_vencidos' => $this->arquivos->lista_arquivos_status(2, 2),
                'arquivos_clientes_excluidos' => $this->arquivos->lista_arquivos_status(3, 2),
                'clientes' => $this->clientes->lista_clientes(),
                'usuarios' => $this->usuarios->lista_usuarios(),                
                'tipos' => $this->clientes->lista_tipoArquivo_usuario($this->session->userdata('id')),
                'servicos' => $this->clientes->lista_servico_cliente($this->session->userdata('id_cliente'))
            );
            
            $this->load->view('sys/inc/header_html');
            $this->load->view('sys/inc/header', $cabecalho);
            $this->load->view('sys/inc/sidebar');
            $this->load->view('sys/telas/arquivos_view', $dados);
            $this->load->view('sys/inc/footer');
            $this->load->view('sys/inc/footer_html');
        }
    }
    
    public function docs_enviados(){
        if($this->session->userdata('clienteLogado') == false){            
            $this->load->view('sys/telas/login_view');
        }else{    
            $cabecalho = array(
                'config' => $this->configuracoes->lista_configs(1),
                'mensagens' => $this->chamados->lista_chamados_cliente_usuario($this->session->userdata('id_cliente'), $this->session->userdata('id'), 1)
            );            
            //'tipos' => $this->arquivos->lista_tipos_servicos_clientes($this->session->userdata('id_cliente')),
            $dados = array(
                'arquivos_enviados' => $this->arquivos->lista_arq_recebidos_cliente_limite_ordem($this->session->userdata('id_cliente'), 9999, 'desc'),
                'tipos' => $this->clientes->lista_tipoArquivo_usuario($this->session->userdata('id')),
                'servicos' => $this->clientes->lista_servico_cliente($this->session->userdata('id_cliente'))
            );
            
            $this->load->view('sys/inc/header_html');
            $this->load->view('sys/inc/header', $cabecalho);
            $this->load->view('sys/inc/sidebar');
            $this->load->view('sys/telas/arquivos_enviados_view', $dados);
            $this->load->view('sys/inc/footer');
            $this->load->view('sys/inc/footer_html');
        }
    }
    
    public function lista_arquivos(){
        $dados = array(
            'tipo' => $this->input->post('tipo'),
            'clientes' => $this->clientes->lista_clientes(),
            'clientes_usuarios' => $this->clientes->lista_todos_usuarios(),
            'usuarios' => $this->usuarios->lista_usuarios(),            
            'arquivos_novos' => $this->arquivos->lista_arquivos_tipo_status($this->input->post('tipo'), 0, 1, $this->session->userdata('id_cliente')),
            'arquivos_abertos' => $this->arquivos->lista_arquivos_tipo_status($this->input->post('tipo'), 1, 1, $this->session->userdata('id_cliente')),
            'arquivos_vencidos' => $this->arquivos->lista_arquivos_tipo_status($this->input->post('tipo'), 2, 1, $this->session->userdata('id_cliente')),
            'arquivos_excluidos' => $this->arquivos->lista_arquivos_tipo_status($this->input->post('tipo'), 3, 1, $this->session->userdata('id_cliente'))
        );
         $this->load->view('sys/telas/arquivos_lista_tipo_view', $dados);
        
    }
    
    public function add(){
        // faz upload
        $config['upload_path'] = 'uploads/arquivos/';
        $config['allowed_types'] = 'doc|docx|xls|pdf|gif|jpg|png|jpeg';
        //$config['max_size'] = '2048';
        //$config['max_width'] = '800';
        //$config['max_height'] = '600';
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);

        $this->upload->do_upload();      
        $arquivo_upado = $this->upload->data();
                
        // resgata id do setor
        $setor = $this->arquivos->lista_tipo_id($this->input->post('servico'));
        
        $dados = array(
            'id_usuario' => $this->input->post('usuario'),
            'id_clientes' => $this->input->post('cliente'),
            'id_servicos' => $setor->id_servico,
            'id_tipo' => $this->input->post('servico'),
            'titulo' => $this->input->post('titulo'),
            'arquivo' => $arquivo_upado['file_name'],
            'referencia' => $this->input->post('referencia'),
            'data_vencimento' => $datavcto,            
            'data_exclusao' => $data_exclusao,            
            'status' => 0
        );  
        
        if($this->arquivos->add_arquivos($dados)){
            
            // registra ação de logs
            logs_acao(1, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), $this->input->post('servico'), null, '<div class="text-success">Cadastrou o arquivo '.$this->input->post('titulo').'</div>');
            
            // registro de notificação
            notificacoes($this->session->userdata('id_cliente'), $this->session->userdata('id'), 1, $this->input->post('servico'), 'Cadastrou o arquivo '.$this->input->post('titulo'));
            
            // dispara notificação
            $usuario = $this->usuarios->lista_usuarios();
            
            if(count($usuario) > 0):
                
                $dataEnvio = date('d/m/Y H:i:s');
               
                foreach($usuario as $cli):
                    
                    $mensagem = "";
                    $nome = "";
                    $email = "";
                    
                    $nome = $cli->nome;
                    $email = $cli->email;
                
                    $mensagem .= "Olá ".$nome.",<br />";
                    $mensagem .= "Acaba de ser envido um documento <b>".$this->input->post('titulo')."</b> para o escritório virtual. <br />";
                    $mensagem .= "Acesse sua Área Administrativa, para visaulizar o mesmo, segue abaixo detalhes: <br />";
                    $mensagem .= "<hr />";
                    $mensagem .= "<b>Nome do documento: </b>".$this->input->post('titulo')."<br />";
                    $mensagem .= "<b>Data de Cadastro: </b>".$dataEnvio."<br />";                    
                    $mensagem .= anchor('/admin/arquivos', 'Clique aqui', 'target="_blank"')." para acessar.";
                    $mensagem .= "<hr />";
                                    
                    envia_email($email, 'Novo Documento - '.$this->input->post('titulo'), $mensagem);
                    
                endforeach;
            endif;
            
            
            $this->session->set_flashdata('sucesso', 'Arquivo cadastrado com sucesso.');
            redirect('/arquivos/docs_enviados');
        }else{
            $this->session->set_flashdata('error', 'Erro ao cadastrar arquivo, tente novamente.');
            redirect('/arquivos/docs_enviados');
        }
    }
           
    // envia arquivo para lixeira
    public function lixeira(){
        $dados = array(
            'id_arquivo' => $this->uri->segment(4),
            'status' => 3
        );
        if($this->arquivos->editar_arquivo($dados)){
            
            $arquivo = $this->arquivos->lista_arquivo_id($this->uri->segment(4));
            $nome = $arquivo->titulo;    
            // registra ação de logs
            logs_acao(1, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), $nome->id_servicos, $this->uri->segment(4), '<div class="text-danger">Enviou o arquivo '.$nome.' para lixeira.</div>');
            
            $this->session->set_flashdata('sucesso', 'Arquivo enviado para lixeira com sucesso!');
            redirect('/arquivos/');
        }else{
            $this->session->set_flashdata('error', 'Erro ao enviar arquivo para lixeira, tente novamente!');
            redirect('/arquivos/');
        }
    }
    
    public function excluir(){
        
        $arquivo = $this->arquivos->lista_arquivo_id($this->input->post('id'));
        $nome = $arquivo->titulo;
        
        $arquivo_del = './uploads/arquivos/'.$arquivo->arquivo;

        // deleta arquivo antigo
        @unlink($arquivo_del);
            
            if($this->arquivos->excluir($this->input->post('id'))){
                // registra ação de logs
                logs_acao(1, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), $arquivo->id_servicos, $this->input->post('id'), '<div class="text-info">Deletou o arquivo '.$nome.'.</div>');                 
               
                $this->session->set_flashdata('sucesso', 'Arquivo e documento excluido com sucesso!');
                redirect('/arquivos/docs_enviados');
            }else{
                $this->session->set_flashdata('error', 'Erro ao excluir o arquivo, tente novamente!');
                redirect('/arquivos/docs_enviados');
            }                                                   
    }
    
    // multiplos uploads
    public function upload_arquivos(){        
        
        $documentos = trata_vetorUpload($_FILES['docs']);
        echo '<ul class="listaDocs">';
        $i = 1;
        foreach($documentos as $doc){
            // move para pasta temporária
            if(move_uploaded_file($doc['tmp_name'], './uploads/tmp_docs/'.$doc['name'])){
                echo '<li id="lista_doc_'.$i.'"><div class="nome_doc"><i class="text-success glyphicon glyphicon-ok-sign"></i> '.$doc['name'].'</div> <div class="btn_acao"><a href="javascript:void()" title="Deletar arquivo" onclick="deleta_documento('.$i.')" class="label label-danger"><i class="glyphicon glyphicon-trash"></i></a></div> <input type="hidden" name="documentos[]" value="'.$doc['name'].'" /></li>';                                
                
            }else{
                echo '<li  id="lista_doc_'.$i.'"><i class="text-danger glyphicon glyphicon-remove-sign"></i>'.$doc['name'].' <span class="label label-danger">Erro ao Upar documento</span></li>';
            }
            $i++;
        }
        echo '</ul>';
        
    }
    
    public function envia_lote(){
        $cliente = $this->input->post('cliente');
        $tipo = $this->input->post('servicos');
        $datavcto = implode("-", array_reverse(explode("/", $this->input->post('validade'))));
        $documentos = $this->input->post('documentos');           
        
        // verifica quais os arquivos vencidos
        $dias = $this->configuracoes->lista_configs(1);         

        // Exibe o resultado
        $data_exclusao = SomarData($datavcto, $dias->vcto_arquivos, 0, 0);                        

        // resgata id do setor
        $setor = $this->arquivos->lista_tipo_id($tipo);
        $service =  $setor->id_servico;
        $tipo = $setor->id_tipo;
        foreach($documentos as $doc):
            
            $path_tmp = './uploads/tmp_docs/'.$doc;
            $path = './uploads/arquivos/'.$doc;
            
            if(copy($path_tmp, $path)){
                  
                
                /***************************************************************
                 * INICIO
                **************************************************************/               
                $dados = array(
                    'id_usuario' => $this->session->userdata('id'),
                    'id_clientes' => $cliente,
                    'id_servicos' => $service,
                    'id_tipo' => $tipo,
                    'titulo' => $doc,
                    'arquivo' => $doc,
                    'referencia' => 2,
                    'data_vencimento' => $datavcto,            
                    'data_exclusao' => $data_exclusao,            
                    'status' => 0
                );  

                if($this->arquivos->add_arquivos($dados)){

                    // registra ação de logs
                    logs_acao(1, 2, $cliente, $this->session->userdata('id'),$service, null, '<div class="text-success">Cadastrou o arquivo '.$doc.'</div>');
                    
                    // registro de notificação
                    notificacoes($this->session->userdata('id_cliente'), $this->session->userdata('id'), 1, $this->input->post('servicos'), 'Cadastrou o arquivo '.$this->input->post('titulo'));

                    // dispara notificação
                    $usuario = $this->usuarios->lista_usuarios();

                    if(count($usuario) > 0):

                        $dataEnvio = date('d/m/Y H:i:s');

                        foreach($usuario as $cli):

                            $mensagem = "";
                            $nome = "";
                            $email = "";

                            $nome = $cli->nome;
                            $email = $cli->email;

                            $mensagem .= "Olá ".$nome.",<br />";
                            $mensagem .= "Acaba de ser envido um documento <b>".$doc."</b> para o escritório virtual. <br />";
                            $mensagem .= "Acesse sua Área Administrativa, para visaulizar o mesmo, segue abaixo detalhes: <br />";
                            $mensagem .= "<hr />";
                            $mensagem .= "<b>Nome do documento: </b>".$doc."<br />";
                            $mensagem .= "<b>Data de Cadastro: </b>".$dataEnvio."<br />";                    
                            $mensagem .= anchor('/admin/arquivos', 'Clique aqui', 'target="_blank"')." para acessar.";
                            $mensagem .= "<hr />";

                            envia_email($email, 'Novo Documento - '.$doc, $mensagem);

                        endforeach;
                    endif;

                }else{            
                    echo 'OK Não Adicionado - '.$doc.'<br />';   
                }
                /***************************************************************
                 * TÉRMINO
                 **************************************************************/
                
            }else{
                echo 'ERROR - '.$doc.'<br />';            
            }                                    
        endforeach;
    }

  
/*******************************************************************************
 * REGISTROS DE PROTOCOLOS
 ******************************************************************************/    
    public function protocolo_download(){
        $dados = array(            
            'id_arquivo' => $this->input->post('arquivo'),
            'data_abertura' => date('Y-m-d H:i:s'),
            'status' => 1
        );
        
        if($this->arquivos->editar_arquivo($dados)){
            
            $arquivo = $this->arquivos->lista_arquivo_id($this->input->post('arquivo'));
            $nome = $arquivo->titulo;
            // registra ação de logs
            logs_acao(1, 1, $this->session->userdata('id_cliente'), $this->session->userdata('id'), $arquivo->id_servicos, $this->input->post('arquivo'), '<div class="text-info">Abriu o arquivo '.$nome.'.</div>');            
        }

    }    
    
}