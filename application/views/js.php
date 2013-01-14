<?php

/*
 * This view file will wrap the passed data in a javascript object.
 */
$json = json_encode($data, JSON_PRETTY_PRINT +  JSON_FORCE_OBJECT);
echo "var LS = $json";

// Disable logging.
foreach (App()->log->routes as $route)
{
    if ($route instanceof CWebLogRoute)
    {
        $route->enabled = false;
    }
}
?>
