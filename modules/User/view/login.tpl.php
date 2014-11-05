<div class="container">

  <form class="form-signin" role="form" method="post" action="connexion.php?action=in">
    <h2 class="form-signin-heading">Please sign in</h2>
    <input type="hidden" name="form_sent" value="1" />
    <input type="hidden" name="redirect_url" value="<?php echo WWW_ROOT; ?>" />
    <input type="text" class="form-control" placeholder="Email address" name="req_username" required autofocus>
    <input type="password" class="form-control" placeholder="Password" name="req_password" required>
    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
  </form>

</div> <!-- /container -->