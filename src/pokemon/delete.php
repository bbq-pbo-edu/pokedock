<?php

deleteById('pokemon', $id);

header('Location: /pokemon/read');
exit();