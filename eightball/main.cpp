//
// The Magic 8-ball
//
// Created by Ryan Lederman <lederman@gmail.com> on 14 June 2017
//
// License: MIT
// Original Copyright (C) Mattel Inc. This program is not affiliated with Mattel Inc.,
// and they retain all intellecutal property rights to the Magic 8-Ball.
//

#include "eightball.h"
#include "cmdline.h"
#include <iostream>

using namespace std;
using namespace eightball;

int main(int argc, const char** argv) {
    CommandLine cmdLine;
    if (!cmdLine.ProcessCommandLine(argc, argv))
        return 1;

    wstring question = cmdLine.GetQuestion();
    bool noAscii = cmdLine.NoAscii();

    if (!question.empty()) {
        ProcessQuestion(question, true, noAscii);
    } else {
        do { // No question from command-line; get input from stdin.
            wcout << L"What is your question? ";
            wstring question = RetrieveQuestion();
        
            switch (question.length()) {
                case 0: {
                    continue;
                }
                break;
                case 1: {
                    if (tolower(question.at(0)) == 'q')
                        return 0;
                }
                break;
            }
        
            ProcessQuestion(question, false, noAscii);
        } while (true);
    }
    
    return 0;
}
