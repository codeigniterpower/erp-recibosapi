<?php
/**!
 * @package   ReceiptAPI
 * @filename  lists.php
 * @version   1.0
 * @autor     Díaz Urbaneja Víctor Eduardo Diex <diazvictor@tutamail.com>
 * @date      22.11.2023 02:33:35 -04
 */

class api_v1_lists_model extends model {

  private $sql;
  private $err;

  public function notFound() {
    $this->borrow('notFound')->show();
  }

  public function get($page = 1) {
    $and = ' ';
    if (intval($_SESSION['id_profile']) == 2) {
      // ESTO ES COMPRADOR
      // @TODO: Como se cuales son mis recibos ?
      $and = '  ';
    }

    $page = intval($page);
    $limit = 5;
    $this->sql = 'select '
                  .'  count(id_recibo) as total '
                  .'from apirec_recibo where true ' . $and;
    $records = intval($this->db->execute($this->sql)[0]['total']);
    $offset = ($limit * ($page - 1));
    $pages = ceil ($records / $limit);
    $pagination = [
      "total_records" => $records,
      "total_pages"   => $pages,
      "current_page"  => $page,
      "next_page"     => ($records == 0) ? 1 : ($page + 1),
      "prev_page"     => ($records == 0) ? 1 : ($page - 1)
    ];

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
                .'order by apirec_recibo.cod_recibo desc limit %d offset %d';

    if (($results = $this->db->execute($this->sql, "", $limit, $offset)) === false) {
      $this->notFound();
      return false;
    }

    /*retorno la data*/
    header('Content-Type: application/json; charset=utf-8');
    print( json_encode([
      'ok'          => (intval($records) > 0),
      'data'        => $results,
      'pagination'  => $pagination
    ]));
  }

}
