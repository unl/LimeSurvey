<?php
header('Content-Type: text/javascript;charset=utf-8');

/*
 * This view file will wrap the passed data in a javascript object.
 */
$json = json_encode($data, JSON_FORCE_OBJECT);
echo "var LS = $json";

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