/*
 * cmdline.cpp:
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
