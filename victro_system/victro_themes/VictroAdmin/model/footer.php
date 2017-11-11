<footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> <?php echo VERSION; ?>
    </div>
    <strong><?php if(date('Y') > 2016 ){ ?>
    Copyright &#xa9; 2016 - <?php echo date('Y'); ?> <a target="_blank" href="http://victrobrain.com">VictroBrain</a>
    <?php } else { ?>
        Copyright &#xa9; 2016 <a target="_blank" href="http://victrobrain.com">VictroBrain</a>
    <?php } ?></strong> All rights
    reserved.
  </footer>
  <?php include('control.php'); ?>
</div>
<?php basic_js2(); ?>

<div class="modal fade modal-primary" id="victro-modal-system-admin">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                  <h4 class="modal-title"><?php victro_translate("Victro's commands"); ?></h4>
              </div>
              <div class="modal-body">
                  <iframe src="<?php echo SITE_URL."terminal"; ?>" width="100%" frameBorder="0"></iframe>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline pull-left" data-dismiss="modal"><?php victro_translate("close"); ?></button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
<script>
  SITE_URL = '<?php echo SITE_URL; ?>';
  
    function system_function(params){
        $.ajax({
            type: "POST",
            global: false,
            url: '<?php echo SITE_URL; ?>sys/commandDesign',
            data: params,
            success: function (dados1) {
                var dados2 = JSON.parse(dados1);
                $('#extra1').val(dados2['param1']);
                $('#extra2').val(dados2['param2']);
                $('#extra3').val(dados2['param3']);
                $('#extra4').val(dados2['param4']);
                $('#extra5').val(dados2['param5']);
                $('#adm-command').val("<?php victro_translate("Answer"); ?>: "+dados2['return']+"\n"+"<?php victro_translate("Command"); ?>: "+searchvalue+textcommand+"\n");
                if(dados2['return'] == "clean"){
                    $('#adm-command').val('');
                    $('#extra1').val('');
                    $('#extra2').val('');
                    $('#extra3').val('');
                    $('#extra4').val('');
                    $('#extra5').val('');
                } else if(dados2['return'] == "Reloading..."){
                    location.reload();
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
            }
        });
    }
</script>
</body>
</html>
