<?php
//delete_option('sr_users');
wp_enqueue_style('sr-crm');
wp_enqueue_script('sr_tinyMC');


$sr = $seo_rets_plugin;
$plugin_title = $sr->admin_title;
$plugin_id = $sr->admin_id;
global $current_user;
get_currentuserinfo();


$users = get_option('sr_users');
//echo "<pre>";
//print_r($users);
//echo "</pre>";
if (is_array($type)) $type = $type[0];

$server_name = $sr->feed->server_name;
$match = array();
if (preg_match("/^([a-zA-Z]+)\\.([a-zA-Z]+)$/", $type, $match)) {
    $server_name = $match[1];
}
$listings = $request->result;
$listing = $listings[0];
$photo_dir = 'http://img.seorets.com/' . $server_name;
$map_settings = get_option('sr-map');
if($sr->feed->agent_crm) {
?>
<script>
    jQuery(document).ready(function () {
        jQuery(function () {
            jQuery("#tabs").tabs();
        });
        jQuery('.addContactInfo').click(function (e) {
            e.preventDefault();
            jQuery('#addContactInfo').stop().toggle('fast');
        });
        jQuery('.statusChange').click(function (e) {
            e.preventDefault();
            jQuery('.statusHide').toggle('fast');
        });
        jQuery('.showMore').click(function (e) {
            e.preventDefault();
            jQuery(this).next().stop().toggle('fast');
        });
        jQuery('.search_users').keyup(function () {
            jQuery.ajax({
                url: '<?php bloginfo('url') ?>/sr-ajax?action=getUserByName',
                type: 'post',
                data: {
                    name: jQuery(this).val()
                },
                success: function (response) {
                    console.log(response);
                }
            });
        });
    });
</script>
<div class="wrap">
    <div id="icon-tools" class="icon32"></div>
    <h2><?php echo $plugin_title ?> :: Signed Up Users<sup>
            <!--            <small><i>(beta)</i></small>-->
        </sup></h2>
    <?php
    date_default_timezone_set("US/Central");
    function get_csv($users)
    {
        $fp = fopen('file.csv', 'w');
        fputcsv($fp, 'name,mail,phone');
        fputcsv($fp, '\n');
        foreach ($users as $key => $user) {
            fputcsv($fp, $user['name'] . "," . $user['email'] . "," . $user['u_mobile'] . ",\n");
        }
        fclose($fp);

//        file_put_contents('ar.txt','asd');
    }

    if ($_POST['notes-save']) {
        $noteInfo = [
            'notetxt' => $_POST['notes'],
            'time_add' => date("Y-m-d H:i:s"),
            'author' => $current_user->user_login
        ];
        $users[$_POST['userID']]['note'][] = $noteInfo;
        update_option('sr_users', $users);
        ?>
        <script>
            location.reload();
        </script>
        <?
    }
    if ($_POST['status-save']) {
        $users[$_POST['userID']]['status'] = $_POST['a_status'];
        update_option('sr_users', $users);
        ?>
        <script>
            location.reload();
        </script>
        <?
    }
    if ($_POST['contact-save']) {
        $users[$_POST['userID']]['a_mobilePhone'] = $_POST['mobilePhone'];
        $users[$_POST['userID']]['a_homePhone'] = $_POST['homePhone'];
        $users[$_POST['userID']]['a_homeAddress'] = $_POST['homeAddress'];
        update_option('sr_users', $users);
        ?>
        <script>
            location.reload();
        </script>
        <?
    }
    if ($_GET['csv']) {
        $csv = get_csv($users);
//        header("Pragma: public");
//        header("Expires: 0");
//        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
//        header("Cache-Control: private", false);
//        header("Content-Type: application/octet-stream");
//        header("Content-Disposition: attachment; filename=\"report.csv\";");
//        header("Content-Transfer-Encoding: binary");
//
        print_r($csv);
//        exit;
    }
    if ($_GET['deletenote'] || $_GET['deletenote'] === '0') {
        unset($users[$_GET['profile']]['note'][$_GET['deletenote']]);
        update_option('sr_users', $users);
        $url = 'admin.php?page=seo-rets-crm&profile=' . $_GET['profile'];
        echo '<script>window.location = "' . $url . '";</script>';
    }
    if ($users) {
    if ($_GET['profile'] || $_GET['profile'] === '0') {
        $i = $_GET['profile'];
        ?>
        <script src="//tinymce.cachefly.net/4.2/tinymce.min.js"></script>
        <script>
            jQuery(document).ready(function () {
                jQuery.ajax({
                    url: '<?php bloginfo('url') ?>/sr-ajax?action=get-last-view',
                    type: 'post',
                    data: {
                        userID: <? echo $i; ?>
                    },
                    success: function (response) {
//                        console.log(response);
                        jQuery('#recentlyViewed').html(response);
                    }
                });
                jQuery.ajax({
                    url: '<?php bloginfo('url') ?>/sr-ajax?action=get-fav',
                    type: 'post',
                    data: {
                        userID: <? echo $i; ?>
                    },
                    success: function (response) {
                        console.log(response);
                        jQuery('#tabs-2').html(response);
                    }
                });
                jQuery.ajax({
                    url: '<?php bloginfo('url') ?>/sr-ajax?action=get-email-alerts',
                    type: 'post',
                    data: {
                        userID: <? echo $i; ?>
                    },
                    success: function (response) {
                        console.log(response);
                        jQuery('#tabs-4').html(response);
                    }
                });
            });
        </script>
        <script>
            tinymce.init({
                selector: 'textarea',
                toolbar: [
                    "undo redo  styleselect  bold italic  link image  alignleft  aligncenter  alignright  numlist "
                ],
                menubar: false,
                statusbar: false
            });</script>
        <div class="userProfile">
            <div class="row userProfileHeader">
                <a style="float: right" href="admin.php?page=seo-rets-crm"><i class="fa fa-arrow-left"></i> Back to
                    user
                    page</a>
            </div>
            <div class="row profileInfo">
                <div class="col-4-left shortInfo">
                    <h1>Contact info</h1>
                    <ul>
                        <?php if ($users[$i]['u_mobile'] != "") { ?>
                            <li>
                                <a target="_blank" href="tel:<?php echo $users[$i]['u_mobile']; ?>"><span><i
                                            class="fa fa-mobile"></i> <?php echo $users[$i]['u_mobile']; ?></span></a>
                            </li>
                        <?php } ?>
                        <?php if ($users[$i]['u_phone'] != "") { ?>
                            <li>
                                <a target="_blank" href="tel:<?php echo $users[$i]['u_phone']; ?>"> <span><i
                                            class="fa fa-phone"></i> <?php echo $users[$i]['u_phone']; ?></span></a>
                            </li>
                        <?php } ?>
                        <li>
                            <a target="_blank" href="mailto:<?php echo $users[$i]['email']; ?>"> <span><i
                                        class="fa fa-envelope"></i> <?php echo $users[$i]['email']; ?></span></a>
                        </li>
                        <?php if ($users[$i]['al_1'] != "" || $users[$i]['al_2'] != "" || $users[$i]['u_city'] != "" || $users[$i]['u_state'] != "" || $users[$i]['u_zip'] != "") { ?>
                            <li>
                                <span><i class="fa fa-map-marker"></i> <?php echo $users[$i]['al_1']; ?>
                                    <?php echo $users[$i]['al_2']; ?>
                                    <?php echo $users[$i]['u_city']; ?>, <?php echo $users[$i]['u_state']; ?>
                                    , <?php echo $users[$i]['u_zip']; ?></span>
                            </li>
                        <?php } ?>
                    </ul>
                    <?php
                    if ($users[$i]['a_mobilePhone'] != "" || $users[$i]['a_homePhone'] != "" || $users[$i]['a_homeAddress'] != "") {
                        ?>
                        <h1>Added contacts</h1>
                        <ul>
                            <?php if ($users[$i]['a_mobilePhone'] != "") { ?>
                                <li>
                                    <a target="_blank" href="tel:<?php echo $users[$i]['a_mobilePhone']; ?>"> <span><i
                                                class="fa fa-mobile"></i> <?php echo $users[$i]['a_mobilePhone']; ?></span></a>
                                </li>
                            <?php } ?>
                            <?php if ($users[$i]['a_homePhone'] != "") { ?>
                                <li>
                                    <a target="_blank" href="tel:<?php echo $users[$i]['a_homePhone']; ?>"> <span><i
                                                class="fa fa-phone"></i> <?php echo $users[$i]['a_homePhone']; ?></span></a>
                                </li>
                            <?php } ?>
                            <?php if ($users[$i]['a_homeAddress'] != "") { ?>
                                <li>
                                    <span><i
                                            class="fa fa-map-marker"></i> <?php echo $users[$i]['a_homeAddress']; ?></span>
                                </li>
                            <?php } ?>
                        </ul>
                        <?
                    }
                    ?>
                    <a class="addContactInfo" href="#addContactInfo"><i class="fa fa-plus"></i> Add contact information
                    </a>

                    <form action="" method="post">
                        <ul id="addContactInfo">
                            <li>
                                <label for="addC_mobPhone"><i class="fa fa-mobile"></i> Mobile</label>
                                <input id="addC_mobPhone" name="mobilePhone" type="text"
                                       value="<?php echo $users[$i]['a_mobilePhone']; ?>"
                                       class="form-control mobilePhone">
                            </li>
                            <li>
                                <label for="addC_phone"><i class="fa fa-phone"></i> Second Phone</label>
                                <input id="addC_phone" name="homePhone" type="text" class="form-control homePhone"
                                       value="<?php echo $users[$i]['a_homePhone']; ?>">
                            </li>
                            <li>
                                <label for="addC_Address"><i class="fa fa-map-marker"></i> Address</label>
                                <input id="addC_Address" name="homeAddress" type="text"
                                       value="<?php echo $users[$i]['a_homeAddress']; ?>"
                                       class="form-control homeAddress">
                            </li>
                            <li>
                                <input type="text" hidden name="userID" value="<?= $i; ?>">
                                <input type="submit" style="float: right; margin-top: 10px" name="contact-save"
                                       id="contact-save"
                                       class="button-primary" value="Save"/>
                            </li>
                        </ul>
                    </form>
                </div>
                <div class="col-2-left mainInfo">
                    <h1><?php echo $users[$i]['name'];
                        if ($users[$i]['full_name'] != "") { ?>(<?php echo $users[$i]['full_name']; ?>) <? } ?></h1>

                    <p>
                        Status: <?php
                        if ($users[$i]['status'] != "") {
                            echo $users[$i]['status'];
                        } else {
                            echo "Potential Client";
                        }
                        ?> <i class="statusChange fa fa-pencil-square-o"></i>

                    <div class="statusHide">
                        <form action="" method="post">
                            <select name="a_status" id="#status">
                                <option <? if ($users[$i]['status'] == 'Potential Client') echo 'selected'; ?>
                                    value="Potential Client">Potential Client
                                </option>
                                <option <? if ($users[$i]['status'] == 'Active') echo 'selected'; ?> value="Active">
                                    Active
                                </option>
                                <option <? if ($users[$i]['status'] == 'Closed') echo 'selected'; ?> value="Closed">
                                    Closed
                                </option>
                            </select>
                            <input type="text" hidden name="userID" value="<?= $i; ?>">
                            <input type="submit" name="status-save"
                                   id="status-save"
                                   class="button-primary" value="Save"/>
                        </form>
                    </div>
                    </p>

                    <div id="tabs">
                        <ul>
                            <li><a href="#tabs-1">notes</a></li>
                            <li><a href="#tabs-2">favorites</a></li>
                            <li><a href="#tabs-3">saved search</a></li>
                            <li><a href="#tabs-4">email alerts</a></li>
                        </ul>
                        <div id="tabs-1">
                            <label for="notes">Add a note</label>

                            <form action="" method="post">
                                <input type="text" hidden name="userID" value="<?= $i; ?>">

                                <textarea name="notes" id="notes"></textarea>
                                <input type="submit" style="float: right; margin-top: 10px" name="notes-save"
                                       id="notes-save"
                                       class="button-primary" value="Add this note"/>
                            </form>
                            <div style="clear: both"></div>
                            <?php
                            foreach ($users[$i]['note'] as $key => $note) {
                                ?>
                                <div>
                                    <p>
                                        <?php echo $note['notetxt']; ?>
                                    </p>
                                <span style="float: right"><? echo $note['author']; ?> | <? echo $note['time_add']; ?>
                                    <a href="admin.php?page=seo-rets-crm&profile=<?= $i; ?>&deletenote=<?= $key ?>">Delete</a>  </span>


                                </div>
                                <?
                            }
                            ?>
                        </div>
                        <div id="tabs-2">
                            <div class="loaderImage"><img id="ajax-loader2"
                                                          src="<?php echo $sr->plugin_dir ?>resources/images/ajax2.gif"/>
                            </div>
                        </div>
                        <div id="tabs-3">
                            <?php
                            $p = count($users[$i]['savesearch']);
                            $array = [];
                            for ($s = 0; $s <= $p - 1; $s++) {
                                $t = array(
                                    'link' => json_decode(base64_decode($users[$i]['savesearch'][$s]['base64link']), true),
                                    'originals' => urlencode($users[$i]['savesearch'][$s]['base64link'])
                                );
                                array_push($array, $t);
                            }
                            foreach ($array as $key => $ar) {
                                ?>
                                <div class="row"
                                     style="margin-top: 20px; padding-bottom: 20px;border-bottom: 1px solid #ccc">
                                    <div class="col-7-left">

                                        <?
                                        foreach ($ar['link']['q']['c'] as $c) {
                                            if ($c['c']) {
                                                foreach ($c['c'] as $l) {
                                                    ?>
                                                    <div class="row">
                                                        <div class="col-2-left">
                                                            <?
                                                            $minMax = str_replace('=', '', $l['o']);
                                                            if ($minMax != "" && $minMax == ">") {
                                                                echo 'Min ' . $l['f'];
                                                            } elseif ($minMax != "" && $minMax == "<") {
                                                                echo 'Max ' . $l['f'];
                                                            } else {
                                                                echo $l['f'];
                                                            }
                                                            ?>
                                                        </div>
                                                        <div class="col-2-left">
                                                            <?= $l['v']; ?>
                                                        </div>
                                                    </div>
                                                    <?
                                                }
                                            }
                                            ?>
                                            <div class="row">
                                                <div class="col-2-left">
                                                    <?
                                                    $minMax2 = str_replace('=', '', $c['o']);
                                                    if ($minMax2 != "" && $minMax2 == ">") {
                                                        echo 'Min ' . $c['f'];
                                                    } elseif ($minMax2 != "" && $minMax2 == "<") {
                                                        echo 'Max ' . $c['f'];
                                                    } else {
                                                        echo $c['f'];
                                                    }
                                                    ?>
                                                </div>
                                                <div class="col-2-left">
                                                    <?= $c['v']; ?>
                                                </div>
                                            </div>
                                            <?
                                        } ?>

                                    </div>
                                    <div class="col-4-left">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <a target="_blank" href="/sr-search/?<?= $ar['originals']; ?>">Repeat
                                                    Search</a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <?

                            }

                            ?>
                        </div>
                        <div id="tabs-4">
                            <div class="loaderImage"><img id="ajax-loader2"
                                                          src="<?php echo $sr->plugin_dir ?>resources/images/ajax2.gif"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4-left">
                    <h1>Recently viewed</h1>

                    <div id="recentlyViewed">
                        <div class="loaderImage"><img id="ajax-loader2"
                                                      src="<?php echo $sr->plugin_dir ?>resources/images/ajax2.gif"/>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <?
    } else {
    if ($_GET['search_user']) {
    function array_find($needle, $haystack)
    {
        foreach ($haystack as $key => $item) {
            if (strpos($item, $needle) !== FALSE || array_find($needle, $item) !== FALSE) {
                return $key;
            }
        }
    }

    function recursive_array_search($needle, $haystack)
    {
        $ar = array();
        foreach ($haystack as $key => $value) {
            $current_key = $key;
            if (preg_match("/$needle/i", $value) OR (is_array($value) && recursive_array_search($needle, $value) !== false)) {
                $ar[] = $current_key;
            }
        }
        if (!empty($ar)) {
            return $ar;
        }
        return false;
    }

    $key = recursive_array_search($_GET['user_search'], $users);
    ?>

        <div class="row pad">

            <div class="col-4-left"></div>
            <div class="col-2-left">

            </div>
            <div class="col-4-left">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-6-left">
                            <input type="text" hidden name="page" value="seo-rets-crm">
                            <input style="width:100%;" placeholder="Search user " type="text" name="user_search">

                        </div>
                        <div class="col-4-left">
                            <input type="submit" name="search_user" value="search" class="button">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="user-lists row">
            <?php
            foreach ($key as $keys) {
                ?>
                <div class="col-2-left ">
                    <div class="userBlock">
                        <div class="col-2-left">
                            <h1>
                                <a href="?page=seo-rets-crm&profile=<?php echo $users[$keys]['index']; ?>"><?= $users[$keys]['name']; ?></a>
                            </h1>
                            <?php if ($users[$keys]['u_mobile'] != "") { ?>
                                <p><a href="tel:<?php echo $users[$keys]['u_mobile']; ?>"><i
                                            class="fa fa-mobile"></i> <?php echo $users[$keys]['u_mobile'] ?></a></p>
                            <?php } ?>

                            <?php if ($users[$keys]['email'] != "") { ?>
                                <p><a target="_blank" href="mailto:<?php echo $users[$keys]['email'] ?>"><i
                                            class="fa fa-envelope"></i> <?php echo $users[$keys]['email'] ?></a></p>
                            <?php } ?>
                        </div>
                        <div class="col-2-left">
                            <ul>
                                <li>
                                <span id="last-login">Last Login:
                                    <?php echo $users[$keys]['last_login']; ?></span>
                                </li>
                                <li>
                                <span id="last-login">Listings in favorites:
                                    <?php echo count($users[$keys]['favorites']);
                                    ?>
                                </span>
                                </li>
                                <li>
                                <span id="last-login">Total view listings:
                                    <?php echo count($users[$keys]['other']);
                                    ?>
                                </span>
                                </li>
                                <li>
                                <span id="last-login">Saved search:
                                    <?php echo count($users[$keys]['savesearch']);
                                    ?>
                                </span>
                                </li>
                                <li>
                                <span id="last-login">Status:
                                    <?php
                                    if ($users[$keys]['status'] != "") {
                                        echo $users[$keys]['status'];
                                    } else {
                                        echo "Potential Client";
                                    }
                                    ?>
                                </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="row pad">

            <div class="col-4-left"></div>
            <div class="col-2-left">

            </div>
            <div class="col-4-left">
                <a style="float: right" href="?page=seo-rets-crm">Back to all users</a>
            </div>
        </div>
        <?
    } else {
        ?>
        <div class="row pad">

            <div class="col-4-left"></div>
            <div class="col-2-left">
                <?
                $page = !empty($_GET['pages']) ? (int)$_GET['pages'] : 1;
                $total = count($users); //total items in array
                $limit = 10; //per page
                $totalPages = ceil($total / $limit); //calculate total pages
                $page = max($page, 1); //get 1 page when $_GET['page'] <= 0
                $page = min($page, $totalPages); //get last page when $_GET['page'] > $totalPages
                $offset = ($page - 1) * $limit;
                if ($offset < 0) $offset = 0;

                $users = array_slice($users, $offset, $limit);
                $link = 'admin.php?page=seo-rets-crm&pages=%d';
                $pagerContainer = '<div class="pagination__DIV" style="width: 300px;">';
                if ($totalPages != 0) {
                    for ($i = 1; $i <= $totalPages; $i++) {
                        if ($page == $i) {
                            $pagerContainer .= sprintf('<a class="pagiA activePage" href="' . $link . '">' . $i . '</a>', $i);
                        } else {
                            $pagerContainer .= sprintf('<a class="pagiA" href="' . $link . '">' . $i . '</a>', $i);
                        }
                    }
                }
                $pagerContainer .= '</div>';

                echo $pagerContainer;
                ?>
            </div>
            <div class="col-4-left">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-6-left">
                            <input type="text" hidden name="page" value="seo-rets-crm">
                            <input style="width:100%;" placeholder="Search user " type="text" name="user_search">

                        </div>
                        <div class="col-4-left">
                            <input type="submit" name="search_user" value="search" class="button">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="user-lists row">
            <?php
            foreach ($users as $index => $user) {
                $user['index'] = $index;
                if ($user['email']) {
                    ?>
                    <div class="col-2-left ">
                        <div class="userBlock">
                            <div class="col-2-left">
                                <h1>
                                    <a href="?page=seo-rets-crm&profile=<?php echo $user['index']; ?>"><?= $user['name']; ?></a>
                                </h1>
                                <?php if ($user['u_mobile'] != "") { ?>
                                    <p><a href="tel:<?php echo $user['u_mobile']; ?>"><i
                                                class="fa fa-mobile"></i> <?php echo $user['u_mobile'] ?></a></p>
                                <?php } ?>

                                <?php if ($user['email'] != "") { ?>
                                    <p><a target="_blank" href="mailto:<?php echo $user['email'] ?>"><i
                                                class="fa fa-envelope"></i> <?php echo $user['email'] ?></a></p>
                                <?php } ?>
                            </div>
                            <div class="col-2-left">
                                <ul>
                                    <li>
                                <span id="last-login">Last Login:
                                    <?php echo $user['last_login']; ?></span>
                                    </li>
                                    <li>
                                <span id="last-login">Listings in favorites:
                                    <?php echo count($user['favorites']);
                                    ?>
                                </span>
                                    </li>
                                    <li>
                                <span id="last-login">Total view listings:
                                    <?php echo count($user['other']);
                                    ?>
                                </span>
                                    </li>
                                    <li>
                                <span id="last-login">Saved search:
                                    <?php echo count($user['savesearch']);
                                    ?>
                                </span>
                                    </li>
                                    <li>
                                <span id="last-login">Status:
                                    <?php
                                    if ($user['status'] != "") {
                                        echo $user['status'];
                                    } else {
                                        echo "Potential Client";
                                    }
                                    ?>
                                </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <?
                }
            }
            ?>
        </div>
        <div class="row pad">
            <? echo $pagerContainer;
            ?>
<!--            <a href="admin.php?page=seo-rets-crm&csv=yes">Get csv</a>-->
        </div>
    <? }
    }
    }
    else {
    ?>
        <div class="alertInfo">
            There are no users registered with Seo Rets.
        </div>
        <?
    }
    ?>
</div>
<? }
else {
    ?>
    <div class="alertInfo">
        Sorry you don't have permission to this page!!!
    </div>
    <?
}
?>