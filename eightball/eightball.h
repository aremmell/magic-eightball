//
// eightball command-line utility
//

#ifndef EIGHTBALL_H
#define EIGHTBALL_H

#include <string>

namespace eightball
{
    static const wchar_t* Answers[] =
    {
        L"As I see it, yes",
        L"It is certain",
        L"It is decidedly so",
        L"Most likely",
        L"Outlook good",
        L"Signs point to yes",
        L"Without a doubt",
        L"Yes",
        L"Yes, definitely",
        L"You may rely on it",
        L"Reply hazy; try again",
        L"Ask again later",
        L"Better not tell you",
        L"Cannot say at this time",
        L"Concentrate and ask again",
        L"Don't count on it",
        L"My reply is no",
        L"My sources say no",
        L"Outlook not good",
        L"Very doubtful",
    };
    
    std::wstring RetrieveQuestion();
    std::wstring ComputeMagicAnswer(const std::wstring& question);
    std::wstring EightBallASCII(const std::wstring& answer);
    int PrintUsage();

}; // !namespace eightball


#endif // !EIGHTBALL_H

