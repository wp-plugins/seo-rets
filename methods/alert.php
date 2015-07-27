<?php if (isset($_GET['address'])) : ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
        "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">

        <head>

            <title>SEO RETS :: Email Alerts</title>
            <?php
            wp_enqueue_style('sr_method_alert_css',$this->css_resources_dir.'methods/alert.css');
            wp_print_styles(array('sr_method_alert_css'));
            ?>



            <script type="text/javascript">
                var listing = <?php echo json_encode($_GET) ?>;
            </script>
            <?php wp_enqueue_script('sr_method_alert',$this->js_resources_dir.'methods/alert.js',array( 'jquery' ));
            wp_print_scripts(array('sr_method_alert'));
            ?>

        </head>

        <body>
            <div style="text-align:center;" id="container">
                Sign Up For Email Alerts For:
                <p>Get daily alerts when the price changes, new photos, and more as listings are changed or added to the MLS. Just choose the type of alert you would like to receive:</p>
                <div>
                    <input type="radio" name="type" value="mls_id" checked="checked" /> <strong>Only for <?php echo htmlentities($_GET['address']) ?>, <?php echo htmlentities($_GET['city']) ?>, <?php echo htmlentities($_GET['state']) ?></strong><br />
                    <input type="radio" name="type" value="city" /> Changes and new additions for all of <?php echo htmlentities($_GET['city']) ?>, <?php echo htmlentities($_GET['state']) ?><br />
                    <input type="radio" name="type" value="zip" /> Changes and new additions within <?php echo htmlentities($_GET['zip']) ?><br />
                </div>

                <div style="margin-top:30px;">
                    Name: <input type="text" id="sr-name" /><br />
                    Email: <input type="text" id="email" /><br />
                    <button id="signup">Sign Up</button><br/>
                    <p>Not enough options? <a href="sr-alert" target="_parent">Click here</a> to further customize your listing alerts.</p>
                </div>
                <!-- Commented by David Pope - Broken and need rewrite.
                <!--<div>
                    <iframe src="http://www.google.com/recaptcha/api/noscript?k=6Lfki9ESAAAAANZ3ZaQPg6l7W6v2hV3TrayhR9_j" height="300" width="500" frameborder="0" id="captch_frame"></iframe><br/>
                    <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
                    <input type="hidden" name="recaptcha_response_field" value="manual_challenge"/>
                </div>-->  
            </div>
        </body>

    </html>
    <?php
//    exit;
else :
    $currentPage->post_content = do_shortcode("[sr-subscribe]");
    $currentPage->post_title = "Email Alerts";
endif;
//<?php //Window sr-subscribe?email=pope.davidc%40gmail.com&sr-name=this+is+a+test&conditions%5B1%5D%5Bvalue%5D=&conditions%5B2%5D%5Bvalue%5D=&conditions%5B5%5D%5Bvalue%5D=&conditions%5B6%5D%5Bvalue%5D=&conditions%5B7%5D%5Bvalue%5D=&conditions%5B8%5D%5Bvalue%5D=&conditions%5B31%5D%5Bvalue%5D=&conditions%5B32%5D%5Bvalue%5D=&type=Homes&perpage=20&conditions%5B3%5D%5Boperator%5D=LIKE&conditions%5B3%5D%5Bloose%5D=1&conditions%5B3%5D%5Bfield%5D=area&conditions%5B1%5D%5Boperator%5D=%3E%3D&conditions%5B1%5D%5Bfield%5D=price&conditions%5B2%5D%5Boperator%5D=%3C%3D&conditions%5B2%5D%5Bfield%5D=price&conditions%5B5%5D%5Boperator%5D=%3E%3D&conditions%5B5%5D%5Bfield%5D=bedrooms&conditions%5B6%5D%5Boperator%5D=%3C%3D&conditions%5B6%5D%5Bfield%5D=bedrooms&conditions%5B7%5D%5Boperator%5D=%3E%3D&conditions%5B7%5D%5Bfield%5D=baths_full&conditions%5B8%5D%5Boperator%5D=%3C%3D&conditions%5B8%5D%5Bfield%5D=baths_full&conditions%5B31%5D%5Boperator%5D=%3E%3D&conditions%5B31%5D%5Bfield%5D=sqft&conditions%5B32%5D%5Boperator%5D=%3C%3D&conditions%5B32%5D%5Bfield%5D=sqft&recaptcha_challenge_field=03AHJ_Vut_bKIoehjeqe3_WC5MSE5ifej8u8eTOzbrxrl-UiguMdDfDFoC7PVs_E6m2UbQFWuV1FTZVETm0-Z28vLGB4-5_sC3MBHdCLa3A4On7J6KqSjk2dyfN5MQZgeqJCdsOB8-gOAczkUEiPSzOXxMMjfWaxZOOYRvVolrJtGeJ1Y8WnYLxaI&recaptcha_response_field=elfyll+was