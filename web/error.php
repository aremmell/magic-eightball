<?php

?>

<style>
    .error-text {
        color: #b30013;
        font-style: normal;
        text-align: center;
    }

    .error-intro {
        font-weight: bold;
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    .error-explanation {
        font-weight: normal;
        font-size: 1.5rem;
    }

    .post-error-divider {
        color: #ccc;
        width: 80%;
        margin-top: 3.3rem;
    }

    .post-error-message {
        margin-top: 0;
    }

    .big-down-arrow {
        font-size: 8rem;
        margin-top: 0;
        margin-bottom: 0;
    }

    .error-title-container {
        text-align: center;
    }
</style>

<!-- <body> -->

<div class="eb-header">
    <div class="error-title-container">
        <p class="error-text error-intro">
            <?php echo htmlentities(LOC_ERROR_INTRO); ?>
        </p>
        <p class="error-text error-explanation">
            <?php echo $error_msg; ?>
        </p>
    </div>
    <!-- <div id="new-answer-container">
        <? php/*
        if ($error_state === false) {
            if (!empty($answer)) {
                echo sprintf("<span class=\"answer-intro\">%s<span class=\"answer-text\">%s</span>", LOC_ANSWER_PREFIX, $answer);
            } else {
            }
        } else {
            echo "<hr class=\"post-error-divider\"/>" .
                "<div class=\"post-error-message\"><p class=\"big-down-arrow\">&#x261F;</p><p></p></div>";
        }
        */ ?>
    </div> -->
    <hr class="post-error_divider" />
    <div class="post-error-message">
        <p class="big-down-arrow">
            &#x261F;
        </p>
    </div>
</div>
