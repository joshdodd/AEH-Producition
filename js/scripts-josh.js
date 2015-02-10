//READY
$(document).ready(function() {

  /*********************SMOOTH SCROLL************************/


      //SMOOTH SCROLL
      $("#people").smoothDivScroll({
        startAtElementId: "starter",
      hotSpotScrollingInterval: 33,
      touchScrolling: true,
      });

      $('.people_box').mouseenter(function()
        {
          $(this).find(".p_hover").show();
          }).mouseleave(function() {
            $(this).find(".p_hover").hide();
        });

 
   $("#real-form").click(function(e) {
      e.preventDefault();
      var formData = {
        'real-id'  : $('input#real-id').val()
      };
      var request = $.ajax({
        url: "/wp-content/themes/EssentialHospitals/templates/includes/real-track.php",
        type: "POST",
        data    : formData
      });


         $("#real-form").submit();


      request.fail(function(jqXHR, textStatus) {
        alert( "Request failed: " + textStatus );
      });
         
    }

  );
 
 

//END DOC
});
 