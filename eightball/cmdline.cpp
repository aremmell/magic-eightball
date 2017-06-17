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
#include <iostream>
#include <cwchar>

using namespace std;

namespace eightball
{
    bool ProcessCommandLine(int argc, const wchar_t** argv)
    {
        if (argc > 1) {

            for (int n = 1; n < argc; n++) {
                if (0 == wcscmp(argv[n], CommandLineArgQuestion)) {
                    if (argc <= n + 1) {
                        PrintUsage();
                    } else {
                        ProcessQuestion(argv[n + 1], true);
                    }
                    break;
                } else if (0 == wcscmp(argv[n], CommandLineArgVersion)) {
                    PrintBanner();
                    break;
                } else {
                    wcout << L"Unknown argument: " << argv[n] << endl;
                    PrintUsage();
                    break;
                }
            }

            return true;
        }

        return false;
    }
}; // !namespace eightball
