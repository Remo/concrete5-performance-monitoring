$(document).ready(function () {
   function checkAppName () {
      var $selectedAppName = $("input[name=appName]:checked");
      if ($selectedAppName.val() === "CUSTOM") {
         $("input[name=appNameValue]").show().focus();         
      }
      else {
         $("input[name=appNameValue]").hide();
      }
   }

   checkAppName();

   $("#ccm-dashboard-content").on("click", "input[name=appName]", function (event) {
      checkAppName();
   });
});