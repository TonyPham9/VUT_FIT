-- cpu.vhd: Simple 8-bit CPU (BrainLove interpreter)
-- Copyright (C) 2021 Brno University of Technology,
--                    Faculty of Information Technology
-- Author(s): DOPLNIT
--

library ieee;
use ieee.std_logic_1164.all;
use ieee.std_logic_arith.all;
use ieee.std_logic_unsigned.all;

-- ----------------------------------------------------------------------------
--                        Entity declaration
-- ----------------------------------------------------------------------------
entity cpu is
 port (
   CLK   : in std_logic;  -- hodinovy signal
   RESET : in std_logic;  -- asynchronni reset procesoru
   EN    : in std_logic;  -- povoleni cinnosti procesoru
 
   -- synchronni pamet ROM
   CODE_ADDR : out std_logic_vector(11 downto 0); -- adresa do pameti
   CODE_DATA : in std_logic_vector(7 downto 0);   -- CODE_DATA <- rom[CODE_ADDR] pokud CODE_EN='1'
   CODE_EN   : out std_logic;                     -- povoleni cinnosti
   
   -- synchronni pamet RAM
   DATA_ADDR  : out std_logic_vector(9 downto 0); -- adresa do pameti
   DATA_WDATA : out std_logic_vector(7 downto 0); -- ram[DATA_ADDR] <- DATA_WDATA pokud DATA_EN='1'
   DATA_RDATA : in std_logic_vector(7 downto 0);  -- DATA_RDATA <- ram[DATA_ADDR] pokud DATA_EN='1'
   DATA_WREN  : out std_logic;                    -- cteni z pameti (DATA_WREN='0') / zapis do pameti (DATA_WREN='1')
   DATA_EN    : out std_logic;                    -- povoleni cinnosti
   
   -- vstupni port
   IN_DATA   : in std_logic_vector(7 downto 0);   -- IN_DATA obsahuje stisknuty znak klavesnice pokud IN_VLD='1' a IN_REQ='1'
   IN_VLD    : in std_logic;                      -- data platna pokud IN_VLD='1'
   IN_REQ    : out std_logic;                     -- pozadavek na vstup dat z klavesnice
   
   -- vystupni port
   OUT_DATA : out  std_logic_vector(7 downto 0);  -- zapisovana data
   OUT_BUSY : in std_logic;                       -- pokud OUT_BUSY='1', LCD je zaneprazdnen, nelze zapisovat,  OUT_WREN musi byt '0'
   OUT_WREN : out std_logic                       -- LCD <- OUT_DATA pokud OUT_WE='1' a OUT_BUSY='0'
 );
end cpu;


-- ----------------------------------------------------------------------------
--                      Architecture declaration
-- ----------------------------------------------------------------------------
architecture behavioral of cpu is

	--PC
	signal PC_reg : std_logic_vector (11 downto 0);
	signal PC_inc : std_logic;
	signal PC_dec : std_logic;
	--PC end

	--CNT vnoøený while
	signal CNT_reg : std_logic_vector (11 downto 0);
	signal CNT_inc : std_logic;
	signal CNT_dec : std_logic;
	--CNT

	--PTR ukazatel do pamìti
	signal PTR_reg : std_logic_vector (9 downto 0);
	signal PTR_inc : std_logic;
	signal PTR_dec : std_logic;
	--PTR end

	--STATES stavy automatu, který ovládá fsm
	type FSM_state is (
		s_start, -- zaèátek
		s_fetch, -- naètení
		s_decode, -- dekódování instrukce

		s_pointer_inc, --'>' inkrementace ukazatele
		s_pointer_dec, --'<' dekrementace ukazatele

		s_current_inc,s_current_inc_next,s_current_inc_final, -- '+' inkrementace hodnoty aktuální buòky
		s_current_dec,s_current_dec_next,s_current_dec_final, -- '-' dekrementace hodnoty aktuální buòky

		s_while_start,s_while_start_next,s_while_start_final,s_while_start_enable,
		s_while_end,s_while_end_next,s_while_end_final,s_while_end_prefinal,s_while_end_enable,

		s_write,s_write_final,
		s_get,s_get_final,
		
		s_break,s_break_enable,s_break_final,
		
		s_null
	);
	signal state : FSM_state := s_start; --poèáteèní stav FSM
	signal nState : FSM_state; --následující stav
	--STATES end

	--MX
	signal MX_select : std_logic_vector (1 downto 0) := (others => '0');
	signal MX_output : std_logic_vector (7 downto 0) := (others => '0');
	--MX end

begin

	--PC podle schématu
	PC_cntr: process (CLK, RESET, PC_inc, PC_dec)
	begin
		if (RESET = '1') then
			PC_reg <= (others => '0');
		elsif (CLK'event) and (CLK = '1') then
			if (PC_inc = '1') then
				PC_reg <= PC_reg + 1;
			elsif (PC_dec = '1') then
				PC_reg <= PC_reg - 1;
			end if;
		end if;
	end process;
	CODE_ADDR <= PC_reg;
	--PC end

	--CNT podle schématu
	CNT_cntr: process (CLK, RESET, CNT_inc, CNT_dec)
	begin
		if (RESET = '1') then
			CNT_reg <= (others => '0');
		elsif (CLK'event) and (CLK = '1') then
			if (CNT_inc = '1') then
				CNT_reg <= CNT_reg + 1;
			elsif (CNT_dec = '1') then
				CNT_reg <= CNT_reg - 1;
			end if;
		end if;
	end process;
	
	OUT_DATA <= DATA_RDATA;
	--CNT end

	--PTR	podle schématu
	PTR_cntr: process (CLK, RESET, PC_inc, PC_dec)
	begin
		if (RESET = '1') then
			PTR_reg <= (others => '0');
		elsif (CLK'event) and (CLK = '1') then
			if (PTR_inc = '1') then
				PTR_reg <= PTR_reg + 1;
			elsif (PTR_dec = '1') then
				PTR_reg <= PTR_reg - 1;
			end if;
		end if;
	end process;
	
	DATA_ADDR <= PTR_reg;
	--PTR end

	--MX podle schématu
	MX: process (CLK, RESET, MX_output)
	begin
		if (RESET = '1') then
			MX_output <= (others => '0');
		elsif (CLK'event) and (CLK = '1') then
			MX_output <= "00000000";
			case MX_select is
				when "00" =>
					MX_output <= IN_DATA;
				when "01" =>
					MX_output <= DATA_RDATA + 1;
				when "10" =>
					MX_output <= DATA_RDATA - 1;
				when "11" =>
					MX_output <= x"00";
				when others =>
					MX_output <= (others => '0');

			end case;
		end if;
	end process;
	
	DATA_WDATA <= MX_output;
	--MUX end

	--FSM actual state logic
	FSM_state_logic: process (CLK, RESET)
	begin
		if (RESET = '1') then
			state <= s_start;
		elsif CLK'event and CLK = '1' then
			if EN = '1' then
			
				state <= nstate;
				
			end if;
		end if;
	end process;
	--FSM actual end

	--FSM next state logic
	FSM_nstate_logic: process (state, OUT_BUSY, IN_VLD, CODE_DATA, CNT_reg, DATA_RDATA) is
	begin
			--inicializaze
			DATA_WREN <= '0';
			PC_inc <= '0';
			PC_dec <= '0';

			PTR_inc <= '0';
			PTR_dec <= '0';

			CNT_inc <= '0';
			CNt_dec <= '0';
			
			MX_select <= "00";

			DATA_EN <= '0';
			CODE_EN <= '0';
			OUT_WREN <= '0';
			IN_REQ <= '0';
		--
		case state is
		
				--poèáteèní stav
			when s_start =>
			
				nState <= s_fetch;
				
				--naèítání instrukce
			when s_fetch =>
			
				CODE_EN <= '1';
				
				nState <= s_decode;
				
				--dekódování instrukce
			when s_decode =>
			
				case CODE_DATA is
					
					-- '>' inkrementace hodnoty ukazatele ( ptr += 1; )
					when x"3E" =>
						nState <= s_pointer_inc; 
						
					-- '<' dekrementace hodnoty ukazatele ( ptr -= 1; )
					when x"3C" =>
						nState <= s_pointer_dec;
					
					-- '+' inkrementace hodnoty aktuální buòky ( *ptr += 1; )
					when x"2B" =>
						nState <= s_current_inc; 
					
					-- '-' dekrementace hodnoty aktuální buòky ( *ptr -= 1; )
					when x"2D" =>
						nState <= s_current_dec;
						
					-- '.' vytiskne hodnotu aktuální buòky ( putchar(*ptr); )
					when x"2E" =>
						nState <= s_write;
					
					-- ',' naète hodnotu a uloží ji do aktuální buòky ( *ptr = getchar(); )
					when x"2C" =>
						nState <= s_get;
						
					-- '[' je-li hodnota aktuální buòky nulová, skoèí za odpovídající pøíkaz ']' jinak pokraèuje následujícím znakem ( while (*ptr) { )
					when x"5B" =>
						nState <= s_while_start;
					
					-- '[' je-li hodnota aktuální buòky NEnulová, skoèí za odpovídající pøíkaz '[' jinak pokraèuje následujícím znakem ( } )
					when x"5D" =>
						nState <= s_while_end;
					
					-- '~' ukonèí právì provádìnou smyèku while ( break; )
					when x"7E" =>
						nState <= s_break;
						
					-- null zastaví vykonávání programu ( return; )
					when x"00" =>
						nState <= s_null;
						
					-- zbytek
					when others =>
						PC_inc <= '1'; -- PC ‹ PC + 1
						nState <= s_fetch;
						
				end case;
			
			-- '>' inkrementace hodnoty ukazatele
			when s_pointer_inc =>
				PTR_inc <= '1'; 	-- PTR ‹ PTR + 1
				PC_inc <= '1';  	-- PC ‹ PC + 1
				
				nstate <= s_fetch;
			
			-- '<' dekrementace hodnoty ukazatele 
			when s_pointer_dec =>
				PTR_dec <= '1';	-- PTR ‹ PTR - 1
				PC_inc <= '1'; 	-- PC ‹ PC - 1
				
				nstate <= s_fetch;
			
			-- '+' inkrementace hodnoty aktuální buòky
			when s_current_inc => -- DATA RDATA ‹ ram[PTR]
				DATA_EN <= '1';
				DATA_WREN <= '0';

				nstate <= s_current_inc_next;
			
			when s_current_inc_next =>
				MX_select <= "01"; -- ram[PTR] ‹ DATA RDATA + 1

				nstate <= s_current_inc_final;

			when s_current_inc_final =>
				DATA_EN <= '1';
				DATA_WREN <= '1';

				PC_inc <= '1'; -- PC ‹ PC + 1

				nstate <= s_fetch;
			
			-- '-' dekrementace hodnoty aktuální buòky
			when s_current_dec => --DATA RDATA ‹ ram[PTR]
				DATA_EN <= '1';
				DATA_WREN <= '0';

				nstate <= s_current_dec_next;

			when s_current_dec_next =>
				MX_select <= "10"; -- ram[PTR] ‹ DATA RDATA - 1

				nstate <= s_current_dec_final;

			when s_current_dec_final =>
				DATA_EN <= '1';
				DATA_WREN <= '1';

				PC_inc <= '1'; -- PC ‹ PC + 1

				nstate <= s_fetch;
				
			-- '.' vytiskne hodnotu aktuální buòky
			when s_write => -- OUT DATA ‹ ram[PTR]
				DATA_EN <= '1';
				DATA_WREN <= '0';
				
				nstate <= s_write_final;
				
			when s_write_final => -- while (OUT BUSY) {}
				if OUT_BUSY = '1' then
					
					DATA_EN <= '1';
					DATA_WREN <= '0';

					nstate <= s_write_final;
				else
					OUT_WREN <= '1'; 

					PC_inc <= '1'; -- PC ‹ PC + 1

					nstate <= s_fetch;
				end if;
				
			-- ',' naète hodnotu a uloží ji do aktuální buòky
			when s_get =>
					IN_REQ <= '1'; -- IN REQ ‹ 1
					MX_select <= "00"; 

					nstate <= s_get_final;

			when s_get_final => -- while (!IN VLD) {}
				if IN_VLD /= '1' then
					IN_REQ <= '1';
					MX_select <= "00"; 

					nstate <= s_get_final;
				else
				
					DATA_EN <= '1'; -- ram[PTR] ‹ IN DATA
					DATA_WREN <= '1';

					PC_inc <= '1'; -- PC ‹ PC + 1

					nstate <= s_fetch;
				end if;
			-- '[' je-li hodnota aktuální buòky nulová, skoèí za odpovídající pøíkaz ']' jinak pokraèuje následujícím znakem 
			when s_while_start =>
				PC_inc <= '1'; -- PC ‹ PC + 1
				DATA_EN <= '1'; -- DATA_RDATA = RAM[PTR]
				DATA_WREN <= '0';
				
				nstate <= s_while_start_next;
				
			when s_while_start_next =>
				if DATA_RDATA = "00000000" then --if (ram[PTR] == 0)
					CNT_inc <= '1'; --CNT ‹ 1
					CODE_EN <= '1'; --c ‹ rom[PC]
					
					nstate <= s_while_start_final;
					
					else
				
					nstate <= s_fetch;
					
				end if;
				
			when s_while_start_final =>
			
				if CNT_reg /= "0000000000" then --while (CNT != 0)
					if CODE_DATA = x"5B" then --if c == ’[’) CNT ‹ CNT + 1
						CNT_inc <= '1';
						
					elsif CODE_DATA = x"5D" then -- elsif (c == ’]’) CNT ‹ CNT - 1
						CNT_dec <= '1'; 
						
					end if;
					
						PC_inc <= '1'; -- -- PC ‹ PC + 1
					
						nstate <= s_while_start_enable;
					
				else
					
					nstate <= s_fetch;
					
				end if;
			when s_while_start_enable => 
				CODE_EN <= '1';
				
				nstate <= s_while_start_final;
				
			-- '[' je-li hodnota aktuální buòky NEnulová, skoèí za odpovídající pøíkaz '[' jinak pokraèuje následujícím znakem
			when s_while_end =>
				DATA_EN <= '1'; -- DATA_RDATA = RAM[PTR]
				DATA_WREN <= '0';
				
				nstate <= s_while_end_next;
			
			when s_while_end_next =>
				if DATA_RDATA = "0000000000" then -- if (ram[PTR] == 0)
					PC_inc <= '1'; -- PC ‹ PC + 1
				
					nstate <= s_fetch;
					
					else
						
						CNT_inc <= '1'; -- CNT ‹ 1
						PC_dec <= '1'; -- PC ‹ PC - 1
						
						nstate <= s_while_end_final;
				end if;
			
			when s_while_end_prefinal =>
			
					if CNT_reg /= "0000000000" then -- while (CNT != 0)
				
						if CODE_DATA = x"5D" then
							CNT_inc <= '1';
						
						elsif CODE_DATA = x"5B" then
							CNT_dec <= '1';
						
					end if;
					
					nstate <= s_while_end_final;
					
					else
						nstate <= s_fetch;
						
				end if;
				
			when s_while_end_final =>
			
				if CNT_reg /= "0000000000" then -- while (CNT != 0)
			
					PC_dec <= '1';
					else
						
						PC_inc <= '1';
				end if;
				
				nstate <= s_while_end_enable;
			
			when s_while_end_enable =>
				CODE_EN <= '1';
				
				nstate <= s_while_end_prefinal;
				
			-- '~' ukonèí právì provádìnou smyèku while
			when s_break =>
				CNT_inc <= '1';
				PC_inc <= '1';
				
				nstate <= s_break_enable;
			when s_break_enable =>
				CODE_EN <= '1';
				
				nstate <= s_break_final;
				
			when s_break_final =>
				if CNT_reg = "000000000" then 
					
					nstate <= s_fetch;
					
				else 
					if CODE_DATA = x"5B" then 
						CNT_inc <= '1'; 
					elsif CODE_DATA = x"5D" then 
						CNT_dec <= '1'; 
					end if;

					PC_inc <= '1'; 

					nstate <= s_break_enable;
				end if;
				
			-- null zastaví vykonávání programu 
			when s_null =>
				nstate <=s_null; -- PC ‹ PC
				
		end case;
	end process;
	--FSM next end

end behavioral;
