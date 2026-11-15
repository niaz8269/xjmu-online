#!/bin/sh
# Usage: tools/snapshot.sh <outDir>
cd "$(dirname "$0")/.."
php tools/snapshot.php "$1" --urls | while read -r u; do php tools/snapshot.php "$1" "$u" 2>&1 | tail -1; done
