$(document).ready(function () {
  const minus = $(".quantity__minus");
  const plus = $(".quantity__plus");
  const input = $(".quantity__input");

  minus.click(function (e) {
    e.preventDefault();
    var inputElement = $(this).siblings(".quantity__input");
    var value = parseInt(inputElement.val());
    var maxValue = parseInt(inputElement.attr("max"));

    if (value > 1 && value <= maxValue) {
      value--;
      inputElement.val(value);
    } else if (value === 1) {
      inputElement.val(value);
    }

    // Update the hidden input field with the updated value
    inputElement.attr("value", value);

    // Submit the form
    $(this).closest("form").submit();
  });

  plus.click(function (e) {
    e.preventDefault();
    var inputElement = $(this).siblings(".quantity__input");
    var value = parseInt(inputElement.val());
    var maxValue = parseInt(inputElement.attr("max"));

    if (value < maxValue) {
      value++;
      inputElement.val(value);
    }

    // Update the hidden input field with the updated value
    inputElement.attr("value", value);

    // Submit the form
    $(this).closest("form").submit();
  });

  $("#cart-submit").click(function () {
    window.location = "checkout.php";
  });
});
