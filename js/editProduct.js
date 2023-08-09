$(document).ready(function () {
  $(".editProductBtn").click(function () {
    console.log("ola");
    var productid = $("input[name='id']").val();
    var productName = $("input[name='name']").val();
    var productPrice = $("input[name='price']").val();
    var productPicture = $("input[name='picture']").val().split("\\").pop();
    var productQuantity = $("input[name='quantity']").val();
    var productCategory = $("input[name='category']").val();
    var productDescription = $("textarea[name='description']").val();

    var formData = new FormData();
    formData.append("id", productid);
    formData.append("name", productName);
    formData.append("price", productPrice);
    formData.append("picture", productPicture);
    formData.append("quantity", productQuantity);
    formData.append("category", productCategory);
    formData.append("description", productDescription);

    // AJAX Request
    $.ajax({
      url: "edit.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        console.log("success");
        window.location = "shop.php?msg=updateProduct";
      },
    });
  });
});
