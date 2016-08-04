<!--sidebar start-->
<aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">                        
            <li class="mt"><a href="<?=base_url('/'); ?>"><i class="fa fa-dashboard"></i><span>Página Principal</span></a></li>            
            <li><a href="<?=base_url('/arquivos'); ?>"><i class="fa fa-cloud-upload"></i><span>Arquivos</span></a></li>                        
            <li><a href="<?=base_url('/tarefas'); ?>"><i class="fa fa-clock-o"></i><span>Solicitações de Serviços</span></a></li>  
            <li><a href="<?=base_url('/chamados'); ?>"><i class="fa fa-envelope-o"></i><span>Suporte via Chamado</span></a></li>                                                      
            <?php if($this->session->userdata('tipo') == 1): ?>
            <li><a href="<?=base_url('/protocolos'); ?>"><i class="fa fa-list-alt"></i><span>Relatórios</span></a></li>            
            <li><a href="<?=base_url('/usuarios'); ?>"><i class="fa fa-users"></i><span>Usuários</span></a></li>                        
            <?php endif; ?>
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->