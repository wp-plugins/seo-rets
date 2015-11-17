<script>
    window.fbAsyncInit = function () {
        FB.init({
            appId: '500293866814018',
            cookie: true,  // enable cookies to allow the server to access
                           // the session
            xfbml: true,  // parse social plugins on this page
            version: 'v2.2' // use version 2.2
        });

    };
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

</script>
<div class="sr-content">
    <div class="row sr-list-menu">
        <div class="col-md-12">
            <a href="/sr-user">Personal Cabinet</a>
            <a href="/sr-favorites">Favorites</a>
            <a href="/sr-search-fav">Saved Search</a>
            <a onclick="FB.logout()" href="<?php echo get_bloginfo('url')?>/sr-logout">Logout</a>
        </div>
    </div>
    <div style="clear: both"></div>
    <form action="" method="get">
        <div class="sr_options margin-top-20">
            <div class="row">
                <div class="col-md-6 col-sm-6">
                    <label for="">Username</label>
                    <input type="text" name="u_name" value="<?= $users[$index]['name'] ?>" class="form-control">
                </div>
                <div class="col-md-6 col-sm-6">
                    <label for="">Full name</label>
                    <input type="text" name="full_name" value="<?= $users[$index]['full_name'] ?>" class="form-control">
                </div>
            </div>
            <div class="row margin-top-10">
                <div class="col-md-6 col-sm-6">
                    <label for="">Your Phone:</label>
                    <input type="text" name="mobile" value="<?= $users[$index]['u_mobile'] ?>" class="form-control">
                </div>
                <div class="col-md-6 col-sm-6">
                    <label for="">Secondary Phone:</label>
                    <input type="text" name="phone" value="<?= $users[$index]['u_phone'] ?>" class="form-control">
                </div>
            </div>

            <div class="row margin-top-15">
                <div class="col-md-8 col-sm-8">
                </div>
                <div class="col-md-4 col-sm-4">
                    <label for="">&nbsp;</label>
                    <input type="submit" name="user-save"
                           id="user-save"
                           class="button-primary form-control" value="Save"/>
                </div>
            </div>
        </div>
    </form>
</div>