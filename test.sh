#!/bin/sh

if [$1 -eq ''] 
then 
    echo "You need to specify TELEPHONY_REMOTE_SERVER as first argument"
    exit;
fi

TELEPHONY_REMOTE_SERVER=$1 composer run test