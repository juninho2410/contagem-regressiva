<?php 
/*
Plugin Name: Contagem Regressiva - Coisas de Orlando
Plugin URI: https://github.com/juninho2410/contagem-regressiva
Description: Cadastra e exibe futuros viajantes de Orlando
Version: 1.1
Author: Josй Mendes
Author URI: 
*/
class ContagemRegressiva{

 public function ativar(){
     global $wpdb;  
     $ddl = "CREATE TABLE ".$wpdb->prefix."VIAJANTES(ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,NOME VARCHAR(100) NULL,EMAIL VARCHAR(150) NULL,DATA_SAIDA DATE, DATA_CHEGADA DATE)";
     
     $wpdb->query($ddl); 
    
 }
 public function desativar(){
    global $wpdb;
    $ddl = "DROP TABLE ".$wpdb->prefix."VIAJANTES";
    $wpdb->query($ddl); 
 }
 public function criarMenu(){ 
    add_menu_page('Contagem Regressiva', 'Contagem Regressiva','10', 'contagem-regressiva/contagem-regressiva-config.php'); 
    add_submenu_page('contagem-regressiva/contagem-regressiva-config.php', 'Cadastrados', 'Cadastrados', 10, 'contagem-regressiva/contagem-regressiva-cadastrados.php');
 
 }   
}

   /* APAGAR OS USUARIOS CADASTRADOS NA BASE DA CONTAGEM REGRESSIVA*/
   function delete_traveler(){
       global $wpdb;
       $data = $_POST['data'];
     $tableName = $wpdb->prefix ."VIAJANTES";
     header( "Content-Type: application/json" );
     if(isset($data['email']) && isset($data['data'])){
          $email = trim(sanitize_text_field($data['email']));
          $data = trim(sanitize_text_field($data['data']));

          if($email == NULL && $data == NULL){
            $msg=utf8_encode("Email ou data nгo enviado");
              echo json_encode(array('status'=>'error','msg'=>$msg));
              die();
          }
          
          $sql = $wpdb->prepare("DELETE FROM $tableName WHERE EMAIL = '%s' AND DATA_CHEGADA = '%s'",$email,$data);
          $result = $wpdb->query($sql);
          
          if($result > 0){
                $msg=utf8_encode("Viajante Excluнdo com sucesso!");
                echo json_encode(array('status'=>'ok','msg'=>$msg));
          }
          else{
              $msg = utf8_encode("Erro ao excluir Viajante!");
              echo json_encode(array('status'=>'error','msg'=>$msg));
          }
      }
      die();
   
   }
// Ativar o widget
  function contagem_regressiva()
  {
    // Adicionar o widget
     register_widget('FormWidget');
     register_widget('DisplayWidget');
     
  }
  function my_init() {
    if (!is_admin()) {
      wp_enqueue_script('jquery');
      add_action( 'wp_ajax_nopriv_save_form', 'save_form' ); 
      /* need this to serve non logged in users. Function in file contagem-regressiva-widget-form.php */
      
    }
    /* need this to serve logged in users. Function in file contagem-regressiva-widget-form.php */
    add_action( 'wp_ajax_save_form', 'save_form' );
    
    add_action( 'wp_ajax_delete_traveler', 'delete_traveler' );
  }
 
  $pathPlugin = substr(strrchr(dirname(__FILE__),DIRECTORY_SEPARATOR),1).DIRECTORY_SEPARATOR.basename(__FILE__);
  require_once ('contagem-regressiva-widget-form.php');
  require_once ('contagem-regressiva-widget-display.php');
  if(function_exists('add_action'))
    add_action('init', 'my_init');
   
    // Funзгo ativar
     if(function_exists('register_activation_hook'))
        register_activation_hook( $pathPlugin, array('ContagemRegressiva','ativar'));
     
    // Funзгo desativar
    if(function_exists('register_uninstall_hook'))
      register_uninstall_hook( $pathPlugin, array('ContagemRegressiva','desativar'));
      
    if(function_exists('add_action'))
      add_action('widgets_init', 'contagem_regressiva');
  
  
  
  //Aзгo de criar menu
    if(function_exists('add_action'))
      add_action('admin_menu', array('ContagemRegressiva','criarMenu'));
   
  //Filtro do conteъdo
  //add_filter("the_content", array("ContagemRegressiva","adicionaFrase"));
 

?>