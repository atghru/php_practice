#!/bin/bash

firstname=$(shuf -n1  /usr/share/dict/american-english)
# firstname="Bella's"
firstname=${firstname//\'/}
firstname=$(perl -ne 'print ucfirst' <<<"$firstname")
lastname=$(perl -ne 'print ucfirst' <<<$(shuf -n1  /usr/share/dict/american-english))
lastname=${lastname//\'/}
mail=${firstname,,}@example.com
password=$(shuf -n1  /usr/share/dict/american-english)
if [ "$RANDOM" -gt 15000 ]; then gender="male"; else gender="female"; fi
if [ "$RANDOM" -gt 150 ]; then userlisting="disabled"; else userlisting="enabled"; fi
form_textarea=$(shuf -n10 /usr/share/dict/american-english)

curl --silent \
    --output /dev/null \
    -F firstname="$firstname" \
    -F lastname="$lastname" \
    -F email="$mail" \
    -F password="$password" \
    -F gender=$gender \
    -F userlisting=$userlisting \
    -F form_textarea="$form_textarea"  \
    -F input_file=@shiba_dog.png \
    --url http://localhost/index.php?mode=add
