<?php

?>

<script>
    $(function() {
        $("#try-again").click(function() {
            window.location.href = "/";
        });
    });
</script>

<style>
    .error-text {
        color: #b30013;
        font-style: normal;
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
        width: 100%;
        margin-top: 3rem;
    }

    .post-error-message {
        margin-top: 3rem;
        text-align: center;
    }

    .error-title-container {
        text-align: center;
    }

    .try-again {
        font-size: 1.5 em;
        font-weight: bold;
    }
</style>

<!-- <body> -->

<div class="eb-header">
    <div class="error-title-container">
        <p class="error-text error-intro">
            <?php echo htmlentities(LOC_ERROR_INTRO); ?>
        </p>
        <p class="error-text error-explanation">
            <?php echo $appState->get_error_state_message(); ?>
        </p>
    </div>
    <hr class="post-error-divider" />
    <div class="post-error-message">
        <a class="try-again" href="/" role="button"> <?php echo htmlentities(LOC_TRY_AGAIN); ?> </a>
    </div>
</div>
