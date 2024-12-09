-- MySQL dump 10.13  Distrib 8.0.30, for Linux (x86_64)
--
-- Host: localhost    Database: db_passion_lecture
-- ------------------------------------------------------
-- Server version	8.0.30

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `review`
--

DROP TABLE IF EXISTS `review`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `review` (
  `book_fk` int NOT NULL,
  `user_fk` int NOT NULL,
  `grade` tinyint DEFAULT NULL,
  PRIMARY KEY (`book_fk`,`user_fk`),
  KEY `user_fk` (`user_fk`),
  CONSTRAINT `review_ibfk_1` FOREIGN KEY (`book_fk`) REFERENCES `t_book` (`book_id`),
  CONSTRAINT `review_ibfk_2` FOREIGN KEY (`user_fk`) REFERENCES `t_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `review`
--

LOCK TABLES `review` WRITE;
/*!40000 ALTER TABLE `review` DISABLE KEYS */;
INSERT INTO `review` VALUES (6,1,2);
/*!40000 ALTER TABLE `review` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_author`
--

DROP TABLE IF EXISTS `t_author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_author` (
  `author_id` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(128) NOT NULL,
  `last_name` varchar(128) NOT NULL,
  PRIMARY KEY (`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_author`
--

LOCK TABLES `t_author` WRITE;
/*!40000 ALTER TABLE `t_author` DISABLE KEYS */;
INSERT INTO `t_author` VALUES (1,'Victor','Hugo'),(2,'mile','Zola'),(3,'Marcel','Proust'),(4,'Albert','Camus'),(5,'Gustave','Flaubert'),(6,'George','Sand'),(7,'Eishima','Jun'),(8,'Collins','Suzanne'),(9,'Rowling','J.K.'),(10,'Akasaka','Aka'),(11,'Hirohiko','Araki'),(12,'','Hergé'),(13,'Rimbaud','Arthur'),(14,'Baudelaire','Charles'),(15,'','Collectif'),(16,'Camus','Albert');
/*!40000 ALTER TABLE `t_author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_book`
--

DROP TABLE IF EXISTS `t_book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_book` (
  `book_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `excerpt` varchar(255) NOT NULL,
  `summary` text NOT NULL,
  `release_date` date NOT NULL,
  `cover_image` varchar(128) NOT NULL,
  `number_of_pages` smallint NOT NULL,
  `user_fk` int NOT NULL,
  `category_fk` int NOT NULL,
  `publisher_fk` int NOT NULL,
  `author_fk` int NOT NULL,
  PRIMARY KEY (`book_id`),
  KEY `user_fk` (`user_fk`),
  KEY `category_fk` (`category_fk`),
  KEY `publisher_fk` (`publisher_fk`),
  KEY `author_fk` (`author_fk`),
  CONSTRAINT `t_book_ibfk_1` FOREIGN KEY (`user_fk`) REFERENCES `t_user` (`user_id`),
  CONSTRAINT `t_book_ibfk_2` FOREIGN KEY (`category_fk`) REFERENCES `t_category` (`category_id`),
  CONSTRAINT `t_book_ibfk_3` FOREIGN KEY (`publisher_fk`) REFERENCES `t_publisher` (`publisher_id`),
  CONSTRAINT `t_book_ibfk_4` FOREIGN KEY (`author_fk`) REFERENCES `t_author` (`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_book`
--

LOCK TABLES `t_book` WRITE;
/*!40000 ALTER TABLE `t_book` DISABLE KEYS */;
INSERT INTO `t_book` VALUES (1,'Les Misrables','Un chef-d\'oeuvre de la littrature franaise','Un roman qui explore la nature humaine et la justice sociale.','1862-04-03','les_miserables.jpg',1463,1,1,5,1),(2,'Germinal','Un portrait saisissant des luttes ouvrires','Le rcit poignant de la vie dans les mines du nord de la France.','1885-03-25','germinal.jpg',592,2,1,6,2),(3,' la recherche du temps perdu','Un voyage dans les souvenirs','Une exploration complexe des souvenirs et des motions humaines.','1913-11-14','recherche_du_temps_perdu.jpg',4211,3,1,1,3),(4,'L\'tranger','Une rflexion sur l\'absurde','Une histoire captivante d\'indiffrence et de choix personnels.','1942-06-19','l_etranger.jpg',123,1,1,1,4),(5,'Madame Bovary','Un roman d\'amour et de tragdie','L\'histoire d\'une femme prise au pige par ses rves romantiques.','1857-12-15','madame_bovary.jpg',362,2,1,2,5),(6,'La Mare au Diable','Un roman pastoral','Un conte touchant sur l\'amour et la vie rurale.','1846-03-02','mare_au_diable.jpg',184,3,1,4,6),(7,'NieR:Automata: Long Story Short','https://www.goodreads.com/book/show/39796468-nier','Experience the world and characters of the hit video game franchise!\r\n\r\nWhen alien forces invade with an army of Machines, the remnants of humanity must depend on Androids of their own design—the placid 2B and the excitable 9S—to survive.\r\n\r\nFrom: Pod 042\r\n\r\nTo: Fans of NieR: Automata\r\n\r\nRecommendation: The action to finish reading this novel.\r\n\r\n[ref & NieR: Automata—Long Story Short]\r\n\r\nResponse: A novel is a story that used to be told by humans.\r\n\r\nQuestion: The definition of the word “interesting”?\r\n\r\nAnswer: A possible definition is that the ability to continue reading this novel makes it “interesting.”\r\n\r\nFrom Pod 042 to 153: We have concluded our promotional duties.','2018-10-09','../public/assets/img/cover/img_6756a3ed2b8495.49649937.jpg',256,1,1,7,7),(8,'The Hunger Games','https://www.goodreads.com/book/show/2767052-the-hunger-games','Could you survive on your own in the wild, with every one out to make sure you don\'t live to see the morning?\r\n\r\nIn the ruins of a place once known as North America lies the nation of Panem, a shining Capitol surrounded by twelve outlying districts. The Capitol is harsh and cruel and keeps the districts in line by forcing them all to send one boy and one girl between the ages of twelve and eighteen to participate in the annual Hunger Games, a fight to the death on live TV.\r\n\r\nSixteen-year-old Katniss Everdeen, who lives alone with her mother and younger sister, regards it as a death sentence when she steps forward to take her sister\'s place in the Games. But Katniss has been close to dead before—and survival, for her, is second nature. Without really meaning to, she becomes a contender. But if she is to win, she will have to start making choices that weight survival against humanity and life against love.','2008-10-14','../public/assets/img/cover/img_6756a47081fb56.24531454.jpg',374,1,1,8,8),(9,'Harry Potter and the Order of the Phoenix','https://www.goodreads.com/book/show/2.Harry_Potter_and_the_Order_of_the_Phoenix','Harry Potter is about to start his fifth year at Hogwarts School of Witchcraft and Wizardry. Unlike most schoolboys, Harry never enjoys his summer holidays, but this summer is even worse than usual. The Dursleys, of course, are making his life a misery, but even his best friends, Ron and Hermione, seem to be neglecting him.\r\n\r\nHarry has had enough. He is beginning to think he must do something, anything, to change his situation, when the summer holidays come to an end in a very dramatic fashion. What Harry is about to discover in his new year at Hogwarts will turn his world upside down...','2004-09-01','../public/assets/img/cover/img_6756a4db166172.21355975.jpg',912,1,1,9,9),(10,'[Oshi No Ko], Vol. 1','https://myanimelist.net/manga/126146/Oshi_no_Ko','Sixteen-year-old Ai Hoshino is a talented and beautiful idol who is adored by her fans. She is the personification of a pure, young maiden. But all that glitters is not gold.\r\n\r\nGorou Amemiya is a countryside gynecologist and a big fan of Ai. So when the pregnant idol shows up at his hospital, he is beyond bewildered. Gorou promises her a safe delivery. Little does he know, an encounter with a mysterious figure would result in his untimely death—or so he thought.\r\n\r\nOpening his eyes in the lap of his beloved idol, Gorou finds that he has been reborn as Aquamarine Hoshino—Ai\'s newborn son! With his world turned upside down, Gorou soon learns that the world of showbiz is paved with thorns, where talent does not always beget success. Will he manage to protect Ai\'s smile that he loves so much with the help of an eccentric and unexpected ally?','2020-07-17','../public/assets/img/cover/img_6756a55814a3f1.86024257.jpg',228,1,2,10,10),(11,'Jojo\'s Bizarre Adventure Part VII: Steel Ball Run, Vol. 1','https://myanimelist.net/manga/1706/JoJo_no_Kimyou_na_Bouken_Part_7__Steel_Ball_Run','In the American Old West, the world\'s greatest race is about to begin. Thousands line up in San Diego to travel over six thousand kilometers for a chance to win the grand prize of fifty million dollars. With the era of the horse reaching its end, contestants are allowed to use any kind of vehicle they wish. Competitors will have to endure grueling conditions, traveling up to a hundred kilometers a day through uncharted wastelands. The Steel Ball Run is truly a one-of-a-kind event.\r\n\r\nThe youthful Johnny Joestar, a crippled former horse racer, has come to San Diego to watch the start of the race. There he encounters Gyro Zeppeli, a racer with two steel balls at his waist instead of a gun. Johnny witnesses Gyro using one of his steel balls to unleash a fantastical power, compelling a man to fire his gun at himself during a duel. In the midst of the action, Johnny happens to touch the steel ball and feels a power surging through his legs, allowing him to stand up for the first time in two years. Vowing to find the secret of the steel balls, Johnny decides to compete in the race, and so begins his bizarre adventure across America on the Steel Ball Run.','2004-05-20','../public/assets/img/cover/img_6756a6df75d9d8.88074555.jpg',160,1,2,11,11),(12,'Objectif Lune','https://www.goodreads.com/book/show/900530.Objectif_Lune','Alors qu\'ils reviennent à Moulinsart après leur périple en Afrique du Nord (voir \"Au pays de l\'or noir\"), Tintin et Haddock apprennent que Tournesol a quitté le château voilà trois semaines...\r\n\r\nQuelques minutes plus tard, Nestor leur amène un télégramme du professeur, qui leur demande de le rejoindre en Syldavie (le pays de l\'eau minérale, un comble pour le capitaine !!). Une fois arrivée à l\'aéroport, ils sont réceptionnés par un ami de Tournesol qui les conduit, à travers un impressionnant dispositif de contrôle, jusqu\'à une base secrète.\r\n\r\nUne fois arrivé, Tournesol leur explique qu\'il dirige la section aéronautique du Centre de Recherches Atomiques de Sbrodj, et qu\'à ce propos il projette d\'envoyer une fusée sur la lune, dont Tintin, Haddock et lui seraient les passagers !\r\n\r\nUn soir, l\'alarme de la base se déclenche : des parachutistes ont été lâchés au dessus du centre. Des espions ? Tintin décide d\'enquêter...','1950-01-01','../public/assets/img/cover/img_6756a79f8fd297.69435735.png',62,1,3,12,12),(13,'Une Saison en Enfer','https://www.fr.fnac.ch/a13355564/Arthur-Rimbaud-Une-saison-en-enfer','\"Un soir, j\'ai assis la Beauté sur mes genoux.- Et je l\'ai trouvée amère.\"Lorsque Arthur Rimbaud écrit les poèmes en prose d\'Une saison en enfer, il vit dans la tourmente qui fait suite à sa violente rupture avec Paul Verlaine. Il y relate ses souffrances, qui l\'entraînent aux portes de la folie, ses désillusions, mais aussi ses espoirs.Une saison en enfer est suivi des Illuminations, derniers poèmes avant l\'exil de leur auteur et son entrée dans le silence, qui révèlent l\'apogée de sa voyance et sa joie d\'être poète. Il n\'a alors que vingt ans.- Objet d\'étude : La poésie du XIXe au XXIe siècle (lecture cursive)- Dossier pédagogique spécial nouveaux programmes- Prolongements : La vocation poétique - Le poème en prose (corpus de texte).Classe de première.','2019-06-19','../public/assets/img/cover/img_6756a81fc6d394.04487384.jpg',128,1,5,13,13),(14,'Les Fleurs du mal','https://www.fr.fnac.ch/a12988561/Charles-Baudelaire-Les-Fleurs-du-mal','LES GRANDS TEXTES DU XIXe SIÈCLE\r\n\r\n\" Dans ce livre atroce, j\'ai mis tout mon cœur, toute ma tendresse, toute ma religion, toute ma haine. \" Étranger dans un monde qui le refuse, maudit et damné, Baudelaire n\'a pas d\'autre choix que d\'explorer l\'enfer et le mal. Puisque la vie n\'est qu\'extase et horreur, le poète la transfigure dans une contrée imaginaire où le désespoir et la beauté se confondent. Il s\'évade dans les paradis artificiels du haschisch, de l\'opium et du vin, de la luxure et du vice.\r\nLes Fleurs du mal sont le journal intime, le cri de terreur et de jouissance du poète. \" Fleurs maladives \" qui annoncent toute la littérature moderne et dont le parfum et les poisons ne cessent de troubler.\r\n','2018-09-01','../public/assets/img/cover/img_6756a88394b5a5.12125041.jpg',192,1,5,14,14),(15,'Arsène Lupin, Sherlock Holmes et autres détectives : Nouvelles policières','https://www.fr.fnac.ch/a18321987/Arsene-Lupin-Arsene-Lupin-Sherlock-Holmes-et-autres-detectives-Nouvelles-policieres-Collectif','Le crime n\'est jamais parfait, puisque le détective finit toujours par démasquer le coupable. Arsène Lupin, Sherlock Holmes, l\'inspecteur Wens et le père Brown, quatre redresseurs de torts parmi les plus célèbres, rivalisent de flair et de vivacité d\'esprit. Et nous de leur emboîter le pas, à la recherche de la vérité, non sans suspens. TOUT POUR COMPRENDRE - Notes lexicales - Contexte littéraire : la figure du détective - Genre des oeuvres - Chronologie TOUT POUR RÉUSSIR - Questions sur les oeuvres - Portraits de détectives.','2023-10-18','../public/assets/img/cover/img_6756a8e47d8819.34487264.jpg',160,1,4,2,15),(16,'L\'étranger','https://www.amazon.fr/L%C3%A9tranger-Albert-Camus/dp/2070360024','\"Quand la sonnerie a encore retenti, que la porte du box s\'est ouverte, c\'est le silence de la salle qui est monté vers moi, le silence, et cette singulière sensation que j\'ai eue lorsque j\'ai constaté que le jeune journaliste avait détourné les yeux. Je n\'ai pas regardé du côté de Marie. Je n\'en ai pas eu le temps parce que le président m\'a dit dans une forme bizarre que j\'aurais la tête tranchée sur une place publique au nom du peuple français...\"','1971-12-01','../public/assets/img/cover/img_6756a94dd02589.80968860.jpg',191,1,1,1,16);
/*!40000 ALTER TABLE `t_book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_category`
--

DROP TABLE IF EXISTS `t_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_category` (
  `category_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`category_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_category`
--

LOCK TABLES `t_category` WRITE;
/*!40000 ALTER TABLE `t_category` DISABLE KEYS */;
INSERT INTO `t_category` VALUES (3,'bande-dessinee'),(2,'manga'),(4,'nouvelle'),(5,'poesie'),(1,'roman');
/*!40000 ALTER TABLE `t_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_publisher`
--

DROP TABLE IF EXISTS `t_publisher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_publisher` (
  `publisher_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`publisher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_publisher`
--

LOCK TABLES `t_publisher` WRITE;
/*!40000 ALTER TABLE `t_publisher` DISABLE KEYS */;
INSERT INTO `t_publisher` VALUES (1,'Gallimard'),(2,'Flammarion'),(3,'Grasset'),(4,'Albin Michel'),(5,'Folio'),(6,'Le Livre de Poche'),(7,'VIZ Media LLC'),(8,'Scholastic Press'),(9,'Scholastic Inc.'),(10,'Yen Press'),(11,'Shueisha'),(12,'Casterman'),(13,'Librio'),(14,'Pocket');
/*!40000 ALTER TABLE `t_publisher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `t_user`
--

DROP TABLE IF EXISTS `t_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `t_user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `sign_up_date` date NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `t_user`
--

LOCK TABLES `t_user` WRITE;
/*!40000 ALTER TABLE `t_user` DISABLE KEYS */;
INSERT INTO `t_user` VALUES (1,'booklover123','2022-01-15',0),(2,'admin_guy','2021-12-05',1),(3,'reading_queen','2023-03-22',0);
/*!40000 ALTER TABLE `t_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-09  8:27:18
