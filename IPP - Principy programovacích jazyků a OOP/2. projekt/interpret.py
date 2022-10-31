import xml.etree.ElementTree as elmTree
import sys
import re
import os
""" Importy potřebné k funkčnsti programu   """

"""
    Author: Tony Pham (xphamt00)
    Version: 1.0
"""

""" Základní funkce programu interpret.py    """
"""
    Funkce rozdělí vstup na frame a nazev framu
    var = GF@test -> return: frame = GF, name = test
"""
def split_var(var):
    if var.startswith("GF") or var.startswith("LF") or var.startswith("TF"):
        frame, name = var.split("@", 1)
        return frame, name
    else:
        exit_with_error("Nejedná se o var", 53)


"""  Funkce vypíše na stderr chybnou zprávu a ukončí program s přiděleným číslem  """
def exit_with_error(err_msg, err_num):
    sys.stderr.write(err_msg + "\n")
    exit(err_num)


"""  
    Funkce zkontroluje typ argumentu -> jestli je jedná o var nebo o konstantu.
    Pokud se jedná o konstantu jen vrátí typ a obsah argumentu.
    Pokud se jedná o proměnnou, zkontroluje jestli je rámec definován a jestli se nachází v rámci.
"""
def check_symb(argument, type_arg, frames):
    if type_arg in ("int", "bool", "string", "nil", "label", "type"):
        return type_arg, argument
    else:
        frame, value = split_var(argument)
        frame_obj = frames.get_frame(frame)
        if frame_obj is None:
            exit_with_error("Nelze cist promennou z neexistujiciho ramce", 55)
        if value not in frame_obj:
            exit_with_error("Nelze cist nedefinovanou promennou v existujicim ramci.", 54)
        else:
            if frame_obj[value]["type_arg"] is None:
                exit_with_error("Nelze cist promennou bez hodnot", 56)
            return frame_obj[value]["type_arg"], frame_obj[value]["value"]


""" Funkce zkontroluje vstupní argumenty a vrátí cesty k souborům.  """
def input_check():
    xml_path = ""
    input_path = ""
    if len(sys.argv) == 2 or len(sys.argv) == 3:
        for argument in sys.argv[1:]:
            if argument == "--help":
                print("Skript (interpret.py v jazyce Python 3.8) načte XML reprezentaci programu a tento program s "
                      "využitím vstupu dle parametrů příkazové řádky interpretuje a generuje výstup." + "\n")

            elif argument.startswith("--source="):
                xml_path = argument[9:]
            elif argument.startswith("--input="):
                input_path = argument[8:]

            else:
                exit_with_error("Spatny argument (parametr)", 10)

    else:
        exit_with_error("Spatny pocet argumentu (parametr)", 10)

    return xml_path, input_path


""" Funkce zkontroluje počet argumentů v souboru xml.  """
def arg_count_check(root):
    for instruction in root:
        if instruction.attrib["opcode"].upper() in ["CREATEFRAME", "PUSHFRAME", "POPFRAME", "BREAK", "RETURN"]:
            if len(instruction) == 0:
                continue
            else:
                exit_with_error("Chyba v poču argumentu v XML.", 32)
        elif instruction.attrib["opcode"].upper() in ["DPRINT", "DEFVAR", "CALL", "PUSHS", "POPS", "LABEL", "JUMP", "WRITE", "EXIT"]:
            if len(instruction) == 1:
                continue
            else:
                exit_with_error("Chyba v poču argumentu v XML.", 32)
        elif instruction.attrib["opcode"].upper() in ["MOVE", "INT2CHAR", "READ", "STRLEN", "TYPE", "NOT"]:
            if len(instruction) == 2:
                continue
            else:
                exit_with_error("Chyba v poču argumentu v XML.", 32)
        elif instruction.attrib["opcode"].upper() in ["ADD", "SUB", "MUL", "IDIV", "LT", "GT", "EQ", "AND", "OR", "JUMPIFEQ", "JUMPIFNEQ", "STRI2INT", "CONCAT", "GETCHAR", "SETCHAR"]:
            if len(instruction) == 3:
                continue
            else:
                exit_with_error("Chyba v poču argumentu v XML.", 32)
        else:
                exit_with_error("Chyba v XML.", 32)


""" Funkce zkontroluje první 2 řádky souboru xml.  """
def check_head(root):
    if root.tag != "program":
        exit_with_error("Chyba v hlavičce XML", 31)
    for i in root.attrib:
        if i not in ["language", "name", "description"]:
            exit_with_error("Chyba v hlavičce XML", 32)
    if "language" not in root.attrib :
        exit_with_error("Chyba v hlavičce XML", 31)
    if root.attrib["language"].lower() != "ippcode22":
        exit_with_error("Chyba v hlavičce XML", 31)


""" Funkce zkontroluje instrukce a jejich prvky  """
def check_instructions(root):
    for instruction in root:
        if instruction.tag != "instruction":
            exit_with_error("Nespravny nazev elementu instruction.", 32)
        if "order" not in instruction.attrib:
            exit_with_error("Chybi atribut order u elmentu instrukce.", 32)
        if "opcode" not in instruction.attrib:
            exit_with_error("Chybi atribut opcode u elementu instrukce,", 32)
        try:
            instr_number = int(instruction.attrib["order"])
            if instr_number <= 0:
                exit_with_error("Cisla instrukci musi byt kladne a nenulove.", 32)
        except ValueError:
            exit_with_error("Nebylo mozne precist hodnotu int u parametru order u argumentu instrukce.", 32)
        for argument in instruction:
            if "type" not in argument.attrib:
                exit_with_error("Chybejici atribut type u argumentu instrukce.", 32)
            if argument.attrib["type"] not in ["string", "int", "bool", "label", "type", "nil", "var"]:
                exit_with_error("Chybny udaj atributu type u argumentu instrukce.", 32)


""" Třídy programu interpret.py """

""" 
    Třída Instruction je hlavní třída programu interpret.py.
    Obsahuje funkce na nastavení a získání vlastností.
"""
class Instruction:

    def __init__(self, opcode, num_of_args, order):
        self._opcode = opcode
        self._numOfArg: int = num_of_args
        self._order: int = order
        self._arg1_type: str = ""
        self._arg1 = ""
        self._arg2_type: str = ""
        self._arg2 = ""
        self._arg3_type: str = ""
        self._arg3 = ""

    def get_opcode(self):
        return self._opcode

    def get_order(self):
        return self._order

    def get_num_of_arg(self):
        return self._numOfArg

    def set_arg1(self, type_arg, value):
        self._arg1_type = type_arg
        self._arg1 = value

    def set_arg2(self, type_arg, value):
        self._arg2_type = type_arg
        self._arg2 = value

    def set_arg3(self, type_arg, value):
        self._arg3_type = type_arg
        self._arg3 = value

    def get_arg_1(self):
        return self._arg1_type

    def get_arg_2(self):
        return self._arg2_type

    def get_arg_3(self):
        return self._arg3_type

    def get_arg_1_value(self):
        return self._arg1

    def get_arg_2_value(self):
        return self._arg2

    def get_arg_3_value(self):
        return self._arg3


""" Zde se nachází potomci třídy Instruction a volají konstruktor s určitými parametry  """
class Move(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("MOVE", num_of_args, order)


class Createframe(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("CREATEFRAME", num_of_args, order)


class Pushframe(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("PUSHFRAME", num_of_args, order)


class Popframe(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("POPFRAME", num_of_args, order)


class Defvar(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("DEFVAR", num_of_args, order)


class Call(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("CALL", num_of_args, order)


class Return(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("RETURN", num_of_args, order)


class Pushs(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("PUSHS", num_of_args, order)


class Pops(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("POPS", num_of_args, order)


class Add(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("ADD", num_of_args, order)


class Sub(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("SUB", num_of_args, order)


class Mul(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("MUL", num_of_args, order)


class Idiv(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("IDIV", num_of_args, order)


class Lt(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("LT", num_of_args, order)


class Gt(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("GT", num_of_args, order)


class Eq(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("EQ", num_of_args, order)


class And(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("AND", num_of_args, order)


class Or(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("OR", num_of_args, order)


class Not(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("NOT", num_of_args, order)


class Int2char(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("INT2CHAR", num_of_args, order)


class Stri2int(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("STRI2INT", num_of_args, order)


class Read(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("READ", num_of_args, order)


class Write(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("WRITE", num_of_args, order)


class Concat(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("CONCAT", num_of_args, order)


class Strlen(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("STRLEN", num_of_args, order)


class Getchar(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("GETCHAR", num_of_args, order)


class Setchar(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("SETCHAR", num_of_args, order)


class Type(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("TYPE", num_of_args, order)


class Label(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("LABEL", num_of_args, order)


class Jump(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("JUMP", num_of_args, order)


class Jumpifeq(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("JUMPIFEQ", num_of_args, order)


class Jumpifneq(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("JUMPIFNEQ", num_of_args, order)


class Exit(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("EXIT", num_of_args, order)


class Dprint(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("DPRINT", num_of_args, order)


class Break(Instruction):

    def __init__(self, num_of_args, order):
        super().__init__("BREAK", num_of_args, order)


""" Třída Factory dostane string a podle něj se rozhoduje, jaký konstruktor zavolá"""
class Factory:

    @classmethod
    def resolve(cls, string, order, num_of_args):
        if string.upper() == "MOVE":
            return Move(order, num_of_args)
        elif string.upper() == "CREATEFRAME":
            return Createframe(order, num_of_args)
        elif string.upper() == "PUSHFRAME":
            return Pushframe(order, num_of_args)
        elif string.upper() == "POPFRAME":
            return Popframe(order, num_of_args)
        elif string.upper() == "DEFVAR":
            return Defvar(order, num_of_args)
        elif string.upper() == "CALL":
            return Call(order, num_of_args)
        elif string.upper() == "RETURN":
            return Return(order, num_of_args)
        elif string.upper() == "PUSHS":
            return Pushs(order, num_of_args)
        elif string.upper() == "POPS":
            return Pops(order, num_of_args)
        elif string.upper() == "ADD":
            return Add(order, num_of_args)
        elif string.upper() == "SUB":
            return Sub(order, num_of_args)
        elif string.upper() == "MUL":
            return Mul(order, num_of_args)
        elif string.upper() == "IDIV":
            return Idiv(order, num_of_args)
        elif string.upper() == "LT":
            return Lt(order, num_of_args)
        elif string.upper() == "GT":
            return Gt(order, num_of_args)
        elif string.upper() == "EQ":
            return Eq(order, num_of_args)
        elif string.upper() == "AND":
            return And(order, num_of_args)
        elif string.upper() == "OR":
            return Or(order, num_of_args)
        elif string.upper() == "NOT":
            return Not(order, num_of_args)
        elif string.upper() == "INT2CHAR":
            return Int2char(order, num_of_args)
        elif string.upper() == "STRI2INT":
            return Stri2int(order, num_of_args)
        elif string.upper() == "READ":
            return Read(order, num_of_args)
        elif string.upper() == "WRITE":
            return Write(order, num_of_args)
        elif string.upper() == "CONCAT":
            return Concat(order, num_of_args)
        elif string.upper() == "STRLEN":
            return Strlen(order, num_of_args)
        elif string.upper() == "GETCHAR":
            return Getchar(order, num_of_args)
        elif string.upper() == "SETCHAR":
            return Setchar(order, num_of_args)
        elif string.upper() == "TYPE":
            return Type(order, num_of_args)
        elif string.upper() == "LABEL":
            return Label(order, num_of_args)
        elif string.upper() == "JUMP":
            return Jump(order, num_of_args)
        elif string.upper() == "JUMPIFEQ":
            return Jumpifeq(order, num_of_args)
        elif string.upper() == "JUMPIFNEQ":
            return Jumpifneq(order, num_of_args)
        elif string.upper() == "EXIT":
            return Exit(order, num_of_args)
        elif string.upper() == "DPRINT":
            return Dprint(order, num_of_args)
        elif string.upper() == "BREAK":
            return Break(order, num_of_args)
        else:
            exit_with_error("spatny opcode", 53)


""" 
    Třída Frame obsahuje slovníky TF, GL a zásobník s LF.
    Dále obsahuje funkce pro lehčí práci s rámci.
"""
class Frame:

    def __init__(self):
        self._globalFrame = {}
        self._tmpFrame = {}
        self._tmpFrameDefined = False
        self._frameStack = []

    """ Funkce dostane string s názvem GF,LF,LF a vrátí odkaz na práci s ním"""
    def get_frame(self, frame):
        if frame == "GF":
            return self._globalFrame
        elif frame == "LF":
            return self._frameStack[-1] if len(self._frameStack) > 0 else None
        elif frame == "TF":
            return self._tmpFrame if self._tmpFrameDefined else None
        else:
            return None

    """ Funkce vytvoří TF, smaže data na něm a definuje ho"""
    def create_frame(self):
        self._tmpFrame = {}
        self._tmpFrameDefined = True

    """ Funkce pošle obsah TF na zásobník rámců"""
    def push_frame(self):
        if self._tmpFrameDefined:
            self._frameStack.append(self._tmpFrame)
            self._tmpFrameDefined = False
        else:
            exit_with_error("Nelze pushnout frame, ktery je nedefinovany.", 55)

    """ Funkce veme vrchol zásobníku a předá ho na TF"""
    def pop_frame(self):
        if len(self._frameStack):
            self._tmpFrame = self._frameStack.pop()
            self._tmpFrameDefined = True
        else:
            exit_with_error("Nelze ziskat hotnotu z prazdneho zasobniku.", 55)

    """ Funkce nastaví hodnotu proměnné v rámci dle volání  """
    def set_var(self, arg, type_arg, value):
        frame, name = split_var(arg)
        frame_obj = self.get_frame(frame)
        if frame_obj is None:
            exit_with_error("Nelze cist z nedefinovaneho ramce.", 55)
        if name not in frame_obj:
            exit_with_error("Promenna neexistuje v ramci.", 54)
        frame_obj[name]["value"] = value
        frame_obj[name]["type_arg"] = type_arg

    """ Funkce definuje proměnnou v rámci dle volání  """
    def def_var(self, arg):
        frame, name = split_var(arg)
        frame_obj = self.get_frame(frame)
        if frame_obj is None:
            exit_with_error("Nelze cist z nedefinovaneho ramce.", 55)
        else:
            if name in frame_obj:
                exit_with_error("Nelze redefinovat promennou.", 52)
            else:
                frame_obj[name] = {"value": None, "type_arg": None}


def main():
    """ Zkontroluju a načtu vstupy """
    global xml, input_file, input_source
    input_stdin = "false"
    xml_path, input_path = input_check()
    if xml_path == "":
        try:
            xml = sys.stdin
        except Exception:
            exit_with_error("Nelze cist ze stdin.", 11)
    else:
        try:
            xml = open(xml_path, "r")
        except FileNotFoundError:
            exit_with_error("Soubor --source nenalezen.", 11)

    if input_path == "":
        input_stdin = "true"
    else:
        try:
            input_file = open(input_path, "r")
            input_source = input_file.read().splitlines()
        except FileNotFoundError:
            exit_with_error("Soubor --input nenalezen.", 11)

    """ Pomocné proměnné, objekty, slovníky, zásobníky """
    frames = Frame()
    tree = elmTree.parse(xml)
    root = tree.getroot()
    instr_dict = {}
    label_dict = {}
    data_stack = []
    position_stack = []
    order_array = []
    read_line = 1
    arg_count_check(root)
    check_head(root)
    check_instructions(root)
    """ Projdu soubor xml a vyberu z něj podstatná data """
    for instruction in root.findall("instruction"):
        opcode = instruction.attrib["opcode"]
        order = instruction.attrib["order"]
        order_array.append(int(order))
        instr_dict[order] = Factory.resolve(opcode, len(instruction), order)
        for y in range(1, len(instruction)+1):
            value = root.find("instruction[@order='" + str(order) + "']/arg" + str(y))
            type_arg = value.attrib["type"]
            if y == 1:
                instr_dict[order].set_arg1(type_arg, value.text)
            if y == 2:
                instr_dict[order].set_arg2(type_arg, value.text)
            if y == 3:
                instr_dict[order].set_arg3(type_arg, value.text)
    order_array.sort()
    """ Vyberu názvi a pozice návěstí ještě před hlavním cyklem """
    array_cnt = 0
    while array_cnt < len(order_array):
        label_fill_numb = order_array[array_cnt]
        if instr_dict[str(label_fill_numb)].get_opcode() == "LABEL":
            type_arg_1, label_name = check_symb(instr_dict[str(label_fill_numb)].get_arg_1_value(), instr_dict[str(label_fill_numb)].get_arg_1(), frames)
            if type_arg_1 == "label":
                label_dict[label_name] = array_cnt
            else:
                exit_with_error("Nelze provest LABEL -> spatne typ.", 53)
        array_cnt += 1

    """ Hlavní cyklus programu -> provádí instrukce dle názvu opcodu """
    array_cnt = 0
    while array_cnt < len(order_array):
        key = order_array[array_cnt]
        if instr_dict[str(key)].get_opcode() == "MOVE":
            type_arg, value = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            replace_arr = re.findall(r"\\[0-9]{3}", str(value))
            if len(replace_arr) > 0:
                i = 0
                while i < len(replace_arr):
                    value = re.sub(r"\\[0-9]{3}", chr(int(replace_arr[i][2:])), value)
                    i += 1
            if instr_dict[str(key)].get_arg_2() == "label":
                exit_with_error("Nelze provest MOVE -> label je spatny typ.", 53)
            frames.set_var(instr_dict[str(key)].get_arg_1_value(), type_arg, value)
        elif instr_dict[str(key)].get_opcode() == "CREATEFRAME":
            frames.create_frame()
        elif instr_dict[str(key)].get_opcode() == "PUSHFRAME":
            frames.push_frame()
        elif instr_dict[str(key)].get_opcode() == "POPFRAME":
            frames.pop_frame()
        elif instr_dict[str(key)].get_opcode() == "DEFVAR":
            frames.def_var(instr_dict[str(key)].get_arg_1_value())
        elif instr_dict[str(key)].get_opcode() == "CALL":
            type_arg, label_name = check_symb(instr_dict[str(key)].get_arg_1_value(), instr_dict[str(key)].get_arg_1(), frames)
            position_stack.append(key)
            if type_arg == "label":
                if label_name in label_dict:
                    array_cnt = label_dict[label_name]
                    continue
                else:
                    exit_with_error("Skok na neexistujici label.", 52)
            else:
                exit_with_error("Nelze provest JUMP -> spatny typ.", 53)

        elif instr_dict[str(key)].get_opcode() == "RETURN":
            try:
                array_cnt = position_stack.pop()
                continue
            except IndexError:
                exit_with_error("Nelze provest pop na prazdny zasobnik.", 56)
        elif instr_dict[str(key)].get_opcode() == "PUSHS":
            type_arg, value = check_symb(instr_dict[str(key)].get_arg_1_value(), instr_dict[str(key)].get_arg_1(), frames)
            if type_arg != "label":
                data_stack.append((type_arg, value))
            else:
                exit_with_error("Nelze provest push s labelem", 52)
        elif instr_dict[str(key)].get_opcode() == "POPS":
            try:
                type_arg, value = data_stack.pop()
                frames.set_var(instr_dict[str(key)].get_arg_1_value(), type_arg, value)

            except IndexError:
                exit_with_error("Nelze provest pop na prazdny zasobnik.", 56)
        elif instr_dict[str(key)].get_opcode() == "ADD":
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, value_2 = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            if type_arg_2 == type_arg_1 == "int":
                frames.set_var(instr_dict[str(key)].get_arg_1_value(), "int", str(int(value_1) + int(value_2)))
            else:
                exit_with_error("Nelze provest operaci s rozdilnymi typi.", 53)
        elif instr_dict[str(key)].get_opcode() == "SUB":
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, value_2 = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            if type_arg_2 == type_arg_1 == "int":
                frames.set_var(instr_dict[str(key)].get_arg_1_value(), "int", str(int(value_1) - int(value_2)))
            else:
                exit_with_error("Nelze provest operaci s rozdilnymi typi.", 53)
        elif instr_dict[str(key)].get_opcode() == "MUL":
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, value_2 = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            if type_arg_2 == type_arg_1 == "int":
                frames.set_var(instr_dict[str(key)].get_arg_1_value(), "int", str(int(value_1) * int(value_2)))
            else:
                exit_with_error("Nelze provest operaci s rozdilnymi typi.", 53)
        elif instr_dict[str(key)].get_opcode() == "IDIV":
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, value_2 = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            if int(value_2) == 0:
                exit_with_error("Nelze delit 0", 57)
            # // kvůli celočíselnému dělení
            if type_arg_2 == type_arg_1 == "int":
                frames.set_var(instr_dict[str(key)].get_arg_1_value(), "int", str(int(value_1) // int(value_2)))
            else:
                exit_with_error("Nelze provest operaci s rozdilnymi typi.", 53)
        elif instr_dict[str(key)].get_opcode() == "LT":
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, value_2 = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            if type_arg_2 == type_arg_1:
                if type_arg_1 == "nil" or type_arg_2 == "nil":
                    exit_with_error("Nelze porovnat nil u LT.", 53)
                else:
                    if type_arg_1 == "int":
                        if int(value_1) < int(value_2):
                            bool_value = "true"
                        else:
                            bool_value = "false"
                    elif type_arg_1 == "bool":
                        if value_1 == "false" and value_2 == "true":
                            bool_value = "true"
                        else:
                            bool_value = "false"
                    elif type_arg_1 == "string":
                        if value_1 < value_2:
                            bool_value = "true"
                        else:
                            bool_value = "false"
                    else:
                        bool_value = "false"

            else:
                exit_with_error("Nelze porovnat rozdilne typy", 53)
            frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", bool_value)
        elif instr_dict[str(key)].get_opcode() == "GT":
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, value_2 = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            if type_arg_2 == type_arg_1:
                if type_arg_1 == "nil" or type_arg_2 == "nil":
                    exit_with_error("Nelze porovnat nil u GT.", 53)
                else:
                    if type_arg_1 == "int":
                        if int(value_1) > int(value_2):
                            bool_value = "true"
                        else:
                            bool_value = "false"
                    elif type_arg_1 == "bool":
                        if value_1 == "true" and value_2 == "false":
                            bool_value = "true"
                        else:
                            bool_value = "false"
                    elif type_arg_1 == "string":
                        if value_1 > value_2:
                            bool_value = "true"
                        else:
                            bool_value = "false"
                    else:
                        bool_value = "false"

            else:
                exit_with_error("Nelze porovnat rozdilne typy", 53)
            frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", bool_value)
        elif instr_dict[str(key)].get_opcode() == "EQ":
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, value_2 = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            bool_value = "false"
            if type_arg_2 == type_arg_1:
                if type_arg_1 == "nil" and type_arg_2 == "nil":
                    frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", "true")
                else:
                    if type_arg_1 == "int":
                        if int(value_1) == int(value_2):
                            bool_value = "true"
                        else:
                            bool_value = "false"
                    elif type_arg_1 == "bool":
                        if value_1 == value_2:
                            bool_value = "true"
                        else:
                            bool_value = "false"
                    elif type_arg_1 == "string":
                        if value_1 == value_2:
                            bool_value = "true"
                        else:
                            bool_value = "false"
                    else:
                        bool_value = "false"
            elif type_arg_1 == "nil" or type_arg_2 == "nil":
                frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", "false")
            else:
                exit_with_error("Nelze porovnat rozdilne typy", 53)
            if value_1 == value_2 == "nil":
                frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", "true")
            else:
                frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", bool_value)

        elif instr_dict[str(key)].get_opcode() == "AND":
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, value_2 = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            if type_arg_2 == type_arg_1 == "bool":
                if value_1 == value_2 == "true":
                    bool_value = "true"
                else:
                    bool_value = "false"
            else:
                exit_with_error("Nelze porovnat jine typy nez bool", 53)
            frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", bool_value)
        elif instr_dict[str(key)].get_opcode() == "OR":
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, value_2 = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            if type_arg_2 == type_arg_1 == "bool":
                if value_1 == "true" or value_2 == "true":
                    bool_value = "true"
                else:
                    bool_value = "false"
            else:
                exit_with_error("Nelze porovnat jine typy nez bool", 53)
            frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", bool_value)
        elif instr_dict[str(key)].get_opcode() == "NOT":
            type_arg, value = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            if type_arg == "bool":
                if value == "true":
                    bool_value = "false"
                elif value == "false":
                    bool_value = "true"
            else:
                exit_with_error("Nelze porovnat jine typy nez bool", 53)
            frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", bool_value)
        elif instr_dict[str(key)].get_opcode() == "INT2CHAR":
            type_arg, int_data = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            if type_arg == "int":
                try:
                    char = chr(int(int_data))
                except ValueError:
                    exit_with_error("Nebylo mozne prevest hodnotu", 58)
                frames.set_var(instr_dict[str(key)].get_arg_1_value(), "string", char)
            else:
                exit_with_error("Nelze provadet INT2CHAR s typem jinym nez int", 53)
        elif instr_dict[str(key)].get_opcode() == "STRI2INT":
            type_arg_1, string_value = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, int_value = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            if type_arg_2 == "int" and type_arg_1 == "string":
                if int(int_value) >= 0 and int(int_value) < len(string_value):
                    pass_value = ord(string_value[int(int_value)])
                    frames.set_var(instr_dict[str(key)].get_arg_1_value(), "int", pass_value)
                else:
                    exit_with_error("Indexace mimo retezec.", 58)
            else:
                exit_with_error("Nelze provadet STRI2INT s typem jinym nez string a int", 53)
        elif instr_dict[str(key)].get_opcode() == "READ":
            type_arg, value = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            if input_stdin == "true":
                try:
                    read_value = input()
                    if read_value == "":
                        if value == "bool":
                            frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", "false")
                        frames.set_var(instr_dict[str(key)].get_arg_1_value(), "nil", "nil")
                        array_cnt += 1
                        continue
                except:
                    if value == "bool":
                        frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", "false")
                    frames.set_var(instr_dict[str(key)].get_arg_1_value(), "nil", "nil")
                    array_cnt += 1
                    continue
            else:
                if os.stat(input_path).st_size == 0:
                    if value == "bool":
                        frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", "false")
                    frames.set_var(instr_dict[str(key)].get_arg_1_value(), "nil", "nil")
                    array_cnt += 1
                    continue
                else:
                    read_value = input_source[read_line]
            if value == "int":
                try:
                    int_value = str(int(read_value))
                except:
                    sys.stderr.write("Neni to int.")
                    frames.set_var(instr_dict[str(key)].get_arg_1_value(), "nil", "")
                else:
                    frames.set_var(instr_dict[str(key)].get_arg_1_value(), "int", int_value)
            elif value == "bool":
                if read_value.lower() == "true":
                    frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", "true")
                elif read_value.lower() == "false":
                    frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", "false")
                else:
                    sys.stderr.write("Spatny udaj v bool.")
                    frames.set_var(instr_dict[str(key)].get_arg_1_value(), "bool", "false")
            else:
                frames.set_var(instr_dict[str(key)].get_arg_1_value(), "string", read_value)
        elif instr_dict[str(key)].get_opcode() == "WRITE":
            type_arg, value = check_symb(instr_dict[str(key)].get_arg_1_value(), instr_dict[str(key)].get_arg_1(), frames)
            if type_arg == "nil" and value == "nil":
                value = ""
                print(value, end="")
            else:
                print(value, end="")
        elif instr_dict[str(key)].get_opcode() == "CONCAT":
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, value_2 = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            if type_arg_2 == "string" and type_arg_1 == "string":
                value_1 = "" if value_1 is None else value_1
                value_2 = "" if value_2 is None else value_2
                frames.set_var(instr_dict[str(key)].get_arg_1_value(), "string", value_1 + value_2)
            else:
                exit_with_error("Nelze provest concat když hodnoty nejsou string.", 53)
        elif instr_dict[str(key)].get_opcode() == "STRLEN":
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            if instr_dict[str(key)].get_arg_2() == "string":
                replace_arr = re.findall(r"\\[0-9]{3}", str(value_1))
                if len(replace_arr) > 0:
                    i = 0
                    while i < len(replace_arr):
                        value_1 = re.sub(r"\\[0-9]{3}", chr(int(replace_arr[i][2:])), value_1)
                        i += 1
            if type_arg_1 == "string":
                if value_1 is None:
                    frames.set_var(instr_dict[str(key)].get_arg_1_value(), "int", 0)
                else:
                    frames.set_var(instr_dict[str(key)].get_arg_1_value(), "int", len(value_1))
            else:
                exit_with_error("Nelze provest strlen když hodnoty nejsou string.", 53)
        elif instr_dict[str(key)].get_opcode() == "GETCHAR":
            type_arg_1, string_value = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, int_value = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            if type_arg_2 == "int" and type_arg_1 == "string":
                if int(int_value) >= 0 and int(int_value) < len(string_value):
                    frames.set_var(instr_dict[str(key)].get_arg_1_value(), "string", string_value[int(int_value)])
                else:
                    exit_with_error("INdex je mimo pole.", 58)
            else:
                exit_with_error("Nelze provest GETCHAR -> spatne typy var", 53)
        elif instr_dict[str(key)].get_opcode() == "SETCHAR":
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, value_2 = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            type, data = check_symb(instr_dict[str(key)].get_arg_1_value(), instr_dict[str(key)].get_arg_1(), frames)

            if type_arg_1 == "int" and type_arg_2 == "string" and type == "string":
                if int(value_1) < 0 or int(value_1) >= len(data) or data == "" or data is None:
                    exit_with_error("Indexace mimo retezec.", 58)
                if value_2 == "" or value_2 is None:
                    exit_with_error("Prazdny retezec - chyba u instrukce SETCHAR.", 58)
                else:
                    data_list = list(data)
                    data_list[int(value_1)] = value_2[0]
                    data = "".join(data_list)
                    frames.set_var(instr_dict[str(key)].get_arg_1_value(), "string", data)
            else:
                exit_with_error("Nelze provest setchar.", 53)
        elif instr_dict[str(key)].get_opcode() == "TYPE":
            type_arg_1 = instr_dict[str(key)].get_arg_2()
            value_1 = instr_dict[str(key)].get_arg_2_value()
            frame, value = split_var(value_1)
            frame_obj = frames.get_frame(frame)
            if frame_obj is None:
                exit_with_error("Nelze cist promennou z neexistujiciho ramce", 55)
            if value not in frame_obj:
                exit_with_error("Nelze cist nedefinovanou promennou v existujicim ramci.", 54)
            else:
                if frame_obj[value]["type_arg"] is None:
                    type_arg_1 = ""
                else: type_arg_1 = frame_obj[value]["type_arg"]
            frames.set_var(instr_dict[str(key)].get_arg_1_value(), "string", type_arg_1)
        elif instr_dict[str(key)].get_opcode() == "LABEL":
            pass
        elif instr_dict[str(key)].get_opcode() == "JUMP":
            type_arg_1, label_name = check_symb(instr_dict[str(key)].get_arg_1_value(), instr_dict[str(key)].get_arg_1(), frames)
            if type_arg_1 == "label":
                if label_name in label_dict:
                    array_cnt = label_dict[label_name]
                    continue
                else:
                    exit_with_error("Skok na neexistujici label.", 52)
            else:
                exit_with_error("Nelze provest JUMP -> spatne typ", 53)
        elif instr_dict[str(key)].get_opcode() == "JUMPIFEQ":
            label_type, label_name = check_symb(instr_dict[str(key)].get_arg_1_value(), instr_dict[str(key)].get_arg_1(), frames)
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, value_2 = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            if type_arg_1 == type_arg_2 or type_arg_1 == "nil" or type_arg_2 == "nil":
                if value_1 == value_2:
                    if label_name in label_dict:
                        array_cnt = label_dict[label_name]
                        continue
                    else:
                        exit_with_error("Skok na neexistujici label.", 52)
            else:
                exit_with_error("Typy se musi rovnat.", 53)
        elif instr_dict[str(key)].get_opcode() == "JUMPIFNEQ":
            label_type, label_name = check_symb(instr_dict[str(key)].get_arg_1_value(), instr_dict[str(key)].get_arg_1(), frames)
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_2_value(), instr_dict[str(key)].get_arg_2(), frames)
            type_arg_2, value_2 = check_symb(instr_dict[str(key)].get_arg_3_value(), instr_dict[str(key)].get_arg_3(), frames)
            if type_arg_1 == type_arg_2 or type_arg_1 == "nil" or type_arg_2 == "nil":
                if value_1 != value_2:
                    if label_name in label_dict:
                        array_cnt = label_dict[label_name]
                        continue
                    else:
                        exit_with_error("Skok na neexistujici label.", 52)
            else:
                exit_with_error("Typy se musi rovnat.", 53)
        elif instr_dict[str(key)].get_opcode() == "EXIT":
            type_arg_1, value_1 = check_symb(instr_dict[str(key)].get_arg_1_value(), instr_dict[str(key)].get_arg_1(), frames)
            if type_arg_1 == "int":
                if int(value_1) < 0 or int(value_1) > 49:
                    exit_with_error("EXIT s cislem mimo skalu", 57)
                else:
                    exit_with_error("EXIT", int(value_1))
            else:
                exit_with_error("spatny typ pro EXIT", 53)
        elif instr_dict[str(key)].get_opcode() == "DPRINT":
            type_arg, value = check_symb(instr_dict[str(key)].get_arg_1_value(), instr_dict[str(key)].get_arg_1(), frames)
            if type_arg == "nil" and type_arg == "nil":
                value = ""
                sys.stderr.write(value)
            else:
                sys.stderr.write(value)
        elif instr_dict[str(key)].get_opcode() == "BREAK":
            sys.stderr.write("order: " + str(key))
            sys.stderr.write("TF: " + str(frames.get_frame("TF")))
            sys.stderr.write("GF: " + str(frames.get_frame("GF")))
            sys.stderr.write("LF: " + str(frames.get_frame("LF")))
        #inkrementace přes slovník
        array_cnt += 1


if __name__ == "__main__":
    main()
