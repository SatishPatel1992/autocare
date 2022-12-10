<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class MenuModel extends CI_Model {

    public function get_menu($level, $parent, $cnt) {
        $this->db->select('*');
        $this->db->from('tbl_menu');
        $this->db->join('tbl_menu_rights','tbl_menu_rights.menu_id=tbl_menu.menu_id','left');
        $this->db->where('tbl_menu_rights.user_id',$_SESSION['data']->user_id);
        $this->db->where('level',$level);
        $this->db->where('parent_id',$parent);
        $this->db->where('have_rights','Y');
        $this->db->where('tbl_menu_rights.is_active','1');
        $this->db->where('tbl_menu.is_active','1');
        $this->db->order_by('order,parent_id');
        $getAllMenus = $this->db->get()->result_array();

        foreach ($getAllMenus as $menu) {
                //to add active class
                // $current_page = explode('/', $_SERVER['REQUEST_URI']);
                //submenu query
                $submenu_where = "level = '" . ($menu['level'] + 1) . "' AND parent_id = '" . $menu['menu_id'] . "' AND tbl_menu.is_active = '1'";

                $this->db->select('*');
                $this->db->from('tbl_menu');
                $this->db->join('tbl_menu_rights','tbl_menu_rights.menu_id=tbl_menu.menu_id','left');
                $this->db->where($submenu_where);
                $this->db->where('tbl_menu_rights.is_active',1);
                $submenu_query = $this->db->get()->result_array();
                $submenu_num_row = count($submenu_query);
                $is_sub_menu = '';
                if ($submenu_num_row > 0) { 
                    $is_sub_menu = 'has-sub';
                }
                if($cnt == 0) {
                    $menu_html = '<ul class="site-menu">';
                }
                $menu_html .= '<li class="site-menu-item '.$is_sub_menu.'">';
                // $acive_menu = explode('&', $current_page[2]);
                
                $menu_html .= "<a class='page_".$menu['menu_id']."' href=";
                if ($submenu_num_row > 0) {
                   $menu_html .= '#';
                } else {
                   $menu_html .= "'".$menu['alias']."'";
                }
                $menu_html .= '>';
                $menu_html .= '<i class="site-menu-icon ' . $menu['icon_class'] . '"></i><span class="site-menu-title">'.$menu['name'].'</span>';
                if ($submenu_num_row > 0) {
                  $menu_html .= ' <span class="site-menu-arrow"></span>';
                }
                $menu_html .= '</a>';
                if ($submenu_num_row > 0) {
                   $menu_html .= '<ul class="site-menu-sub">';
                   $menu_html .= $this->get_menu($level + 1, $menu['menu_id'], 1); 
                   //$menu_html .= '</ul>'; 
                 } 
                 $menu_html .= '</li>';
                 $cnt++;}
                 $menu_html .= '</ul>';
                 return $menu_html;
    }
}
