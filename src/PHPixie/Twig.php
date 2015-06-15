<?php

/**
 * Created by PhpStorm.
 * User: cyrill
 * Date: 15.06.15
 * Time: 19:38
 */

namespace PHPixie;

class Twig extends View {
  protected $_loader;
  protected $_twig;
  protected $_template;
  protected $_name;
  protected $_cache;
  protected $_extension = 'twig';
  protected function __construct($name, $cache = false) {
    parent::__construct($name);
    $this->_name = $name;
    $this->_cache = (!$cache) ? false : Config::get('twig.render_dir');

    if(!class_exists('Twig_Autoloader')){
      $file = Misc::find_file('vendor', 'Twig/Autoloader');
      if (!$file)
        throw new Exception('Could not find Twig.');
      require_once $file;
      Twig_Autoloader::register();
    }


    $this->_loader = new Twig_Loader_Filesystem('application/views/');
    if(!$this->_cache)
      $this->_twig = new Twig_Environment(
        $this->_loader,
        array(
          "cache"	=> $this->_cache
        )
      );
    $this->_twig->addExtension(new Twig_Extension_Escaper());
  }
  public function render() {
    ob_start();
    $this->_template = $this->_twig->loadTemplate($this->_name . "." . $this->_extension);

    echo $this->_template->render($this->_data);
    $out = ob_get_contents();
    ob_end_clean();
    return $out;
  }
}