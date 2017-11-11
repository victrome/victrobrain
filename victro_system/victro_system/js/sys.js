function open_addon(){
  $('#victro-modal-system').modal('show');
  $('#victro-modal-title').html('Addon');
  new_addon();
}
function list_addons(){
  $.ajax({
		type: "POST",
		global: false,
		url: SITE_URL+'sys/modal',
		data: {type: 'listmodal'},
    beforeSend: function(){
      loading_idhtml('victro-modal-body');
    },
		success: function (data) {
			$('#victro-modal-body').html(data);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
      alert(textStatus);
		}
	});
}
function new_addon(){
  $.ajax({
		type: "POST",
		global: false,
		url: SITE_URL+'sys/modal',
		data: {type: 'newaddon'},
    beforeSend: function(){
      loading_idhtml('victro-modal-body');
    },
		success: function (data) {
			$('#victro-modal-body').html(data);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
      alert(textStatus);
		}
	});
}
function loading_idhtml(victro_idhtml){
  $('#'+victro_idhtml).html('<div class="loader"></div><div class="text-center">'+translate("Loading")+'...</div>');
}
function translate(victro_text){
  return victro_text;
}
