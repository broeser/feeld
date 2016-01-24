#!/bin/sh

_script=$0
DIR="$(dirname $_script)"

/home/benedict/.composer/vendor/bin/phpdoc run -vvv --ansi --progressbar --directory $DIR/src --target $DIR/doc --title feeld
echo $DIR
