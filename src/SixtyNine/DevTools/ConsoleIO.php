<?php

namespace SixtyNine\DevTools;

use SixtyNine\DevTools\Model\Path;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleIO extends SymfonyStyle
{
    public function dumpFile(Path $path)
    {
        $this->writeln('<title>FILE</title>');
        foreach (explode(PHP_EOL, file_get_contents($path->getPath())) as $line) {
            $this->writeln(sprintf(' |  <code>%s</code>', $line));
        }

    }
}
