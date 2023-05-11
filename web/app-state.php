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
const ME_Q_IN_PARAMS_AND_VALID = 1; // boolean: present and decoded from query params.
const ME_A_IN_PARAMS_AND_VALID = 2; // boolean: present and decoded from query params.
const ME_IN_ERROR_STATE        = 3; // boolean: render an error page.
const ME_ERROR_STATE_MESSAGE   = 4; // string: an error description to display on the page.
const ME_Q_NORMAL_VALUE        = 5; // string: plaintext copy of question.
const ME_A_NORMAL_VALUE        = 6; // string: plaintext copy of answer.
const ME_Q_PERMALINK_VALUE     = 7; // string: encoded copy of question for permalink.    
const ME_A_PERMALINK_VALUE     = 8; // string: encoded copy of answer for permalink.

// An immutable list of possible rendering modes, by name.
enum RenderModeName: string
{
    case Error                 = "error";
    case PromptForQuestion     = "prompt";
    case ComputeAnswer         = "compute";
    case DisplayAnswer         = "display";
    case DisplayAnswerSansLink = "display-sans-link";
}

final class MagicEightballAppState
{
    // Public //

    // the central decision-maker for how the app will render itself,
    // based on the app state.
    public function compute_render_mode_name(): RenderModeName
    {
        if ($this->get_in_error_state() === true)
            return RenderModeName::Error;

        $question = $this->get_q_normal_value();
        $answer = $this->get_a_normal_value();

        if ($this->get_q_in_params_and_valid() === false &&
            $this->get_a_in_params_and_valid() === false)
            return RenderModeName::PromptForQuestion;

        if (!empty($question) && !empty($answer)) {
            $q_encoded = $this->get_q_permalink_value();
            $a_encoded = $this->get_a_permalink_value();   
            
            if (empty($q_encoded) || empty($a_encoded))
                return RenderModeName::DisplayAnswerSansLink;
            
            return RenderModeName::DisplayAnswer;
        }

        if (!empty($question) && empty($answer))
            return RenderModeName::ComputeAnswer;        

        return RenderModeName::Error;
    }

    public function get_q_in_params_and_valid(): bool
    {
        return $this->_get_bool_from_map(ME_Q_IN_PARAMS_AND_VALID);
    }

    public function set_q_in_params_and_valid(bool $value): void
    {
        $this->_set_in_map(ME_Q_IN_PARAMS_AND_VALID, $value);
    }

    public function get_a_in_params_and_valid(): bool
    {
        return $this->_get_bool_from_map(ME_A_IN_PARAMS_AND_VALID);
    }

    public function set_a_in_params_and_valid(bool $value): void
    {
        $this->_set_in_map(ME_A_IN_PARAMS_AND_VALID, $value);
    }

    public function get_in_error_state(): bool
    {
        return $this->_get_bool_from_map(ME_IN_ERROR_STATE);
    }

    public function set_in_error_state(bool $value): void
    {
        $this->_set_in_map(ME_IN_ERROR_STATE, $value);
    }

    public function get_error_state_message(): string
    {
        $tmp = $this->_get_string_from_map(ME_ERROR_STATE_MESSAGE, true);

        if (empty($tmp))
            $tmp = htmlentities(LOC_ERRMSG_UNKNOWN);

        return $tmp;
    }

    public function set_error_state_message(string $value): void
    {
        if (empty($value))
            $value = LOC_ERRMSG_UNKNOWN;

        $this->_set_in_map(ME_ERROR_STATE_MESSAGE, $value);
    }

    public function get_q_normal_value(): string
    {
       return $this->_get_string_from_map(ME_Q_NORMAL_VALUE, true);
    }

    public function set_q_normal_value(string $value): void
    {
        $this->_set_in_map(ME_Q_NORMAL_VALUE, $value);
    }

    public function get_a_normal_value(): string
    {
        return $this->_get_string_from_map(ME_A_NORMAL_VALUE, true);
    }

    public function set_a_normal_value(string $value): void
    {
        $this->_set_in_map(ME_A_NORMAL_VALUE, $value);
    }

    public function get_q_permalink_value(): string
    {
        return $this->_get_string_from_map(ME_Q_PERMALINK_VALUE);
    }

    public function set_q_permalink_value(string $value): void
    {
        $this->_set_in_map(ME_Q_PERMALINK_VALUE, $value);
    }

    public function get_a_permalink_value(): string
    {
        return $this->_get_string_from_map(ME_A_PERMALINK_VALUE);
    }

    public function set_a_permalink_value(string $value): void
    {
        $this->_set_in_map(ME_A_PERMALINK_VALUE, $value);
    }

    public function has_permalink(): bool
    {
        $q_encoded = $this->get_q_permalink_value();
        $a_encoded = $this->get_a_permalink_value();

        return (!empty($q_encoded) && !empty($a_encoded));
    }

    public function get_permalink(): string
    {
        $q_encoded = $this->get_q_permalink_value();
        $a_encoded = $this->get_a_permalink_value();           
        return "/" . create_permalink_query_params($q_encoded, $a_encoded);
    }

    // Private //

    private function _get_string_from_map(string $key, bool $htmlencode = false): string
    {
        $ret = $this->_stateMap[$key] ?? "";
        if ($htmlencode === true)
            $ret = htmlentities($ret);
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
        ME_Q_IN_PARAMS_AND_VALID => false,
        ME_A_IN_PARAMS_AND_VALID => false,
        ME_IN_ERROR_STATE => false,
        ME_ERROR_STATE_MESSAGE => "",
        ME_Q_NORMAL_VALUE => "",
        ME_A_NORMAL_VALUE => "",
        ME_Q_PERMALINK_VALUE => "",
        ME_A_PERMALINK_VALUE => ""        
    );
}

$appState = new MagicEightballAppState();

?>
