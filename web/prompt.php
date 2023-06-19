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
        min-width: 312px !important;
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
</style>

<!-- <body> -->

<div class="eb-header">
    <img class="eb-ball-image" src="icon.svg" width="128" height="128" alt="<?php echo_loc_string(LOC_MAGIC_EIGHTBALL); ?>" />
    <p class="header-text">
        <?php echo_loc_string(LOC_PROMPT_INTRO); ?>
    </p>
</div>

<div class="eb-form-container">
    <form class="needs-validation" action="index.php" method="get" novalidate>
        <div class="input-group col-6">
            <input type="text" id="eb-question-edit" class="eb-question-edit form-control-lg" name="q" placeholder="<?php echo_loc_string(LOC_INPUT_PLACEHOLDER); ?>" autocomplete="off" autofocus required>        
            <button type="submit" class="eb-submit-button btn btn-lg btn-outline-secondary"><?php echo_loc_string(LOC_SUBMIT_BUTTON); ?></button>
            <div class="invalid-feedback">
                <?php echo_loc_string(LOC_MISSING_INPUT); ?>
            </div>
        </div>
    </form>
</form>
