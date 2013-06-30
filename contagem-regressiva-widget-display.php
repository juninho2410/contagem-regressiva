<?php

/**
 * Widget de Display de Futuros Viajantes a Orlando
 * 
 * @author José Mendes <junior@nuvem111.com.br>
 */
class DisplayWidget extends WP_Widget {
	
	/**
	 * Construtor
	 */
	public function DisplayWidget() {
		parent::WP_Widget(false, $name = 'Display de Datas de Viagem');
	}
	
	/**
	 * Exibição final do Widget (já no sidebar)
	 *
	 * @param array $argumentos Argumentos passados para o widget
	 * @param array $instancia Instância do widget
	 */

	public function widget($argumentos, $instancia) {

		// Exibe o HTML do Widget
		echo $argumentos['before_widget'];
		echo $argumentos['before_title'] . $instancia['title_widget'] . $argumentos['after_title'];
    $pathPlugin = substr(strrchr(dirname(__FILE__),DIRECTORY_SEPARATOR),1).DIRECTORY_SEPARATOR;
    setlocale(LC_ALL, "pt_BR", "pt_BR.UTF8", "pt_BR.utf-8", "portuguese-brazilian");
    date_default_timezone_set('America/Sao_Paulo');
    global $wpdb; 
    $tableName = $wpdb->prefix ."VIAJANTES";
    $datas = $wpdb->get_results("SELECT DATA_CHEGADA FROM $tableName GROUP BY DATA_CHEGADA ORDER BY DATA_CHEGADA ");
    $viagens=$wpdb->get_results("SELECT * FROM $tableName ORDER BY DATA_CHEGADA");
    $dados=array();
    for($i=0;$i<count($datas);$i++){
            $sql = $wpdb->prepare("SELECT NOME FROM $tableName WHERE DATA_CHEGADA = %s",$datas[$i]->DATA_CHEGADA);
            $nomes= $wpdb->get_results($sql);
            $dados[$i]=array('nomes'=>$nomes,'data'=>$datas[$i]->DATA_CHEGADA);
    }
    /*$data = str_replace("-","/",$data);
		$data = explode ("/", $data);
		return  $data[2] . "/" . $data[1] . "/". $data[0];*/
    echo '<ul>';
          foreach( $dados as $dado) {
             $time_inicial = strtotime(date('Y-m-d'));
             $time_final = strtotime($dado['data']);
             $diferenca =  $time_final - $time_inicial;
             $dias = (int)floor( $diferenca / (60 * 60 * 24));
             if($dias >=0){
                 echo'<li>';
                 echo '<div class="title">';
                 if($dias ==0)
                    echo "Embarca Hoje!";
                 else if($dias ==1)
                    echo "Embarca Amanha!";
                 else
                    echo "Faltam $dias dias";
                 echo '</div>';
                 
                $j=0;
                foreach($dado['nomes'] as $nome){
                    echo $nome->NOME;
                    if($j!=(count($dado['nomes']) -1)){
                      echo ',';
                    }
                    $j++;
                }             
              echo '</li>';
            }
         }
        echo '</ul>';
    ?>
    
    
    <?php
		echo $argumentos['after_widget'];
	}
	
	/**
	 * Salva os dados do widget no banco de dados
	 *
	 * @param array $nova_instancia Os novos dados do widget (a serem salvos)
	 * @param array $instancia_antiga Os dados antigos do widget
	 * 
	 * @return array $instancia Dados atualizados a serem salvos no banco de dados
	 */
	public function update($nova_instancia, $instancia_antiga) {
		$instancia = array_merge($instancia_antiga, $nova_instancia);
		
		return $instancia;
	}
	
	/**
	 * Formulário para os dados do widget (exibido no painel de controle)
	 *
	 * @param array $instancia Instância do widget
	 */
	public function form($instancia) {
		$widget['title_widget'] = (string)$instancia['title_widget'];
		?>
		<p><label for="<?php echo $this->get_field_id('title_widget'); ?>"><input id="<?php echo $this->get_field_id('title_widget'); ?>" name="<?php echo $this->get_field_name('title_widget'); ?>" type="text" value="<?=$widget['title_widget'];?>" /> <?php _e('Título do Widget'); ?></label></p>
		<?php	
	}
	
}


?>
