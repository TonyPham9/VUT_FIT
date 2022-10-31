-- uart.vhd: UART controller - receiving part
-- Author(s): Tony Pham xphamt00
--
library ieee;
use ieee.std_logic_1164.all;
use ieee.std_logic_unsigned.all;

-------------------------------------------------
entity UART_RX is
port(	
  CLK: 	    in std_logic;
	RST: 	    in std_logic;
	DIN: 	    in std_logic;
	DOUT: 	    out std_logic_vector(7 downto 0);
	DOUT_VLD: 	out std_logic
);
end UART_RX;  

-------------------------------------------------
architecture behavioral of UART_RX is
signal CNT: std_logic_vector(4 downto 0);
signal CNT_SEC: std_logic_vector(3 downto 0);
signal RX_EN: std_logic;
signal CNT_EN: std_logic;
signal VAL_DAT: std_logic;
begin
  FSM: entity work.UART_FSM(behavioral)
  port map (
  CLK => CLK,
  RST => RST,
  DIN => DIN,
  CNT => cnt,
  CNT_SEC => cnt_sec,
  RX_EN => rx_en,
  CNT_EN => cnt_en,
  VAL_DAT => val_dat
  );
  process (CLK) begin
    if rising_edge(CLK) then 
      
      if val_dat = '1' then
        DOUT_VLD <= '1';
      else
        DOUT_VLD <= '0';
        end if;
        
      if cnt_en = '1' then
        cnt <= cnt + 1;
      else
        cnt <= "00000";
      end if;
      
      if cnt_sec = "1000" or rx_en = '0' then
        cnt_sec <= "0000";
      end if;
      
      if rx_en = '1' then
        --if cnt(4) = '1' then
        if cnt = "01111" or cnt = "10111" then
          cnt <= "00000";
          case cnt_sec is
            when "0000" => DOUT(0) <= DIN;
            when "0001" => DOUT(1) <= DIN;
            when "0010" => DOUT(2) <= DIN;
            when "0011" => DOUT(3) <= DIN;
            when "0100" => DOUT(4) <= DIN;
            when "0101" => DOUT(5) <= DIN;
            when "0110" => DOUT(6) <= DIN;
            when "0111" => DOUT(7) <= DIN;
            when others => null;
          end case;
          cnt_sec <= cnt_sec + 1;
          end if;
        end if;
    end if;
  end process;
end behavioral;
