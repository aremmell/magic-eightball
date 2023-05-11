<?php

?>

<style>
    .prev-question-container {
        text-align: center;
    }

    .prev-question-intro {
        font-size: 2.5em;
        font-style: normal;
        font-weight: bold;
    }

    .prev-question {
        color: #888;
        font-size: 1.5em;
        font-style: italic;
        font-weight: normal;
    }

    .question-answer-divider {
        color: #ccc;
        width: 100%;
    }

    .answer-container {
        margin-top: 1rem;
    }

    .answer-intro {
        font-size: 2.5em;
        font-weight: bold;
    }

    .answer-text {
        color: #888;
        font-size: 1.5em;
        font-weight: normal;
        font-style: italic;
    }

    .answer-followup {
        margin-top: 3em;
        text-align: center;
    }
</style>

<!-- <body> -->

<?php
    $questionIntro = htmlentities(LOC_YOU_ASKED);
    $answerPrefix  = htmlentities(LOC_ANSWER_PREFIX);
    $copyPermalink = htmlentities(LOC_COPY_PERMALINK);
    $home          = htmlentities(LOC_HOME);
    $permalink     = $appState->get_permalink();

    // It only seems appropriate that if no question mark was entered
    // at the time of the actual question, we should add one here.
    if (!str_ends_with($question, "?"))
        $question .= "?";
?>

<script>
    $(function() {
        let copyButton = $("#permalink");
        copyButton.click(() => {
            navigator.clipboard.writeText("<?php echo $permalink; ?>");
        });

        $("#home").click(() => {
            window.location.href = "/";
        });
    });
</script>

<div class="eb-header">
    <div id="prev-question-container">
        <p class="prev-question-intro">
            <?php echo $questionIntro; ?>
            &nbsp;
        </p>
        <p class="prev-question">
            &#x2018;
            <?php echo $question; ?>
            &#x2019;
        </p>
    </div>
    <hr class="question-answer-divider" />
    <div class="answer-container">
        <p class="answer-intro">
            <?php echo $answerPrefix; ?>
        </p>
        <p class="answer-text">
            <?php echo $answer; ?>
        </p>
    </div>
</div>

<div class="answer-followup">
    <p>
        <button id="permalink" class="btn btn-lg btn-eb-primary"><?php echo $copyPermalink; ?></button>
    </p>
    <p>    
        <button id="home" class="btn btn-lg btn-eb-primary" href="/" role="button"><?php echo $home; ?></button>
    </p>
</div>
