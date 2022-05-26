-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Mag 26, 2022 alle 21:19
-- Versione del server: 10.4.21-MariaDB
-- Versione PHP: 8.0.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ltw`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `admin`
--

CREATE TABLE `admin` (
  `email` varchar(190) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `admin`
--

INSERT INTO `admin` (`email`) VALUES
('ciaosonoelfo@gmail.com'),
('danielebbertagnoli@gmail.com');

-- --------------------------------------------------------

--
-- Struttura della tabella `comments`
--

CREATE TABLE `comments` (
  `user` varchar(190) NOT NULL,
  `trip` int(11) NOT NULL,
  `text` varchar(500) NOT NULL,
  `datetime` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `comments`
--

INSERT INTO `comments` (`user`, `trip`, `text`, `datetime`) VALUES
('ciaosonoelfo@gmail.com', 2, 'Viaggio bellissimo, complimenti!', '2022-04-20 15:45:19'),
('ciaosonoelfo@gmail.com', 8, 'Ciao bel viaggio', '2022-05-25 16:10:35'),
('danielebbertagnoli@gmail.com', 1, 'Wow!', '2022-05-18 19:20:48'),
('danielebbertagnoli@gmail.com', 1, 'Bellissimo', '2022-05-25 09:26:26'),
('danielebbertagnoli@gmail.com', 8, 'wow!', '2022-05-25 16:08:55'),
('mario@gmail.com', 1, 'Molto interessante!', '2022-05-02 20:02:37'),
('mario@gmail.com', 2, 'Credo che la mia prossima meta sarà Parigi, grazie :)', '2022-05-02 20:03:26'),
('mario@gmail.com', 3, 'Avrei preferito qualche immagine in più :(', '2022-05-02 20:04:15'),
('mario@gmail.com', 4, 'Wow!', '2022-05-02 20:04:25'),
('mario@gmail.com', 5, 'Come si chiama l\'aerea di sosta???', '2022-05-02 20:04:50'),
('mario@gmail.com', 6, 'Meraviglioseeee!!!!', '2022-05-02 20:05:04'),
('mario@gmail.com', 7, 'Bellissime foto complimenti!!!!', '2022-05-02 20:05:18');

-- --------------------------------------------------------

--
-- Struttura della tabella `friends`
--

CREATE TABLE `friends` (
  `user1` varchar(190) NOT NULL,
  `user2` varchar(190) NOT NULL,
  `pending` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `friends`
--

INSERT INTO `friends` (`user1`, `user2`, `pending`) VALUES
('danielebbertagnoli@gmail.com', 'ciaosonoelfo@gmail.com', 0);

-- --------------------------------------------------------

--
-- Struttura della tabella `problems`
--

CREATE TABLE `problems` (
  `id` int(11) NOT NULL,
  `email` varchar(190) NOT NULL,
  `subject` varchar(190) NOT NULL,
  `text` varchar(500) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `problems`
--

INSERT INTO `problems` (`id`, `email`, `subject`, `text`, `date`) VALUES
(34, 'danielebbertagnoli@gmail.com', 'Problema', 'bug!', '2022-05-25');

-- --------------------------------------------------------

--
-- Struttura della tabella `trip`
--

CREATE TABLE `trip` (
  `id` int(11) NOT NULL,
  `title` varchar(190) NOT NULL,
  `description` longtext NOT NULL,
  `visited_places` varchar(190) NOT NULL,
  `user` varchar(190) NOT NULL,
  `keywords` varchar(190) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `trip`
--

INSERT INTO `trip` (`id`, `title`, `description`, `visited_places`, `user`, `keywords`) VALUES
(1, 'Avventura a Roma', '~(~~)~Dal 2021-05-15 al 2021-05-16: In questi primi 2 giorni del nostro breve viaggio, ci siamo concentrati principalemente nella zona centrale di Roma. Abbiamo soggiornato in un ostello in zona Termini. Come prima cosa abbiamo visitato il Colosseo e successivamente ci siamo spostati di qualche chilomentro a piedi per vedere l\'altare della patria. Il giorno dopo abbiamo visitato palazzo chigi grazie a una visita guidata e la fontana di Trevi.\n~(~~)~Dal 2021-05-17 al 2021-05-18: Negli ultimi 2 giorni abbiamo visitato i comuni che si trovano in periferia di Roma, in particolare quelli dei castelli romani. Tra tutti i comuni il più bello è assolutamente Nemi grazie anche al panorama offerto dal lago. Consiglio vivamente di visitare queste zone in questo periodo in quanto ci sono molte sagre caratteristiche.\n', ',roma,palazzo chigi,fontana di trevi,colosseo,castelli romani,', 'danielebbertagnoli@gmail.com', ' avventura roma  città '),
(2, 'Esplorando Parigi', '~(~~)~Dal 2020-08-15 al 2020-08-16: Il primo giorno del mio viaggio ho deciso di iniziare il tour della città visitando la torre Eiffer. Fortunatamente sono riuscito anche a prendere l\'ascensore che porta in cima alla torre, consiglio vivamente di provare quest\'esperienza che permette di avere una vista mozzafiato sulla città. Ho anche svolto un breve tour in barcasul fiume Senna, onestamente non è valso il prezzo pagato, dunque se volete risparmiare qualcosa io eviterei di svolgerlo. Il giorno successivo ho visitato la cattedrale di Notredame, molto bella e caratteristica.\n~(~~)~Dal 2020-08-17 al 2020-08-18: Gli ultimi due giorni del viaggio ho visitato il museo del Louvre, consiglio di prenotare una visita guidata che permette di risparmiare molto tempo sulle code di entrata. Infine ho deciso di spostarmi da Parigi uscendo dal centro andando in periferia. L\'ultima cosa che ho visitato è stata la regga di Versailles, veramente fantastica, consiglio di prendersi almeno mezza giornata per godersi a pieno la visita.\n', ',parigi,louvre,reggia di Versailles,torre eiffel,cattedrale notredame,', 'danielebbertagnoli@gmail.com', ' esplorando parigi  città  relax '),
(3, 'Passeggiata per Venezia', '~(~~)~Dal 2022-04-04 al 2022-04-04: In questi due giorni a Venezia abbiamo viaggiato per tutta Venezia. La prima tappa è stata piazza San Marco, dove abbiamo preso un caffè in uno dei bar che si trovano nella piazza. Successivamente abbiamo pranzato in uno dei piccoli ristoranti che si trovano nelle vie adiacenti alla piazza. Dopo pranzo siamo andati a vedere Ponte Vecchio, passandoci sotto grazie ad un breve tratto in gondola. Infine abbiamo preso un gelato nella famosissima geletaria Venchi sempre in piazza San Marco.\n', ',Venezia,piazza San Marco,', 'danielebbertagnoli@gmail.com', ' passeggiata venezia  città  relax '),
(4, 'Escursione in Colorado', '~(~~)~Dal 2021-05-02 al 2021-05-14: Primo giorno passato nella locale cittadina, ho passato un paio di giorni li per ambientarmi. Fa decisamente piu\' freddo di quanto mi aspettassi. \r\nLe persone sono molto gentili e adoro il paesaggio\n~(~~)~Dal 2021-05-17 al 2021-05-19: Piccola escursione sulle montagne rocciose. Ho incontrato un gruppo di scalatori molto simpatici sul cammino.\n', ',Colorado\r\nDallas Divide\r\nRocky Mountains,', 'ciaosonoelfo@gmail.com', ' escursione colorado  montagna '),
(5, 'Scalata sulle Alpi', '~(~~)~Dal 2022-01-03 al 2022-01-04: Abitando ai piedi delle Alpi, ho pensato fosse un\'esperienza carina quella di fare campeggio per una notte. Arrivato di pomeriggio in un\'area adibita al campeggio, ho piantato la mia tenda. Fortunatamente ho terminato in tempo per potermi godere il tramonto. Nella stessa zona di sosta ho conosciuto due ragazzi in camper che avevano avuto la mia stessa idea. Consiglio a tutti una notte del genere per stare a contatto con la natura!\n', ',Alpi,', 'danielebbertagnoli@gmail.com', ' scalata sulle alpi  montagna  avventura '),
(6, 'Un tuffo in Costarica', '~(~~)~Dal 2020-05-18 al 2020-05-19: Per le vacanze di quest\'anno io e mia moglie abbiamo deciso di regalarci una vacanza in Costarica, spero che  queste immagini vi lascino a bocca aperta come hanno lasciato me.\r\nLa prima parte del viaggio e\' stata passata tra le mille spiagge del posto.\n~(~~)~Dal 2020-05-20 al 2020-05-24: La seconda parte e\' stata dedicata all\'escursionismo piu\' sfrenato.\n', ',Cartagena\r\nTamarindo\r\nSamara\',', 'giacomo.frascarelli1@gmail.com', ' tuffo costarica  mare '),
(7, 'Viaggio ad Amsterdam', '~(~~)~Dal 2022-04-25 al 2022-04-26: Abbiamo esplorato la bellissima citta\' di Amsterdam. Ricca di storia e molto progressista.\n~(~~)~Dal 2022-04-28 al 2022-04-30: Abbiamo visto i magnifici campi di fiori appena fuori la citta\'\n', ',Amsterdam,', 'giacomo.frascarelli1@gmail.com', ' viaggio ad amsterdam  città '),
(8, 'Praga', '~(~~)~Dal 2022-05-01 al 2022-05-02: periodo 1\n~(~~)~Dal 2022-05-03 al 2022-05-04: periodo 2\n', ',praga,pubblica ceca,', 'danielebbertagnoli@gmail.com', ' praga  città  relax ');

-- --------------------------------------------------------

--
-- Struttura della tabella `users`
--

CREATE TABLE `users` (
  `email` varchar(190) NOT NULL,
  `password` varchar(190) NOT NULL,
  `username` varchar(20) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `country` varchar(190) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `privacy` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `users`
--

INSERT INTO `users` (`email`, `password`, `username`, `gender`, `country`, `birthday`, `privacy`) VALUES
('ciaosonoelfo@gmail.com', '$2y$10$nywPm19Gy/w9WIbvmle9UO9Vv/izj1kDARoZVLfmcHaH.1RlRIhuu', 'Elfo', 'Donna', 'Germania', '2000-10-05', 1),
('danielebbertagnoli@gmail.com', '$2y$10$lyY.ZXpVMMKhzGtVhQra5uX2NjZ0/YJWJsmHT57dtH3CSwCq9MwZe', 'DANIELE', 'Uomo', 'Italia', '2001-01-18', 1),
('giacomo.frascarelli1@gmail.com', '$2y$10$2vGaM7RKjIYM4ewat22KDO.cIxiLXaifIcSNmakAFuYILOvZf672q', 'xx_Giacomo_xx', NULL, NULL, NULL, 0),
('mario@gmail.com', '$2y$10$GMEIlL/csxbBvJtAY6lD0uK5q.q4MfR5A2FSLFocMkXhjTfuLWb6y', 'Mario', 'Uomo', 'Italia', '1970-05-20', 0),
('user@gmail.com', '$2y$10$/1MFwlcTBE/gitWtIJDQFuYUCl0Z0wfjN672CBYYpiR8lXTCaxqry', 'user', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `votes`
--

CREATE TABLE `votes` (
  `vote` varchar(11) NOT NULL,
  `user` varchar(190) NOT NULL,
  `trip` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `votes`
--

INSERT INTO `votes` (`vote`, `user`, `trip`) VALUES
('1', 'ciaosonoelfo@gmail.com', 1),
('5', 'ciaosonoelfo@gmail.com', 2),
('1', 'ciaosonoelfo@gmail.com', 8),
('4', 'danielebbertagnoli@gmail.com', 1),
('5', 'danielebbertagnoli@gmail.com', 2),
('4', 'danielebbertagnoli@gmail.com', 3),
('3', 'danielebbertagnoli@gmail.com', 5),
('4', 'danielebbertagnoli@gmail.com', 7),
('4', 'danielebbertagnoli@gmail.com', 8),
('5', 'giacomo.frascarelli1@gmail.com', 1),
('5', 'giacomo.frascarelli1@gmail.com', 2),
('4', 'mario@gmail.com', 1),
('4', 'mario@gmail.com', 2),
('3', 'mario@gmail.com', 3),
('5', 'mario@gmail.com', 4),
('4', 'mario@gmail.com', 5),
('4', 'mario@gmail.com', 6),
('5', 'mario@gmail.com', 7);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`email`);

--
-- Indici per le tabelle `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`user`,`trip`,`datetime`);

--
-- Indici per le tabelle `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`user1`,`user2`);

--
-- Indici per le tabelle `problems`
--
ALTER TABLE `problems`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `trip`
--
ALTER TABLE `trip`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`email`);

--
-- Indici per le tabelle `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`user`,`trip`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `problems`
--
ALTER TABLE `problems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
