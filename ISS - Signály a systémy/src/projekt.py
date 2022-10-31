# -*- coding: utf-8 -*-

# -- Sheet --

import numpy as np
import matplotlib.pyplot as plt
from scipy.io import wavfile
from scipy import signal
from scipy.signal import spectrogram, lfilter, freqz, tf2zpk

#1. ukol
#nacteni souboru
fs, data = wavfile.read('xphamt00.wav')
data = data[:data.size]
t = np.arange(data.size) / fs
#vypis grafu
plt.figure(figsize=(10, 5))
plt.plot(t, data)
plt.gca().set_xlabel('$t [s]$')
plt.gca().set_title('Ukol 1')
plt.tight_layout()
plt.show()

print("Delka signalu:", data.size / fs, "[s]")
print("Pocet vzorku signalu:", data.size)
print("Minimální hodnota:", np.min(data))
print("Maximální hodnota:", np.max(data))

#stredni hodnota
meanValue = np.mean(data)

print("Stredni hodnota:", meanValue)

data = data - meanValue

#normalizace
data = data / max(abs(np.min(data)),np.max(data))

frame_size = 1024
i = 0
overlap = 512

frames = np.array([data[i*overlap:i*overlap + frame_size] for i in range(len(data) //overlap - frame_size//overlap + 1)])

t = np.arange(len(frames[51])) / fs
plt.figure(figsize=(10, 5))
plt.plot(t, frames[51])
plt.gca().set_xlabel('$t [s]$')
plt.gca().set_title('Ukol 2')
plt.show()

def DFT(x):
    
    N = len(x)
    n = np.arange(N)
    k = n.reshape((N, 1))
    e = np.exp(-2j * np.pi * k * n / N)
    
    X = np.dot(e, x)
    
    return X

X = DFT(frames[51])

# calculate the frequency
N = len(X)
n = np.arange(N/2)

plt.figure(figsize = (10, 5))
plt.plot(n/frame_size*fs, abs(X)[:frame_size//2])
plt.xlabel('Freq (Hz)')
plt.ylabel('DFT Amplitude |X(freq)|')
plt.gca().set_title('Ukol 3')
plt.show()

Y = np.fft.fft(frames[51])
plt.figure(figsize = (10, 5))
plt.plot(n/frame_size*fs, abs(Y)[:frame_size//2])
plt.xlabel('Freq (Hz)')
plt.ylabel('DFT Amplitude |X(freq)|')
plt.gca().set_title('Ukol 3 Porovnani')
plt.show()
print("Porovnání grafů, jestli jsou stejný:", np.allclose(X,Y))

f, t, sgr = spectrogram(data, fs, nperseg=frame_size, noverlap=overlap)
sgr_log = 10 * np.log10(sgr**2) 
plt.figure(figsize=(18,6))
plt.pcolormesh(t,f,sgr_log,shading="gouraud")
plt.gca().set_xlabel('Čas [s]')
plt.gca().set_ylabel('Frekvence [Hz]')
plt.gca().set_title('Ukol 4')
cbar = plt.colorbar()
cbar.set_label('Spektralní hustota výkonu [dB]', rotation=270, labelpad=15)

print("Ukol 5: Dělal jsem to ručně. Přiloži jsem pravítko k monitoru -> 1000hz = 2cm:")
print("f1 = 1,9cm = 950 hz")
print("f2 = 3,8cm = 1900 hz")
print("f3 = 5,7cm = 2850 hz")
print("f4 = 7,6cm = 3800 hz")


cos_1 = np.cos(2*np.pi * 950 * np.arange(data.size) / fs)
cos_2 = np.cos(2*np.pi * 1900 * np.arange(data.size) / fs)
cos_3 = np.cos(2*np.pi * 2850 * np.arange(data.size) / fs)
cos_4 = np.cos(2*np.pi * 3800 * np.arange(data.size) / fs)

final_cos = cos_1 + cos_2 + cos_3 + cos_4
wavfile.write("../audio/4cos.wav", fs, final_cos)

f_cos, t_cos, sgr_cos = spectrogram(final_cos, fs, nperseg=frame_size, noverlap=overlap)
sgr_log = 10 * np.log10(sgr_cos**2) 
plt.figure(figsize=(18,6))
plt.pcolormesh(t,f,sgr_log,shading="gouraud")
plt.gca().set_xlabel('Čas [s]')
plt.gca().set_ylabel('Frekvence [Hz]')
plt.gca().set_title('Ukol 6')
cbar = plt.colorbar()
cbar.set_label('Spektralní hustota výkonu [dB]', rotation=270, labelpad=15)

def butter_bandstop(lowcut, highcut, sampling_rate, order=3):
    nyq = 0.5 * sampling_rate
    low = lowcut / nyq
    high = highcut / nyq
    b, a = signal.butter(order, [low, high], btype='bandstop')
    return a, b

# impulsni odezva
N_imp = 32
imp = [1, *np.zeros(N_imp-1)]

a, b = butter_bandstop(900, 1000, fs)
h = lfilter(b, a, imp)

plt.figure(figsize=(10,5))
plt.stem(np.arange(N_imp), h, basefmt=' ')
plt.gca().set_xlabel('$n$')
plt.gca().set_title('Impulsní odezva $h[n]$')

plt.grid(alpha=0.5, linestyle='--')

plt.tight_layout()
print("Koeficien a:", a)
print("Koeficien b:", b)

a, b = butter_bandstop(1850, 1950, fs)
h = lfilter(b, a, imp)

plt.figure(figsize=(10,5))
plt.stem(np.arange(N_imp), h, basefmt=' ')
plt.gca().set_xlabel('$n$')
plt.gca().set_title('Impulsní odezva $h[n]$')

plt.grid(alpha=0.5, linestyle='--')

plt.tight_layout()
print("Koeficien a:", a)
print("Koeficien b:", b)

a, b = butter_bandstop(2800, 2900, fs)
h = lfilter(b, a, imp)

plt.figure(figsize=(10,5))
plt.stem(np.arange(N_imp), h, basefmt=' ')
plt.gca().set_xlabel('$n$')
plt.gca().set_title('Impulsní odezva $h[n]$')

plt.grid(alpha=0.5, linestyle='--')

plt.tight_layout()
print("Koeficien a:", a)
print("Koeficien b:", b)

a, b = butter_bandstop(3750, 3850, fs)
h = lfilter(b, a, imp)

plt.figure(figsize=(10,5))
plt.stem(np.arange(N_imp), h, basefmt=' ')
plt.gca().set_xlabel('$n$')
plt.gca().set_title('Impulsní odezva $h[n]$')

plt.grid(alpha=0.5, linestyle='--')

plt.tight_layout()
print("Koeficien a:", a)
print("Koeficien b:", b)

a, b = butter_bandstop(900, 1000, fs)
z, p, k = tf2zpk(b, a)
plt.figure(figsize=(8,7.6))

# jednotkova kruznice
ang = np.linspace(0, 2*np.pi,100)
plt.plot(np.cos(ang), np.sin(ang))

# nuly, poly
plt.scatter(np.real(z), np.imag(z), marker='o', facecolors='none', edgecolors='r', label='nuly')
plt.scatter(np.real(p), np.imag(p), marker='x', color='g', label='póly')

plt.gca().set_xlabel('Realná složka $\mathbb{R}\{$z$\}$')
plt.gca().set_ylabel('Imaginarní složka $\mathbb{I}\{$z$\}$')

plt.grid(alpha=0.5, linestyle='--')
plt.legend(loc='upper right')

plt.tight_layout()

a, b = butter_bandstop(1850, 1950, fs)
z, p, k = tf2zpk(b, a)
plt.figure(figsize=(8,7.6))

# jednotkova kruznice
ang = np.linspace(0, 2*np.pi,100)
plt.plot(np.cos(ang), np.sin(ang))

# nuly, poly
plt.scatter(np.real(z), np.imag(z), marker='o', facecolors='none', edgecolors='r', label='nuly')
plt.scatter(np.real(p), np.imag(p), marker='x', color='g', label='póly')

plt.gca().set_xlabel('Realná složka $\mathbb{R}\{$z$\}$')
plt.gca().set_ylabel('Imaginarní složka $\mathbb{I}\{$z$\}$')

plt.grid(alpha=0.5, linestyle='--')
plt.legend(loc='upper right')

plt.tight_layout()

a, b = butter_bandstop(2800, 2900, fs)
z, p, k = tf2zpk(b, a)
plt.figure(figsize=(8,7.6))

# jednotkova kruznice
ang = np.linspace(0, 2*np.pi,100)
plt.plot(np.cos(ang), np.sin(ang))

# nuly, poly
plt.scatter(np.real(z), np.imag(z), marker='o', facecolors='none', edgecolors='r', label='nuly')
plt.scatter(np.real(p), np.imag(p), marker='x', color='g', label='póly')

plt.gca().set_xlabel('Realná složka $\mathbb{R}\{$z$\}$')
plt.gca().set_ylabel('Imaginarní složka $\mathbb{I}\{$z$\}$')

plt.grid(alpha=0.5, linestyle='--')
plt.legend(loc='upper right')

plt.tight_layout()

a, b = butter_bandstop(3750, 3850, fs)
z, p, k = tf2zpk(b, a)
plt.figure(figsize=(8,7.6))

# jednotkova kruznice
ang = np.linspace(0, 2*np.pi,100)
plt.plot(np.cos(ang), np.sin(ang))

# nuly, poly
plt.scatter(np.real(z), np.imag(z), marker='o', facecolors='none', edgecolors='r', label='nuly')
plt.scatter(np.real(p), np.imag(p), marker='x', color='g', label='póly')

plt.gca().set_xlabel('Realná složka $\mathbb{R}\{$z$\}$')
plt.gca().set_ylabel('Imaginarní složka $\mathbb{I}\{$z$\}$')

plt.grid(alpha=0.5, linestyle='--')
plt.legend(loc='upper right')

plt.tight_layout()

a, b = butter_bandstop(900, 1000, fs)

# filtrace
sf = lfilter(b, a, data)
f, t, sfgr = spectrogram(sf, fs)
sfgr_log = 10 * np.log10(sfgr+1e-20)

w, H = freqz(b, a)
_, ax = plt.subplots(1, 2, figsize=(10,5))

ax[0].plot(w / 2 / np.pi * fs, np.abs(H))
ax[0].set_xlabel('Frekvence [Hz]')
ax[0].set_title('Modul frekvenční charakteristiky $|H(e^{j\omega})|$')

ax[1].plot(w / 2 / np.pi * fs, np.angle(H))
ax[1].set_xlabel('Frekvence [Hz]')
ax[1].set_title('Argument frekvenční charakteristiky $\mathrm{arg}\ H(e^{j\omega})$')

for ax1 in ax:
    ax1.grid(alpha=0.5, linestyle='--')

plt.tight_layout()

a, b = butter_bandstop(1850, 1950, fs)

# filtrace
sf = lfilter(b, a, sf)
f, t, sfgr = spectrogram(sf, fs)
sfgr_log = 10 * np.log10(sfgr+1e-20)

w, H = freqz(b, a)
_, ax = plt.subplots(1, 2, figsize=(10,5))

ax[0].plot(w / 2 / np.pi * fs, np.abs(H))
ax[0].set_xlabel('Frekvence [Hz]')
ax[0].set_title('Modul frekvenční charakteristiky $|H(e^{j\omega})|$')

ax[1].plot(w / 2 / np.pi * fs, np.angle(H))
ax[1].set_xlabel('Frekvence [Hz]')
ax[1].set_title('Argument frekvenční charakteristiky $\mathrm{arg}\ H(e^{j\omega})$')

for ax1 in ax:
    ax1.grid(alpha=0.5, linestyle='--')

plt.tight_layout()

a, b = butter_bandstop(2800, 2900, fs)

# filtrace
sf = lfilter(b, a, sf)
f, t, sfgr = spectrogram(sf, fs)
sfgr_log = 10 * np.log10(sfgr+1e-20)

w, H = freqz(b, a)
_, ax = plt.subplots(1, 2, figsize=(10,5))

ax[0].plot(w / 2 / np.pi * fs, np.abs(H))
ax[0].set_xlabel('Frekvence [Hz]')
ax[0].set_title('Modul frekvenční charakteristiky $|H(e^{j\omega})|$')

ax[1].plot(w / 2 / np.pi * fs, np.angle(H))
ax[1].set_xlabel('Frekvence [Hz]')
ax[1].set_title('Argument frekvenční charakteristiky $\mathrm{arg}\ H(e^{j\omega})$')

for ax1 in ax:
    ax1.grid(alpha=0.5, linestyle='--')

plt.tight_layout()

a, b = butter_bandstop(3750, 3850, fs)

# filtrace
sf = lfilter(b, a, sf)
f, t, sfgr = spectrogram(sf, fs)
sfgr_log = 10 * np.log10(sfgr+1e-20)

w, H = freqz(b, a)
_, ax = plt.subplots(1, 2, figsize=(10,5))

ax[0].plot(w / 2 / np.pi * fs, np.abs(H))
ax[0].set_xlabel('Frekvence [Hz]')
ax[0].set_title('Modul frekvenční charakteristiky $|H(e^{j\omega})|$')

ax[1].plot(w / 2 / np.pi * fs, np.angle(H))
ax[1].set_xlabel('Frekvence [Hz]')
ax[1].set_title('Argument frekvenční charakteristiky $\mathrm{arg}\ H(e^{j\omega})$')

for ax1 in ax:
    ax1.grid(alpha=0.5, linestyle='--')

plt.tight_layout()


plt.figure(figsize=(18,6))
plt.pcolormesh(t,f,sfgr_log,shading="gouraud")
plt.gca().set_title('Spektrogram vyfiltrovaného signálu')
plt.gca().set_xlabel('Čas [s]')
plt.gca().set_ylabel('Frekvence [Hz]')
cbar = plt.colorbar()
cbar.set_label('Spektralní hustota výkonu [dB]', rotation=270, labelpad=15)

plt.tight_layout()

wavfile.write("../audio/clean_bandstop.wav", fs, sf)
t = np.arange(sf.size) / fs
plt.figure(figsize=(10, 5))
plt.plot(t, sf)
plt.gca().set_xlabel('$t [s]$')
plt.gca().set_title('Ukol 10')
plt.tight_layout()
plt.show()

