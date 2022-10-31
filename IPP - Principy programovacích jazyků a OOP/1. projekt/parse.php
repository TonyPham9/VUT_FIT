<?php
const ERR_PASSED = 0;
const ERR_BAD_PARAMS = 10;
const ERR_BAD_HEAD = 21;
const ERR_BAD_CODE = 22;
const ERR_CODE_OTHER = 23;

$stderr = fopen('php://stderr', 'w');
$stdout = fopen('php://stdout', 'w');
$stdin = fopen('php://stdin', 'r');
function symb_check($split_arr,$number) {
    global $stdout;
    $type = type_check($split_arr[$number]);
    $check = explode('@', $split_arr[$number], 2);
    if($check[0] == "string") {
        $check[1] = str_replace("&", "&amp;", $check[1]);
        $check[1] = str_replace("<", "&lt;", $check[1]);
        $check[1] = str_replace(">", "&gt;", $check[1]);
        fwrite($stdout, "  <arg$number type=\"$check[0]\">$check[1]</arg$number>\n");
    } else if($type == "var"){
        $check[1] = str_replace("&", "&amp;", $check[1]);
        $check[1] = str_replace("<", "&lt;", $check[1]);
        $check[1] = str_replace(">", "&gt;", $check[1]);
        fwrite($stdout, "  <arg$number type=\"$type\">$check[0]@$check[1]</arg$number>\n");
    } else {
        fwrite($stdout, "  <arg$number type=\"$check[0]\">$check[1]</arg$number>\n");
    }
}
function type_check($string) {
    global $stderr;
    //kontrola symb
    if (preg_match('~^int@[+-]?[0-9]+$~', $string) ||
        preg_match('~^nil@nil$~', $string) ||
        preg_match('~^bool@(true|false)$~', $string) ||
        preg_match('~^string@$~', $string) ||
        (preg_match('~^string@~', $string) &&
            !preg_match('~(\\\\($|\p{S}|\p{P}\p{Z}|\p{M}|\p{L}|\p{C})|(\\\\[0-9]{0,2}($|\p{S}|\p{P}\p{Z}|\p{M}|\p{L}|\p{C}))| |#)~', $string))
    ) {
        return "symb";
        //kontrola type
    } else if (preg_match('~^(int|bool|string)$~', $string)) {
        return "type";
        //kontrola var
    } else if (preg_match('~^(GF|TF|LF)@([a-zA-Z_\-$&%*!?])([a-zA-Z0-9_\-$&%*!?])*[0-9]*$~', $string)) {
        return "var";
    } else {
        fwrite($stderr, "Konstanta $string ma spatny typ.\n");
        exit(ERR_CODE_OTHER);
    }

}


function arg_check($argc, $argv) {
    global $stderr;

    for ($i=1; $i < $argc; $i++) {
        if ($argv[$i] == "--help") {
            if ($argc > 2) {
                fwrite($stderr, "Nelze zadat vice argumentu spolecne s \"--help\".\n");
                exit(ERR_BAD_PARAMS);
            }
            echo "Skript typu filtr (parse.php v jazyce PHP 8.1)\n";
            echo "nacte ze standardniho vstupu zdrojovy kod v IPPcode22\n";
            echo "zkontroluje lexikalni a syntaktickou spravnost kodu\n";
            echo "a vypise na standardni vystup XML reprezentaci programu.\n";
            exit(ERR_PASSED);
        }
    }
}

function parse() {
    global $stdin;
    global $stderr;
    global $stdout;
    $order = 1;
    $head = 0;
    while (feof($stdin) == false) {

        $line = fgets($stdin);

        //odstraním přebytečné mezery a tabulátory
        $line = trim(preg_replace('/\t+/', ' ', $line));
        $line = trim(preg_replace('/\s+/', ' ', $line));
        //odstraním komentáře
        if (strpos($line, "#") !== false) {
            $pieces = explode('#', $line);
            $pieces[0] = rtrim($pieces[0]);
            $line = $pieces[0] . PHP_EOL;
        }
        $line = rtrim($line, PHP_EOL);
        $line = ltrim($line);
        $line = rtrim($line);
        if ($head == 0) {
            if($line == "") {
                continue;
            }
            if($line == ".IPPcode22") {
                $head = 1;
                fwrite($stdout, "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n");
                fwrite($stdout, "<program language=\"IPPcode22\">\n");
                continue;
            } else {
                fwrite($stderr, "Hlavička je zadána špatně.\n");
                exit(ERR_BAD_HEAD);
            }
        }

        //počítání mezer -> slova
        $num_of_words = substr_count($line, ' ');
        //oddělení slov a varů
        $split_arr = explode(' ', $line);
        $split_arr[0] = strtoupper($split_arr[0]);
        switch ($split_arr[0]) {
            case "":
                break;

            case "CREATEFRAME": case "PUSHFRAME":case "POPFRAME":case "RETURN":case "BREAK":

                if ($num_of_words == 0) {
                    fwrite($stdout, " <instruction order=\"$order\" opcode=\"$split_arr[0]\">\n");
                    fwrite($stdout, " </instruction>\n");
                    $order++;
                    break;
                }else{
                    fwrite($stderr, "Instrukce má špatný počet argumentů.\n");
                    exit(ERR_CODE_OTHER);
                }

            case "DEFVAR":case "CALL":case "PUSHS":case "POPS":case "WRITE":case "LABEL":case "JUMP":case "EXIT": case "DPRINT":
                if ($num_of_words == 1) {
                    if($split_arr[0] == "LABEL" || $split_arr[0] == "JUMP" || $split_arr[0] == "CALL") {
                        if (preg_match('~^[a-zA-Z_\-$&%*!?][a-zA-Z0-9_\-$&%*!?]*$~', $split_arr[1])) {
                            fwrite($stdout, " <instruction order=\"$order\" opcode=\"$split_arr[0]\">\n");
                            fwrite($stdout, "  <arg1 type=\"label\">$split_arr[1]</arg1>\n");
                            fwrite($stdout, " </instruction>\n");
                            $order++;
                            break;
                        } else {
                            fwrite($stderr, "Instrukce má špatný typ argumentů.\n");
                            exit(ERR_CODE_OTHER);
                        }
                    }

                        fwrite($stdout, " <instruction order=\"$order\" opcode=\"$split_arr[0]\">\n");
                        $type = type_check($split_arr[1]);
                    if($split_arr[0] == "DEFVAR" || $split_arr[0] == "POPS") {
                        if ($type != "var") {
                            fwrite($stderr, "Instrukce má špatný typ argumentů.\n");
                            exit(ERR_CODE_OTHER);
                        }
                    }
                    if($split_arr[0] == "PUSHS" || $split_arr[0] == "WRITE" || $split_arr[0] == "EXIT" || $split_arr[0] == "DPRINT") {
                        if ($type != "symb" && $type != "var") {
                            fwrite($stderr, "Instrukce má špatný typ argumentů.\n");
                            exit(ERR_CODE_OTHER);
                        }
                    }
                        if ($type == "symb" || $type == "var") {
                            symb_check($split_arr,1);
                            fwrite($stdout, " </instruction>\n");
                            $order++;
                            break;
                        } else {
                            fwrite($stdout, "  <arg1 type=\"$type\">$split_arr[1]</arg1>\n");
                            fwrite($stdout, " </instruction>\n");
                            $order++;
                            break;
                        }


                }else{
                    fwrite($stderr, "Instrukce má špatný počet argumentů.\n");
                    exit(ERR_CODE_OTHER);
                }

            case "MOVE":case "INT2CHAR":case "READ":case "STRLEN":case "TYPE": case "NOT":
                if ($num_of_words == 2) {

                   $type = type_check($split_arr[1]);
                    if($split_arr[0] == "MOVE" || $split_arr[0] == "INT2CHAR" || $split_arr[0] == "STRLEN" || $split_arr[0] == "TYPE" || $split_arr[0] == "NOT") {
                        if ($type != "var") {
                            fwrite($stderr, "Instrukce má špatný typ argumentů.\n");
                            exit(ERR_CODE_OTHER);
                        }
                        $type = type_check($split_arr[2]);
                        if ($type != "symb" && $type != "var") {
                            fwrite($stderr, "Instrukce má špatný typ argumentů.\n");
                            exit(ERR_CODE_OTHER);
                        }
                    }
                    $type = type_check($split_arr[1]);
                    if($split_arr[0] == "READ" ) {
                        if ($type != "var") {
                            fwrite($stderr, "Instrukce má špatný typ argumentů.\n");
                            exit(ERR_CODE_OTHER);
                        }
                        $type = type_check($split_arr[2]);
                        if ($type != "type") {
                            fwrite($stderr, "Instrukce má špatný typ argumentů.\n");
                            exit(ERR_CODE_OTHER);
                        }
                    }
                    $type = type_check($split_arr[1]);
                    fwrite($stdout, " <instruction order=\"$order\" opcode=\"$split_arr[0]\">\n");
                    if ($type == "symb" || $type == "var") {
                        symb_check($split_arr,1);

                    } else {
                        fwrite($stdout, "  <arg1 type=\"$type\">$split_arr[1]</arg1>\n");
                    }
                    $type = type_check($split_arr[2]);
                    if ($type == "symb" || $type == "var") {
                        symb_check($split_arr,2);
                        fwrite($stdout, " </instruction>\n");
                        $order++;
                        break;
                    } else {
                        fwrite($stdout, "  <arg2 type=\"$type\">$split_arr[2]</arg2>\n");
                        fwrite($stdout, " </instruction>\n");
                        $order++;
                        break;
                    }

                }else{
                    fwrite($stderr, "Instrukce má špatný počet argumentů.\n");
                    exit(ERR_CODE_OTHER);
                }

            case "ADD":case "SUB":case "MUL":case "IDIV":case "LT":case "GT":case "EQ":case "AND":case "OR":case "STRI2INT":case "CONCAT":case "GETCHAR":case "SETCHAR":case "JUMPIFEQ": case "JUMPIFNEQ":
                if ($num_of_words == 3) {
                    if($split_arr[0] == "JUMPIFEQ" || $split_arr[0] == "JUMPIFNEQ") {
                        $type1 = type_check($split_arr[2]);
                        $type2 = type_check($split_arr[3]);
                        if (($type1 == $type2) || (($type1 == "var") && ($type2 == "symb")) ||
                            (($type1 == "symb") && ($type2 == "var"))) {

                        if (preg_match('~^[a-zA-Z_\-$&%*!?][a-zA-Z0-9_\-$&%*!?]*$~', $split_arr[1])) {
                            fwrite($stdout, " <instruction order=\"$order\" opcode=\"$split_arr[0]\">\n");
                            fwrite($stdout, "  <arg1 type=\"label\">$split_arr[1]</arg1>\n");

                            $type = type_check($split_arr[2]);
                            if ($type == "symb" || $type == "var") {
                                symb_check($split_arr, 2);
                            } else {
                                fwrite($stdout, "  <arg2 type=\"$type\">$split_arr[2]</arg2>\n");
                            }
                            $type = type_check($split_arr[3]);
                            if ($type == "symb" || $type == "var") {
                                symb_check($split_arr, 3);
                                fwrite($stdout, " </instruction>\n");
                                $order++;
                                break;
                            } else {
                                fwrite($stdout, "  <arg3 type=\"$type\">$split_arr[3]</arg3>\n");
                                fwrite($stdout, " </instruction>\n");
                                $order++;
                                break;
                            }
                        } else {
                            fwrite($stderr, "Konstanta ma spatny typ.\n");
                            exit(ERR_CODE_OTHER);
                        }
                    } else {
                            fwrite($stderr, "Konstanta ma spatny typ.\n");
                            exit(ERR_CODE_OTHER);
                        }
                    }
                    fwrite($stdout, " <instruction order=\"$order\" opcode=\"$split_arr[0]\">\n");
                    $type = type_check($split_arr[1]);
                    if($split_arr[0] == "ADD" || "SUB" || "MUL" || "IDIV") {
                        if($type != "var") {
                            fwrite($stderr, "Konstanta ma spatny typ.\n");
                            exit(ERR_CODE_OTHER);
                        }

                    }
                    if ($type == "symb" || $type == "var") {
                        symb_check($split_arr,1);

                    } else {
                        fwrite($stdout, "  <arg1 type=\"$type\">$split_arr[1]</arg1>\n");
                    }


                    $type = type_check($split_arr[2]);
                    if ($type == "symb" || $type == "var") {
                        symb_check($split_arr,2);
                    } else {
                        fwrite($stdout, "  <arg2 type=\"$type\">$split_arr[2]</arg2>\n");
                    }

                    $type = type_check($split_arr[3]);
                    if ($type == "symb" || $type == "var") {
                        symb_check($split_arr,3);
                        fwrite($stdout, " </instruction>\n");
                        $order++;
                        break;
                    } else {
                        fwrite($stdout, "  <arg3 type=\"$type\">$split_arr[3]</arg3>\n");
                        fwrite($stdout, " </instruction>\n");
                        $order++;
                        break;
                    }

                }else{
                    fwrite($stderr, "Instrukce má špatný počet argumentů.\n");
                    exit(ERR_CODE_OTHER);
                }

            default:
            fwrite($stderr, "Vyskytla se syntakticka chyba pri zpracovani syntaxe.\n");
            exit(ERR_BAD_CODE);
        }

    }

    fclose($stdin);
    fwrite($stdout, "</program>\n");
}
arg_check($argc, $argv);
parse();