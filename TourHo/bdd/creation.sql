DROP DATABASE tourho;

create database tourho;

use tourho;

CREATE TABLE Tournament (
  idTournament INTEGER   NOT NULL AUTO_INCREMENT,
  Name VARCHAR(45)    ,
  dDate DATE    ,
  Place VARCHAR(45)      ,
PRIMARY KEY(idTournament));




CREATE TABLE Round (
  idRound INTEGER   NOT NULL AUTO_INCREMENT,
  Tournament_idTournament INTEGER   NOT NULL ,
  dDate DATETIME      ,
PRIMARY KEY(idRound)  ,
  FOREIGN KEY(Tournament_idTournament)
    REFERENCES Tournament(idTournament));


CREATE INDEX Round_FKIndex1 ON Round (Tournament_idTournament);


CREATE INDEX IFK_Rel_08 ON Round (Tournament_idTournament);


CREATE TABLE Settings (
  idSettings INTEGER   NOT NULL AUTO_INCREMENT,
  Tournament_idTournament INTEGER   NOT NULL   ,
  `victory` int(11) NOT NULL,
  `large_victory` int(11) NOT NULL,
  `draw` int(11) NOT NULL,
  `lost` int(11) NOT NULL,
  `little_lost` int(11) NOT NULL,
  `refused` int(11) NOT NULL,
  `conceeded` int(11) NOT NULL,
  `victory_team` int(11) NOT NULL,
  `draw_team` int(11) NOT NULL,
  `lost_team` int(11) NOT NULL,
  `large_victory_gap` int(11) NOT NULL,
  `little_lost_gap` int(11) NOT NULL,
  `rank1` int(11) NOT NULL,
  `rank2` int(11) NOT NULL,
  `rank3` int(11) NOT NULL,
  `rank4` int(11) NOT NULL,
  `rank5` int(11) NOT NULL,
  `rank1_team` int(11) NOT NULL,
  `rank2_team` int(11) NOT NULL,
  `rank3_team` int(11) NOT NULL,
  `rank4_team` int(11) NOT NULL,
  `rank5_team` int(11) NOT NULL,
  `byteam` tinyint(1) NOT NULL,
  `teammates` int(11) NOT NULL,
  `team_pairing` int(11) NOT NULL,
  `team_indiv_pairing` int(11) NOT NULL,
  `team_victory_points` int(11) NOT NULL,
  `team_draw_points` int(11) NOT NULL,
  `team_victory_only` tinyint(1) NOT NULL,
  `groups_enable` tinyint(1) NOT NULL,
  `substitutes` tinyint(1) NOT NULL,
  `game_type` int(11) NOT NULL,
  `activate_clans` tinyint(1) NOT NULL,
  `avoid_first_match` tinyint(1) NOT NULL,
  `avoid_match` tinyint(1) NOT NULL,
  `clan_teammates_number` tinyint(1) NOT NULL,
  `multi_roster` tinyint(1) NOT NULL,
  `individually_balanced` tinyint(1) NOT NULL,
  `team_balanced` tinyint(1) NOT NULL,
  `use_large_victory` tinyint(1) NOT NULL,
  `use_little_loss` tinyint(1) NOT NULL,
  `table_bonus` tinyint(1) NOT NULL,
  `table_bonus_per_round` tinyint(1) NOT NULL,
  `table_bonus_coeff` decimal(10,0) NOT NULL,
  `use_best_indiv` tinyint(1) NOT NULL,
  `use_best_team` tinyint(1) NOT NULL,
  `best_indiv_result` int(11) NOT NULL,
  `best_team_result` int(11) NOT NULL,
  `apply_to_annex_indiv` tinyint(1) NOT NULL,
  `appl_to_annex_team` tinyint(1) NOT NULL,
  `except_worst_and_best_indiv` tinyint(1) NOT NULL,
  `except_worst_and_best_team` tinyint(1) NOT NULL,
PRIMARY KEY(idSettings, Tournament_idTournament)  ,
  FOREIGN KEY(Tournament_idTournament)
    REFERENCES Tournament(idTournament));


CREATE INDEX Settings_FKIndex1 ON Settings (Tournament_idTournament);


CREATE INDEX IFK_Rel_03 ON Settings (Tournament_idTournament);


CREATE TABLE Criteria (
  idCriteria INTEGER   NOT NULL AUTO_INCREMENT,
  Settings_Tournament_idTournament INTEGER   NOT NULL ,
  Settings_idSettings INTEGER   NOT NULL ,
  Name VARCHAR(20)    ,
  Points_For INTEGER    ,
  Points_Against INTEGER    ,
  Points_Team_For INTEGER    ,
  Points_Team_Against INTEGER      ,
PRIMARY KEY(idCriteria)  ,
  FOREIGN KEY(Settings_idSettings, Settings_Tournament_idTournament)
    REFERENCES Settings(idSettings, Tournament_idTournament));


CREATE INDEX Criteria_FKIndex1 ON Criteria (Settings_idSettings, Settings_Tournament_idTournament);


CREATE INDEX IFK_Rel_02 ON Criteria (Settings_idSettings, Settings_Tournament_idTournament);


CREATE TABLE Clan (
  idClan INTEGER   NOT NULL AUTO_INCREMENT,
  Tournament_idTournament INTEGER   NOT NULL ,
  Name VARCHAR(45)    ,
  Picture TEXT      ,
PRIMARY KEY(idClan)  ,
  FOREIGN KEY(Tournament_idTournament)
    REFERENCES Tournament(idTournament));


CREATE INDEX Clan_FKIndex1 ON Clan (Tournament_idTournament);


CREATE INDEX IFK_Rel_04 ON Clan (Tournament_idTournament);


CREATE TABLE Ranking (
  idRanking INTEGER   NOT NULL AUTO_INCREMENT,
  Criteria_idCriteria INTEGER   NOT NULL ,
  Round_idRound INTEGER   NOT NULL ,
  Name VARCHAR(45)      ,
PRIMARY KEY(idRanking, Criteria_idCriteria)    ,
  FOREIGN KEY(Round_idRound)
    REFERENCES Round(idRound),
  FOREIGN KEY(Criteria_idCriteria)
    REFERENCES Criteria(idCriteria));


CREATE INDEX Ranking_FKIndex1 ON Ranking (Round_idRound);
CREATE INDEX Ranking_FKIndex2 ON Ranking (Criteria_idCriteria);


CREATE INDEX IFK_Rel_17 ON Ranking (Round_idRound);
CREATE INDEX IFK_Rel_18 ON Ranking (Criteria_idCriteria);


CREATE TABLE Position (
  idPosition INTEGER   NOT NULL AUTO_INCREMENT,
  Ranking_Criteria_idCriteria INTEGER   NOT NULL ,
  Ranking_idRanking INTEGER   NOT NULL ,
  Name VARCHAR(20)    ,
  Value1 INTEGER    ,
  Value2 INTEGER    ,
  Value3 INTEGER    ,
  Value4 INTEGER    ,
  Value5 INTEGER    ,
  Position INTEGER    ,
  Positive BOOL      ,
PRIMARY KEY(idPosition)  ,
  FOREIGN KEY(Ranking_idRanking, Ranking_Criteria_idCriteria)
    REFERENCES Ranking(idRanking, Criteria_idCriteria));


CREATE INDEX Position_FKIndex1 ON Position (Ranking_idRanking, Ranking_Criteria_idCriteria);


CREATE INDEX IFK_Rel_19 ON Position (Ranking_idRanking, Ranking_Criteria_idCriteria);


CREATE TABLE Team (
  idTeam INTEGER   NOT NULL AUTO_INCREMENT,
  Clan_idClan INTEGER   NOT NULL ,
  Name VARCHAR(45)    ,
  Picture TEXT      ,
PRIMARY KEY(idTeam)  ,
  FOREIGN KEY(Clan_idClan)
    REFERENCES Clan(idClan));


CREATE INDEX Team_FKIndex1 ON Team (Clan_idClan);


CREATE INDEX IFK_Rel_05 ON Team (Clan_idClan);


CREATE TABLE Coach (
  idCoach INTEGER   NOT NULL AUTO_INCREMENT,
  Tournament_idTournament INTEGER NOT NULL,
  Team_idTeam INTEGER ,
  Clan_idClan INTEGER ,
  Name VARCHAR(45)    ,
  Picture TEXT    ,
  Team VARCHAR(45)    ,
  Roster VARCHAR(20)    ,
  NAF INTEGER    ,
  Rank INTEGER    ,
  Handicap INTEGER      ,
PRIMARY KEY(idCoach)    ,
   FOREIGN KEY(Tournament_idTournament)
    REFERENCES Tournament(idTournament)
);


CREATE INDEX Coach_FKIndex1 ON Coach (Clan_idClan);
CREATE INDEX Coach_FKIndex2 ON Coach (Team_idTeam);


CREATE INDEX IFK_Rel_04 ON Coach (Clan_idClan);
CREATE INDEX IFK_Rel_06 ON Coach (Team_idTeam);


CREATE TABLE TeamMatch (
  idTeamMatch INTEGER   NOT NULL AUTO_INCREMENT,
  Team_idTeam INTEGER   NOT NULL ,
  Round_idRound INTEGER   NOT NULL   ,
PRIMARY KEY(idTeamMatch)      ,
  FOREIGN KEY(Round_idRound)
    REFERENCES Round(idRound),
  FOREIGN KEY(Team_idTeam)
    REFERENCES Team(idTeam),
  FOREIGN KEY(Team_idTeam)
    REFERENCES Team(idTeam));


CREATE INDEX TeamMatch_FKIndex1 ON TeamMatch (Round_idRound);
CREATE INDEX TeamMatch_FKIndex2 ON TeamMatch (Team_idTeam);
CREATE INDEX TeamMatch_FKIndex3 ON TeamMatch (Team_idTeam);


CREATE INDEX IFK_Rel_10 ON TeamMatch (Round_idRound);
CREATE INDEX IFK_Rel_15 ON TeamMatch (Team_idTeam);
CREATE INDEX IFK_Rel_16 ON TeamMatch (Team_idTeam);





CREATE TABLE CoachMatch (
  idCoachMatch INTEGER   NOT NULL AUTO_INCREMENT,
  Coach1_idCoach INTEGER   NOT NULL ,
  Coach2_idCoach INTEGER   NOT NULL ,
  Round_idRound INTEGER   NOT NULL ,
  TeamMatch_idTeamMatch INTEGER ,
  RefusedBy1	BOOLEAN,
  RefusedBy2	BOOLEAN,
  ConceededBy1	BOOLEAN,
  ConceededBy2	BOOLEAN,
PRIMARY KEY(idCoachMatch)        ,
  FOREIGN KEY(Round_idRound)
    REFERENCES Round(idRound),
  FOREIGN KEY(Coach1_idCoach)
    REFERENCES Coach(idCoach),
  FOREIGN KEY(Coach2_idCoach)
    REFERENCES Coach(idCoach));


CREATE INDEX CoachMatch_FKIndex2 ON CoachMatch (Round_idRound);
CREATE INDEX CoachMatch_FKIndex4 ON CoachMatch (Coach2_idCoach);
CREATE INDEX CoachMatch_FKIndex3 ON CoachMatch (Coach1_idCoach);
CREATE INDEX IFK_Rel_07 ON CoachMatch (TeamMatch_idTeamMatch);


CREATE TABLE CoachValue (
  idCoachValue INTEGER   NOT NULL AUTO_INCREMENT,
  CoachMatch_idCoachMatch INTEGER   NOT NULL ,
  Value1 INTEGER    ,
  Value2 INTEGER    ,
  CriteriaName VARCHAR(20)      ,
PRIMARY KEY(idCoachValue)  ,
  FOREIGN KEY(CoachMatch_idCoachMatch)
    REFERENCES CoachMatch(idCoachMatch));


CREATE INDEX IFK_Rel_12 ON CoachValue (CoachMatch_idCoachMatch);
