$(document).ready(function () {
  $("#choose-file").change(function () {
    var i = $(this).prev("label").clone();
    var file = $("#choose-file")[0].files[0].name;
    $(this).prev("label").text(file);
  });
});
