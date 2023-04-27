<?php
   /*
    * util.php:
    *
    * This file is part of the Magic Eightball project.
    *
    * Author: Ryan M. Lederman <lederman@gmail.com>
    * GitHub: https://github.com/aremmell/magic-eightball/
    * Play online: https://rml.dev/magic-eightball/
    *
    * Please read the LICENSE file if you intend to modify
    * or redistribute the source code contained herein.
    */
    
    // takes plaintext input and returns htmlentity-encoded,
    // then base64-encoded, then URL-encoded version of the input.
    //    
    // Returns the encoded string, or an empty string if an error occurs.
    function encode_permalink_data(string $plain_text): string
    {
        if (empty($plain_text))
            return "";
            
        $value_out = htmlentities($plain_text);
        $pl_out = base64_encode($plain_text);
        $pl_out = urlencode($pl_out);
        return $pl_out;
    }

    // htmlentity-decodes and then base64-decodes a value from the query parameters.
    // Will fail if any of these are true:
    //
    //  1. If the $params array is null;
    //  2. Doesn't contain $param_key;
    //  3. $param_key is empty;
    //  4. Or the value returned for $param_key fails to be decoded;
    //
    // Returns boolean succcess. When false, sets $out to an empty string. When
    // true, sets $out to the the decoded value.
    function decode_param_if_present(array|null $params, string $param_key, string& $out): bool
    {
        $out = "";

        if (!$params || empty($param_key) || !array_key_exists($param_key, $params))
            return false;

        $param_value = $params[$param_key];

        // TODO: Some day, someone should check these
        // values and see if they even look like URL-encoded
        // and base64 encoded strings.
        if (!$param_value || empty($param_value))
            return false;

        $decoded = html_entity_decode($param_value);
        $decoded = base64_decode($decoded, true);

        if ($decoded === false)
            return false;

        $out = $decoded;
        return true;
    }
    
    // Takes as input the output of the `encode_permalink_data` function,
    // as two separate query parameter values to include in a URI that points
    // back at this installation.
    //
    // Returns the permalink URI if both inputs are !empty(). They will be
    // evaluated left-to-right, and the first letter of their name will be
    // the query parameter's key (.e.g, $q_encoded becomes ?q=). If either
    // of them are empty, or in some later iteration of this code determined
    // to be invalid, an empty string will be returned.
    //
    // NOTE: This function does *not* return the protocol, hostname, or port
    // parts; it only creates the query parameters to append to whichever
    // installation this is–right now we don't have a good way to determine
    // that, so it's safer to leave that code in index.php.
    function create_permalink_query_params(string $q_encoded, string $a_encoded): string
    {
        $retval = "";

        if (!empty($q_encoded)) {
            $retval .= "?a=" . $q_encoded;
        }

        if (!empty($a_encoded)) {
            $retval .= "&a=" . $a_encoded;
        }

        return $retval;
    }

?>
