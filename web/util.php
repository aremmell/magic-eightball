<?php

function encode_io_variables(string $new_value, string &$pl_out)
{
    $value_out = htmlentities($new_value);
    $pl_out = base64_encode($new_value);
    $pl_out = urlencode($pl_out);
    return $value_out;
}

function decode_if_present_in_params(string $param_value)
{
    if (empty($param_value))
        return "";

    $decoded = htmlspecialchars_decode($param_value);
    $decoded = base64_decode($param_value, true);

    if ($decoded === false)
        return "";

    return $decoded;
}

?>
