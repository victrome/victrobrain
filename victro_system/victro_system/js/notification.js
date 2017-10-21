function get_notification(siteurl){
	setdata = '';
	$.ajax({
		type: "POST",
		global: false,
		url: siteurl+'system/notification',
		data: setdata,
		success: function (dados1) {
			$('#victro_get_notifications').html(dados1);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
		}
	});
}