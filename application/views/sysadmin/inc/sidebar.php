<!-- **********************************************************************************************************************************************************
MAIN SIDEBAR MENU
*********************************************************************************************************************************************************** -->
<!--sidebar start-->
<aside>
    <div id="sidebar"  class="nav-collapse ">
        <!-- sidebar menu start-->
        <ul class="sidebar-menu" id="nav-accordion">                
            <li class="mt">
                <a href="<?=base_url('/admin'); ?>">
                    <i class="fa fa-dashboard"></i>
                    <span>Página Principal</span>
                </a>
            </li>
            <?php
                if(count($menu) > 0):                    
                    
                    foreach($menu as $mn):
                        $menuAtivo = '';
                        if($this->uri->segment(2) == $mn->controller){
                            $menuAtivo = 'class="active"';
                        }
                        echo '<li>'.anchor('/admin/'.$mn->controller, '<i class="'.$mn->icone.'"></i> '.$mn->nome, $menuAtivo).'</li>';
                    endforeach;
                endif; 
            ?>
            <li class="sub-menu">
                <a href="javascript:void()" >
                    <i class="fa fa-link"></i>
                    <span>Links úteis</span>
                </a>
                <ul class="sub">
                    <li><a  href="<?=base_url('/admin/links/nfse'); ?>">NFS-E</a></li>                    
                    <li><a  href="<?=base_url('/admin/links/consulta_cnpj'); ?>">Consulta CNPJ</a></li>                    
                </ul>
            </li>
            <?php if($this->session->userdata('tipo') == 1): ?>
            <li class="sub-menu">
                <a href="javascript:void();" >
                    <i class="fa fa-cogs"></i>
                    <span>Configurações</span>
                </a>
                <ul class="sub">
                    <li><a  href="<?=base_url('/admin/configuracoes'); ?>">Dados da empresa</a></li>
                    <li><a  href="<?=base_url('/admin/servicos'); ?>">Setores</a></li>
                    <li><a  href="<?=base_url('/admin/configuracoes/pastas_documentos'); ?>">Pastas de documentos</a></li>
                    <li><a  href="<?=base_url('/admin/usuarios'); ?>">Usuários</a></li>
                </ul>
            </li>
            <?php endif; ?>
        </ul>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->