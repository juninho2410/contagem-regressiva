<?php 
/*
Plugin Name: Contagem Regressiva - Coisas de Orlando
Plugin URI: 
Description: Cadastra e exibe futuros viajantes de Orlando
Version: 1.0
Author: Josщ Mendes
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
 define( 'CONTAGEMREGRESSIVA_PATH', plugin_dir_path(__FILE__));
 
 add_menu_page('Contagem Regressiva', 'Contagem Regressiva',10, CONTAGEMREGRESSIVA_PATH.'/contagem-regressiva-config.php');
 
 add_submenu_page(plugin_dir_path(__FILE__).'contagem-regressiva/contagem-regressiva-config.php', 'Sub pagina 01', 'titulo 01', 10, 'contagem-regressiva/contagem-regressiva-sub-pagina.php');
 
 }   
}
// Ativar o widget
  function contagem_regressiva()
  {
    // Adicionar o widget
     register_widget('FormWidget');
     register_widget('DisplayWidget');

    // Adicionar o controle ao widget
    //register_widget_control('Posts Mais Quentes', 'configurar_posts_mais_quentes');
  }
  function my_init() {
    if (!is_admin()) {
      wp_enqueue_script('jquery');
    }
  }
  $pathPlugin = substr(strrchr(dirname(__FILE__),DIRECTORY_SEPARATOR),1).DIRECTORY_SEPARATOR.basename(__FILE__);
  require_once ('contagem-regressiva-widget-form.php');
  require_once ('contagem-regressiva-widget-display.php');
  if(function_exists('add_action'))
    add_action('init', 'my_init');
   
    // Funчуo ativar
     if(function_exists('register_activation_hook'))
        register_activation_hook( $pathPlugin, array('ContagemRegressiva','ativar'));
     
    // Funчуo desativar
    if(function_exists('register_uninstall_hook'))
      register_uninstall_hook( $pathPlugin, array('ContagemRegressiva','desativar'));
      
    if(function_exists('add_action'))
      add_action('widgets_init', 'contagem_regressiva');
  
  
  
  //Aчуo de criar menu
  //add_action('admin_menu', array('ContagemRegressiva','criarMenu'));
   
  //Filtro do conteњdo
  //add_filter("the_content", array("ContagemRegressiva","adicionaFrase"));
 

?>