<?php

?>

<style>
    .header-text {
        font-size: 3rem;
        margin-bottom: 2rem;
    }

    .eb-form-container {
        max-width: 66%;
        margin-left: auto;
        margin-right: auto;
    }

    .eb-question-edit {
        min-width: 470px;
        width: 100%;

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
        border-left-width: 0 !important;
        border-top-width: 0 !important;
        border-right-width: 0 !important;
        border-bottom-width: 0 !important;
        transition: border-color none !important;
    }

   .eb-question-edit:focus {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-right-width: 0 !important;

        padding-top: 0.375rem;
        padding-right: 0.75rem;
        padding-bottom: 0.375rem;
        padding-left: 0.75rem;

        box-shadow: none !important;
        transition: border-color none !important;
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
</style>

<!-- <body> -->

<div class="eb-header">
    <p class="header-text">
        TODO: Go ahead, ask me anything.
    </p>
</div>

<?php
    $placeholder = htmlentities(LOC_INPUT_PLACEHOLDER);
    $submit_caption = htmlentities(LOC_SUBMIT_BUTTON);
    $invalid_feedback = htmlentities(LOC_MISSING_INPUT);
?>

<div class="row eb-form-container">
    <form class="needs-validation" action="index.php" method="get" novalidate>
        <div class="input-group col-6">
            <input type="text" id="eb-question-edit" class="eb-question-edit form-control" name="q" placeholder="<?php echo $placeholder; ?>" autocomplete="off" autofocus required>        
            <button type="submit" class="eb-submit-button btn btn-outline-secondary"><?php echo $submit_caption; ?></button>
            <div class="invalid-feedback">
                <?php echo $invalid_feedback; ?>
            </div>                
        </div>
    </form>
</form>

