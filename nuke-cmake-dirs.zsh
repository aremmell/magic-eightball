#!/bin/zsh

typeset -a to_nuke=(
    "./build"
)

echo "Have ${#to_nuke} directories/files to nuke..."

for n in "${to_nuke[@]}"; do
    if ! rm -rf "${n}"; then
        echo "ERROR: Failed to nuke ${n}!"
    else
        echo "Nuked ${n}"
    fi
done

echo "Bye."
