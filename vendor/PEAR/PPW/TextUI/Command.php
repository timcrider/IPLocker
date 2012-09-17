<?php
/**
 * PHP Project Wizard (PPW)
 *
 * Copyright (c) 2011, Sebastian Bergmann <sb@sebastian-bergmann.de>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Sebastian Bergmann nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @package   PPW
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright 2009-2011 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @since     File available since Release 1.0.0
 */

/**
 * TextUI frontend for PPW.
 *
 * @author    Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @copyright 2011 Sebastian Bergmann <sb@sebastian-bergmann.de>
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: 1.0.4
 * @link      http://github.com/sebastianbergmann/php-project-wizard/tree
 * @since     Class available since Release 1.0.0
 */
class PPW_TextUI_Command
{
    /**
     * Main method.
     */
    public static function main()
    {
        $input  = new ezcConsoleInput;
        $output = new ezcConsoleOutput;

        $input->registerOption(
          new ezcConsoleOption(
            '',
            'name',
            ezcConsoleInput::TYPE_STRING,
            NULL,
            FALSE,
            '',
            '',
            array(),
            array(),
            TRUE,
            TRUE
           )
        );

        $input->registerOption(
          new ezcConsoleOption(
            '',
            'source',
            ezcConsoleInput::TYPE_STRING,
            'src'
           )
        );

        $input->registerOption(
          new ezcConsoleOption(
            '',
            'tests',
            ezcConsoleInput::TYPE_STRING,
            'tests'
           )
        );

        $input->registerOption(
          new ezcConsoleOption(
            '',
            'bootstrap',
            ezcConsoleInput::TYPE_STRING
           )
        );

        $input->registerOption(
          new ezcConsoleOption(
            '',
            'phpcs',
            ezcConsoleInput::TYPE_STRING,
            'PEAR',
            FALSE
           )
        );

        $input->registerOption(
          new ezcConsoleOption(
            '',
            'phpmd',
            ezcConsoleInput::TYPE_STRING,
            'codesize,design,naming,unusedcode',
            FALSE
           )
        );

        $input->registerOption(
          new ezcConsoleOption(
            'h',
            'help',
            ezcConsoleInput::TYPE_NONE,
            NULL,
            FALSE,
            '',
            '',
            array(),
            array(),
            FALSE,
            FALSE,
            TRUE
           )
        );

        $input->registerOption(
          new ezcConsoleOption(
            'v',
            'version',
            ezcConsoleInput::TYPE_NONE,
            NULL,
            FALSE,
            '',
            '',
            array(),
            array(),
            FALSE,
            FALSE,
            TRUE
           )
        );

        try {
            $input->process();
        }

        catch (ezcConsoleOptionException $e) {
            self::showHelp();

            print "\n" . $e->getMessage() . "\n";

            exit(1);
        }

        if ($input->getOption('help')->value) {
            self::showHelp();
            exit(0);
        }

        else if ($input->getOption('version')->value) {
            self::printVersionString();
            exit(0);
        }

        $arguments = $input->getArguments();
        $name      = $input->getOption('name')->value;
        $source    = $input->getOption('source')->value;
        $tests     = $input->getOption('tests')->value;
        $bootstrap = $input->getOption('bootstrap')->value;
        $phpcs     = $input->getOption('phpcs')->value;
        $phpmd     = $input->getOption('phpmd')->value;

        if (!is_dir($source)) {
            mkdir($source, 0777, TRUE);
        }

        if (!is_dir($tests)) {
            mkdir($tests, 0777, TRUE);
        }

        if ($bootstrap) {
            $bootstrap = 'bootstrap="' . $bootstrap . '"' . "\n         ";
        } else {
            $bootstrap = '';
        }

        $templatePath = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR .
                        'Processor' . DIRECTORY_SEPARATOR .
                        'Template' . DIRECTORY_SEPARATOR;

        if (isset($arguments[0])) {
            $target = $arguments[0];
        }

        else if (isset($_ENV['PWD'])) {
            $target = $_ENV['PWD'];
        }

        else {
            self::showHelp();
            exit(1);
        }

        self::printVersionString();

        $generated = sprintf(
          'Generated by PHP Project Wizard (PPW) 1.0.4 on %s',
          date('D M j G:i:s T Y', $_SERVER['REQUEST_TIME'])
        );

        $template = new Text_Template($templatePath . 'build.xml');
        $_target  = $target . DIRECTORY_SEPARATOR . 'build.xml';

        $processor = new PPW_Processor_Ant($template, $_target);
        $processor->setGenerated($generated);
        $processor->setProjectName($name);
        $processor->setSourcesFolder($source);
        $processor->setPHPCSRules($phpcs);
        $processor->setPHPMDRules($phpmd);
        $processor->render();

        print "\nWrote build script for Apache Ant to " . $_target;

        $template = new Text_Template($templatePath . 'phpunit.xml');
        $_target  = $target . DIRECTORY_SEPARATOR . 'phpunit.xml.dist';

        $processor = new PPW_Processor_PHPUnit($template, $_target);
        $processor->setGenerated($generated);
        $processor->setProjectName($name);
        $processor->setSourcesFolder($source);
        $processor->setTestsFolder($tests);
        $processor->setBootstrapFile($bootstrap);
        $processor->render();

        print "\nWrote configuration for PHPUnit to " . $_target . "\n";
    }

    /**
     * Shows the help.
     */
    protected static function showHelp()
    {
        self::printVersionString();

        print <<<EOT
Usage: ppw [switches] <directory>

  Mandatory switches

    --name <name>         Name of the project.

  Optional switches

    --source <directory>  Directory with the project's sources (default: src)
    --tests <directory>   Directory with the project's tests (default: tests)

    --bootstrap <script>  Bootstrap script for testsuite.
    --phpcs <ruleset>     Ruleset for PHP_CodeSniffer (default: PEAR)
    --phpmd <ruleset,...> Ruleset(s) for PHPMD
                          (default: codesize,design,naming,unusedcode)

  --help                  Prints this usage information.
  --version               Prints the version and exits.

EOT;
    }

    /**
     * Prints the version string.
     */
    protected static function printVersionString()
    {
        print "PHP Project Wizard (PPW) 1.0.4 by Sebastian Bergmann.\n";
    }
}
