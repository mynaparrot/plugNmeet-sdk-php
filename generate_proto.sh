#!/usr/bin/env sh

mkdir -p src/gen
rm -rf src/gen/*

cd protocol
./generate.sh
cd ../

protoc \
  --proto_path=./protocol/proto_files \
  --proto_path=./protocol/tmp/protovalidate/proto/protovalidate \
  --proto_path=./protocol/tmp/livekit-protocol/protobufs \
  --php_out=src/gen \
  ./protocol/proto_files/*.proto \
  ./protocol/tmp/livekit-protocol/protobufs/*.proto

rsync -a ./patch/* ./src/gen/
