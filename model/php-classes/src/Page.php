<?php

namespace Classes;

use Rain\Tpl;

class Page {

    private $tpl;
    private $options = [];


    public function __construct($opts=array(), $tpl_dir="/views/"){

        $this->options = $opts;

        $config = array(
            "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
            "cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
            "debug"         => false    //Comentarios de erros e testes
        );

        Tpl::configure( $config );

        $this->tpl = new Tpl;

        $this->setData($this->options);

        $this->tpl->draw("header");

    }


    private function setData($data=array()){

        foreach ($data as $key => $value) {
            $this->tpl->assign($key, $value);
        }


    }

    public function setTpl($name, $data = array(), $returnHTML = false){

        $this->setData($data);

        $_SESSION['mensagem'] = '';

        return $this->tpl->draw($name, $returnHTML);

    }


    public function __destruct(){

        $this->tpl->draw("footer");

    }

}