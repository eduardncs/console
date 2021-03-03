<?php
//Define the template layouts
//Template Fashion Blog
//Defining Header rules
define("HEADER", 
                  ['<!DOCTYPE html>
                  <html lang="en">
                  <head>
                  <meta charset="UTF-8">
                  <meta http-equiv="X-UA-Compatible" content="IE=Edge">',
                  '<title>%s</title>
                  <meta name="description" content="%s">
                  <meta name="author" content="%s">
                  <link rel="icon" href="%s">',
                  '</head>'
                  ]);
define("BODY", ['<body style="overflow-x:hidden;">','<div id="ajax"></div>','</body></html>']);
//Defining social rules
define("SOCIAL", 
                  [ '<div class="w3ls-social-icons">',
                    '<a href="%s"><i class="fab fa-%s"></i></a>',
                    '</div>'
                  ]);
//Defining menu rules
define("MENU_BASE",['<ul class="navbar-nav ml-auto _R3xd13dsc" editable="editable" data-panel="menu" data-panelid="_R3xd13dsc">',
                    '<li class="nav-item %s" data-link="%s"><a class="nav-link" href="%s" target="%s">%s</a></li>',
                    '</ul>'
                ]);
define("MENU_TREE",['<li class="nav-item %s dropdown"  data-link="%s">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">%s</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">',
                    '<a class="dropdown-item %s" data-link="%s" href="%s" target="%s">%s</a>',
                    '</div></li>'
                ]);
class Builder extends Main
{
  public function buildHead($title = null)
  {
      $skeleton_header = HEADER[0];
      $skeleton_body = HEADER[1];
      $skeleton_footer = HEADER[2];
      $meat = $this->getinfo();
      $layout = $this->getWidgetJSON('layout');
      $template = [];
      $url = $_SERVER['REQUEST_URI'];
      $url = str_ireplace("post/","",$url);
      $template[] = "<base href='".$url."'>";
        //Add css first
        foreach($layout['Css'] as $scss)
        {
          key_exists("Integrity",$scss) ? $template[] = "<link rel='stylesheet' type='text/css' href='".$scss['Href']."' integrity='".$scss['Integrity']."' crossorigin='".$scss['Crossorigin']."'>\n"
                                         : $template[] = "<link rel='stylesheet' type='text/css' href='".$scss['Href']."'>\n";
        }
        //then fonts
        foreach($layout['Fonts'] as $font)
        {
          $template[] = "<link rel='stylesheet' type='text/css' href='".$font['Href']."'>\n";
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
      $skeleton_header = BODY[0];
      $skeleton_body = BODY[1];
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
        if(array_key_exists("Style",$meat))
          $classes = $meat['Style']["Scheme"]." ".$meat['Style']["Background"];
        else $classes = "";

        $raw_menu = $meat['Menu'];
        $formatted_body = '';

        foreach($raw_menu as $m)
        {
          if($m['P_Key'] == "0" || $m['P_Key'] == 0)
          {
            //Solo elements
            $formatted_body .= $skeleton_body_base;
            $formatted_body = sprintf($formatted_body,$m['Key'],$m['Key'],$m['Href'],$m['Target'],$m['Text']);
          }
          elseif($m['P_Key'] == "1" || $m['P_Key'] == 1)
          {
            $formatted_body .= $skeleton_body_tree;
            $formatted_body = sprintf($formatted_body,$m['Key'],$m['Key'],$m['Text']);
            if(array_key_exists("Children",$m))
            {
              for($i=0; $i < count($m['Children']); $i++)
              {
                $c = $m['Children'][$i];
                $formatted_body .= $skeleton_body_inner;
                $formatted_body = sprintf($formatted_body,$c['Key'],$c['Key'],$c['Href'],$c['Target'],$c['Text']);
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
        $skeleton_header = SOCIAL[0];
        $skeleton_body = SOCIAL[1];
        $skeleton_footer = SOCIAL[2];
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
      $layout = $this->getWidgetJSON('layout');
      $base = "<script src='%s'></script>";
      $return ="";
      foreach($layout['Js'] as $js)
      {
        $return .= sprintf($base,$js['Href']);
      }
      return $return;
    }
}
?>
