$(function () {
    $("article.trick").slice(0, 10).show();
    $("#loadMoreTrick").on("click", function (e) {
        e.preventDefault();
        $("article.trick:hidden").slice(0, 10).slideDown();
        if ($("article.trick:hidden").length === 0) {
            $("#loadMoreTrick").hide("slow");
            $("#loadLessTrick").show("slow");
        }
    });
    $("#loadLessTrick").on("click", function (e) {
        e.preventDefault();
        $("article.trick").slice(10, $("article.trick").length).hide();
        $("#loadLessTrick").hide("slow");
        $("#loadMoreTrick").show("slow");
    });
});

$(function () {
    $("div.comment").slice(0, 4).show();
    $("#loadMoreComment").on("click", function (e) {
        e.preventDefault();
        $("div.comment:hidden").slice(0, 4).slideDown();
        if ($("div.comment:hidden").length === 0) {
            $("#loadMoreComment").hide("slow");
        }
    });
});