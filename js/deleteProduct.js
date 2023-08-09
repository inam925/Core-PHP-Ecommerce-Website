$(document).ready(function () {
  var ids = [];
  $(".deleteCheckbox").click(function () {
    ids = [];
    $(".deleteCheckbox:checked").each(function () {
      id = $(this).val();
      ids.push(id);
    });
    console.log(ids);
  });

  $(".deleteBtn").click(function () {
    if (ids.length > 0) {
      // AJAX Request
      $.ajax({
        url: "delete.php",
        type: "POST",
        data: {
          id: ids,
        },
        success: function (response) {
          console.log("success");
          $.each(ids, function (i, l) {
            $(".i_" + l).remove();
            window.location = "shop.php?msg=deleteMultipleProducts";
          });
        },
      });
    } else {
      var id = $(this).closest("tr").attr("class").split("_")[1];
      var idArray = Array.from([id]);
      $.ajax({
        url: "delete.php",
        type: "POST",
        data: {
          id: idArray,
        },
        success: function (response) {
          console.log("success");
          $(".i_" + id).remove();
          window.location = "shop.php?msg=deleteProduct";
        },
      });
    }
  });
});
