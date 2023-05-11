<?php

?>

<style>
    .header-text {
        font-size: 3rem;
        margin-bottom: 3rem;
    }

    .eb-ball-image {
        margin-top: 1rem;
        margin-bottom: 1rem;
        margin-left: auto;
        margin-right: auto;
    }

    .eb-form-container {
        max-width: 66%;
        margin-left: auto;
        margin-right: auto;
    }

    .eb-question-edit {
        flex-grow: 1;
        border-left-width: 0 !important;
        border-top-right-radius: 0 !important;
        border-top-width: 0 !important;
        border-right-width: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-bottom-width: 0 !important;
        background-color: #e0e0e0;
        color: #85939d;
    }

    .eb-question-edit:focus-visible {
        outline: 0 none;
        padding-right: 1rem !important;
    }

   .eb-question-edit:focus {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-right-width: 0 !important;
        padding-right: 1rem !important;
    }

    .eb-submit-button {
        background-color: indigo;
        color: #e0e0e0;
        border-left-width: 0 !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-top-right-radius: 0.3rem !important;
        border-bottom-right-radius: 0.3rem !important;
        border: 0 1px 1px 1px solid #9400ff !important;
    }

    .eb-submit-button:focus-visible {
        border-left-width: 0 !important;
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        border-top-right-radius: 0.3rem !important;
        border-bottom-right-radius: 0.3rem !important;
    }

    .eb-submit-button:hover {
        background-color: #9400ff !important;
        border-color: #e0e0e0 !important;
        color: #e0e0e0 !important;
    }

    /* 
     * These are according to Bootstrap.
     * 
     * Extra small: <= 576px
     * Small: > 576px and <= 768px
     * Medium: > 768px and <= 992px
     * (I don't really care about the rest)
     */
    @media only screen and (max-width: 992px) {
        .eb-header {
            max-width: 100%;
        }

        .header-text {
            font-size: 2.5rem;
        }

        .eb-ball-image {
            width: 96px;
            height: 96px;
        }

        .eb-form-container {
            max-width: 100%;
        }
    }     

    @media only screen and (max-width: 768px) {
        body {
            padding: 10px;
        }

        .eb-question-edit  {
            font-size: 1rem;
        }
    }
        
    @media only screen and (max-width: 576px) {
        .header-text {
            font-size: 2rem;
        }

        .eb-ball-image {
            width: 64px;
            height: 64px;
        }        
    }

</style>

<!-- <body> -->

<?php
    $placeholder      = htmlentities(LOC_INPUT_PLACEHOLDER);
    $submit_caption   = htmlentities(LOC_SUBMIT_BUTTON);
    $invalid_feedback = htmlentities(LOC_MISSING_INPUT);
    $prompt_intro     = htmlentities(LOC_PROMPT_INTRO);
    $image_alt        = htmlentities(LOC_MAGIC_EIGHTBALL);
?>

<div class="eb-header">
    <img class="eb-ball-image" src="icon.svg" width="128" height="128" alt="<?php echo $image_alt; ?>" />
    <p class="header-text">
        <?php echo $prompt_intro; ?>
    </p>
</div>

<div class="row eb-form-container">
    <form class="needs-validation" action="index.php" method="get" novalidate>
        <div class="input-group col-6">
            <input type="text" id="eb-question-edit" class="eb-question-edit form-control-lg" name="q" placeholder="<?php echo $placeholder; ?>" autocomplete="off" autofocus required>        
            <button type="submit" class="eb-submit-button btn btn-lg btn-outline-secondary"><?php echo $submit_caption; ?></button>
            <div class="invalid-feedback">
                <?php echo $invalid_feedback; ?>
            </div>                
        </div>
    </form>
</form>
