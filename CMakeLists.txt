cmake_minimum_required(VERSION 3.0.0)

# variables
set(PROJECT_NAME magic-eightball)

# define the project
project(
    ${PROJECT_NAME}
    VERSION 1.2
    DESCRIPTION The Magic Eightball toy, in digital form.
    HOMEPAGE_URL https://github.com/aremmell/magic-eightball/
    LANGUAGES CXX C
)

# policy settings, so CMake doesn't whine.
cmake_policy(SET CMP0048 NEW) # project VERSION
cmake_policy(SET CMP0065 NEW) # ENABLE_EXPORTS

# add source and binary directories.
add_subdirectory(${CMAKE_SOURCE_DIR} ${CMAKE_BINARY_DIR})

# don't even know what this does.
set(CPACK_PROJECT_NAME ${PROJECT_NAME})
set(CPACK_PROJECT_VERSION ${PROJECT_VERSION})
include(CPack)
