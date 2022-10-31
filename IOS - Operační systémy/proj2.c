#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <sys/types.h>
#include <semaphore.h>
#include <fcntl.h>
#include <sys/mman.h>
#include <string.h>

// Ukazael na výstupní soubor
FILE *proj2out = NULL;

// Semafory
sem_t *write_file = NULL;
sem_t *queue = NULL;
sem_t *mutex = NULL;
sem_t *mutex_bar = NULL;
sem_t *vodik = NULL;
sem_t *kyslik = NULL;
sem_t *barrier = NULL;
sem_t *barrier2 = NULL;

//Parametry programu
int NO; // Počet kyslíků
int NH; //  Počet vodíků
int TI; // Maximální čas milisekundách, po který atom kyslíku/vodíku po svém vytvoření čeká, než se zařadí do fronty na vytváření molekul
int TB; // Maximální čas v milisekundách nutný pro vytvoření jedné molekuly. 0<=TB<=1000

//Sdílena paměť
typedef struct sh_memory{
    int radek;
    int H_in_Q;
    int O_in_Q;
    int count;
    int molecule;
    int H_zbytek;
    int O_zbytek;
}SH_MEMORY;
SH_MEMORY *memory = NULL;

void semafor_init() {
    //Pokud program předím spadnul
    sem_unlink("/xphamt00-write_file");
    sem_unlink("/xphamt00-queue");
    sem_unlink("/xphamt00-mutex");
    sem_unlink("/xphamt00-mutex_bar");
    sem_unlink("/xphamt00-vodik");
    sem_unlink("/xphamt00-kyslik");
    sem_unlink("/xphamt00-barrier");
    sem_unlink("/xphamt00-barrie2");

    // Vytváření semaforů
    if ((write_file = sem_open("/xphamt00-write_file", O_CREAT | O_EXCL, 0666, 1)) == SEM_FAILED) {
        fprintf(stderr,"Chyba při vytváření semaforu\n");
        exit(EXIT_FAILURE);
    }
    if ((queue = sem_open("/xphamt00-queue", O_CREAT | O_EXCL, 0666, 1)) == SEM_FAILED) {
        fprintf(stderr,"Chyba při vytváření semaforu\n");
        exit(EXIT_FAILURE);
    }
    if ((mutex = sem_open("/xphamt00-mutex", O_CREAT | O_EXCL, 0666, 1)) == SEM_FAILED) {
        fprintf(stderr,"Chyba při vytváření semaforu\n");
        exit(EXIT_FAILURE);
    }

    if ((mutex_bar = sem_open("/xphamt00-mutex_bar", O_CREAT | O_EXCL, 0666, 1)) == SEM_FAILED) {
        fprintf(stderr,"Chyba při vytváření semaforu\n");
        exit(EXIT_FAILURE);
    }
    if ((vodik = sem_open("/xphamt00-vodik", O_CREAT | O_EXCL, 0666, 1)) == SEM_FAILED) {
        fprintf(stderr,"Chyba při vytváření semaforu\n");
        exit(EXIT_FAILURE);
    }
    if ((kyslik = sem_open("/xphamt00-kyslik", O_CREAT | O_EXCL, 0666, 1)) == SEM_FAILED) {
        fprintf(stderr,"Chyba při vytváření semaforu\n");
        exit(EXIT_FAILURE);
    }
    if ((barrier = sem_open("/xphamt00-barrier", O_CREAT | O_EXCL, 0666, 1)) == SEM_FAILED) {
        fprintf(stderr,"Chyba při vytváření semaforu\n");
        exit(EXIT_FAILURE);
    }
    if ((barrier2 = sem_open("/xphamt00-barrier2", O_CREAT | O_EXCL, 0666, 1)) == SEM_FAILED) {
        fprintf(stderr,"Chyba při vytváření semaforu\n");
        exit(EXIT_FAILURE);
    }
    sem_init(vodik, 1,0);
    sem_init(kyslik, 1,0);
    sem_init(barrier, 1,0);
}

void finish_and_close() {
    sem_close(write_file);
    sem_close(queue);
    sem_close(mutex);
    sem_close(mutex_bar);
    sem_close(vodik);
    sem_close(kyslik);
    sem_close(barrier);
    sem_close(barrier2);
    sem_unlink("/xphamt00-write_file");
    sem_unlink("/xphamt00-queue");
    sem_unlink("/xphamt00-mutex");
    sem_unlink("/xphamt00-mutex_bar");
    sem_unlink("/xphamt00-vodik");
    sem_unlink("/xphamt00-kyslik");
    sem_unlink("/xphamt00-barrier");
    sem_unlink("/xphamt00-barrier2");
    int close = fclose(proj2out);
    if(close == EOF) {
        fprintf(stderr,"Chyba při zavírání souboru\n");
        exit(EXIT_FAILURE);
    }
    int result = munmap(memory, sizeof(SH_MEMORY));
    if(result == -1) {
        fprintf(stderr,"Chyba při mazání sdílené paměti\n");
        exit(EXIT_FAILURE);
    }
}

SH_MEMORY *create_mmap(size_t size) {
    return mmap(NULL, size, PROT_READ | PROT_WRITE, MAP_SHARED | MAP_ANONYMOUS,-1,0);
}

void check_args(int argc,int NO, int NH, int TI, int TB) {
    if (argc != 5) {
        fprintf(stderr,"Špatný počet argumentů\n");
        exit(EXIT_FAILURE);
    }
    if (NO < 0 || NH < 0){
        fprintf(stderr,"Vstup musí být větší jak nula\n");
        exit(EXIT_FAILURE);
    }
    if(TI < 0  || TI > 1000) {
        fprintf(stderr,"Vstup musí být v rozmezí 0 až 1000\n");
        exit(EXIT_FAILURE);
    }
    if(TB < 0  || TB > 1000) {
        fprintf(stderr,"Vstup musí být v rozmezí 0 až 1000\n");
        exit(EXIT_FAILURE);
    }
}
void file_init() {
    proj2out = fopen("proj2.out","w");

    if (proj2out == NULL) {
        fprintf(stderr,"Chyba při otvírání souboru. \n");
        exit(EXIT_FAILURE);
    }
}
//simulace vodíku nebo kyslíku jdoucí do fronty
void go_to_queue_H(int id) {
    usleep(((rand() % (TI + 1)))); // Spawning process in random time from interval
    sem_wait(queue);
    sem_wait(write_file);
    memory->radek++;
    fprintf(proj2out,"%d: H %d: going to queue\n",memory->radek, id);
    fflush(proj2out);
    sem_post(write_file);
    sem_post(queue);
}
void go_to_queue_O(int id) {
    usleep(((rand() % (TI + 1)))); // Spawning process in random time from interval
    sem_wait(queue);
    sem_wait(write_file);
    memory->radek++;
    fprintf(proj2out,"%d: O %d: going to queue\n",memory->radek, id);
    fflush(proj2out);
    sem_post(write_file);
    sem_post(queue);
}
void fnc_barrier(){
    sem_wait(mutex_bar);
    memory->count++;
    if (memory->count == 3) {
        sem_wait(barrier2);
        sem_post(barrier);
        memory->molecule++;

    }
    sem_post(mutex_bar);

    sem_wait(barrier);
    sem_post(barrier);

    sem_wait(mutex_bar);
    memory->count--;
    if (memory->count == 0) {
        sem_wait(barrier);
        sem_post(barrier2);

    }
    sem_post(mutex_bar);

    sem_wait(barrier2);
    sem_post(barrier2);

    sem_post(mutex_bar);
}
int main (int argc, char *argv[]) {
    NO = strtol(argv[1],NULL,10);
    NH = strtol(argv[2],NULL,10);
    TI = strtol(argv[3],NULL,10);
    TB = strtol(argv[4],NULL,10);
    check_args(argc,NO,NH,TI,TB);

    semafor_init();
    file_init();

    // Vytvořím Sdílenou paměť
    memory = create_mmap(sizeof(SH_MEMORY));
    if(memory == MAP_FAILED) {
        fprintf(stderr,"Chyby při vytváření sdílené paměti\n");
        exit(EXIT_FAILURE);
    }
    memory->molecule++;
    memory->H_zbytek = NH;
    memory->O_zbytek = NO;
        for (int i = 1; i<=NO; i++) {
            pid_t kyslik_p = fork();
            if (kyslik_p == 0) {
                //zapisování
                sem_wait(write_file);
                memory->radek++;
                fprintf(proj2out, "%d: O %d: started\n", memory->radek, i);
                fflush(proj2out);
                sem_post(write_file);

                go_to_queue_O(i);

                sem_wait(mutex);
                memory->O_in_Q++;
                if (memory->H_in_Q >= 2) {
                    sem_post(vodik);
                    sem_post(vodik);
                    memory->H_in_Q -=2;
                    memory->H_zbytek -=2;
                    sem_post(kyslik);
                    memory->O_in_Q -=1;
                    memory->O_zbytek -=1;
                }  else if ( memory->H_zbytek < 2 ) {
                    sem_wait(write_file);
                    memory->radek++;
                    fprintf(proj2out,"%d: O %d: not enough H\n",memory->radek,i);
                    fflush(proj2out);
                    sem_post(write_file);
                    sem_post(vodik);
                    sem_post(kyslik);
                    sem_post(mutex);
                    exit( 0 );
                }else {
                    sem_post(mutex);
                }
                sem_wait(kyslik);

                sem_wait(write_file);
                memory->radek++;
                fprintf(proj2out,"%d: O %d: creating molecule %d\n",memory->radek,i, memory->molecule);
                fflush(proj2out);
                sem_post(write_file);

                fnc_barrier();

                sem_wait(write_file);
                memory->radek++;
                fprintf(proj2out,"%d: O %d: molecule %d created\n",memory->radek,i, memory->molecule-1);
                fflush(proj2out);
                sem_post(write_file);

                sem_post(mutex);

                exit(EXIT_SUCCESS);
            } else if (kyslik_p < 0) { // Nelze vytvořit proces
                finish_and_close();
                fprintf(stderr, "Nešel vytvořit proces\n");
                exit(EXIT_FAILURE);
            }
        }
        for (int i = 1; i<=NH; i++) {
            pid_t vodik_p = fork();
            if (vodik_p == 0) {
                sem_wait(write_file);
                memory->radek++;
                fprintf(proj2out,"%d: H %d: started\n",memory->radek,i);
                fflush(proj2out);
                sem_post(write_file);

                go_to_queue_H(i);
                sem_wait(mutex);
                memory->H_in_Q++;
                if (memory->H_in_Q >= 2 && memory->O_in_Q >=1) {
                    sem_post(vodik);
                    sem_post(vodik);
                    memory->H_in_Q -=2;
                    memory->H_zbytek -=2;
                    sem_post(kyslik);
                    memory->O_in_Q -=1;
                    memory->O_zbytek -=1;

                } else if ( memory->H_zbytek < 2 || memory->O_zbytek == 0) {
                    sem_wait(write_file);
                    memory->radek++;
                    fprintf(proj2out,"%d: H %d: not enough H or O\n",memory->radek,i);
                    fflush(proj2out);
                    sem_post(write_file);
                    exit( 0 );
                }else {
                    sem_post(mutex);
                }
                sem_wait(vodik);

                sem_wait(write_file);
                memory->radek++;
                fprintf(proj2out,"%d: H %d: creating molecule %d\n",memory->radek,i, memory->molecule);
                fflush(proj2out);
                sem_post(write_file);

                fnc_barrier();

                sem_wait(write_file);
                memory->radek++;
                fprintf(proj2out,"%d: H %d: molecule %d created\n",memory->radek,i, memory->molecule-1);
                fflush(proj2out);
                sem_post(write_file);

                exit(EXIT_SUCCESS);
            } else if (vodik_p < 0) { // Nelze vytvořit proces
                finish_and_close();
                fprintf(stderr,"Nešel vytvořit proces\n");
                exit(EXIT_FAILURE);
            }
        }

    finish_and_close();
    return 0;
}
/*
sem_wait(mutex);
shared.oxygen += 1;
if ( shared.hydrogen >= HYDROGEN_AMOUNT ) {
sem_post(h_queue);
sem_post(h_queue);
shared.hydrogen -= 2;
shared.h_rem -= 2;
sem_post(o_queue);
shared.oxygen -= 1;
shared.o_rem -= 1;
} else if ( shared.h_rem < HYDROGEN_AMOUNT ) {
sem_wait(print_mutex);
print("%d: O %d: not enough H\n", ++shared.op_count, idO);
sem_post(print_mutex);
sem_post(o_queue);
sem_post(h_queue);
sem_post(mutex);
exit( 0 );
} else {
sem_post(mutex);
}
 */