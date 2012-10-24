(function($) {
  var fb_login = null, 
      tw_login = null,
      gg_login = null;

  var init = function() {
    fb_login = $('#social_login_fb');
    tw_login = $('#social_login_tw');
    gg_login = $('#social_login_gg');
    
    fb_login.click(ev_fb_login);
    fb_login.click(ev_tw_login);
    fb_login.click(ev_gg_login);
  };

  var ev_tw_login = function(ev) {
    var redir = 'http://'+document.location.hostname+'/api?m=login_tw';
    pop(redir);
    return false;
  };

  var ev_gg_login = function(ev) {
    var redir = 'http://'+document.location.hostname+'/api?m=login_gg';
    pop(redir);
    return false;
  };
  
  var ev_fb_login = function(ev) {
    var fb_id = $("meta[name='fb-app']").attr('value');;
    var redir = 'http://'+document.location.hostname+'/api/fb';
    pop('https://graph.facebook.com/oauth/authorize?client_id=' + fb_id + '&redirect_uri=' + redir + '&scope=email');
    return false;
  };
  
  var pop = function(url) {
    return window.open(url,'','scrollbars=no,menubar=no,height=400,width=800,resizable=yes,toolbar=no,status=no');
  };

  var auth = window.hg_auth = function(inf) {
    // console.info('hg_auth');
    console.info(inf);
    window.location.reload(true);
  };
  
  $(document).ready(init);
})(jQuery.noConflict());