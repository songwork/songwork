#!/bin/sh
for i in test*.php ; do phpunit $i ; done
