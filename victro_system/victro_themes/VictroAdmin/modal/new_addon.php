<?php
?>
<form id="mainAddon">
  <div class="form-group">
    <label for="host"><?php victro_translate("Actual Host"); ?>:</label>
    <input type="text" class="form-control" id="host" onkeypress="fieldsAddon(this)" onkeyup="ToDown(this);">
  </div>
  <div class="form-group">
    <label for="wifi"><?php victro_translate("Wifi Type"); ?>:</label>
    <select class="form-control" id="wifi">
      <option value="AP">Access Point</option>
      <option value="ST">Station</option>
    </select>
  </div>
  <div class="form-group">
    <label for="ssid">SSID:</label>
    <input type="text" class="form-control" id="ssid">
  </div>
  <div class="form-group">
    <label for="pass"><?php victro_translate("Password"); ?>:</label>
    <input type="text" class="form-control" id="pass">
  </div>
  <div class="form-group">
    <label for="nhost"><?php victro_translate("New Host"); ?>:</label>
    <input type="text" class="form-control" id="nhost" onkeyup="ToDown(this);" onkeypress="fieldsAddon(this)">
  </div>
  <div class="form-group">
    <label for="model" style="width:100%;"><?php victro_translate("Model"); ?>:</label>
    <input type="text" class="form-control" readonly id="prefix" style="width:30%; display:inline-block; text-align:right" value="VICTRO_"><input onkeyup="ToUp(this);" onkeypress="fieldsAddon2(this)" style="width:70%; display:inline-block" type="text" class="form-control" id="model">
  </div>
  <button type="button" onclick="tryCon()" class="btn btn-default"><?php victro_translate("Try Connection"); ?></button>
</form>
<script>
function fieldsAddon(e1){
  $(e1).on('keypress', function(e) {
    var falseChar = [32, 47, 92, 44, 46, 33, 64,35, 36, 37, 94, 38, 42, 40, 41, 95, 45, 43, 61, 124, 63];
    if(falseChar.indexOf(e.which) != -1){
      return(false);
    }
    //console.log(e.which);
  });
  $(e1).val($(e1).val().toLowerCase());
}
function fieldsAddon2(e1){
  $(e1).on('keypress', function(e) {
    var falseChar = [32, 47, 92, 44, 46, 33, 64,35, 36, 37, 94, 38, 42, 40, 41, 95, 45, 43, 61, 124, 63];
    if(falseChar.indexOf(e.which) != -1){
      return(false);
    }
    //console.log(e.which);
  });
  $(e1).val($(e1).val().toUpperCase());
}
function ToDown(e1){
  $(e1).val($(e1).val().toLowerCase());
}
function ToUp(e1){
  $(e1).val($(e1).val().toUpperCase());
}
function tryCon(){
  var sign = prompt("Are you using the same network that your addon?");
  execAjax = false;
  while(sign.toLowerCase() != "yes") {
    sign = prompt("Are you using the same network that your addon?");
  }
  if(sign.toLowerCase() == "yes"){
    execAjax = true;
  }
  console.log(execAjax);
  if(execAjax == true){
    var actualHost = $('#host').val();
    var nowHTML = $('#victro-modal-body').html();
    $.ajax({
      type: "POST",
      global: false,
      url: SITE_URL+"sys/modal",
      //url: "http://"+actualHost+".local/config.vic",
      data: {type:"send_addon" ,TYPE: $('#wifi').val(), SSID: $('#ssid').val(), PASS: $('#pass').val(), HOST: $('#nhost').val(), MODEL: $('#model').val(), CONNECT: $("#host").val()},
      beforeSend: function(){
        execAjax == false;
        loading_idhtml('victro-modal-body');
      },
      success: function (data) {
        var returnedData = $.parseJSON(data);
        console.log(returnedData);
        if(returnedData.ERROR != true){
          alert("Success");
          $('#victro-modal-body').html(data);
        } else {
          alert("Error");
          $('#victro-modal-body').html(nowHTML);
        }
      },
      error: function (XMLHttpRequest, textStatus, errorThrown) {
        alert("Error: Cant find ADDON");
        $('#victro-modal-body').html(nowHTML);
      }
    });
  }
}
</script>
