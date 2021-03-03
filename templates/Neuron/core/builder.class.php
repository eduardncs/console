<?php

define("MENU_BASE",['<ul class="nav navbar-nav">',
                    '<li class="nav_item"><a href="%s" target="%s">%s</a></li>',
                    '</ul>'
                ]);
define("MENU_TREE",['<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">%s <b class="fas fa-chevron-down"></b></a>
                     <ul class="dropdown-menu" aria-labelledby="navbarDropdown">',
                    '<li><a class="dropdown-item" href="%s" target="%s">%s</a></li>',
                    '</ul></li>'
                ]);
class Builder extends Main
{
    public function buildHead($title = null)
    {
        $skeleton_header = '<!DOCTYPE html>
        <html lang="en">
        <head>

        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">';
        $skeleton_body = '
        <title>%s</title>
        <meta name="description" content="%s">
        <meta name="author" content="%s">
        <link rel="icon" href="%s">';
        $skeleton_footer = '</head>';
        $meat = $this->getinfo();
        $layout = $this->getWidgetJSON('layout');
        $template = [];
        $url = $_SERVER['REQUEST_URI'];
        $url = str_ireplace("post/","",$url);
        $template[] = "<base href='".$url."'>";
          //Add css first
          foreach($layout['Css'] as $scss)
          {
            key_exists($scss['Integrity']) ? $template[] = "<link rel='stylesheet' type='text/css' href='".$scss['Href']."' integrity='".$scss['Integrity']."' crossorigin='".$scss['Crossorigin']."'>"
                                           : $template[] = "<link rel='stylesheet' type='text/css' href='".$scss['Href']."'>";
          }
          //then fonts
          foreach($layout['Fonts'] as $font)
          {
            $template[] = "<link rel='stylesheet' type='text/css' href='".$font['Href']."'>";
          }
          if($title == null)
            $title = $meat['Title'];
          $description = $meat['Meta']['Description'];
          $author = $meat['Meta']['Author'];
          $favicon = $meat['Favicon'];
          $formatted_body = sprintf($skeleton_body,$title,$description,$author,$favicon);
          foreach($template as $css)
          {
            $formatted_body .= $css;
          }
          return $skeleton_header.$formatted_body.$skeleton_footer;
    }
    public function buildBody()
    {
      $skeleton_header = "<body>";
      $skeleton_body = $this->buildMenu();
      $skeleton_footer = "";

      return $skeleton_header.$skeleton_body.$skeleton_footer;
    }
    public function buildMenu()
    {
        $skeleton_header_base = MENU_BASE[0];
        $skeleton_header_tree = MENU_TREE[0];

        $skeleton_body_base = MENU_BASE[1];
        $skeleton_body_tree = MENU_TREE[0];
        $skeleton_body_inner = MENU_TREE[1];

        $skeleton_footer_base = MENU_BASE[2];
        $skeleton_footer_tree = MENU_TREE[2];

        $meat = $this->getWidgetJSON("menu");
        $classes = $meat['Style']["Scheme"]." ".$meat['Style']["Background"];

        $raw_menu = $meat['Menu'];
        $formatted_body = '';

        foreach($raw_menu as $m)
        {
          if($m['P_Key'] == "0" || $m['P_Key'] == 0)
          {
            $formatted_body .= $skeleton_body_base;
            $formatted_body = sprintf($formatted_body,$m['Href'],$m['Target'],$m['Text']);
          }
          elseif($m['P_Key'] == "1" || $m['P_Key'] ==1)
          {
            $formatted_body .= $skeleton_body_tree;
            $formatted_body = sprintf($formatted_body,$m['Text']);
            foreach($raw_menu as $c)
            {
              if($c['P_Key'] == $m['Key']){
                $formatted_body .= $skeleton_body_inner;
                $formatted_body = sprintf($formatted_body,$c['Href'],$c['Target'],$c['Text']);
              }
            }
            $formatted_body .= $skeleton_footer_tree;
          }
        }

         if(empty($raw_menu))
        {
          $formatted_body .= $skeleton_body;
          $formatted_body = sprintf($formatted_body, "javascript:void(0)","_self","No items added into the menu");
        } 
        return $skeleton_header_base.$formatted_body.$skeleton_footer_base;
    }

    public function buildSocial()
    {
        $skeleton_header = '<ul class="social-icon" style="display:inline-block;" editable="editable" datapanel="socialmenu">';
        $skeleton_body = '<li><a href="%s" class="fab fa-%s"></a></li>';
        $skeleton_footer = ' </ul>';
        $formatted_body ="";
        $meat = $this->getWidgetJSON("social");
        foreach($meat['SocialMedia'] as $link){
            $formatted_body .= $skeleton_body;
            $formatted_body = sprintf($formatted_body,$link['Link'], lcfirst($link['Icon']));
        }

        return $skeleton_header.$formatted_body.$skeleton_footer;
    }

    public function buildJS()
    {
      $return = "";
      foreach($layout['Js'] as $js)
      {
        key_exists("Integrity") ? $return .= "<script src='".$js['Href']."' integrity='".$js['Integrity']."' crossorigin='".$js['Crossorigin']."'></script>" : $return = "<script src='".$js['Href']."'></script>";
      }
      return $return;
    }
}
?>
