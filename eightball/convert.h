//
// The Magic 8-ball
//
// Created by Ryan Lederman <lederman@gmail.com> on 17 June 2017
//
// License: MIT
// Original Copyright (C) Mattel Inc. This program is not affiliated with Mattel Inc.,
// and they retain all intellecutal property rights to the Magic 8-Ball.
//

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
