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

 // Key-value pairs for the app state map (array). Ensure that if you add any new entries,
 // they are also initialized in the $appState array with a default (invalid) value.
const ME_Q_IN_PARAMS_AND_VALID = 1; // boolean: present and decoded from query params.
const ME_A_IN_PARAMS_AND_VALID = 2; // boolean: present and decoded from query params.
const ME_IN_ERROR_STATE        = 3; // boolean: render an error page.
const ME_ERROR_STATE_MESSAGE   = 4; // string: an error description to display on the page.
const ME_Q_NORMAL_VALUE        = 5; // string: completely plaintext copy of question.
const ME_A_NORMAL_VALUE        = 6; // string: completely plaintext copy of answer.
const ME_Q_PERMALINK_VALUE     = 7; // string: fully encoded copy of question for permalink.    
const ME_A_PERMALINK_VALUE     = 8; // string: fully encoded copy of answer for permalink.

// Global app state.
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

// An enumeration (immutable list) of possible rendering modes.
// At the beginning of rendering HTML, we will select one of
// these based on the app state and react accordingly.
enum RenderMode: string
{
    case Error                 = "error";
    case PromptForQuestion     = "prompt-for-question";
    case DisplayAnswer         = "display-answer";
    case DisplayAnswerSansLink = "display-answer-sans-link";
}

// Step #1 in the rendering mode selection process. If an error has occurred,
// this is the only thing to render.
function app_render_error_page(string& $msg): bool
{
    $msg = "";
    global $appState;

    if ($appState[ME_IN_ERROR_STATE] === true) {
        $msg = htmlentities($appState[ME_ERROR_STATE_MESSAGE] ?? "");

        if (empty($msg))
            $msg = LOC_ERRMSG_UNKNOWN;

        return true;
    }

    return false;
}

// The most likely usage scenario: a visitor who's not
// using a permalink (or trying to exploit our site by counterfeiting one).
//
// Prerequisities for this rendering mode are:
//
//   1. app_render_error_page returns false.
//   2. Both ME_Q_IN_PARAMS_AND_VALID and ME_A_IN_PARAMS_AND_VALID
//      in the global app state are false.
//
// Clearly, we can't trust the caller to verify all of that, so we'll
// have to do it ourselves.
//
// Returns true if it's okay to use `RenderMode::PromptForQuestion`,
// or false if something else needs to be rendered.
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
//   1. Everything listed in app_render_usual_page;
//   2. Both ME_Q_PERMALINK_VALUE and ME_A_PERMALINK_VALUE in
//      the app config hold non-empty strings.
//   3. create_permalink_query_params must return a non-empty string
//       when passed these values.
//   4. Both ME_Q_NORMAL_VALUE and ME_A_NORMAL_VALUE must be present
//      and non-empty in the app config.
//
// #3 is sort of optional; it just means that the permalink cannot be
// generated in the footer of the page; everything else should work just fine.
//
// Returns true as long as #1 and #4 are true. It will be the responsibility
// of the caller to verify that $pl_query_params is non-empty, and render the
// correct HTML.
//
// If this function returns false, the only logical thing to do
// is set an 'unknown error' message and render the error page.
function app_render_display_answer_page(string& $question, string& $answer,
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

function determine_render_mode(): RenderMode
{
    global $appState;


}

?>
