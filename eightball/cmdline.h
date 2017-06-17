//
// The Magic 8-ball
//
// Created by Ryan Lederman <lederman@gmail.com> on 17 June 2017
//
// License: MIT
// Original Copyright (C) Mattel Inc. This program is not affiliated with Mattel Inc.,
// and they retain all intellecutal property rights to the Magic 8-Ball.
//

#ifndef EIGHTBALL_CMDLINE_H
#define EIGHTBALL_CMDLINE_H

#include <string>

namespace eightball
{
    // Command-line constants
    static const char* CommandLineArgQuestion    = "-q";
    static const char* CommandLineArgVersion     = "--version";
    static const char* CommandLineArgNoAscii     = "--no-ascii";

    // Implementation
    class CommandLine
    {
    public:
        bool ProcessCommandLine(int argc, const char** argv);

        std::wstring GetQuestion() { return _question; }
        bool NoAscii() { return _noAscii; }
    
    private:
        std::wstring _question;
        bool _noAscii = false;
    };
    
}; // !namespace eightball

#endif // !EIGHTBALL_CMDLINE_H
