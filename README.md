# 🚀 ManagerOne

Un'applicazione completa per la gestione di progetti e task costruita con Laravel e Livewire. Permette di organizzare team, assegnare tasks, tracciare progressi e collaborare in tempo reale.

![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

---

## 📋 Indice

- [Caratteristiche](#-caratteristiche)
- [Screenshot](#-screenshot)
- [Requisiti](#-requisiti)
- [Installazione](#-installazione)
- [Utilizzo](#-utilizzo)
- [Licenza](#-licenza)

---

## ✨ Caratteristiche

### 🔐 Autenticazione & Autorizzazione
- Sistema di creazione collaboratori direttamente dalla dashboard e login completo
- Autenticazione tramite Laravel
- Gestione ruoli e permessi (Admin, Project Manager, Developer, Client)
- Reset password via email

### 📊 Gestione Progetti
- Creazione e gestione di progetti multipli
- Dashboard con statistiche in tempo reale
- Timeline dei progetti
- Assegnazione membri del team
- Sistema di priorità e deadline

### ✅ Task Management
- Creazione, modifica ed eliminazione task
- Stati personalizzabili 
- Commenti e allegati sui task
- Notifiche push per aggiornamenti in calendario

### 👥 Collaborazione
- Chat in tempo reale tra membri del team e Admin
- Menzioni utenti 
- Activity log dettagliato
- Calendario condiviso

### 📈 Report & Analytics
- Dashboard con grafici
- Export fatture in PDF
- Time tracking per task

### 🎨 UI/UX
- Ricerca avanzata con filtri

---

## 📸 Screenshot

### Dashboard
![Dashboard](./docs/screenshots/home.png)


---

## 📋 Requisiti

### Database Supportati
- My0SQL 

---

## 🚀 Installazione

### 2️⃣ Installa le Dipendenze PHP

```bash
composer install
```

### 3️⃣ Installa le Dipendenze JavaScript

```bash
npm install
```

### 6️⃣ Esegui le Migrazioni e i Seeder

```bash
# Esegui le migrazioni
php artisan migrate

# Popola il database con dati di esempio
# Se desideri popolare dati di esempio anche di client, pm e dev decommenta i seeder in DatabaseSeeder 
php artisan db:seed


### 7️⃣ Crea il Link Simbolico per Storage

```bash
php artisan storage:link
```

### 8️⃣ Compila gli Asset Frontend

```bash
# Sviluppo
npm run dev
```

### 9️⃣ Avvia il Server di Sviluppo

```bash
php artisan serve
```

### 9️⃣ Avvia Queue

```bash
php artisan queue:work
```

### 9️⃣ Installa e avvia il Server Reverb

```bash
php artisan reverb:start
```

---

## 📖 Utilizzo

### Accesso all'Applicazione

Dopo l'installazione e il seeding, puoi accedere con:

| Ruolo | Email | Password |
|-------|-------|----------|
| **Admin** | admin@test.com | password |
| **Project Manager** | pm@test.com | password |
| **Developer** | dev@test.com | password |

> ⚠️ **Importante:** Cambia queste password se in produzione!

### Creazione di un Nuovo Cliente

1. Accedi alla dashboard
2. Clicca su "Anagrafica Clienti" nel menu laterale
2. Clicca su "Aggiungi Cliente"
3. Compila i campi richiesti

### Creazione di un Nuovo Progetto
> ⚠️ **Importante:** Per creare un progetto devi creare un Cliente!

2. Clicca su "Progetti" nel menu laterale
2. Clicca su "Aggiungi Progetto"

### Creazione Membri
> ⚠️ **Importante:** Cambia le password se in produzione!
 
1. Clicca su "Membri" nel menu laterale
2. Clicca su "Aggiungi membro"
3. Scegli se aggiungere Developer o Project manager e cliccare su avanti
4. Compilare le generalità
5. Compilare le skills

### Creazione Team
> ⚠️ **Importante:** Per creare un team devi avere almeno un project manager e un developer!

1. Clicca su "Gestione Teams" nel menu laterale
2. Clicca su "Aggiungi Team"
3. Clicca su compila i campi richesti (puoi aggiungere un solo pm e tanti developer)

 
### Gestione Progetti

1. I nuovi progetti devono essere Approvati / Non approvati / In approvazione
2. I progetti approvati possono essere assegnati a un team lavorativo
3. I progetti approvati vengono fatturati al cliente (la fattura è visualizzabile dal menù "Fatture" o dai dettagli del progetto o del cliente)
3. I progetti quando approvati avviano la deadline in base alla data di scadenza  
4. I progetti possono avere delle annotazioni con allegati nella sezione dedicata in tabella

### Gestione Task

1. I progetti assegnati a un team  verranno elencati in tabella
2. per creare una task cliccare su +
3. Compilari i campi richiesti e assegnarli a un developer
4. Le tasks verranno visualizzate nei dettagli del progetto, del developer


### Gestione Task

1. I progetti assegnati a un team  verranno elencati in tabella
2. per creare una task cliccare su +
3. Compilari i campi richiesti e assegnarli a un developer
4. Le tasks verranno visualizzate nei dettagli del progetto, del developer

### Gestione Calendario
> ⚠️ **Importante:** Al momento la creazione degli eventi in calendario è gestito solo dall'Admin

1. Puoi creare un evento cliccando sul giorno e compilare i campi richiesti
2. L'evento verrà immediatamente visualizzato in calendario
3. Se assegni un evento a un collaboratore, quest'ultimo riceverà una notifica in realtime
4. Le tasks verranno visualizzate in base alle date verranno visualizzate in calendario

### Gestione Documenti
 
1. Se sei un Admin puoi visualizzare i documenti di tutti gli utenti ed eliminarli
2. Per gli altri collaboratri verranno visualizzati solo i documenti relativi all'utente autenticato

### Gestione Logs
 
1. L'Admin puoi visualizzare\cancellare i vari log del sistema e eventuali messaggi di errore

### Chat
 
1. L'Admin puoi visualizzare tutti gli utenti del sistema
2. Il Project Manager puoi visualizzare tutti gli utenti del proprio gruppo di lavoro e l'Admin
3. Il developer puoi visualizzare tutti gli utenti del proprio gruppo di lavoro, il proprio Pm e l'Admin

---

## 📄 Licenza

Questo progetto è totalmente opensource

---

## 👥 Autore

- **Salvatore Pitanza** - *Sviluppatore principale* - [@salvatorepitanza](https://github.com/Salvo170586SP)

 
---

## 🙏 Ringraziamenti

- [Laravel Framework](https://laravel.com) - Il framework PHP più elegante
- [Tailwind CSS](https://tailwindcss.com) - Framework CSS utility-first
- [Livewire](https://laravel-livewire.com) - Componenti dinamici per Laravel
- [Alpine.js](https://alpinejs.dev) - Framework JavaScript minimalista
- [Chart.js](https://www.chartjs.org) - Grafici interattivi
- Tutti i contributors open source

---

<p align="center">Made with ❤️ in Italy</p>
