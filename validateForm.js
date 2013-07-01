
        jQuery(function ($) {
            $('#contagemRegressiva').on('submit',function(){
                var nome=$('#nome').val();
                var email = $('#email').val();
                var diaChegada = $('#diaChegada').val();
                var mesChegada = $('#mesChegada').val();
                var anoChegada = $('#anoChegada').val();
                var diaSaida = $('#diaSaida').val();
                var mesSaida = $('#mesSaida').val();
                var anoSaida = $('#anoSaida').val();
                
                var dataChegada =  diaChegada+"/"+mesChegada+"/"+anoChegada;
                //var dataChegada =  anoChegada+"-"+mesChegada+"-"+diaChegada;
                var dataSaida =  diaSaida+"/"+mesSaida+"/"+anoSaida;
                //var dataSaida =  anoSaida+"-"+mesSaida+"-"+diaSaida;
                
                
                if( nome== "" || email==""){
                    alert('Preencha o nome e o e-mail!');
                    return false;
                }
                else if(validateEmail($('#email').val())==false){
                    alert('Preecha um e-mail válido!');
                    return false;
                }
                else if(!validateDate(dataChegada) || !validateDate(dataSaida)){
                    alert('Data inválida! Por favor digite uma data válida');
                    return false;
                }
                else{
            
                $.ajax({
                  type: "POST",
                  url:  $(this).attr('action'),
                  data:  $(this).serialize(),
                  dataType: "json",
                  processData :false,            
                  beforeSend: function () {
                   
                  },
                  success: function(data) {
               
                    if(data['status'] ==="ok"){
                      alert('Viagem cadastrada com sucesso!');
                      $('#nome').val('');
                      $('#email').val('');
                      tb_remove();
                      location.reload(1);
                      
                    }
                    else{
                      alert('Erro: '+data['msg']);
                    }
                  
                  },
                  error: function (request) {
                          
                          
                          }
                });
                            return false;
              } 
                        
          })
        })
        function validateEmail($email) {
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
            if( !emailReg.test( $email ) ) {
                return false;
            } else {
                return true;
            }
          }
        function validateDate(date) {
           var regex = /^((((0?[1-9]|1\d|2[0-8])\/(0?[1-9]|1[0-2]))|((29|30)\/(0?[13456789]|1[0-2]))|(31\/(0?[13578]|1[02])))\/((19|20)?\d\d))$|((29\/0?2\/)((19|20)?(0[48]|[2468][048]|[13579][26])|(20)?00))$/;
           
           resultado = regex.test(date);
           if(!resultado) {
             return false;
           }
           else {
            return true;
           }
          }//fim da função
    