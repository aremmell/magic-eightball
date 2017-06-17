all:
	mkdir bin
	g++ -o bin/eightball eightball/main.cpp eightball/eightball.cpp eightball/cmdline.cpp -std=c++11
