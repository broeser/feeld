#!/bin/sh

_script=$0
DIR="$(dirname $_script)"

phpdoc run --ansi --progressbar --directory $DIR/src --target $DIR/doc --title feeld
echo $DIR
