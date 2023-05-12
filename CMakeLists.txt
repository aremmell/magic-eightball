cmake_minimum_required(VERSION 3.0.0)

# variables
set(PROJECT_NAME magic-eightball)

# policy settings, so CMake doesn't whine.
cmake_policy(SET CMP0048 NEW) # project VERSION
cmake_policy(SET CMP0065 NEW) # ENABLE_EXPORTS
cmake_policy(SET CMP0126 NEW) # cache
cmake_policy(SET CMP0056 NEW) # CMAKE_EXE_LINKER_FLAGS
cmake_policy(SET CMP0066 NEW) # CMAKE_<LANG>_FLAGS_<BUILDTYPE>

# toolchain-related
set(CXX_FLAGS_DEBUG -g -O0 -DDEBUG)
set(CXX_FLAGS_RELEASE -O3 -DNDEBUG)
set(CMAKE_CXX_COMPILER /usr/bin/g++)

# define the project
project(
    ${PROJECT_NAME}
    VERSION 1.2.1
    LANGUAGES CXX
    DESCRIPTION "The Magic Eightball toy, in digital form."
    HOMEPAGE_URL https://github.com/aremmell/magic-eightball/
)

execute_process(
    COMMAND git rev-parse --short --verify HEAD
    OUTPUT_VARIABLE GIT_COMMIT_HASH
    OUTPUT_STRIP_TRAILING_WHITESPACE
    ECHO_OUTPUT_VARIABLE
    COMMAND_ERROR_IS_FATAL ANY
)

configure_file(
    src/version.h.in
    include/version.h
    NEWLINE_STYLE LF
)

add_executable(
    ${PROJECT_NAME}
    src/magic-eightball.cpp
    src/cmdline.cpp
    src/main.cpp
)

target_include_directories(
    ${PROJECT_NAME}
    PUBLIC
    src
    ${CMAKE_CURRENT_BINARY_DIR}/include
)

target_compile_features(
    ${PROJECT_NAME}
    PUBLIC
    cxx_std_14
)

install(
    PROGRAMS ${CMAKE_CURRENT_BINARY_DIR}/${PROJECT_NAME}
    TYPE BIN
    CONFIGURATIONS Release
)
