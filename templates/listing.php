<?php
$l = $listing;
$l->city2 = preg_replace('/\s/', '+', $l->city);
$l->subdivision2 = preg_replace('/\s/', '+', $l->subdivision);

wp_enqueue_style('sr_templates_listing',$this->css_resources_dir.'templates/listing.css');
wp_print_styles(array('sr_templates_listing'));
?>

    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('.zoom-gallery').magnificPopup({
                delegate: 'a',
                type: 'image',
                closeOnContentClick: false,
                closeBtnInside: false,
                mainClass: 'mfp-with-zoom mfp-img-mobile',
                image: {
                    verticalFit: true,
                    titleSrc: function (item) {
                        return item.el.attr('title') + ' &middot; <a class="image-source-link" href="' + item.el.attr('data-source') + '" target="_blank">image source</a>';
                    }
                },
                gallery: {
                    enabled: true
                },
                zoom: {
                    enabled: true,
                    duration: 300, // don't foget to change the duration also in CSS
                    opener: function (element) {
                        return element.find('img');
                    }
                }

            });
        });
        var sr_plugin_dir = '<?php echo $sr->plugin_dir?>';

        var sr_popup2;

        function close_popup2() {
            jQuery("#sr-popup-form2").fadeOut("slow", function() {
                sr_popup2.fadeOut("slow");
            });
        };


        jQuery(function($) {

            $('#main-photo-a').click(function (){
                $('.sr-listing-photos .sr-thumbs a:eq(0)').click();
            });

            $(".sr-listing-photos .sr-thumbs a").mouseover(function() {
                var sender = $(this);
                $(".sr-listing-photo-details-main").attr('src', $(sender.children()[0]).attr('src'));
                $('#main-photo-a').unbind("click").click(function (){sender.click();});
            });
            $("#sr-alert").click(function () {
//                var mfp = jQuery.magnificPopup.instance;
                var htm = '<div id="sr-popup2" class="zoom-anim-dialog"><iframe style="width: 100%;height:300px;border:0;" id="popup-iframe" border="0" scrolling="no" src="<?php echo get_bloginfo('url')?>/sr-alert?mls_id=<?php echo urlencode($l->mls_id)?>&type=<?php echo urlencode($wp_query->query['sr_type'])?>&address=<?php echo urlencode($l->address)?>&city=<?php echo urlencode($l->city)?>&state=<?php echo urlencode($l->state)?>&zip=<?php echo urlencode($l->zip)?>"></iframe></div>';
                jQuery.magnificPopup.open({
                    items: {
                        src:'<?php echo get_bloginfo('url') . '/sr-alert?mls_id=' . urlencode($l->mls_id) . '&type=' . urlencode($wp_query->query['sr_type']) . '&address=' . urlencode($l->address) . '&city=' . urlencode($l->city) . '&state=' . urlencode($l->state) . '&zip=' . urlencode($l->zip) ?>',
                        type: 'ajax'
                    },
                    fixedContentPos: false,
                    fixedBgPos: true,

                    overflowY: 'auto',

                    closeBtnInside: true,
                    preloader: false,

                    midClick: true,
                    closeOnBgClick: false,
                    removalDelay: 300,
                    mainClass: 'my-mfp-zoom-in'
                });
            });
//            $("#sr-alert").click(function() {
//                $("#sr-popup2").remove();
//
//                var htm = '<div id="sr-popup2" style="display:none;"> <style>#sr-popup-form2 { background-color: #f0f4f5; transition: 0.5s; } @media only screen and (min-width: 630px) { #sr-popup-form2 { height: 360px; width: 565px; max-height: 95%; } iframe#popup-iframe { height: 340px; } } @media only screen and (max-width: 630px) { #sr-popup-form2 { min-width: 200px; padding-right: 20px; max-height: 595px; width: 95%; height: 95%; } iframe#popup-iframe { max-height: 570px; } }</style> <div id="sr-popup-form2" style="display:none;"><a href="javascript: void(0);"><img src="<?php //echo $sr->plugin_dir ?>//resources/images/close.png" id="sr-popup-close2"/></a> <iframe id="popup-iframe" style="border:0;" border="0" scrolling="no" src="<?php //bloginfo('url'); ?>///sr-alert?mls_id=<?php //echo urlencode($l->mls_id) ?>//&type=<?php //echo urlencode($wp_query->query['sr_type']) ?>//&address=<?php //echo urlencode($l->address) ?>//&city=<?php //echo urlencode($l->city) ?>//&state=<?php //echo urlencode($l->state) ?>//&zip=<?php //echo urlencode($l->zip) ?>//"></iframe> </div> </div>';
//
//                $("body").append(htm);
//
//                sr_popup2 = $("#sr-popup2");
//
//                sr_popup2.fadeIn("slow", function() {
//                    $("#sr-popup-form2").fadeIn("slow");
//                });
//
//                $("#sr-popup-close2").click(close_popup2);
//            });
        });

    </script>

<?php
$templates = get_option('sr_templates');
$extraData['backLink']=array_key_exists('HTTP_REFERER',$_SERVER)?$_SERVER['HTTP_REFERER']:'';
$extraData['siteUrl']=get_site_url();

if (isset($tmp)){

    switch ($tmp) {
        case "community":
            $template=get_option('sr_templates_community');
            eval('?>' . $template);
            break;
        case "overview":
            $template=get_option('sr_templates_overview');
            eval('?>' . $template);
            break;
        case "features":
            $template=get_option('sr_templates_features');
            eval('?>' . $template);
            break;
        case "map":
            $template=get_option('sr_templates_map');
            eval('?>' . $template);
            break;
        case "video":
            $template=get_option('sr_templates_video');
            eval('?>' . $template);
            break;
    }
}
else{
    update_post_meta($currentPage->ID, '_wp_page_template', get_theme_root().'/era-test/page_wide.php');

    if ( isset($templates['details']) ) {
        if (isset($templates['css'])){?>

            <?php echo $templates['css']; ?>

        <?php
        }
        else{
            include ($sr->resp_css);
        }

        eval('?>' . $templates['details']);
        //echo $templates['details'];
    } else {
        include($this->details_template);
    }
    if ($extraFieldsTemplate['show_related_properties']=='true'){
        echo '<h2 class="entry-title">Related Listings</h2>';
        $listings = $addRequest->result;
        include($sr->server_plugin_dir . "/templates/results.php");
    }
}


