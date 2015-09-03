<script type="text/javascript">

    <?php
    $short_conditions = $sr->convert_to_search_conditions($qcc);
    $shortcode_conditions = json_encode($short_conditions['c']);
    ?>

    function str_to_float(str) {
        return parseFloat(str.replace(/[^0-9.]/g, ""));
    }

    if (typeof window.Base64 === "undefined") window.Base64 = {
// private property
        _keyStr: "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",

// public method for encoding
        encode: function (input) {
            var output = "";
            var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
            var i = 0;

            input = Base64._utf8_encode(input);

            while (i < input.length) {

                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);

                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;

                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }

                output = output +
                    this._keyStr.charAt(enc1) + this._keyStr.charAt(enc2) +
                    this._keyStr.charAt(enc3) + this._keyStr.charAt(enc4);

            }

            return output;
        },

// public method for decoding
        decode: function (input) {
            var output = "";
            var chr1, chr2, chr3;
            var enc1, enc2, enc3, enc4;
            var i = 0;

            input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

            while (i < input.length) {

                enc1 = this._keyStr.indexOf(input.charAt(i++));
                enc2 = this._keyStr.indexOf(input.charAt(i++));
                enc3 = this._keyStr.indexOf(input.charAt(i++));
                enc4 = this._keyStr.indexOf(input.charAt(i++));

                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;

                output = output + String.fromCharCode(chr1);

                if (enc3 != 64) {
                    output = output + String.fromCharCode(chr2);
                }
                if (enc4 != 64) {
                    output = output + String.fromCharCode(chr3);
                }

            }

            output = Base64._utf8_decode(output);

            return output;

        },

// private method for UTF-8 encoding
        _utf8_encode: function (string) {
            string = string.replace(/\r\n/g, "\n");
            var utftext = "";

            for (var n = 0; n < string.length; n++) {

                var c = string.charCodeAt(n);

                if (c < 128) {
                    utftext += String.fromCharCode(c);
                }
                else if ((c > 127) && (c < 2048)) {
                    utftext += String.fromCharCode((c >> 6) | 192);
                    utftext += String.fromCharCode((c & 63) | 128);
                }
                else {
                    utftext += String.fromCharCode((c >> 12) | 224);
                    utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                    utftext += String.fromCharCode((c & 63) | 128);
                }

            }

            return utftext;
        },

// private method for UTF-8 decoding
        _utf8_decode: function (utftext) {
            var string = "";
            var i = 0;
            var c = c1 = c2 = 0;

            while (i < utftext.length) {

                c = utftext.charCodeAt(i);

                if (c < 128) {
                    string += String.fromCharCode(c);
                    i++;
                }
                else if ((c > 191) && (c < 224)) {
                    c2 = utftext.charCodeAt(i + 1);
                    string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                    i += 2;
                }
                else {
                    c2 = utftext.charCodeAt(i + 1);
                    c3 = utftext.charCodeAt(i + 2);
                    string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                    i += 3;
                }

            }

            return string;
        }

    };

    jQuery(function ($) {
        $("#sr-refinebtn").click(function () {
            var conditions = <?php echo $shortcode_conditions?>;

            if ($("#bedrooms").val() != "") conditions.push({
                "f": "bedrooms",
                "o": ">=",
                "v": parseInt($("#bedrooms").val())
            });

            if ($("#baths").val() != "") conditions.push({
                "f": "baths",
                "o": ">=",
                "v": parseInt($("#baths").val())
            });


            if ($("#price-low").val() != "") conditions.push({
                "f": "price",
                "o": ">=",
                "v": str_to_float($("#price-low").val())
            });

            if ($("#price-high").val() != "") conditions.push({
                "f": "price",
                "o": "<=",
                "v": str_to_float($("#price-high").val())
            });


            var request = {
                "q": {
                    "b": 1,
                    "c": conditions
                },
                "t": "<?php echo $type?>",
                "p": 10,
                "g": 1
            };


            document.location = "<?php echo get_bloginfo('url')?>/sr-search?" + encodeURIComponent(Base64.encode(JSON.stringify(request)));
        });
    });


</script>
<div class="sr-content" id="refinesearch">
    <div class="row">
        <div class="col-md-12">
            <span>Refine Your Search</span>
        </div>
    </div>
    <div class="row margin-top-10">
        <div class="col-md-6 col-sm-6">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <label for="bedrooms">Bedrooms:</label>
                    <select class="form-control" id="bedrooms">
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
                <div class="col-md-6 col-sm-6">
                    <label for="">Baths:</label>
                    <select class="form-control" id="bedrooms">
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
        </div>
        <div class="col-md-6 col-sm-6">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <label for="">Price:</label>
                    <input class="form-control" type="text" size="9" placeholder="Min" id="price-low">
                </div>
                <div class="col-md-4 col-sm-4">
                    <label for="">&nbsp;</label>
                    <input class="form-control" type="text" size="9" placeholder="Max" id="price-high">
                </div>
                <div class="col-md-4 col-sm-4">
                    <label for="">&nbsp;</label>
                    <input type="submit" class="form-control" id="sr-refinebtn" value="Refine">
                </div>
            </div>
        </div>
    </div>
</div>