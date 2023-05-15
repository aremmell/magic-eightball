#!/bin/bash

#
# This script is essentially just a wrapper around CMake Tools.
# It is primarily used for Travis CI, but can also be used to build
# the project outside of VS Code.
#
set -x
_current_dir=$(pwd)

function cmake_configure() {
    cmake --version; \
    mkdir -p build/install && cmake --preset release
}

function cmake_build() {
    cmake --build --preset release
}

function cmake_test() {
    build/magic-eightball -q "Will this build work?"
}

_args=("$@")

case "${_args[0]}" in
    "configure")
        cmake_configure
        ;;
    "build")
        cmake_build
        ;;
    "test")
        cmake_test
        ;;
    *)
        echo "Usage: $0 [configure|build|test]"
        exit 1
        ;;
esac
