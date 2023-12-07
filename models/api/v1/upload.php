<?php
/**!
 * @package   ReceiptAPI
 * @filename  upload.php
 * @version   1.0
 * @autor     Díaz Urbaneja Víctor Eduardo Diex <diazvictor@tutamail.com>
 * @date      22.11.2023 01:03:17 -04
 */

class api_v1_upload_model extends model {

  private $sql;
  private $err;

  public function notFound() {
    $this->borrow('notFound')->show();
  }

  /**
   * Crea el directorio en el cloud
   * @return string
   */
  private function create_cloud_dir() {
    $dir = sprintf('%scloud/%s/%s/%s/', ROOT, date('Y'),date('m'),date('d'));
    mkdir($dir, 0777, true);
    return $dir ;
  }

  /**
   * Decodifica un base64 y lo guarda en el filesystem
  */
  private function save_file($b64,$filepath) {
    $ifp = fopen( $filepath, 'wb' ); 
    $data = explode(',', $b64);
    if (isset($data[1])){
      fwrite( $ifp, base64_decode($data[1]));
    } else {
      fwrite( $ifp, base64_decode($data[0]));
    }
    fclose($ifp);
  }

  /**
   * Crea el ID loco de PICCORO posta no es loco.. con esto identificas varias cosas, fecha, donde cuando 
   *    ah y de paso se ordena solo ya que nunca dara unnumero menor a menos este el sistema trampeado
   * @return string YYYYMMDDHHmmss
   */
  private function mkid() {
    return date('YmdHis');
  }

  private function save_ok($post) {
    $result = true; /*esto es true a menos que algo salga mal*/
    $validator   = new validator(); /*inicializo la clase de validacion*/
    /*los campos a validar en este arreglo, creo que se explica solos*/
    $validations = [
      'rif_agente' => [
        'type'      => 'string',
        "required"  => true
      ],
      'fecha_recibo' => [
        'type'      => 'string',
        "required"  => true
      ],
      'monto_recibo' => [
        'type'      => 'float',
        "required"  => true
      ],
      'monto_excento' => [
        'type'      => 'float',
        "required"  => true
      ],
      'monto_iva' => [
        'type'      => 'float',
        "required"  => true
      ],
      'tasa_iva' => [
        'type'      => 'float',
        "required"  => true
      ],
      'tipo_recibo' => [
        'type'      => 'enum',
        'values'    => ['factura', 'nota'],
        "required"  => true
      ],
      'adjunto' => [
        'type'      => 'string',
        "required"  => true
      ]
    ];

    if (!empty($post["rif_sujeto"])) {
      $validations = $validations + [
        'num_recibo' => [
          'type'      => 'string',
          "required"  => true
        ],
        'num_control' => [
          'type'      => 'string',
          "required"  => true
        ],
      ];
    }

    $keys = [
      "id_recibo",
      "cod_recibo",
      "num_recibo",
      "num_control",
    ];
    /*esto es una edicion por que id_recibo existe*/
    if (isset($post["id_recibo"])) {
      $validations = $validations + [
        'id_recibo' => [
          'type'         => 'string',
          "required"     => true,
          'maxlen'       => 14,
          'minlen'       => 14,
        ]
      ];
      $exist = $this->db->exist(
        'apirec_recibo', $keys,
        $post, 'id_recibo', $post["id_recibo"]
      );
    } else {
      $exist = $this->db->exist(
        'apirec_recibo', $keys, $post
      );
    }

    if ($exist) {
      $this->err = "El campo " . $exist . " ya existe.";
      $result = false;
    }

    /*hago todas las validaciones*/
    $check = $validator->execute($validations);
    if ($check[0] === false ) {
      $this->err = $check[1];
      $result = false;
    }

    return $result;
  }

  public function save($post) {
    $update = false;
    $this->err  = false;
    $permission = $this->auth->getPermission('upload', $_SESSION['id_user']);
    header('Content-Type: application/json; charset=utf-8');

    if (isset($post['id_recibo']) and intval($post['id_recibo'])) {
      $update = true;
      if (is_false($permission['update'])){
        $this->err = 'Sin permisos para actualizar';
      }
    } else {
      if (is_false($permission['write'])) {
        $this->err = 'Sin permisos de escritura';
      }
    }

    if ($this->save_ok($post) === false || $this->err) {
      print(json_encode([
        'ok'        => false,
        'msg'       => $this->err,
        'id_recibo' => false
      ]));
      return;
    }

    /*los campos a guardar*/
    $keys = [
      "id_recibo",
      "rif_agente",
      "rif_sujeto",
      "cod_recibo",
      "num_recibo",
      "num_control",
      "fecha_recibo",
      "fecha_compra",
      "monto_imponible",
      "monto_excento",
      "monto_iva",
      "tasa_iva",
      "tipo_recibo"
    ]; // @TODO: No se que son los *flags*

    /*inicio la transaccion*/
    if ($this->db->query("begin") == false) {
      $this->notFound();
      return false;
    }

    if ($update) {
      $id_recibo = $post['id_recibo'];
      /*hago el update*/
      if ($this->db->update("apirec_recibo", $id_recibo, 'id_recibo', $post, $keys) === false) {
        $this->db->query("rollback");
        $this->notFound();
        return false;
      }
    } else {
      $post["id_recibo"] = $this->mkid();
      /*hago el insert*/
      if ($this->db->insert("apirec_recibo", $post, $keys) === false) {
        $this->db->query("rollback");
        $this->notFound();
        return false;
      }

      $id_recibo = $post["id_recibo"];
    }

    if (!empty($post["adjunto"])) {
      $this->db->delete('apirec_recibo_adjunto', 'id_recibo', $id_recibo);
      $updir = $this->create_cloud_dir();
      $filepath = $updir .  $id_recibo;

      $keys = ["id_recibo", "adjunto", "ruta"]; // @TODO: No se que son los *flags*
      $values = [
        "id_recibo" => $id_recibo,
        "adjunto"   => $post["adjunto"],
        "ruta"      => $filepath
      ];

      if ($this->db->insert("apirec_recibo_adjunto", $values, $keys) === false) {
        $this->db->query("rollback");
        $this->notFound();
        return false;
      }

      $this->save_file($post["adjunto"], $filepath);
    }

    /*finalmente hago el commit y retorno*/
    if ($this->db->query("commit") != false) {
      print(json_encode([
        'ok'        => true,
        'msg'       => 'Guardado con exito!',
        'id_recibo' => $id_recibo
      ]));
      return;
    }

    print(json_encode([
      'ok'        => false,
      'msg'       => 'Err: Will Robinson!',
      'id_recibo' => false
    ]));
  }

}
