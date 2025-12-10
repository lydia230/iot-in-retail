<?php

shell_exec("/usr/local/bin/gpio -g mode 27 out");
shell_exec("/usr/local/bin/gpio -g mode 18 out");
shell_exec("/usr/local/bin/gpio -g mode 17 out");

shell_exec("/usr/local/bin/gpio -g write 27 1");
shell_exec("/usr/local/bin/gpio -g write 18 1");
shell_exec("/usr/local/bin/gpio -g write 17 0");

sleep(10);

shell_exec("/usr/local/bin/gpio -g write 27 0");
shell_exec("/usr/local/bin/gpio -g write 18 0");
shell_exec("/usr/local/bin/gpio -g write 17 0");

?>

