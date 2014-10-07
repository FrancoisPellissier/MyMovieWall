<?php
namespace library;

class View {
    private $data;
    private $layout;
    private $view;
    
    /**
     * View::__construct()
     * 
     * @param string $module
     * @param string $view
     * @return void
     */
    public function __construct($module, $view) {
        
        $this->view = 'modules/'.ucwords($module).'/view/'.$view.'.tpl.php';

        /*
        if(!file_exists($this->view))
        {
            header('Location: index.php');
            exit;
        } 
        */   
        $this->layout();
    }
         
    /**
     * View::layout()
     * 
     * @return void
     */
    private function layout() {
            $this->layout = 'assets/layouts/layout.tpl.php';    
    }
    
    /**
     * View::with()
     * 
     * @param string $var
     * @param string $value
     * @return void
     */
    public function with($var, $value) {
        $this->data[$var] = $value;
    }
    
    /**
     * View::make()
     * 
     * @return void
     */
    public function make() {
        global $pun_user, $domaine, $site_config;
        
        if(!empty($this->data)) {
            foreach($this->data AS $var => $value)
                ${$var} = $value;
        }
        $view = $this->view;
        include($this->layout);
    }
}
?>