$(document).ready(function () {
  $(".addProductBtn").click(function () {
    var fieldNames = [
      "name",
      "price",
      "picture",
      "quantity",
      "category",
      "description",
    ];
    var isValid = true;

    for (var i = 0; i < fieldNames.length; i++) {
      var fieldName = fieldNames[i];
      var fieldValue = $("input[name='" + fieldName + "']").val();
      var errorElement = $("#" + fieldName + "-error");

      if (fieldValue === "") {
        errorElement.text(
          "Please enter a value for the " + fieldName + " field."
        );
        errorElement.show();
        isValid = false;
      } else {
        errorElement.hide(); // Hide the error message
      }
    }

    if (isValid) {
      var productName = $("input[name='name']").val();
      var productPrice = $("input[name='price']").val();
      var productPicture = $("input[name='picture']").val().split("\\").pop();
      var productQuantity = $("input[name='quantity']").val();
      var productCategory = $("input[name='category']").val();
      var productDescription = $("textarea[name='description']").val();

      var formData = new FormData();
      formData.append("name", productName);
      formData.append("price", productPrice);
      formData.append("picture", productPicture);
      formData.append("quantity", productQuantity);
      formData.append("category", productCategory);
      formData.append("description", productDescription);

      // AJAX Request
      $.ajax({
        url: "add.php",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
          // Redirect to admin panel
          window.location = "shop.php?msg=insertProduct";
        },
      });
    }
  });
});
