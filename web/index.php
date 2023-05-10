<?php
   /*
    * index.php:
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

    require 'locale.php';
    require 'util.php';
    require 'app-state.php';

    const ME_GITHUB_REPO = "https://github.com/aremmell/magic-eightball";
    const ME_CLI_EXEC = "magic-eightball";
    const ME_CLI_ARGS = "--no-ascii -q ";
    
    $question = "";
    $answer   = "";
    $q_valid  = decode_param_if_present($_GET, "q", $question);
    $a_valid  = decode_param_if_present($_GET, "a", $answer);

    $appState->set_q_in_params_and_valid($q_valid);
    $appState->set_a_in_params_and_valid($a_valid);

    $appState->set_q_normal_value($question);
    $appState->set_a_normal_value($answer);

    unset($question);
    unset($answer);

    // If an answer is present and valid, but a question is not,
    // that is an error state from which we cannot recover. All
    // we can do is display a page that informs the user that
    // they used a bad [perma]link.
    if ($a_valid !== false && $q_valid === false) {
        $appState->set_in_error_state(true);
        $appState->set_error_state_message(LOC_ERRMSG_NO_QUESTION);
    } else if ($q_valid !== false) {
        // All right–we've got a question to send to the magic-eightball CLI tool.
        $question     = $appState->get_q_normal_value($qestion);
        $shell_cmd    = sprintf("%s %s %s", ME_CLI_EXEC, ME_CLI_ARGS, escapeshellarg($question));
        $shell_output = array();
        $result_code  = 1;
        $exec_retval  = exec($shell_cmd, $shell_output, $result_code);

        if ($exec_retval != false || $result_code === 0 && count($shell_output) > 0) {
            // The execution of the CLI tool was succcessful, and we have an
            // answer to provide to the user. At this time, we will prepare 
            // the data necessary to construct a permalink that represents
            // this pair of question and answser–forever, so that the user
            // may revisit it for a laugh or encouragement.
            // 
            // However, this form of the encoded data is kept separate for
            // display in the resulting web page we're about to render.
            //
            // The values for the permalink will be HTML-entity encoded,
            // then base64 encoded, then URL-encoded.
            //
            // The values we use for the remainder of the script are only
            // HTML-entity encoded.

            $appState->set_q_permalink_value(encode_permalink_data($question, $q_encoded));
            $appState->set_a_permalink_value(encode_permalink_data($shell_output[0], $a_encoded));

            // Now $question and $answer are just plaintext, and stored in their encoded forms in $appState.
            // Functions that return this type of data to the DOM-rendering code will add htmlenties.
        } else {
            // We've failed to succcessfully execute the magic-eightball binary.
            // Now we're in an error state.
            $appState->set_in_error_state(true);
            $appState->set_error_state_message(LOC_PROGRAM_FAILURE);
        }
    }

    // Let the app state object decide which page we render:
    $renderModeName = $appState->compute_render_mode_name();
    $module      = "";

    switch ($renderModeName)
    {
        case RenderModeName::PromptForQuestion:
            $directory = "prompt";
            break;
        case RenderModeName::DisplayAnswer:
            $directory = "display";
            break;
        case RenderModeName::DisplayAnswerSansLink:
            $directory = "display";
            break;
        case RenderModeName::Error:
            $directory = "error";
        default:
            break;
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <title>
            <?php echo htmlentities(LOC_MAGIC_EIGHTBALL); ?>
        </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Ryan M. Lederman <lederman@gmail.com>">
        <link rel="icon" type="image/png" href="img/favicon.png">
        <link rel="stylesheet" href="css/index.css">
        <script src="js/jquery-3.6.4.min.js"></script>
        <script src="js/magic-eightball.js"></script>
    </head>

    <body>
        <?php
            require($module . ".php");
            $permalink = $appState->get_permalink();
        ?>

        <div class="eb-footer">
            <div class="footer-entry github-logo">
                <a href="<?php echo ME_GITHUB_REPO; ?>" target="_blank">
                    <img class="footer-entry" src="img/github-mark.png" alt="Visit the GitHub repository." width="24" height="24">
                </a>
            </div>
            <?php if (RenderModeName::Error !== $renderModeName && !empty($permalink)) {
                echo "<span class=\"footer-entry\">|</span><span><a href=\"/8b/$permalink\" target=\"_blank\">Permalink</a></span>";
            } ?>
        </div>        
    </body>
</html>
