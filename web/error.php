<?php

?>

<script>
    $(function() {
        $("button.btn-eb-primary").click(function() {
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
        <button class="btn btn-lg btn-eb-primary" href="/" type="button"> <?php echo htmlentities(LOC_TRY_AGAIN); ?> </button>
    </div>
</div>
