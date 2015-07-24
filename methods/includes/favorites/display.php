<?php

$sr = $this;

if ( count($user['favorites']) > 0 ):
    if (isset($templates['css'])){?>
        <?php
        wp_enqueue_style('sr_method_display',$this->css_resources_dir.'methods/display.css');
        wp_add_inline_style('sr_method_display',$templates['css']);
        wp_print_styles(array('sr_method_display'));
        ?>
<!--        <style type="text/css">-->
<!--            --><?php //echo $templates['css']; ?>

<!--        </style>-->
    <?php
    }
    foreach ( $user['favorites'] as $index => $favorite ):

        $server_name = $this->feed->server_name;
        $match = array();
        if (preg_match("/^([a-zA-Z]+)\\.([a-zA-Z]+)$/", $favorite['type'], $match)) {
            $server_name = $match[1];
        }
        $photo_dir = "http://img.seorets.com/" . $server_name;

        $request = $this->api_request('get_listings', array(
            'type' => $favorite['type'],
            'query' => array(
                'boolopr' => 'AND',
                'conditions' => array(
                    array(
                        'field' => 'mls_id',
                        'operator' => '=',
                        'value' => $favorite['mls']
                    )
                )
            ),
            'limit' => array(
                'range' => 1,
                'offset' => 0
            )
        ));


        $l = $request->result[0];
        $l->city2 = preg_replace('/\s/', '+', $l->city);
        $url = $this->listing_to_url($l, $favorite['type']);
        $templates = get_option('sr_templates');
        $type = $favorite['type'];

        echo '<div class="sr-fav"><a href="' . get_bloginfo('url') . '/sr-favorites?remove=' . $index . '" style="float:right;color:red;">Remove</a>';

        if ( isset($templates['result']) ) {
            if (isset($templates['css'])){?>

                <?php echo $templates['css']; ?>
            <?php
            }
            else{
                include ($sr->resp_css);
            }
            eval('?>' . $templates['result']);
        } else {
            include($this->server_plugin_dir . '/resources/defaults/template-result.php');
        }


        echo '<div style="clear:both"></div></div>';
    endforeach;

else: ?>
    <p>You haven't saved any listings yet.</p>
<?php endif; ?>


<a href="<?php echo get_bloginfo('url')?>/sr-logout">Logout</a>
