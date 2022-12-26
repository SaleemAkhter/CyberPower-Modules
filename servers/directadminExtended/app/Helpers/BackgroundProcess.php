<?php
namespace ModulesGarden\Servers\DirectAdminExtended\App\Helpers;

/**
 * BackgroundProcess
 *
 * Runs a process in the background.
 *
 * @package   BraincraftedBackgroundProcess
 * @author    Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright 2013 Florian Eckerstorfer
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @link      http://braincrafted.com/php-background-processes/ Running background processes in PHP
 */
class BackgroundProcess
{
    /** @var string */
    private $command;

    /** @var integer */
    private $pid;

    /** @var string */
    private $outputFile;

    /** @var string */
    private $timestamp;

    /**
     * Constructor.
     *
     * @param string $command The command to execute
     */
    public function __construct($command)
    {
        $this->command = escapeshellcmd($command);
    }

    /**
     * Runs the command in a background process.
     *
     * @param string $outputFile File to write the output of the process to; defaults to /dev/null
     *
     * @return void
     */
    public function run($outputFile = '/dev/null', $append = false,$timestamp=false)
    {
        $this->pid = (int)shell_exec(sprintf('%s %s %s 2>&1 & echo $!', $this->command, ($append) ? '>>' : '>', $outputFile));
        // $this->outputFile = strstr(
        //     trim(
        //         shell_exec(
        //             'curl http://admin:l5qC-4KHQsMtRCyo@193.31.29.55:2222/CMD_PLUGINS_ADMIN/custombuild/build_software_command_sse.raw?command=php_expert%207.4%20php-fpm &'
        //         )
        //     ),
        //     ' ',
        //     true
        // );
        // $numofdots = substr_count($this->outputFile, '.'); // must be 2
        if (!$this->pid>0) {
            die('Background process has not been started!'.$this->outputFile);
        }
        // $getpid = explode(".", $this->outputFile);
        // $this->pid = $getpid[1];
        $this->timestamp = ($timestamp)??time();
    }

    /**
     * Returns if the process is currently running.
     *
     * @return boolean TRUE if the process is running, FALSE if not.
     */
    public function isRunning()
    {
        return (bool)posix_getpgid($this->pid);
    }

    /**
     * Returns the ID of the process.
     *
     * @return integer The ID of the process
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param $pid
     */
    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    public function getOutputFile()
    {
        return $this->outputFile;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }
    public function setTimestamp($timestamp)
    {
        return $this->timestamp=$timestamp;
    }
}

?>
