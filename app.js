// The URL to send the post request to.
var post_URL = '';

$('.btn').click(function (eventObject) {

   // Replace underscores with spaces
   var name = $(this).context.id.replace(/_/g, ' ');
   var success = function (data) {};

   // Send data to backend
   $.post(post_URL, name, success);
   // Remove element
   $(this).parent().parent().fadeOut(500);
});
