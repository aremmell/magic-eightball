//
// The Magic 8-ball
//
// Created by Ryan Lederman <lederman@gmail.com> on 14 June 2017
//
// License: MIT
// Original Copyright (C) Mattel Inc. This program is not affiliated with Mattel Inc.,
// and they retain all intellecutal property rights to the Magic 8-Ball.
//

#ifndef CRC32_H
#define CRC32_H

#include <stdint.h>
#include <stddef.h>

#define POLY 0xedb88320

inline uint32_t crc32c(uint32_t crc, const unsigned char *buf, size_t len)
{
    int k;

    crc = ~crc;
    while (len--) {
        crc ^= *buf++;
        for (k = 0; k < 8; k++)
            crc = crc & 1 ? (crc >> 1) ^ POLY : crc >> 1;
    }
    return ~crc;
}

#endif // !CRC32_H
