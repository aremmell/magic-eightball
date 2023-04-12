/*
 * main.cpp:
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

#include "magic-eightball.h"
#include "cmdline.h"
#include <iostream>

using namespace std;
using namespace magic_eightball;

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
