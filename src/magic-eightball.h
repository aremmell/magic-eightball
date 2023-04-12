/*
 * eightball.h:
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

#ifndef MAGIC_EIGHTBALL_H
#define MAGIC_EIGHTBALL_H

#include <string>

namespace magic_eightball
{
    enum class AnswerType : uint8_t
    {
        Affirmative = 0U,
        Noncommital,
        Negative
    };
    
    struct EightBallAnswer
    {
        AnswerType type;
        const wchar_t *text;
    };
    
    static const EightBallAnswer Answers[] =
    {
        { AnswerType::Affirmative, L"It is certain" },
        { AnswerType::Affirmative, L"It is decidedly so" },
        { AnswerType::Affirmative, L"Without a doubt" },
        { AnswerType::Affirmative, L"Yes definitely" },
        { AnswerType::Affirmative, L"You may rely on it" },
        { AnswerType::Affirmative, L"As I see it, yes" },
        { AnswerType::Affirmative, L"Most likely" },
        { AnswerType::Affirmative, L"Outlook good" },
        { AnswerType::Affirmative, L"Yes" },
        { AnswerType::Affirmative, L"Signs point to yes" },
        { AnswerType::Noncommital, L"Reply hazy try again" },
        { AnswerType::Noncommital, L"Ask again later" },
        { AnswerType::Noncommital, L"Better not tell you now" },
        { AnswerType::Noncommital, L"Cannot predict now" },
        { AnswerType::Noncommital, L"Concentrate and ask again" },
        { AnswerType::Negative, L"Don't count on it" },
        { AnswerType::Negative, L"My reply is no" },
        { AnswerType::Negative, L"My sources say no" },
        { AnswerType::Negative, L"Outlook not so good" },
        { AnswerType::Negative, L"Very doubtful" },
    };
    
    std::wstring RetrieveQuestion();
    std::wstring ComputeMagicAnswer(const std::wstring& question);
    std::wstring EightBallASCII(const std::wstring& answer);
    void ProcessQuestion(const std::wstring& question, bool printQuestion = false, bool noAscii = false);
    bool PrintUsage();
    void PrintBanner();

}; // !namespace magic_eightball

#endif // !MAGIC_EIGHTBALL_H
