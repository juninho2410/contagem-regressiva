<?php 
  global $wpdb;
  function convertData($data){
    $data = str_replace("-","/",$data);
        $data = explode ("/", $data);
   return  $data[2] . "/" . $data[1] . "/". $data[0];
  
  }
  function getViajantes(){
      global $wpdb;
      $tableName = $wpdb->prefix ."VIAJANTES";
      $result=$wpdb->get_results("SELECT * FROM $tableName");
      return $result;
  }
   wp_enqueue_script('jquery');

?>
<h2>Viagens Cadastradas</h2>

<table class="wp-list-table widefat fixed">
    <thead>
      <tr>
        <td>Nome</td>
        <td>Email</td>
        <td>Data Chegada</td>
        <td>Data Saída</td>
        <td>Ações</td>
      </tr>
     <tbody>
     <?php $viajantes = getViajantes();
           foreach($viajantes as $viajante) : ?>
            <tr>
              <td><?=$viajante->NOME;?></td>
              <td><?=$viajante->EMAIL;?></td>
              <td><?=convertData($viajante->DATA_CHEGADA);?></td>
              <td><?=convertData($viajante->DATA_SAIDA);?></td>
              <td>                
                <a href="<?=plugin_dir_url(__FILE__);?>delete.php?email=<?=$viajante->EMAIL;?>&data=<?=$viajante->DATA_CHEGADA;?>" class="btn-delete-viajante">Excluir</a>
              </td>
            </tr>
     <?php endforeach; ?>
     </tbody>
</table>
<script type="text/javascript">
       jQuery(function ($) {
       
        $('.btn-delete-viajante').click(function(e){
            var that = $(this);
            $.ajax({
                  type: "GET",
                  url:  $(this).attr('href'),
                  dataType: "json",
                  processData :false,            
                  beforeSend: function () {
                   
                  },
                  success: function(data) {
               
                    if(data['status'] ==="ok"){
                      alert(data['msg']);
                      that.closest('tr').remove();
                      return false;
                    }
                    else{
                      alert('Erro: '+data['msg']);
                      return false;
                    }
                  
                  },
                  error: function (request) {
                      alert('Erro: '+data['msg']);
                      return false;      
                  }
                });
                return false;
        
        })
      })
</script>