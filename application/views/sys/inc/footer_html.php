</section>
  
    <script src="<?php echo base_url('layout/admin/js/bootstrap.min.js'); ?>"></script>
    <script class="include" type="text/javascript" src="<?php echo base_url('layout/admin/js/jquery.dcjqaccordion.2.7.js'); ?>"></script>
    <script src="<?php echo base_url('layout/admin/js/jquery.scrollTo.min.js'); ?>"></script>
    <script src="<?php echo base_url('layout/admin/js/jquery.nicescroll.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('layout/admin/js/jquery.sparkline.js'); ?>"></script>


    <!--common script for all pages-->
    <script src="<?php echo base_url('layout/admin/js/common-scripts.js'); ?>"></script>
    
    <script type="text/javascript" src="<?php echo base_url('layout/admin/js/gritter/js/jquery.gritter.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('layout/admin/js/gritter-conf.js'); ?>"></script>

    <!--script for this page-->
    <script src="<?php echo base_url('layout/admin/js/sparkline-chart.js'); ?>"></script>    
    <script src="<?php echo base_url('layout/admin/js/zabuto_calendar.js'); ?>"></script>	
        
    <script src="<?php echo base_url('layout/admin/js/tasks.js'); ?>" type="text/javascript"></script>
        
    <!--<script src="<?php echo base_url('layout/admin/js/jquery-1.12.4.min.js'); ?>" type="text/javascript"></script>-->
    <script src="<?php echo base_url('layout/admin/js/dataTables/jquery.dataTables.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('layout/admin/js/dataTables/dataTables.buttons.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('layout/admin/js/dataTables/buttons.flash.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('layout/admin/js/dataTables/jszip.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('layout/admin/js/dataTables/pdfmake.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('layout/admin/js/dataTables/vfs_fonts.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('layout/admin/js/dataTables/buttons.html5.min.js'); ?>" type="text/javascript"></script>
    <script src="<?php echo base_url('layout/admin/js/dataTables/buttons.print.min.js'); ?>" type="text/javascript"></script>
    
    <script src="<?php echo base_url('layout/admin/js/jquery-ui.js'); ?>"></script>
    
    <script src="<?php echo base_url('/layout/admin/js/jquery.form.js'); ?>" type="text/javascript"></script>
    <script>
        $( ".datepicker" ).datepicker({
            dateFormat: 'dd/mm/yy',
            dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
            dayNamesMin: ['D','S','T','Q','Q','S','S','D'],
            dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb','Dom'],
            monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
            monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
            nextText: 'Próximo',
            prevText: 'Anterior'
        });
    </script>
    
    <script type="text/javascript">
            $(document).ready(function () {
                // habilita popovers
                $('.info').popover();                                            
            });
                        
	</script>
	
	<script type="application/javascript">
            $(document).ready(function () {
                $("#date-popover").popover({html: true, trigger: "manual"});
                $("#date-popover").hide();
                $("#date-popover").click(function (e) {
                    $(this).hide();
                });

                $("#my-calendar").zabuto_calendar({
                    action: function () {
                        return myDateFunction(this.id, false);
                    },
                    action_nav: function () {
                        return myNavFunction(this.id);
                    },
                    ajax: {
                        url: "show_data.php?action=1",
                        modal: true
                    },
                    legend: [
                        {type: "text", label: "Special event", badge: "00"},
                        {type: "block", label: "Regular event", }
                    ]
                });
            });


            function myNavFunction(id) {
                $("#date-popover").hide();
                var nav = $("#" + id).data("navigation");
                var to = $("#" + id).data("to");
                console.log('nav ' + nav + ' to: ' + to.month + '/' + to.year);
            }
            
            
            // abre tarefa
            function abre_tarefa(tarefa){
                $.ajax({
                    type: 'POST',
                    data: {'tarefa': tarefa},
                    url: '<?=base_url('/tarefas/abre_tarefa'); ?>',        
                    beforeSend: function(){
                        $('.msg_retorno').html('<div class="alert alert-warning">Carregando tarefa, aguarde...</div>');
                    },
                    success: function(info){
                        $('.msg_retorno').html('');            
                        $('#retorno_ajax').html(info);
                        $('#modalTarefa').modal('show');
                    },
                    error: function(){
                        $('.msg_retorno').html('<div class="alert alert-danger">Erro ao carregar tarefas, atualize sua página e tente novamente...</div>');
                    }        

                });
            }

            function envia_mensagem(tarefa){
                var dados = $('#frmMensagens'+tarefa).serialize();

                $.ajax({
                    type: 'POST',
                    data: dados,
                    url: '<?=base_url('/tarefas/addmensagem'); ?>',
                    beforeSend: function(){
                       $('.msg_retorno_modal').html('<div class="alert alert-warning">Enviando mensagem, aguarde...</div>');
                    },
                    success: function(info){
                       $('.msg_retorno_modal').html(info);
                       $('#msg').val('');
                       carrega_mensagens(tarefa);
                    },
                    error: function(){
                       $('.msg_retorno_modal').html('<div class="alert alert-danger">Erro ao enviar mensagem, atualize a página e tente novamente.</div>');
                    }        
                });            
            }

            function carrega_mensagens(tarefa){
                $.ajax({
                    type: 'POST',
                    data: {'tarefa': tarefa},
                    url: '<?=base_url('/tarefas/listamensagens'); ?>',
                    beforeSend: function(){
                       $('.msg_retorno_modal').html('<div class="alert alert-warning">Carregando mensagens, aguarde...</div>');
                    },
                    success: function(info){
                       $('.msg_retorno_modal').html('');
                       $('.lista-msg').html(info);
                    },
                    error: function(){
                       $('.msg_retorno_modal').html('<div class="alert alert-danger">Erro ao carregar mensagens, atualize a página e tente novamente.</div>');
                    }        
                });
            }
    </script>
  

  </body>
</html>
