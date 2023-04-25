<?php

// Let's get some includes up in here.
require 'locale.php';
require 'util.php';

const github_url = "https://github.com/aremmell/magic-eightball";
const cli_path   = "magic-eightball";
const cli_args   = "--no-ascii -q ";

$entire_pl = "";
$question_pl = "";
$answer_pl = "";
$error_mode = false;
$question = isset($_GET["q"]) ? $_GET["q"] : "";
$answer   = isset($_GET["a"]) ? $_GET["a"] : "";


// Try to detect/decode question and answer from the URI.
$question = decode_if_present_in_params($question);
$answer = decode_if_present_in_params($answer);

// If the answer is already present, then someone used a
// permalink and there's nothing to do there.
//
// It is an error situation for an answer to be present
// without a question, so we'll have to figure that out.
if (!empty($answer) && empty($question)) {
    // We'll just display errors and ask them to try again.
    $error_mode = true;
    $question = LOC_ERRMSG_NO_QUESTION;
    $answer = "";
}

if ($question !== "") {
    // All right–we've got a question to send to the CLI tool.
    $_cmd = sprintf("%s %s %s", cli_path, cli_args, escapeshellarg($question));

    // Off we go...
    $_cli_output = array();
    $_result_code = 1;
    $_exec_result = exec($_cmd, $_cli_output, $_result_code);

    if ($_exec_result != false || $_result_code === 0 && count($_cli_output) > 0) {
        // We are good to store the answer, create a permalink, and
        // display the output to the user.
        // TODO: if question doesn't end in '?', we need to add it.

        $question = encode_io_variables($question, $question_pl);
        $answer = encode_io_variables($_cli_output[0], $answer_pl);
    } else {
        // Something has gone dreadfully wrong and we need to cause a lot of noise.
        printf("ERROR: Unable to run CLI program!");
    }
}

if (!empty($question_pl)) {
    $entire_pl = "?q=$question_pl";
}

if (!empty($answer_pl)) {
    $entire_pl .= "&a=$answer_pl";
}

?>

<!doctype html>
<html lang="en">

<head>
    <title><?php echo LOC_MAGIC_EIGHTBALL ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Ryan M. Lederman <lederman@gmail.com>">
    <link rel="icon" type="image/png" href="img/favicon.png">
    <script type="text/javascript" src="js/form.validation.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="css/eb.css">
</head>

<body>
    <div id="eb-header">
        <div id="eb-previous-question">
            <?php if ($error_mode !== true) {
                if (!empty($question)) {
                    $myvar = LOC_YOU_ASKED;
                    echo "<span class=\"prev-question-intro\">$myvar<span class=\"prev-question\">&#x2018;$question&#x2019;</span>";
                }
            } else {
                echo "<span class=\"error_mode\">$question</span>";
            } ?>
            <div id="eb-new-answer">
                <?php if ($error_mode !== true) {
                    if (!empty($answer)) {
                        $tmpvar = LOC_ANSWER_PREFIX;
                        echo "<span class=\"answer-intro\">$tmpvar<span class=\"answer-text\">$answer</span>";
                    }
                } else {
                    echo "<span class=\"error_mode\"></span>";
                } ?>
            </div>
        </div>

        <div id="eb-form-container" class="container">
            <div class="row justify-content-center">
                <div class="col col-sm2">
                    <form action="index.php" method="get" class="form-inline needs-validation" novalidate>
                        <div id="eb-question-group" class="input-group input-group-lg">
                            <input type="text" class="form-control xm_auto" id="eb-question-taker" name="q" placeholder="Type your question..." autocomplete="off" autofocus required>
                            <div class="input-group-append">
                                <button type="submit" id="eb-submit-button" class="btn btn-outline-primary">go</button>
                            </div>
                            <div class="valid-feedback">
                                <span>Lookin' good.</span>
                            </div>
                            <div class="invalid-feedback">
                                <span>Can't get an answer without a question...</span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div id="eb-footer">
                <span class="footer-entry">
                <?php $clean_url = urldecode(github_url); ?>
                <a href="<?php echo "$clean_url" ?>" target="_blank">
                    <img class="footer-entry" src="img/github-mark.png" alt="GitHub logo" width="24" height="24">GitHub
                </a>
                </span>
                <?php if (!$error_mode && !empty($entire_pl)) {
                    echo "<span class=\"footer-entry\">|</span><span><a href=\"/8b/$entire_pl\" target=\"_blank\">Permalink</a></span>";
                } ?>
        </div>
    </body>
</html>
