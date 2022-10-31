<?php
/**
 * Author: Tony Pham (xphamt00)
 * Version: 1.0
*/

/**
 * Třída arg_check slouží k uchování informací o tom jak budou testy provedeny.
*/
class arg_check {
    public $directory = ".";
    public $recursive = false;
    public $parse_script = "parse.php";
    public $int_script = "interpret.py";
    public $parse_only = false;
    public $int_only = false;
    public $jexamxml = '/pub/courses/ipp/jexamxml/jexamxml.jar';
    public $noclean = false;

    /**
     * Funkce zkontroluje jestli k souboru je validní.
     */
    private function check_if_file_exists($path): void {
        if (!file_exists($path)) {
            fwrite(STDERR, "Soubor neexistuje.\n");
            exit(41);
        }
    }

    /**
     * Zbylé funkce ve třídě slouží k nastavení atributů třídy arg_check.
     */
    public function set_directory($value): void {
        $this->check_if_file_exists($value);
        $this->directory = $value;
    }

    public function set_parse_script($value): void {
        $value = realpath($value);
        $this->check_if_file_exists($value);
        $this->parse_script = $value;
    }

    public function set_int_script($value): void {
        $value = realpath($value);
        $this->check_if_file_exists($value);
        $this->int_script = $value;
    }

    public function set_jexamxml_path($value): void {
        $value = realpath($value);
        $this->check_if_file_exists($value);
        $this->jexamxml = $value;
    }

}

/**
 * Hlavní funkce pro kontrolu vstupních argumentů
 */
function check_line_arg($argc, $argv) {
    $args = new arg_check();
    if ($argc >= 7) {
        fwrite(STDERR, "Prilis mnoho parametru (nebo nejsou kompatibilni).\n");
        exit(10);
    }
    for ($i=1; $i < $argc; $i++) {
        if ($argv[$i] == "--help") {
            if($argc > 2) {
                fwrite(STDERR, "Help nelze kombinovat s ostatními parametry.\n");
                exit(11);
            } else {
                print ("Skript (test.php v jazyce PHP 8.1) bude sloužit pro automatické testování (postupné) aplikace
                        parse.php a interpret.py. Skript projde zadaný adresář s testy a využije je pro automatické
                        otestování správné funkčnosti jednoho či obou předchozích skriptů včetně vygenerování přehledného
                        souhrnu v HTML 5 na standardní výstup.");
            }
        } elseif (str_contains($argv[$i], "--directory=")) {
            $check = explode('=', $argv[$i], 2);
            $args->set_directory($check[1]);
        } elseif ($argv[$i] == "--recursive") {
            $args->recursive = true;
        } elseif ($argv[$i] == "--parse-only") {
            $args->parse_only = true;
        } elseif ($argv[$i] == "--int-only") {
            $args->int_only = true;
        } elseif ($argv[$i] == "--noclean") {
            $args->noclean = true;
        } elseif (str_contains($argv[$i], "--int-script=")) {
            $check = explode('=', $argv[$i], 2);
            $args->set_int_script($check[1]);
        } elseif (str_contains($argv[$i], "--parse-script=")) {
            $check = explode('=', $argv[$i], 2);
            $args->set_parse_script($check[1]);
        } elseif (str_contains($argv[$i], "--jexampath=")) {
            $check = explode('=', $argv[$i], 2);
            $args->set_jexamxml_path($check[1]);
        }
    }
    if ($args->parse_only == "true" && $args->int_only == "true") {
        fwrite(STDERR, "int-only a parse-only nelze kombinovat.\n");
        exit(10);
    }
    return $args;
}

/**
 * Třída test_load slouží k uchování informací o složkách s testy.
 * $folders obsahuje všechny složky, které byli prohledány
 * $files obsahuje jména všech testů
 */
class test_load {
    public $folders;
    public $files;

    public function __construct() {
        $this->folders = [];
        $this->files = [];
    }

    /**
     * Funkce find_tests je hlavní funkce třídy test_load.
     * Ukolem funkce je naplnit atributy třídy validními daty a rozhoduje se jestli bude hledání
     * testů prováděno rekurzivně nebo ne.
     */
    public function find_tests($folder, $is_recursive): void {

        $Directory = new RecursiveDirectoryIterator($folder);
        if ($is_recursive) {
            $Iterator = new RecursiveIteratorIterator($Directory);
        }
        else {
            $Iterator = new IteratorIterator($Directory);
        }

        $Regex = new RegexIterator($Iterator, '/^.+\.src$/i', RegexIterator::GET_MATCH);

        foreach ($Regex as $file) {
            $name = preg_replace('/^(.*\/)?(.+)\.src$/','\2', $file[0]);
            $folder = preg_replace('/^(.*\/).+\.(in|out|rc|src)$/','\1', $file[0]);
            $this->files[$folder][$name]['name'] = $name;
            if (!in_array($folder, $this->folders)) {
                $this->folders[] = $folder;
            }

            if (!file_exists($folder.$name.'.rc')) {
                file_put_contents($folder.$name.'.rc', "0");
            }
            if (!file_exists($folder.$name.'.in')) {
                file_put_contents($folder.$name.'.in', "");
            }
            if (!file_exists($folder.$name.'.out')) {
                file_put_contents($folder.$name.'.out', "");
            }
        }
        sort($this->folders);
        array_multisort($this->files);
    }

    /**
     * Funkce save_test_output uloží výsledky testů
     */
    public function save_test_output($test_folder, $test_name, $parser, $interpret, $completed): void {
        $this->files[$test_folder][$test_name]['parser'] = $parser;
        $this->files[$test_folder][$test_name]['interpret'] = $interpret;
        $this->files[$test_folder][$test_name]['completed'] = $completed;
    }
}
/**
 * Třída HTML_gen obsahuje funkce pro generováni HTML výstupu
 */
class HTML_gen {
    public $tests;
    public $total;
    public $failed;

    public function __construct($tests, $number_of_tests, $failed) {
        $this->tests = $tests;
        $this->total = $number_of_tests;
        $this->failed = $failed;
    }
    /**
     * Funkce vygeneruje základnní rozložení HTML stránky
     */
    private function html_layout(): string {
        $html = '<!DOCTYPE html>
        <html lang="cz">
        <head>
            <meta charset="utf-8">
            <title>IPPcode22 Testy</title>
            <meta name="Testování scriptů parse.php a interpret.py">
            <style>
            html {
                font-family: Arial, Helvetica, sans-serif;
            }
            h1 {
                text-align: center;
                color: grey;
                font-size: 75px;
            }
            .PASSED {
                color: green;
            }
            .FAILED {
                color: red;
            }
            p {
                text-align: center;
                font-size: 50px;
            }
            td, th {
                width: 125px;
                text-align: center;
                padding: 50px;
            }
            table, th, td {
                border: 1px solid grey;
                border-collapse: collapse;
                font-size: 20px;
            }
            #tests {
                padding-top: 20px;
            }
            button {
                padding: 5px;
            }
            
            </style>
        </head>
        <body>
            <div id="main">
                <h1>Výsledky testů<h1>
            </div>
            <div id="summary">
            <p>Počet provedených testů: ';
        $html = $html . $this->total . ' </p>';
        $html = $html . '<p class="PASSED">Počet úspěšných testů: ';
        $html = $html . ($this->total - $this->failed) . ' </p>';
        $html = $html . '<p class="FAILED">Počet neúspěšných testů: ';
        $html = $html . $this->failed . ' </p>';
        $html = $html . '<p class="PASSED">Úspěšnost: ';
        if ($this->total != 0) {
            $html = $html . number_format((($this->total - $this->failed) / $this->total * 100), 2, '.', '');
        }
        $html = $html . '% </p>';

        $html = $html . '</div>
        <div id="tests">
        <table>
        <thead>
            <tr>
                <th>Číslo testu</th>
                <th>Soubor</th>
                <th>parse.php</th>
                <th>interpret.py</th>
                <th>Očekáváno</th>
                <th>Výsledek</th>
            </tr>
        </thead>
        <tbody>';
        return $html;
    }

    /**
     * Funkce naplní tabulky s testy v HTML výstupu
     */
    private function fill_html($html): string {
        $test_counter = 0;
        foreach ($this->tests->folders as $folder) {
            foreach ($this->tests->files[$folder] as $file) {
                $test_counter++;
                if ($file['completed']) {
                    $html = $html . '<tr class="completed">';
                } else {
                    $html = $html . '<tr>';
                }
                $html = $html . '<td>' . $test_counter . '</td>';
                $html = $html . '<td>' . $file['name'] . '.src' . '</td>';
                $html = $html . '<td>' . $file['parser'] . '</td>';
                $html = $html . '<td>' . $file['interpret'] . '</td>';
                $html = $html . '<td>' . file_get_contents($folder . $file['name'] . '.rc') . '</td>';

                if ($file['completed']) {
                    $html = $html . '<td class="PASSED">' . 'PASSED' . '</td>';
                } else {
                    $html = $html . '<td class="FAILED">' . 'FAILED' . '</td>';
                }
                $html = $html . '</tr>';
            }
        }
        return $html . '</tbody></table></div></body></html>';
    }

    /**
     * Funkce vygeneruje a naplní výstupní HTML soubor a vypíše ho na stdout
     */
    public function generate_html(): void {
        $html = $this->html_layout();
        $html = $this->fill_html($html);
        print ($html);
    }
}
/**
 * Funkce main obsahuje všechny víše zmíňěné funkce a vypíše HTML výstup na stdout
 */
function main($argc, $argv): void {
    $args = check_line_arg($argc, $argv);
    $tests = new test_load();
    $tests->find_tests($args->directory, $args->recursive);
    $number_of_tests = 0;
    $failed = 0;
    //Hlavní cyklus pro spouštění testů
    foreach ($tests->folders as $folder) {
        foreach ($tests->files[$folder] as $file) {
            $number_of_tests++;

            $source = $folder . $file['name'] . '.src';
            $input = $folder . $file['name'] . '.in';
            $return = $folder . $file['name'] . '.rc';
            $output = $folder . $file['name'] . '.out';

            //testy pouze pro interpret
            if ($args->int_only) {
                unset($int_output);
                unset($int_return_value);
                exec("python3.8 " . $args->int_script . " --source=" . $source . " --input=" . $input . " > " . $folder . $file['name'] . ".int_out_tmp" . " 2> " . $folder . $file['name']. ".int_err_tmp", $int_output, $int_return_value);
                if (($int_return_value == 0) && ($int_return_value == file_get_contents($return))) {
                    //porovnávání výstupu testů
                    unset($diff_retval);
                    exec("python3.8 " . $args->int_script . " --source=" . $source . " --input=" . $input . " 2>/dev/null | diff " . $output . " -", $diff_out, $diff_retval);
                    if ($diff_retval == 0) {
                        $tests->save_test_output($folder, $file['name'], '', $int_return_value, true);
                    } else {
                        $tests->save_test_output($folder, $file['name'], '', $int_return_value, false);
                        $failed++;
                    }
                } elseif (($int_return_value != 0) && ($int_return_value == file_get_contents($return))) {
                    //pokud selže
                    $tests->save_test_output($folder, $file['name'], '', $int_return_value, true);
                } else {
                    //ještě nedokončený testy
                    $tests->save_test_output($folder, $file['name'], '', $int_return_value, false);
                    $failed++;
                }
            } else {

                unset($parse_out);
                unset($parse_retval);
                exec("php8.1 " . $args->parse_script . " < " . $source . " > " . $folder . $file['name'] . ".int_out_tmp" . " 2> " . $folder . $file['name']. ".int_err_tmp", $parse_out, $parse_retval);

                if ($parse_retval == 0 && !$args->parse_only) {
                    unset($int_output);
                    unset($int_return_value);
                    exec("php8.1 " . $args->parse_script . " < " . $source . " 2>/dev/null | python3.8 " . $args->int_script . " --input=" . $input, $int_output, $int_return_value);

                    if (($int_return_value == 0) && ($int_return_value == file_get_contents($return))) {
                        //porovnávání výstupu testů
                        unset($diff_retval);
                        exec("php8.1 " . $args->parse_script . " < " . $source . " 2>/dev/null | python3.8 " . $args->int_script . " --input=" . $input . " 2>/dev/null | diff " . $output . " -", $diff_out, $diff_retval);
                        if ($diff_retval == 0) {
                            $tests->save_test_output($folder, $file['name'], $parse_retval, $int_return_value, true);
                        } else {
                            $tests->save_test_output($folder, $file['name'], $parse_retval, $int_return_value, false);
                            $failed++;
                        }
                    } elseif (($int_return_value != 0) && ($int_return_value == file_get_contents($return))) {
                        //pokud selže
                        $tests->save_test_output($folder, $file['name'], $parse_retval, $int_return_value, true);
                    } else {
                        //ještě nedokončený testy
                        $tests->save_test_output($folder, $file['name'], $parse_retval, $int_return_value, false);
                        $failed++;
                    }
                } else {
                    if ($parse_retval == file_get_contents($return)) {
                        if ($args->parse_only) {
                            $file_tmp = tmpfile();
                            fwrite($file_tmp, implode("\n", $parse_out));
                            if (count($parse_out) > 0)
                                fwrite($file_tmp, "\n");
                            unset($xml_retval);
                            exec("java -jar " . $args->jexamxml . " " . $file_tmp . $output . " /dev/null", $xmloutput, $xml_retval);
                            if ($xmloutput == 0) {
                                $tests->save_test_output($folder, $file['name'], $parse_retval, '', true);
                            } else {
                                $tests->save_test_output($folder, $file['name'], $parse_retval, '', false);
                            }
                            fclose($file_tmp);
                        } else {
                            $tests->save_test_output($folder, $file['name'], $parse_retval, '', true);
                        }
                    } else {
                        $tests->save_test_output($folder, $file['name'], $parse_retval, '', false);
                        $failed++;
                    }

                }
            }
            if ($args->noclean == false) {
                unlink($folder . $file['name'] . ".int_out_tmp");
                unlink($folder . $file['name'] . ".int_err_tmp");
            }
        }
    }

    $HTML_OIU = new HTML_gen($tests, $number_of_tests, $failed);
    $HTML_OIU->generate_html();
}

main($argc, $argv);