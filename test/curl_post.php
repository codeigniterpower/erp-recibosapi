#!/usr/bin/php
<?php

$login = 'diazvictor';
$password = 'vitronic';
$url = 'http://api.me/api/v1/upload/';

$fields = [
  "id_recibo"  => null,
  "rif_agente" => "V-31772274-0",
  "rif_sujeto" => "V-16010147-0",
  "cod_recibo" =>  "0000022",
  "num_recibo" =>  "0000022",
  "num_control" => "0000022",
  "fecha_factura" => "20231123",
  "fecha_compra" => "20231123",
  "monto_imponible" => "1000.00",
  "monto_excento" => "0.00",
  "monto_iva" => "160.00",
  "tasa_iva" => "1.99",
  "tipo_recibo" => "factura",
  "adjunto" => base64_encode(file_get_contents('Voucher-1536x901.jpg'))
];

//var_dump($fields);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_COOKIEJAR, '/tmp/cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, '/tmp/cookies.txt');
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
$data = curl_exec($ch);
curl_close($ch);
var_dump($data);

echo(PHP_EOL);
