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
define("BODY", ['<body>',
                '</body></html>']);
//Defining social rules
define("SOCIAL", 
                  [ '<div class="main-social-footer-29">',
                    '<a class="nav-link" href="%s"><span class="fab fa-%s"></span></a>',
                    '</div>'
                  ]);
//Defining menu rules
define("MENU_BASE",['<ul class="navbar-nav ml-auto">',
                    '<li class="nav-item"><a class="nav-link" href="%s" target="%s">%s</a></li>',
                    '</ul>'
                ]);
define("MENU_TREE",['<li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">%s</a>
                     <div class="dropdown-menu" aria-labelledby="navbarDropdown">',
                    '<a class="dropdown-item" href="%s" target="%s">%s</a>',
                    '</div></li>'
                ]);
//Defining porotoflio rules
define("PORTOFOLIO",[
                    '<img src="%s" alt="" class="img-responsive" style="width:350px; height:250px; cursor:pointer;" onClick="$(`#%s`).modal(`show`)" >
                     <div id="%s" data-backdrop="false" class="modal fade slow" tabindex="-1" role="dialog">
                      <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header justify-content-center">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    <i class="fas fa-times" style="color:#FF3636;"></i>
                                  </button>
                                  <h4 class="title title-up">%s</h4>
                        </div>
                        <input type="hidden" value="" name="action" id="action">
                        <div class="modal-body">
                          <img src="%s" class="img-responsive" style="width:100%;">
                          <p style="margin-top:15px;">%s</p>
                        </div>
                        <div class="modal-footer text-center">
                        <a class="btn btn-pill btn-default"><i class="fas fa-globe"></i></a>
                        ',
                        '<a class="btn btn-pill btn-default"><i class="fab fa-github"></i></a>',
                        '</div>
                        </div>
                      </div>
                    </div>'
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
            key_exists("Integrity",$scss) ? $template[] = "<link rel='stylesheet' type='text/css' href='".$scss['Href']."' integrity='".$scss['Integrity']."' crossorigin='".$scss['Crossorigin']."'>"
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
    public function buildBody($start)
    {
      $start === true ? $ret = BODY[0] : $ret = BODY[1];
      return $ret;
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
      $layout = $this->GetWidgetJSON("layout");
      $return = "";
      foreach($layout['Js'] as $js)
      {
        key_exists("Integrity",$js) ? $return .= "<script src='".$js['Href']."' integrity='".$js['Integrity']."' crossorigin='".$js['Crossorigin']."'></script>" : $return .= "<script src='".$js['Href']."'></script>";
      }
      return $return;
    }

    public function buildPortofolio()
    {
      $skeleton_body = PORTOFOLIO[0];
      $skeleton_ads = PORTOFOLIO[1];
      $skeleton_footer = PORTOFOLIO[2];

      $credentials = $this->getDatabaseCredentials();
    	$database = new Database($credentials);
		  $connection = $database->dbconnectmaster();

      $query = "SELECT * FROM portofolio ORDER BY Date DESC";
      $result = mysqli_query($connection,$query);
      $database->dbclosemaster($connection);
      if(!$result)
        return null;
      $formatted_body="";
      while($row = mysqli_fetch_assoc($result)){
        $formatted_body .= sprintf($skeleton_body,$row['Cover'],$row['ID'],$row['ID'],$row['Title'],$row['Cover'],$row['Description'],$row['Demo']);
        if($row['Source'] != "")
        {
          $formatted_body.= sprintf($skeleton_ads,$row['Source']);
        }
      }
      return $formatted_body.$skeleton_footer;
    }
}
?>
