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

    // Takes plaintext input and returns htmlentity-encoded,
    // then base64-encoded, then URL-encoded version of the input.
    //
    // Returns the encoded string, or an empty string if an error occurs.
    function encode_permalink_data(string $plain_text): string
    {
        if (empty($plain_text))
            return "";

        $plain_text = htmlentities($plain_text);
        $plain_text = base64_encode($plain_text);
        $plain_text = urlencode($plain_text);

        return $plain_text;
    }

    // htmlentity-decodes and then base64-decodes a value from the query parameters.
    // Will fail if any of these are true:
    //
    //  1. If the $params array is null;
    //  2. Doesn't contain $param_key;
    //  3. $param_key is empty;
    //  4. Or the value returned for $param_key fails to be decoded;
    //
    // Returns boolean succcess. Upon failure, sets $out to an empty string. Upon success,
    // places the decoded parameter in $out.
    function extract_params_from_query(array|null $params, string $param_key, string& $out): bool
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

        $param_value = urldecode($param_value);
        $param_value = base64_decode($param_value, true);
        $param_value = html_entity_decode($param_value);

        if ($param_value === false)
            return false;

        $out = $param_value;
        return true;
    }

    // HACKHACK:
    // Creates a string that contains the protocol, the host name, and the path
    // to the script that's running. This is intended to create links back to the root directory.
    //
    // Returns a URL to the root directory of the installation if successful,
    // or an empty string upon failure.
    function create_permalink_prefix(): string
    {
        // TODO: For now, use $_SERVER["HTTP_HOST"] to figure out where
        // we're running without hard-coding the domain in. This should be
        // taken care of in a more thorough manner later.
        $https = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on";
        $retval = "http" . ($https === true ? "s" : "") . "://";

        if (!isset($_SERVER["HTTP_HOST"]))
            return "";

        $retval .= $_SERVER["HTTP_HOST"];

        if (!isset($_SERVER["PHP_SELF"]))
            return "";

        // Next, we've got to parse the URI, just in case we decide to add
        // more folders/files later.
        $matches = array();
        if (preg_match_all('#(\/[\/\w._-]+\/?)#si', $_SERVER["PHP_SELF"], $matches) !== 1) {
            $retval .= "/";
        } else {
            $retval .= $matches[1][0];
        }

        return $retval;
    }

    // Takes as input the output of the `encode_permalink_data` function,
    // as two separate query parameter values to include in a URI that points
    // back at a particular question/answer pair.
    //
    // Returns the query paramaters ready to be tacked on to the end of the permalink if successful.
    // Upon failure, returns an empty string.
    function create_permalink_query_params(string $q_encoded, string $a_encoded): string
    {
        $retval = "";

        if (empty($q_encoded))
            return "";

        $retval .= "?q=" . $q_encoded;

        if (empty($a_encoded))
            return "";

        $retval .= "&a=" . $a_encoded;

        return $retval;
    }

    // Executes the magic-eightball CLI program, passing a question string to it
    // and capturing its stdout.
    //
    // Returns false if the shell code is other than zero, or if the stdout is
    // empty.
    //
    // If successful, places the array of lines from stdout in $stdout. Otherwise,
    // an empty array.
    function execute_magic_eightball_cli(string $question, array& $stdout): bool
    {
        $stdout = array();

        if (empty($question))
            return false;

        $shell_cmd    = sprintf("%s %s %s", ME_CLI_BINARY, ME_CLI_CMDLINE, escapeshellarg($question));
        $shell_output = array();
        $result_code  = 1;
        $exec_retval  = exec($shell_cmd, $shell_output, $result_code);

        if ($exec_retval != false || $result_code === 0 && count($shell_output) > 0) {
            $stdout = $shell_output;
            return true;
        }

        return false;
    }

    // Just pulls up the locale string and runs it through htmlentities,
    // since this is done all over the codebase.
    function get_html_ready_loc_string(string $loc_id): string
    {
        return htmlentities($loc_id);
    }

    // Another handy utility function to load a loc string and
    // echo it into the DOM.
    function echo_loc_string(string $loc_id): void
    {
        echo get_html_ready_loc_string($loc_id);
    }
?>
