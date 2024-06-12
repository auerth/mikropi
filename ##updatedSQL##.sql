/*
 Private Flag for Cuts 27.10.2023
 */
ALTER TABLE `cut`
	ADD COLUMN `isPrivate` TINYINT(1) NOT NULL DEFAULT '0' AFTER `toDelete`;

/*END*/