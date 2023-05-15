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

# $1 = the name of a variable to receive
# an array containing the version segments
get_cmake_version() {
    if [[ -z ${1} ]]; then
        return 1
    fi

    local _ver_output=$(cmake --version)
    if [[ $_ver_output =~ [^\d]+([0-9]+)\.([0-9]+)\.([0-9]+) ]]; then
        local _major=${BASH_REMATCH[1]}
        local _minor=${BASH_REMATCH[2]}
        local _patch=${BASH_REMATCH[3]}

        eval $1'=($_major $_minor $_patch)'
        return 0
    fi

    return 1
}

# $1 = version number array from get_cmake_version.
is_cmake_preset_capable() {
    local _min_major=3
    local _min_minor=21
    local _min_patch=0

    if [[ ${1} -lt $_min_major ]] || [[ ${2} -lt $_min_minor ]] || \
       [[ ${3} -lt $_min_patch ]]; then
        return 1
    fi

    return 0
}

# $1 = version number array from get_cmake_version.
print_cmake_version() {
    echo "${1}.${2}.${3}"
}

print_cmake_info() {
    _cur_ver=()
    get_cmake_version _cur_ver
    
    echo -n "cmake = $(which cmake), and its version is $(print_cmake_version ${_cur_ver[@]})"

    if is_cmake_preset_capable "${_cur_ver[@]}"; then
        echo " (preset capable)"
    else
        echo " (NOT preset capable)"
    fi 
}

update_cmake_if_required() {
    local _too_old=false

    echo "Checking current version..."

    _cur_ver=();
    if get_cmake_version _cur_ver; then
        echo "Current version is $(print_cmake_version ${_cur_ver[@]})"    
        if ! is_cmake_preset_capable; then
            _too_old=true
        fi
    else
        _too_old = true
    fi

    if [[ $_too_old = true ]]; then
        echo "Current version is too old; updating..."
        if ! update_cmake_from_publisher; then
            return 1
        fi
    fi
}

build_magic_eightball() {
    # if cmake is new enough (>= 3.20.0), using the preset commands
    # will work. If cmake is older, we will have to figure out a workaround.
    print_cmake_info
    
    local _use_presets=false
    if is_cmake_preset_capable ${_cur_ver[@]}; then
        _use_presets=true
        echo "cmake is new enough to proceed with preset commands..."
    else
        echo "cmake is too old to proceed with preset commands; have to" \
             " revert to older behavior..."
    fi

    if [[ ${_use_presets} = true ]]; then
        echo "=== Available presets ==="
        cmake --list-presets
        echo "========================="

        cmake --debug-output --fresh --preset release && \
        cmake --debug-output --build --preset release
    else
        # the CMake Tools extension will print out the commands
        # that *should* work without the presets file. Let's just try those.

        # I have seen one particular cmake complain that the build directory doesn't exist.
        # The current version also complains about --target being and unknown command, but it
        # is clearly explained in the documentation.
        mkdir -p build/install

        # this is configure
        cmake --debug-output --fresh -DCMAKE_BUILD_TYPE=Release -DCMAKE_INSTALL_PREFIX=build/install \
              --check-system-vars -S$(pwd) -B$(pwd)/build -G Ninja && \
        # this is build
        cmake --debug-output --verbose  --build $(pwd)/build --clean-first --target magic-eightball 
    fi

    [[ -x "build/magic-eightball" ]] && build/magic-eightball -q "Do you think this will work?"
}

_args=($@)
case ${_args[0]} in
    #"update-cmake")
     #   update_cmake_if_required
      #  ;;
    "cmake-info")
        print_cmake_info
        ;;
    "build")
        build_magic_eightball
        ;;
    *)
        echo "Usage: $0 [cmake-info|build]"
        ;;
esac
