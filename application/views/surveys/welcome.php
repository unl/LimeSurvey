<?php 
    App()->getClientScript()->registerCoreScript('jquery');
    App()->getClientScript()->registerScriptFile(App()->getBaseUrl(). 'scripts/navigator.js');
    Twig::activateTemplate($template);
    $twig = Twig::getTwigEnvironment();
    echo $twig->render('welcome.twig', $context);
?>
