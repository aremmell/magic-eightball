/*
 * util.h:
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

#ifndef MAGIC_EIGHTBALL_UTIL_H
#define MAGIC_EIGHTBALL_UTIL_H

#include <string>
#include <cstdint>
#include <cstdlib>
#include <cstring>
#include <stddef.h>

using namespace std;

namespace magic_eightball
{
    inline wstring UTF8ToWideString(const char* utf8)
    {
        wstring retval;

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

   inline  uint32_t crc32c(uint32_t crc, const unsigned char *buf, size_t len)
    {
        constexpr uint32_t POLY = 0xedb88320U;
        int k = 0;

        crc = ~crc;
        while (len--) {
            crc ^= *buf++;
            for (k = 0; k < 8; k++)
                crc = crc & 1 ? (crc >> 1) ^ POLY : crc >> 1;
        }
        return ~crc;
    }    
}; // !namespace magic_eightball

#endif // !MAGIC_EIGHTBALL_UTIL_H
