/*
 * version.h:
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

#include <cstdint>
#include <cwchar>

namespace magic_eightball
{
    constexpr wchar_t* PROGRAM_NAME    = L"magic-eightball";
    constexpr wchar_t* GIT_COMMIT_HASH = L"";    
    constexpr uint8_t VERSION_MAJOR    = 1;
    constexpr uint8_t VERSION_MINOR    = 2;
    constexpr uint8_t VERSION_BUILD    = 0;
}; // !namespace magic_eightball
