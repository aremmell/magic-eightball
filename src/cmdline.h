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

#ifndef MAGIC_EIGHTBALL_CMDLINE_H
#define MAGIC_EIGHTBALL_CMDLINE_H

#include <string>

namespace magic_eightball
{
    // Command-line constants
    constexpr char* CommandLineArgQuestion = "-q";
    constexpr char* CommandLineArgVersion  = "--version";
    constexpr char* CommandLineArgNoAscii  = "--no-ascii";

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
    
}; // !namespace magic_eightball

#endif // !MAGIC_EIGHTBALL_CMDLINE_H
