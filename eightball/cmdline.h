/*
 * cmdline.h:
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
