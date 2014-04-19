       jQuery(function ($) {
       
        $('.btn-delete-viajante').click(function(e){
            var that = $(this);
                request = {
                  nonce: MyAjaxAdmin.nonce,
                  email: $(this).data('email'),
                  data: $(this).data('data-chegada')
                
                };
              jQuery.post(
                    // see tip #1 for how we declare global javascript variables
                    MyAjaxAdmin.ajaxurl,
                    {
                        // here we declare the parameters to send along with the request
                        // this means the following action hooks will be fired:
                        // wp_ajax_nopriv_myajax-submit and wp_ajax_myajax-submit
                        action : 'delete_traveler',
                 
                        // other parameters can be added along with "action"
                        data :request
                    },
                    function( data ) {
                          //data = JSON.parse(data);
                              if(data['status'] ==="ok"){
                                  alert(data['msg']);
                                  that.closest('tr').remove();
                             
                          
                              }
                              else{
                                alert('Erro: '+ data['msg']);
                              }
                    }
                );
                e.preventDefault();
        
        })
      })

