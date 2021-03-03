<?php require_once("core/ssk.req.class.php");
require_once("core/main.class.php");
require_once("core/builder.class.php");
require_once("core/database.class.php");
$main = new Main();
$builder = new Builder();
$info = $main->getInfo();
echo $builder->buildHead();
echo $builder->buildBody(true);
 ?>
        <nav class="navbar sticky-top navbar-expand-lg navbar-light bg-white _6007f7ff9848c py-4" editable="editable" data-panel="header" data-panelid="_6007f7ff9848c">
            <div class="container">
                <div class="_6d07f7ff9843c" editable="editable" data-panel="text" data-panelid="_6d07f7ff9843c"><a class="navbar-brand js-scroll-trigger" href="#page-top"><b>Start Bootstrap</b></a></div>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <?php  
                    echo $builder->buildMenu();
                    ?>
                </div>
            </div>
        </nav>
        <header class="vh-100 _6007f7ff986ca py-3" editable="editable" data-panel="section" data-panelid="_6007f7ff986ca" style="background-image:url(//localhost/clients/ncs/Creative/images/bg-masthead.jpg);background-size:100% 100%;background-position:50% 50%;background-repeat:no-repeat;background-color:rgb(255, 255, 255);">
<div class="row _omhcfui9k" style="min-height: 100px;" editable="editable" data-panel="row" data-panelid="_omhcfui9k"><div class="_xgg3ihy75 col-md-12" editable="editable" data-panel="column" data-panelid="_xgg3ihy75"></div></div>
        </header>
<section class="py-4 section _gfro9yjae mt-0" editable="editable" data-panelid="_gfro9yjae" data-panel="section" style="background-size:0% 0%;background-position:0% 0%;background-repeat:repeat;background-color:rgb(249, 115, 47);">
        <div class="container _t93ku4csk" editable="editable" data-panel="container" data-panelid="_t93ku4csk">
            <div class="row h-100 _4023757ff9d6f" editable="editable" data-panel="row" data-panelid="_4023757ff9d6f">
                <div class="col-md-12 _o0ydc8nwi" editable="editable" data-panel="column" data-panelid="_o0ydc8nwi">
                    <div editable="editable" data-panel="text" data-panelid="_6xbbaekkk" class="_6xbbaekkk mx-auto">
                        <h1 style="text-align: center; "><font color="#ffffff">Call to Action</font></h1>
                        <p class="lead" style="text-align: center;"><font color="#ffffff">
                            Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts
                        </font></p>
                    </div>
                    <div class="_fx3kad39d container text-center" editable="editable" data-panel="container" data-panelid="_fx3kad39d" style="width:500px;">
                        <a class="btn mt-5 _ug1xsa8sa mx-auto btn-rounded btn-light" href="https://www.rosance.com/" editable="editable" data-panel="button" data-panelid="_ug1xsa8sa">Downloads</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
        <section class="p-4 _eemeaj6bz" editable="editable" data-panelid="_eemeaj6bz" data-panel="section">
        <div class="container">
            <div class="row text-center justify-content-center _mkluq40cu" editable="editable" data-panel="row" data-panelid="_mkluq40cu">
                <div class="col-md-8 _0y0j9qv0q" editable="editable" data-panel="column" data-panelid="_0y0j9qv0q">
                    <div editable="editable" data-panel="text" data-panelid="_1tiqb0z98" class="_1tiqb0z98">
                        <h1>Meet Our Team</h1>
                    </div>
                </div>
            </div>
            <div class="row text-center justify-content-center _peih4abz1" editable="editable" data-panel="row" data-panelid="_peih4abz1">
                <div class="col-md-3 m-sm-auto _9gz1emts4" editable="editable" data-panel="column" data-panelid="_9gz1emts4" style="position:relative;left:auto;top:auto;width:auto;height:auto;">
                    <img editable="editable" data-panel="image" alt="Man with glasses" class="img-fluid rounded-circle _mlvj4t6nr" src="//localhost/clients/ncs/Creative/images/9.jpg" data-panelid="_mlvj4t6nr" title="Image title" style="height:auto;width:auto;top:auto;left:auto;">
                    <div editable="editable" data-panel="text" data-panelid="_s6tfme5jv" class="_s6tfme5jv">
                        <h3><strong>Sara Doe</strong></h3>
                        <p>"Wild Question Marks, but the Little Blind"</p>
                    </div>
                </div>
                <div class="col-md-3 m-sm-auto _jvb05uchb" editable="editable" data-panel="column" data-panelid="_jvb05uchb">
                    <img editable="editable" data-panel="image" alt="image" class="img-fluid rounded-circle _36frcbl6b" src="./images/6.jpg" data-panelid="_36frcbl6b">
                    <div editable="editable" data-panel="text" data-panelid="_k4ia901pc" class="_k4ia901pc">
                        <h3><strong>Sara Doe</strong></h3>
                        <p>"Wild Question Marks, but the Little Blind"</p>
                    </div>
                </div>
                <div class="col-md-3 m-sm-auto _hsd5tl2x3" editable="editable" data-panel="column" data-panelid="_hsd5tl2x3">
                    <img editable="editable" data-panel="image" alt="image" class="img-fluid rounded-circle _wfy7n7nun" src="./images/5.jpg" data-panelid="_wfy7n7nun">
                    <div editable="editable" data-panel="text" data-panelid="_azzge1rke" class="_azzge1rke">
                        <h3><strong>Sara Doe</strong></h3>
                        <p>"Wild Question Marks, but the Little Blind"</p>
                    </div>
                </div>
                <div class="col-md-3 m-sm-auto _y2beeuxp7" editable="editable" data-panel="column" data-panelid="_y2beeuxp7">
                    <img editable="editable" data-panel="image" alt="image" class="img-fluid rounded-circle _hjq7kcuiv" src="./images/8.jpg" data-panelid="_hjq7kcuiv">
                    <div editable="editable" data-panel="text" data-panelid="_yqpohy290" class="_yqpohy290">
                        <h3><strong>Sara Doe</strong></h3>
                        <p>"Wild Question Marks, but the Little Blind"</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center text-center _aqwli8p97 mt-5" editable="editable" data-panel="row" data-panelid="_aqwli8p97">
                <div class="col-md-3 m-sm-auto _20jbkjepm" editable="editable" data-panel="column" data-panelid="_20jbkjepm">
                    <img editable="editable" data-panel="image" alt="image" class="img-fluid rounded-circle _upq6siqdg" src="./images/3.jpg" data-panelid="_upq6siqdg">
                    <div editable="editable" data-panel="text" data-panelid="_5oys79six" class="_5oys79six">
                        <h3><strong>Sara Doe</strong></h3>
                        <p>"Wild Question Marks, but the Little Blind"</p>
                    </div>
                </div>
                <div class="col-md-3 m-sm-auto _s3gfbe2zq" editable="editable" data-panel="column" data-panelid="_s3gfbe2zq">
                    <img editable="editable" data-panel="image" alt="image" class="img-fluid rounded-circle _ei55jdhr2" src="./images/9.jpg" data-panelid="_ei55jdhr2">
                    <div editable="editable" data-panel="text" data-panelid="_ce4mih2nu" class="_ce4mih2nu">
                        <h3><strong>Sara Doe</strong></h3>
                        <p>"Wild Question Marks, but the Little Blind"</p>
                    </div>
                </div>
                <div class="col-md-3 m-sm-auto _68rfr8823" editable="editable" data-panel="column" data-panelid="_68rfr8823">
                    <img editable="editable" data-panel="image" alt="image" class="img-fluid rounded-circle _h405fwcte" src="./images/7.jpg" data-panelid="_h405fwcte">
                    <div editable="editable" data-panel="text" data-panelid="_451dws73h" class="_451dws73h">
                        <h3><strong>Sara Doe</strong></h3>
                        <p>"Wild Question Marks, but the Little Blind"</p>
                    </div>
                </div>
                <div class="col-md-3 m-sm-auto _kdz29eohm" editable="editable" data-panel="column" data-panelid="_kdz29eohm">
                    <img editable="editable" data-panel="image" alt="image" class="img-fluid rounded-circle _n5d6zjwli" src="./images/2.jpg" data-panelid="_n5d6zjwli">
                    <div editable="editable" data-panel="text" data-panelid="_19pknwpiu" class="_19pknwpiu">
                        <h3><strong>Sara Doe</strong></h3>
                        <p>"Wild Question Marks, but the Little Blind"</p>
                    </div>
                </div>
            </div>
        </div>
    </section><section class="py-4 mt-2 section _zm6hm006x" editable="editable" data-panelid="_zm6hm006x" data-panel="section">
        <div class="container _grmy55y8e" editable="editable" data-panel="container" data-panelid="_grmy55y8e">
            <div class="row h-100 justify-content-center _cluoz3am7" editable="editable" data-panel="row" data-panelid="_cluoz3am7">
                <div class="col-md-12 text-center _fupq3ptfs" editable="editable" data-panel="column" data-panelid="_fupq3ptfs">
                    <div editable="editable" data-panel="text" data-panelid="_se1sqgu31" class="_se1sqgu31">
                        <h1>Our work so far</h1>
                        <p class="lead">
                            Check out this collection of projects that we made by ourself for our customers</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <footer class="text-center text-lg-start text-white _123dcasd1d" style="background-color: #3e4551" editable="editable" data-panel="footer" data-panelid="_123dcasd1d">
        <div class="container _rxzd34h1d" editable="editable" data-panel="container" data-panelid="_rxzd34h1d">
            <div class="row pt-4 _roxd37h12" editable="editable" data-panel="column" data-panelid="_roxd37h12">
                <div class="col-md-12 _cacdvgh12" editable="editable" data-panel="column" data-panelid="_cacdvgh12">
                    <div class="text-center _ccad3g" editable="editable" data-panel="text" data-panelid="_ccad3g">
                        <h2> Follow us on  </h2>
                    </div>
                </div>
            </div>
            <div class="row h-100 _frdx13g" editable="editable" data-panel="row" data-panelid="_frdx13g">
                <div class="col-md-8 mx-auto justify-content-center align-items-center d-flex _xcg3p23g" style="height:100px;" editable="editable" data-panel="column" data-panelid="_xcg3p23g">
                    <div editable="editable _cd2q3ptxf" editable="editable" data-panel="social" data-panelid="_cd2q3ptxf">
                        <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-facebook"></i></a>
                        <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-instagram"></i></a>
                        <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-twitter"></i></a>
                        <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-github"></i></a>
                        <a class="btn btn-outline-light btn-floating m-1" href="#!" role="button"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <hr class="mb-4 _craq44ptdh" editable="editable" data-panel="line" data-panelid="_craq44ptdh">

        <div class="container p-4 pt-5 pb-0 _bxaq48htv" editable="editable" data-panel="container" data-panelid="_bxaq48htv">
            <div class="row _cgj579htv" editable="editable" data-panel="row" data-panelid="_cgj579htv">
                <div class="col-md-4 _cda79ngy" editable="editable" data-panel="column" data-panelid="_cda79ngy">
                    <div class="_fcl79ngy" editable="editable" data-panel="text" data-panelid="_fcl79ngy">
                        <h5>FOOTER CONTENT</h5>
                        <p>
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Molestiae modi cum ipsam ad, illo possimus laborum ut
                        reiciendis obcaecati. Ducimus, quas. Corrupti, pariatur eaque?
                        Reiciendis assumenda iusto sapiente inventore animi?
                        </p>
                    </div>
                </div>
                <div class="col-md-2 _hk48yncf" editable="editable" data-panel="column" data-panelid="_hk48yncf">
                    <div class="_fr489ycc4" editable="editable" data-panel="text" data-panelid="_fr489ycc4">
                        <h5>LINKS</h5>
                    </div>
                    <ul class="list-unstyled _fr489yrc1" editable="editable" data-panel="list" data-panelid="_fr489yrc1">
                        <li>
                            <a href="#!" class="text-white">Link 1</a>
                        </li>
                        <li>
                            <a href="#!" class="text-white">Link 2</a>
                        </li>
                        <li>
                            <a href="#!" class="text-white">Link 3</a>
                        </li>
                        <li>
                            <a href="#!" class="text-white">Link 4</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-2 _hdr8yrh2" editable="editable" data-panel="column" data-panelid="_hdr8yrh2">
                    <div class="_fr489y98c" editable="editable" data-panel="text" data-panelid="_fr489y98c">
                        <h5>LINKS</h5>
                    </div>
                    <ul class="list-unstyled _nc439y768c" editable="editable" data-panel="list" data-panelid="_nc439y768c">
                        <li>
                            <a href="#!" class="text-white">Link 1</a>
                        </li>
                        <li>
                            <a href="#!" class="text-white">Link 2</a>
                        </li>
                        <li>
                            <a href="#!" class="text-white">Link 3</a>
                        </li>
                        <li>
                            <a href="#!" class="text-white">Link 4</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-2 _noc88drq" editable="editable" data-panel="column" data-panelid="_noc88drq">
                    <div class="_nb488dca"  editable="editable" data-panel="text" data-panelid="_nb488dca">
                        <h5>LINKS</h5>
                    </div>
                    <ul class="list-unstyled _nb433dbf" editable="editable" data-panel="list" data-panelid="_nb433dbf">
                        <li>
                            <a href="#!" class="text-white">Link 1</a>
                        </li>
                        <li>
                            <a href="#!" class="text-white">Link 2</a>
                        </li>
                        <li>
                            <a href="#!" class="text-white">Link 3</a>
                        </li>
                        <li>
                            <a href="#!" class="text-white">Link 4</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-2 _r4c5ddc4" editable="editable" data-panel="column" data-panelid="_r4c5ddc4">
                    <div class="_r4cyffc4" editable="editable" data-panel="text" data-panelid="_r4cyffc4">
                        <h5>LINKS</h5>
                    </div>
                    <ul class="list-unstyled _jjc3d4d90" editable="editable" data-panel="list" data-panelid="_jjc3d4d90">
                        <li>
                            <a href="#!" class="text-white">Link 1</a>
                        </li>
                        <li>
                            <a href="#!" class="text-white">Link 2</a>
                        </li>
                        <li>
                            <a href="#!" class="text-white">Link 3</a>
                        </li>
                        <li>
                            <a href="#!" class="text-white">Link 4</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row text-center p-3 bg-dark">
            <div class="col-md-12">
                <div class="text-center">
                    © 2020 by Your Company , powered by <a href="https://rosance.com">Rosance</a>
                </div>
            </div>
        </div>
  </footer>
    <?php echo $builder->buildJS(); ?>