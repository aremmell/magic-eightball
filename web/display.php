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
</style>

<!-- <body> -->

<div class="eb-header">
    <div id="prev-question-container">
        <p class="prev-question-intro">
            <?php echo htmlentities(LOC_YOU_ASKED); ?>
            &nbsp;
        </p>
        <p class="prev-question">
            &#x2018;
            <?php echo $question; ?>
            &#x2019;
        </p>
    </div>
    <hr class="question-answer-divider"/>
    <div class="answer-container">
        <p class="answer-intro">
            <?php echo htmlentities(LOC_ANSWER_PREFIX); ?>
        </p>
        <p class="answer-text">
            <?php echo $answer; ?>
        </p>
    </div>
</div>
