<?php

function loading_page_get_screen_list(){
    $dir = LOADING_PAGE_PLUGIN_DIR.'/loading-screens';
    $screens = array();
    if(file_exists($dir)){
        $d = dir($dir);
        while (false !== ($entry = $d->read())) {
            if($entry != '.' && $entry != '..' && is_dir($dir.'/'.$entry)){
                $screen_dir = $dir.'/'.$entry.'/';
                if(file_exists($screen_dir.'config.ini')){
                    $c = parse_ini_file($screen_dir.'config.ini', true);
                    
                    if(!empty($c['script'])){
                        $c['script'] = LOADING_PAGE_PLUGIN_URL.'/loading-screens/'.$entry.'/'.$c['script'];
                    }
                    
                    if(!empty($c['style'])){
                        $c['style'] = LOADING_PAGE_PLUGIN_URL.'/loading-screens/'.$entry.'/'.$c['style'];
                    }
                    
                    $screens[] = $c;
                    
                }
            }	
        }
        $d->close();
    }

    return $screens;	
}

function loading_page_get_screen($id){
    $screens = loading_page_get_screen_list();
    if(!empty($screens)){
        foreach($screens as $s){
            if($s['id'] == $id)
                return $s;
        }
        return $screens[0];
    }
    return false;
}
?>