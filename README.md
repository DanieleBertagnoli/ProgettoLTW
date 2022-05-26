All’interno del file zip sono presenti le seguenti cartelle: Bootstrap, Contacts, CSS, Logged, Login, Media, ProfilePics, Signup, TripImages e il file index.html.
Il file index.html rappresenta la homepage che viene mostrata all’interno del sito.

    - Cartella “Bootstrap”, all'interno sono presenti i file standard di Bootstrap 5. In particolare il css generale e la cartella contenente i file JavaScript di Bootstrap.

    - Cartella "Contacts", all'interno sono presenti i file utilizzati per gestire la sezione dei contatti del sito:

        - "contactsPage.php", rappresenta la pagina dei contatti, utilizzata per inviare segnalazioni. Se l'utente ha effettuato il login allora la sezione PHP stamperà una navbar personalizzata per gli utenti loggati, altrimenti viene stampata una navbar standard. Il resto della pagina si compone di un form realizzato senza l'uso del tag HTML form, in quanto la richiesta verrà effettuata dal file JS richiamato dal pulsante di submit.

        - "contactsScript.js", all'interno troviamo la funzione check() invocata dal form di "contactsPage.php". Questa effettua un controllo sui campi del form per verificare la corretta compilazione di quest'ultimo. Infine se non sono stati rilevati errori, viene richiamata la funzione sendProblem() che effettua una richiesta POST tramite AJAX ad una risorsa per l'inserimento della segnalazione nel database.

    - Cartella "CSS", all'interno sono presenti i file CSS utilizzati per stilizzare le varie pagine del sito. Tutti i file sono stati realizzati tenendo conto della responsiveness.

    - Cartella "Login" Al cui interno troviamo i file necessari per effettuare il login:

        - "completeLogin.php", è lo script invocato dal form presente in "loginPage.html". Se l'utente è registrato al sito, allora viene avviata la sessione con gli attributi di sessione email e username dell'utente.

        - "errorPageLogin.php", è la pagina che viene richiamata da "completeLogin.php" in caso di errore durante il login.

        - "loginPage.html", è la pagina che contiene il form di login.

        - "loginScript.js", contiene tutti i controlli per il form di login assicurandosi che email o password non siano vuoti, altrimenti crea una box di errore.

    - Cartella "Media Contiene tutte le immagini del sito come gli sfondi e il logo del sito.

    - Cartella "Profile Pics" contiene tutte le immagini di profilo dei vari utenti rinominate in base all'email del loro utente.

    - Cartella "Signup" Al cui interno troviamo i file necessari per effettuare il signup.

        - "completeSignup.php", è lo script invocato dal form presente in "signupPage.html". Se l'utente non è ancora registrato al sito allora viene inserito all'interno del database e reindirizzato nella "successPage.html".

        - "completeSignup.php", è la pagina che viene richiamata da "completeLogin.php" in caso di errore durante la registrazione.

        - "signupPage.html", è la pagina che contiene il form di signup.

        - "signupScript.js", contiene tutti i controlli per il form di signup assicurandosi che la password, l'email e lo username rispettino determinati criteri, che  la password ripetuta coincida con quella principale, e che l'email o la password non siano vuoti, altrimenti crea un messaggio di errore.

        - "successPage.html", mostra un messaggio di successo e contiene un link alla pagina di login

    -  Cartella "Trip images" Al cui interno troviamo le cartelle dei relativi viaggi, numerati con una successione crescente. All'interno della cartella del viaggio abbiamo la thumbnail del viaggio il cui nome è thumbnail e poi le varie cartelle dei period nominate "period-" numero di periodo, al cui interno troviamo le immagini del periodo.

    - Cartella "Logged", all'interno sono presenti i file che richiedono il login da parte dell'utente per poter essere acceduti:

    - Cartella "Utility", all'interno sono presenti i file che rappresentano script utilizzati all'interno del sito:

        - Cartella "JS", all'interno sono presenti tutti gli script JavaScript utilizzati dal sito:

            - "homeScript.js", all'interno di questo script è presente una funzione checkForm() che si assicura che la barra di ricerca presente nella pagina "homePage.php" venga compilata nel momento dell'invio della richiesta. Se non vengono rilevati errori, viene richiamata la funzione addSearchCookie() che si occupa di prendere il cookie con nome "search" e aggiungere la ricerca appena effettuata alla lista. I vecchi valori del cookie "search" vengono ottenuti mediante la funzione getCookie().

            - "myProfileScript.js", all'interno di questo script abbiamo 3 funzioni per ogni voce del profilo. Queste 3 funzioni sono change, confirm e abort dell'informazione. La funzione di change è collegata ai vari bottoni "Cambia" ed inietta nell'html del relativo elemento tutti gli elementi necessari a cambiare tale informazione (nel caso del genere fa comparire un menù a tendina). La funzione abort invece viene invocata dal bottone "Annulla" e semplicemente ripristina la vista originale. La funzione confirm invece fa una query al database inviando la nuova informazione utilizzando Ajax per evitare di ricaricare la pagina per visualizzare l'informazione.

            - "newTripScript.js", all'interno di questo script è presente una funzione init(), richiamata dall'evento "load" dell'oggetto "window", la quale si occupa di inizializzare l'array che conterrà i "periods" (sezioni che permettono di suddividere uno stesso itinerario in più tappe) creati dall'utente. La funzione addPeriod() si occupa di aggiungere in fondo alla pagina un nuovo periodo. La funzione removePeriod() si occupa di eliminare un periodo, in particolare ogni periodo possiede un bottone che richiama questa funzione passando in input l'id del proprio periodo. Le funzioni changeThumbnail() e changeLabel() si occupano di modificare il testo delle etichette associate. In particolare changeLabel() modifica il testo dell'etichetta del periodo, indicando il numero di immagini selezionate. Infine la funzione checkForm() si occupa di effettuare una validazione sui campi, i quali devono essere compilati secondo determinate restrizioni. Se viene rilevato un errore, viene invocata la funzione setError() che crea un messaggio di errore.

            - "showProblemsScript.js", all'interno di questo script sono presenti due funzioni: update() e setInvisible(). La funzione update() ottiene le due date del form presenti in “showProblems.php”  ed effettua una query al database per estrapolare le segnalazioni presenti all'interno di quell'intervallo di tempo. Tutto questo facendo uso di Ajax. 
                        
            - "tripViewerScript.js", all'interno di questo script sono presenti varie funzioni per la gestione dei voti, dei commenti e del popup delle immagini. La funzione changeStarLevel() viene chiamata ad ogni nuovo caricamento della pagina o ogni volta che inviamo un nuovo voto, la funzione sendVote() viene chiamata ogni volta che si clicca una stella, dunque invia il nuovo voto al database tramite una query Ajax e poi aggiornare le stelle. L'ultima funzione è refreshVoteAvg() che effettua una query al database ottenendo la media dei voti per l’itinerario in questione. Per quanto riguarda i commenti la situazione rimane similare, una funzione refreshComments() ricarica i commenti una volta inseriti e una funzione sendComment() invia il commento al database. Inoltre per evitare l'inserimento di commenti "vuoti" la funzione checkButton() viene chiamata alla pressione del bottone "SendComment" che controlla che il commento non sia composto da soli spazi vuoti o andate a capo. Le funzioni per il popup sono invece "openPopup" che viene chiamato alla pressione di un'immagine che la visualizza a risoluzione più grande e "closePopup" che invece chiude questa visualizzazione.

        - Cartella "PHP", all'interno sono presenti tutti gli script PHP utilizzati dal sito:
        
            - "acceptFriend.php", questo script si occupa aggiornare una richiesta di amicizia presente all'interno del database, in particolare indicandola come non più "pending".

            - "getComments.php", questo script si occupa di fornire tutti i commenti per un dato itinerario. In particolare fornisce già elementi HTML formattati ad hoc.
                    
            - "getProblems.php", questo script si occupa di fornire tutte le segnalazioni effettuate in un dato periodo. In particolare fornisce già elementi HTML formattati ad hoc.
                    
            - "getVoteAvg.php", questo script si occupa di fornire la media dei voti assegnati ad un dato itinerario.

            - "initConnection.php", questo script si occupa di inizializzare la connesione al database.

            - "insertFriend.php", questo script si occupa di inserire una richiesta di amicizia "pending" all'interno del database.

            - "insertNewComment.php", questo script si occupa di inserire un nuovo commento per un dato itinerario all'interno del database.

            - "insertNewMessage.php", questo script si occupa di inserire una nuova segnalazione all'interno del database.

            - "insertNewTrip.php", questo script si occupa di inserire un nuovo itinerario all'interno del database. Inoltre prima di effettuare l'inserimento, esegue post-processing sui dati ricevuti per poterli conformare al database e riutilizzare in maniera più semplice. È stato aggiunto un sistema di "keywords" per facilitare la ricerca degli itinerari, queste vengono generate partendo dal titolo del viaggio. In particolare viene effettuato uno split sul carattere spazio, successivamente vengono rimosse tutte congiunzioni, articoli, ecc... Inoltre alle "keywords" vengono aggiunti anche i tag assegnati all'itinerario.

            - "insertVote.php", questo script si occupa di inserire un nuovo voto all’interno del database per un dato itinerario.
                        
            - "isAdmin.php", questo script si occupa di controllare se l'utente corrente è un admin facendo una query alla tabella admin e controllando che l'email dell'utente sia presente nella tabella.

            - "logout.php", questo script distrugge la sessione effettuando il lougout dell'utente.

            - "refuseFriend.php", questo script si occupa di rimuovere dal database la richiesta di amicizia pending selezionata.

            - "update*.php", questo script si occupa di aggiornare la relativa informazione del database, che sia questa la nazione, data di nascita, genere, password, visibilità o immagine di profilo dell'utente.
    
        - "errorPage.php", questa pagina si occupa di mostrare un messaggio di errore. La pagina prende in input una stringa di errore personalizzata in base all'evento che ha generato l'errore.

        - "externalProfilePage.php", questa pagina si occupa di mostrare il profilo dell'utente senza la possibilità di modificare i suoi contenuti, con una visualizzazione read only, appunto "esterna".

        - "homePage.php", questa pagina è la home del sito, dove possiamo trovare una scritta di saluto, la barra della ricerca degli utenti e dei viaggi e il carosello con i viaggi consigliati, che possono eventualmente venire modificate in base alle ricerche effettuate. Inoltre in fondo alla pagina troviamo la sezione con i viaggi in natura e nella città.

        - "myFriends.php", questa pagina si occupa di mostrare tutti gli amici dell'utente. Per ogni utente trovato dalla query, viene creato un elemento che rappresenta appunto l’utente. Ogni elemento rappresenta un link alla pagina del profilo dell’utente.

        - "myProfilePage.php", questa pagina si occupa di mostrare il profilo personale dell'utente, visibile solo da lui, che consente anche la possibilità di modificare i suoi valori.

        - "myRequestsPage.php", questa pagina si occupa di mostrare tutte le richieste di amicizia inviate all'utente che non sono ancora state accettate o rifiutate.

        - "newTripPage.php, questa pagina consente di creare nuovi itinerari potendo compilare tutte le informazioni necessarie come il titolo, i luoghi visitati, i tag, la thumbnail e i vari periodi, con ognuno la propria descrizione, timespan e immagini.

        - "searchResult.php", questa pagina si occupa di mostrare i risultati della ricerca effettuata, mostrando gli utenti o i viaggi corrispondenti al nome inserito. La ricerca viene effettuata andando a cercare la stringa passata dalla richiesta tra i titoli degli itinerari. Successivamente viene effettuato uno split sulla ricerca in base al carattere spazio, vengono effettuate altre query in cui si cerca ogni parola dello split all’interno delle keywords o dei luoghi visitati. Per ogni itinerario trovato viene creato un elemento che rappresenta un link alla pagina dell’itinerario stesso.

        - "showProblems.php", questa pagina si occupa di mostrare le segnalazioni avvenute all'interno del time span selezionato. 

        - "tripViewer.php", questa pagina si occupa di mostrare il viaggio selezionato suddividendo ogni periodo in caselle, al cui interno è posto un carosello con le immagini del periodo, e sotto di esso la descrizione con il time span. Inoltre sono presenti anche due sezioni per votare e commentare il suddetto itinerario.

        - "userTripsPage.php", questa pagina si occupa di mostrare tutti i viaggi effettuati da un determinato utente.

