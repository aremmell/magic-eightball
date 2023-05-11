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

    $q_valid = extract_params_from_query($_GET, "q", $question);
    $a_valid = extract_params_from_query($_GET, "a", $answer);

    // If an answer is present and valid, but a question is not,
    // that is an error state from which we cannot recover. All
    // we can do is display a page that informs the user that
    // they used a bad [perma]link.
    if ($a_valid !== false && $q_valid === false) {
        $appState->set_in_error_state(true);
        $appState->set_error_state_message(LOC_ERRMSG_NO_QUESTION);
    }

    $appState->set_question_value($question);
    $appState->set_answer_value($answer);    

    // Let the app state object decide which page we render:
    $renderModeName = $appState->compute_render_mode_name();
    $module         = "";

    switch ($renderModeName)
    {
        case RenderModeName::PromptForQuestion:
            $module = "prompt";
            break;
        case RenderModeName::ComputeAnswer:
            $module = "display";
            break;
        case RenderModeName::DisplayAnswer:
            $module = "display";
            break;
        case RenderModeName::DisplayAnswerSansLink:
            // TODO: The rendering modules do not check for this
            // render mode before trying to display the permalink.
            // The render mode should be stored in $appState so that
            // it can determine whether or not to give the caller the
            // permalink if they ask for it.
            $module = "display";
            break;
        case RenderModeName::Error:
        default:
            $module = "error";
            break;
    }

    // All right–we've got a question to send to the magic-eightball CLI tool.
    if ($renderModeName == RenderModeName::ComputeAnswer) {

        $shell_output = array();

        if (execute_magic_eightball_cli($question, $shell_output)) {
            $answer = $shell_output[0];
            $appState->set_answer_value($answer);
        } else {
            // We've failed to succcessfully execute the magic-eightball binary.
            // Now we're in an error state.
            $appState->set_in_error_state(true);
            $appState->set_error_state_message(LOC_PROGRAM_FAILURE);
        }
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
        <link rel="icon" href="favicon.png">
        <link rel="icon" href="favicon.ico" sizes="any">
        <link rel="icon" href="icon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="manifest" href="icon.webmanifest">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="css/index.css">        
        <script src="js/jquery-3.6.4.min.js"></script>
        <script src="js/magic-eightball.js"></script>
    </head>

    <body>
        <div class="container-fluid">

            <?php
                require($module . ".php");
            ?>

            <div class="eb-footer">
                <div class="footer-entry github-logo">
                    <a href="<?php echo ME_GITHUB_REPO; ?>" target="_blank">
                        <img class="footer-entry" src="img/github-mark-white.png" alt="Visit the GitHub repository." width="24" height="24">
                    </a>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    </body>
</html>
