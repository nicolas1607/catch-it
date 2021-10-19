-- DECLARE @json NVarChar(2048) = N'{ 
--     "email": "nicolas160796@gmail.com",
--     "password": "$2y$13$XOyg2hn/RUMWm/KxZL/qi.jay8L4TrAEOGe4UhwkH0yo.1.mSEXXq",
--     "roles": ["ROLE_ADMIN"]
-- }'

-- INSERT INTO user
-- SELECT * 
-- FROM OPENJSON(@json)
-- WITH  (
--         email      varchar         '$.email',  
--         password   varchar         '$.password', 
--         roles      nvarchar(max)   '$.roles' 
--     );

-- INSERT INTO user(email, password, firstname, lastname, contact, roles)
-- VALUES ('nicolas160796@gmail.com', '$2y$13$XOyg2hn/RUMWm/KxZL/qi.jay8L4TrAEOGe4UhwkH0yo.1.mSEXXq',
-- 'Nicolas', 'Mormiche', '0627712403', OPENJSON(@admin))



INSERT INTO album(id, name, created_at) VALUES (1, 'Lego', '2021-01-22');
INSERT INTO item(name, album_id) VALUES ('Millennium Falcon', 1);
INSERT INTO item(name, album_id) VALUES ('Stormtrooper', 1);
INSERT INTO item(name, album_id) VALUES ('BB-8', 1);
INSERT INTO item(name, album_id) VALUES ('R2-2', 1);
INSERT INTO item(name, album_id) VALUES ('Imperial Star Destroyer', 1);
INSERT INTO item(name, album_id) VALUES ('AT-AT', 1);
INSERT INTO item(name, album_id) VALUES ('The Mandalorian', 1);

INSERT INTO album(id, name, created_at) VALUES (2, 'Jeux vidéos', '2021-09-09');
INSERT INTO item(name, album_id) VALUES ('Last Of Us 2', 2);
INSERT INTO item(name, album_id) VALUES ('Destiny 2', 2);
INSERT INTO item(name, album_id) VALUES ('CyberPunk', 2);
INSERT INTO item(name, album_id) VALUES ('Fifa 22', 2);
INSERT INTO item(name, album_id) VALUES ('Horizon Zero Dawn', 2);
INSERT INTO item(name, album_id) VALUES ('Far Cry 6', 2);
INSERT INTO item(name, album_id) VALUES ('Alan Wake', 2);
INSERT INTO item(name, album_id) VALUES ('Call of Modern Warfare', 2);
INSERT INTO item(name, album_id) VALUES ('Tokyo Jungle', 2);
INSERT INTO item(name, album_id) VALUES ('Dark Soul', 2);
INSERT INTO item(name, album_id) VALUES ('Metal Gear Solid', 2);
INSERT INTO item(name, album_id) VALUES ('Red Dead Redemption 2', 2);
INSERT INTO item(name, album_id) VALUES ('Grand Theft Auto 5', 2);

INSERT INTO album(id, name, created_at) VALUES (3, 'Moog', '2021-05-16');
INSERT INTO item(name, album_id) VALUES ('Grandmother', 3);
INSERT INTO item(name, album_id) VALUES ('Mother-32', 3);
INSERT INTO item(name, album_id) VALUES ('Minitaur', 3);
INSERT INTO item(name, album_id) VALUES ('DFAM', 3);
INSERT INTO item(name, album_id) VALUES ('Theremini', 3);
INSERT INTO item(name, album_id) VALUES ('Subharmonicon', 3);

INSERT INTO album(id, name, created_at) VALUES (4, 'Roland', '2019-06-03');
INSERT INTO item(name, album_id) VALUES ('TB-03', 4);
INSERT INTO item(name, album_id) VALUES ('TR-09', 4);
INSERT INTO item(name, album_id) VALUES ('TB-303', 4);
INSERT INTO item(name, album_id) VALUES ('TR-8S', 4);
INSERT INTO item(name, album_id) VALUES ('TB-3', 4);
INSERT INTO item(name, album_id) VALUES ('TR-06', 4);
INSERT INTO item(name, album_id) VALUES ('Jupiter-X', 4);
INSERT INTO item(name, album_id) VALUES ('VR-730', 4);
INSERT INTO item(name, album_id) VALUES ('MC-707', 4);
INSERT INTO item(name, album_id) VALUES ('Fantom 6', 4);
INSERT INTO item(name, album_id) VALUES ('RD-2000', 4);
INSERT INTO item(name, album_id) VALUES ('TR-909', 4);

INSERT INTO album(id, name, created_at) VALUES (5, 'Daft Punk', '2019-07-30');
INSERT INTO item(name, album_id) VALUES ('Random Access Memories', 5);
INSERT INTO item(name, album_id) VALUES ('Discovery', 5);
INSERT INTO item(name, album_id) VALUES ('Homework', 5);
INSERT INTO item(name, album_id) VALUES ('Tron: Legacy', 5);
INSERT INTO item(name, album_id) VALUES ('Human After All', 5);
INSERT INTO item(name, album_id) VALUES ('Alive 2007', 5);
INSERT INTO item(name, album_id) VALUES ('Alive 1997', 5);
INSERT INTO item(name, album_id) VALUES ('Greatest Hits', 5);
INSERT INTO item(name, album_id) VALUES ('Deep Cuts', 5);
INSERT INTO item(name, album_id) VALUES ('Daft Club', 5);

INSERT INTO album(id, name, created_at) VALUES (6, 'Genshin Impact', '2021-10-18');
INSERT INTO item(name, album_id) VALUES ('Razor', 6);
INSERT INTO item(name, album_id) VALUES ('Kenya', 6);
INSERT INTO item(name, album_id) VALUES ('Xiao', 6);
INSERT INTO item(name, album_id) VALUES ('Xiangling', 6);
INSERT INTO item(name, album_id) VALUES ('Venti', 6);
INSERT INTO item(name, album_id) VALUES ('Sucrose', 6);
INSERT INTO item(name, album_id) VALUES ('Jean', 6);
INSERT INTO item(name, album_id) VALUES ('Kazuha', 6);
INSERT INTO item(name, album_id) VALUES ('Sayu', 6);
INSERT INTO item(name, album_id) VALUES ('Klee', 6);
INSERT INTO item(name, album_id) VALUES ('Benett', 6);
INSERT INTO item(name, album_id) VALUES ('Diluc', 6);
INSERT INTO item(name, album_id) VALUES ('Yanfey', 6);
INSERT INTO item(name, album_id) VALUES ('Yoimiya', 6);
INSERT INTO item(name, album_id) VALUES ('Shogun Raiden', 6);
INSERT INTO item(name, album_id) VALUES ('Chongyun', 6);
INSERT INTO item(name, album_id) VALUES ('Albedo', 6);
INSERT INTO item(name, album_id) VALUES ('Tartaglia', 6);
INSERT INTO item(name, album_id) VALUES ('Xingqiu', 6);