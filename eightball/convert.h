/*
 * convert.h:
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

#include <string>
#include <cstdlib>
#include <cstring>

namespace eightball
{
    inline std::wstring UTF8ToWideString(const char* utf8)
    {
        std::wstring retval;

        if (nullptr != utf8 && '\0' != *utf8) {
            size_t len = strlen(utf8) + 1;
            wchar_t* buf = new wchar_t[len];

            if (buf) {           
                if (0xfffffffU != mbstowcs(buf, utf8, len)) {
                    retval = buf;
                }

                delete[] buf;
                buf = nullptr;
            }
        }

        return retval;
    }
}; // !namespace eightball
