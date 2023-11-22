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

class api_v1_lists_controller extends controller {

  public function execute() {
    /*esta variable recoje los parametros de la url,
     *es un array y el indice 0 indica el primer parametro*/
    $parameters = ($this->router->parameters) ? $this->router->parameters : false;

    if (
      $parameters[0] and $parameters[0] == 'pages' and
      valid_input($parameters[1], VALIDATE_NUMBERS, VALIDATE_NONEMPTY)
    ) {
      $this->model->get($parameters[2]);
      return;
    }

    $this->model->get();
    // $this->model->notFound();
  }

}
