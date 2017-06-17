//
// The Magic 8-ball
//
// Created by Ryan Lederman <lederman@gmail.com> on 17 June 2017
//
// License: MIT
// Original Copyright (C) Mattel Inc. This program is not affiliated with Mattel Inc.,
// and they retain all intellecutal property rights to the Magic 8-Ball.
//

#include "cmdline.h"
#include "eightball.h"
#include "convert.h"
#include <iostream>
#include <cwchar>

using namespace std;

namespace eightball
{
    //
    // CommandLine : Public
    //
    bool CommandLine::ProcessCommandLine(int argc, const char** argv)
    {
        if (argc > 1) {

            for (int n = 1; n < argc; n++) {
                if (0 == strcmp(argv[n], CommandLineArgVersion)) {
                    PrintBanner(); break;
                } else if (0 == strcmp(argv[n], CommandLineArgNoAscii)) {
                    _noAscii = true;
                } else if (0 == strcmp(argv[n], CommandLineArgQuestion)) {
                    if (argc <= n + 1)
                        return PrintUsage();
                    _question = UTF8ToWideString(argv[++n]);
                } else {
                    wcout << L"Unknown argument: " << argv[n] << endl;
                    return PrintUsage();
                }
            }
        }

        return true;
    }
}; // !namespace eightball
