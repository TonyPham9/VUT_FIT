#include <stdio.h>
#include <unistd.h>
#include <string.h>
#include <sys/socket.h>
#include <netinet/in.h>
#include <stdlib.h>

// Funkce pro výpočet využití procesoru
int get_cpu_usage() {
    FILE *fp;
    char delim[] = " ";
    char cpu_usage_buffer_prev[BUFSIZ + 1];
    char cpu_usage_buffer[BUFSIZ + 1];
    //Nahraju si řádek s hodnotami do cpu_usage_buffer_prev
    fp = popen("cat /proc/stat | grep \"cpu\" | head -n 1 | awk '{ for(i=2; i<=NF; ++i) printf $i\"\"FS; print \"\" }'", "r");
    if (fp != NULL) {
        fread(cpu_usage_buffer_prev, sizeof(char), BUFSIZ, fp );
        pclose(fp);
    }
    //Hodnoty si rozdělím do pole
    int i = 0;
    char *p = strtok (cpu_usage_buffer_prev, delim);
    char *prev_array[10];

    while (p != NULL) {
        prev_array[i++] = p;
        p = strtok (NULL, delim);
    }

    //Počkám at získám nové hodnoty a opakuji předchozí kreoky
    sleep(1);

    fp = popen("cat /proc/stat | grep \"cpu\" | head -n 1 | awk '{ for(i=2; i<=NF; ++i) printf $i\"\"FS; print \"\" }'", "r");
    if (fp != NULL) {
        fread(cpu_usage_buffer, sizeof(char), BUFSIZ, fp );
        pclose(fp);
    }

    int j = 0;
    char *o = strtok (cpu_usage_buffer, delim);
    char *array[10];

    while (o != NULL) {
        array[j++] = o;
        o = strtok (NULL, delim);
    }

    // výpočty vzorečků
    unsigned long long previdle =  strtoul(prev_array[3], NULL, 10);
    unsigned long long previowait =  strtoul(prev_array[4], NULL, 10);
    unsigned long long PrevIdle = previdle + previowait;

    unsigned long long idle =  strtoul(array[3], NULL, 10);
    unsigned long long iowait =  strtoul(array[4], NULL, 10);
    unsigned long long Idle = idle + iowait;

    unsigned long long prevuser = strtoul(prev_array[0], NULL, 10);
    unsigned long long prevnice = strtoul(prev_array[1], NULL, 10);
    unsigned long long prevsystem = strtoul(prev_array[2], NULL, 10);
    unsigned long long previrq = strtoul(prev_array[5], NULL, 10);
    unsigned long long prevsoftirq = strtoul(prev_array[6], NULL, 10);
    unsigned long long prevsteal = strtoul(prev_array[7], NULL, 10);
    unsigned long long PrevNonIdle = prevuser + prevnice + prevsystem + previrq + prevsoftirq + prevsteal;

    unsigned long long user = strtoul(array[0], NULL, 10);
    unsigned long long nice = strtoul(array[1], NULL, 10);
    unsigned long long system = strtoul(array[2], NULL, 10);
    unsigned long long irq = strtoul(array[5], NULL, 10);
    unsigned long long softirq = strtoul(array[6], NULL, 10);
    unsigned long long steal = strtoul(array[7], NULL, 10);
    unsigned long long NonIdle = user + nice + system + irq + softirq + steal;

    unsigned long long PrevTotal = PrevIdle + PrevNonIdle;
    unsigned long long Total = Idle + NonIdle;

    unsigned long long totald = Total - PrevTotal;
    unsigned long long idled = Idle - PrevIdle;

    double final = (double)(totald - idled) / (double)totald * 100.00;
    final = (int)final;
    return final;
}

void get_Host_name(char *Host_name_final) {
    FILE *fp;
    fp = popen("cat /proc/sys/kernel/hostname", "r");
    if (fp != NULL) {
        fread(Host_name_final, sizeof(char), 512, fp );
    }
    pclose(fp);
}

void get_Cpu_name(char *cpu_buffer_final) {
    FILE *fp;
    fp = popen("cat /proc/cpuinfo | grep \"model name\" | head -n 1 | awk '{ for(i=4; i<=NF; ++i) printf $i\"\"FS; print \"\" }' ", "r");
    if (fp != NULL) {
        fread(cpu_buffer_final, sizeof(char), 512, fp );
    }
    pclose(fp);
}

//Server
int main( int argc, char *argv[]) {
    //port
    long PORT;
    char *ptr;
    //kontrola správnosti portu
    if (argc == 2) {
        PORT = strtol(argv[1], &ptr, 10);
    }else {
            fprintf(stderr,"Wrong port \n");
            return EXIT_FAILURE;
        }

    //nesmí se zadat nic víc arg s portem
    if (argc > 2) {
        fprintf(stderr,"Too many arguments \n");
        return EXIT_FAILURE;
    }

    char delim[] = " ";
    int CPU_Percentage;
    int server_socket;
    int opt_val = 1;
    struct sockaddr_in server_address;

    server_socket = socket(AF_INET, SOCK_STREAM, IPPROTO_TCP);
    if (server_socket <= 0) {
        perror("ERROR in socket");
        exit(EXIT_FAILURE);
    }

    setsockopt(server_socket, SOL_SOCKET, SO_REUSEADDR | SO_REUSEPORT, (const void *)&opt_val, sizeof(int));

    bzero((char *) &server_address, sizeof (server_address));
    server_address.sin_family = AF_INET;
    server_address.sin_addr.s_addr = htonl(INADDR_ANY);
    server_address.sin_port = htons( PORT );

    //Bind
    if( bind(server_socket,(struct sockaddr *)&server_address , sizeof(server_address)) < 0) {
        perror("ERROR in bind");
        exit(EXIT_FAILURE);
    }

    //Listen
    if( listen(server_socket , 1) < 0) {
        perror("ERROR in listen");
        exit(EXIT_FAILURE);
    }

    //Cyklus serveru
    while(1) {
        unsigned int server_address_len = sizeof(server_address);
        int comm_socket = accept(server_socket, (struct sockaddr *) &server_address, &server_address_len);
        char buff[1024] = {0};
        char msg[1024] = {0};
        char cpu_buffer_final[512] = {0};
        char Host_name_final[512] = {0};

        //pokud dojde k navázání komunikace
        if (comm_socket > 0) {
            recv(comm_socket, buff, 1024, 0);
            int i = 0;
            char test[1024];
            while(strcmp(&buff[i], "\n") == 0) {
                test[i] = buff[i];
                i++;
            }
            char *cmp = strtok(buff, delim);
            printf("%s", cmp);
            cmp = strtok(NULL, delim);
            printf("%s", test);

            if (strcmp(cmp, "/load") == 0) {
                CPU_Percentage = get_cpu_usage();
                sprintf(msg, "HTTP/1.1 200 OK\r\nContent-Type: text/plain;\r\n\r\n%d %%\n", CPU_Percentage);
                if (send(comm_socket, msg,strlen(msg), 0) < 0) {
                    perror("ERROR in send");
                    exit(EXIT_FAILURE);
                }
                close(comm_socket);

            } else if (strcmp(cmp, "/hostname") == 0) {
                get_Host_name(Host_name_final);
                sprintf(msg, "HTTP/1.1 200 OK\r\nContent-Type: text/plain;\r\n\r\n%s", Host_name_final);
                if (send(comm_socket, msg,strlen(msg), 0) < 0) {
                    perror("ERROR in send");
                    exit(EXIT_FAILURE);
                }
                close(comm_socket);

            } else if (strcmp(cmp, "/cpu-name") == 0) {
                get_Cpu_name(cpu_buffer_final);
                sprintf(msg, "HTTP/1.1 200 OK\r\nContent-Type: text/plain;\r\n\r\n%s", cpu_buffer_final);
                if (send(comm_socket, msg,strlen(msg), 0) < 0) {
                    perror("ERROR in send");
                    exit(EXIT_FAILURE);
                }

                close(comm_socket);
            } else {
                if (send(comm_socket,"HTTP/1.1 200 OK\r\nContent-Type: text/plain;\r\n\r\n 400 bad request",strlen("HTTP/1.1 200 OK\r\nContent-Type: text/plain;\r\n\r\n 400 bad request"), 0) < 0) {
                    perror("ERROR in send");
                    exit(EXIT_FAILURE);
                }
                close(comm_socket);
            }
        }
    }
    return 0;
}