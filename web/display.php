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
    if (!str_ends_with($question, "?"))
        $question .= "?";    
?>

<script>
    $(function() {
        let copyButton = $("#permalink");
        copyButton.click(() => {
            navigator.clipboard.writeText("<?php echo $appState->get_permalink(); ?>");
        });

        $("#home").click(() => {
            window.location.href = "<?php echo create_permalink_prefix(); ?>";
        });
    });
</script>

<div class="eb-header">
    <div id="prev-question-container">
        <p class="prev-question-intro">
            <span><?php echo_loc_string(LOC_YOU_ASKED); ?>&nbsp;</span>
        </p>
        <p class="prev-question">
            <span>&#x2018;</span><?php echo $question; ?><span>&#x2019;</span>
        </p>
    </div>
    <hr class="question-answer-divider" />
    <div class="answer-container">
        <p class="answer-intro">
            <?php echo_loc_string(LOC_ANSWER_PREFIX); ?>
        </p>
        <p class="answer-text">
            <?php echo $answer; ?>
        </p>
    </div>
</div>

<div class="answer-followup">
    <p>
        <button id="permalink" class="btn btn-lg btn-eb-primary"><?php echo_loc_string(LOC_COPY_PERMALINK); ?></button>
    </p>
    <p>    
        <button id="home" class="btn btn-lg btn-eb-primary" role="button"><?php echo_loc_string(LOC_HOME); ?></button>
    </p>
</div>
