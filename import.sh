#!/bin/bash

path="c:/xampp/htdocs/planner5d/"
log="c:/xampp/htdocs/planner5d/_log/"

# ищем и удаляем файлы "$log"import.run созданные более 1 дня
find "$log"import.run -ctime +1 -exec rm -R {} \;
if ! [ -f "$log"import.run ]; then

    date >> "$log"import.run


    echo "START" >> "$log"import.result
    date >> "$log"import.result

    /usr/local/bin/php "$path"import.php >> "$log"import.result

    echo "END" >> "$log"import.result
    date >> "$log"import.result


    rm "$log"import.run

fi

