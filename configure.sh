#!/bin/bash

update_cmake_from_publisher() {
    local _arch=$(uname -m)
    local _url=""

    echo "Machine architecture is: $_arch"

    if [[ $_arch = "x86_64" ]]; then
        _url="https://github.com/Kitware/CMake/releases/download/v3.26.3/cmake-3.26.3-linux-x86_64.sh"
    elif [[ $_arch = "aarch64" ]]; then
        _url="https://github.com/Kitware/CMake/releases/download/v3.26.3/cmake-3.26.3-linux-aarch64.sh"
    else
        echo "No package provided for $_arch. Would need to build from source. Giving up."
        return 1
    fi

    echo "Downloading cmake 3.26.3-linux-${_arch} from Kitware..."
    if ! wget "${_url}" -O cmake-installer.sh; then
        echo "Download failed; Giving up."
        return 1
    fi

    echo "Completed download; installing..."
    if sudo chmod +x cmake-installer.sh && sudo ./cmake-installer.sh --skip-license --prefix=/usr/local; then
        sudo rm cmake-installer.sh
        export PATH="/usr/local/bin:$PATH"
        echo "Installation completed in /usr/local; PATH variable updated. Done."
        return 0
    else
        echo "Installation failed. Giving up."
        return 1
    fi
}

check_cmake_version() {
    local _min_major=3
    local _min_minor=20
    local _min_patch=0
    local _too_old=false

    echo "The minimum required version is $_min_major.$_min_minor.$_min_patch"
    echo "Checking current version..."

    local _ver_output=$(cmake --version)
    if [[ $_ver_output =~ [^\d]+([0-9]+)\.([0-9]+)\.([0-9]+) ]]; then
        local _major=${BASH_REMATCH[1]}
        local _minor=${BASH_REMATCH[2]}
        local _patch=${BASH_REMATCH[3]}
        
        echo "$(which cmake) = $_major.$_minor.$_patch"

        if [[ $_major -lt $_min_major ]] || [[ $_minor -lt $_min_minor ]] || \
           [[ $_patch -lt $_min_patch ]]; then
            _too_old=true
            echo "Proceeding to upgrade."
        else
            echo "Current cmake is sufficient to build with."
        fi
    else
        _too_old = true
        echo "Unable to determine cmake version; proceeding to upgrade."
    fi

    if [[ $_too_old = true ]]; then
        update_cmake_from_publisher
    fi
}

_args=($@)
case ${_args[0]} in
    "check-cmake")
        check_cmake_version
        ;;
    *)
        echo "Usage: $0 [check-cmake]"
        ;;
esac

