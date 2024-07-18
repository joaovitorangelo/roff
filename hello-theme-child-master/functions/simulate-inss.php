<?php
defined( 'ABSPATH' ) || exit;
function simulate_form( $fields ) {
  $data_inicial = $fields['construction_ini']['value']; // Mês de referência
  $data_final = $fields['construction_end']['value']; // Fim da obra
  $uf = $fields['state']['value']; // Estado
  $tipo_de_obra = $fields['constructionType']['value'];
  $metragem = $fields['totalArea']['value'];
  $metragem = floatval(str_replace(',', '.', str_replace('.', '', $metragem)));

  $args = array(
    'post_type' => 'vau',
    's'         => date('m/Y', strtotime( $data_final ))
  );

  $posts = get_posts( $args );
  if ( !empty($posts) ) {
    $vau = get_post_meta( $posts[0]->ID, 'vau', true );
    if ( !empty( $vau ) ) {
      // Valor do VAU
      $vau_value = $vau[$uf][$tipo_de_obra];
      $vau_value = floatval(str_replace(',', '.', str_replace('.', '', $vau_value)));
      // Identifificar equivalência
      $ped = get_option( 'ped', array() );
      $equivalencia = $ped[$tipo_de_obra];
      $valor_da_equivalencia = null;
      foreach ($equivalencia as $index => $values) {
        $min_value = floatval(str_replace(',', '.', str_replace('.', '', $values['min'])));
        $max_value = !empty($values['max']) ? floatval(str_replace(',', '.', str_replace('.', '', $values['max']))) : '';
        if ($metragem >= $min_value && $metragem <= $max_value) {
          $valor_da_equivalencia = floatval(str_replace(',', '.', str_replace('.', '', $values['fathor'] ) ) );
          $metragem_final = $metragem; 
          $metragem_final *= $valor_da_equivalencia / 100;
          // $metragem_final = floatval(str_replace(',', '.', str_replace('.', '', $metragem_final)));
          break;
        }
      }
      // Cálculo da remuneração da mão de obra VAU * Metragem final
      $calc = $vau_value * $metragem_final; // 487096110
      // Obter o fator 
      $fathor = $ped['FS'];
      $fathorval = null;
      foreach ($fathor as $i => $fval) {
        $fminval = $fval['min'];
        $fmaxval = $fval['max'];
        if ($metragem >= $fminval && $metragem <= $fmaxval) {
          $fathorval = floatval(str_replace(',', '.', str_replace('.', '', $fval['fathor'] ) ) );
          break;
        }
      }
      // Aplicar o fator ao cálculo
      $calc *= $fathorval / 100;
      // Calc da mão de obra
      $calc *= 20 / 100;
      // Calc do imposto
      $calc *= 36.8 / 100;
      // Calc do concreto usinado
      if ($fields['concreteType']['value'] == 'Sim') {
        $usi = 2 / 100;
        $calc_usi_ini = $calc * $usi;
        $calc_usi_end = $calc - $calc_usi_ini;  
        $calc_usi_end = number_format($calc_usi_end, 2, ',', '.'); // "number_format" Formata um número com milhares agrupados
        return $calc_usi_end;
      } else {
        $calc = number_format($calc, 2, ',', '.');
        // Retorna o valor sem o calculo do valor unsinado
        return $calc;
      }
    } else {
      return 'empty';
    }
  } else {
  }
  
  return;
}