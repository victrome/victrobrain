function get_search(siteurl, search){
	setdata = 'SEARCH='+search;
	$.ajax({
		type: "POST",
		global: false,
		url: siteurl+'system/search',
		data: setdata,
		success: function (dados1) {
			$('#victro_search_result').html(dados1);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
		}
	});
}