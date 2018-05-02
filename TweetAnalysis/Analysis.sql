SELECT REPLACE(State, "\"", "") FROM TweetData;

UPDATE TweetData SET State=REPLACE(State, "\"", "");

UPDATE StateMaster SET State=REPLACE(State, "\"", ""), Abv=REPLACE(Abv, "\"", "");

SELECT (CASE WHEN SUBSTRING_INDEX(State,', ',-1)="USA" THEN SUBSTRING_INDEX(State,', ',1) ELSE SUBSTRING_INDEX(State,', ',-1) END) AS StateAlias FROM TweetData;

UPDATE TweetData SET State=(CASE WHEN SUBSTRING_INDEX(State,', ',-1)="USA" THEN SUBSTRING_INDEX(State,', ',1) ELSE SUBSTRING_INDEX(State,', ',-1) END);

SELECT a.State FROM TweetData a INNER JOIN StateMaster b ON a.State = b.State;

UPDATE TweetData a INNER JOIN StateMaster b ON a.State=b.State SET a.State=b.Abv;

SELECT DISTINCT(State) FROM TweetData;

SELECT State, FavorOfGuns, COUNT(*) FROM TweetData WHERE FavorOfGuns != -2 GROUP BY State, FavorOfGuns;

SELECT State, MAX(CASE WHEN FavorOfGuns=0 THEN Cnt END) AS AgainstGuns, MAX(CASE WHEN FavorOfGuns=1 THEN Cnt END) AS FavorOf FROM (SELECT State, FavorOfGuns, COUNT(*) AS Cnt FROM TweetData WHERE FavorOfGuns != -2 GROUP BY State, FavorOfGuns) a GROUP BY State;

SELECT State, ABS(AgainstGuns-FavorOf) AS Diff FROM (SELECT State, MAX(CASE WHEN FavorOfGuns=0 THEN Cnt END) AS AgainstGuns, MAX(CASE WHEN FavorOfGuns=1 THEN Cnt END) AS FavorOf FROM (SELECT State, FavorOfGuns, COUNT(*) AS Cnt FROM TweetData WHERE FavorOfGuns != -2 GROUP BY State, FavorOfGuns) a GROUP BY State) b;

