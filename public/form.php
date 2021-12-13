<?php

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  return $data;
}

$ok = test_input('<p>powaerse</p>./<div></div>../.');

echo $ok;