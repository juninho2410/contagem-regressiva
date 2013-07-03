<?php
 
 require_once( "../../../wp-load.php" );
 global $wpdb;
 $tableName = $wpdb->prefix ."VIAJANTES";
 if(isset($_GET['email']) && isset($_GET['data'])){
      $email = trim(sanitize_text_field($_GET['email']));
      $data = trim(sanitize_text_field($_GET['data']));

      if($email == NULL && $data == NULL){
          echo json_encode(array('status'=>'error','msg'=>"Email ou data não enviado"));
          return false;
      }
      
      $sql = $wpdb->prepare("DELETE FROM $tableName WHERE EMAIL = '%s' AND DATA_CHEGADA = '%s'",$email,$data);
      $result = $wpdb->query($sql);
      
      if($result > 0){
            echo json_encode(array('status'=>'ok','msg'=>'Viajante Excluído com sucesso!'));
      }
      else{
          echo json_encode(array('status'=>'error','msg'=>"Erro ao excluir Viajante!"));
      }
  }

?>