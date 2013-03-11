<!doctype html>
<html>
    <head>
<?php 
$path = getTemplatePath($template);
echo templatereplace(file_get_contents($path . '/startpage.pstpl'));
echo file_get_contents($path . '/welcome.pstpl');
echo templatereplace(file_get_contents($path . '/endpage.pstpl'));
?>
</html>