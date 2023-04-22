<?php function get($body, $key, $required = false)
{
    if (isset($body[$key])) {
        return $body[$key];
    } else {
        if ($required) {
            throw new Exception("error." . $key . ".required", 400);
        }
    }
    return null;
}