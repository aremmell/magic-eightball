//
// eightball command-line utility
//

#include "eightball.h"
#include "crc32.h"

#include <iostream>
#include <sstream>
#include <chrono>

using namespace std;
using namespace std::chrono;

namespace eightball {
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
            uint8_t* buf = new uint8_t[buflen];

            if (buf) {
                // Fill buffer with input question, last CRC checksum (if present), and current time.
                memcpy(buf, question.c_str(), questionLen * 2);
                memcpy(buf + (questionLen * 2), &lastCrc32, sizeof(uint32_t));

                microseconds now = duration_cast<microseconds>(steady_clock::now().time_since_epoch());
                memcpy(buf + (questionLen * 2) + sizeof(uint32_t), &now, sizeof(microseconds::rep));

                // Compute CRC32.
                uint32_t crc32 = crc32c(0U, buf, buflen);

                // Store checksum for next pass.
                lastCrc32 = crc32;

                // Seed RNG with new checksum.
                srand(crc32);

                // Look up magic answer!
                retval = Answers[rand() % (sizeof(Answers) / sizeof(Answers[0]))];

                delete[] buf;
                buf = nullptr;
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
    
    int PrintUsage()
    {
        // TODO: If command-line arguments are added, implement this.
        return 1;
    }
}; // !namespace eightball
