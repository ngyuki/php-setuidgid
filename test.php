<?php
file_put_contents("/tmp/hoge", "hoge");
echo `ls -l /tmp/hoge`;
