#!/usr/bin/env sh

composer install

composer development-enable

rr serve -d -c .rr.yaml
