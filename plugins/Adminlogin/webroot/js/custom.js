 $(document).ready(function(){    
     
    $(".read_more").on("click",function()
    { 
        $(".more_text").show();
        $(this).hide();
    });
    //Clear Filters
    $(".form-clear").on("click",function(){
        console.log('Clear form');
        $('form').clearForm();
    });
    

    $.fn.clearForm = function() {
      // iterate each matching form
      return this.each(function() {
        // iterate the elements within the form
        $(':input', this).each(function() {
          var type = this.type, tag = this.tagName.toLowerCase();
          if (type == 'text' || type == 'password' || tag == 'textarea')
            this.value = '';
          else if (type == 'checkbox' || type == 'radio')
            this.checked = false;
          else if (tag == 'select')
            this.selectedIndex = -1;
        });
      });
    };
    
 setTimeout(function() {   
        $('#message_success').fadeOut('fast');
     }, 4000); // <-- time in milliseconds

})