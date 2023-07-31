/**
 * Here we are converting the phone number from normal text to (xxx) xxx-xxxx
 * format. And when giving other than 10 digit phone number the format again
 * converted to normal text. And also disable to submit field if not giving
 * valid phone number.
 */
(function ($) {
  Drupal.behaviors.phoneNumber = {
    attach: function (context) {
      if (!Drupal.behaviors.phoneNumber.attached) {
        Drupal.behaviors.phoneNumber.attached = true;

        var phoneNumberField = $('#edit-field-phone-0-value', context);
        var numberRegex = /^\d+$/;
        
        phoneNumberField.on('input', function () {
          phoneNumberField.next('.phone-message').remove();
          var phone = phoneNumberField.val();

          // Checking if the phone number is valid. Then convert the number to
          // (xxx) xxx-xxxx format.
          if (numberRegex.test(phone) && phone.length == 10) {
            var formatted = phone.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
            phoneNumberField.val(formatted);
            phoneNumberField.css('border', '2px solid green');
            $('#edit-submit').prop('disabled', false);
          }
          else {
            // Convert the text to normal format if giving other than 10 digit
            // phone number.
            var unformatted = phone.replace(/[() -]/g, '');
            phoneNumberField.val(unformatted);
            $('#edit-submit').prop('disabled', true);

            // Showing the error message after the phone number field.
            var message = $('<div class="phone-message">Please provide valid 10 digit phone number</div>');
            message.css("color", "red");
            message.css("margin-top", "5px");
            phoneNumberField.css('border', '1px solid black');
            phoneNumberField.after(message);
          }
        });
      }
    }
  };

})(jQuery, Drupal);
