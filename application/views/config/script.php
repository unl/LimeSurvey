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

LS.createUrl = function (route, params)
{
    if (typeof params === 'undefined') {
        params = {};
    }
    var result = LS.baseUrl;
    
    if (LS.showScriptName)
    {
        result = result + '/index.php';
    }
    
    
    if (LS.urlFormat == 'get')
    {
        // Configure route.
        result += '?r=' + route;
         
        // Configure params.
        for (var key in params)
        {
            result = result + '&' + key+ '=' + params[key];
        }
    }
    else
    {
        // Configure route.
        result += route;
        
        // Configure params.
        for (var key in params)
        {
            result = result + '/' + key + '/' + params[key];
        }
    }
    
    return result;
}