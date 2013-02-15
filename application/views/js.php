<?php
header('Content-Type: text/javascript;charset=utf-8');
$options = 0;
if (defined('JSON_PRETTY_PRINT'))
{
    $options += JSON_PRETTY_PRINT;
}

$json = json_encode($data, $options);
echo $json;

// Disable logging.
if (isset(App()->log))
{
    foreach (App()->log->routes as $route)
    {
        if ($route instanceof CWebLogRoute)
        {
            $route->enabled = false;
        }
    }
}
?>