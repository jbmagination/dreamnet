<?php
require 'functions/functions.php';
$db = dbConnect();
include 'partials/header.php';
$id = false;
if(isset($_GET['id'])) { $id = $_GET['id']; }
if(isset($_POST['id'])) { $id = $_POST['id']; }
if(!isset($_GET['action']) && !is_numeric($id) || strlen($id) < 9 || strlen($id) > 10) {
    $action = 'display_form';
} else {
    $action = 'search_art';
}
if(isset($_GET['action']) == 'raw') {
    $action = 'raw';
}

switch($action) { // switching between form and search and raw output

    case 'display_form': //displays the itunes id input form
        ?>
            <div class="input_box">
                <div class="input_box_top">
                    <h1>Glasklart iTunes Data Crawler</h1>
                    <span style="font-size: 12px;">(made for <a href="https://github.com/glasklart/hd">https://github.com/glasklart/hd</a>)</span>
                </div>
                <form id="itunes_form" method="post" action="">
                    <div class="box_middle">
                        <p style="font-size: 20px;">Please enter <b>iTunes ID</b>:</p>
                        <div style="text-align: center; margin-top: 20px;">
                            <span style="margin-right: 10px;"><img valign="middle" src="img/arrow.png" width="34" height="26"></span>
                            <span><input id="itunes_id" type="text" onkeypress="return isNumberKey(event);" pattern="\d*" name="id" size="10" maxlength="10" value="" required="required" autofocus="autofocus" /></span>
                            <span style="margin-left: 10px;"><img valign="middle" src="img/backarrow.png" width="34" height="26"></span>
                        </div>
                        <p style="padding-top: 20px;"><button id="search" type="submit" name="submit"><b>Grab data!</b></button></p>
                        <p style="font-size: 12px;">(This can take some time...)</p>
                    </div>
                </form>
                <div class="box_bottom">
                  <div><b>&copy;</b> <a href="https://github.com/dreamnet">dreamnet</a> 2015. Thanks to <a href="https://github.com/alldayremix">alldayremix</a> & <a href="https://github.com/jfelchner">jfelchner</a>.</div>
                  <div style="margin-top: 8px;"><b>NonCommercial use only!</b></div>
                </div>
            </div>
            <script>
                function isNumberKey(evt){
                    var charCode = (evt.which) ? evt.which : evt.keyCode
                    return !(charCode > 31 && (charCode < 48 || charCode > 57));
                }
            </script>
        <?php
    break;

    case 'search_art': // displays the result or the error message
        $blacklist = array(
            array('Jiho Kang', 849035309, 'The developer of this app refuses to add his app(s) to Glasklart. We are sorry.'),
            array('Jiho Kang', 819323365, 'The developer of this app refuses to add his app(s) to Glasklart. We are sorry.')
        );
        $exist = checkiTunesID($db,$id);
        $data = crawl_data($id);
        // check blacklist
        $blacklistet = false;
        foreach($blacklist as $black) {
            if(($black[0] == trim($data['results'][0]['sellerName'])) && ($black[1] == $data['results'][0]['trackId'])) {
                $blacklistet = true;
                $reason = $black[2];
            } else {
                $blacklistet = false;
            }
        }
        if($blacklistet) {
            ?>
            <div class="result_box">
                <div class="result_box_top"><h1>We are very sorry...</h1></div>
                <div class="result_box_bottom">
                    <div><?php echo $reason; ?></div>
                    <div><a href="http://getart.dreamnet.at"><button style="font: bold 1em Arial, Helvetica;"><b>Reset</b></button></a></div>
                </div>
            </div>
            <?php
        } else {
            if ($data != false) {
                if($exist != false) { $duplicate = '[DUPLICATE of #'.$exist['issue'].']'; } else { $duplicate = ''; }
                if($duplicate != '') { $dataText = $duplicate."\n"; } else { $dataText = ''; }
                $dataText .= assembleData($data);
                $artwork = $data['results'][0]['artworkUrl512'];
                $bigjpg = str_replace('512x512','1024x1024',$artwork);
                $bigpng = str_replace('.jpg','.png',$bigjpg);
                if(url_exists($bigpng)) {
                    $artwork = $bigpng;
                } elseif(url_exists($bigjpg)) {
                    $artwork = $bigjpg;
                }
                $server = $data['server'];
                ?>
                <div class="result_box">
                    <div class="result_box_top">
                        <h1>We have found some app data...</h1>
                        <p>...on the <?php echo $server; ?> iTunes Store.</p>
                        <p style="margin-top: 5px;">
                            <?php if($exist == false) { ?>
                                <a target="_blank" href="https://github.com/glasklart/hd/issues/new"><button style="font: bold 1em Arial, Helvetica;"><b>New Issue</b></button></a>
                            <?php } ?>
                            <a href="http://getart.dreamnet.at"><button style="font: bold 1em Arial, Helvetica;"><b>Reset</b></button></a>
                        </p>
                    </div>
                    <div class="result_box_middle">
                        <?php if($exist == false) { ?>
                            <p style="text-align: justify;">Please <b>copy</b> the text in the box below <b>and paste</b> it into a <a target="_blank" href="https://github.com/glasklart/hd/issues/new"><b>new issue</b></a> on Github. Copy the text <b>as it is</b> - even if no image is shown. The title of your new issue is <b>only the exact app name</b> as shown in the box below (without the **App Name:**). Do <b>NOT</b> modifiy anything else! If you are not sure what to do, <b>please read our <a href="https://github.com/glasklart/hd/wiki/How-to-Submit-an-Icon-Request">WIKI</a></b>!!!</p>
                        <?php } else { ?>
                            <div style="text-align: center;">...but wait a minute, there is already an existing issue for the app<br><code><b>"<?php echo $exist['trackName']; ?>"</b></code><br>on Github.</div>
                            <div style="padding: 10px; margin: 10px; text-align: center; background-color: #f00; color: #fff; border: 1px solid #000; border-radius: 5px; text-shadow: 2px 2px 3px #000;"><b>So... please DO NOT open a new one!!</b></div>
                            <div style="text-align: center;">If something is going wrong with this app or needs to be changed, please leave us a comment on the</div>
                            <div style="text-align: center;"><a target="_blank" href="https://github.com/glasklart/hd/issues/<?php echo $exist['issue']; ?>"><button style="font: bold 1em Arial, Helvetica;"><b>Existing Issue</b></button></a></div>
                        <?php } ?>
                        <textarea id="iTunes_data" rows="22" cols="90" onclick="this.focus();this.select()" autofocus><?php echo $dataText; ?></textarea>
                        <p style="text-align: center;">Direct link to this page: <code>http://getart.dreamnet.at?id=<?php echo $id; ?></code></p>
                    </div>
                    <div class="result_box_bottom">
                        <img class="artwork" src="<?php echo $artwork; ?>" width="512" height="512">
                    </div>
                </div>
            <?php } else { ?>
                <div class="result_box">
                    <div class="result_box_top">
                        <h1><b>We tried our best, but...</b></h1>
                    </div>
                    <div class="result_box_middle" style="text-align: center;">
                        <p>...the iTunes Servers did not return any data for ID #<code><b><?php echo $id; ?></b></code>.</p>
                        <p><b>Please double check.</b></p>
                    </div>
                    <div class="result_box_bottom">
                        <a href="http://getart.dreamnet.at"><button style="font: bold 1em Arial, Helvetica;"><b>Reset</b></button></a>
                    </div>
                </div>
            <?php
            }
        }
    break;

    case 'raw':
        $data = crawl_data($id);
        echo '<div style="background: #fff;"><pre>';
        echo htmlspecialchars(var_dump($data), ENT_QUOTES);
        echo '</pre></div>';
    break;

}

include 'partials/footer.php';

$db->close();
