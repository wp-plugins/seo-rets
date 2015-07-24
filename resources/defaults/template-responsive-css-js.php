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
    /* Responsive*/
#page {
    width: 74%;
    padding-right:0;
}
#sidebar {
    width: 21%;
    min-width:153px;
}
#wrapper{
    max-width: 960px;
    width: auto;
}
#sidebar input[type|="text"] {
    width:100%
}
.sr-listing-image{
    display:table;
}
.sr-listing-descr{
    display: table-cell;
}
.sr-listing-image{
    float:left;
    margin-right: 10px;
}
.sr-listing-descr{

}
.sr-listing-clear{
    clear:both;
}

.basic_info {
    width: 40%;
}
.description {
    float: none;
    margin-left: 0 !important;
    text-align: left !important;
}
.sr-thumbs{
    margin-left:auto;
    margin-right:auto;
}
.sr-listing-photos {
    float: right;
    width: 50%;
    text-align: left;
    min-width:285px;
}
.smallScreen{
    display:none;
}
.sr-listing-photo-details-main{
    height: auto;
    width: 315px;
    max-height: 236px;
}
#page .sr-listing{
    margin-bottom: 50px;
}
#footer{
    min-height: 0;
}

#primary {
    width:70%;

}
#primary .sr-listing-photos{
    width: 58%
}
#primary .sr-listing-det-buttons{
    width:48%;
}
.backButton{
    width:20px;
}
    /* media styles*/
@media only screen
and (max-width : 990px) {

}
@media only screen
and (max-width : 940px) {
    .sr-listing-det-buttons img{
        width:47%;
    }
    .sr-listing-photo-details-main{
        width:95%;
    }
    .sr-thumbs a img{
        width:30% !important;
        height:auto;
    }
    #page{
        width:73%;
    }
}
@media only screen
and (max-width : 895px) {
    #page {
        width: 70%;
    }
    .sr-listing-photo {
    }
}
@media only screen
and (max-width : 760px) {
    .sr-listing-photo {
        height: auto;
    }
    .sr-listing {
        min-width: 0;
    }
    .sr-listing-image {
        float: none;
        text-align: center;
        display:block;
    }

    .sr-listing-descr {
        display:inline;
        text-align: center;
    }
    .srm-pages {
        min-width: 0;
    }
    .sr-listing {
        min-width: 0;
    }
    div.widget-area {
        margin-left: 10px;
    }
    .smallScreen{
        display:block;
    }
    .bigScreen{
        display:none;
    }
}

@media only screen
and (max-width : 700px) {
    #page{
        width:65%;
    }
}
@media only screen
and (max-width : 610px) {
    #page {
        width: 61%;
    }
}


@media only screen
and (max-width : 540px) {
    #page{
        width:95%;
        float:none;
    }
    #sidebar{
        float:none;
        margin-right: auto;
        margin-left: auto;
    }
}
@media only screen
and (max-width : 650px) {
    div.site-content{
        width:100%;
    }
    div.widget-area {
        float: none;
        margin-left: 0;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
    }
}

@media only screen
and (max-width : 635px) {
    div.description{
        float: right;
    }
}

@media only screen
and (max-width : 725px) {
    .sr-listing-photos{
        float: none;
    }
    .basic_info {
        float: none;
        width:50%;
        margin-right: auto;
        margin-left: auto;
    }
    div.sr-listing-photos {
        float:none;
        margin-right: auto;
        margin-left: auto;
    }
    .sr-listing-pg{

    }
}
@media only screen
and (max-width : 545px) {
    .sr-listing-photo-details-main{
        min-width:216px;
    }
    .sr-thumbs a img{
        min-width:68px;
    }
    .sr-listing-image {
        float: none;
        text-align: center;
    }
    .sr-listing-det-buttons img{
        min-width:107px;
    }

    .sr-listing-descr {
        text-align: center;
    }
    .srm-pages {
        min-width: 0;
    }
    .sr-listing {
        min-width: 0;
    }
    .sr-listing-photos {
        max-width: 330px;
        width: auto;
    }
}
@media only screen
and (max-width : 460px) {
    #navigation {
        top: 270px;
    }
    #agent-profile {
        top: 105px;
        left:0;
        text-align:left;
    }
    #phone-number {
        top: 225px;
        left: 147px;
    }
    #header{
        height: 299px;
    }
    #agent-photo {
        top: 0px;
        right: 10px;
    }
    #wrapper{
        min-width:310px;
    }

}
    /* Minimum width of 600 pixels. */
@media screen and (min-width: 600px) {
    .author-avatar {
        float: left;
        margin-top: 8px;
        margin-top: 0.571428571rem;
    }
    .author-description {
        float: right;
        width: 80%;
    }
    .site {
        margin: 0 auto;
        max-width: 960px;
        max-width: 68.571428571rem;
        overflow: hidden;
    }
    .site-content {
        float: left;
        width: 65.104166667%;
    }
    body.template-front-page .site-content,
    body.single-attachment .site-content,
    body.full-width .site-content {
        width: 100%;
    }
    .widget-area {
        float: right;
        width: 26.041666667%;
    }
    .site-header h1,
    .site-header h2 {
        text-align: left;
    }
    .site-header h1 {
        font-size: 26px;
        font-size: 1.857142857rem;
        line-height: 1.846153846;
        margin-bottom: 0;
    }
    .main-navigation ul.nav-menu,
    .main-navigation div.nav-menu > ul {
        border-bottom: 1px solid #ededed;
        border-top: 1px solid #ededed;
        display: inline-block !important;
        text-align: left;
        width: 100%;
    }
    .main-navigation ul {
        margin: 0;
        text-indent: 0;
    }
    .main-navigation li a,
    .main-navigation li {
        display: inline-block;
        text-decoration: none;
    }
    .main-navigation li a {
        border-bottom: 0;
        color: #6a6a6a;
        line-height: 3.692307692;
        text-transform: uppercase;
        white-space: nowrap;
    }
    .main-navigation li a:hover {
        color: #000;
    }
    .main-navigation li {
        margin: 0 40px 0 0;
        margin: 0 2.857142857rem 0 0;
        position: relative;
    }
    .main-navigation li ul {
        display: none;
        margin: 0;
        padding: 0;
        position: absolute;
        top: 100%;
        z-index: 1;
    }
    .main-navigation li ul ul {
        top: 0;
        left: 100%;
    }
    .main-navigation ul li:hover > ul {
        border-left: 0;
        display: block;
    }
    .main-navigation li ul li a {
        background: #efefef;
        border-bottom: 1px solid #ededed;
        display: block;
        font-size: 11px;
        font-size: 0.785714286rem;
        line-height: 2.181818182;
        padding: 8px 10px;
        padding: 0.571428571rem 0.714285714rem;
        width: 180px;
        width: 12.85714286rem;
        white-space: normal;
    }
    .main-navigation li ul li a:hover {
        background: #e3e3e3;
        color: #444;
    }
    .main-navigation .current-menu-item > a,
    .main-navigation .current-menu-ancestor > a,
    .main-navigation .current_page_item > a,
    .main-navigation .current_page_ancestor > a {
        color: #636363;
        font-weight: bold;
    }
    .menu-toggle {
        display: none;
    }
    .entry-header .entry-title {
        font-size: 22px;
        font-size: 1.571428571rem;
    }
    #respond form input[type="text"] {
        width: 46.333333333%;
    }
    #respond form textarea.blog-textarea {
        width: 79.666666667%;
    }
    .template-front-page .site-content,
    .template-front-page article {
        overflow: hidden;
    }
    .template-front-page.has-post-thumbnail article {
        float: left;
        width: 47.916666667%;
    }
    .entry-page-image {
        float: right;
        margin-bottom: 0;
        width: 47.916666667%;
    }
    .template-front-page .widget-area .widget,
    .template-front-page.two-sidebars .widget-area .front-widgets {
        float: left;
        width: 51.875%;
        margin-bottom: 24px;
        margin-bottom: 1.714285714rem;
    }
    .template-front-page .widget-area .widget:nth-child(odd) {
        clear: right;
    }
    .template-front-page .widget-area .widget:nth-child(even),
    .template-front-page.two-sidebars .widget-area .front-widgets + .front-widgets {
        float: right;
        width: 39.0625%;
        margin: 0 0 24px;
        margin: 0 0 1.714285714rem;
    }
    .template-front-page.two-sidebars .widget,
    .template-front-page.two-sidebars .widget:nth-child(even) {
        float: none;
        width: auto;
    }
    .commentlist .children {
        margin-left: 48px;
        margin-left: 3.428571429rem;
    }
}
@media screen and (min-width: 960px) {
    body {
        background-color: #e6e6e6;
    }
    body .site {
        padding: 0 40px;
        padding: 0 2.857142857rem;
        margin-top: 48px;
        margin-top: 3.428571429rem;
        margin-bottom: 48px;
        margin-bottom: 3.428571429rem;
        box-shadow: 0 2px 6px rgba(100, 100, 100, 0.3);
    }
    body.custom-background-empty {
        background-color: #fff;
    }
    body.custom-background-empty .site,
    body.custom-background-white .site {
        padding: 0;
        margin-top: 0;
        margin-bottom: 0;
        box-shadow: none;
    }
}


    /* =Print
   ----------------------------------------------- */

@media print {
    body {
        background: none !important;
        color: #000;
        font-size: 10pt;
    }
    footer a[rel=bookmark]:link:after,
    footer a[rel=bookmark]:visited:after {
        content: " [" attr(href) "] "; /* Show URLs */
    }
    a {
        text-decoration: none;
    }
    .entry-content img,
    .comment-content img,
    .author-avatar img,
    img.wp-post-image {
        border-radius: 0;
        box-shadow: none;
    }
    .site {
        clear: both !important;
        display: block !important;
        float: none !important;
        max-width: 100%;
        position: relative !important;
    }
    .site-header {
        margin-bottom: 72px;
        margin-bottom: 5.142857143rem;
        text-align: left;
    }
    .site-header h1 {
        font-size: 21pt;
        line-height: 1;
        text-align: left;
    }
    .site-header h2 {
        color: #000;
        font-size: 10pt;
        text-align: left;
    }
    .site-header h1 a,
    .site-header h2 a {
        color: #000;
    }
    .author-avatar,
    #colophon,
    #respond,
    .commentlist .comment-edit-link,
    .commentlist .reply,
    .entry-header .comments-link,
    .entry-meta .edit-link a,
    .page-link,
    .site-content nav,
    .widget-area,
    img.header-image,
    .main-navigation {
        display: none;
    }
    .wrapper {
        border-top: none;
        box-shadow: none;
    }
    .site-content {
        margin: 0;
        width: auto;
    }
    .singular .entry-header .entry-meta {
        position: static;
    }
    .singular .site-content,
    .singular .entry-header,
    .singular .entry-content,
    .singular footer.entry-meta,
    .singular .comments-title {
        margin: 0;
        width: 100%;
    }
    .entry-header .entry-title,
    .entry-title,
    .singular .entry-title {
        font-size: 21pt;
    }
    footer.entry-meta,
    footer.entry-meta a {
        color: #444;
        font-size: 10pt;
    }
    .author-description {
        float: none;
        width: auto;
    }

    /* Comments */
    .commentlist > li.comment {
        background: none;
        position: relative;
        width: auto;
    }
    .commentlist .avatar {
        height: 39px;
        left: 2.2em;
        top: 2.2em;
        width: 39px;
    }
    .comments-area article header cite,
    .comments-area article header time {
        margin-left: 50px;
        margin-left: 3.57142857rem;
    }
}
</style>