<?php
 //require_once("../../../wp-blog-header.php");
      
 require_once( "../../../wp-config.php" );
 global $wpdb;
 if(
  isset($_POST['nome']) && 
  isset($_POST['email']) && 
  isset($_POST['diaSaida']) && 
  isset($_POST['mesSaida']) && 
  isset($_POST['anoSaida']) &&
  isset($_POST['diaChegada']) && 
  isset($_POST['mesChegada']) && 
  isset($_POST['anoChegada'])
  ){
      $nome = trim(sanitize_text_field($_POST['nome']));
      $email =  trim(sanitize_text_field($_POST['email']));
      $dataSaida = sprintf("%4d-%02d-%02d",intval($_POST['anoSaida']),intval($_POST['mesSaida']),intval($_POST['diaSaida']));
      $dataChegada = sprintf("%4d-%02d-%02d",intval($_POST['anoChegada']),intval($_POST['mesChegada']),intval($_POST['diaChegada']));
      $tableName = $wpdb->prefix."VIAJANTES";
      if(!is_email($email))
          echo json_encode(array('status'=>'error','msg'=>"Email inválido"));

      $sql = $wpdb->prepare("SELECT * FROM $tableName WHERE EMAIL = '%s'",$email);
      $result = $wpdb->get_results($sql);
      if(count($result)==0){
          $data=array('NOME'=>$nome,'EMAIL'=>$email,'DATA_SAIDA'=>$dataSaida,'DATA_CHEGADA'=>$dataChegada);
          $format = array('%s','%s','%s','%s');
          if($wpdb->insert($tableName,$data,$format)){
            echo json_encode(array('status'=>'ok','msg'=>'Viagem cadastrada com sucesso!'));
          }
          else{
            echo json_encode(array('status'=>'error','msg'=>"$wpdb->print_error()"));
          }
      }
      else{
          echo json_encode(array('status'=>'error','msg'=>"Você já está cadastrado!"));
      }
      
      
        
  
  }
 //$resultados = $wpdb->get_results('Select * from'.$wpdb->prefix.'Viajante');
 //print_r($wpdb);
 

?>