<?php
class Usuarios_Model extends CI_Model{
            
/*******************************************************************************
 * USUÁRIOS
 ******************************************************************************/    
    // lista todos os usuários
    public function lista_usuarios(){
        $this->db->order_by('nome', 'asc');
        return $this->db->get('usuarios')->result();
    }
    
    // lista usuário por id
    public function lista_usuario_id($id){
        $this->db->where('id', $id);
        return $this->db->get('usuarios')->row(0);
    }
    
    // validação de dados de acesso
    public function login($dados = array()){
        $this->db->where('email', $dados['usuario']);        
        $this->db->where('senha', $dados['senha']);
        $this->db->where('status', 1);
        
        $query = $this->db->get('usuarios');
        
        if($query->num_rows() == 1){
            return $query->result();
        }else{
            return false;
        }        
    }
    
    // cadastro de usuário
    public function adicionar($dados = null, $permissoes = null){
        
        if($dados != null):           
            $this->db->insert('usuarios', $dados);                    
            $id = $this->db->insert_id();
            if(count($permissoes) > 0):
                foreach($permissoes as $pm):
                    $lista = array('usuarios_id' => $id, 'modulos_id' => $pm);
                    $this->db->insert('modulos_permissoes', $lista);
                endforeach;
            endif;
        
            return true;
            
        endif;
        
    }
    
    public function editar_usuario($dados = array(), $permissoes = array()){
        if(isset($dados['nome'])):
            $this->db->set('nome', $dados['nome']);
        endif;
        if(isset($dados['foto'])):
            $this->db->set('foto', $dados['foto']);
        endif;
        if(isset($dados['email'])):
            $this->db->set('email', $dados['email']);
        endif;
        if(isset($dados['senha'])):
            $this->db->set('senha', $dados['senha']);
        endif;
        if(isset($dados['tipo'])):
            $this->db->set('tipo_usuario', $dados['tipo']);
        endif;
        if(isset($dados['id_servico'])):
            $this->db->set('id_servico', $dados['id_servico']);
        endif;
        if(isset($dados['status'])):
            $this->db->set('status', $dados['status']);
        endif;
        
        $this->db->where('id', $dados['id_usuario']);
        $this->db->update('usuarios');                
                
        if(count($permissoes) > 0):
            // caso exista configurações de permissões atualiza registro
            // verifica se há registro do usuário           
            $this->db->where('usuarios_id', $dados['id_usuario']);
            $query = $this->db->get('modulos_permissoes');
            
            if($query->num_rows() > 0):
                $this->db->where('usuarios_id', $dados['id_usuario']);
                $this->db->delete('modulos_permissoes');
            endif;
                                    
            foreach($permissoes as $perm):
                $linha = array('usuarios_id'=> $dados['id_usuario'], 'modulos_id'=>$perm);
                $this->db->insert('modulos_permissoes', $linha);
            endforeach;
            
        endif;
            
        return $this->db->affected_rows();
    }
    
    public function excluir($id){
        // exclui usuário da tabela modulos_permissoes
        $this->db->where('usuarios_id', $id);
        $this->db->delete('modulos_permissoes');        
        
        $this->db->where('id', $id);
        return $this->db->delete('usuarios');
    }
    
    // valida usuário para recuperação de senha
    public function valida_usuario($email){
        $this->db->where('email', $email);
        return $this->db->get('usuarios')->row(0);
    }

/*******************************************************************************
 * MÓDULOS
 ******************************************************************************/    
    // lista modulos
    public function lista_modulos(){
        $this->db->where('status', '1');
        $this->db->order_by('ordem', 'asc');
        return $this->db->get('modulos')->result();
    }
    
    // lista usuarios por nivel de acesso
    public function lista_modulos_usuario(){
        return $this->db->get('modulos_permissoes')->result();
    }
    
    // verificação de módulos de acesso
    public function nivel_acesso($usuario){
        $this->db->from('modulos mod');
        $this->db->join('modulos_permissoes muser', 'muser.modulos_id = mod.id');
        $this->db->where('muser.usuarios_id', $usuario);
        $this->db->where('mod.status', 1);
        $this->db->order_by('mod.ordem', 'asc');
        return $this->db->get()->result();
    }
    
    // controle de acesso interno individual
    public function controle_acesso($usuario, $controller){
        $this->db->from('modulos mod');
        $this->db->join('modulos_permissoes muser', 'muser.modulos_id = mod.id');
        $this->db->where('muser.usuarios_id', $usuario);
        $this->db->where('mod.controller', $controller);
        $this->db->where('mod.status', 1);
        $this->db->order_by('mod.ordem', 'asc');
        return $this->db->get()->result();
    }
    
    
       
}