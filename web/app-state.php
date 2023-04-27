<?php
/*
 * app-state.php:
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

 // Key values for the app state key/value map (arrray). Make sure if you add any,
 // they go in the initialization below.
const ME_Q_IN_PARAMS_AND_VALID = 1; // boolean: present and decoded from query params.
const ME_A_IN_PARAMS_AND_VALID = 2; // boolean: present and decoded from query params.
const ME_IN_ERROR_STATE        = 3; // boolean: render an error page.
const ME_ERROR_STATE_MESSAGE   = 4; // string: an error description to display on the page.
const ME_Q_NORMAL_VALUE        = 5; // string: completely plaintext copy of question.
const ME_A_NORMAL_VALUE        = 6; // string: completely plaintext copy of answer.
const ME_Q_PERMALINK_VALUE     = 7; // string: fully encoded copy of question for permalink.    
const ME_A_PERMALINK_VALUE     = 8; // string: fully encoded copy of answer for permalink.

// Said global app state.
$appState = array
(
    ME_Q_IN_PARAMS_AND_VALID => false,
    ME_A_IN_PARAMS_AND_VALID => false,
    ME_IN_ERROR_STATE => false,
    ME_ERROR_STATE_MESSAGE => "",
    ME_Q_NORMAL_VALUE => "",
    ME_A_NORMAL_VALUE => "",
    ME_Q_PERMALINK_VALUE => "",
    ME_A_PERMALINK_VALUE => ""
);

// An enumeration (immutable list) of possible rendering modes
// of our little application. At the beginning of rendering HTML,
// we will select one of these and react accordingly.
//
// I am thinking it would be easiest and least error prone to name
// external PHP files after these and inject them in the DOM, rather
// than having 50 if-else statements in the HTML.
//
// The selection of one of these is simple if we follow the
// logic I detailed in index.php starting at line 83.
//
enum RenderMode: string {
    case Error         = "error-need-to-restart";
    case Usual         = "prepared-for-query";
    case Success       = "query-success";
    case SuccessNoLink = "query-success-no-link";
}

// Takes the highest prioity of rendering modes.
// If anyone has set the error state, the error page
// will render (with an optional message).
// TODO: How to handle when there isn't a message?
function app_render_error_page(string& $msg): bool
{
    $msg = "";
    global $appState;

    if ($appState[ME_IN_ERROR_STATE] === true) {
        $msg = htmlentities($appState[ME_ERROR_STATE_MESSAGE] ?? "");
        return true;
    }

    return false;
}

// The most likely rendering path–someone visiting without
// using a permalink (or trying to attack the site by faking one).
//
// Prerequisities for this rendering mode are:
//   1. `app_render_error_page` returns false.
//   2. Both `ME_Q_IN_PARAMS_AND_VALID` and `ME_A_IN_PARAMS_AND_VALID`
//      in the global app state are false.
//
// Clearly, we can't trust the caller to verify all of that, so we'll
// have to do it ourselves.
//
// Returns true if it's okay to render the default welcome page, or false
// if something else needs to be rendered.
function app_render_usual_page(): bool
{
    global $appState;

    $error_msg = "";
    if (app_render_error_page($error_msg)) {
         return false;
    }

    if ($appState[ME_Q_IN_PARAMS_AND_VALID] !== false ||
        $appState[ME_A_IN_PARAMS_AND_VALID] !== false) {
        return false;
    }

    return true;
}

// Prerequisites for this rendering mode are:
//
//   1. Everything listed in `app_render_usual_page`;
//   2. Both `ME_Q_PERMALINK_VALUE` and `ME_A_PERMALINK_VALUE` in
//      the app config hold non-empty strings.
//   3. `create_permalink_query_params` must return a non-empty string
//       when passed these values.
//   4. Both `ME_Q_NORMAL_VALUE` and `ME_A_NORMAL_VALUE` must be present
//      and non-empty in the app config.
//
// Three is sort of optional; it just means that the permalink cannot be
// generated in the footer of the page; everything else should work just fine.
//
// I will return true from this function as long as 1 and 4 are true.
function app_render_success_page(string& $question, string& $answer,
    string& $pl_query_params): bool
{
    global $appState;
    
    $question        = "";
    $answer          = "";
    $pl_query_params = "";

    if (!app_render_usual_page())
        return false;

    // Check for plaintext question and answer, and prepare them for rendering
    // in an HTML document.
    $question = htmlentities($appState[ME_Q_NORMAL_VALUE] ?? "");
    $answer   = htmlentities($appState[ME_A_NORMAL_VALUE] ?? "");
    
    if (empty($question) || empty($answer))
        return false;

    $q_encoded = $appState[ME_Q_PERMALINK_VALUE];
    $a_encoded = $appState[ME_A_PERMALINK_VALUE];

    // Generate the permalink query parameters and we're ready to render.
    $pl_query_params = create_permalink_query_params($q_encoded, $a_encoded);

    return true;
}

?>
