<?php

/**
 * Widget de Formulário de Inscrição para viagens a orlando
 * 
 * @author José Mendes <junior@nuvem111.com.br>

 */
if(class_exists('WP_Widget')){
    class FormWidget extends WP_Widget {
      
      /**
       * Construtor
       */
      public function FormWidget() {
        parent::WP_Widget(false, $name = 'Formulario de Inscrição');
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

        
        ?>
        <div id="my-content-id" style="display:none;">
        <form method="post" action="<?=plugin_dir_url(__FILE__);?>save.php" id="contagemRegressiva">
          <h4>Cadastre sua viagem</h4>
          <p><label for="nome">Nome:</label><input type="text" name="nome" id="nome" /></p>
          <p><label for="email">Email:</label><input type="text" name="email" id="email" /></p>
           <p><label for="diaChegada">Chegada:</label><br/>
          <select name="diaChegada" id="diaChegada">
            <?php for($i=1;$i<=31;$i++){ ?>
                <option value="<?=$i;?>" <?=$i==date('j')? "selected":"";?>><?=sprintf('%02d',$i);?></option>
            <?php } ?>
          </select>
          <select name="mesChegada" id="mesChegada">
             <?php for($i=1;$i<=12;$i++){ ?>
              <option value="<?=$i;?>" <?=$i==date('n')? "selected":"";?>><?=utf8_encode(strftime("%B",mktime(0,0,0,$i,1)));?></option>
              <?php } ?>
          </select>
          <select name="anoChegada" id="anoChegada">
            <?php for($i=date('Y');$i<=date('Y')+5;$i++){ ?>
              <option value="<?=$i;?>" <?=$i==date('Y')? "selected":"";?>><?=$i;?></option>
              <?php } ?>
          </select>
          </p>
          <p><label for="diaSaida">Saída:</label><br/>
          <select name="diaSaida" id="diaSaida">
          <?php for($i=1;$i<=31;$i++){ ?>
                <option value="<?=$i;?>" <?=$i==date('j')? "selected":"";?>><?=sprintf('%02d',$i);?></option>
            <?php } ?>
          </select>
          <select name="mesSaida" id="mesSaida" value="<?=date('n');?>">
              <?php for($i=1;$i<=12;$i++){ ?>
              <option value="<?=$i;?>" <?=$i==date('n')? "selected":"";?>><?=utf8_encode(strftime("%B",mktime(0,0,0,$i,1)));?></option>
              <?php } ?>
          </select>
          <select name="anoSaida" id="anoSaida">
              <?php for($i=date('Y');$i<=date('Y')+5;$i++){ ?>
              <option value="<?=$i;?>" <?=$i==date('Y')? "selected":"";?>><?=$i;?></option>
              <?php } ?>
          </select>
          </p>
         
          <p><input type="submit" class="submit" value="Enviar" /></p>
        
        </form>
        </div>
        
        <a href="#TB_inline?width=300&height=350&inlineId=my-content-id" class="thickbox">
            <?php if($instancia['link_image_banner']!="") {?>
              <img src="<?=$instancia['link_image_banner'];?>" alt=" Contagem regressiva cadastre-se aqui!"/>
            <?php } else{?>
            Contagem regressiva cadastre-se aqui!
            <?php }?>
        </a>	
        
        <?php
        echo $argumentos['after_widget'];
        wp_enqueue_script('validateForm',plugins_url('validateForm.js' , __FILE__ ),array( 'jquery' ));
        add_thickbox();


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
        <p><label for="<?php echo $this->get_field_id('link_image_banner'); ?>"><input id="<?php echo $this->get_field_id('link_image_banner'); ?>" name="<?php echo $this->get_field_name('link_image_banner'); ?>" type="text" value="<?=$widget['link_image_banner'];?>" /> <?php _e('Link Imagem do Banner'); ?></label></p>
        <?php	
      }
      
    }
}
?>
