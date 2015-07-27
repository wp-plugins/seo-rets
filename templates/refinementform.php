[sr-search type="script"]

<script type="text/javascript">
    jQuery(function(){
        var form				= jQuery("#refinesearch");
        var refinebtn		= jQuery("#sr-refinebtn");
        var bedsfield		= jQuery("#sr-bedsfield");
        var bathsfield	= jQuery("#sr-bathsfield");
        var pricefieldl	= jQuery("#sr-pricefieldl");
        var pricefieldh	= jQuery("#sr-pricefieldh");

        var request = <?php echo json_encode($query)?>;
        var query = request.q;
        form.attr("srtype", request.t);

        if (query.b !== 1) {
            query = {b:1,c:[query]};
        }

        for (var i=0;i<query.c.length;i++) {
            var cond = query.c[i];

            if (sr_parse_condition(cond)) {
                query.c.splice(i,1);
                i--;
            }
        }

        function format_money(amount, decimals, decimal_sep, thousands_sep) {
            var n = amount,
                c = isNaN(decimals) ? 2 : Math.abs(decimals),
                d = decimal_sep || '.',
                t = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                sign = (n < 0) ? '-' : '',
                i = parseInt(n = Math.abs(n).toFixed(c)) + '',
                j = ((j = i.length) > 3) ? j % 3 : 0;
            return sign + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '');
        }

        function sr_parse_condition(condition) {
            if (condition.f == "bedrooms" && (condition.o == ">=" || condition.o == ">")) {
                if (condition.o == ">") {
                    condition.v++;
                }
                bedsfield.find("option[value=" + condition.v + "]").attr("selected", "selected");
            } else if (condition.f == "baths" && (condition.o == ">=" || condition.o == ">")) {
                if (condition.o == ">") {
                    condition.v++;
                }
                bathsfield.find("option[value=" + condition.v + "]").attr("selected", "selected");
            } else if (condition.f == "price") {
                if (condition.o == ">" || condition.o == ">=") {
                    pricefieldl.val("$" + format_money(condition.v, 0));
                } else if (condition.o == "<" || condition.o == "<=") {
                    pricefieldh.val("$" + format_money(condition.v, 0));
                }
            } else {
                return false;
            }
            return true;
        }


        refinebtn.click(function(){
            var newrequest = seorets.getFormRequest(form);
            request.q = newrequest.q;
            request.q.c = request.q.c.concat(query.c);
            request.g = 1;
            window.location = "?" + encodeURIComponent(Base64.encode(JSON.stringify(request)));
        });
    });
</script>
<div id="refinesearch">
    <table>
        <tr>
            <td class="sr-refine-label" colspan="3">
                Refine Your Search
            </td>
        </tr>
        <tr>
            <?php
            wp_enqueue_style('sr_templates_refinementform',$this->css_resources_dir.'templates/refinementform.css');
            wp_print_styles(array('sr_templates_refinementform'));
            ?>

            <td style="padding-right:10px;">
                <div class="sr-search-bar" >
                    <div class="sr-search-bar-item" id="sr-selects">
                        <div class="sr-search-bar-sub-item" id="sr-bedrooms">
                            Bedrooms:
                            <select class="sr-formelement" id="sr-bedsfield" name="fk30c" srfield="bedrooms"
                                    srtype="numeric" sroperator=">=">
                                <option value="">Any</option>
                                <option value="1">1+</option>
                                <option value="2">2+</option>
                                <option value="3">3+</option>
                                <option value="4">4+</option>
                                <option value="5">5+</option>
                                <option value="6">6+</option>
                                <option value="7">7+</option>
                                <option value="8">8+</option>
                            </select>
                        </div>
                        <div class="sr-search-bar-sub-item" id="sr-baths">
                            Baths: <select class="sr-formelement" id="sr-bathsfield" name="3jc7q" srfield="baths"
                                           srtype="numeric" sroperator=">=">
                                <option value="">Any</option>
                                <option value="1">1+</option>
                                <option value="2">2+</option>
                                <option value="3">3+</option>
                                <option value="4">4+</option>
                                <option value="5">5+</option>
                                <option value="6">6+</option>
                                <option value="7">7+</option>
                                <option value="8">8+</option>
                            </select>
                        </div>
                    </div>
                    <div class="sr-search-bar-item" id="sr-inputs">

                        Price Range: <input type="text" id="sr-pricefieldl" name="sd94j" class="sr-formelement"
                                            srfield="price" srtype="numeric" sroperator=">=" size="9"/> - <input
                            type="text" id="sr-pricefieldh" name="mkx73" class="sr-formelement" srfield="price"
                            srtype="numeric" sroperator="<=" size="9"/>
                    </div>
            </td>
        </tr>
    </table>
    <input type="submit" id="sr-refinebtn" value="Refine"/>
</div>