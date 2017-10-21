function victro_ajax(actionurl, data, div){
	setdata = 'data='+data;
	$.ajax({
		type: "POST",
		global: false,
		url: actionurl,
		data: setdata,
		success: function (dados1) {
			$(div).html(dados1);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown){
			alert('Error: Review your victro_code');
		}
	});
}