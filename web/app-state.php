<?php
/*
 * app-state.php:
 *
 * This file is part of the Magic Eightball project.
 *
 * Author: Ryan M. Lederman <lederman@gmail.com>
 * GitHub: https://github.com/aremmell/magic-eightball/
 * Play online: https://rml.dev/magic-eightball/
 *
 * Please read the LICENSE file if you intend to modify
 * or redistribute the source code contained herein.
 */

 // Key-value pairs for the app state map (array). Ensure that if you add any new entries,
 // they are also initialized in the MagicEightballAppState::_stateMap array with a default (invalid) value.
const ME_IN_ERROR_STATE       = 1; // boolean: render an error page.
const ME_ERROR_STATE_MESSAGE  = 2; // string: an error description to display on an error page.
const ME_QUESTION_VALUE       = 3; // string: plaintext copy of question.
const ME_ANSWER_VALUE         = 4; // string: plaintext copy of answer.

// Other miscelleaneous constants.
const ME_GITHUB_REPO = "https://github.com/aremmell/magic-eightball";
const ME_CLI_BINARY  = "magic-eightball";
const ME_CLI_CMDLINE = "--no-ascii -q ";

// An immutable list of possible rendering modes.
enum RenderMode: string
{
    case Error                 = "error";
    case PromptForQuestion     = "prompt";
    case ComputeAnswer         = "compute";
    case DisplayAnswer         = "display";

    public const DisplayAnswerSansLink = self::DisplayAnswer;
}

final class MagicEightballAppState
{
    // Public //
    public function compute_render_mode(): RenderMode
    {
        return $this->_renderMode = $this->_compute_render_mode();
    }

    public function get_in_error_state(null|string& $msg): bool
    {
        if ($msg)
            $msg = $this->get_error_state_message();

        return $this->_get_bool_from_map(ME_IN_ERROR_STATE);
    }

    public function set_in_error_state(bool $value, string $msg): void
    {
        $this->_set_in_map(ME_IN_ERROR_STATE, $value);

        if (empty($msg))
            $msg = LOC_ERRMSG_UNKNOWN;

        $this->_set_in_map(ME_ERROR_STATE_MESSAGE, $msg);
    }

    public function get_error_state_message(): string
    {
        $tmp = $this->_get_string_from_map(ME_ERROR_STATE_MESSAGE, true);

        if (empty($tmp))
            $tmp = get_html_ready_loc_string(LOC_ERRMSG_UNKNOWN);

        return $tmp;
    }

    public function set_error_state_message(string $value): void
    {
        if (empty($value))
            $value = LOC_ERRMSG_UNKNOWN;

        $this->_set_in_map(ME_ERROR_STATE_MESSAGE, $value);
    }

    public function get_question_value(): string
    {
       return $this->_get_string_from_map(ME_QUESTION_VALUE, true);
    }

    public function set_question_value(string $value): void
    {
        $this->_set_in_map(ME_QUESTION_VALUE, $value);
    }

    public function get_answer_value(): string
    {
        return $this->_get_string_from_map(ME_ANSWER_VALUE, true);
    }

    public function set_answer_value(string $value): void
    {
        $this->_set_in_map(ME_ANSWER_VALUE, $value);
    }

    public function get_q_permalink_value(): string
    {
        return encode_permalink_data($this->get_question_value());
    }

    public function get_a_permalink_value(): string
    {
        return encode_permalink_data($this->get_answer_value());
    }

    public function get_permalink(): string
    {
        // Can we permalink?
        if ($this->_renderMode == RenderMode::DisplayAnswerSansLink)
            return "";

        $params = create_permalink_query_params($this->get_q_permalink_value(),
            $this->get_a_permalink_value());
            
        if (empty($params))
            return "";

        $prefix = create_permalink_prefix();

        if (empty($prefix))
            return "";
        
        return $prefix . $params;
    }

    // Private //

    // the central decision-maker for how the app will render itself,
    // based on the app state.
    private function _compute_render_mode(): RenderMode
    {
        if ($this->get_in_error_state() === true)
            return RenderMode::Error;

        $question = $this->get_question_value();
        $answer = $this->get_answer_value();

        if (empty($question) && empty($answer))
            return RenderMode::PromptForQuestion;

        if (!empty($question) && !empty($answer)) {

            $q_encoded = $this->get_q_permalink_value();
            $a_encoded = $this->get_a_permalink_value();   
            
            if (empty($q_encoded) || empty($a_encoded))
                return RenderMode::DisplayAnswerSansLink;
            
            return RenderMode::DisplayAnswer;
        }

        if (!empty($question) && empty($answer))
            return RenderMode::ComputeAnswer;        

        return RenderMode::Error;
    }    

    private function _get_string_from_map(string $key, bool $htmlencode = false): string
    {
        $ret = $this->_stateMap[$key] ?? "";
        if ($htmlencode === true)
            $ret = get_html_ready_loc_string($ret);
        return $ret;
    }

    private function _get_bool_from_map(string $key): bool
    {
        return $this->_stateMap[$key] ?? false;
    }

    private function _set_in_map(string $key, mixed $value): void
    {
        $this->_stateMap[$key] = $value;
    }

    private $_stateMap = array(
        ME_IN_ERROR_STATE => false,
        ME_ERROR_STATE_MESSAGE => "",
        ME_QUESTION_VALUE => "",
        ME_ANSWER_VALUE => "",   
    );

    private $_renderMode = RenderMode::Error;
}

// Global application state.
$appState = new MagicEightballAppState();

?>
