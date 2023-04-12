/*
 * eightball.cpp:
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

#include "eightball.h"
#include "crc32.h"

#include <iostream>
#include <sstream>
#include <cstring>
#include <memory>
#include <chrono>
#include <random>

using namespace std;
using namespace std::chrono;

const float EIGHTBALL_VERSION = 1.1f;

namespace eightball
{
    wstring RetrieveQuestion()
    {
        wstring question;
        getline(wcin, question);
        return question;
    }
    
    wstring ComputeMagicAnswer(const wstring& question)
    {
        wstring retval;

        if (!question.empty()) {
            static uint32_t lastCrc32 = 0U;

            size_t questionLen = question.length();
            size_t buflen = questionLen * 2 + sizeof(uint32_t) + sizeof(time_t);
            shared_ptr<uint8_t> buf(new uint8_t[buflen]);

            if (buf) {
                // Fill buffer with input question, last CRC checksum (if present), and current time.
                memmove(buf.get(), question.c_str(), questionLen * 2);
                memmove(buf.get() + (questionLen * 2), &lastCrc32, sizeof(uint32_t));

                microseconds now = duration_cast<microseconds>(steady_clock::now().time_since_epoch());
                memmove(buf.get() + (questionLen * 2) + sizeof(uint32_t), &now, sizeof(microseconds::rep));

                // Compute CRC32.
                uint32_t crc32 = crc32c(0U, buf.get(), buflen);

                // Store checksum for next pass.
                lastCrc32 = crc32;

                // Seed RNG with new checksum (uses 32-bit mersenne twister).
                shared_ptr<mt19937> rng(new mt19937(crc32));

                // Look up magic answer!
                retval = rng ? Answers[(*rng)() % (sizeof(Answers) / sizeof(Answers[0]))].text : L"ERROR!";
            }
        }

        return retval;
    }

    wstring EightBallASCII(const wstring& answer)
    {
        wstringstream strm;

        strm << L"    ,888888888." << endl;
        strm << L"  d8'         `8b" << endl;
        strm << L" 88     888      88" << endl;
        strm << L"88     8   8      88" << endl;
        strm << L"88      888       88" << "\t" << answer << endl;
        strm << L"88     8   8      88" << endl;
        strm << L" 88     888      88" << endl;
        strm << L"  Y8.          .8P" << endl;
        strm << L"    `888888888P`'" << endl;

        return strm.str();
    }

    void ProcessQuestion(const wstring& question, bool printQuestion /*= false*/,
        bool noAscii /*= false*/)
    {
        if (noAscii) {
            wcout << ComputeMagicAnswer(question);
        } else {
            if (printQuestion)
                wcout << endl << L"You asked: " << question << endl;
            wcout << endl << EightBallASCII(ComputeMagicAnswer(question));
        }

        wcout << endl;
    }
    
    bool PrintUsage()
    {
        wcout << "Usage:" << endl << L"eightball [[--no-ascii] -q <question>|--version]" << endl;
        return false;
    }

    void PrintBanner()
    {
        wcout << "eightball v" << EIGHTBALL_VERSION << endl;
    }
}; // !namespace eightball
