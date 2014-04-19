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
   wp_enqueue_script('admin-script-co',plugins_url('js/admin.js' , __FILE__ ),array( 'jquery' ));
   wp_localize_script('admin-script-co', 'MyAjaxAdmin', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) , 'nonce'=>wp_create_nonce( 'MyAjaxAdminNonce' ) ) ); // setting ajaxurl
if ( current_user_can('manage_options')) {
 
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
                <a href="#" data-data-chegada="<?=$viajante->DATA_CHEGADA;?>"  data-email="<?=$viajante->EMAIL;?>" class="btn-delete-viajante">Excluir</a>
              </td>
            </tr>
     <?php endforeach; ?>
     </tbody>
     <tfoot>
        <tr>
          <td colspan="3">Total de cadastrados: <?=count($viajantes);?></td>
        </tr>
     </tfoot>
</table>
<?php }
else{
    echo "Você não tem permissão para acessar esta tela";
} ?>