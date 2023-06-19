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
    
    $question = "";
    $answer   = "";

    $q_valid = extract_params_from_query($_GET, "q", $question);
    $a_valid = extract_params_from_query($_GET, "a", $answer);

    // If an answer is present and valid, but a question is not,
    // that is an error state from which we cannot recover. All
    // we can do is display a page that informs the user that
    // they used a bad [perma]link.
    if ($a_valid !== false && $q_valid === false)
        $appState->set_in_error_state(true, LOC_ERRMSG_NO_QUESTION);

    $appState->set_question_value($question);
    $appState->set_answer_value($answer);    

    // All right–we've got a question to send to the magic-eightball CLI tool.
    if ($appState->compute_render_mode() == RenderMode::ComputeAnswer) {
        $shell_output = array();
        if (execute_magic_eightball_cli($question, $shell_output)) {
            $answer = $shell_output[0];
            $appState->set_answer_value($answer);
        } else {
            // We've failed to succcessfully execute the magic-eightball binary.
            $appState->set_in_error_state(true, LOC_PROGRAM_FAILURE);
        }
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <title><?php echo_loc_string(LOC_MAGIC_EIGHTBALL); ?>
    </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Ryan M. Lederman <lederman@gmail.com>">

        <meta property="og:title" content="<?php echo_loc_string(LOC_MAGIC_EIGHTBALL); ?>">

        <?php $show_question = $appState->compute_render_mode() == RenderMode::DisplayAnswer; ?>
        <meta property="og:description" content="<?php echo($show_question ? ($appState->get_question_value() . "&nbsp;" . LOC_OG_DESC_PERMALINK) : LOC_MAGIC_EIGHTBALL_DESC); ?>">
        <meta property="og:url" content="<?php echo($show_question ? $appState->get_permalink() : "https://rml.dev/magic-eightball") ?>">
        <meta property="og:type" content="website">
        <meta property="og:image" content="https://rml.dev/magic-eightball/img/og-image.png">

        <link rel="icon" href="favicon.png">
        <link rel="icon" href="favicon.ico" sizes="any">
        <link rel="icon" href="icon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="manifest" href="icons.webmanifest">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="css/index.css">    
            
        <script src="js/jquery-3.6.4.min.js"></script>
        <script src="js/magic-eightball.js"></script>
    </head>

    <body>
        <div class="container-fluid">

            <?php
                require($appState->compute_render_mode()->value . ".php");
                // TODO: The rendering modules do not check for this
                // render mode before trying to display the permalink.
                // The render mode should be stored in $appState so that
                // it can determine whether or not to give the caller the
                // permalink if they ask for it.
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
