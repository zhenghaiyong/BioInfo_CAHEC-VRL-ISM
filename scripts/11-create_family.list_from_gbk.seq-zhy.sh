#!/bin/bash
if [ $# != 2 ]; then
    echo -ne "Create family list from GenBank files.\n"
    echo -ne "Usage:\n"
    echo -ne "\t$0 <directory path> <family name>\n"
    exit 7
fi

echo -ne "Creating $2.list from GenBank files in $1:\n"
grep -n -r $2 `ls -v $1/*.seq` > $2.list #2>&1
echo -ne "Done.\n"

echo
echo -ne "Line numbers of $2.list: `cat $2.list|wc -l`\n"
echo
