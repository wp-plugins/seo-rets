
<?php
wp_enqueue_style('sr_templates_list',$this->css_resources_dir.'templates/list.css');
wp_print_styles(array('sr_templates_list'));

$prefix = $type . '_' . $object;
?>
<div class="<?php echo $prefix; ?> sr-list-main">

    <?php

    $alphas = range('A', 'Z');

    if ($object == "city") {
        $link = "/sr-cities/";
    } elseif ($object == "subdivision") {
        $link = "/sr-communities/";
    } elseif (($type == 'cnd')||($object=='proj_name')) {
        $link = "/sr-condos/";
    } else {
        $link = 'die';
    }
    ?>

    <?php

    $lettersAmount=array();
    foreach ($alphas as $alpha) {
        echo '<a class="' . $alpha . ' sr_alpha" href="#' . $prefix.'_'.$alpha . '">' . $alpha . '</a>';
        $lettersAmount[$alpha]=0;
    }

    $perPage = 50;
    $perColumn = 25;
    $z=0;
    $count = count($request->result);

    $columns=array();


    foreach ($request->result as $key => $element) {
        $element = trim($element);
        preg_match('/^[^A-Za-z\s]+/', $element, $matches, PREG_OFFSET_CAPTURE);
        if (!empty($matches)) {
            $aLenHalf = round(strlen($request->result[$z]) / 2);
            preg_match('/\s/', $element, $matches2, PREG_OFFSET_CAPTURE);
            if (empty($matches2) || ($matches2[0][1] > $aLenHalf)) {
                $secondName = preg_replace('/^[^A-Za-z\s]+/', '', $element);
            } else {
                $secondName = preg_replace('/^[^A-Za-z\s]+.*?\s/', '', $element);
            }
        } else {
            $secondName = $element;
        }
        $secondName = ucfirst($secondName);
        if (empty($secondName[0]) || $secondName[0] == '') {
            echo '1' . $element . '1';
        }

        $lettersAmount[$secondName[0]]++;
    }
    foreach ($lettersAmount as $letter => $value) {
        $columnNumber = 1;
        $summ = 0;
        while ($value >= $perPage) {
            $summ += $perColumn;
            $columns[$letter][$columnNumber] = $summ;
            $columnNumber++;
            $summ += $perColumn;
            $columns[$letter][$columnNumber] = $summ;
            $columnNumber++;
            $value = $value - $perColumn - $perColumn;
        }
        if ($value > 1 && $value < $perPage) {
            $summ += ceil($value / 2);
            $columns[$letter][$columnNumber] = $summ;
            $columnNumber++;
            $summ += $value - ceil($value / 2);
            $columns[$letter][$columnNumber] = $summ;
        } elseif ($value == 1) {
            $summ++;
            $columns[$letter][$columnNumber] = $summ;
        }
    }

    echo '<div class="sr-List">';

    foreach ($alphas as $alpha) {
        $page = 2;
        $t = 0;
        $column = 1;
        echo '<div class="letter ' . $alpha . '"><a name="' . $prefix . '_' . $alpha . '"></a><div class="letterData' . $alpha . '">';
        echo '<div class="' . $alpha . '_1"><div class="listColumn"><ul>';



        preg_match('/^[^A-Za-z\s]+/', $request->result[$z], $matches, PREG_OFFSET_CAPTURE);
        if (!empty($matches)) {
            $aLenHalf = round(strlen($request->result[$z]) / 2);
            preg_match('/\s/', $request->result[$z], $matches2, PREG_OFFSET_CAPTURE);
            if (empty($matches2) || ($matches2[0][1] > $aLenHalf)) {
                $secondName = preg_replace('/^[^A-Za-z\s]+/', '', $request->result[$z]);
            } else {
                $secondName = preg_replace('/^[^A-Za-z\s]+.*?\s/', '', $request->result[$z]);
            }
        } else {
            $secondName = $request->result[$z];
        }
        $secondName = ucfirst($secondName);



        while ((empty($secondName) || !preg_match('/[A-Z]/', $secondName)) && ($z < $request->count)) {
            $z++;
        }
        while (($alpha === $secondName[0]) && ($z < $request->count)) {
            while ((empty($secondName) || !preg_match('/[A-Z]/', $secondName)) && ($z < $request->count)) {
                $z++;
            }

            $currObjectLink = get_bloginfo("url") . $link . preg_replace('/\s/', '+', $request->result[$z]) . '/' . $type;
            echo '<li class="li"><span class="SRA_element"><a href="' . $currObjectLink . '">' . $secondName . '</a></span></li>';
            $z++;
            $t++;
            if ($t % $perPage == 0) {
                echo '</ul></div></div>';
                if ($t!=end($columns[$alpha])){
                    echo '<div class="' . $alpha . '_' . $page . '" style="display:none"><div class="listColumn"><ul>';
                }
                else{
                    $flag1=true;
                    break;
                }
                $column++;
                $page++;
            }
            if ($t == $columns[$alpha][$column]) {
                echo '</ul></div><div class="listColumn"><ul>';
                $column++;
            }

            preg_match('/^[^A-Za-z\s]+/', $request->result[$z], $matches, PREG_OFFSET_CAPTURE);
            if (!empty($matches)) {
                $aLenHalf = round(strlen($request->result[$z]) / 2);
                preg_match('/\s/', $request->result[$z], $matches2, PREG_OFFSET_CAPTURE);
                if (empty($matches2) || ($matches2[0][1] > $aLenHalf)) {
                    $secondName = preg_replace('/^[^A-Za-z\s]+/', '', $request->result[$z]);
                } else {
                    $secondName = preg_replace('/^[^A-Za-z\s]+.*?\s/', '', $request->result[$z]);
                }
            } else {
                $secondName = $request->result[$z];
            }
            $secondName = ucfirst($secondName);
        }

        if (isset($flag1)&&$flag1){
            echo '</div>';
            $flag1=false;
        }
        else{
            echo '</ul></div></div></div>';
        }

        if ($z == $request->count) {
            break;
        }
        echo '<div style="clear:both"></div>';
        if ($page > 2 && $lettersAmount[$alpha] != $perPage) {
            $page--;
            echo '<div class="' . $alpha . '">';
            for ($i = 1; $i <= $page; $i++) {
                echo '<a class="page pageNumber pageNumber_' . $i . '">' . $i . '</a> |';
            }
            echo '</div>';
        }
        echo '<div class="clear"></div></div>';
    }
    echo '</div>';
    echo '<div class="clear"></div></div>';
    ?>

    <script type="text/javascript">
        if (typeof window.defaultAlphaID == 'undefined') {
            var defaultAlphaID = Array();
        }
        window.defaultAlphaID.<?php echo $prefix;?> = 'A';
        jQuery(document).ready(function () {
            jQuery('.<?php echo $prefix;?> .page').click(function () {
                var prefix = "<?php echo $prefix;?>";
                var part2 = jQuery(this).text();
                var part1 = jQuery(this).parent().attr("class");
                jQuery(' .' + prefix + ' .letterData' + part1 + ' div').hide();
                jQuery(' .' + prefix + ' .' + part1 + '_' + part2).show();
                jQuery(' .' + prefix + ' .' + part1 + '_' + part2 + ' div').show();
            });


        });
    </script>
</div>