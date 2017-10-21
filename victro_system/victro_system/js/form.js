$(function () {
    function removeCampo() {
        $(".removerCampo").unbind("click");
        $(".removerCampo").bind("click", function () {
            if($victro_("tr.linhas").length > 1){
                $(this).parent().parent().remove();
            }
        });
    }
    function removeCampo1() {
        $(".removerCampo1").unbind("click");
        $(".removerCampo1").bind("click", function () {
            if($victro_("tr.linhas1").length > 1){
                $(this).parent().parent().remove();
            }
        });
    }
    $(".addsubmenu").click(function () {
        novoCampo = $("tr.linhas1:first").clone();
        novoCampo.find("input").val("");
        novoCampo.insertAfter("tr.linhas1:last");
        removeCampo();

    });
    $(".adicionarCampo").click(function () {
        novoCampo = $("tr.linhas:first").clone();
        novoCampo.find("input").val("");
        novoCampo.insertAfter("tr.linhas:last");
        removeCampo();

    });
});