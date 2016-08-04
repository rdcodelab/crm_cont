<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->model('Usuarios_model', 'usuarios');
        $this->load->model('Clientes_model', 'clientes');
        $this->load->model('Arquivos_model', 'arquivos');
        $this->load->model('Configuracoes_model', 'configuracoes');
        $this->load->model('Chamados_model', 'chamados');
        $this->load->model('Servicos_model', 'servicos');
        $this->load->model('Tarefas_model', 'tarefas');
        
        $this->load->helper('encode');
        
        /***********************************************************************
         * VERIFICA SE OS DOCUMENTOS SERÃO DELETADOS DO SERVIDOR E DO SISTEMA
         **********************************************************************/
        verifica_exclusao();  
        /***********************************************************************
         * DISPARA NOTIFICAÇÕES TODOS OS DIAS AS 10:00h
         **********************************************************************/
        if(date('H:i') == '10:00'):
            $dataExpiracao = SomarData(date('Y-m-d'), 5, 0, 0);
            arquivos_a_vencer($dataExpiracao);
        endif;                
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
            
            // chamados_atendimento => Lista somente os chamados que o usuário esta atendendo
            $dados = array(
                'clientes' => $this->clientes->lista_clientes(),
                'arquivos' => $this->arquivos->lista_arquivos(),
                'ultimos_arquivos' => $this->arquivos->lista_arquivos_limite_ordem(1, 5, 'desc'),
                'arquivos_vencer' => $this->arquivos->lista_arquivos_a_vencer($dataExpiracao),
                'chamados' => $this->chamados->lista_chamados_status(1),
                'chamados_novos' => $this->chamados->lista_chamados_usuario_status(null, 0),
                'chamados_atendimento' => $this->chamados->lista_chamados_usuario_status($this->session->userdata('id'), 1),
                'usuarios' => $this->usuarios->lista_usuarios(),                
                'clientes_usuarios' => $this->clientes->lista_todos_usuarios(),                
                'tipos' => $this->arquivos->lista_tipos(),
                'servicos' => $this->servicos->lista_servicos(),
                'tarefas' => $this->tarefas->getResumoTarefas($this->session->userdata('id'), date('Y-m-d')),
                'tipos' => $this->tarefas->lista_tipos(),
                'tarefas_clientes' => $this->tarefas->getDemandaTarefaCliente($this->session->userdata('servico'))
            );
            
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/home/index', $dados);
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    
    public function permissao_negada(){
        
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
                       
            $this->load->view('sysadmin/inc/header_html');
            $this->load->view('sysadmin/inc/header', $cabecalho);
            $this->load->view('sysadmin/inc/sidebar', $menu);
            $this->load->view('sysadmin/telas/home/permissao_negada');
            $this->load->view('sysadmin/inc/footer');
            $this->load->view('sysadmin/inc/footer_html');
        }
    }
    
    public function login(){
        $this->form_validation->set_rules('usuario', 'USUÁRIO', 'trim|required');
        $this->form_validation->set_rules('senha', 'SENHA', 'trim|required');
        
        if($this->form_validation->run() == true){
            $dados = array(
                'usuario' => $this->input->post('usuario'),
                'senha' => md5($this->input->post('senha')),
            );
            
            if($data = $this->usuarios->login($dados)){
                foreach($data as $ln): endforeach;
                $sessao = array(
                    'id' => $ln->id,
                    'nome' => $ln->nome,
                    'foto' => $ln->foto,
                    'email' => $ln->email,
                    'tipo' => $ln->tipo_usuario,
                    'servico' => $ln->id_servico, 
                    'usuarioLogado' => true
                );
                $this->session->set_userdata($sessao);
                
                // registra log de acesso
                logs_acao(2, 2, null, $this->session->userdata('id'), $this->session->userdata('servico'), null, '<div class="text-primary">Acessou o sistema.</div>');
                
                redirect('/admin/home');
            }else{
                $this->session->set_flashdata('error', 'Usuário ou senha incorretos');
                redirect('/admin/home/');
            }
            
            
        }else{
                $this->session->set_flashdata('error', 'Usuário ou senha incorretos');
                redirect('/admin/home/');
        }
    }
    
    public function lock(){
        //$this->session->sess_destroy();
        //$this->session->set_flashdata('sucesso', 'Obrigao por acessar nosso sistema!');
        $this->load->view('/sysadmin/telas/bloqueio_view');
    }
    
    public function logout(){
        // zera pasta de arquivos temporários
        //apagaTemporarios('./uploads/tmp_docs');
        // registra log de acesso
        logs_acao(2, 2, null, $this->session->userdata('id'), $this->session->userdata('servico'), null, '<div class="text-primary">Saiu do sistema.</div>');
        
        $this->session->sess_destroy();
        $this->session->set_flashdata('sucesso', 'Obrigado por acessar nosso sistema!');
        redirect('/admin/home/');
    }
    
    // recuperação de senha
    public function recupera_senha(){
        
        $dados = $this->usuarios->valida_usuario($this->input->post('usuario'));
        
        if(count($dados) > 0){ 
            
            // registra log de acesso
            logs_acao(2, 2, null, $dados->id, $dados->id_servico, null, '<div class="text-warning">Solicitou recuperação de senha.</div>');
            
            // dispara e-mail com o link de redefinição
            $id_cripto = url_base64_encode($dados->id);

            $msg  = "Olá ".$dados->nome.", <br />";
            $msg .= "Foi solicitado em nosso sistema a redefinição de sua senha de acesso ao mesmo, para redefini-lá acesse o link abaixo: <br />";
            $msg .= anchor('/admin/home/recover/'.$id_cripto, 'Clique aqui', 'target="_blank"')." para redefinir. <br />";
            $msg .= "Caso não tenha sido você, por favor ignore esta mensagem e informe os administradores do sistema.";
            
            // envia email
            envia_email($dados->email, 'Redefinição de senha', $msg);            
            
            // configura mensagem
            echo '<div class="alert alert-success">Enviamos ao seu e-mail, o link de redefinição de senha. Caso não tenha recebido, verifique em sua caixa de span.</div>';
           
        }else{
            echo '<div class="alert alert-warning">Não localizamos seu e-mail em nosso banco de dados, verifique se o mesmo esta correto e tente novamente.</div>';
        }
    }
    
    // recover de atualização de senha
    public function recover(){        
        
        $this->form_validation->set_rules('senha', 'SENHA', 'trim|required|strtolower');
        $this->form_validation->set_message('matches', 'O campo %s está diferente do campo %s');// mensagem personalizada
        $this->form_validation->set_rules('senha2', 'CONFIRME A SENHA', 'trim|required|strtolower|matches[senha]');
        
        if($this->form_validation->run() == TRUE){ 
            
            $iduser = @url_base64_decode($this->uri->segment(4));
           
            $dados = array(
                'id_usuario' => $iduser,
                'senha' => md5($this->input->post('senha'))
            );
            
                        
            if($this->usuarios->editar_usuario($dados, null)){                                
                
                $user = $this->usuarios->lista_usuario_id($iduser);
                
                logs_acao(2, 2, null, $iduser, $user->id_servico, null, '<div class="text-info">Atualizou sua senha, pelo processo de recuperação de senha.</div>');
                
                $this->session->set_flashdata('sucesso', 'Senha atualizada com sucesso, faça seu login abaixo!');
                redirect('/admin/home');      
               
            }else{
                $this->session->set_flashdata('error', 'Erro ao atualizar senha, tente novamente!');
                redirect('/admin/home/recover/'.$this->uri->segment(4));                                               
            }
            
        }else{                           
            
            $iduser = @url_base64_decode($this->uri->segment(4));
            
            $dados = array(
                'usuario' => $this->usuarios->lista_usuario_id($iduser)                
            );
            
            $this->load->view('sysadmin/telas/recuperasenha_view', $dados);
            
        }
        
      
    }
    
}
