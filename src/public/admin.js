$(function () {
    var activeTab = $("ul.tabs a.active");

    $(".tab h2:first-child").hide();
    $(".tab").hide();
    $(activeTab.attr("href")).show();

    $("form#periods input[type='submit']").hide();
});

$(document).on("click", "#menu-button", function (e) {
    $("#menu").toggleClass("shown");
});

$(document).on("click", "ul.tabs li a", function (e) {
    var activeTab = $(this).parents("ul.tabs").find(".active");

    activeTab.removeClass("active");
    $(activeTab.attr("href")).hide();

    $(this).addClass("active");
    $(this.hash).show();

    e.preventDefault();
});

$(document).on("change", "select#periodID", function (e) {
    location.href = "?periodID=" + $(this).val();
});
