<?php
/**
* Plugin Name: Product import
* Plugin URI: https://www.your-site.com/
* Description: Product import.
* Version: 0.1
* Author: Artur Vilkicki
* Author URI: https://www.your-site.com/
**/

// Add menu
function customplugin_menu() {

  add_menu_page("Product import", "Product import","manage_options", "myplugin", "uploadfile",plugins_url('/customplugin/img/icon.png'));
  // add_submenu_page("myplugin","Upload file", "Upload file","manage_options", "uploadfile", "uploadfile");

}

add_action("admin_menu", "customplugin_menu");

function uploadfile(){
  include "uploadfile.php";
}
