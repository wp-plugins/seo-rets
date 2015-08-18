<script >

    jQuery(document).ready(function(){
        checkWindowWidth();
        jQuery(window).resize(checkWindowWidth);
    });
    function swapNodes(a, b) {

    }
    var checkWindowWidth=function checkWindowWidth(){
        var contentChildren=jQuery('#content').children();
        if ((typeof contentChildren[0] !='undefined' )&&(contentChildren[0].id=='sidebar') &&(jQuery(window).width()<540)){
            var a=contentChildren[0];
            var b=contentChildren[1];
            var aparent= a.parentNode;
            var asibling= a.nextSibling===b? a : a.nextSibling;
            b.parentNode.insertBefore(a, b);
            aparent.insertBefore(b, asibling);
        }
        if ((typeof contentChildren[0] !='undefined' ) &&(contentChildren[0].id=='page') &&(jQuery(window).width()>540)){
            var a=contentChildren[0];
            var b=contentChildren[1];
            var aparent= a.parentNode;
            var asibling= a.nextSibling===b? a : a.nextSibling;
            b.parentNode.insertBefore(a, b);
            aparent.insertBefore(b, asibling);
        }
    }
</script>
<style>
    .sr-listing-title {
        font-size: 15px;
    }
    .sr-info-section {
        font-size: 16px;
    }
    .sr-listing-title-price {
        font-size: 17px;
        color: #98bd15;
    }
    .sr-listing-title {
        background-color: #f5f5f5;
    }
    .sr-float-right {
        text-align: right;
    }
    /* Comments */

    .comments-area {
        display: none;
    }
</style>