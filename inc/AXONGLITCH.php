<?php 

function generateMenuTemplates($menuName) {
    $content = '
    <ax-elements 
    mode="dropdown"
    headTitlecolor="#FFF4A3"
    height="70"
    color="#282A35"
    colorHover="#fff"
    activeBackground="#282A35"
    headBackground="#0000"
    headBackgroundHover="#04AA6D"
    structure="dropdownGroup"
    title="Menu"
    background="#cbcbcb"
    subOpening="sub"
    subTrigger="click">';
        
    $menus = wp_get_nav_menus();
    foreach ( $menus as $menu ) :
        $menuData = wp_get_nav_menu_object($menu->name);
        if(get_field("location_on_theme", $menuData) == $menuName) :
            $structureContent = "";
            foreach(get_field('structure', $menuData) as $structure) $structureContent .= "$structure ";
            $content .= "<ax-elements 
            mode='dropdown'
            exit='". get_field('exit', $menuData) ."'
            headTitle='". get_field("headtitle", $menuData) ."'
            headTitlecolor='". get_field("headtitlecolor", $menuData) ."'
            height='". get_field("height", $menuData) ."'
            color='". get_field("color", $menuData) ."'
            colorHover='". get_field("colorHover", $menuData) ."'
            activeBackground='". get_field("activeBackground", $menuData) ."'
            headBackground='". get_field("headBackground", $menuData) ."'
            headBackgroundHover='". get_field("headBackgroundHover", $menuData) ."'
            structure='". $structureContent ."'
            title='". get_field("title", $menuData) ."'
            background='". get_field("background", $menuData) ."'
            targetLocator='". strtolower(str_replace(' ', '_', get_field("headtitle", $menuData))) ."_targetLocator'
            subOpening='". get_field("subopening", $menuData) ."'
            subTrigger='". get_field("subtrigger", $menuData) ."'
            options='". json_encode(wordpressAXDropdownContent(wp_get_nav_menu_items($menuData->name))) ."'></ax-elements>";
        endif; endforeach;
    $content .= '</ax-elements>';

    return $content;
}

// handler menu items to export a json in format of required in AXG library
function wordpressAXDropdownContent($data) {
    $level=0;
    $menuObj=array();
    $menuObj2=array();
    for($i=0; $i<count($data); $i++) {
        $object = new stdClass();
        $object->title = $data[$i]->title;
        $object->url = $data[$i]->url;
        $data[$i]->menu_item_parent==0?$object->level = "undertab":null;
        if($data[$i]->menu_item_parent == 0) {
            $object->color = "#FFF4A3";
            $menuObj[$data[$i]->ID] = $object;
        } else {
            // $object->color = "#fff";
            $menuObj[$data[$i]->menu_item_parent]->content[] = $object;
        }
    }
    foreach ($menuObj as $value) $menuObj2[] = $value;
    return $menuObj2;
}
