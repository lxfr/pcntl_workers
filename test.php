<?php
// Количество параллельных форков
$parallelProcesses = 50;
$childProcesses = array();
// Создаем форки
for ($i = 0; $i < $parallelProcesses; $i++) {
    // Пробуем создать форк
    print "Родитель, создую потомка..." . PHP_EOL;
    $childPid = pcntl_fork();
    if ($childPid == -1) {
        throw new \Exception('Cant create child fork');
    } elseif ($childPid) {
        /*print "Это код родителя, обычно тут пусто" . PHP_EOL;*/
        $childProcesses[] = $childPid;
        // В данный момент все процессы форкнуты, ждем пока они все выполнятся
        if ($i == ( $parallelProcesses -1 ) ) {
            foreach ($childProcesses as $pid) {
                // Ждем завершение заданного дочернего процесса
                $status = 0;
                pcntl_waitpid($pid, $status);
            }
        }
    } else {
        // Это код форка
        $result = rand(1, 1000000);
        echo "Форк " . ($i+1) . " отработал: " . $result . PHP_EOL;
        // Потом форк дальше ничего не делает и нормально завершается
        exit(0);
    }
}
// Едем дальше
