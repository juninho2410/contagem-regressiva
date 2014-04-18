<?php 
/*
Plugin Name: Contagem Regressiva - Coisas de Orlando
Plugin URI: https://github.com/juninho2410/contagem-regressiva
Description: Cadastra e exibe futuros viajantes de Orlando
Version: 1.0
Author: José Mendes
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
/*FUNÇÃO PARA SALVAR OS DADOS NO FORMULÁRIO*/
function save_form(){
   
     $nonce = $_POST['data']['nonce']; 
     $data= $_POST['data'];
     
     global $wpdb;
     if(
        isset($data['nome']) && 
        isset($data['email']) && 
        isset($data['diaSaida']) && 
        isset($data['mesSaida']) && 
        isset($data['anoSaida']) &&
        isset($data['diaChegada']) && 
        isset($data['mesChegada']) && 
        isset($data['anoChegada']) &&
        wp_verify_nonce($nonce,"MyAjaxNonce")
        ){
            $nome = trim(sanitize_text_field($data['nome']));
            $email =  trim(sanitize_text_field($data['email']));
            $dataSaida = sprintf("%4d-%02d-%02d",intval($data['anoSaida']),intval($data['mesSaida']),intval($data['diaSaida']));
            $dataChegada = sprintf("%4d-%02d-%02d",intval($data['anoChegada']),intval($data['mesChegada']),intval($data['diaChegada']));
            $tableName = $wpdb->prefix."VIAJANTES";
            $er = "/^[a-zA-Z0-9]+/";
            header( "Content-Type: application/json" );
            if(!preg_match($er,$nome)){
                echo json_encode(array('status'=>'error','msg'=>"Há caracteres inválidos.\nNão são permitidos caracteres como $#@¨&*"));
                die();
            }
            if(is_email($nome)){
                echo json_encode(array('status'=>'error','msg'=>"Não é possível cadastrar e-mail como nome. Cadastre um nome que aparecerá no site"));
                die();
            }
            if(!is_email($email)){
                echo json_encode(array('status'=>'error','msg'=>"Email inválido"));
                die();
            }
            $sql = $wpdb->prepare("SELECT * FROM $tableName WHERE EMAIL = '%s' AND DATA_CHEGADA >= DATE(NOW())",$email);
   
            $result = $wpdb->get_results($sql);
            if(count($result)==0){
                $data=array('NOME'=>$nome,'EMAIL'=>$email,'DATA_SAIDA'=>$dataSaida,'DATA_CHEGADA'=>$dataChegada);
                $format = array('%s','%s','%s','%s');
                if($wpdb->insert($tableName,$data,$format)){
                  echo json_encode(array('status'=>'ok','msg'=>'Viagem cadastrada com sucesso!'));
                  die();
                }
                else{
                  $msg = utf8_encode("ERRO no INSERT: $wpdb->print_error()");
                  echo json_encode(array('status'=>'error','msg'=>$msg));
                  die();
                }
            }
            else{
                $msg = utf8_encode("Você já está cadastrado!");
                echo json_encode(array('status'=>'error','msg'=>$msg));
                die();
            }
            
            
              
        
        }
     //die();// wordpress may print out a spurious zero without this - can be particularly bad if using json
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
      add_action( 'wp_ajax_nopriv_save_form', 'save_form' ); // need this to serve non logged in users
      
    }
    add_action( 'wp_ajax_save_form', 'save_form' );
  }
 
  $pathPlugin = substr(strrchr(dirname(__FILE__),DIRECTORY_SEPARATOR),1).DIRECTORY_SEPARATOR.basename(__FILE__);
  require_once ('contagem-regressiva-widget-form.php');
  require_once ('contagem-regressiva-widget-display.php');
  if(function_exists('add_action'))
    add_action('init', 'my_init');
   
    // Função ativar
     if(function_exists('register_activation_hook'))
        register_activation_hook( $pathPlugin, array('ContagemRegressiva','ativar'));
     
    // Função desativar
    if(function_exists('register_uninstall_hook'))
      register_uninstall_hook( $pathPlugin, array('ContagemRegressiva','desativar'));
      
    if(function_exists('add_action'))
      add_action('widgets_init', 'contagem_regressiva');
  
  
  
  //Ação de criar menu
    if(function_exists('add_action'))
      add_action('admin_menu', array('ContagemRegressiva','criarMenu'));
   
  //Filtro do conteúdo
  //add_filter("the_content", array("ContagemRegressiva","adicionaFrase"));
 

?>