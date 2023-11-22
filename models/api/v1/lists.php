<?php
/*
            ____                  _     _
           / ___|_   _  __ _  ___| |__ (_)
          | |  _| | | |/ _` |/ __| '_ \| |
          | |_| | |_| | (_| | (__| | | | |
           \____|\__,_|\__,_|\___|_| |_|_|
Copyright (c) 2014  Díaz  Víctor  aka  (Máster Vitronic)
Copyright (c) 2018  Díaz  Víctor  aka  (Máster Vitronic)
<vitronic2@gmail.com>   <mastervitronic@vitronic.com.ve>
*/

class api_v1_lists_model extends model {

  private $sql;
  private $err;

  public function notFound() {
    $this->borrow('notFound')->show();
  }

  public function get($page = null) {
    $and = ' ';
    if (intval($_SESSION['id_profile']) == 2) {
      $and = '  ';
    }
    $this->sql = 'select '
                .'  apirec_recibo.id_recibo, '
                .'  apirec_recibo.rif_agente, '
                .'  apirec_recibo.rif_sujeto, '
                .'  apirec_recibo.cod_recibo, '
                .'  apirec_recibo.num_recibo, '
                .'  apirec_recibo.num_control, '
                .'  apirec_recibo.fecha_factura, '
                .'  apirec_recibo.fecha_compra, '
                .'  apirec_recibo.monto_imponible, '
                .'  apirec_recibo.monto_excento, '
                .'  apirec_recibo.monto_iva, '
                .'  apirec_recibo.tasa_iva, '
                .'  apirec_recibo.tipo_recibo, '
                .'  case '
                .'      when apirec_recibo_adjunto.ruta != %s then '
                .'        apirec_recibo_adjunto.ruta '
                .'      else apirec_recibo_adjunto.adjunto '
                .'  end as adjunto '
                .'from apirec_recibo '
                .'  inner join apirec_recibo_adjunto on ( '
                .'    apirec_recibo_adjunto.id_recibo=apirec_recibo.id_recibo '
                .'  ) '
                .' where true ' .  $and
                .'order by apirec_recibo.id_recibo desc ';
    
    /*ejecuto la consulta*/
    if (($results = $this->db->execute($this->sql,"")) === false) {
      $this->notFound();
      return false;
    }
    /*retorno la data*/
    header('Content-Type: application/json; charset=utf-8');
    // print( json_encode($results) );
    print( json_encode([
      'ok'        => true,
      'data'      => $results
    ]));
  }

}
