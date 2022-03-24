<?php
print('<div class="alert alert-success pt-3" role="alert">');
print('👍 Запись № '. mysqli_insert_id($link) . ' добавлена в таблицу.');
print('</div>');
