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

    $appState[ME_Q_IN_PARAMS_AND_VALID] = decode_param_if_present($_GET, "q", $question);
    $appState[ME_A_IN_PARAMS_AND_VALID] = decode_param_if_present($_GET, "a", $answer);
    $appState[ME_Q_NORMAL_VALUE] = $question;
    $appState[ME_A_NORMAL_VALUE] = $answer;

    unset($question);
    unset($answer);

    // If an answer is present and valid, but a question is not,
    // that is an error state from which we cannot recover. All
    // we can do is display a page that informs the user that
    // they used a bad [perma]link.
    $a_valid = $appState[ME_A_IN_PARAMS_AND_VALID];
    $q_valid = $appState[ME_Q_IN_PARAMS_AND_VALID];

    if ($a_valid !== false && $q_valid === false) {
        $appState[ME_IN_ERROR_STATE] = true;
        $appState[ME_ERROR_STATE_MESSAGE] = LOC_ERRMSG_NO_QUESTION;
    } else if ($q_valid !== false) {
        // All right–we've got a question to send to the magic-eightball CLI tool.
        $question     = $appState[ME_Q_NORMAL_VALUE];
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

            $appState[ME_Q_PERMALINK_VALUE] = encode_permalink_data($question, $q_encoded);
            $appState[ME_A_PERMALINK_VALUE] = encode_permalink_data($shell_output[0], $a_encoded);

            // Now $question and $answer are just plaintext, and stored in
            // $appState[ME_Q_PERMALINK_VALUE], and $apState[ME_A_PERMALINK_VALUE], respectively.
            // Functions that return this type of data to the DOM-rendering code will add htmlenties.
            // The temporary values stored in $appState are ready for creating a permalink back to this state.
        } else {
            // We've failed to succcessfully execute the magic-eightball binary.
            // Now we're in an error state.
            $appState[ME_IN_ERROR_STATE]       = true;
            $appState[ME_ERROR_STATE_MESSAGE]  = LOC_PROGRAM_FAILURE;
        }
    }

    // The rest of the HTML-rendering part of the script can use the following to determine
    // what the scenario is:
    //
    //   1. Some myriad possible known or unknown error(s).
    //   2. Usual visit; no question/answer pre-recorded;
    //   3. Question/answer pre-recorded, valid.
    //   4. Question/answer pre-recorded, but invalid;
    //  
    // The way to select which mode to use is by invoking these functions in
    // this exact order, and following the rules established by their behavior. However, it is very important
    // that the following rules are followed carefully: none of these functions may be called until we reach
    // this point in the script, and perhaps call a function called select_page_to_render() which wraps this
    // behavior up for us. and secondly, they must always be called in this order with no exception.
    //
    //    1. function app_render_error_page(string& $msg): bool
    //        a. Determines if we already have an error, and if so, provides
    //           the associated message. We must display the error page. STOP.
    //    2. function app_render_usual_page(): bool
    //        a. Determines that function #1 has returned false.
    //        b. Determines that no pre-recorded question or answer are present.
    //    3. function app_render_success_page(string& $question, string& $answer, string& $pl_query_params): bool
    //        a. Determines that functions #1 and #2 have both returned false.
    //        b. Ensures that both `ME_Q_PERMALINK_VALUE` and `ME_A_PERMALINK_VALUE` in the app config are non-empty strings.
    //        c. Ensures that `create_permalink_query_params` returns a non-empty string value with the aforementioend as input varibles.
    //        d. Ensures that `ME_Q_NORMAL_VALUE` and `ME_A_NORMAL_VALUE` in the app config are non-empty strings. These
    //           are the strings directly rendered in the page as the question and answer.
    //        e. CAVEAT: Rendering this could lead us back to an error page; just because we had no errors
    //           when we started running this checklist doesn't mean we won't find one now:
    //             i. non-empty string does not guarantee corruption or incorrect format, encoding, etc.
    //             ii. Technically, we can move forward if (b) and (c) fail; we just omit the permalink element.
    //             iii. Thus, there is a pathway to a successful, normal(-ish) rendering if just (a) and (d) succeed.
    //
    // I believe the above is the answer to logically selecting behavior and content in a rational and pragmatic way.
    // There's one way to find out...


    //
    // TODO:
    // write a process deducton function that determines which page to render and which
    // variables from the app state will be required.
    //
    // break this HTML up into sections, and reuse them for different page renderings.
    //
    // Finito.

    $error_msg   = "";
    $error_state = app_render_error_page($error_msg);
    
    if (!$error_state) {
        // We can move on to checking the next possible rendering mode!
    }
?>

<!doctype html>
<html lang="en">
    <head>
        <title>
            <?php echo LOC_MAGIC_EIGHTBALL ?>
        </title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Ryan M. Lederman <lederman@gmail.com>">
        <link rel="icon" type="image/png" href="img/favicon.png">
        <link rel="stylesheet" href="css/eb.css">
        <script src="js/jquery-3.6.4.min.js"></script>
        <script src="js/magic-eightball.js"></script>
    </head>

    <body>
        <div id="eb-header">
            <div id="eb-previous-question">
                <?php
                    if ($error_state === true) {
                        echo sprintf("<p class=\"error_text error_intro\">%s</p>", LOC_ERROR_INTRO);
                        echo "<p class=\"error_text error_explanation\">$error_msg</p>";
                    } else {
                        echo sprintf("<p class=\"prev-question-intro\">%s&nbsp;<p class=\"prev-question\">&#x2018;%s&#x2019;</p>",
                            LOC_YOU_ASKED, $question);
                    }
                ?>
            <div>
            <div id="eb-new-answer">
                <?php
                    if ($error_state === false) {
                        echo sprintf("<span class=\"answer-intro\">%s<span class=\"answer-text\">%s</span>", LOC_ANSWER_PREFIX, $answer);
                    } else {
                        echo "<hr class=\"post-error-divider\"/>" . 
                             "<div class=\"post-error-message\"><p class=\"big-down-arrow\">&#x261F;</p><p></p></div>";
                    }
                ?>
            </div>
        </div>

        <div class="eb-form-container" style="<?php echo ($error_state === true ? "display:none;" : "display:block;") ?>">
            <form class="needs-validation" action="index.php" method="get">
                <input type="text" class="form-control xm_auto" id="eb-question-edit" name="q"
                    placeholder="<?php echo LOC_INPUT_PLACEHOLDER ?>" autocomplete="off" autofocus required>
                    <button type="submit" id="eb-submit-button" class=""><?php echo LOC_SUBMIT_BUTTON ?></button>
                </div>
            </form>
        </div>

        <div id="eb-footer">
            <span class="footer-entry">
                <?php $clean_url = urldecode(ME_GITHUB_REPO); ?>
                <a href="<?php echo "$clean_url" ?>" target="_blank">
                    <img class="footer-entry" src="img/github-mark.png" alt="Visit the GitHub repository." width="24" height="24">
                </a>
            </span>
            <?php if ($error_state !== true && !empty($entire_pl)) {
                echo "<span class=\"footer-entry\">|</span><span><a href=\"/8b/$entire_pl\" target=\"_blank\">Permalink</a></span>";
            } ?>
        </div>
    </body>
</html>
