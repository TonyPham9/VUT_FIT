#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <string.h>
#define SIZE 10241

/*	IZP projekt 1
 *	Tony Pham 
 *	xphamt00 
 *	Poznámka - vím, že tento program šel naprogramovat lépe pomocí funkcí, ale už jsem neměl moc čas to předělat. Omlouvám se za lehkou nečitelnost
 */
//funkce pro vypis delimu[0][1]
char delimy(char sloupec, char put_char)
{

	for (int i = 0; i < sloupec - 1; i++)
	{
		putchar(put_char);
	}
	return 0;
}
//funkce pro sort pole
void swap(int *xp, int *yp)
{
	int temp = *xp;
	*xp = *yp;
	*yp = temp;
}

void bubbleSort(int arr[], int n)
{
	int i, j;
	for (i = 0; i < n - 1; i++)

		for (j = 0; j < n - i - 1; j++)
			if (arr[j] > arr[j + 1])
				swap(&arr[j], &arr[j + 1]);
}

//MAIN
int main(int argc, char *argv[])

{

	int delka_ARGV2 = 1;
	char pole_delim[delka_ARGV2][1];
	//defaultne je mezera
	pole_delim[0][1] = ' ';
	//cteni oddelovace sloupcu
	//pokud najdu parametr -d 
	if (argv[1][0] == '-' && argv[1][1] == 'd')
	{
		delka_ARGV2 = strlen(argv[2]);
		//udelam si pole delimu
		for (int i = 0; i < delka_ARGV2; i++)
		{

			pole_delim[i][1] = argv[2][i];

			for (int j = 0; j < delka_ARGV2; j++)
			{

				if (pole_delim[j][1] == argv[2][i]) {}
				else
				{
					pole_delim[i][1] = argv[2][i];
				}
			}
		}
	}
	//odstranm duplikaty delimu
	for (int i = 0; i < delka_ARGV2; i++)
	{
		for (int j = i + 1; j < delka_ARGV2; j++)
		{

			if (pole_delim[i][1] == pole_delim[j][1])
			{

				for (int k = j; k < delka_ARGV2; k++)
				{
					pole_delim[k][1] = pole_delim[k + 1][1];
				}

				delka_ARGV2--;

				j--;
			}
		}
	}
	//pomocne promenne
	int sloupec = 1;
	int radek = 1;
	//AROW
	int pocetArow = 0;
	int arow;
	//DROW
	int parametrDROW;
	int pocetDrow = 0;
	int zapisDrow = 0;
	int inkrementaceDrow = 0;
	//DROWS
	int parametrDrowN = 0;
	int parametrDrowM = 0;
	//ICOL
	int parametrICOL;
	int pocetIcol = 0;
	int zapisIcol = 0;
	//ACOL
	int pocetAcol = 0;
	//DCOL
	int parametrDCOL;
	int pocetDcol = 0;
	int zapisDcol = 0;
	int poleDcols[sloupec];
	//DCOLS
	int pocetDcolsNM = 0;
	int poleDcolsNMfinal[sloupec];
	//ROWS
	int pocetRows = 0;
	int parametrROWSN;
	int parametrROWSM;
	int inkrementaceRows = 0;
	int rows = 0;
	//delim + radek
	char put_char = pole_delim[0][1];
	char NactRadek[SIZE];

	//ERROR
	int error = 0;
	//počítaní opakovaných argumentů
	//zjistím si kolikrat je argument zadan
	for (int i = 0; i < argc; i++)
	{
		//DROW
		if (strcmp(argv[i], "drow") == 0)
		{
			pocetDrow++;
		}
		//AROW
		if (strcmp(argv[i], "arow") == 0)
		{
			arow = 1;
			pocetArow++;
		}
		//ICOL
		if (strcmp(argv[i], "icol") == 0)
		{
			parametrICOL = atoi(argv[i + 1]);
			if (parametrICOL <= 0)
			{
				error = 1;
			}
			pocetIcol++;
		}
		//ACOL
		if (strcmp(argv[i], "acol") == 0)
		{
			pocetAcol++;
		}
		//DCOL
		if (strcmp(argv[i], "dcol") == 0)
		{
			parametrDCOL = atoi(argv[i + 1]);
			pocetDcol++;
		}
		//DCOLS N M
		if (strcmp(argv[i], "dcols") == 0)
		{
			int parametrDCOLN = atoi(argv[i + 1]);
			int parametrDCOLM = atoi(argv[i + 2]);
			if (parametrDCOLN <= 0 || parametrDCOLN <= 0 || parametrDCOLN > parametrDCOLM)
				error = 1;
			while (parametrDCOLN <= parametrDCOLM)
			{
				pocetDcolsNM++;
				parametrDCOLN++;
			}
		}
		//ROWS
		if (strcmp(argv[i], "rows") == 0)
		{
			rows = 1;
			parametrROWSN = atoi(argv[i + 1]);
			parametrROWSM = atoi(argv[i + 2]);
			if (*argv[i + 1] == '-' && *argv[i + 2] == '-' && parametrROWSN > 0 && parametrROWSM > 0)
			{
			 	// only_last_row = 1;
			}
			else if (parametrROWSN > 0 && *argv[i + 2] == '-' && parametrROWSM > 0)
			{
			 	//until_last = 1;
			}
			else
			{
				if (parametrROWSN <= 0 || parametrROWSM <= 0 || parametrROWSN > parametrROWSM)
					error = 1;
			}

			while (parametrROWSN <= parametrROWSM)
			{
				pocetRows++;
				parametrROWSN++;
			}
		}
	}
	//printf("pocet Rows: %d",pocetRows);
	//Promenne pro vice argumentu
	//vytvorim pole a nastavim poli hodnoty 

	//promenne poli
	int poleDrow[pocetDrow];
	int poleIcol[pocetIcol];
	int poleDcol[pocetDcol];
	int poleDcolsNM[pocetDcolsNM];
	int poleRows[pocetRows];

	for (int i = 0; i < argc; i++)
	{
		//DROW
		if (strcmp(argv[i], "drow") == 0)
		{
			parametrDROW = atoi(argv[i + 1]);
			if (parametrDROW <= 0)
			{
				error = 1;
			}
			poleDrow[zapisDrow] = parametrDROW;
			zapisDrow++;
		}

		//ICOL
		if (strcmp(argv[i], "icol") == 0)
		{
			parametrICOL = atoi(argv[i + 1]);
			if (parametrICOL <= 0)
				error = 1;
			poleIcol[zapisIcol] = parametrICOL;
			zapisIcol++;
		}

		//DCOL
		if (strcmp(argv[i], "dcol") == 0)
		{
			parametrDCOL = atoi(argv[i + 1]);
			parametrDCOL = atoi(argv[i + 1]);
			if (parametrDCOL <= 0)
				error = 1;
			poleDcol[zapisDcol] = parametrDCOL;
			zapisDcol++;
		}
		//DCOLS N M
		int count = 0;
		if (strcmp(argv[i], "dcols") == 0)
		{
			int parametrDCOLN = atoi(argv[i + 1]);
			int parametrDCOLM = atoi(argv[i + 2]);
			if (parametrDCOLN <= 0 || parametrDCOLN <= 0 || parametrDCOLN > parametrDCOLM)
				error = 1;
			while (parametrDCOLN <= parametrDCOLM)
			{
				poleDcolsNM[count] = parametrDCOLN;
				parametrDCOLN++;
				count++;
			}
		}

		//ROWS N M
		int count_rows = 0;
		if (strcmp(argv[i], "rows") == 0)
		{
			parametrROWSN = atoi(argv[i + 1]);
			parametrROWSM = atoi(argv[i + 2]);
			while ((parametrROWSN <= parametrROWSM) && parametrROWSM > 0)
			{
				poleRows[count_rows] = parametrROWSN;
				parametrROWSN++;
				count_rows++;
			}
		}
	}
	//sesortim pole 
	bubbleSort(poleDrow, pocetDrow);
	bubbleSort(poleIcol, pocetIcol);
	bubbleSort(poleDcol, pocetDcol);
	bubbleSort(poleDcolsNM, pocetDcolsNM);
	bubbleSort(poleRows, pocetRows);

	//hlavní while s nacitanim z stdin
	while (fgets(NactRadek, SIZE, stdin) != NULL)
	{
		char tmp[SIZE];
		int delka_radku = strlen(NactRadek);
		//sloupce mi staci zjistit jen z 1.radku
		if (radek <= 1)
		{
			for (int i = 0; i < delka_radku; i++)
			{
			 	//pocitani sloupcu
				for (int j = 0; j < delka_ARGV2; j++)
				{
					if (NactRadek[i] == pole_delim[j][1]) sloupec++;
				}
			}
		}

		//nahradim delimy za 1. delim
		for (int i = 0; i < delka_radku; i++)
		{
			//pocitani sloupcu
			for (int j = 0; j < delka_ARGV2; j++)
			{
				if (NactRadek[i] == pole_delim[j][1])
					if (NactRadek[i] != pole_delim[0][1])
						NactRadek[i] = pole_delim[0][1];
			}
		}

		//DCOL
		if (radek <= 1)
		{
			for (int i = 1; i <= sloupec; i++)
			{
				for (int j = 0; j < pocetDcol; j++)
				{
					if (poleDcol[j] == i)
					{
						poleDcols[i - 1] = poleDcol[j];
						break;
					}
					else
					{
						poleDcols[i - 1] = 0;
					}
				}
			}
		}

		//DCOLS
		if (radek <= 1)
		{
			for (int i = 1; i <= sloupec; i++)
			{
				for (int j = 0; j < pocetDcolsNM; j++)
				{
					if (poleDcolsNM[j] == i)
					{
						poleDcolsNMfinal[i - 1] = poleDcolsNM[j];
						break;
					}
					else
					{
						poleDcolsNMfinal[i - 1] = 0;
					}
				}
			}
		}

		if (rows == 0)
			poleRows[inkrementaceRows] = radek;
		//rows
		if (radek == poleRows[inkrementaceRows])
		{

			//FOR pro provedeni argumentu zpracování dat
			for (int i = 0; i < argc; i++)
			{

				//CSET
				if (strcmp(argv[i], "cset") == 0)
				{
					int parametrCSET = atoi(argv[i + 1]);
					if (parametrCSET <= 0)
						error = 1;
					int col = 1;
					char stringCSET[100];
					int j = 0;
					int p = 0;
					int last_row = 0;
					strcpy(stringCSET, argv[i + 2]);
					if (strlen(stringCSET) > 100)
						break;

					//cteni radku
					for (int i = 0; i < SIZE; i++)
					{

						//pocitani sloupcu
						for (int k = 0; k < delka_ARGV2; k++)
						{

							if (NactRadek[i] == pole_delim[k][1])
							{

								col++;
								//podminka pro 1. sloupec
								if (parametrCSET == 1)
								{
									for (int q = 0; q < 100; q++)
									{
										if (stringCSET[p] == '\0')
										{
											break;
										}
										tmp[j] = stringCSET[p];
										j++;
										p++;
									}
								}
								//vypisuju delim
								tmp[j] = NactRadek[i];
								j++;
								i++;
							}
						}

						//vypis pro parametr > 1
						if (col == parametrCSET)
						{
							for (int q = 0; q < 100; q++)
							{
								if (stringCSET[p] == '\0')
								{
									if (parametrCSET == sloupec)
									{
									 							//hledam posledni radek
										if (NactRadek[delka_radku - 1] != '\n')
										{
											last_row = 1;
										}
										if (last_row == 0)
										{
											tmp[j] = '\n';
											tmp[j + 1] = '\0';
											break;
										}
										else
										{
											tmp[j] = '\0';
										}
									}
									break;
								}
								tmp[j] = stringCSET[p];
								j++;
								p++;
							}
							if (parametrCSET == sloupec)
								break;
							//vypis puvodniho radku
						}
						else
						{
							tmp[j] = NactRadek[i];
							j++;
						}
					}
				}

				//TOLOWER C
				if (strcmp(argv[i], "tolower") == 0)
				{

					int parametrTOLOWER = atoi(argv[i + 1]);
					if (parametrTOLOWER <= 0)
						error = 1;
					int col = 1;
					int j = 0;
					//cteni radku
					for (int i = 0; i < SIZE; i++)
					{

						//pocitani sloupcu
						for (int k = 0; k < delka_ARGV2; k++)
						{
							if (NactRadek[i] == pole_delim[k][1])
							{

								tmp[j] = NactRadek[i];
								col++;
								i++;
								j++;
							}
						}

						if (col == parametrTOLOWER)
						{
							if (NactRadek[i] >= 'A' && NactRadek[i] <= 'Z')
							{
								tmp[j] = (NactRadek[i] + 32);
								j++;
							}
							else
							{
								tmp[j] = NactRadek[i];
								j++;
							}
						}
						else
						{
							tmp[j] = NactRadek[i];
							j++;
						}
					}
				}

				//TOUPPER C
				if (strcmp(argv[i], "toupper") == 0)
				{

					int parametrTOUPPER = atoi(argv[i + 1]);
					if (parametrTOUPPER <= 0)
						error = 1;
					int col = 1;
					int j = 0;
					//cteni radku
					for (int i = 0; i < SIZE; i++)
					{

						//pocitani sloupcu
						for (int k = 0; k < delka_ARGV2; k++)
						{
							if (NactRadek[i] == pole_delim[k][1])
							{

								tmp[j] = NactRadek[i];
								col++;
								i++;
								j++;
							}
						}

						if (col == parametrTOUPPER)
						{
							if (NactRadek[i] >= 'a' && NactRadek[i] <= 'z')
							{
								tmp[j] = (NactRadek[i] - 32);
								j++;
							}
							else
							{
								tmp[j] = NactRadek[i];
								j++;
							}
						}
						else
						{
							tmp[j] = NactRadek[i];
							j++;
						}
					}
				}

				//ROUND C
				if (strcmp(argv[i], "round") == 0)
				{
					int parametrROUND = atoi(argv[i + 1]);
					if (parametrROUND <= 0)
						error = 1;
					int col = 1;
					int j = 0;
					int m = 0;
					char string_number[100];

					//cteni radku
					for (int i = 0; i < SIZE; i++)
					{

						//pocitani sloupcu
						for (int k = 0; k < delka_ARGV2; k++)
						{
							if (NactRadek[i] == pole_delim[k][1])
							{

								col++;
								i++;
								j++;
							}
						}

						//ulozim si cislo se kterym budu pracovat do stringu
						if (col == parametrROUND)
						{
						 				//zapisu si do zvlastni promenne
							string_number[m] = NactRadek[i];
							m++;
						}
						else
						{

							j++;
						}
					}

					//zaokrouhlim cislo prevedu ho na string a resetuju promenne
					double number = atof(string_number);
					number = number + 0.50;
					int numberFinal = number;
					char string_number_final[100];
					int last_row = 0;
					sprintf(string_number_final, "%d", numberFinal);
					col = 1;
					j = 0;
					m = 0;

					//cteni radku
					for (int i = 0; i < SIZE; i++)
					{

						//pocitani sloupcu
						for (int k = 0; k < delka_ARGV2; k++)
						{
							if (NactRadek[i] == pole_delim[k][1])
							{
								col++;
								//podminka pro 1. sloupec 
								if (parametrROUND == 1)
								{
								 						//zapisu do sloupce nove cislo
									for (int q = 0; q < 100; q++)
									{
										if (string_number_final[m] == '\0')
										{
											break;
										}
										tmp[j] = string_number_final[m];
										j++;
										m++;
									}
								}
								//vypisuju delim
								tmp[j] = NactRadek[i];
								j++;
								i++;
							}
						}

						if (col == parametrROUND)
						{

							for (int q = 0; q < 100; q++)
							{
								if (string_number_final[m] == '\0')
								{
									if (parametrROUND == sloupec)
									{
									 							//hledam posledni radek
										if (NactRadek[delka_radku - 1] != '\n')
										{
											last_row = 1;
										}
										if (last_row == 0)
										{
											tmp[j] = '\n';
											tmp[j + 1] = '\0';
											break;
										}
										else
										{
											tmp[j] = '\0';
										}
									}
									break;
								}
								//zapisuuju cislo
								tmp[j] = string_number_final[m];
								j++;
								m++;
							}
							if (parametrROUND == sloupec)
								break;
							//vypis puvodniho radku
						}
						else
						{
							tmp[j] = NactRadek[i];
							j++;
						}
					}
				}

				//INT C
				if (strcmp(argv[i], "int") == 0)
				{
					int parametrINT = atoi(argv[i + 1]);
					if (parametrINT <= 0)
						error = 1;
					int col = 1;
					int j = 0;
					int m = 0;
					char string_number[100];

					//cteni radku
					for (int i = 0; i < SIZE; i++)
					{

						//pocitani sloupcu
						for (int k = 0; k < delka_ARGV2; k++)
						{
							if (NactRadek[i] == pole_delim[k][1])
							{

								col++;
								i++;
								j++;
							}
						}

						//ulozim si cislo se kterym budu pracovat do stringu
						if (col == parametrINT)
						{
						 				//zapisu si do zvlastni promenne
							string_number[m] = NactRadek[i];
							m++;
						}
						else
						{

							j++;
						}
					}

					//prevedu cislo na int prevedu ho na string a resetuju promenne
					int number = atoi(string_number);
					int numberFinal = number;
					char string_number_final[100];
					int last_row = 0;
					sprintf(string_number_final, "%d", numberFinal);
					col = 1;
					j = 0;
					m = 0;

					//cteni radku
					for (int i = 0; i < SIZE; i++)
					{

						//pocitani sloupcu
						for (int k = 0; k < delka_ARGV2; k++)
						{
							if (NactRadek[i] == pole_delim[k][1])
							{
								col++;
								//podminka pro 1. sloupec 
								if (parametrINT == 1)
								{
								 						//zapisu do sloupce nove cislo
									for (int q = 0; q < 100; q++)
									{
										if (string_number_final[m] == '\0')
										{
											break;
										}
										tmp[j] = string_number_final[m];
										j++;
										m++;
									}
								}
								//vypisuju delim
								tmp[j] = NactRadek[i];
								j++;
								i++;
							}
						}

						if (col == parametrINT)
						{

							for (int q = 0; q < 100; q++)
							{
								if (string_number_final[m] == '\0')
								{
									if (parametrINT == sloupec)
									{
									 							//hledam posledni radek
										if (NactRadek[delka_radku - 1] != '\n')
										{
											last_row = 1;
										}
										if (last_row == 0)
										{
											tmp[j] = '\n';
											tmp[j + 1] = '\0';
											break;
										}
										else
										{
											tmp[j] = '\0';
										}
									}
									break;
								}
								//zapisuuju cislo
								tmp[j] = string_number_final[m];
								j++;
								m++;
							}
							if (parametrINT == sloupec)
								break;
							//vypis puvodniho radku
						}
						else
						{
							tmp[j] = NactRadek[i];
							j++;
						}
					}
				}
				//COPY N M 
				if (strcmp(argv[i], "copy") == 0)
				{
					int parametrCOPYN = atoi(argv[i + 1]);
					int parametrCOPYM = atoi(argv[i + 2]);
					if (parametrCOPYN <= 0 || parametrCOPYM <= 0)
						error = 1;
					int col = 1;
					int j = 0;
					int m = 0;
					int p = 0;
					char string_copy[100];
					//vyprazdnuju string
					strncpy(string_copy, "", sizeof(string_copy));

					//cteni radku
					for (int i = 0; i < SIZE; i++)
					{
					 			//pocitani sloupcu
						for (int k = 0; k < delka_ARGV2; k++)
						{
							if (NactRadek[i] == pole_delim[k][1])
							{

								col++;
								i++;
							}
						}

						//ulozim si bunku se kterou budu pracovat do stringu
						if (col == parametrCOPYN)
						{

							//zapisu si do zvlastni promenne
							if (NactRadek[i] == '\n') continue;
							//if(parametrCOPYN == sloupec && NactRadek[i] == '\0') break;
							string_copy[m] = NactRadek[i];
							m++;
						}
					}

					//resetuju promenne
					int last_row = 0;
					col = 1;

					//cteni radku
					for (int i = 0; i < SIZE; i++)
					{

						//pocitani sloupcu
						for (int k = 0; k < delka_ARGV2; k++)
						{

							if (NactRadek[i] == pole_delim[k][1])
							{

								col++;
								//podminka pro 1. sloupec
								if (parametrCOPYM == 1)
								{
									for (int q = 0; q < 100; q++)
									{
										if (string_copy[p] == '\0')
										{
											break;
										}
										tmp[j] = string_copy[p];
										j++;
										p++;
									}
								}
								//vypisuju delim
								tmp[j] = NactRadek[i];
								j++;
								i++;
							}
						}

						//vypis pro parametr > 1
						if (col == parametrCOPYM)
						{
							for (int q = 0; q < 100; q++)
							{
								if (string_copy[p] == '\0')
								{
									if (parametrCOPYM == sloupec)
									{
									 							//hledam posledni radek
										if (NactRadek[delka_radku - 1] != '\n')
										{
											last_row = 1;
										}
										if (last_row == 0)
										{
											tmp[j] = '\n';
											tmp[j + 1] = '\0';
											break;
										}
										else
										{
											tmp[j] = '\0';
										}
									}
									break;
								}
								tmp[j] = string_copy[p];
								j++;
								p++;
							}
							if (parametrCOPYM == sloupec)
								break;
							//vypis puvodniho radku
						}
						else
						{
							tmp[j] = NactRadek[i];
							j++;
						}
					}
				}

				//swap N M - problém když přehazuju poslední s jakýmkoliv
				if (strcmp(argv[i], "swap") == 0)
				{
					int parametrSWAPN = atoi(argv[i + 1]);
					int parametrSWAPM = atoi(argv[i + 2]);
					if (parametrSWAPN <= 0 || parametrSWAPM <= 0)
						error = 1;
					if (parametrSWAPN == parametrSWAPM)
					{
						strcpy(tmp, NactRadek);
						break;
					}
					if (parametrSWAPN > sloupec || parametrSWAPM > sloupec)
					{
						strcpy(tmp, NactRadek);
						break;
					}
					int col = 1;
					int j = 0;
					int m = 0;
					int p = 0;
					char string_swap_N[100];
					char string_swap_M[100];
					//vyprazdnuju string
					strncpy(string_swap_N, "", sizeof(string_swap_N));
					strncpy(string_swap_M, "", sizeof(string_swap_M));

					//cteni radku
					for (int i = 0; i < SIZE; i++)
					{
					 			//pocitani sloupcu
						for (int k = 0; k < delka_ARGV2; k++)
						{
							if (NactRadek[i] == pole_delim[k][1])
							{

								col++;
								i++;
							}
						}

						//ulozim si bunku se kterou budu pracovat do stringu
						if (col == parametrSWAPN)
						{
						 				//zapisu si do zvlastni promenne
							if (NactRadek[i] == '\n') continue;
							if (NactRadek[i] == '\0') break;
							string_swap_N[m] = NactRadek[i];
							m++;
						}

						//zapisu si do zvlastni promenne
						if (col == parametrSWAPM)
						{
							if (NactRadek[i] == '\n') continue;
							if (NactRadek[i] == '\0') break;
							string_swap_M[p] = NactRadek[i];
							p++;
						}
					}

					//resetuju promenne
					int last_row = 0;
					col = 1;
					m = 0;
					p = 0;

					//cteni radku
					for (int i = 0; i < SIZE; i++)
					{

						//pocitani sloupcu
						for (int k = 0; k < delka_ARGV2; k++)
						{
							if (NactRadek[i] == pole_delim[k][1])
							{

								col++;
								//podminka pro 1. sloupec 
								if (parametrSWAPN == 1)
								{
								 						//zapisu do sloupce string z N
									for (int q = 0; q < 100; q++)
									{

										if (string_swap_M[m] == '\0')
										{
											break;
										}

										tmp[j] = string_swap_M[m];
										j++;
										m++;
									}
								}
								if (parametrSWAPM == 1)
								{
								 						//zapisu do sloupce string z N
									for (int q = 0; q < 100; q++)
									{

										if (string_swap_N[p] == '\0')
										{
											break;
										}

										tmp[j] = string_swap_N[p];
										j++;
										p++;
									}
								}
								//vypisuju delim
								tmp[j] = NactRadek[i];
								j++;
								i++;
							}
						}

						if (col == parametrSWAPN)
						{

							for (int q = 0; q < 100; q++)
							{
								if (string_swap_M[m] == '\0')
								{
									if (parametrSWAPN == sloupec)
									{
									 							//hledam posledni radek
										if (NactRadek[delka_radku - 1] != '\n')
										{
											last_row = 1;
										}
										if (last_row == 0)
										{
											tmp[j] = '\n';
											tmp[j + 1] = '\0';
											break;
										}
										else
										{
											tmp[j] = '\0';
										}
									}
									break;
								}
								//zapisuuju string z N
								tmp[j] = string_swap_M[m];
								j++;
								m++;
							}
							if (parametrSWAPN == sloupec)
								break;
							//vypis puvodniho radku
						}
						else if (col == parametrSWAPM)
						{

							for (int q = 0; q < 100; q++)
							{
								if (string_swap_N[p] == '\0')
								{
									if (parametrSWAPM == sloupec)
									{
									 							//hledam posledni radek
										if (NactRadek[delka_radku - 1] != '\n')
										{
											last_row = 1;
										}
										if (last_row == 0)
										{
											tmp[j] = '\n';
											tmp[j + 1] = '\0';
											break;
										}
										else
										{
											tmp[j] = '\0';
										}
									}
									break;
								}
								//zapisuuju string z N
								tmp[j] = string_swap_N[p];
								j++;
								p++;
							}
							if (parametrSWAPM == sloupec)
								break;
							//vypis puvodniho radku
						}
						else
						{
							tmp[j] = NactRadek[i];
							j++;
						}
					}
				}

				//MOVE N M
				if (strcmp(argv[i], "move") == 0)
				{
					int parametrMOVEN = atoi(argv[i + 1]);
					int parametrMOVEM = atoi(argv[i + 2]);
					if (parametrMOVEN <= 0 || parametrMOVEM <= 0)
						error = 1;

					if (parametrMOVEN > sloupec || parametrMOVEM > sloupec)
					{
						strcpy(tmp, NactRadek);
						break;
					}

					if (parametrMOVEN == parametrMOVEM)
					{
						strcpy(tmp, NactRadek);
						break;
					}
				}
			}
			inkrementaceRows++;
		}
		else
		{
			strcpy(tmp, NactRadek);
		}

		//FOR pro provedeni argumentu uprav tabulky
		for (int i = 0; i < argc; i++)
		{

			if (strcmp(argv[i], "irow") == 0 || strcmp(argv[i], "drow") == 0 || strcmp(argv[i], "drows") == 0 || strcmp(argv[i], "acol") == 0 || strcmp(argv[i], "arow") == 0 || strcmp(argv[i], "icol") == 0)
				strcpy(tmp, NactRadek);
			//IROW R
			if (strcmp(argv[i], "irow") == 0)
			{
				int parametrIROW;
				parametrIROW = atoi(argv[i + 1]);
				if (parametrIROW <= 0)
				{
					error = 1;
				}
				if (parametrIROW == radek)
				{
					delimy(sloupec, put_char);
					printf("\n");
				}
			}
			//DROW
			if (strcmp(argv[i], "drow") == 0)
			{

				if (poleDrow[inkrementaceDrow] == radek)
				{
					tmp[0] = '\0';
					inkrementaceDrow++;
					strcpy(NactRadek, tmp);
				}
			}
			//DROWS N M 
			if (strcmp(argv[i], "drows") == 0)
			{
				parametrDrowN = atoi(argv[i + 1]);
				parametrDrowM = atoi(argv[i + 2]);

				if (parametrDrowN <= 0 || parametrDrowN > parametrDrowM)
				{
					error = 1;
				}
				if (parametrDrowN == parametrDrowM)
				{
					if (radek == parametrDrowN)
					{
						tmp[0] = '\0';
					}
				}
				else
				{
					if ((radek >= parametrDrowN) && (radek <= parametrDrowM))
					{

						tmp[0] = '\0';
						strcpy(NactRadek, tmp);
					}
				}
			}
			//ICOL
			if (strcmp(argv[i], "icol") == 0)
			{
				int inkrementaceIcol = 0;
				int col = 1;
				for (int i = 0; i < SIZE; i++)
				{

					for (int k = 0; k < delka_ARGV2; k++)
					{
						if (NactRadek[i] == pole_delim[k][1])
						{

							col++;
						}
					}

					if (poleIcol[inkrementaceIcol] == 1)
					{
						tmp[i] = pole_delim[0][1];
						inkrementaceIcol++;

						for (int j = i; j < SIZE; j++)
						{
							tmp[j + 1] = NactRadek[j];
						}
					}
					else
					{
						if (poleIcol[inkrementaceIcol] == col)
						{

							tmp[i] = pole_delim[0][1];
							inkrementaceIcol++;
							col++;

							for (int j = i; j < SIZE; j++)
							{
								tmp[j + 1] = NactRadek[j];
							}
						}
					}
				}
			}

			//ACOL
			if (strcmp(argv[i], "acol") == 0)
			{
				strcpy(tmp, NactRadek);
				for (int i = 0; i < pocetAcol; i++)
				{

					for (int i = 0; i < SIZE; i++)
					{

						if (tmp[i] == '\0')
						{
							if (tmp[i - 1] == '\n')
							{
								tmp[i - 1] = pole_delim[0][1];
								tmp[i] = '\n';
								tmp[i + 1] = '\0';
								break;
							}
							else
							{
								tmp[i] = pole_delim[0][1];
								tmp[i + 1] = '\0';
								break;
							}
						}
					}
					strcpy(NactRadek, tmp);
					break;
				}
			}

			//DCOL 
			if (strcmp(argv[i], "dcol") == 0)
			{
				strcpy(tmp, NactRadek);
				int delete_rows = 0;
				for (int t = 0; t < pocetDcol; t++)
				{
					if (poleDcols[t] != 0)
						delete_rows++;
				}
				if (delete_rows == sloupec)
				{
					tmp[0] = '\0';
					break;
				}
				int col = 1;
				int j = 0;
				int inkrementaceDcol = 0;
				int last_row = 0;
				for (int i = 0; i < SIZE; i++)
				{

					if (poleDcols[inkrementaceDcol] == col)
					{
						if (poleDcols[sloupec - 1] == sloupec && sloupec == col)
						{
						 				//hledam posledni radek
							if (NactRadek[delka_radku - 1] != '\n')
							{
								last_row = 1;
							}
							if (last_row == 0)
							{
								tmp[j - 1] = '\n';
								tmp[j] = '\0';
								break;
							}
							else
							{
								tmp[j - 1] = '\0';
							}
						}
					}
					else
					{

						//zapis do tmp pole
						tmp[j] = NactRadek[i];
						j++;
					}
					for (int k = 0; k < delka_ARGV2; k++)
					{
						if (tmp[i] == pole_delim[k][1])
						{
							col++;
							inkrementaceDcol++;
						}
					}
				}
			}
			//DCOlS N M  
			if (strcmp(argv[i], "dcols") == 0)
			{
				strcpy(tmp, NactRadek);
				int delete_rows = 0;
				for (int t = 0; t < pocetDcolsNM; t++)
				{
					if (poleDcols[t] != 0)
						delete_rows++;
				}
				if (delete_rows == sloupec)
				{
					tmp[0] = '\0';
					break;
				}
				int col = 1;
				int j = 0;
				int inkrementaceDcols = 0;
				int last_row = 0;
				for (int i = 0; i < SIZE; i++)
				{

					if (poleDcolsNMfinal[inkrementaceDcols] == col)
					{
						if (poleDcolsNMfinal[sloupec - 1] == sloupec && sloupec == col)
						{

							//hledam posledni radek
							if (NactRadek[delka_radku - 1] != '\n')
							{
								last_row = 1;
							}
							if (last_row == 0)
							{
								tmp[j - 1] = '\n';
								tmp[j] = '\0';
								break;
							}
							else
							{
								tmp[j - 1] = '\0';
							}
						}
					}
					else
					{

						//zapis do tmp pole
						tmp[j] = NactRadek[i];
						j++;
					}
					for (int k = 0; k < delka_ARGV2; k++)
					{
						if (tmp[i] == pole_delim[k][1])
						{
							col++;
							inkrementaceDcols++;
						}
					}
				}
			}
		}

		strcpy(NactRadek, tmp);
		if (error == 1)
		{
			fprintf(stderr, "Byl zadan spatny parametr\n");
			return 1;
			//break;
		}
		//vypis
		printf("%s", NactRadek);

		//pocitani radku
		radek++;
	}	//konec hlavního whilu	
	//Arow + kdyz jich je vic
	for (int i = 0; i < pocetArow; i++)
	{
		if (arow == 1)
		{
			printf("\n");
			delimy(sloupec, put_char);
		}
	}

	return 0;

}

