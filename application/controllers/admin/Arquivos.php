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
        $this->load->model('Tarefas_model', 'tarefas');
                
        // controle de nível de acesso
        $this->load->helper('Permissoes');
        controle_acesso($this->session->userdata('id'), 'arquivos');
        
        
        $this->controla_exclusao_vcto();
        // realiza controle de status de arquivos.
        $this->arquivos->controle_status();
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
                'arquivos_novos' => $this->arquivos->lista_arquivos_status(0, 1),
                'arquivos_abertos' => $this->arquivos->lista_arquivos_status(1, 1),
                'arquivos_vencidos' => $this->arquivos->lista_arquivos_status(2, 1),
                'arquivos_excluidos' => $this->arquivos->lista_arquivos_status(3, 1),
                'clientes' => $this->clientes->lista_clientes(),
                'clientes_usuarios' => $this->clientes->lista_todos_usuarios(),
                'usuarios' => $this->usuarios->lista_usuarios(),
                'tipos' => $this->arquivos->lista_tipos_servico($this->session->userdata('servico')),
                'servicos' => $this->servicos->lista_servicos()
            );
            
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/arquivos/index', $dados);
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    
    public function listadocs(){
        
        $docs = $this->arquivos->lista_arquivos();
        
        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/json");
        
        echo '{              
                "data": [';
                    $lista = "";
                    foreach($docs as $ln):
                        // data de envio
                        $data1   = substr($ln->data_cadastro, 0, 10);
                        $data_br = implode("/", array_reverse(explode("-", $data1)));
                        // formata hora
                        $hora1 = substr($ln->data_cadastro, 11, 8);
                        $dataRegistro = $data_br.' '. $hora1;
                        
                        // resgata nome do cliente
                        $cliente = $this->clientes->lista_cliente_id($ln->id_clientes);
                        
                        // resgata setor e pasta
                        $setor = $this->servicos->lista_servico_id($ln->id_servicos);
                        $pasta = $this->arquivos->lista_tipo_id($ln->id_tipo);                       
                        $n_setor = $setor->nome." > ".$pasta->nome_tipo;
                        
                        // resgata nome do responsável
                        $user = $this->usuarios->lista_usuario_id($ln->id_usuario);
                        
                        // formata status do arquivo                        
                        switch ($ln->status){
                            case 0:
                                $status = '<div class=\"label label-default\">Não aberto</div>';
                            break;
                            case 1:
                                $status = '<div class=\"label label-info\">Aberto</div>';
                            break;
                            case 2:
                                $status = '<div class=\"label label-warning\">Vencido</div>';
                            break;
                            case 3:
                                $status = '<div class=\"label label-danger\">Excluído</div>';
                            break;     
                            default :
                                $status = '<div class=\"label label-default\">n/d</div>';
                            break;
                        }
                        
                        $lista .= '{
                                "dataenvio": "'.$dataRegistro.'",                                
                                "cliente": "'.$cliente->razao_social.'",
                                "documento": "'.$ln->titulo.'",
                                "setor": "'.$n_setor.'",
                                "responsavel": "'.$user->nome.'",
                                "validade": "'.implode("/", array_reverse(explode("-", $ln->data_vencimento))).'",                               
                                "status": "'.$status.'",                               
                                "opc": "<a href=\"javascript:void()\" title=\"Excluir arquivo\" class=\"btn btn-danger btn-sm delArquivo\" onclick=\"deleta_arquivo('.$ln->idarquivos.', \''.$ln->titulo.'\', '.$ln->status.')\" data-toggle=\"modal\" data-target=\"#delArquivo\"><i class=\"fa fa-trash-o\"></i></a>"
                              },';
                    endforeach;
                    
                    echo substr($lista, 0, -1);
            echo ']

            }';        
        
        
    }
    
    
    public function listadocs_filtro(){
        
        $dados = array(
            'cliente' => $this->input->get('cliente'),
            'documento' => $this->input->get('documento'),
            'setor' => $this->input->get('servico'),
            'status' => $this->input->get('status'),
            'envio_inicial' => implode("-", array_reverse(explode("/", $this->input->get('envio_inicial')))),
            'envio_final' => implode("-", array_reverse(explode("/", $this->input->get('envio_final')))),
            'validade_inicial' => implode("-", array_reverse(explode("/", $this->input->get('validade_inicial')))),
            'validade_final' => implode("-", array_reverse(explode("/", $this->input->get('validade_final'))))
        );
        
        
        $docs = $this->arquivos->getArquivosFiltros($dados);
        
        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/json");
        
        echo '{              
                "data": [';
                    $lista = "";
                    foreach($docs as $ln):
                        // data de envio
                        $data1   = substr($ln->data_cadastro, 0, 10);
                        $data_br = implode("/", array_reverse(explode("-", $data1)));
                        // formata hora
                        $hora1 = substr($ln->data_cadastro, 11, 8);
                        $dataRegistro = $data_br.' '. $hora1;
                        
                        // resgata nome do cliente
                        $cliente = $this->clientes->lista_cliente_id($ln->id_clientes);
                        
                        // resgata setor e pasta
                        $setor = $this->servicos->lista_servico_id($ln->id_servicos);
                        $pasta = $this->arquivos->lista_tipo_id($ln->id_tipo);                       
                        $n_setor = $setor->nome." > ".$pasta->nome_tipo;
                        
                        // resgata nome do responsável
                        $user = $this->usuarios->lista_usuario_id($ln->id_usuario);
                        
                        // formata status do arquivo                        
                        switch ($ln->status){
                            case 0:
                                $status = '<div class=\"label label-default\">Não aberto</div>';
                            break;
                            case 1:
                                $status = '<div class=\"label label-info\">Aberto</div>';
                            break;
                            case 2:
                                $status = '<div class=\"label label-warning\">Vencido</div>';
                            break;
                            case 3:
                                $status = '<div class=\"label label-danger\">Excluído</div>';
                            break;     
                            default :
                                $status = '<div class=\"label label-default\">n/d</div>';
                            break;
                        }
                        
                        $lista .= '{
                                "dataenvio": "'.$dataRegistro.'",                                
                                "cliente": "'.$cliente->razao_social.'",
                                "documento": "'.$ln->titulo.'",
                                "setor": "'.$n_setor.'",
                                "responsavel": "'.$user->nome.'",
                                "validade": "'.implode("/", array_reverse(explode("-", $ln->data_vencimento))).'",                               
                                "status": "'.$status.'",                               
                                "opc": "<a href=\"javascript:void()\" title=\"Excluir arquivo\" class=\"btn btn-danger btn-sm delArquivo\" data-toggle=\"modal\" onclick=\"deleta_arquivo('.$ln->idarquivos.', \''.$ln->titulo.'\', '.$ln->status.')\" data-target=\"#delArquivo\"><i class=\"fa fa-trash-o\"></i></a>"
                              },';
                    endforeach;
                    
                    echo substr($lista, 0, -1);
            echo ']

            }';                        
    }
    
    
    public function add(){
        // faz upload
        $config['upload_path'] = 'uploads/arquivos/';
        $config['allowed_types'] = 'doc|docx|xls|pdf|gif|jpg|png|jpeg';        
        $config['encrypt_name'] = false;
        $this->load->library('upload', $config);              
        
        $this->upload->do_upload();      
        $arquivo_upado = $this->upload->data();
        
        // verifica quais os arquivos vencidos
        $dias = $this->configuracoes->lista_configs(1);
        $datavcto = implode("-", array_reverse(explode("/", $this->input->post('vcto'))));
        
        // Exibe o resultado
        $data_exclusao = SomarData($datavcto, $dias->vcto_arquivos, 0, 0);                        
        
        // resgata id do setor
        $setor = $this->arquivos->lista_tipo_id($this->input->post('tipo'));
        
        $dados = array(
            'id_usuario' => $this->input->post('usuario'),
            'id_clientes' => $this->input->post('cliente'),
            'id_servicos' => $setor->id_servico,
            'id_tipo' => $this->input->post('tipo'),
            'titulo' => $this->input->post('titulo'),
            'arquivo' => $arquivo_upado['file_name'],
            'referencia' => $this->input->post('referencia'),
            'data_vencimento' => $datavcto,            
            'data_exclusao' => $data_exclusao,            
            'status' => 0
        );  
        
        if($this->arquivos->add_arquivos($dados)){
            
            // registra ação de logs
            logs_acao(1, 2, null, $this->input->post('cliente'), $this->session->userdata('id'), $setor->id_servico, null, '<div class="text-success">Cadastrou o arquivo '.$this->input->post('titulo').'</div>');
            //notificacoes($this->session->userdata('id'), 1, 'O documento '.$this->input->post('titulo').' vence hoje.', $datavcto);
            // dispara notificação
            $cliente_usuario = $this->clientes->lista_usuarios_cliente($this->input->post('cliente'));
            
            if(count($cliente_usuario) > 0):
                $tipo = $this->arquivos->lista_tipo_id($this->input->post('tipo'));
                $dataEnvio = date('d/m/Y H:i:s');
                $vencimento = implode("/", array_reverse(explode("-", $this->input->post('vcto'))));
                // resgata dados da empresa
                $dados = $this->configuracoes->lista_configs(1);
                
                foreach($cliente_usuario as $cli):
                    
                    $mensagem = "";
                    $nome = "";
                    $email = "";
                    
                    $nome = $cli->nome;
                    $email = $cli->email;
                
                    $mensagem .= "Olá ".$nome.",<br />";
                    $mensagem .= "Acabamos de enviar um documento <b>".$this->input->post('titulo')."</b> para você. <br />";
                    $mensagem .= "Acesse sua Área Administrativa, para visaulizar o mesmo, segue abaixo detalhes: <br />";
                    $mensagem .= "<hr />";
                    $mensagem .= "<b>Tipo do documento: </b>".$tipo->nome_tipo."<br />";
                    $mensagem .= "<b>Nome do documento: </b>".$this->input->post('titulo')."<br />";
                    $mensagem .= "<b>Data de Cadastro: </b>".$dataEnvio."<br />";
                    $mensagem .= "<b>Expira em: </b>".$vencimento."<br />";
                    $mensagem .= "<b>URL:</b> <a href=\"".$dados->site_escritorio."\" title=\"Acessar escritório virtual\">".$dados->site_escritorio."</a>";
                    $mensagem .= "<hr />";
                    $mensagem .= "<small>Atenção! Após a data de expiração o documento não ficará mais disponível.</small>";
                                    
                    envia_email($cli->email, 'Novo Documento - '.$this->input->post('titulo'), $mensagem);
                    
                endforeach;
            endif;
            
                       
            // se rota for = 1 => cadastro de arquivos veio pela página de detalhes
            if($this->input->post('rota') == 1){
                $this->session->set_flashdata('sucesso', 'Arquivo cadastrado com sucesso.');
                redirect('admin/clientes/detalhes/'.$this->input->post('cliente'));
            }else{
                $this->session->set_flashdata('sucesso', 'Arquivo cadastrado com sucesso.');
                redirect('admin/arquivos');
            }
            
        }else{            
            // se rota for = 1 => cadastro de arquivos veio pela página de detalhes
            if($this->input->post('rota') == 1){
                $this->session->set_flashdata('error', 'Erro ao cadastrar arquivo, tente novamente.');
                redirect('admin/clientes/detalhes/'.$this->input->post('cliente'));
            }else{
                $this->session->set_flashdata('error', 'Erro ao cadastrar arquivo, tente novamente.');
                redirect('admin/arquivos');
            }
        }
    }
    
    public function editar(){
        
         if($this->session->userdata('usuarioLogado') == false){            
            $this->load->view('sysadmin/telas/login_view');
        }else{ 
        
            $this->form_validation->set_rules('cliente', 'CLIENTE', 'trim|required|numeric');
            $this->form_validation->set_rules('titulo', 'TÍTULO DO ARQUIVO', 'trim|required');                
            $this->form_validation->set_rules('vcto', 'VENCIMENTO', 'trim|required');                
            if($this->form_validation->run() == TRUE){     
                
                // verifica quais os arquivos vencidos
                $dias = $this->configuracoes->lista_configs(1);
                $datavcto = $this->input->post('vcto');

                // Exibe o resultado
                $data_exclusao = SomarData($datavcto, $dias->vcto_arquivos, 0, 0);  
                

                $dados = array(
                    'id_arquivo' => $this->input->post('arquivo'),
                    'id_clientes' => $this->input->post('cliente'),
                    'id_servicos' => $this->input->post('servico'),
                    'id_tipo' => $this->input->post('tipo'),
                    'titulo' => $this->input->post('titulo'),                                        
                    'data_vencimento' => $this->input->post('vcto'),                                
                    'data_exclusao' => $data_exclusao,            
                );                

                if($this->arquivos->editar_arquivo($dados)){
                    
                    // registra ação de logs
                    logs_acao(1, 2, $this->input->post('cliente'), $this->session->userdata('id'), $this->input->post('servico'), $this->input->post('arquivo'), '<div class="text-success">Atualizou o arquivo '.$this->input->post('titulo').'</div>');
                    
                    $this->session->set_flashdata('sucesso', 'Arquivo atualizado com sucesso!');
                    redirect('/admin/arquivos/editar/'.$this->input->post('arquivo'));      

                }else{
                    $this->session->set_flashdata('error', 'Erro ao atualizar arquivo, tente novamente!');
                    redirect('/admin/arquivos/editar/'.$this->input->post('arquivo'));                                               
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
                    'arquivo' => $this->arquivos->lista_arquivo_id($this->uri->segment(4)),
                    'clientes' => $this->clientes->lista_clientes(),
                    'usuarios' => $this->usuarios->lista_usuarios(),
                    'tipos' => $this->arquivos->lista_tipos(),
                    'servicos' => $this->servicos->lista_servicos(),
                    'serv_clientes' => $this->clientes->lista_clientes_servicos()
                );

                $this->load->view('sysadmin/inc/header_html');
                $this->load->view('sysadmin/inc/header', $cabecalho);
                $this->load->view('sysadmin/inc/sidebar', $menu);
                $this->load->view('sysadmin/telas/arquivos_editar_view', $dados);
                $this->load->view('sysadmin/inc/footer');
                $this->load->view('sysadmin/inc/footer_html');
            }
        }
    }

    
    // atualiza arquivo
    public function atualizaFile(){
        
        // faz upload
        $config['upload_path'] = './uploads/arquivos/';
        $config['allowed_types'] = 'doc|docx|xls|pdf|gif|jpg|png|jpeg';                       
        $config['encrypt_name'] = true;
        $this->load->library('upload', $config);

        if(! $this->upload->do_upload()){                                    
            
            $this->session->set_flashdata('error', 'Erro ao upar arquivo. '.$this->upload->display_errors());
            redirect('admin/arquivos/editar/'.$this->input->post('arquivo')); 
        }else{                            
            $arquivo_upado = $this->upload->data();
            $dados = array(
                'id_arquivo'=> $this->input->post('arquivo'),
                'arquivo' => $arquivo_upado['file_name']                    
            );

            $arquivo_del = './uploads/arquivos/'.$this->input->post('arquivoAntigo');

            // deleta arquivo antigo
            if(@unlink($arquivo_del)){
                $msg_erro = ' arquivo deletado.';
            }else{
                $msg_erro = ' erro ao deletar arquivo.';
            }

            if($this->arquivos->editar_arquivo($dados)){
                $nome = $this->arquivos->lista_arquivo_id($this->input->post('arquivo'));
                
                // registra ação de logs
                logs_acao(1, 2, $this->session->userdata('id'), $nome->id_servicos, $this->input->post('arquivo'), '<div class="text-info">Atualizou o arquivo '.$nome->titulo.'</div>');
                
                // configura mensagem
                $this->session->set_flashdata('sucesso', 'Arquivo salvo com sucesso!!! '.$msg_erro);
                redirect('admin/arquivos/editar/'.$this->input->post('arquivo'));                
            }else{
                // configura mensagem
                $this->session->set_flashdata('error', 'Erro ao atualizar arquivo, tente novamente!!!'.$msg_erro);
                redirect('admin/arquivos/editar/'.$this->input->post('arquivo'));
            }
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
            logs_acao(1, 2, $arquivo->id_clientes, $this->session->userdata('id'), $arquivo->id_servicos, $this->uri->segment(4), '<div class="text-danger">Enviou o arquivo '.$nome.' para lixeira.</div>');
                            
            // parametro da rota passado via get
            if($this->uri->segment(5) == 1){
                $this->session->set_flashdata('sucesso', 'Arquivo enviado para lixeira com sucesso!');
                redirect('/admin/clientes/detalhes/'.$arquivo->id_clientes);
            }else{
                $this->session->set_flashdata('sucesso', 'Arquivo enviado para lixeira com sucesso!');
                redirect('/admin/arquivos/');
            }
        }else{
            // parametro da rota passado via get
            if($this->uri->segment(5) == 1){
                $this->session->set_flashdata('sucesso', 'Arquivo enviado para lixeira com sucesso!');
                redirect('/admin/clientes/detalhes/'.$arquivo->id_clientes);
            }else{
                $this->session->set_flashdata('sucesso', 'Arquivo enviado para lixeira com sucesso!');
                redirect('/admin/arquivos/');
            }
        }
    }
    
    // restaura arquivo da lixeira
    public function recover(){
        $dados = array(
            'id_arquivo' => $this->uri->segment(4),
            'status' => 1
        );
        if($this->arquivos->editar_arquivo($dados)){
            
            $nome = $this->arquivos->lista_arquivo_id($this->uri->segment(4));                
            // registra ação de logs
            logs_acao(1, 2, $this->session->userdata('id'), $nome->id_servicos, $this->uri->segment(4),'<div class="text-info">Restaurou o arquivo '.$nome->titulo.' da lixeira.</div>');                 
            
            $this->session->set_flashdata('sucesso', 'Arquivo restaurado com sucesso!');
            redirect('/admin/arquivos/');
        }else{
            $this->session->set_flashdata('error', 'Erro ao restaurar arquivo, tente novamente!');
            redirect('/admin/arquivos/');
        }
    }
    
    
    public function excluir(){
        
        $arquivo = $this->arquivos->lista_arquivo_id($this->input->post('id'));
        $nome = $arquivo->titulo;
        $cliente = $arquivo->id_clientes;
        $arquivo_del = './uploads/arquivos/'.$arquivo->arquivo;

        // deleta arquivo antigo
        if(@unlink($arquivo_del)){
            
            if($this->arquivos->excluir($this->input->post('id'))){
                // registra ação de logs
                logs_acao(1, 2, $arquivo->id_clientes, $this->session->userdata('id'), $arquivo->id_servicos, $this->input->post('id'), '<div class="text-info">Deletou o arquivo e '.$nome.' da lixeira, e o documento vinculado do servidor.</div>');                 
                
                $this->session->set_flashdata('sucesso', 'Arquivo e documento excluido com sucesso!');
                // rota = 1 - solicitação veio da página de detalhes do cliente
                if($this->input->post('rota') == 1){
                    redirect('/admin/clientes/detalhes/'.$cliente);
                }else{
                    redirect('/admin/arquivos/');
                }
            }else{
                $this->session->set_flashdata('error', 'Erro ao excluir o arquivo, tente novamente!');
                // rota = 1 - solicitação veio da página de detalhes do cliente
                if($this->input->post('rota') == 1){
                    redirect('/admin/clientes/detalhes/'.$cliente);
                }else{
                    redirect('/admin/arquivos/');
                }
            }            
            
        }else{
            $this->session->set_flashdata('error', 'Erro ao excluir o documento do arquivo, tente novamente!');
            redirect('/admin/arquivos/');
        }                                                
    }

    // controla exclusão de arquivos conforme vencimento
    public function controla_exclusao_vcto(){
        
        // verifica quais os arquivos vencidos
        $dias = $this->configuracoes->lista_configs(1);
        
        // Calcula a data daqui 3 dias
        $timestamp = strtotime("+".$dias->vcto_arquivos." days");
        // Exibe o resultado
        //echo date('d/m/Y H:i', $timestamp); // 27/03/2009 05:02
        
        //$arquivo_del = './uploads/arquivos/'.$this->input->post('arquivoAntigo');
    }
        
    public function envia_lote(){
        $cliente = $this->input->post('cliente');
        $tipo = $this->input->post('tipo');
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
                    'referencia' => 1,
                    'data_vencimento' => $datavcto,            
                    'data_exclusao' => $data_exclusao,            
                    'status' => 0
                );  

                if($this->arquivos->add_arquivos($dados)){

                    // registra ação de logs
                    logs_acao(1, 2, $cliente, $this->session->userdata('id'),$service, null, '<div class="text-success">Cadastrou o arquivo '.$doc.'</div>');
                    //notificacoes($this->session->userdata('id'), 1, 'O documento '.$doc.' vence hoje.', $datavcto);
                    // dispara notificação
                    $cliente_usuario = $this->clientes->lista_usuarios_cliente($cliente);

                    if(count($cliente_usuario) > 0):
                        $tpo = $this->arquivos->lista_tipo_id($tipo);
                        $dataEnvio = date('d/m/Y H:i:s');
                        $vencimento = implode("/", array_reverse(explode("-", $datavcto)));
                        $dados = $this->configuracoes->lista_configs(1);
                        foreach($cliente_usuario as $cli):

                            $mensagem = "";
                            $nome = "";
                            $email = "";

                            $nome = $cli->nome;
                            $email = $cli->email;

                            $mensagem .= "Olá ".$nome.",<br />";
                            $mensagem .= "Acabamos de enviar um documento <b>".$doc."</b> para você. <br />";
                            $mensagem .= "Acesse sua Área Administrativa, para visaulizar o mesmo, segue abaixo detalhes: <br />";
                            $mensagem .= "<hr />";
                            $mensagem .= "<b>Tipo do documento: </b>".$tpo->nome_tipo."<br />";
                            $mensagem .= "<b>Nome do documento: </b>".$doc."<br />";
                            $mensagem .= "<b>Data de Cadastro: </b>".$dataEnvio."<br />";
                            $mensagem .= "<b>Expira em: </b>".$vencimento."<br />";
                            $mensagem .= "<b>URL:</b> <a href=\"".$dados->site_escritorio."\" title=\"Acessar escritório virtual\">".$dados->site_escritorio."</a>";
                            $mensagem .= "<hr />";
                            $mensagem .= "<small>Atenção! Após a data de expiração o documento não ficará mais disponível.</small>";

                            envia_email($cli->email, 'Novo Documento - '.$doc, $mensagem);

                        endforeach;
                    endif; 
                    
                    echo 'OK Adicionado - '.$doc.'<br />';   

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
 * SERVIÇOS POR CLIENTES
 ******************************************************************************/    
    public function servico_cliente(){
        $dados = $this->clientes->lista_servico_cliente($this->input->post('cliente'));
        
        if(count($dados) == 0){
            echo '<div class="alert alert-warning">Nã há serviços vincualados a este cliente</div>';
        }else{
            //echo '<label>Serviços</label>';
            echo '<select name="tipo" class="form-control servicos_cliente">';
            echo '<option value="">Selecione um serviço</option>';
            foreach($dados as $ln):
                echo '<option value="'.$ln->id.'">'.$ln->nome.'</option>';
            endforeach;
            echo '</select>';
        }        
    } 
    
    public function tipo_arquivo_cliente(){
        $dados = $this->arquivos->lista_tipos_servicos_clientes($this->input->post('cliente'));
        
        if(count($dados) == 0){
            echo '<div class="alert alert-warning">Nã há tipos de arquivos vinculados a este cliente.</div>';
        }else{
            //echo '<label>Serviços</label>';
            echo '<select name="tipo" class="form-control servicos_cliente">';
            echo '<option value="">Selecione um tipo</option>';
            foreach($dados as $ln):
                echo '<option value="'.$ln->id_tipo.'">'.$ln->nome_tipo.'</option>';
            endforeach;
            echo '</select>';
        }
    }
/*******************************************************************************
 * TIPOS DE ARQUIVOS
 ******************************************************************************/    
    public function tipos_arquivos(){
        $dados = array(
                    'tipos' => $this->arquivos->lista_tipos(),
                    'servicos' => $this->servicos->lista_servicos()
                );
        
        $this->load->view('sysadmin/telas/arquivos_tipos_view', $dados);
    }
    
    public function addtipo(){
        $dados = array('nome_tipo'=>$this->input->post('tipo'), 'id_servico' => $this->input->post('servico'));
        
        if($this->arquivos->add_tipo($dados)){            
            // registra ação de logs
            logs_acao(1, 2, $this->session->userdata('id'), $this->input->post('servico'), null, '<div class="text-success">Cadastrou o tipo de arquivo '.$this->input->post('tipo').'.</div>');
                             
            echo '<div class="alert alert-success">Tipo de cliente cadastrado com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger">Erro ao cadastrar tipo de cliente, tente novamente.</div>';
        }
    }
    
    public function edita_tipo(){
        
        $dados = array(
            'id_tipo' => $this->input->post('id_tipo'),
            'nome_tipo' => $this->input->post('tipo'),
            'id_servico' => $this->input->post('servico')
        );
        
        
        if($this->arquivos->atualiza_tipo($dados)){
            
            // registra ação de logs
            logs_acao(1, 2, $this->session->userdata('id'), $this->input->post('servico'), null, '<div class="text-info">Atualizou o tipo de arquivo '.$this->input->post('tipo').'.</div>');            
            
            echo '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Dados atualizados com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Erro ao atualizar dados.</div>';
        }
        
    }
    
    public function excluir_tipo(){
        $tipo = $this->arquivos->lista_tipo_id($this->input->post('id_tipo'));
        $nome = $tipo->nome_tipo;
                
        if($this->arquivos->del_tipo($this->input->post('id_tipo'))){
            
            // registra ação de logs
            logs_acao(1, 2, $this->session->userdata('id'), $tipo->id_servico, null, '<div class="text-danger">Deletou o tipo de arquivo '.$nome.'.</div>');            
            
            echo '<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Dados atualizados com sucesso.</div>';
        }else{
            echo '<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Erro ao atualizar dados.</div>';
        }
    }
/*******************************************************************************
 * NOTIFICAÇÕES
 ******************************************************************************/    
    public function modal_email(){
        $dados = array(
            'cliente' => $this->clientes->lista_cliente_id($this->input->post('cliente')),
            'cliente_usuarios' => $this->clientes->lista_usuarios_cliente($this->input->post('cliente')),
            'arquivo' => $this->arquivos->lista_arquivo_id($this->input->post('arquivo'))
        );
        
        $this->load->view('sysadmin/telas/arquivos_modal_email', $dados);
    }    
    
    public function envia_email(){
        
        if(count($_POST['destinatarios']) > 0){
            foreach($_POST['destinatarios'] as $ln):                
                // envia notificação
                envia_email($ln, $this->input->post('assunto'), $this->input->post('mensagem'));
            endforeach;
            
            $this->session->userdata('sucesso', 'Mensagem enviada com sucesso!');
            redirect('/admin/arquivos');
        }else{
            $this->session->userdata('error', 'Você deve selecionar pelo menos um destinatário!');
            redirect('/admin/arquivos');
        }
        
        
        
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
            logs_acao(1, 2, $this->session->userdata('id'), $arquivo->id_servicos, $this->input->post('arquivo'), '<div class="text-info">Abriu o arquivo '.$nome.'.</div>');            
        }
        
    } 
    
}