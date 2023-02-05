<?php


namespace core\base\settings;


use core\base\controllers\Singleton;

class Settings
{
    use Singleton;
    private $routes = [
        'admin' => [
            'alias'=> 'admin',
            'path' => 'core/admin/controllers/',
            'hrUrl' => false,
            'routes' => []
        ],
        'settings' => [
          'path' => 'core/base/settings/'
        ],
        'plugins' => [
            'path' => 'core/plugins/',
            'hrUrl' => false,
            'dir' => 'controller',
            'routes' => []
        ],
        'user' => [
            'path' => 'core/user/controllers/',
            'hrUrl' => true,
            'routes' => [
                    'site' => 'index/hello',
//                'catalog' => 'site/input/output/'
            ]
        ],
        'default' => [
            'controller'=> 'IndexController',
            'inputMethod' =>'inputData',
            'outputMethod' => 'outputData'
        ]
    ];
    private $expansion = 'core/admin/expansions/';

    private $messages = 'core/base/messages/';
    
    private $defaultTable = 'goods';

    private $projectTables = [
        'catalog' => ['name'=>'Каталог'],
        'goods' => ['name'=> 'Товары','img'=> 'pages.png'],
        'filters' => [' name'=> 'Фильтры'],
        'articles' => ['name'=>'Статьи'],
        'information' => ['name'=> 'Информация'],
        'socials'=> ['name' => 'Социальные сети'],
        'settings' => ['name'=>'Системные настройки'],
    ];
    private $formTemplates = PATH . 'core/admin/views/include/form_templates/';

    private $translate = [
        'name' => ['Название', 'Не более 100 символов'],
        'visible' => ['Показывать на странице'],
        'menu_position' => ['Позиция в списке'],
        'keywords' => ['Ключевые слова','Не более 70 символов'],
        'content' => ['Описание'],
        'description' => ['SEO описание'],
        'phone' => ['Телефон'],
        'email' => ['Email'],
        'address' => ['Адрес'],
        'alias' => ['Ссылка ЧПУ'],
        'show_top_menu' => ['Показывать в верхнем меню'],
        'external_alias' => ['Внешняя ссылка']
    ];

    private $radio = [
        'visible' =>['Нет', 'Да', 'default' => 'Да'],
        'show_top_menu' =>['Нет', 'Да', 'default' => 'Да']
    ];

    private $rootItems = [
        'name' => 'Корневая',
        'tables' => ['articles', 'filters', 'catalog']
    ];

    private $templateArr = [
        'text' => ['name', 'phone', 'email', 'alias', 'external_alias'],
        'textarea' => ['keywords', 'content', 'address', 'description'],
        'radio' => ['visible', 'show_top_menu'],
        'checkboxlist' => ['filters'],
//        'checkBoxList' => ['filters'],
        'select' => ['menu_position', 'parent_id'],
        'img' => ['img','main_img'],
        'gallery_img' => ['gallery_img', 'new_gallery_img']
    ];
    private  $fileTemplates = ['img', 'gallery_img'];

    private $manyToMany = [
        'goods_filters' => ['goods', 'filters'] //, 'type'=>'root' || 'child'
    ];

    private $blockNeedle = [
        'vg-rows' => [],
        'vg-img' => ['img','main_img'],
        'vg-content' => ['content']
    ];

    private $validation = [
        'name' =>['empty'=>true, 'trim'=>true],
        'price' => ['int'=>true],
        'login' => ['empty'=>true, 'trim'=>true],
        'password' => ['crypt'=>true, 'empty' => true],
        'keywords' => ['count'=>70, 'trim'=>true],
        'description' => ['count'=>160, 'trim'=>true]
    ];

    private $mail = [
        'mail_text' => ['short', 'price', 'name_mail'],
        'mail_textarea' => ['goods_content']
    ];

    static public function get($property){
        return self::instance()->$property;
    }

    public function glueProperties($class){
        $baseProperties = [];
        foreach($this as $name => $item){
            $property = $class::get($name);
            if(is_array($property) && is_array($item)){
                $baseProperties[$name] = $this->arrayMergeRecursive($this->$name, $property);
                continue;
            }
                if(!$property) $baseProperties[$name] = $this->$name;
        }
        return $baseProperties;
    }

    public function arrayMergeRecursive(){
        $arrays = func_get_args();
        $base = array_shift($arrays);
        foreach ($arrays as $array){
            foreach ($array as $key=>$value){
                if(is_array($value)&& is_array($base)){
                    $base[$key] = $this->arrayMergeRecursive($base[$key], $value);
                } else {
                    if(is_int($key)){
                        if(!in_array($value, $base)) array_push($base, $value);
                        continue;
                    }
                    $base[$key] = $value;
                }
            }
        }
        return $base;
    }
}
//base admin 375