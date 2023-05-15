#!/bin/bash

# $1 = the path to cmake
# $2 = the name of a variable to receive
# an array containing the version segments
get_cmake_version() {
    if [[ ! -x ${1} ]] || [[ -z ${2} ]]; then
        return 1
    fi

    local _ver_output=$("${1}" --version)
    if [[ $_ver_output =~ [^\d]+([0-9]+)\.([0-9]+)\.([0-9]+) ]]; then
        local _major=${BASH_REMATCH[1]}
        local _minor=${BASH_REMATCH[2]}
        local _patch=${BASH_REMATCH[3]}

        eval $2'=($_major $_minor $_patch)'
        return 0
    fi

    return 1
}

# $1 = left hand side number
# $2 = right hand side number
# $3 = name of a variable to receive the return value
#
# returns:
# -1 if left > right
#  0 if left == right
#  1 if left < right
numeric_compare() {
    local _ret=0
    if [[ ${1} -gt ${2} ]]; then
        _ret=-1
    elif [[ ${1} -lt ${2} ]]; then
        _ret=1
    fi

    eval $3'=$_ret'
}

# $1 = version number array from get_cmake_version.
is_cmake_preset_capable() {
    # this is the minimum CMake version mapped to the preset JSON schema version 6.
    local _min_major=3
    local _min_minor=25
    local _min_patch=0

    if [[ ${1} -lt $_min_major ]] || [[ ${2} -lt $_min_minor ]] || \
       [[ ${3} -lt $_min_patch ]]; then
        return 1
    fi

    return 0
}

is_cmake_snap_installed() {
    [[ -x /snap/bin/cmake ]]
}

# $1 = version number array from get_cmake_version.
print_cmake_version() {
    echo "${1}.${2}.${3}"
}

# $1 = path to cmake
_print_cmake_info() {
    if [[ ! -x ${1} ]]; then
        return 1
    fi

    _cur_ver=()
    get_cmake_version "${1}" _cur_ver
    
    echo -n "cmake = ${1}, and its version is $(print_cmake_version ${_cur_ver[@]})"

    if is_cmake_preset_capable "${_cur_ver[@]}"; then
        echo " (preset capable)"
    else
        echo " (NOT preset capable)"
    fi
}

print_cmake_info() {
    _print_cmake_info "$(which cmake)"

    if is_cmake_snap_installed; then
        _print_cmake_info "/snap/bin/cmake"
    fi
}

# $1 = the name of a variable that will receive
# the path of the cmake binary to use.
# $2 = the name of a variable that will receive
# the version number of the cmake binary chosen.
select_cmake_binary() {
    if [[ -z ${1} ]] || [[ -z ${2} ]]; then
        return 1
    fi

    local _binary="$(which cmake)"
    local _snap_wins=false
    local _ver=()
    
    _ver1=()
    get_cmake_version "${_binary}" _ver1    

    if is_cmake_snap_installed; then
        # compare versions; select the higher one.
        _ver2=()
        get_cmake_version "/snap/bin/cmake" _ver2

        _majors=0
        numeric_compare ${_ver1[0]} ${_ver2[0]} _majors

        _minors=0
        numeric_compare ${_ver1[1]} ${_ver2[1]} _minors

        _patches=0
        numeric_compare ${_ver1[2]} ${_ver2[2]} _patches

        if [[ ${_majors} -gt 0 ]] || [[ ${_minors} -gt 0 ]] || [[ ${_patches} -gt 0 ]]; then
            _snap_wins=true
        fi
    fi

    if [[ ${_snap_wins} = true ]]; then
        _binary="/snap/bin/cmake"
        _ver=("${_ver2[@]}")
    else
        _ver=("${_ver1[@]}")
    fi

    echo "Using cmake = ${_binary}"
    eval $1'=${_binary}'
    eval $2'=(${_ver[@]})'
}

build_magic_eightball() {
    # if cmake is new enough (>= 3.20.0), using the preset commands
    # will work. If cmake is older, we will have to figure out a workaround.

    # we could have more than one instance of cmake sitting around. in particular,
    # a snap–check 'which cmake' and check for a snap version. select whichever
    # has the higher version.
    print_cmake_info

    _cmake="$(which cmake)"
    _cmake_ver=()
    select_cmake_binary _cmake _cmake_ver

    local _use_presets=false
    if is_cmake_preset_capable ${_cmake_ver[@]}; then
        _use_presets=true
        echo "cmake is new enough to proceed with preset commands..."
    else
        echo "cmake is too old to proceed with preset commands; have to" \
             " revert to older behavior..."
    fi

    if [[ ${_use_presets} = true ]]; then
        echo "========================="
        ${_cmake} --list-presets
        echo "========================="

        ${_cmake} --preset release && \
        ${_cmake} --build $(pwd)/build --preset release
    else
        # the CMake Tools extension will print out the commands
        # that *should* work without the presets file. Let's just try those.

        # I have seen one particular cmake complain that the build directory doesn't exist.
        # The current version also complains about --target being and unknown command, but it
        # is clearly explained in the documentation.
        mkdir -p build/install

        # this is clean, configure, then build
        ${_cmake} --build $(pwd)/build --clean-first --verbose --target clean && \
        ${_cmake} -DCMAKE_BUILD_TYPE=Release -DCMAKE_INSTALL_PREFIX=build/install \
                  --check-system-vars -S$(pwd) -B$(pwd)/build -G Ninja && \
        ${_cmake} --build $(pwd)/build --clean-first --verbose --target magic-eightball 
    fi

    [[ -x "build/magic-eightball" ]] && build/magic-eightball -q "Do you think this will work?"
}

_args=($@)
case ${_args[0]} in
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
