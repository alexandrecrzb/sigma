<!DOCTYPE html>
<html class="no-js" lang="pt-br" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {arquivo_css}
    {jquery}
<title><?php if(isset($titulo)): ?>{titulo} |<?php endif; ?> {titulo_padrao}</title>
</head>
<body>
    <?php if(esta_logado(FALSE)): ?>
        <div class="row header">
            <div class="small-8 columns">
                <a href="<?php echo base_url('dashboard'); ?>"><h1>Sigma</h1></a>
            </div>
              <div class="small-4 columns">
                  
                <button type="button" class="dropdown button user-button" data-toggle="usuario" data-close-on-click="true"><i class="fi fi-torso"></i><?php echo $this->session->userdata('user_nome'); ?></button>
                <div class="dropdown-pane" id="usuario" data-dropdown data-close-on-click="true">
                    <ul class="user-ul"> 
                    <li>
                        <?php echo anchor('usuarios/alterar_senha/'.$this->session->userdata('user_id'), ' &nbsp &nbsp Alterar senha', 'class="fi-lock"'); ?>
                        </li>                        
                        <?php echo anchor('usuarios/logout', '&nbsp &nbsp Sair', 'class="fi-power"'); ?>
                    </li>
                    </ul>

                </div>
            </div>

        <div class="row barra-menu">
            <div class="small-12 columns">
                <div class="top-bar">
                <div class="top-bar-left">
                    <ul class="dropdown menu" data-dropdown-menu>
                    <li class="menu-texto"><?php echo anchor ('dashboard', 'Home'); ?></li>
                    <li>
                        <?php echo anchor ('usuarios/gerenciar', 'Usuários'); ?>
                        <ul class="menu vertical">
                            <li><?php echo anchor ('usuarios/cadastrar', 'Cadastrar'); ?></li>
                            <li><?php echo anchor ('usuarios/gerenciar', 'Gerenciar'); ?></li>
                            <li><?php echo anchor ('auditoria/gerenciar', 'Auditoria'); ?></li>
                        </ul>
                    </li>
                    <li>
                        <?php echo anchor ('midia/gerenciar', 'Midia'); ?>
                        <ul class="menu vertical">
                            <li><?php echo anchor ('midia/cadastrar', 'Cadastrar'); ?></li>
                            <li><?php echo anchor ('midia/gerenciar', 'Gerenciar'); ?></li>
                        </ul>
                    </li>        
                   </ul>
                </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="row dashboard">
        {conteudo}    
    </div>
    <div class="row footer">
        <div class="small-12 columns text-center">
            {footer}
        </div>
    </div>
    {arquivo_js}
</body>
</html>