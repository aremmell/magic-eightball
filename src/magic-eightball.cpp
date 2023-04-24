/*
 *MAGIC_EIGHTBALL_NAME.cpp:
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

#include "magic-eightball.h"
#include "version.h"
#include "util.h"

#include <iostream>
#include <sstream>
#include <memory>
#include <chrono>
#include <random>

using namespace std;
using namespace std::chrono;

namespace magic_eightball
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

/*
  .o      .ooooo.       o.   
 .8'     d88'   `8.     `8.  
.8'      Y88..  .8'      `8. 
88        `88888b.        88 
88       .8'  ``88b       88 
`8.      `8.   .88P      .8' 
 `8.      `boood8'      .8'  
  `"                    "'   

       .n                       =u        
     z8"        u+=~~~+u.        '%b.     
   x88~       z8F      `8N.        88b    
  x888       d88L       98E        ?88N   
 :888R       98888bu.. .@*         4888b  
 8888R       "88888888NNu.         48888r 
:8888R        "*8888888888i        48888k 
48888R        .zf""*8888888L       48888R 
'8888R       d8F      ^%888E       48888F 
 8888R       88>        `88~       48888  
 '888R       '%N.       d*"        4888F  
  "888          ^"====="`          d88P   
   ^88L                           .88"    
     "Ru                         .@%      
       '"                       "         



*/
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
        wcout << "Usage:" << endl << PROGRAM_NAME << L" [[--no-ascii] -q <question>|--version]" << endl;
        return false;
    }

    void PrintBanner()
    {
        wcout << "magic-eightball v" << VERSION_MAJOR << "." << VERSION_MINOR << "." 
              << VERSION_PATCH << " (" << GIT_COMMIT_HASH << ")" << endl;
    }
}; // !namespace magic_eightball
