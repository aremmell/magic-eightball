//
// The Magic 8-ball
//
// Created by Ryan Lederman <lederman@gmail.com> on 14 June 2017
//
// License: MIT
// Original Copyright (C) Mattel Inc. This program is not affiliated with Mattel Inc.,
// and they retain all intellecutal property rights to the Magic 8-Ball.
//

#ifndef EIGHTBALL_H
#define EIGHTBALL_H

#include <string>

namespace eightball
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

}; // !namespace eightball


#endif // !EIGHTBALL_H

