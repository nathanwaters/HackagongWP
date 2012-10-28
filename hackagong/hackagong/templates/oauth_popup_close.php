<html>
<head>
<script>
function init() {
  try{
      window.opener.hg_auth(<?php echo $json_user_info?>);
  }catch(err){}
  window.close();
}
</script>
</head>
<body onload="init();">
</body>
</html>