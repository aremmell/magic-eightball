//
// eightball command-line utility
//

#include <iostream>
#include "eightball.h"

using namespace std;
using namespace eightball;

int main(int argc, const char * argv[]) {
    
    do {
        wcout << L"What is your question? ";
        wstring question = RetrieveQuestion();
        
        if (1 == question.length() && tolower(question.at(0)) == 'q')
            break;
        
        wcout << endl << EightBallASCII(ComputeMagicAnswer(question)) << endl;
    } while (true);
    
    return 0;
}
