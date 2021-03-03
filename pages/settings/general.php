<?php
if(!isset($_SESSION))
    session_start();
require_once("../../autoload.php");
use Rosance\Data;
use Rosance\Project;
use Rosance\User;
use Rosance\Editor;
$data = new Data();
$user = $data->GetUser($_SESSION['loggedIN']);
$project = new Project($_COOKIE['NCS_PROJECT']);
$editor = new Editor();
$settings = $editor->getInfo($user,$project->project_name_short,"../../");
?>
<div id="requests"></div>
<form id="general-settings">
<div class="row">
    <div class="col-md-12">
        <div class="card border-0">
            <div class="card-header">
                <h4 class="card-title">General info</h4><br>
                <small>
                Get recognized by your visitors
                </small>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                    <label for="website_name">Website name <i class="fas fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Website name will be used in your title for all pages on your website (Unless it is overriden by seo settings)"></i> </label>
                        <input type="text" name="website_name" id="website_name" value="<?php echo $settings['Title']; ?>" class="form-control" placeholder="Website name">
                    </div>
                    <div class="col-md-6">
                    <label for="website_curency">Curency</label>
                    <select name="website_curency" id="website_curency" class="form-control">
	<option value="EUR" selected="selected">Euro</option>
    <option value="USD">United States Dollars</option>
	<option value="GBP">United Kingdom Pounds</option>
	<option value="DZD">Algeria Dinars</option>
	<option value="ARP">Argentina Pesos</option>
	<option value="AUD">Australia Dollars</option>
	<option value="ATS">Austria Schillings</option>
	<option value="BSD">Bahamas Dollars</option>
	<option value="BBD">Barbados Dollars</option>
	<option value="BEF">Belgium Francs</option>
	<option value="BMD">Bermuda Dollars</option>
	<option value="BRR">Brazil Real</option>
	<option value="BGL">Bulgaria Lev</option>
	<option value="CAD">Canada Dollars</option>
	<option value="CLP">Chile Pesos</option>
	<option value="CNY">China Yuan Renmimbi</option>
	<option value="CYP">Cyprus Pounds</option>
	<option value="CSK">Czech Republic Koruna</option>
	<option value="DKK">Denmark Kroner</option>
	<option value="NLG">Dutch Guilders</option>
	<option value="XCD">Eastern Caribbean Dollars</option>
	<option value="EGP">Egypt Pounds</option>
	<option value="FJD">Fiji Dollars</option>
	<option value="FIM">Finland Markka</option>
	<option value="FRF">France Francs</option>
	<option value="DEM">Germany Deutsche Marks</option>
	<option value="XAU">Gold Ounces</option>
	<option value="GRD">Greece Drachmas</option>
	<option value="HKD">Hong Kong Dollars</option>
	<option value="HUF">Hungary Forint</option>
	<option value="ISK">Iceland Krona</option>
	<option value="INR">India Rupees</option>
	<option value="IDR">Indonesia Rupiah</option>
	<option value="IEP">Ireland Punt</option>
	<option value="ILS">Israel New Shekels</option>
	<option value="ITL">Italy Lira</option>
	<option value="JMD">Jamaica Dollars</option>
	<option value="JPY">Japan Yen</option>
	<option value="JOD">Jordan Dinar</option>
	<option value="KRW">Korea (South) Won</option>
	<option value="LBP">Lebanon Pounds</option>
	<option value="LUF">Luxembourg Francs</option>
	<option value="MYR">Malaysia Ringgit</option>
	<option value="MXP">Mexico Pesos</option>
	<option value="NLG">Netherlands Guilders</option>
	<option value="NZD">New Zealand Dollars</option>
	<option value="NOK">Norway Kroner</option>
	<option value="PKR">Pakistan Rupees</option>
	<option value="XPD">Palladium Ounces</option>
	<option value="PHP">Philippines Pesos</option>
	<option value="XPT">Platinum Ounces</option>
	<option value="PLZ">Poland Zloty</option>
	<option value="PTE">Portugal Escudo</option>
	<option value="ROL">Romania Leu</option>
	<option value="RUR">Russia Rubles</option>
	<option value="SAR">Saudi Arabia Riyal</option>
	<option value="XAG">Silver Ounces</option>
	<option value="SGD">Singapore Dollars</option>
	<option value="SKK">Slovakia Koruna</option>
	<option value="ZAR">South Africa Rand</option>
	<option value="KRW">South Korea Won</option>
	<option value="ESP">Spain Pesetas</option>
	<option value="XDR">Special Drawing Right (IMF)</option>
	<option value="SDD">Sudan Dinar</option>
	<option value="SEK">Sweden Krona</option>
	<option value="CHF">Switzerland Francs</option>
	<option value="TWD">Taiwan Dollars</option>
	<option value="THB">Thailand Baht</option>
	<option value="TTD">Trinidad and Tobago Dollars</option>
	<option value="TRL">Turkey Lira</option>
	<option value="VEB">Venezuela Bolivar</option>
	<option value="ZMK">Zambia Kwacha</option>
	<option value="EUR">Euro</option>
	<option value="XCD">Eastern Caribbean Dollars</option>
	<option value="XDR">Special Drawing Right (IMF)</option>
	<option value="XAG">Silver Ounces</option>
	<option value="XAU">Gold Ounces</option>
	<option value="XPD">Palladium Ounces</option>
	<option value="XPT">Platinum Ounces</option>
</select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card border-0">
            <div class="card-header">
                <h4 class="card-title">Logo</h4><br>
                <small>
                    This is the first thing your clients will see
                </small>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <input type="hidden" id="logo" value="<?php echo $settings['Logo']; ?>" name="logo">
                                <a href="javascript:void(0)"><img class="profile-user-img img-fluid img-circle" src="<?php echo $settings['Logo']; ?>" onerror="this.error=null; this.src='images/placeholder.jpg'" alt="User profile picture" id="logo-preview" data-toggle="tooltip" data-placement="bottom" title="Click to choose your logo"></a>
                            </div>
                            <h3 class="text-center">Your logo <br><small style="font-size:35%;">(You can choose a logo from your project media)</small></h3>

                        </div>
                        <br>
                    </div>
                    <div class="col-md-12">
                        <div class="attachment-block clearfix">
                            <div class="attachment-pushed m-0 p-0">
                                <div class="attachment-heading clearfix">
                                    <p><b>What is a logo ?</b></p>
                                </div>
                                <div class="attachment-text">
                                A logo is a symbol made up of text and images that identifies a business. A good logo shows what a company does and what the brand values.

    Logo design is all about creating the perfect visual brand mark for a company. Depending on the type, a logo usually consists of a symbol or brandmark and a logotype, along with a tagline.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="attachment-block clearfix">
                            <div class="attachment-pushed m-0 p-0">
                                <div class="attachment-text">
                                At the very basic level, logos are symbols made up of text and images that help us identify brands we like. But they can be so much more! A good logo is the cornerstone of your brand. It helps customers understand what you do, who you are and what you value. That’s a lot of responsibility on a tiny image!
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card border-0">
            <div class="card-header">
                <h4 class="card-title">Favicon</h4><br>
                <small>
                Make your website stand up in tabs
                </small>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <ul id="settings-list" class="products-list product-list-in-card">
                            <li class="item pl-2 pr-2">
                                <div class="product-img">
                                <input type="hidden" id="favicon" value="<?php echo $settings['Favicon']; ?>" name="favicon">
                                <a href="javascript:void(0)" onClick='getwidget("widgets/media.php","favicon")'><img src="<?php if($settings['Favicon'] == "") echo "images/favicon.ico"; else echo $settings['Favicon']; ?>" id="favicon_thumbnail" onerror="this.onError=null;this.src='images/placeholder.jpg';" alt="Product Image" class="img-lg" data-toggle="tooltip" data-placement="bottom" title="Choose a favicon"></a>
                                </div>
                                <div class="product-info">
                                <a href="javascript:void(0)" class="product-title">Your favicon</a>
                                <span class="product-description text-sm">
                                    You can choose your favicon from your project media or click the image
                                </span>
                                </div>
                            </li>
                        </ul>
                        <br>
                    </div>
                    <div class="col-md-4">
                        <div style="background-image: url('images/favicon-representation.png'); background-position: centered; background-size:cover; background-repeat: no-repeat; width:100%; height:50px;">
                            <div class="pt-4 float-left" style="margin-left: 30%;"><img src="<?php if($settings['Favicon'] == "") echo "images/favicon.ico"; else echo $settings['Favicon']; ?>" id="favicon-preview" class="img-sm" style="width:25px; height:25px;" alt=""></div>
                            <div class="pt-4 float-left" style="margin-left: 5%;" id="preview-title">Your awesome title</div>
                        </div>
                        <br>
                    </div>

                    <div class="col-md-12">
                        <div class="attachment-block clearfix">
                            <div class="attachment-pushed m-0 p-0">
                                <div class="attachment-heading clearfix">
                                    <p><b>What is a favicon ?</b></p>
                                </div>
                                <div class="attachment-text">
                                A favicon is a small, 16x16 or 32x32 pixel icon used on web browsers to represent a website or a web page. Short for ‘favorite icon’, favicons are most commonly displayed on tabs at the top of a web browser, but they are also found on your browser’s bookmark bar, history, and more. In some instances such as on Google Chrome, they even make an appearance on your browsers’ homepage. In other words, the favicon serves as your website’s icon, or a visual mark with which to identify your website around the web.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="attachment-block clearfix">
                            <div class="attachment-pushed m-0 p-0">
                                <div class="attachment-text">
                                We recommend an image size of 32px by 32px and an image type of .PNG
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-left"><small>Need help ? Visit <a href="https://favicon.io" target="_blank">Favicon</a> and make it for free!</small></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card border-0">
            <div class="card-header">
                <h4 class="card-title">Location</h4><br>
                <small>
                Let users know where they can find you
                </small>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                        <label for="google-maps">Your google maps code </label>
                        <textarea name="google-maps" id="google-maps" class="form-control" rows="5"><?php echo $settings['Maps']; ?></textarea>
                        </div>
                        <div class="attachment-block clearfix">
                            <div class="attachment-pushed m-0 p-0">
                                <div class="attachment-text">
                                If you don't have a physical address leave this field blank
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card card-outline card-danger card-warning">
            <div class="card-header"><h4 class="card-title">Danger area</h4><br><small>This action is definitive and ireversible!</small></div>
            <div class="card-body">
                <button type="button" onClick="deleteproject('<?php echo $project->project_id; ?>');" class="btn btn-block btn-danger elevation-2">I want to delete my project</button>
            </div>
        </div>
    </div>
</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
    $("#general").addClass("active");
    $('[data-toggle="tooltip"]').tooltip();
    
    $("#savechanges").on("click",function(){
        $("#general-settings").submit();
    });
    $("#website_curency").val("<?php echo $settings['Curency']; ?>");
});

function selectMedia(url, dest)
{
    if(dest == 'favicon')
    {
        $("#favicon-preview").attr("src",url);
        $("#favicon_thumbnail").attr("src",url);
        $("#favicon").val(url);
    }
    else if(dest == "logo")
    {
        $("#logo-preview").attr("src",url);
        $("#logo").val(url);
    }
    $("#savechanges").removeClass("disabled");
    $("#savechanges").removeAttr("disabled");
}
$("#website_name").keyup(function(){
    let val = $(this).val();
    $("#preview-title").text(truncate(val,20));
});
$("#general-settings").change(function(){
    $("#savechanges").removeAttr("disabled");
    $("#savechanges").removeClass("disabled");
});
</script>