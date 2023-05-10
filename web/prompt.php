<?php

?>

<style>

    .eb-form-container {
        font-size: 1.2rem;
        max-width: 66%;
        margin-right: auto;
        margin-left: auto;
    }

    .eb-question-edit {
        display: flex;
        flex-grow: 1;
        flex-basis: fit-content;
        min-width: 470px;
        width: 100%;
        flex-shrink: 0;

        border-left-width: 0 !important;
        border-top-right-radius: 0 !important;
        border-top-width: 0 !important;
        border-right-width: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-bottom-width: 0 !important;
        line-height: 24px;
        background-color: #e0e0e0;
        color: #85939d;
    }

    /*.eb-question-edit:focus-visible {
        border-left-width: 0 !important;
        border-top-width: 0 !important;
        border-right-width: 0 !important;
        border-bottom-width: 0 !important;
        transition: border-color none !important;
    }

   .eb-question-edit:focus {

        /* box-shadow: inset 0 0 0 0 #9400ff, inset 0 0 0 2px #e0e0e0; * /

        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-right-width: 0 !important;

        padding-top: 0.375rem;
        padding-right: 0.75rem;
        padding-bottom: 0.375rem;
        padding-left: 0.75rem;

        box-shadow: none !important;
        transition: border-color none !important;
    }*/

    .single-line-input-group {
        display: flex;
        flex-direction: row;
        justify-content: center;
        width: 100%;
    }

    .eb-submit-button {
        display: flex;
        flex-grow: 0;
        flex-basis: fit-content;
        flex-shrink: 1;
        background-color: indigo;
        color: #e0e0e0;
        border-left-width: 0;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 0.3rem;
        border-bottom-right-radius: 0.3rem;
        border: 0 1px 1px 1px solid #9400ff;
    }

    .eb-submit-button:focus-visible {
        border-left-width: 0;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-top-right-radius: 0.3rem;
        border-bottom-right-radius: 0.3rem;
    }

    .eb-submit-button:hover {
        background-color: #9400ff !important;
        border-color: #e0e0e0 !important;
        color: #e0e0e0 !important;
    }
</style>

<!-- <body> -->


<div class="display-header">
    <?php ?>
</div>

<?php
    $placeholder = htmlentities(LOC_INPUT_PLACEHOLDER);
    $submit_caption = htmlentities(LOC_SUBMIT_BUTTON);
?>

<link rel="stylesheet" href="css/question-form.css">

<div class="eb-form-container">
    <form class="needs-validation" action="index.php" method="get">
        <input type="text" class="eb-question-edit form-control xm_auto" name="q" placeholder="<?php echo $placeholder; ?>" autocomplete="off" autofocus required>
        <button type="submit" class="eb-submit-button"><?php echo $submit_caption; ?></button>
    </form>
</div>
