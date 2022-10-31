; Vernamova sifra na architekture DLX
; Tony	Pham xphamt00
; xphamt00-r13-r23-r25-r26-r30-r0 p = +16 h = +8
        .data 0x04          ; zacatek data segmentu v pameti
login:  .asciiz "xphamt00"  ; <-- nahradte vasim loginem
cipher: .space 9 ; sem ukladejte sifrovane znaky (za posledni nezapomente dat 0)

        .align 2            ; dale zarovnavej na ctverice (2^2) bajtu
laddr:  .word login         ; 4B adresa vstupniho textu (pro vypis)
caddr:  .word cipher        ; 4B adresa sifrovaneho retezce (pro vypis)

        .text 0x40          ; adresa zacatku programu v pameti
        .global main        ; 

main:   ; sem doplnte reseni Vernamovy sifry dle specifikace v zadani

load:
	lb r23, login(r25) 	; na�tu znak
	sgti  r26,r23,96 	; porovn�m
	beqz r26, end 		; ukon�ovac� podm�nka
	nop
	j odd	;lich� v�tev
	nop

odd:				; lich� v�tev
	sgt r30,r30,r0
	bnez r30, even
	nop
	addi r23, r23, 16	; p�id�n� podle 'p'
	addi r30, r30, 1
	sgti r13, r23, 122	; kontrola p�ete�en�
	bnez r13, overflow	
	nop
	j store
	nop

even:				; sud� v�tev
	subi r23, r23, 8	; odebr�n� podle 'h'
	subi r30, r30, 1
        slti r13, r23, 96	; kontrola podte�en� 
	bnez r13, underflow	
	nop
	j store
	nop

overflow:			; p�ete�en�
	addi r13, r13, 121
	sub r23, r23, r13
	addi r23, r23, 96
	subi r13, r13, 122
	j store
	nop

underflow:			; podte�en�
	addi r13, r13, 121
	subi  r13, r13, 96
	add r23, r23,r13
	sgt r13,r0,r13
	j store
	nop  

store:				;ukl�d�n� v�sledku

	sb cipher(r25),r23	;v�sledek
	addi r25,r25, 1
	j load
	nop
	
end:    addi r14, r0, caddr ; <-- pro vypis sifry nahradte laddr adresou laddr
        trap 5  ; vypis textoveho retezce (jeho adresa se ocekava v r14)
        trap 0  ; ukonceni simulace
