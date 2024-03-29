<?php
    
    namespace core\user\controllers;
    
    use core\admin\models\Model;
    use core\base\controllers\BaseController;

    abstract class BaseUser extends BaseController
    {
        protected $model;
        protected $table;
        protected $set;
        protected $menu;
//        Project settings
        protected $socials;
        
        protected function inputData(){
        
        $this->init();
        !$this->model && $this->model = Model::instance();
        
        $this->set = $this->model->get('settings', [
            'order' => ['id'],
            'limit' => 1
        ]);
        
        $this->set && $this->set = $this->set[0];
        
        $this->menu['catalog'] = $this->model->get('catalog', [
           'where'=>['visible'=> 1, 'parent_id'=>null],
           'order'=>['menu_position']
        ]);
        
        $this->menu['information'] = $this->model->get('information', [
            'were' => ['visible'=>1, 'show_top_menu'=>1],
            'order' => ['menu_position']
        ]);
        
        $this->socials = $this->model->get('socials', [
            'were' => ['visible'=>1],
            'order' => ['menu_position']
            ]);
        
        }
        protected function outputData(){
    
            if(!$this->content){
                $args = func_get_arg(0);
                $vars = $args ? $args : [];

                $this->content = $this->render ($this->template, $vars);
            }
    
            $this->header =  $this->render(TEMPLATE . 'include/header', $vars);
            $this->footer =$this->render(TEMPLATE . 'include/footer', $vars);
    
            return $this->render(TEMPLATE . 'layout/default');
        
        }
        
        protected function img($img ='', $tag = false){
        
            if (!$img && is_dir($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . DEFAULT_IMAGE_DIRECTORY)){
                
                $dir = scandir($_SERVER['DOCUMENT_ROOT'] . PATH . UPLOAD_DIR . DEFAULT_IMAGE_DIRECTORY);
                
                $imgArr = preg_grep('/' . $this->getController() . '\./i', $dir) ?: preg_grep('/default\./i', $dir);
                
                $imgArr && $img = DEFAULT_IMAGE_DIRECTORY . '/' . array_shift($imgArr);
            
            }
            
            if ($img){
                
                $path = PATH . UPLOAD_DIR . $img;
    
                if (!$tag){
                    return $path;
                }
                echo '<img src = "' . $path . '" alt="image" title="image">';
            }
            return '';
        }
        
        protected function alias($alias = '', $queryString = ''){
            
            $str = '';
            
            if ($queryString) {
    
                if (is_array($queryString)) {
        
                    foreach ($queryString as $key => $item) {
            
                        $str .= (!$str ? '?' : '&');
            
                        if (is_array($item)) {
                
                            $key .= '[]';
                
                            foreach ($item as $value)
                             
                                $str .= $key . '=' . $value;
                            
                            }else{
        
                                $str .= $key . '=' . $item;
                            }
                        }
                    }else{
                    
                    if (strpos($queryString, '?') === false)
                        
                        $str = '?' . $str;
                    
                    $str .= $queryString;
                   }
                }
            if (is_array($alias)){
                
                $aliasStr = '';
                
                foreach ($alias as $key => $item){
                    if (!is_numeric($key)&& $item){
                        
                        $aliasStr .= $key . '/' . $item . '/';
                        
                    }elseif ($item){
                        $aliasStr .= $item . '/';
                    }
                }
                $alias = trim($aliasStr, '/');
            }
            if (!$alias || $alias === '/')
                
                return PATH . $str;
            if (preg_match('/^\s*https?:\/\//i', $alias))
                
                return $alias . $str;
            
            return preg_replace('/\/{2,}/', '/', PATH . $alias . END_SLASH . $str);
            }
            
            
        protected function wordsForCounters ($counter, $arrEllement = 'years'){

            $arr = [
                'years' => [
                    'лет',
                    'год',
                    'года'
                ]
            ];

            if (is_array($arrEllement)){
                
                $arr = $arrEllement;
            }else{
                
                $arr = $arr[$arrEllement] ?? array_shift($arr);
            }
            
            if(!$arr)
                
                return null;
            
            $char = (int)substr($counter, -1);
            
            $counter = (int)substr($counter, -2);
            
            if (( $counter >= 10 && $counter <= 20) || ($char >=5 && $char <= 9) || !$char)
                return $arr[0] ?? null;
            
                elseif ($char===1)
                    return $arr[1] ?? null;
                else
                    return $arr[2] ?? null;
                    
        }
        
        
        
        }
        
