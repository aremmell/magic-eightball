<?php

?>

<style>
    .prev-question-container {}

    .prev-question-intro {
        font-style: normal;
        font-weight: bold;
        text-align: center;
    }

    .prev-question {
        font-style: italic;
        font-weight: normal;
        text-align: justify;
    }

    .new-answer-container {}

    .answser-intro {
        font-style: bold;
    }

    .answer-text {
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
    <div id="new-answer-container">
        <p class="answer-intro">
            <?php echo htmlentities(LOC_ANSWER_PREFIX); ?>
        </p>
        <p class="answer-text">
            <?php echo $answer; ?>
        </p>
    </div>
</div>
