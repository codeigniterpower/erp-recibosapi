<?php
/**!
 * @package   ReceiptAPI
 * @filename  search.php
 * @version   1.0
 * @autor     Díaz Urbaneja Víctor Eduardo Diex <diazvictor@tutamail.com>
 * @date      23.11.2023 21:14:35 -04
 */

class api_v1_search_model extends model {

  private $sql;
  private $err;

  public function notFound() {
    $this->borrow('notFound')->show();
  }

  public function get($search, $page = 1) {
    $where = ' where 1=1  and '
              .'(' /* this filter is bad, the or and and are mutually exclusive */
              .'  apirec_recibo.id_recibo like %s or '
              .'  apirec_recibo.rif_agente like %s or '
              .'  apirec_recibo.rif_sujeto like %s or '
              .'  apirec_recibo.cod_recibo like %s or '
              .'  apirec_recibo.num_recibo like %s or '
              .'  apirec_recibo.num_control like %s or '
              .'  apirec_recibo.fecha_recibo like %s or '
              .'  apirec_recibo.fecha_compra like %s or '
              .'  apirec_recibo.tipo_recibo like %s '
              .')';
    $and = ' ';
    if (intval($_SESSION['id_profile']) == 2) {
      // ESTO ES COMPRADOR
      // @TODO: Como se cuales son mis recibos ?
      $and = '  ';
    }

    $s = "%$search%";
    $values = [$s,$s,$s,$s,$s,$s,$s,$s,$s];
    $limit = 5;
    $this->sql = 'select '
                  .'  count(id_recibo) as total '
                  .'from apirec_recibo '. $where . $and;

    $page = intval($page);
    $records = intval($this->db->execute($this->sql,$values)[0]['total']);
    $offset = ($limit * ($page - 1));
    $pages = ceil ($records / $limit);
    $pagination = [
      "total_records" => $records,
      "total_pages"   => $pages,
      "per_page"      => $limit,
      "current_page"  => $page,
      "next_page"     => ($records == 0) ? 1 : ($page + 1),
      "prev_page"     => ($records == 0) ? 1 : ($page - 1)
    ];

    $this->sql = 'select '
                  .'apirec_recibo.id_recibo, '
                  .'apirec_recibo.rif_agente, '
                  .'apirec_recibo.rif_sujeto, '
                  .'apirec_recibo.cod_recibo, '
                  .'apirec_recibo.num_recibo, '
                  .'apirec_recibo.num_control, '
                  .'apirec_recibo.fecha_recibo, '
                  .'apirec_recibo.fecha_compra, '
                  .'apirec_recibo.monto_recibo, '
                  .'apirec_recibo.monto_excento, '
                  .'apirec_recibo.tipo_recibo, '
                  .'case '
                  .'   when apirec_recibo_adjunto.ruta != %s then '
                  .'     apirec_recibo_adjunto.ruta '
                  .'   else apirec_recibo_adjunto.adjunto '
                  .' end as adjunto '
                  .'from apirec_recibo '
                  .'  inner join apirec_recibo_adjunto on ( '
                  .'    apirec_recibo_adjunto.id_recibo=apirec_recibo.id_recibo '
                  .'  ) '
                  . $where .  $and
                  ." order by apirec_recibo.id_recibo limit $limit offset $offset";
    if (($results = $this->db->execute($this->sql, '', $values)) === false) {
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
