INSERT INTO `ss_settings` (`key`, `value`) VALUES ('shop_name', '');
UPDATE `ss_settings`
SET `value` =(
    CASE
    WHEN (SELECT COUNT(*) FROM (SELECT * FROM `ss_settings`) as a WHERE `key` = 'license_login' AND `value` = 'license') > 0 THEN `value`
    ELSE SHA1(CONCAT((SELECT `value` FROM (SELECT * FROM `ss_settings`) as b WHERE `key` = 'license_login'), '#', (SELECT `value` FROM (SELECT * FROM `ss_settings`) as c WHERE `key` = 'license_password')))
    END
)
WHERE `key` = 'license_password';
UPDATE `ss_settings` SET `value` = 'license' WHERE `key` = 'license_login';
