-- MySQL dump 10.13 Distrib 5.7.34, for Linux (x86_64) -- -- Host: localhost Database: erp -- ------------------------------------------------------ 
-- Server version 5.7.34 /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */; /*!40101 SET 
@OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */; /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */; /*!40101 SET NAMES utf8 */; 
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */; /*!40103 SET TIME_ZONE='+00:00' */; /*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */; 
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */; /*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, 
SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */; /*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */; -- -- Table structure for table `audit_trails` -- DROP 
TABLE IF EXISTS `audit_trails`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`audit_trails` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_trails_user_id_foreign` (`user_id`),
  KEY `audit_trails_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `audit_trails_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON DELETE SET NULL ON UPDATE 
CASCADE,
  CONSTRAINT `audit_trails_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table 
`audit_trails` -- LOCK TABLES `audit_trails` WRITE; /*!40000 ALTER TABLE `audit_trails` DISABLE KEYS */;
INSERT INTO `audit_trails` VALUES 
('00146c5f-cba5-45fb-a704-8bd3bc30f1cf','ae0c23e3-985b-4f06-931a-94a813c67404','created Overall and Gumboots vet 
item','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 14:46:44','2022-02-05 
14:46:44',NULL),('029b21c9-57f8-41b6-8a1b-9f5c3f286f20','ae0c23e3-985b-4f06-931a-94a813c67404','Created 
254712345678account','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 18:00:51','2022-02-12 
18:00:51',NULL),('0410787e-bbd5-485a-a792-12263e3ed01a','ae0c23e3-985b-4f06-931a-94a813c67404','created Migori 
route','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 12:28:43','2021-12-17 
12:28:43',NULL),('05ece82d-430b-4be1-99ab-ca931eb1e37b','ae0c23e3-985b-4f06-931a-94a813c67404','created Leather 
Product','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:05:45','2021-12-20 
21:05:45',NULL),('062d926d-1372-407a-9cfd-52114b5cae57','ae0c23e3-985b-4f06-931a-94a813c67404','Created Employment type for Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:40:42','2021-11-20 
12:40:42',NULL),('07323dc0-f6d0-4589-90d3-cd85f60f3caf','ae0c23e3-985b-4f06-931a-94a813c67404','Created department Farmers Outreach to 
83b13245-7138-461f-b832-e5b4f2256a1b','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:57:49','2022-02-12 
16:57:49',NULL),('08697a5b-2372-4c51-aec1-42ba28f24912','ae0c23e3-985b-4f06-931a-94a813c67404','Created recruitment post ACCOUNTANTfor Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:54:56','2021-11-20 
12:54:56',NULL),('096e64dd-2b15-4b60-ba3b-8281d61565ed','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','created KCB Bank',NULL,'2021-07-23 
01:10:07','2021-07-23 01:10:07',NULL),('0aff5f36-435c-405f-b51c-1b8c5989244e','ae0c23e3-985b-4f06-931a-94a813c67404','created ELDAMA RAVINE 
route',NULL,'2021-07-24 10:43:17','2021-07-24 10:43:17',NULL),('0b8d338d-db2c-42c7-afa3-f2b08e0377fc','ae0c23e3-985b-4f06-931a-94a813c67404','Assigned 
farmer to Francis','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 12:27:34','2021-12-17 
12:27:34',NULL),('0dd22b44-87fe-4929-aa31-7808c0114dd3','597dfe2f-35c0-4a32-86c7-434901fe7269','Assigned cooperative admin to Dairy 
-James','349228b3-afe3-497a-8001-2d236fb6b50b','2022-01-13 17:07:59','2022-01-13 
17:07:59',NULL),('0eb07b32-d19f-49f5-94b9-901f5ed575e2','ae0c23e3-985b-4f06-931a-94a813c67404','Created 
m.njorogeaccount','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 16:05:32','2022-01-06 
16:05:32',NULL),('0f9c4db9-1800-4797-b588-84e6b68ca819','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','created Milk Product',NULL,'2021-07-23 
01:05:38','2021-07-23 01:05:38',NULL),('10194cc8-a2ba-4334-9e5e-2aa46884f802','ae0c23e3-985b-4f06-931a-94a813c67404','Deleted Production 
b3ee8c3e-7153-4a78-b0f6-0caca4281b1f','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 15:39:21','2022-01-06 
15:39:21',NULL),('109f3323-9710-4b75-bb4f-dc9ec4e41be0','ae0c23e3-985b-4f06-931a-94a813c67404','Created Production 
66a23ef4-44bc-4bc1-a3bf-193e116d7797for product 6ea99c4f-eae3-473d-bb02-588464b952c0','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-16 
21:16:49','2021-12-16 21:16:49',NULL),('10b56e26-394f-47a7-864f-765ba2beeda5','ae0c23e3-985b-4f06-931a-94a813c67404','created freshian 
Breed','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-04 16:54:37','2022-02-04 
16:54:37',NULL),('1117654f-36cf-4c03-b97e-87bbf9e09b6e','ae0c23e3-985b-4f06-931a-94a813c67404','created Foot and Mouth Disease ',NULL,'2021-07-24 
10:59:22','2021-07-24 10:59:22',NULL),('113525a5-d74f-439e-b6da-a523049805c6','597dfe2f-35c0-4a32-86c7-434901fe7269','Assigned cooperative admin to 
Smith',NULL,'2021-07-23 11:23:31','2021-07-23 11:23:31',NULL),('1257fe9f-c3cc-4374-9f96-4896a7f6ec72','ae0c23e3-985b-4f06-931a-94a813c67404','Created 
department PROCUREMENT to 6647f171-5f34-4b61-bc10-66896648739d','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:43:41','2021-11-20 
12:43:41',NULL),('1413a2d4-201a-4746-868b-426963259fac','ae0c23e3-985b-4f06-931a-94a813c67404','created Guernsey Cow',NULL,'2021-10-04 
12:36:02','2021-10-04 12:36:02',NULL),('14c07a84-e08a-4fe5-93cb-74c9e3a57ef2','ae0c23e3-985b-4f06-931a-94a813c67404','Created branch Kisii Branch to 
Milk Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:57:13','2022-02-12 
16:57:13',NULL),('150c3c84-23a7-4929-aae0-68e0bd67bb44','ae0c23e3-985b-4f06-931a-94a813c67404','created Cereals and Legumes 
Category','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:30:48','2022-02-12 
16:30:48',NULL),('15b26ffe-b511-4f6d-b3ca-3dc140908d88','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','created Mary Cow Cow',NULL,'2021-07-23 
01:14:58','2021-07-23 01:14:58',NULL),('15dd60ee-c036-4415-b532-21459185c5b8','ae0c23e3-985b-4f06-931a-94a813c67404','created Jersey 
Breed','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:42:19','2022-02-12 
16:42:19',NULL),('1b0549cb-39ee-44e3-a14a-c6f86c76e9e3','ae0c23e3-985b-4f06-931a-94a813c67404','Created branch Kisii to Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:54:17','2022-02-12 
16:54:17',NULL),('1cb4af09-a766-45fd-be2b-2efa112b2c36','ae0c23e3-985b-4f06-931a-94a813c67404','Updated leave #f815de01-7042-4866-a44b-a1dc09d63e6a 
for of Number: .P1234','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 16:16:27','2022-01-06 
16:16:27',NULL),('1dd9c934-4307-4b71-b4a8-38bf8ca99d5a','6131b2b3-7245-4363-b25d-d208721f8e12','Assigned vet to 
254723678563','de242fbd-8eb8-420e-9adc-394baa8c13a6','2021-12-17 06:51:17','2021-12-17 
06:51:17',NULL),('1e4fa6f5-9582-483d-879b-a77f6779636a','ae0c23e3-985b-4f06-931a-94a813c67404','created SHIKO Cow',NULL,'2021-07-24 
10:57:45','2021-07-24 10:57:45',NULL),('1e526fce-6613-4665-96a4-1c1c5d295d71','ae0c23e3-985b-4f06-931a-94a813c67404','created Fresian 
Breed',NULL,'2021-07-24 10:57:03','2021-07-24 10:57:03',NULL),('208803aa-ae58-4c17-8aa2-1433270088d1','ae0c23e3-985b-4f06-931a-94a813c67404','Created 
Robertaccount','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:57:39','2021-11-20 
12:57:39',NULL),('21b88a57-d33f-4895-be9e-60e1146fec15','ae0c23e3-985b-4f06-931a-94a813c67404','Created raw material 
78e4389a-a3e9-4812-a1bd-f9207fd180f4for product ','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 15:41:00','2022-01-06 
15:41:00',NULL),('241c6e2f-225a-4bde-9307-fb9add86cfbb','ae0c23e3-985b-4f06-931a-94a813c67404','Added collection of product 
#7e3ad0c9-b573-4d2b-b7c4-259df964398d from farmer #ae87e19d-a5a1-4927-9cd3-720beecf19ce','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 
16:43:31','2021-12-13 16:43:31',NULL),('252c7261-0964-4665-b96e-58f35d7d37d4','ae0c23e3-985b-4f06-931a-94a813c67404','created Zebu 
Breed','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:20:27','2021-12-20 
21:20:27',NULL),('26caf928-fe21-46b7-bc19-3ef0111b8f43','ae0c23e3-985b-4f06-931a-94a813c67404','created EQUITY BANK Bank',NULL,'2021-07-24 
10:45:49','2021-07-24 10:45:49',NULL),('26e9bd0e-cc41-4a90-916b-c18bfe98ae90','597dfe2f-35c0-4a32-86c7-434901fe7269','Assigned cooperative admin to 
Mwangi','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-20 18:02:32','2021-12-20 
18:02:32',NULL),('2706f046-917d-4827-95ab-806d17c14fa2','ae0c23e3-985b-4f06-931a-94a813c67404','created Kisumu 
route','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-27 15:27:53','2021-12-27 
15:27:53',NULL),('2767aa60-71f0-48d9-b696-8c7ff82a3d0f','ae0c23e3-985b-4f06-931a-94a813c67404','Created Job Position Quallity Assurance for Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 16:02:24','2022-01-06 
16:02:24',NULL),('2a11b9fc-cea4-4050-a962-08c362a2f9cd','ae0c23e3-985b-4f06-931a-94a813c67404','Assigned farmer to 
Susan','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 08:58:19','2021-12-17 
08:58:19',NULL),('2e07c74e-5f00-40a5-b9f4-1ca365a85079','ae0c23e3-985b-4f06-931a-94a813c67404','Assigned farmer to WANJIRU',NULL,'2021-07-24 
10:54:52','2021-07-24 10:54:52',NULL),('2fcd3ce4-fc2d-4baf-889c-d18e9e69dddb','ae0c23e3-985b-4f06-931a-94a813c67404','Created branch NAIROBI to Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:41:40','2021-11-20 
12:41:40',NULL),('30ffdf7a-8e38-48c9-90bc-f80a46997dca','ae0c23e3-985b-4f06-931a-94a813c67404','created Mango 
Product','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 01:29:23','2021-12-17 
01:29:23',NULL),('31945f00-edac-48ee-81be-d4ae271fa854','597dfe2f-35c0-4a32-86c7-434901fe7269','Optimized 
App','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-20 20:42:29','2021-12-20 
20:42:29',NULL),('31d59f33-015f-4c7a-9056-33bc50c037bb','ae0c23e3-985b-4f06-931a-94a813c67404','created FRUITS Category',NULL,'2021-07-24 
10:47:27','2021-07-24 10:47:27',NULL),('341fe2cc-c454-458c-8732-f01664dd6ab1','ae0c23e3-985b-4f06-931a-94a813c67404','Created final product Mixed 
fruit','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 15:02:36','2022-02-05 
15:02:36',NULL),('345ca57b-383a-45f7-954f-607af503039d','ae0c23e3-985b-4f06-931a-94a813c67404','Assigned farmer to 
jmwaniki','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 16:41:14','2021-12-13 
16:41:14',NULL),('377ec95c-57b7-4765-9e10-64c55f99ab34','ae0c23e3-985b-4f06-931a-94a813c67404','created Critical Disease Category',NULL,'2021-07-24 
10:58:50','2021-07-24 10:58:50',NULL),('37b40234-bfe4-4a78-b5ad-1b86d27235e5','ae0c23e3-985b-4f06-931a-94a813c67404','created Wakulima Sacco 
Bank','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:40:22','2022-02-12 
16:40:22',NULL),('385eae7e-45dc-48b2-920b-94bf300cfbed','ae0c23e3-985b-4f06-931a-94a813c67404','created Mary 
Cow','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:44:03','2022-02-12 
16:44:03',NULL),('3aed4ebd-d0ab-4121-b5f5-31339075a560','ae0c23e3-985b-4f06-931a-94a813c67404','created Meru Branch Bank 
Branch','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-04 13:32:16','2022-01-04 
13:32:16',NULL),('3c4f6e21-5bb7-4acc-88ce-fa4535109755','ae0c23e3-985b-4f06-931a-94a813c67404','Created Job Position Communications Officer for Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:59:47','2022-02-12 
16:59:47',NULL),('3c9d9579-fd69-4966-8337-c969e09b57c6','ae0c23e3-985b-4f06-931a-94a813c67404','created Zebu 
Cow','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:21:26','2021-12-20 
21:21:26',NULL),('3d47b77b-9353-4ef7-b9b3-dcb69969b63f','ae0c23e3-985b-4f06-931a-94a813c67404','Created final product Animal 
Feed','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 16:29:58','2021-12-13 
16:29:58',NULL),('3d93da93-d7d3-491c-84c3-0d29d4e4f473','ae0c23e3-985b-4f06-931a-94a813c67404','Created department MARKETING to 
6647f171-5f34-4b61-bc10-66896648739d','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:43:04','2021-11-20 
12:43:04',NULL),('3e92e46c-f1f8-4910-b334-8f6771eaf0e5','ae0c23e3-985b-4f06-931a-94a813c67404','Created final product 
Yoghurt','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 16:29:33','2021-12-13 
16:29:33',NULL),('3f086cea-6021-4ea9-900f-77526b27e2c4','597dfe2f-35c0-4a32-86c7-434901fe7269','Assigned cooperative admin to 
elvis','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-13 14:14:50','2021-12-13 
14:14:50',NULL),('3f2e3a88-65af-461f-b946-f336540c2f61','ae0c23e3-985b-4f06-931a-94a813c67404','created Watermelon 
Product','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:32:32','2022-02-12 
16:32:32',NULL),('3f66d36e-d538-47b2-853a-433fe4903074','ae0c23e3-985b-4f06-931a-94a813c67404','Added collection of product 
#24173b51-713b-40d7-bb97-0c1e7238021a from farmer #118b80b8-6427-4994-ab7a-ea09f677fba7','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 
12:31:04','2021-12-17 12:31:04',NULL),('3faddfc9-b0e9-42c3-817c-ef867efd1d59','ae0c23e3-985b-4f06-931a-94a813c67404','created Dry Pineapples 
Product','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 12:34:02','2021-12-17 
12:34:02',NULL),('400faa63-b957-43e2-8ab2-f93ba896fb9d','ae0c23e3-985b-4f06-931a-94a813c67404','created Fungus Disease 
Category','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 01:27:53','2021-12-17 
01:27:53',NULL),('4170e8ee-69f4-4941-92df-ef875aca6c18','ae0c23e3-985b-4f06-931a-94a813c67404','Created department ACCOUNTS to 
6647f171-5f34-4b61-bc10-66896648739d','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:42:43','2021-11-20 
12:42:43',NULL),('43125bac-f080-498e-a330-e4579e23e7da','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','created Dangerous Disease Category',NULL,'2021-07-23 
01:15:20','2021-07-23 01:15:20',NULL),('46f2d20d-b9db-4b68-a1ad-c6e9cee024e7','ae0c23e3-985b-4f06-931a-94a813c67404','Created raw material 
5200d0e8-b161-4233-944c-a2759e79b2f8for product ','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 15:41:44','2022-01-06 
15:41:44',NULL),('4783441b-8ecd-4d71-ab48-9a0bcf63cf18','ae0c23e3-985b-4f06-931a-94a813c67404','Created final product Packed 
Milk','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 18:07:21','2021-12-13 
18:07:21',NULL),('4867bbc3-9e20-48b9-a195-641a13a5fae2','ae0c23e3-985b-4f06-931a-94a813c67404','created Nyeri Branch Bank 
Branch','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:12:11','2021-12-20 
21:12:11',NULL),('490b7b0e-c97d-4805-9804-4ff00631bd6a','ae0c23e3-985b-4f06-931a-94a813c67404','created Miraa 
Product','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-04 13:30:49','2022-01-04 
13:30:49',NULL),('49a8e9aa-fe68-47ed-baf8-64ca824afbdb','ae0c23e3-985b-4f06-931a-94a813c67404','Created Production 
b3ee8c3e-7153-4a78-b0f6-0caca4281b1ffor product 32ab16ff-a397-44df-adf9-372adb42a38b','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 
12:42:07','2021-12-17 12:42:07',NULL),('4b352076-d69d-4e7d-8056-1b3c2f180f56','ae0c23e3-985b-4f06-931a-94a813c67404','Creating a booking for vet id 
3a1c5fd8-c546-4598-907f-cab1c4691673','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:53:05','2022-02-12 
16:53:05',NULL),('4d1c43c9-e207-454b-a745-01ade0246ef0','ae0c23e3-985b-4f06-931a-94a813c67404','Assigned vet to 
254712345678','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 18:00:51','2022-02-12 
18:00:51',NULL),('4e2ea08a-d011-44d2-b3e9-7389604bd51f','ae0c23e3-985b-4f06-931a-94a813c67404','Updated leave #acf8bd6c-a893-4e17-b58e-d7f75442cfe0 
for of Number: .0012','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 17:03:53','2022-02-12 
17:03:53',NULL),('4e673cee-d89a-4292-8700-2201a4ada621','ae0c23e3-985b-4f06-931a-94a813c67404','Created Job Position Procurement Manager for Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:45:31','2021-11-20 
12:45:31',NULL),('52204067-8b65-485c-a938-41f5b1eff3f1','ae0c23e3-985b-4f06-931a-94a813c67404','Add new customer 
','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-04 21:37:48','2022-02-04 
21:37:48',NULL),('543c1a43-1edb-4066-bcd1-2d4a59b414a9','ae0c23e3-985b-4f06-931a-94a813c67404','Add new customer 
','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 14:40:09','2022-02-05 
14:40:09',NULL),('5873e7f1-89ae-4d45-ad5f-8e87d3d427b6','ae0c23e3-985b-4f06-931a-94a813c67404','Assigned farmer to 
Catherine','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 12:20:40','2021-12-17 
12:20:40',NULL),('59693dcb-587c-44af-a5e0-4c84ee4854ed','ae0c23e3-985b-4f06-931a-94a813c67404','created Dangerous Disease Category',NULL,'2021-07-24 
10:58:31','2021-07-24 10:58:31',NULL),('5c579487-8a97-4bae-8ea8-8388f9e604b4','597dfe2f-35c0-4a32-86c7-434901fe7269','Optimized 
App','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-09 04:08:27','2021-12-09 
04:08:27',NULL),('60ab44c7-ee42-4cf1-a347-beb53fec8812','ae0c23e3-985b-4f06-931a-94a813c67404','created Fungicides vet 
item','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 09:05:37','2021-12-17 
09:05:37',NULL),('62272547-efd3-4d14-9dbb-84a2fe92abb3','ae0c23e3-985b-4f06-931a-94a813c67404','Deleted branch Nyeri belonging to Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 15:58:05','2022-01-06 
15:58:05',NULL),('636a9fb1-6553-4fca-82af-258b19c8d4bc','ae0c23e3-985b-4f06-931a-94a813c67404','created Contagious Disease 
Category','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:44:52','2022-02-12 
16:44:52',NULL),('65d1771a-3f46-42c7-b3c5-0b9bd9d9da3b','ae0c23e3-985b-4f06-931a-94a813c67404','Created leave for of Number: 
.','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 17:58:34','2021-12-13 
17:58:34',NULL),('66ace5f9-ab3e-4f47-8251-7468656d0d05','597dfe2f-35c0-4a32-86c7-434901fe7269','Optimized 
App','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-20 18:13:57','2021-12-20 
18:13:57',NULL),('67f44a76-2d9b-4fc6-a296-04a92bd6c5f4','ae0c23e3-985b-4f06-931a-94a813c67404','Created branch KIAMBU to Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:41:59','2021-11-20 
12:41:59',NULL),('68c0bbf3-4a26-465f-84ad-79c7827ebb4c','ae0c23e3-985b-4f06-931a-94a813c67404','created Jay 
Cow','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-04 16:55:06','2022-02-04 
16:55:06',NULL),('6b68e10b-2eb8-4764-a089-a5f1f4550b16','ae0c23e3-985b-4f06-931a-94a813c67404','created Kgs Unit',NULL,'2021-07-24 
10:48:28','2021-07-24 10:48:28',NULL),('6be83a14-0eee-470d-953c-8b917dd45d9d','597dfe2f-35c0-4a32-86c7-434901fe7269','Optimized 
App','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-20 17:37:55','2021-12-20 
17:37:55',NULL),('6e5e4ec2-f495-4fdc-b4f5-9abf38f00e12','ae0c23e3-985b-4f06-931a-94a813c67404','Add new customer 
','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 14:40:38','2022-02-05 
14:40:38',NULL),('6ead6f4c-ac9c-47ae-8349-2f07a44d5eff','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','created Dairy Category',NULL,'2021-07-23 
01:04:57','2021-07-23 01:04:57',NULL),('71abfb72-96ca-46ac-b7c1-621ccffa7e28','597dfe2f-35c0-4a32-86c7-434901fe7269','Optimized 
App','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-16 21:12:26','2021-12-16 
21:12:26',NULL),('73a775c4-75ee-45e9-b34f-1f718f3d08a8','ae0c23e3-985b-4f06-931a-94a813c67404','Created WANJIRUaccount',NULL,'2021-07-24 
10:54:52','2021-07-24 10:54:52',NULL),('73b5bfd2-198b-4d0e-bcd8-c2e115eef992','ae0c23e3-985b-4f06-931a-94a813c67404','created Cotton 
Product','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-27 15:32:39','2021-12-27 
15:32:39',NULL),('73cfe1ea-0e24-412b-97ed-ce0b89bddfa5','597dfe2f-35c0-4a32-86c7-434901fe7269','Created Muki DAIRY cooperative and user Dairy 
-Jamesaccount','349228b3-afe3-497a-8001-2d236fb6b50b','2022-01-13 17:07:59','2022-01-13 
17:07:59',NULL),('74d01c37-ff99-4fa6-9cca-4ae6e4a4a751','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','Created maryaccount',NULL,'2021-07-23 
01:13:23','2021-07-23 01:13:23',NULL),('768d276d-cc6f-45de-84e8-d574b9ec298f','ae0c23e3-985b-4f06-931a-94a813c67404','Created raw material 
445303e3-60f8-4303-abb2-8f977ac3955afor product 32ab16ff-a397-44df-adf9-372adb42a38b','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 
12:40:00','2021-12-17 12:40:00',NULL),('776e9aed-9d2b-4ede-b7e9-f43c4bbdbbc5','ae0c23e3-985b-4f06-931a-94a813c67404','Added collection of product 
#7e3ad0c9-b573-4d2b-b7c4-259df964398d from farmer #df5a78ad-f8a5-4ed0-a2fa-9ce0c8e195a0','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 
12:38:01','2021-11-20 12:38:01',NULL),('7928aa6f-4629-47b1-9eab-e1928cce5caf','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','created Nairobi 
route',NULL,'2021-07-23 00:57:52','2021-07-23 00:57:52',NULL),('79440f32-c0d0-4b46-8b2d-4cc0b02b151c','ae0c23e3-985b-4f06-931a-94a813c67404','created 
COOPERATIVE BANK Bank',NULL,'2021-07-24 10:46:24','2021-07-24 
10:46:24',NULL),('79e2267b-2c99-42af-b605-51d7528787e1','ae0c23e3-985b-4f06-931a-94a813c67404','Assigned farmer to MICHAEL',NULL,'2021-07-24 
10:56:18','2021-07-24 10:56:18',NULL),('7cea0369-2979-4b30-9c93-8f5e06ca02ba','ae0c23e3-985b-4f06-931a-94a813c67404','Added collection of product 
#7e3ad0c9-b573-4d2b-b7c4-259df964398d from farmer #df5a78ad-f8a5-4ed0-a2fa-9ce0c8e195a0','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 
12:37:32','2021-11-20 12:37:32',NULL),('7eabe4f5-18e6-47fe-bc3d-e5070ed50ce3','597dfe2f-35c0-4a32-86c7-434901fe7269','Optimized 
App','349228b3-afe3-497a-8001-2d236fb6b50b','2022-02-04 15:55:47','2022-02-04 
15:55:47',NULL),('7ee71a88-8d59-4d29-ac0a-727a2dca0233','ae0c23e3-985b-4f06-931a-94a813c67404','Assigned vet to 
254720800800','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 16:49:52','2021-12-13 
16:49:52',NULL),('80335088-706e-4e0f-b897-d22a052d41dd','ae0c23e3-985b-4f06-931a-94a813c67404','Created recruitment post Communications Officerfor 
Milk Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 17:08:47','2022-02-12 
17:08:47',NULL),('82d176de-e7cd-4b32-977b-6f0783f2ca59','ae0c23e3-985b-4f06-931a-94a813c67404','Assigned farmer to 
LMuthoni','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:16:15','2021-12-20 
21:16:15',NULL),('855361e4-b0fe-4817-8870-37549c519de1','ae0c23e3-985b-4f06-931a-94a813c67404','Created final product Mango 
Juice','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 01:30:34','2021-12-17 
01:30:34',NULL),('89453baa-73d5-4831-999c-efb4af383e32','ae0c23e3-985b-4f06-931a-94a813c67404','created Vaccination vet 
service','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:25:37','2021-12-20 
21:25:37',NULL),('89c462bd-904c-45da-be24-00d5430ec709','ae0c23e3-985b-4f06-931a-94a813c67404','Created final product Dried 
Fruit','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 12:38:57','2021-12-17 
12:38:57',NULL),('8a2c4c4c-ec32-4049-8ac1-574953c31db4','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','Assigned farmer to mary',NULL,'2021-07-23 
01:13:23','2021-07-23 01:13:23',NULL),('8acd044d-48fb-4a9a-911b-9a0f42b7717e','ae0c23e3-985b-4f06-931a-94a813c67404','Assigned employee to 
m.njoroge','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 16:05:32','2022-01-06 
16:05:32',NULL),('8b672b89-e584-44d4-843c-7bafe126e0d9','ae0c23e3-985b-4f06-931a-94a813c67404','created Powdery mildew Disease 
','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 01:27:40','2021-12-17 
01:27:40',NULL),('8b9fc1c8-7d6e-4408-b724-414fe4939329','ae0c23e3-985b-4f06-931a-94a813c67404','Created Job Position ACCOUNTANT for Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:44:45','2021-11-20 
12:44:45',NULL),('8d2e2d92-6d32-4435-99b4-cb60a3e36179','ae0c23e3-985b-4f06-931a-94a813c67404','Created final product Watermelon 
Juice','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 17:20:15','2022-02-12 
17:20:15',NULL),('8d6803ad-6911-4c4f-87fe-50e8a42b6a6c','ae0c23e3-985b-4f06-931a-94a813c67404','created Mombasa 
route','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 09:02:14','2021-12-17 
09:02:14',NULL),('8d7a9451-4c41-4964-ab6d-ca8b965cce30','ae0c23e3-985b-4f06-931a-94a813c67404','Created MICHAELaccount',NULL,'2021-07-24 
10:56:18','2021-07-24 10:56:18',NULL),('8d871b01-a00e-409f-b577-2120dec8f692','ae0c23e3-985b-4f06-931a-94a813c67404','Created Employment type for Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 16:12:10','2022-01-06 
16:12:10',NULL),('8d987563-3a37-4657-a0a6-052ec5b772d8','ae0c23e3-985b-4f06-931a-94a813c67404','created Kisii Branch Bank 
Branch','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:41:37','2022-02-12 
16:41:37',NULL),('9099ec78-5b69-4113-ab26-af6d7b030dae','ae0c23e3-985b-4f06-931a-94a813c67404','created Syringes vet 
item','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:27:12','2021-12-20 
21:27:12',NULL),('90d0c20e-2d82-4496-97bc-1d87474f3123','597dfe2f-35c0-4a32-86c7-434901fe7269','Assigned cooperative admin to 
Nyawira','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-20 17:35:31','2021-12-20 
17:35:31',NULL),('911893cb-9825-4de3-adfb-741350b6fcc5','ae0c23e3-985b-4f06-931a-94a813c67404','Assigned employee to 
Robert','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:57:39','2021-11-20 
12:57:39',NULL),('920d7172-bce6-48f2-ab8a-236bee404446','ae0c23e3-985b-4f06-931a-94a813c67404','Add new customer 
','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 15:50:40','2022-01-06 
15:50:40',NULL),('924f5712-e4bb-4761-a0aa-1f79f0c2d626','ae0c23e3-985b-4f06-931a-94a813c67404','Creating a booking for vet id 
3a1c5fd8-c546-4598-907f-cab1c4691673','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-03-01 18:08:22','2022-03-01 
18:08:22',NULL),('9291e4e2-3261-4904-818c-a6538a5b05d2','ae0c23e3-985b-4f06-931a-94a813c67404','Added collection of product 
#7e3ad0c9-b573-4d2b-b7c4-259df964398d from farmer #1cedfe6e-b533-4bcf-bbb6-20eeca602617','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 
16:28:16','2021-12-13 16:28:16',NULL),('92e3fb97-5f73-4d62-bd2a-d0b587305d77','ae0c23e3-985b-4f06-931a-94a813c67404','created MERU Bank 
Branch',NULL,'2021-07-24 10:51:21','2021-07-24 10:51:21',NULL),('95ec76e2-8920-4a33-bdff-8cf56804c97b','ae0c23e3-985b-4f06-931a-94a813c67404','Created 
recruitment post Quallity Assurancefor Milk Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 16:11:33','2022-01-06 
16:11:33',NULL),('970a0291-5d56-4b33-ba26-ec9bdd628778','ae0c23e3-985b-4f06-931a-94a813c67404','created COFFEE Product',NULL,'2021-07-24 
10:49:44','2021-07-24 10:49:44',NULL),('97bbc62d-994a-45d9-be4d-a958b3064bee','ae0c23e3-985b-4f06-931a-94a813c67404','created Foot and mouth Disease 
','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:45:55','2022-02-12 
16:45:55',NULL),('9a73a73d-b64e-4233-a3e8-2eb244daaf6a','ae0c23e3-985b-4f06-931a-94a813c67404','created Checkups vet 
service','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:46:49','2022-02-12 
16:46:49',NULL),('9cf67dea-b947-4a53-90a1-16a77d6daad3','ae0c23e3-985b-4f06-931a-94a813c67404','Created branch BARINGO to Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:42:19','2021-11-20 
12:42:19',NULL),('9d345f8a-83be-4f4a-b619-207d4eeeaa70','ae0c23e3-985b-4f06-931a-94a813c67404','created Cotton 
Category','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-27 15:29:58','2021-12-27 
15:29:58',NULL),('9db837cf-bb01-4dc6-ae6d-1ddaee18aa0d','ae0c23e3-985b-4f06-931a-94a813c67404','created Nyeri 
route','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 20:54:36','2021-12-20 
20:54:36',NULL),('9dc5a43a-2db5-435e-b1d6-30c09e4c1398','ae0c23e3-985b-4f06-931a-94a813c67404','Created leave for of Number: 
.','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 17:05:47','2022-02-12 
17:05:47',NULL),('a0e259cf-1078-4e75-a3c0-d437eb7b7041','ae0c23e3-985b-4f06-931a-94a813c67404','Created 
Francisaccount','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 12:27:34','2021-12-17 
12:27:34',NULL),('a14ae7a8-1cf4-42f1-8fb3-0564d516e28d','ae0c23e3-985b-4f06-931a-94a813c67404','Created 
LMuthoniaccount','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:16:15','2021-12-20 
21:16:15',NULL),('a21c4539-2df3-4bce-9d04-0eff9288844a','ae0c23e3-985b-4f06-931a-94a813c67404','Created 
Jane.Kaccount','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:00:42','2021-12-20 
21:00:42',NULL),('a226a734-2faf-4a72-8814-0e7f2358a3cc','597dfe2f-35c0-4a32-86c7-434901fe7269','Optimized 
App','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-23 19:14:11','2021-12-23 
19:14:11',NULL),('a2520397-1657-4dc5-99df-cdd6d5fdeb07','ae0c23e3-985b-4f06-931a-94a813c67404','created Freshian 
Cow','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:22:17','2021-12-20 
21:22:17',NULL),('a25943ab-fd42-4acc-823b-f1e2c21763b8','ae0c23e3-985b-4f06-931a-94a813c67404','created Meru route',NULL,'2021-07-24 
10:43:08','2021-07-24 10:43:08',NULL),('a28a7870-5f87-44da-b2a9-f758b79b98a2','597dfe2f-35c0-4a32-86c7-434901fe7269','Optimized 
App','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-13 14:12:27','2021-12-13 
14:12:27',NULL),('a4a65f59-8788-4e4d-bd68-06739c9ec630','ae0c23e3-985b-4f06-931a-94a813c67404','Created raw material 
948dd09e-0574-4b13-bbba-d1c51d111a74for product ','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 17:21:19','2022-02-12 
17:21:19',NULL),('a591c2a9-ab19-4c61-84af-e4d1ad6aa184','ae0c23e3-985b-4f06-931a-94a813c67404','created AI SERVICE vet 
service','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-07 08:29:27','2021-12-07 
08:29:27',NULL),('a5f64bf3-6f3c-4a78-bf77-d0f6d587e0b0','ae0c23e3-985b-4f06-931a-94a813c67404','created Guernsey Breed',NULL,'2021-07-24 
10:57:13','2021-07-24 10:57:13',NULL),('a833b5de-a998-4558-90dd-0bb71d088f97','ae0c23e3-985b-4f06-931a-94a813c67404','created Feeds 
Product',NULL,'2021-07-25 10:21:33','2021-07-25 
10:21:33',NULL),('a95ada3c-86d1-46a1-8990-76ff6a861c4b','597dfe2f-35c0-4a32-86c7-434901fe7269','Created Embu Farmers Cooperative cooperative and user 
Nyawiraaccount','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-20 17:35:31','2021-12-20 
17:35:31',NULL),('aaf0203b-9549-4cb9-b228-d7399ec3b02f','597dfe2f-35c0-4a32-86c7-434901fe7269','Created Milk Cooperative cooperative and user 
Smithaccount',NULL,'2021-07-23 11:23:31','2021-07-23 
11:23:31',NULL),('ac395322-1f29-43f3-8bbb-b55b25086dd0','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','created Kgs Unit',NULL,'2021-07-23 
01:04:23','2021-07-23 01:04:23',NULL),('adde011a-86bf-4868-9653-2f45ea33e6b0','ae0c23e3-985b-4f06-931a-94a813c67404','created Crop Spraying vet 
service','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 09:05:08','2021-12-17 
09:05:08',NULL),('ae73b451-3532-4f7c-a520-90a3976e9539','ae0c23e3-985b-4f06-931a-94a813c67404','Added collection of product 
#6e8ea072-2c88-4be1-9bba-c4f2f36b8b71 from farmer #8b2f71f2-af8c-47cf-a4e0-d429a51f9306','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 
21:19:26','2021-12-20 21:19:26',NULL),('aed9ba2e-4ac9-4009-a627-aa98ce9fe394','ae0c23e3-985b-4f06-931a-94a813c67404','created Kiambu 
route',NULL,'2021-07-24 10:42:58','2021-07-24 10:42:58',NULL),('b01a3bb4-c53c-44c3-8717-78408ca7351f','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','created 
Litres Unit',NULL,'2021-07-23 01:04:09','2021-07-23 
01:04:09',NULL),('b1cbe3a5-6036-4473-aacd-f4b8d083f26e','ae0c23e3-985b-4f06-931a-94a813c67404','created Dry Fruits 
Category','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 12:34:46','2021-12-17 
12:34:46',NULL),('b21561be-04e2-4ffe-a431-53f330275eae','ae0c23e3-985b-4f06-931a-94a813c67404','created ELDAMA RAVINE Bank Branch',NULL,'2021-07-24 
10:52:09','2021-07-24 10:52:09',NULL),('b4198487-01f9-4ca1-95b6-7ecfbc50281b','ae0c23e3-985b-4f06-931a-94a813c67404','Created 
Catherineaccount','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 12:20:40','2021-12-17 
12:20:40',NULL),('b467c174-50f9-4be6-ade0-15c6c8f617ff','ae0c23e3-985b-4f06-931a-94a813c67404','Added collection of product 
#1ca1979b-f2a2-4208-9e9a-60b4fec70f80 from farmer #118b80b8-6427-4994-ab7a-ea09f677fba7','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 
12:36:08','2021-12-17 12:36:08',NULL),('b49037d2-203c-4d94-bea0-089014f7d364','ae0c23e3-985b-4f06-931a-94a813c67404','created Bacterial canker and 
blast Disease ','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 01:28:19','2021-12-17 
01:28:19',NULL),('b5ad28ad-0236-4191-a0c0-5b7c0221dea4','ae0c23e3-985b-4f06-931a-94a813c67404','Created leave for of Number: 
.','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 16:08:14','2022-01-06 
16:08:14',NULL),('b601b07f-c6e3-49f2-bae0-05314afc8a15','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','created Fruits Category',NULL,'2021-07-23 
01:04:38','2021-07-23 01:04:38',NULL),('b675e434-7227-43d6-b049-3dd5c03bfb9c','ae0c23e3-985b-4f06-931a-94a813c67404','created MILK 
Product',NULL,'2021-07-24 10:49:12','2021-07-24 
10:49:12',NULL),('b67cf46c-38f7-40d5-bf46-213d6380afa2','ae0c23e3-985b-4f06-931a-94a813c67404','created CASH CROPS Category',NULL,'2021-07-24 
10:47:39','2021-07-24 10:47:39',NULL),('b8c9ec52-b871-4e59-b167-097b7dc04b6d','ae0c23e3-985b-4f06-931a-94a813c67404','Deleted branch Kisii belonging 
to Milk Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:54:46','2022-02-12 
16:54:46',NULL),('b958bb2e-41c9-4851-89b3-ef2097b19a22','ae0c23e3-985b-4f06-931a-94a813c67404','created SICKLE CELL Disease ',NULL,'2021-10-04 
12:37:00','2021-10-04 12:37:00',NULL),('b99a2aa6-7999-41db-b836-373cbf20dddc','ae0c23e3-985b-4f06-931a-94a813c67404','created Taita 
route','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 09:02:04','2021-12-17 
09:02:04',NULL),('baa6355f-fe83-45a0-b2c3-c2b857fc1028','ae0c23e3-985b-4f06-931a-94a813c67404','Created department PRODUCTION to 
b3abe66f-b049-4be2-9448-0a3f95801c96','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 16:00:43','2022-01-06 
16:00:43',NULL),('bbececce-317c-45b0-bca5-62df8971f43a','ae0c23e3-985b-4f06-931a-94a813c67404','Created Employment type for Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:40:48','2021-11-20 
12:40:48',NULL),('bc3db2a7-981f-45ae-a101-a93995db57b2','ae0c23e3-985b-4f06-931a-94a813c67404','Created raw material 
bc1dc281-a87d-4a1e-9d35-87cb27435583for product ','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 15:13:01','2022-01-06 
15:13:01',NULL),('bd912bb5-8f99-4589-bc41-fcbd34a57c7b','ae0c23e3-985b-4f06-931a-94a813c67404','Created final product 
Cloth','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 15:08:52','2022-01-06 
15:08:52',NULL),('beeda1d4-8287-48ef-a8ec-4b827c6964fc','ae0c23e3-985b-4f06-931a-94a813c67404','created Mild Disease Category',NULL,'2021-07-24 
10:59:00','2021-07-24 10:59:00',NULL),('c0731e55-5a5e-4598-b077-1cfea4b716b2','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','created Freshian 
Breed',NULL,'2021-07-23 01:13:56','2021-07-23 01:13:56',NULL),('c20df535-26be-46ea-9655-635e56c40c2f','ae0c23e3-985b-4f06-931a-94a813c67404','Assigned 
farmer to Jane.K','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:00:42','2021-12-20 
21:00:42',NULL),('c2899b94-d0db-4101-bcb9-12a67ae39634','597dfe2f-35c0-4a32-86c7-434901fe7269','Assigned cooperative admin to 
J.Kamau','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-20 20:37:19','2021-12-20 
20:37:19',NULL),('c2bbbfe1-b5ba-4e87-8674-f8d46ec55ae4','6131b2b3-7245-4363-b25d-d208721f8e12','Created 
254723678563account','de242fbd-8eb8-420e-9adc-394baa8c13a6','2021-12-17 06:51:17','2021-12-17 
06:51:17',NULL),('c3205cf4-b8aa-4f17-8fc0-39578956dc8c','ae0c23e3-985b-4f06-931a-94a813c67404','Created 
254726796059account','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 17:48:45','2021-12-13 
17:48:45',NULL),('c3b00a65-b376-4796-b089-9e83c3119b1d','ae0c23e3-985b-4f06-931a-94a813c67404','Deleted Production 
66a23ef4-44bc-4bc1-a3bf-193e116d7797','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 14:57:13','2022-02-05 
14:57:13',NULL),('c5a901f9-7dac-4d7c-a4b5-dd33d7f25970','ae0c23e3-985b-4f06-931a-94a813c67404','Created branch Nyeri to Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 15:57:38','2022-01-06 
15:57:38',NULL),('c5cd918d-6e63-46df-80f6-527d7e33d74b','ae0c23e3-985b-4f06-931a-94a813c67404','Add new customer 
','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 14:31:35','2022-02-05 
14:31:35',NULL),('c69128b5-24e3-4b6a-98e3-aaed56d9fdcb','ae0c23e3-985b-4f06-931a-94a813c67404','created LITRES Unit',NULL,'2021-07-24 
10:48:38','2021-07-24 10:48:38',NULL),('c7557d24-114f-4b98-8927-d77fc3e0b8af','597dfe2f-35c0-4a32-86c7-434901fe7269','Created Maziwa Dairy cooperative 
and user J.Kamauaccount','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-20 20:37:19','2021-12-20 
20:37:19',NULL),('c8690554-e1cb-44db-ac5c-7e8037c20339','ae0c23e3-985b-4f06-931a-94a813c67404','Add new customer 
','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 14:40:03','2022-02-05 
14:40:03',NULL),('c981003d-1ccd-45c4-b1ca-d2af536f8eab','ae0c23e3-985b-4f06-931a-94a813c67404','created Metre 
Unit','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 15:08:05','2022-01-06 
15:08:05',NULL),('cb69ed3c-8a3c-4bfa-8dfa-2ae3a3a4b172','ae0c23e3-985b-4f06-931a-94a813c67404','Added collection of product 
#7e3ad0c9-b573-4d2b-b7c4-259df964398d from farmer #1cedfe6e-b533-4bcf-bbb6-20eeca602617','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 
12:31:38','2021-11-20 12:31:38',NULL),('cbe50d87-9bde-483d-b493-c7a7159434de','ae0c23e3-985b-4f06-931a-94a813c67404','created Ticks Disease 
','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:24:05','2021-12-20 
21:24:05',NULL),('cddaba8d-c0a7-42bc-a49b-72467e17a31c','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','created Nairobi Bank Branch',NULL,'2021-07-23 
01:10:56','2021-07-23 01:10:56',NULL),('cec1d792-594a-4723-b420-8229bd3704a7','ae0c23e3-985b-4f06-931a-94a813c67404','created KCB BANK 
Bank',NULL,'2021-07-24 10:46:57','2021-07-24 10:46:57',NULL),('d173d755-bf96-4cd8-a0c8-c1980a84fdb2','597dfe2f-35c0-4a32-86c7-434901fe7269','Created 
Test Coop cooperative and user elvisaccount','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-13 14:14:50','2021-12-13 
14:14:50',NULL),('d2e425de-8ef9-401f-a611-07131ef63305','ae0c23e3-985b-4f06-931a-94a813c67404','created SEMEN vet 
item','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 16:47:24','2021-12-13 
16:47:24',NULL),('d2e68c04-bacf-448d-9352-14d6089264ae','597dfe2f-35c0-4a32-86c7-434901fe7269','Created African Best Cooperative cooperative and user 
johnaccount',NULL,'2021-07-23 00:55:26','2021-07-23 
00:55:26',NULL),('d47bcefc-4c0b-48f2-9fb8-7dabdf99cf1b','ae0c23e3-985b-4f06-931a-94a813c67404','created Mastitis Disease 
','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:23:38','2021-12-20 
21:23:38',NULL),('d5147390-8458-4dae-a685-1eb6e72e0176','ae0c23e3-985b-4f06-931a-94a813c67404','Created Employment type for Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:40:55','2021-11-20 
12:40:55',NULL),('d9544da2-0da4-408c-a446-42868d61500a','ae0c23e3-985b-4f06-931a-94a813c67404','created KIAMBU Bank Branch',NULL,'2021-07-24 
10:51:42','2021-07-24 10:51:42',NULL),('d9c4df9a-6fa3-4789-a51f-4ad969b266da','ae0c23e3-985b-4f06-931a-94a813c67404','Created 
254720800800account','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 16:49:52','2021-12-13 
16:49:52',NULL),('dc417273-3a02-43f7-b05b-566a154f4fd6','ae0c23e3-985b-4f06-931a-94a813c67404','Deleted employment type Permanent belonging to Milk 
Cooperative','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 16:12:23','2022-01-06 
16:12:23',NULL),('dcf08da4-c211-493a-affc-e7bb07238fd0','ae0c23e3-985b-4f06-931a-94a813c67404','created Vaccines vet 
item','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:48:12','2022-02-12 
16:48:12',NULL),('dea03fa3-e3bf-4ed4-adf3-55e060b59327','ae0c23e3-985b-4f06-931a-94a813c67404','created Kisumu Branch Bank 
Branch','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-27 15:38:02','2021-12-27 
15:38:02',NULL),('deae0984-83fb-4d2a-aed7-f785ccee13f8','ae0c23e3-985b-4f06-931a-94a813c67404','created KILO 2 Cow',NULL,'2021-07-24 
10:58:06','2021-07-24 10:58:06',NULL),('df1df400-a246-4ce8-8503-178f479a6206','ae0c23e3-985b-4f06-931a-94a813c67404','Creating a booking for vet id 
79291b6c-7f9d-467f-be92-d2fafe6d2e18','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 14:43:57','2022-02-05 
14:43:57',NULL),('e21b5dba-6980-42a7-8191-51e5c4bdc988','ae0c23e3-985b-4f06-931a-94a813c67404','created Rift Valley Fever Disease ',NULL,'2021-07-24 
10:59:39','2021-07-24 10:59:39',NULL),('e7082e53-2a34-4102-bccc-c3c499d54954','ae0c23e3-985b-4f06-931a-94a813c67404','Created 
Susanaccount','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 08:58:19','2021-12-17 
08:58:19',NULL),('e7e46141-321b-4d7f-a824-f6416107d1a8','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','created Sleeping Sickness Disease ',NULL,'2021-07-23 
01:16:08','2021-07-23 01:16:08',NULL),('e8107e0f-05da-471a-a450-2282c4e53613','ae0c23e3-985b-4f06-931a-94a813c67404','Created raw material 
960074c1-d554-4575-87dd-77fbe62bee9efor product ','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 15:00:59','2022-02-05 
15:00:59',NULL),('e8769dc0-4ebf-4b66-a5e4-a5cedab6d050','ae0c23e3-985b-4f06-931a-94a813c67404','Creating a booking for vet id 
79291b6c-7f9d-467f-be92-d2fafe6d2e18','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 16:59:56','2021-12-13 
16:59:56',NULL),('eac500ba-adf6-408b-8aca-b5a5d27d73ee','ae0c23e3-985b-4f06-931a-94a813c67404','created Leather 
Category','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:02:36','2021-12-20 
21:02:36',NULL),('eb2e04cb-1f79-47a8-b7cc-e637c2d8e122','597dfe2f-35c0-4a32-86c7-434901fe7269','Created Kiambu Fruits Cooperation cooperative and user 
Mwangiaccount','349228b3-afe3-497a-8001-2d236fb6b50b','2021-12-20 18:02:32','2021-12-20 
18:02:32',NULL),('eb802435-1938-42d2-8812-1183d180b4eb','ae0c23e3-985b-4f06-931a-94a813c67404','Assigned vet to 
254726796059','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 17:48:45','2021-12-13 
17:48:45',NULL),('edb3a356-9d5a-41a0-8180-23f5c3a0822f','ae0c23e3-985b-4f06-931a-94a813c67404','Creating a booking for vet id 
3a1c5fd8-c546-4598-907f-cab1c4691673','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 09:06:34','2021-12-17 
09:06:34',NULL),('ee171663-c0e2-4f32-a770-59618936d5b5','597dfe2f-35c0-4a32-86c7-434901fe7269','Assigned cooperative admin to john',NULL,'2021-07-23 
00:55:26','2021-07-23 00:55:26',NULL),('ef5ed61e-fa29-4621-bf90-260fd96a7fa5','ae0c23e3-985b-4f06-931a-94a813c67404','created Ounces 
Unit','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:30:06','2022-02-12 
16:30:06',NULL),('f8c76a2c-136f-4577-9db6-074c1fe501a2','ae0c23e3-985b-4f06-931a-94a813c67404','created Kisii 
route','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:28:04','2022-02-12 
16:28:04',NULL),('f9080675-c1a4-4c3a-988e-3a84d58ad4f9','ae0c23e3-985b-4f06-931a-94a813c67404','Created raw material 
c7a15d4e-74c7-4d45-93e0-5e741e88033dfor product ','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-11 15:20:32','2022-01-11 
15:20:32',NULL),('fe214031-6bec-4106-811c-cdd174600f40','ae0c23e3-985b-4f06-931a-94a813c67404','created DAIRY PRODUCTS Category',NULL,'2021-07-24 
10:47:17','2021-07-24 10:47:17',NULL),('ffb76770-e984-46e0-aad4-949f94893827','ae0c23e3-985b-4f06-931a-94a813c67404','Created 
jmwanikiaccount','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 16:41:14','2021-12-13 16:41:14',NULL); /*!40000 ALTER TABLE `audit_trails` ENABLE 
KEYS */; UNLOCK TABLES; -- -- Table structure for table `bank_branches` -- DROP TABLE IF EXISTS `bank_branches`; /*!40101 SET @saved_cs_client = 
@@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `bank_branches` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bank_branches_cooperative_id_foreign` (`cooperative_id`),
  KEY `bank_branches_bank_id_foreign` (`bank_id`),
  CONSTRAINT `bank_branches_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `banks` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `bank_branches_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON DELETE SET NULL ON UPDATE 
CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping 
data for table `bank_branches` -- LOCK TABLES `bank_branches` WRITE; /*!40000 ALTER TABLE `bank_branches` DISABLE KEYS */; INSERT INTO `bank_branches` 
VALUES ('1ed06bd5-b8e1-4fab-9c30-077fdb7ab4e1','MERU','M-001','MERU 
TOWN','5dae83b2-e858-4d55-922b-5295b8e9f81f','f16bcbb3-5f82-4eee-9354-39c0d5f994cd','2021-07-24 10:51:21','2021-07-24 
10:51:21',NULL),('352e5c92-701f-4acd-b319-887cfc89abc9','Kisii Branch','Waku1234','Kisii Town, 
Kisii','5dae83b2-e858-4d55-922b-5295b8e9f81f','eb27b25b-fda7-41e0-b900-2e9f592e558f','2022-02-12 16:41:37','2022-02-12 
16:41:37',NULL),('49b7a1e7-4356-4eec-97ed-f72a014be544','Nyeri 
Branch','2547133333333','Nyeri','5dae83b2-e858-4d55-922b-5295b8e9f81f','f16bcbb3-5f82-4eee-9354-39c0d5f994cd','2021-12-20 21:12:11','2021-12-20 
21:12:11',NULL),('56027680-d5ba-410a-aa65-bd47a25b626c','ELDAMA RAVINE','COOP- 
01','BARINGO','5dae83b2-e858-4d55-922b-5295b8e9f81f','59d809d4-808b-4eb8-81c4-f84ce3aff1e9','2021-07-24 10:52:09','2021-07-24 
10:52:09',NULL),('5665be56-d7fc-42ac-b3db-dbc2fea0ba8c','Meru 
Branch','254-012','Meru','5dae83b2-e858-4d55-922b-5295b8e9f81f','59d809d4-808b-4eb8-81c4-f84ce3aff1e9','2022-01-04 13:32:16','2022-01-04 
13:32:16',NULL),('684087c5-34a8-437b-a782-305347ac1f9c','Kisumu 
Branch','254-042','Kisumu','5dae83b2-e858-4d55-922b-5295b8e9f81f','ef7be2da-dc13-4965-94e5-81439527656b','2021-12-27 15:38:02','2021-12-27 
15:38:02',NULL),('cedf450e-602c-49d6-bc81-9465667ed3df','Nairobi','NAIKCB56','Moi Av. 
Street','4995bc45-7b46-473c-8658-76d78011ae0c','d2804eb3-4802-4897-a70a-2381a643ad8a','2021-07-23 01:10:56','2021-07-23 
01:10:56',NULL),('e3f5589b-ff2d-411c-9ca6-c04b5e71362f','KIAMBU','K-0001','KIAMBU','5dae83b2-e858-4d55-922b-5295b8e9f81f','ef7be2da-dc13-4965-94e5-81439527656b','2021-07-24 
10:51:42','2021-07-24 10:51:42',NULL); /*!40000 ALTER TABLE `bank_branches` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `banks` -- 
DROP TABLE IF EXISTS `banks`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`banks` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `swift_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `banks_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `banks_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for 
table `banks` -- LOCK TABLES `banks` WRITE; /*!40000 ALTER TABLE `banks` DISABLE KEYS */; INSERT INTO `banks` VALUES 
('59d809d4-808b-4eb8-81c4-f84ce3aff1e9','COOPERATIVE BANK','254781002001','COOPKEN','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 
10:46:24','2021-07-24 
10:46:24',NULL),('d2804eb3-4802-4897-a70a-2381a643ad8a','KCB','254717567890','KCBKE254','4995bc45-7b46-473c-8658-76d78011ae0c','2021-07-23 
01:10:07','2021-07-23 01:10:07',NULL),('eb27b25b-fda7-41e0-b900-2e9f592e558f','Wakulima 
Sacco','254345678901','Waku1234','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:40:22','2022-02-12 
16:40:22',NULL),('ef7be2da-dc13-4965-94e5-81439527656b','EQUITY BANK','254711202450','EQBLKN','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 
10:45:49','2021-07-24 10:45:49',NULL),('f16bcbb3-5f82-4eee-9354-39c0d5f994cd','KCB 
BANK','254741852936','KCBKLNE','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:46:57','2021-07-24 10:46:57',NULL); /*!40000 ALTER TABLE `banks` 
ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `breeds` -- DROP TABLE IF EXISTS `breeds`; /*!40101 SET @saved_cs_client = 
@@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `breeds` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `breeds_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `breeds_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `breeds` -- LOCK TABLES `breeds` 
WRITE; /*!40000 ALTER TABLE `breeds` DISABLE KEYS */; INSERT INTO `breeds` VALUES 
('258ea13a-2508-4aba-99c1-99596d7547bc','freshian','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-04 16:54:37','2022-02-04 
16:54:37',NULL),('2ab01eb0-1358-48e2-8c99-9c612ebede55','Freshian','4995bc45-7b46-473c-8658-76d78011ae0c','2021-07-23 01:13:56','2021-07-23 
01:13:56',NULL),('49bd07fe-6d2c-417e-881d-e8df0c18c319','Jersey','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:42:19','2022-02-12 
16:42:19',NULL),('864f70ed-7fa7-4eea-84da-35060ae291c6','Guernsey','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:57:13','2021-07-24 
10:57:13',NULL),('9e198881-925a-4f74-98de-5d07232355ca','Fresian','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:57:03','2021-07-24 
10:57:03',NULL),('d67f9fca-862a-4d06-b949-15f34787ab28','Zebu','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:20:27','2021-12-20 
21:20:27',NULL); /*!40000 ALTER TABLE `breeds` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `categories` -- DROP TABLE IF EXISTS 
`categories`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `categories` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `categories_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON DELETE SET NULL ON UPDATE CASCADE 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for 
table `categories` -- LOCK TABLES `categories` WRITE; /*!40000 ALTER TABLE `categories` DISABLE KEYS */; INSERT INTO `categories` VALUES 
('131de6de-1708-43f5-98a0-6f6dbea42cea','CASH CROPS','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:47:39','2021-07-24 
10:47:39',NULL),('2dd259d8-220a-4f85-b15b-365f1c63be43','Cotton','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-27 15:29:58','2021-12-27 
15:29:58',NULL),('50284caf-f9ea-4cf5-b034-b6b3d7ad0e97','Fruits','4995bc45-7b46-473c-8658-76d78011ae0c','2021-07-23 01:04:38','2021-07-23 
01:04:38',NULL),('78953dc4-c0c8-49b8-82b3-c756ead581ed','DAIRY PRODUCTS','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:47:17','2021-07-24 
10:47:17',NULL),('7ad0fe16-0901-4f13-9da6-3e4f2751b44d','Cereals and Legumes','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:30:48','2022-02-12 
16:30:48',NULL),('7be640cf-b3c2-4172-8934-0a33aa6411da','Dry Fruits','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 12:34:46','2021-12-17 
12:34:46',NULL),('8aa37bac-42ef-44a4-a2f2-bf69316308e3','FRUITS','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:47:27','2021-07-24 
10:47:27',NULL),('ba46a958-2755-4575-a929-ae8fa1060ba8','Leather','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 21:02:36','2021-12-20 
21:02:36',NULL),('d6faa137-9c23-4aa8-b387-08fe07921f5d','Dairy','4995bc45-7b46-473c-8658-76d78011ae0c','2021-07-23 01:04:57','2021-07-23 
01:04:57',NULL); /*!40000 ALTER TABLE `categories` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `collections` -- DROP TABLE IF 
EXISTS `collections`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`collections` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `farmer_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_collected` date NOT NULL,
  `agent_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `comments` longtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `collection_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `batch_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `available_quantity` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `collections_farmer_id_foreign` (`farmer_id`),
  KEY `collections_product_id_foreign` (`product_id`),
  KEY `collections_agent_id_foreign` (`agent_id`),
  KEY `collections_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `collections_agent_id_foreign` FOREIGN KEY (`agent_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `collections_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `collections_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `collections_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `collections` -- 
LOCK TABLES `collections` WRITE; /*!40000 ALTER TABLE `collections` DISABLE KEYS */; INSERT INTO `collections` VALUES 
('47447704-27c5-4244-9683-d790af900562','ae87e19d-a5a1-4927-9cd3-720beecf19ce','7e3ad0c9-b573-4d2b-b7c4-259df964398d','2000','1','2021-01-01',NULL,'5dae83b2-e858-4d55-922b-5295b8e9f81f','Kiambu 
Route','2021-12-13 16:43:31','2021-12-13 
16:43:31',NULL,'CR212112131031',NULL,NULL),('5bac6277-9a56-4f35-83a5-70d9317f60bb','df5a78ad-f8a5-4ed0-a2fa-9ce0c8e195a0','7e3ad0c9-b573-4d2b-b7c4-259df964398d','100','1','2021-01-01',NULL,'5dae83b2-e858-4d55-922b-5295b8e9f81f','ROUTE 
11','2021-11-20 12:37:32','2021-11-20 
12:37:32',NULL,NULL,NULL,NULL),('add791a7-a3d8-437d-877b-46f73e8c4a0a','1cedfe6e-b533-4bcf-bbb6-20eeca602617','7e3ad0c9-b573-4d2b-b7c4-259df964398d','45100','1','2021-01-01',NULL,'5dae83b2-e858-4d55-922b-5295b8e9f81f','Litres 
of Milk','2021-12-13 16:28:16','2021-12-13 
16:28:16',NULL,'CR212112131016',NULL,NULL),('c5a9a129-4641-4fbb-85d2-a35e8755e4bc','8b2f71f2-af8c-47cf-a4e0-d429a51f9306','6e8ea072-2c88-4be1-9bba-c4f2f36b8b71','89.4','1','2021-01-01',NULL,'5dae83b2-e858-4d55-922b-5295b8e9f81f','Leather 
collected','2021-12-20 21:19:26','2021-12-20 
21:19:26',NULL,'CR212112200326',NULL,NULL),('e7d75cff-6b03-4525-9772-6a98424d2f2d','118b80b8-6427-4994-ab7a-ea09f677fba7','1ca1979b-f2a2-4208-9e9a-60b4fec70f80','100','1','2021-01-01',NULL,'5dae83b2-e858-4d55-922b-5295b8e9f81f','Dry 
Pineapples','2021-12-17 12:36:08','2021-12-17 
12:36:08',NULL,'CR212112170608',NULL,NULL),('ed18d355-4245-43d9-8ed6-9d1b6944100c','1cedfe6e-b533-4bcf-bbb6-20eeca602617','7e3ad0c9-b573-4d2b-b7c4-259df964398d','10','1','2021-01-01',NULL,'5dae83b2-e858-4d55-922b-5295b8e9f81f','MILK 
COLLECTION','2021-11-20 12:31:38','2021-11-20 
12:31:38',NULL,NULL,NULL,NULL),('f74ac305-f105-4700-9ddd-db79a91d234c','118b80b8-6427-4994-ab7a-ea09f677fba7','24173b51-713b-40d7-bb97-0c1e7238021a','400','1','2021-01-01',NULL,'5dae83b2-e858-4d55-922b-5295b8e9f81f','Mango 
collection per week 2 December','2021-12-17 12:31:04','2021-12-17 
12:31:04',NULL,'CR212112170604',NULL,NULL),('fec1d2a3-2818-4422-96c1-20bf520a46e7','df5a78ad-f8a5-4ed0-a2fa-9ce0c8e195a0','7e3ad0c9-b573-4d2b-b7c4-259df964398d','300','1','2021-01-01',NULL,'5dae83b2-e858-4d55-922b-5295b8e9f81f','MILK 
COLLECTION','2021-11-20 12:38:01','2021-11-20 12:38:01',NULL,NULL,NULL,NULL); /*!40000 ALTER TABLE `collections` ENABLE KEYS */; UNLOCK TABLES; -- -- 
Table structure for table `coop_branch_departments` -- DROP TABLE IF EXISTS `coop_branch_departments`; /*!40101 SET @saved_cs_client = 
@@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `coop_branch_departments` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `office_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coop_branch_departments_branch_id_foreign` (`branch_id`),
  CONSTRAINT `coop_branch_departments_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `coop_branches` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table 
`coop_branch_departments` -- LOCK TABLES `coop_branch_departments` WRITE; /*!40000 ALTER TABLE `coop_branch_departments` DISABLE KEYS */; INSERT INTO 
`coop_branch_departments` VALUES ('114d7485-934d-4963-9b1a-fcc598e3eb6d','PRODUCTION','Production 
01','1','b3abe66f-b049-4be2-9448-0a3f95801c96','2022-01-06 16:00:43','2022-01-06 
16:00:43',NULL),('13e9b2dc-7b35-458a-b5fc-c147fd3ace51','ACCOUNTS','ACC 001','1','6647f171-5f34-4b61-bc10-66896648739d','2021-11-20 
12:42:43','2021-11-20 
12:42:43',NULL),('3095398f-e59c-49a7-9bda-75d4515f014f','PROCUREMENT','003','3','6647f171-5f34-4b61-bc10-66896648739d','2021-11-20 
12:43:41','2021-11-20 12:43:41',NULL),('32cd4776-39d2-45ac-9848-c657662c8a53','MARKETING','002','2','6647f171-5f34-4b61-bc10-66896648739d','2021-11-20 
12:43:04','2021-11-20 12:43:04',NULL),('69100694-1837-4965-848f-181cc68f1c85','Farmers 
Outreach','Kisii-01','01','83b13245-7138-461f-b832-e5b4f2256a1b','2022-02-12 16:57:49','2022-02-12 16:57:49',NULL); /*!40000 ALTER TABLE 
`coop_branch_departments` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `coop_branches` -- DROP TABLE IF EXISTS `coop_branches`; 
/*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `coop_branches` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coop_branches_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `coop_branches_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table 
`coop_branches` -- LOCK TABLES `coop_branches` WRITE; /*!40000 ALTER TABLE `coop_branches` DISABLE KEYS */; INSERT INTO `coop_branches` VALUES 
('6647f171-5f34-4b61-bc10-66896648739d','NAIROBI','NBO1','5dae83b2-e858-4d55-922b-5295b8e9f81f','UPPER HILL','2021-11-20 12:41:40','2021-11-20 
12:41:40',NULL),('83b13245-7138-461f-b832-e5b4f2256a1b','Kisii Branch','Kisii-01','5dae83b2-e858-4d55-922b-5295b8e9f81f','Kisii','2022-02-12 
16:57:13','2022-02-12 16:57:13',NULL),('8c192e50-63c2-4fc0-a1d2-bc7698ea80cf','BARINGO','BAR003','5dae83b2-e858-4d55-922b-5295b8e9f81f','ELDAMA 
RAVINE','2021-11-20 12:42:19','2021-11-20 12:42:19',NULL),('b3abe66f-b049-4be2-9448-0a3f95801c96','KIAMBU','KIAMBU 
02','5dae83b2-e858-4d55-922b-5295b8e9f81f','KIAMBU TOWN','2021-11-20 12:41:59','2021-11-20 
12:41:59',NULL),('cc89671b-81ab-4cc6-946b-4b1b1e522de0','Kisii','Kisii-01','5dae83b2-e858-4d55-922b-5295b8e9f81f','Kisii','2022-02-12 
16:54:17','2022-02-12 16:54:46','2022-02-12 
16:54:46'),('d1a8181d-edf4-4d90-9624-b619cca880ef','Nyeri','Nyeri019','5dae83b2-e858-4d55-922b-5295b8e9f81f','Nyeri','2022-01-06 15:57:38','2022-01-06 
15:58:05','2022-01-06 15:58:05'); /*!40000 ALTER TABLE `coop_branches` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `coop_employees` 
-- DROP TABLE IF EXISTS `coop_employees`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; 
CREATE TABLE `coop_employees` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `county_of_residence` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_of_residence` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marital_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kra` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nhif_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nssf_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `department_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `coop_employees_country_id_foreign` (`country_id`),
  KEY `coop_employees_department_id_foreign` (`department_id`),
  KEY `coop_employees_user_id_foreign` (`user_id`),
  CONSTRAINT `coop_employees_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `coop_employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `coop_branch_departments` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `coop_employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `coop_employees` -- 
LOCK TABLES `coop_employees` WRITE; /*!40000 ALTER TABLE `coop_employees` DISABLE KEYS */; INSERT INTO `coop_employees` VALUES 
('bc9b4c78-9056-4f61-85cd-dda81fadd137','1ea59c59-8243-426d-b1bb-ff1c9bc93d2f','Nairobi','Nairobi','Married','1979-10-24','Male','123456789','254789233004','0012','7845000','4444','5555','13e9b2dc-7b35-458a-b5fc-c147fd3ace51','10412d4d-4db7-4999-93c4-7e5b8f452582',1,'2021-11-20 
12:57:39','2021-11-20 
12:57:39',NULL),('d24ca95a-316a-424a-8db9-871aa38b1a37','1ea59c59-8243-426d-b1bb-ff1c9bc93d2f','Kiambu','Thindigwa','Single','1997-01-21','Female','3456789','254733456789','P1234','A0222224','12345678','12345678','114d7485-934d-4963-9b1a-fcc598e3eb6d','4d327539-ef33-45c0-97ba-50b419577f9f',1,'2022-01-06 
16:05:32','2022-01-06 16:05:32',NULL); /*!40000 ALTER TABLE `coop_employees` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table 
`cooperative_payment_configs` -- DROP TABLE IF EXISTS `cooperative_payment_configs`; /*!40101 SET @saved_cs_client = @@character_set_client */; 
/*!40101 SET character_set_client = utf8 */; CREATE TABLE `cooperative_payment_configs` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shortcode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `consumer_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `consumer_secret` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `passkey` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `initiator_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `initiator_pass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cooperative_payment_configs_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `cooperative_payment_configs_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON UPDATE CASCADE ) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for 
table `cooperative_payment_configs` -- LOCK TABLES `cooperative_payment_configs` WRITE; /*!40000 ALTER TABLE `cooperative_payment_configs` DISABLE 
KEYS */; INSERT INTO `cooperative_payment_configs` VALUES 
('1','600978','Test','b2c','xXJoz3klAOzIkGFbtK0bA9lQeMucAoRG','K0vwa2rLwWxZDbJX','bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919','testapi','Safaricom978!','1','349228b3-afe3-497a-8001-2d236fb6b50b',NULL,NULL),('2','174379','Test','c2b','xXJoz3klAOzIkGFbtK0bA9lQeMucAoRG','K0vwa2rLwWxZDbJX','bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919','testapi','Safaricom978!','1','40801158-8d2a-49f5-920c-8e9b3f94240c',NULL,NULL); 
/*!40000 ALTER TABLE `cooperative_payment_configs` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `cooperatives` -- DROP TABLE IF 
EXISTS `cooperatives`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`cooperatives` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abbreviation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rate_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_details` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'KSH',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cooperatives_email_unique` (`email`),
  UNIQUE KEY `cooperatives_contact_details_unique` (`contact_details`),
  KEY `cooperatives_country_id_foreign` (`country_id`),
  CONSTRAINT `cooperatives_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for 
table `cooperatives` -- LOCK TABLES `cooperatives` WRITE; /*!40000 ALTER TABLE `cooperatives` DISABLE KEYS */; INSERT INTO `cooperatives` VALUES 
('349228b3-afe3-497a-8001-2d236fb6b50b','Shamba 
Equity','ERP','1ea59c59-8243-426d-b1bb-ff1c9bc93d2f','Nairobi','','Nairobi','erp@shambaequity.co.ke','254716345621',NULL,'KSH','2021-07-22 
12:37:58',NULL,NULL),('40801158-8d2a-49f5-920c-8e9b3f94240c','Kiambu Fruits 
Cooperation','KFC','b2aa937e-c4c0-4a14-8d0a-665d7f71b2fb','Kiambu','','Kiambu','fmwangi811@gmail.com','254707191540',NULL,'KSh','2021-12-20 
18:02:32','2021-12-20 18:02:32',NULL),('4995bc45-7b46-473c-8658-76d78011ae0c','African Best 
Cooperative','ABC','1ea59c59-8243-426d-b1bb-ff1c9bc93d2f','Nairobi','','Nairobi','coop@gmail.com','254712345678',NULL,'KES','2021-07-23 
00:55:26','2021-07-23 00:55:26',NULL),('5c348d8a-b850-49cb-9383-2a8cf723f933','Embu Farmers 
Cooperative','EFC','1ea59c59-8243-426d-b1bb-ff1c9bc93d2f','Embu','','0001 00024 Embu','embufco@gmail.com','254700112233',NULL,'KSh','2021-12-20 
17:35:31','2021-12-20 17:35:31',NULL),('5dae83b2-e858-4d55-922b-5295b8e9f81f','Milk 
Cooperative','Milk','b2aa937e-c4c0-4a14-8d0a-665d7f71b2fb','Kiambu','','41200','milk@gmail.com','254784512004',NULL,'KES','2021-07-23 
11:23:31','2021-07-23 11:23:31',NULL),('68dc8065-6920-4138-8f9e-912d93f51ed7','Maziwa 
Dairy','MD','b2aa937e-c4c0-4a14-8d0a-665d7f71b2fb','Nyeri','','Nyeri','maziwadairy@coop','254711111111',NULL,'KSH','2021-12-20 20:37:19','2021-12-20 
20:37:19',NULL),('de242fbd-8eb8-420e-9adc-394baa8c13a6','Test 
Coop','TC','1ea59c59-8243-426d-b1bb-ff1c9bc93d2f','Nairobi','','Nairobi','elvismutende@gmail.com','254717796059',NULL,'Ksh','2021-12-13 
14:14:50','2021-12-13 14:14:50',NULL),('f8be7d28-5260-445c-b30b-7e16733d8cd5','Muki 
DAIRY','MUKI','b2aa937e-c4c0-4a14-8d0a-665d7f71b2fb','Nairobi','','451230','gmwangi13@gmail.com','254784500421',NULL,'KES','2022-01-13 
17:07:59','2022-01-13 17:07:59',NULL); /*!40000 ALTER TABLE `cooperatives` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `countries` 
-- DROP TABLE IF EXISTS `countries`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE 
TABLE `countries` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- 
-- Dumping data for table `countries` -- LOCK TABLES `countries` WRITE; /*!40000 ALTER TABLE `countries` DISABLE KEYS */; INSERT INTO `countries` 
VALUES ('05545c71-5fec-42dd-858a-6984b691623e','Guadeloupe','gp','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('05727175-7d68-4aed-9aed-b53cca7969f7','Guinea','gn','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('06af42ce-7e72-4afd-9e57-3069ae782ba6','Croatia','hr','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('06b1e1d5-6283-441a-84b8-62f3a6813535','Nauru','nr','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('0848e116-739c-4754-8751-702cda697c3e','Bhutan','bt','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('08ccc574-4c88-4442-a84e-7b7046a7d938','Sweden','se','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('0a6781cc-a87f-45cf-bf8d-2b3113110777','Senegal','sn','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('0ae37bfa-4f78-44a0-a914-689b801bd2b1','Eswatini','sz','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('0b70fe7f-7b42-4247-8b27-8fa893222f7e','UK','gb','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('0b8c4680-9320-4def-8e33-4ffa94d86b56','Ireland','ie','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('0e37832b-2590-4446-a73d-60cd9071d400','Honduras','hn','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('1056bc86-187f-4070-9fee-986be5cf9377','Japan','jp','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('112be52e-7ba9-47be-a4e9-aa0348565bd2','Bulgaria','bg','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('11ebb397-972a-493b-a17f-9cd309739682','Sierra Leone','sl','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('121acacf-6432-4fcb-831f-a78bb8050cac','St. Martin','mf','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('136a3ec8-5be0-4e35-a40e-d27489cc6649','Micronesia','fm','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('145800c9-5918-49c1-8ffe-62c3cfdd5b47','Oman','om','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('156180e6-a1a7-4eaf-bf6c-0b7ce0e3bdd4','Norway','no','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('15876826-cb25-44c7-b38c-12440bf408d1','St. Lucia','lc','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('15a34834-d86e-4c7a-9925-079522f38ef7','Andorra','ad','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('1a338fdf-8fed-46c3-8e1f-37b919042584','Switzerland','ch','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('1a4a3999-c173-4196-9fe5-1d98344d8cc0','Puerto Rico','pr','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('1ae8bf9c-8fb9-4af9-a593-9cbe7de4ea8e','Eritrea','er','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('1b034675-b97d-421b-899a-6a1e48c8aa65','Congo - Kinshasa','cd','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('1c303f7e-c14c-4506-8c21-8507a2787a84','Venezuela','ve','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('1c7c21f8-35e3-4b47-87b4-b74a32bf68d8','Bahrain','bh','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('1ea59c59-8243-426d-b1bb-ff1c9bc93d2f','Kenya','ke','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('2147d05f-323d-43cc-9eac-bb7b5fd4aec7','Samoa','ws','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('215d7c1c-ae63-436c-9d5d-b8ed759fe9af','Kazakhstan','kz','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('2561c5be-656c-4af4-a65b-a6706f323d5f','Dominica','dm','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('2565358b-a1ad-48e2-8657-b01c4ee0919f','Algeria','dz','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('2655c18e-b4f5-4569-ae7e-c01cd45e2d03','Armenia','am','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('273081e5-1f6a-4587-9ec6-227b9336b0d8','Mozambique','mz','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('27cb1fdb-16fd-4fbb-900a-e8ecfe3aa75b','Belgium','be','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('27de80e3-8145-4bd1-85a5-868281a34174','Burundi','bi','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('28a74e7c-1f87-426e-bc21-28cb241e925f','Bosnia','ba','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('2a32a8a1-edc2-4cdf-a4aa-8cb6b0e4744f','Zambia','zm','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('2ad4b8a4-e7b5-4abb-8b7a-f8edb00eb20a','Brazil','br','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('2cc9d8c4-20f2-4c81-a9d5-123d655d7c30','St. Pierre & Miquelon','pm','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('2d04daef-8fa1-4f3b-a47a-afb3ba181d7f','Mayotte','yt','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('2d492132-a285-45f5-8e63-0946f1e1a635','Papua New Guinea','pg','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('2dab1b3c-02e6-4248-890d-4bab6b460f39','Tokelau','tk','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('2e0fe30e-7689-437a-8a3f-bdb850219c0a','Germany','de','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('2fabd046-cbfc-48dc-a437-7829e3e77d93','Portugal','pt','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('3304a3ea-a443-4e21-8775-9489ec6fd8a3','Turkmenistan','tm','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('333e42ea-cd24-4940-a86b-6438dfb6f844','Guam','gu','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('33717384-d654-49d0-b95e-fb707c813450','Chad','td','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('36c4f79f-4a1e-4a39-b107-16937234fcfd','Cyprus','cy','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('36cb40eb-55b3-4863-a4c7-b7afca99f41b','Togo','tg','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('37e39c21-d498-4026-97f8-544f0d210409','Lesotho','ls','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('38c3b108-7cd1-4c6a-8489-4e920e3051b1','Iceland','is','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('391c60c9-8379-438d-b246-5efd392402f6','Equatorial Guinea','gq','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('3a0ef22f-158d-4e38-9c5c-9de48157acec','Italy','it','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('3a723d13-6869-41ec-87a3-072217e6340c','Cameroon','cm','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('3accc42f-3789-4d37-897b-44fd806e5fb2','Congo - Brazzaville','cg','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('3b7f8853-4df5-4764-87e6-7e0eb3a80a34','Nigeria','ng','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('3c06060e-0b26-4897-8fe0-459618e68927','Uganda','ug','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('3d6defab-b26f-44e3-bec8-6b3f7bb6a96a','Tuvalu','tv','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('4063509f-08e9-4a2e-83dc-7ecc7947f0c1','Singapore','sg','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('40aa00b5-6dc6-4c73-a5be-6c73ab1b58ca','Grenada','gd','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('46135d1d-9b39-4896-b5ee-e5c9735175bc','South Africa','za','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('463c926b-9fff-4e92-977d-5f7e5a67787c','Vietnam','vn','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('48335bb8-745d-4084-a978-9e48bb392cd7','Spain','es','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('48a6d25e-5a81-4359-a868-17b5441b8d74','Madagascar','mg','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('4908c958-c302-4176-95e0-2e34acd32062','Finland','fi','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('4a04d5c0-a266-42f2-a9a6-8baaeaee0bd8','Greenland','gl','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('4a2e3d18-a3fa-4bef-8cea-de1881f2d522','Namibia','na','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('4ca1280f-5c29-4ea8-8bbc-3fe4c1e4cd60','British Indian Ocean Territory','io','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('4cf3f01e-c5ec-4ec3-b23a-7831af066fd5','St. Vincent & Grenadines','vc','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('4d8986dc-5c37-48fc-8a9d-c6e994f969d7','Taiwan','tw','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('4da2a646-0a35-4bbe-a954-574066fe6ba4','Thailand','th','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('4de197c8-72bb-46b6-80aa-a1728cd4f7ec','Latvia','lv','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('519c1bb6-35cb-4ddd-a49e-ef96eb07d982','US','us','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('54ed04b2-2ed0-4893-9c09-02e69dd45520','Burkina Faso','bf','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('577584ec-89a1-4447-9f4e-82afe48af01c','French Southern Territories','tf','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('57da80c9-5891-47b9-9276-bec879c7e082','Morocco','ma','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('581b934a-fa04-4faa-bf56-64a75df384d8','North Macedonia','mk','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('5853c4fc-632c-4d65-85bf-cd55192d45c6','Wallis & Futuna','wf','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('58d3243d-1910-4a4e-8605-2823af94987f','Botswana','bw','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('590a7d50-12a2-48d5-9523-866a95daa5f0','Montserrat','ms','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('592c4ee5-18ae-4e4e-97b2-bc6172f86af9','Niue','nu','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('59ad9421-7170-4257-965e-7eb271211329','Kyrgyzstan','kg','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('5a8e35e6-d5a7-4d50-8336-2d5cd5018fe8','Saudi Arabia','sa','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('5c213816-d902-4b20-9b27-70c519f86089','Israel','il','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('5c45200c-9400-4332-91cc-1a98749357fa','Czechia','cz','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('5c81945f-a971-4410-985c-26a4aa897860','Netherlands','nl','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('5cccf16e-9e39-41ac-a5a7-935efc6393f5','Niger','ne','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('5d454e50-d2ce-4499-b8be-e13612951626','Montenegro','me','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('5db6a8ec-a54f-47d5-a75d-487c424ca8a6','Egypt','eg','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('5ddb533b-f49a-4ae6-99d0-ceaf7d58d1fa','Pitcairn Islands','pn','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('5f184b7d-50b2-483c-9644-9ebacb4acffe','Indonesia','id','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('601c040c-812a-4984-95d1-b9f34cf09649','Martinique','mq','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('603529af-661e-4e14-9962-3f3088c124a0','Central African Republic','cf','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('6038ebc6-1461-46c6-b52c-6b9c915df8ec','Liechtenstein','li','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('618ddac1-1669-4715-b692-d7a10d934aa1','Poland','pl','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('623379ea-f05f-44e3-b757-2a44f44d07da','Serbia','rs','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('629e4424-f510-4824-83bb-13e262aa67c3','New Zealand','nz','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('63c2e47a-9e03-4e22-a4d7-ce5b24fe8d79','Mauritania','mr','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('63e4b4e8-ac1b-4a9c-9887-a9368f954f4e','St. Helena','sh','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('6491c53d-489f-4c2e-a30a-c6d4b388c22a','Romania','ro','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('65b7b6c8-cb4d-4691-9bed-ad691156336c','Mauritius','mu','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('67d4859a-2b2b-41b7-b961-25929ab6eaeb','Tonga','to','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('6801d50e-f100-4a24-8bcc-4e6b8b4edc75','Anguilla','ai','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('68f8112d-4b8d-4d9c-b46f-deee5e2c9249','Svalbard & Jan Mayen','sj','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('69bd278b-fc2e-41f1-811b-1d9303a7638a','Cook Islands','ck','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('6a61cfe9-d460-4e5e-87b3-664c693ef0bf','Philippines','ph','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('6b10c733-6946-42e7-b066-b92f53c8768f','Estonia','ee','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('6c07fd30-1709-482a-a245-35b84c28fd1b','Nicaragua','ni','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('6cedb4c5-62de-4c78-82f7-1037d2ff1e27','Marshall Islands','mh','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('6d620f63-062b-4ead-bc06-73f84d65231e','Australia','au','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('6fc55e58-783e-4c8e-913f-14abdcf304db','Uruguay','uy','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('7190dd6c-99ef-42d4-ae13-b66fe90999ee','Mongolia','mn','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('71ddc22f-efaf-4630-9b81-4b1e6019fbdc','Colombia','co','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('73fa12d6-8d43-42f7-b2cc-6681a0c5f0d4','Antarctica','aq','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('7566a717-08ab-4e91-9140-3ed5e019d5ad','Jordan','jo','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('76a14be9-405c-4a0e-8966-4a51101d0a7b','British Virgin Islands','vg','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('76bbdffd-4003-4bfb-beb6-98716ea844ed','Yemen','ye','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('773b6bae-670e-4666-96d2-77107bbe35f8','Syria','sy','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('783a09ff-db61-488e-8107-e53bdfdb1335','South Sudan','ss','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('79a528ed-3d90-43c5-b133-150cbcf74dec','Northern Mariana Islands','mp','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('79ddf230-5ffb-4605-bc20-75cde19a0d8a','Somalia','so','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('7a041d41-07ed-4c33-b7ef-e2c673c5fe60','Paraguay','py','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('7c50835d-56c7-401c-8662-348ea188acdb','Bolivia','bo','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('7cec96ef-c003-42af-ae3e-4219677b3c7e','Ukraine','ua','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('7d825ce9-5e9e-458c-ad6f-e09b5c406498','Turkey','tr','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('7e4ddd73-555c-4366-9f8c-26eb90dac502','Sint Maarten','sx','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('7ee30d25-fc9a-4b73-a20f-e8146111842b','Caribbean Netherlands','bq','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('7fa3d554-2274-40a2-ad87-7a6e59dd1cfa','Tanzania','tz','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('813d4cc3-704a-4fed-bd09-fce9aedde2a0','Qatar','qa','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('81815a00-d655-4cce-8c6f-e26b67ea1e0c','Malta','mt','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('8388ae7c-5433-45ad-92ee-a943fc30af07','St. Kitts & Nevis','kn','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('85025d4b-7d80-4440-848d-8c576ad8eec0','Zimbabwe','zw','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('8558e4f0-59ae-452d-a306-9fa1449365db','Åland Islands','ax','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('87335a51-f679-4c90-ada4-9be38b097878','Bahamas','bs','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('8776f3c0-8278-400a-a7e7-14aa27ae40f9','Panama','pa','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('87a7f512-af3a-4a9e-bcc2-0cf096e2b805','Aruba','aw','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('889a59d4-daf7-432d-90de-90fd1b6d4c16','Antigua & Barbuda','ag','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('8aa2dc0a-6add-439a-a6f9-fcf79749a5e7','United Arab Emirates','ae','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('8aa42b27-76c6-410e-8ac7-bc45fc57c4b0','Faroe Islands','fo','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('8aff0f27-fe7f-4f94-9582-c0026a1285b3','Lebanon','lb','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('8c8e0bd8-89ad-480c-9f04-e2bc34f0b0c0','Falkland Islands','fk','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('8e243b04-a909-41ed-8604-2a5efe3d49df','Canada','ca','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('8e6367a5-d863-48b7-84ca-23b275a7c646','Vatican City','va','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('8e83814d-ae04-4afe-911a-556f9158b791','Guatemala','gt','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('8e90bb2d-0950-42af-ad66-7c7d111a4b71','Austria','at','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('8faf0812-f2ee-40a3-90af-0dfd0e6c4b5a','Chile','cl','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('918f222f-eda8-4792-9713-43327c14c471','Guyana','gy','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('921547f0-cdd5-4577-b7bc-86b0a87ea199','Luxembourg','lu','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('93a0b87c-9262-47af-ba08-6430fe0fb2d6','Moldova','md','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('940f06ac-f623-4b4b-a889-103c0bcca5b0','Western Sahara','eh','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('9449e824-957c-4c4e-855c-605b63c12e00','Bangladesh','bd','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('945c6d7b-03a4-49d9-becd-30082543d60f','Kiribati','ki','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('98694463-4ce6-48b2-b2b3-199ce84fef4b','Suriname','sr','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('98a9d9ab-29fd-46ea-8642-228fd84d1953','Iran','ir','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('98fe33fa-60da-4bdc-8400-89e905dad6d5','Ecuador','ec','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('994ae848-c658-459a-a8e3-689d6a83da28','Turks & Caicos Islands','tc','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('999a299a-1cad-4b3e-8408-13ba040d8f46','Russia','ru','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('999f7906-ed23-4999-877d-ca8f915d137f','Lithuania','lt','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('9a61f041-6171-4890-a061-6ea9a8b2ea93','French Polynesia','pf','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('9de5a58d-83e9-4b62-9cf3-50fa0ba859da','U.S. Virgin Islands','vi','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('9ff970a7-5b5a-4fcb-b3b2-f59509b78a4e','Palau','pw','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('a0f44f7a-fca8-4982-8ad0-e6d2ad40f5ad','South Georgia & South Sandwich Islands','gs','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('a20bca83-bf0d-4b7a-8192-ccc2e5933552','Azerbaijan','az','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('a34e8054-e7ad-49c5-81b8-933574a6c5cf','Djibouti','dj','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('a43598d6-6002-4849-a8fa-be9235884ca1','Denmark','dk','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('a43853a8-06a9-4a07-bc6f-277882bc8b56','Sri Lanka','lk','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('a44fc90e-cc34-4293-ad09-13181ed0e865','Guinea-Bissau','gw','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('a83b26a2-4a34-4ee2-891d-084ca86b2bd2','Cayman Islands','ky','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('aa55704f-e7bc-4ff9-82a3-b8c0d8328eaa','Norfolk Island','nf','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('ac60656a-a7be-4ee1-8c64-538acf142b87','Monaco','mc','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('ac631c3d-3d4a-4e25-8b44-3da83ae1259f','Hong Kong','hk','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('ac687684-b056-4b0c-85db-199d8f3ec489','Greece','gr','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('ad08da1c-07cc-40ea-8e40-348a2fe36bd5','Macau','mo','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('ad183d7a-0d72-4fe0-a506-65e4a3f5062e','North Korea','kp','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('adda29f1-1b9d-4d93-ab61-a471477316c4','Slovakia','sk','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('adf78d97-b1ad-43ab-9a94-a93d529de70c','Georgia','ge','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('ae439fdb-b977-49a0-9af2-f8e4954bac98','American Samoa','as','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('af5c187f-9ec8-415c-a508-8b781b55ab59','Slovenia','si','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('af5e88c2-61cf-4238-9314-44e482b079a8','Trinidad & Tobago','tt','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('afc716d2-e921-4d5a-a62c-c834c8a44c66','Gibraltar','gi','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('afd8c148-1864-4db3-bbdb-dbc63f5c5a90','Pakistan','pk','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('aff705e5-9f9a-461c-a5b1-158169632efb','Rwanda','rw','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('b1281fbc-15e2-49e7-861c-3e133922f738','Gabon','ga','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('b1f06e2b-b7e6-4e3b-88e7-7dae3a8fd352','Malawi','mw','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('b21b5cbd-85c3-4fdd-b6ea-dd31efbe7aac','Argentina','ar','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('b27e3074-f9b7-41fa-b3d1-224fe0784614','Cocos (Keeling) Islands','cc','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('b2aa937e-c4c0-4a14-8d0a-665d7f71b2fb','Afghanistan','af','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('b419931f-5b70-48b8-b703-150ef0628299','San Marino','sm','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('b4ae2ee3-0020-45a6-bfc1-0966e5f97e58','Belize','bz','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('b5312991-5a9a-466d-91f0-d9f16d7bd24d','Seychelles','sc','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('b5433c65-c4d7-4160-a96d-9b258ec7c47a','Timor-Leste','tl','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('b5d64d5d-2bb7-4dc9-8bf8-7588a408d836','Curaçao','cw','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('b682aac9-e5d8-4bc0-ab0e-e6f00f995aa9','Barbados','bb','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('b7980379-1b00-47ff-bc44-3aeaedb3f2cb','Maldives','mv','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('b898ed40-8ff2-47b7-a2ce-f59fb9e53a8d','Ghana','gh','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('b9926b0f-d779-4471-b14e-cd54377167bd','Benin','bj','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('b9b0c022-a179-4409-8d0e-265e91bd0715','French Guiana','gf','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('bb32b4b4-26b1-4fb1-95d9-6ac156ebd0e4','Myanmar','mm','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('bc15192d-e481-4007-a7e6-85f7d325cd8e','Jersey','je','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('be3739b7-9507-470b-8669-976743cff9c9','Tajikistan','tj','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('c1d92f60-06dc-4acc-93a7-36dacc890cbe','Ethiopia','et','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('c5caaf76-b6c7-496b-93e3-190e755e1cce','Comoros','km','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('c9d03d02-e0bc-4562-9049-14de18f69013','Côte d’Ivoire','ci','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('ca09a602-959b-4ed5-aaa0-b306deb83c78','Malaysia','my','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('cb3e11a4-8f4a-48e6-a478-27c96684e28a','Heard & McDonald Islands','hm','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('cb42abcd-103f-4e3d-9eb0-a31982ce9fb5','Bermuda','bm','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('cbd8ec60-0cd8-4a1b-b61b-3241a687e87d','China','cn','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('d10aac77-3623-4023-bda6-f7c41bf520f3','Liberia','lr','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('d203af55-3e1e-4d97-986d-19bcbc9f8c38','São Tomé & Príncipe','st','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('d3743962-a6b7-4dc7-bd6d-76f973149de5','Mexico','mx','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('d49343ca-c8c0-44e4-bfc6-7cb93a9564bc','Sudan','sd','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('d4a58bb4-bd12-4f42-b0f4-317891030732','Fiji','fj','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('d75d32d6-3aa7-4f2b-8365-4f6237dc8e3f','Isle of Man','im','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('d7b6f292-d11e-4d6b-a2b8-b2697b87c082','Vanuatu','vu','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('d7c4f058-b4b7-4726-8d73-1ba43fd66614','Iraq','iq','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('d82087e6-09de-4fd3-bb8b-771710adda85','Réunion','re','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('d83c8d42-22fa-4a68-884e-b552d10103db','Gambia','gm','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('d938cf40-2e73-4d56-a91b-02af6281caae','Laos','la','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('db598da7-d561-4155-ba7a-6b61045682a8','Cuba','cu','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('dfaeec11-4137-47df-9fa2-07df10c398bf','Palestine','ps','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('e01f99ca-a7cd-459a-8129-eecb46181ec8','Peru','pe','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('e114530f-9ea0-4650-94c2-3829a6d0fb0b','Bouvet Island','bv','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('e2fee6bf-d6ad-40dc-a77b-cf655b531a5d','Angola','ao','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('e5acfa7f-4d65-4103-bf02-5cd4998c50a0','France','fr','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('e66b9580-e85f-43d7-a372-e4641db9200a','Guernsey','gg','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('e6c90a21-84d5-42f2-9445-12f09c06b1fa','Hungary','hu','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('e7da043f-4a7d-456e-90a4-f577a566a213','El Salvador','sv','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('e7ffd7d7-1d03-42d5-a07a-614c66dc35af','Nepal','np','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('e8e996fe-32f1-4d39-8cbe-55ed2829a5d0','U.S. Outlying Islands','um','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('eba35cee-0524-4c1a-a4fd-64ffa4c8b669','Uzbekistan','uz','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('ec3631dd-0939-4033-b40d-5cf2375d36a8','St. Barthélemy','bl','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('f073018f-660e-4138-8003-cc2704752146','Albania','al','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('f170be73-b194-460a-a219-4af46fea2240','Kuwait','kw','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('f1d951f6-9fc5-444e-87a8-a27ebd9b1955','South Korea','kr','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('f1dd70c0-9896-4417-9082-6eb2d7cd7dd3','Belarus','by','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('f3b5b4b8-793f-4db0-ae28-9d802fddccff','Haiti','ht','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('f42f48aa-2c95-4fec-96d1-97bcf1c2ac85','Jamaica','jm','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('f4f2152c-4ee2-4c41-be09-32ebc0edb4a2','Solomon Islands','sb','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('f78529ef-eb79-4f2c-9427-84d6fe769f6e','Libya','ly','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('f7ed0e5c-a37f-43c1-9835-4ecbb000961d','India','in','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('f8646f8d-fd4d-4096-bd00-5c8754c5d33f','Christmas Island','cx','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('f8871865-84de-453c-a850-a72a84bfb3f7','Tunisia','tn','2021-07-23 00:36:54','2021-07-23 
00:36:54',NULL),('f97f1b3a-5806-4b8d-b986-22bc3aebb5a6','Brunei','bn','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('f997a0c5-8479-4f94-a961-f998feae5014','Dominican Republic','do','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('fa0fc91f-1ac2-44f2-8cc4-f7c4173cbf11','New Caledonia','nc','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('fb944f68-b485-4291-91f8-7a5265eaa088','Mali','ml','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('fc2b699f-6e84-4f74-b3ff-f2344165df24','Costa Rica','cr','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('fca269d0-a166-4dbc-a7c8-260540e838b8','Cape Verde','cv','2021-07-23 00:36:53','2021-07-23 
00:36:53',NULL),('ff17d043-6fbc-4810-aa10-72b43d04a7e2','Cambodia','kh','2021-07-23 00:36:53','2021-07-23 00:36:53',NULL); /*!40000 ALTER TABLE 
`countries` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `cows` -- DROP TABLE IF EXISTS `cows`; /*!40101 SET @saved_cs_client = 
@@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `cows` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `breed_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `farmer_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cows_breed_id_foreign` (`breed_id`),
  KEY `cows_farmer_id_foreign` (`farmer_id`),
  KEY `cows_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `cows_breed_id_foreign` FOREIGN KEY (`breed_id`) REFERENCES `breeds` (`id`),
  CONSTRAINT `cows_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`),
  CONSTRAINT `cows_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `cows` -- LOCK TABLES `cows` WRITE; 
/*!40000 ALTER TABLE `cows` DISABLE KEYS */; INSERT INTO `cows` VALUES 
('5a7bdd20-fd4a-4708-9a1c-9c3cbfd8b21a','Jay','ty','258ea13a-2508-4aba-99c1-99596d7547bc','2c5465ee-db91-46d9-a4d2-8dfc64370820','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-04 
16:55:06','2022-02-04 16:55:06',NULL),('6115a63d-0c3e-4815-b1e2-9a421b5e8403','KILO 2','KILO 
2','9e198881-925a-4f74-98de-5d07232355ca','df5a78ad-f8a5-4ed0-a2fa-9ce0c8e195a0','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 
10:58:06','2021-07-24 
10:58:06',NULL),('69beeeae-286d-460b-9f5c-2a564a5a23e4','Guernsey','G12','864f70ed-7fa7-4eea-84da-35060ae291c6','2c5465ee-db91-46d9-a4d2-8dfc64370820','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-10-04 
12:36:02','2021-10-04 
12:36:02',NULL),('847a6c05-7125-448c-a553-0aab2114768b','SHIKO','S-FRESIOAN','864f70ed-7fa7-4eea-84da-35060ae291c6','2c5465ee-db91-46d9-a4d2-8dfc64370820','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 
10:57:45','2021-07-24 
10:57:45',NULL),('9bcd9ee6-8116-4301-a488-b74530e0bfc7','Mary','CW01','49bd07fe-6d2c-417e-881d-e8df0c18c319','15ae5b4f-1151-42ef-90c1-917494a39abe','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 
16:44:03','2022-02-12 16:44:03',NULL),('b1e35b86-1fe6-4506-a942-aa76e81dc99d','Mary 
Cow','MR-678','2ab01eb0-1358-48e2-8c99-9c612ebede55','1cedfe6e-b533-4bcf-bbb6-20eeca602617','4995bc45-7b46-473c-8658-76d78011ae0c','2021-07-23 
01:14:58','2021-07-23 
01:14:58',NULL),('d9f060c3-5a23-4a82-aa85-d4a6ef1e24fa','Zebu','Zebu-01','d67f9fca-862a-4d06-b949-15f34787ab28','8b2f71f2-af8c-47cf-a4e0-d429a51f9306','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 
21:21:26','2021-12-20 
21:21:26',NULL),('f1b765af-a93f-4dfd-855e-59d1eee85d47','Freshian','F-01','9e198881-925a-4f74-98de-5d07232355ca','8b2f71f2-af8c-47cf-a4e0-d429a51f9306','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 
21:22:17','2021-12-20 21:22:17',NULL); /*!40000 ALTER TABLE `cows` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `customers` -- DROP 
TABLE IF EXISTS `customers`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`customers` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` enum('Mr.','Mrs.','Miss.','Dr.','Proff.','Pstr.','Rev.') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Mr.',
  `gender` enum('M','F','X') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'M',
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_visit` date NOT NULL DEFAULT '2021-12-31',
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customers_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `customers_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `customers` 
-- LOCK TABLES `customers` WRITE; /*!40000 ALTER TABLE `customers` DISABLE KEYS */; INSERT INTO `customers` VALUES 
('210d7791-0aad-4301-8f5c-8a59270dadd1','Raj 
Vishnu','Dr.','M','rajvishnu@gmail.com','254723333333','2022-02-05','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 14:40:03','2022-02-05 
14:40:03'),('28523553-e971-4778-9077-05989a402685','Raj 
Vishnu','Proff.','M','rajvishnu@gmail.com','254723333333','2022-02-05','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 14:40:38','2022-02-05 
14:40:38'),('5d64759a-aa4e-45aa-924a-ed0cc4834a58','Raj 
Vishnu','Dr.','M','rajvishnu@gmail.com','254723333333','2022-02-05','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 14:40:09','2022-02-05 
14:40:09'),('887c46fd-d00f-4735-a40e-ee58fb913037','Rachel 
Wanjiku','Mrs.','F','rwanjiku@gmail.com','254715158974','2022-02-04','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-04 21:37:48','2022-02-04 
21:37:48'),('dd5de2b9-23bc-460a-95bc-920c1906854e','Raj 
Vishnu','Dr.','M','rajvishnu@gmail.com','254723333333','2022-02-05','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 14:31:35','2022-02-05 
14:31:35'),('fa68373a-c89b-4dbf-a073-fa35aa7e7af5','Mark','Mr.','M','markmwai@production.com','254723456789','2022-01-06','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 
15:50:40','2022-01-06 15:50:40'); /*!40000 ALTER TABLE `customers` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `disease_categories` 
-- DROP TABLE IF EXISTS `disease_categories`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; 
CREATE TABLE `disease_categories` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `disease_categories_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `disease_categories_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `disease_categories` 
-- LOCK TABLES `disease_categories` WRITE; /*!40000 ALTER TABLE `disease_categories` DISABLE KEYS */; INSERT INTO `disease_categories` VALUES 
('1a8a99ab-0168-4d20-9f21-9b2d499ca262','Fungus','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 01:27:53','2021-12-17 
01:27:53',NULL),('48db7050-51a3-46d2-978e-bc7219d004be','Critical','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:58:50','2021-07-24 
10:58:50',NULL),('67b3770b-80fe-4128-8767-2f0995d48ca7','Dangerous','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:58:31','2021-07-24 
10:58:31',NULL),('7ee6c227-74f2-4fb5-b955-f8bfd670a6a2','Contagious','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:44:52','2022-02-12 
16:44:52',NULL),('7f57574a-6854-41cb-b96f-b37f1412d6e6','Mild','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:59:00','2021-07-24 
10:59:00',NULL),('d434c2c5-7c0b-4b6e-9c41-7da2e44d60f7','Dangerous','4995bc45-7b46-473c-8658-76d78011ae0c','2021-07-23 01:15:20','2021-07-23 
01:15:20',NULL); /*!40000 ALTER TABLE `disease_categories` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `diseases` -- DROP TABLE IF 
EXISTS `diseases`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `diseases` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `disease_category_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `diseases_disease_category_id_foreign` (`disease_category_id`),
  KEY `diseases_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `diseases_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`),
  CONSTRAINT `diseases_disease_category_id_foreign` FOREIGN KEY (`disease_category_id`) REFERENCES `disease_categories` (`id`) ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `diseases` -- LOCK 
TABLES `diseases` WRITE; /*!40000 ALTER TABLE `diseases` DISABLE KEYS */; INSERT INTO `diseases` VALUES 
('169940f5-aeeb-4241-9fd6-3c51f72dfd44','Powdery mildew','7f57574a-6854-41cb-b96f-b37f1412d6e6','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 
01:27:40','2021-12-17 01:27:40',NULL),('1ee54ee6-a5bc-4642-a879-2dcf9f061170','Sleeping 
Sickness','d434c2c5-7c0b-4b6e-9c41-7da2e44d60f7','4995bc45-7b46-473c-8658-76d78011ae0c','2021-07-23 01:16:08','2021-07-23 
01:16:08',NULL),('2d9594fa-206d-4679-a26c-4f9f643a10a7','Bacterial canker and 
blast','1a8a99ab-0168-4d20-9f21-9b2d499ca262','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 01:28:19','2021-12-17 
01:28:19',NULL),('6470c04d-4bfd-4a22-80cf-30c8ee14bedf','Foot and 
mouth','7ee6c227-74f2-4fb5-b955-f8bfd670a6a2','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:45:55','2022-02-12 
16:45:55',NULL),('6f3ecc16-ae20-49b0-8635-54872fc6956c','Foot and 
Mouth','7f57574a-6854-41cb-b96f-b37f1412d6e6','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:59:22','2021-07-24 
10:59:22',NULL),('88ef0e7d-fc21-4f41-8314-6774e152ef74','Ticks','7f57574a-6854-41cb-b96f-b37f1412d6e6','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 
21:24:05','2021-12-20 21:24:05',NULL),('961955f7-4f48-4112-8c9c-7658cbfb4970','Rift Valley 
Fever','67b3770b-80fe-4128-8767-2f0995d48ca7','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:59:39','2021-07-24 
10:59:39',NULL),('ab9e71fe-83f5-4567-a618-45ef51e324d7','Mastitis','48db7050-51a3-46d2-978e-bc7219d004be','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 
21:23:38','2021-12-20 21:23:38',NULL),('cc2d9e74-233c-4ae1-9b59-cf7a93cce199','SICKLE 
CELL','7f57574a-6854-41cb-b96f-b37f1412d6e6','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-10-04 12:37:00','2021-10-04 12:37:00',NULL); /*!40000 ALTER 
TABLE `diseases` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `employee_bank_details` -- DROP TABLE IF EXISTS 
`employee_bank_details`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`employee_bank_details` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_branch_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_bank_details_employee_id_foreign` (`employee_id`),
  KEY `employee_bank_details_bank_branch_id_foreign` (`bank_branch_id`),
  CONSTRAINT `employee_bank_details_bank_branch_id_foreign` FOREIGN KEY (`bank_branch_id`) REFERENCES `bank_branches` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `employee_bank_details_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `coop_employees` (`id`) ON UPDATE CASCADE ) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for 
table `employee_bank_details` -- LOCK TABLES `employee_bank_details` WRITE; /*!40000 ALTER TABLE `employee_bank_details` DISABLE KEYS */; INSERT INTO 
`employee_bank_details` VALUES ('4913ca99-20d1-452a-b98d-0015dc8aff6f','bc9b4c78-9056-4f61-85cd-dda81fadd137','ROBERT 
WANJIKU','123123123','e3f5589b-ff2d-411c-9ca6-c04b5e71362f','2021-11-20 12:57:39','2021-11-20 
12:57:39',NULL),('4c856685-be53-453e-998d-15f900413848','d24ca95a-316a-424a-8db9-871aa38b1a37','Mary 
Njoroge','2222224','e3f5589b-ff2d-411c-9ca6-c04b5e71362f','2022-01-06 16:05:32','2022-01-06 16:05:32',NULL); /*!40000 ALTER TABLE 
`employee_bank_details` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `employee_employment_types` -- DROP TABLE IF EXISTS 
`employee_employment_types`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`employee_employment_types` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employment_type_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_employment_types_employee_id_foreign` (`employee_id`),
  KEY `employee_employment_types_employment_type_id_foreign` (`employment_type_id`),
  CONSTRAINT `employee_employment_types_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `coop_employees` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `employee_employment_types_employment_type_id_foreign` FOREIGN KEY (`employment_type_id`) REFERENCES `employment_types` (`id`) ON UPDATE 
CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping 
data for table `employee_employment_types` -- LOCK TABLES `employee_employment_types` WRITE; /*!40000 ALTER TABLE `employee_employment_types` DISABLE 
KEYS */; INSERT INTO `employee_employment_types` VALUES 
('035c1916-e195-4dbb-ad05-90524cffe306','9fa7b884-8248-473d-b149-411764f7094e','d24ca95a-316a-424a-8db9-871aa38b1a37','2022-01-06 
16:05:32','2022-01-06 
16:05:32',NULL),('d9c5daf8-2cb4-4234-aa94-c9aa899dbce1','9fa7b884-8248-473d-b149-411764f7094e','bc9b4c78-9056-4f61-85cd-dda81fadd137','2021-11-20 
12:57:39','2021-11-20 12:57:39',NULL); /*!40000 ALTER TABLE `employee_employment_types` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table 
`employee_files` -- DROP TABLE IF EXISTS `employee_files`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET 
character_set_client = utf8 */; CREATE TABLE `employee_files` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_files_employee_id_foreign` (`employee_id`),
  CONSTRAINT `employee_files_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `coop_employees` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table 
`employee_files` -- LOCK TABLES `employee_files` WRITE; /*!40000 ALTER TABLE `employee_files` DISABLE KEYS */; /*!40000 ALTER TABLE `employee_files` 
ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `employee_leaves` -- DROP TABLE IF EXISTS `employee_leaves`; /*!40101 SET 
@saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `employee_leaves` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` longtext COLLATE utf8mb4_unicode_ci,
  `remarks` text COLLATE utf8mb4_unicode_ci,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `employee_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_leaves_employee_id_foreign` (`employee_id`),
  CONSTRAINT `employee_leaves_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `coop_employees` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table 
`employee_leaves` -- LOCK TABLES `employee_leaves` WRITE; /*!40000 ALTER TABLE `employee_leaves` DISABLE KEYS */; INSERT INTO `employee_leaves` VALUES 
('254b0413-e1cd-41ad-b435-6bd80e922e7d','2022-02-25','2022-03-04','Family Issues','Family issues 
leave','',0,'bc9b4c78-9056-4f61-85cd-dda81fadd137','2022-02-12 17:05:47','2022-02-12 
17:05:47',NULL),('acf8bd6c-a893-4e17-b58e-d7f75442cfe0','2021-12-14','2021-12-15','SICK LEAVE','Check 
Up','',1,'bc9b4c78-9056-4f61-85cd-dda81fadd137','2021-12-13 17:58:34','2022-02-12 
17:03:53',NULL),('f815de01-7042-4866-a44b-a1dc09d63e6a','2022-01-28','2022-07-11','Maternity/partenity leave','Maternity 
leave','',1,'d24ca95a-316a-424a-8db9-871aa38b1a37','2022-01-06 16:08:14','2022-01-06 16:16:27',NULL); /*!40000 ALTER TABLE `employee_leaves` ENABLE 
KEYS */; UNLOCK TABLES; -- -- Table structure for table `employee_positions` -- DROP TABLE IF EXISTS `employee_positions`; /*!40101 SET 
@saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `employee_positions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_positions_employee_id_foreign` (`employee_id`),
  KEY `employee_positions_position_id_foreign` (`position_id`),
  CONSTRAINT `employee_positions_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `coop_employees` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `employee_positions_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `job_positions` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table 
`employee_positions` -- LOCK TABLES `employee_positions` WRITE; /*!40000 ALTER TABLE `employee_positions` DISABLE KEYS */; INSERT INTO 
`employee_positions` VALUES 
('11a210ac-b289-4df2-9911-4ab0fb919bd0','ea39212a-80d0-459c-86f5-d93537210742','bc9b4c78-9056-4f61-85cd-dda81fadd137','2021-11-20 
12:57:39','2021-11-20 
12:57:39',NULL),('ff2d461c-8c70-4636-ac5d-e5d666a99c2f','22c51f78-8800-44fe-ab2c-0be651b0f851','d24ca95a-316a-424a-8db9-871aa38b1a37','2022-01-06 
16:05:32','2022-01-06 16:05:32',NULL); /*!40000 ALTER TABLE `employee_positions` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table 
`employment_types` -- DROP TABLE IF EXISTS `employment_types`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET 
character_set_client = utf8 */; CREATE TABLE `employment_types` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooperative_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employment_types_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `employment_types_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON UPDATE CASCADE ) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for 
table `employment_types` -- LOCK TABLES `employment_types` WRITE; /*!40000 ALTER TABLE `employment_types` DISABLE KEYS */; INSERT INTO 
`employment_types` VALUES ('4adb5084-9ee2-4d82-80cb-4fcd7fae8e85','Permanent','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 16:12:10','2022-01-06 
16:12:23','2022-01-06 16:12:23'),('9fa7b884-8248-473d-b149-411764f7094e','Permanent','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 
12:40:42','2021-11-20 12:40:42',NULL),('b52bcaac-e877-4afb-bb24-16c9f80e97ad','Casual','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 
12:40:55','2021-11-20 12:40:55',NULL),('d69120b4-09d0-4233-a730-70c682695d12','Outsourced','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 
12:40:48','2021-11-20 12:40:48',NULL); /*!40000 ALTER TABLE `employment_types` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table 
`farmers` -- DROP TABLE IF EXISTS `farmers`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; 
CREATE TABLE `farmers` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `county` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `route_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_branch_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `member_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kra` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `farm_size` double NOT NULL DEFAULT '0',
  `age` int(11) NOT NULL DEFAULT '18',
  `dob` date NOT NULL DEFAULT '2021-12-16',
  `gender` enum('M','F','X') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'M',
  `geolocation_lat` double DEFAULT NULL,
  `geolocation_long` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `farmers_country_id_foreign` (`country_id`),
  KEY `farmers_route_id_foreign` (`route_id`),
  KEY `farmers_bank_branch_id_foreign` (`bank_branch_id`),
  KEY `farmers_user_id_foreign` (`user_id`),
  CONSTRAINT `farmers_bank_branch_id_foreign` FOREIGN KEY (`bank_branch_id`) REFERENCES `bank_branches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `farmers_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `farmers_route_id_foreign` FOREIGN KEY (`route_id`) REFERENCES `routes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `farmers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; -- -- Dumping data for table `farmers` -- LOCK 
TABLES `farmers` WRITE; /*!40000 ALTER TABLE `farmers` DISABLE KEYS */; INSERT INTO `farmers` VALUES 
('118b80b8-6427-4994-ab7a-ea09f677fba7','1ea59c59-8243-426d-b1bb-ff1c9bc93d2f','Taita','Taita','700700700','254740300300','44769ba4-d939-4569-b5c7-c062266534ac','1234567','e3f5589b-ff2d-411c-9ca6-c04b5e71362f','10003','monthly','7845500','37e9f0a5-aadd-441c-8a1c-02810e0f6387','2021-12-17 
08:58:19','2021-12-17 
08:58:19',NULL,10,27,'1994-06-15','F',NULL,NULL),('15ae5b4f-1151-42ef-90c1-917494a39abe','1ea59c59-8243-426d-b1bb-ff1c9bc93d2f','Juja','Juja','900900900','254725900900','6d0fea8c-c209-41a1-b424-9a1755fdf74a','890890890','56027680-d5ba-410a-aa65-bd47a25b626c','004','monthly','784510','d9d25896-6a97-4997-bea2-e6c2c322efae','2021-12-17 
12:20:40','2021-12-17 
12:20:40',NULL,12,21,'2000-01-11','F',NULL,NULL),('1cedfe6e-b533-4bcf-bbb6-20eeca602617','1ea59c59-8243-426d-b1bb-ff1c9bc93d2f','Nairobi','Karen','12345678','254767890123','c355b1b5-e1c0-4607-a0b1-bc6266fec460','1134567890','cedf450e-602c-49d6-bc81-9465667ed3df','FR46789','monthly','A20456987Z','af7f8d72-023c-47d1-ba90-29a20f6a4231','2021-07-23 
01:13:23','2021-07-23 
01:13:23',NULL,0,18,'2021-12-16','M',NULL,NULL),('2c5465ee-db91-46d9-a4d2-8dfc64370820','1ea59c59-8243-426d-b1bb-ff1c9bc93d2f','KIAMBU','NDUMBERI','12345678','254720897654','0ced6a08-c2e2-40da-bfab-064bc7e49799','011202020','e3f5589b-ff2d-411c-9ca6-c04b5e71362f','001','monthly','P700800546L','3c71e95f-2785-4d86-9c02-f54b3d8e9ae9','2021-07-24 
10:54:52','2021-07-24 
10:54:52',NULL,0,18,'2021-12-16','M',NULL,NULL),('8b2f71f2-af8c-47cf-a4e0-d429a51f9306','b2aa937e-c4c0-4a14-8d0a-665d7f71b2fb','Nyeri','Nyeri','12345679','254714444444','dadfecf6-6ca1-43d5-9c48-73dd49b21c5f','2222221','49b7a1e7-4356-4eec-97ed-f72a014be544','02','monthly','A23456CC','e15afdc0-03f0-40c2-86a0-01dfd6b9feab','2021-12-20 
21:16:15','2021-12-20 
21:16:15',NULL,3,47,'1974-02-23','F',NULL,NULL),('ae87e19d-a5a1-4927-9cd3-720beecf19ce','1ea59c59-8243-426d-b1bb-ff1c9bc93d2f','Kiambu','Ndumberi','100100100','254720100100','44769ba4-d939-4569-b5c7-c062266534ac','123456789','e3f5589b-ff2d-411c-9ca6-c04b5e71362f','0003','weekly','7451000','3366c996-2080-4cf2-a060-8ac85b2c8b80','2021-12-13 
16:41:14','2021-12-13 
16:41:14',NULL,0,18,'2021-12-16','M',NULL,NULL),('da2cd186-c477-41ba-addf-57da71cef97b','b2aa937e-c4c0-4a14-8d0a-665d7f71b2fb','TAITA','Taita','700700700','254742100100','0ced6a08-c2e2-40da-bfab-064bc7e49799','451200','56027680-d5ba-410a-aa65-bd47a25b626c','3000','weekly','45120000','f300236f-6fe1-41b4-bca1-7eb260bf21b3','2021-12-17 
12:27:34','2021-12-17 
12:27:34',NULL,5,18,'2003-12-16','M',NULL,NULL),('df5a78ad-f8a5-4ed0-a2fa-9ce0c8e195a0','1ea59c59-8243-426d-b1bb-ff1c9bc93d2f','MERU','NKUBU','78945612','254321654987','0ced6a08-c2e2-40da-bfab-064bc7e49799','050202020','1ed06bd5-b8e1-4fab-9c30-077fdb7ab4e1','0002','monthly','ATY2021450','00462423-508f-4cc1-88bc-52d5b5e11586','2021-07-24 
10:56:18','2021-07-24 
10:56:18',NULL,0,18,'2021-12-16','M',NULL,NULL),('ecc075fc-8b0b-4326-9e25-0f310bcf64f1','b2aa937e-c4c0-4a14-8d0a-665d7f71b2fb','Nyeri','Nyeri','12345678','254712222222','0ced6a08-c2e2-40da-bfab-064bc7e49799','2222222','56027680-d5ba-410a-aa65-bd47a25b626c','01','weekly','A23456TY','d0c10ae9-ffd8-4ef5-b9ef-fabec7d0d1b4','2021-12-20 
21:00:42','2021-12-20 21:00:42',NULL,1,36,'1985-12-05','M',NULL,NULL); /*!40000 ALTER TABLE `farmers` ENABLE KEYS */; UNLOCK TABLES; -- -- Table 
structure for table `farmers_products` -- DROP TABLE IF EXISTS `farmers_products`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 
SET character_set_client = utf8 */; CREATE TABLE `farmers_products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `farmer_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `farmers_products_farmer_id_foreign` (`farmer_id`),
  KEY `farmers_products_product_id_foreign` (`product_id`),
  CONSTRAINT `farmers_products_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `farmers_products_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ) 
ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- 
Dumping data for table `farmers_products` -- LOCK TABLES `farmers_products` WRITE; /*!40000 ALTER TABLE `farmers_products` DISABLE KEYS */; INSERT 
INTO `farmers_products` VALUES 
(1,'af7f8d72-023c-47d1-ba90-29a20f6a4231','f62bb800-1182-4108-92dc-7d33da5e66df',NULL,NULL),(2,'3c71e95f-2785-4d86-9c02-f54b3d8e9ae9','7e3ad0c9-b573-4d2b-b7c4-259df964398d',NULL,NULL),(3,'00462423-508f-4cc1-88bc-52d5b5e11586','7e3ad0c9-b573-4d2b-b7c4-259df964398d',NULL,NULL),(4,'3366c996-2080-4cf2-a060-8ac85b2c8b80','7e3ad0c9-b573-4d2b-b7c4-259df964398d',NULL,NULL),(5,'37e9f0a5-aadd-441c-8a1c-02810e0f6387','24173b51-713b-40d7-bb97-0c1e7238021a',NULL,NULL),(6,'d9d25896-6a97-4997-bea2-e6c2c322efae','24173b51-713b-40d7-bb97-0c1e7238021a',NULL,NULL),(7,'f300236f-6fe1-41b4-bca1-7eb260bf21b3','24173b51-713b-40d7-bb97-0c1e7238021a',NULL,NULL),(8,'d0c10ae9-ffd8-4ef5-b9ef-fabec7d0d1b4','7e3ad0c9-b573-4d2b-b7c4-259df964398d',NULL,NULL),(9,'e15afdc0-03f0-40c2-86a0-01dfd6b9feab','6e8ea072-2c88-4be1-9bba-c4f2f36b8b71',NULL,NULL),(10,'e15afdc0-03f0-40c2-86a0-01dfd6b9feab','7e3ad0c9-b573-4d2b-b7c4-259df964398d',NULL,NULL),(11,'ec126071-2854-455c-964c-e70f546b8a9b','4f1f0ace-936e-4a69-a517-5fdf81c6ddcd',NULL,NULL),(12,'a484b8d4-243b-4a7c-ab1a-2340d23ef313','4f1f0ace-936e-4a69-a517-5fdf81c6ddcd',NULL,NULL),(13,'283b2a32-1ca8-4b59-a623-58de078147ab','a2b4651f-4238-4cc9-b9ed-92407440070d',NULL,NULL),(14,'bb008cef-2149-4018-b069-9d8032b38809','a2b4651f-4238-4cc9-b9ed-92407440070d',NULL,NULL),(15,'45a6aa4a-37a5-47a3-b831-c3cbda8cf1d7','a2b4651f-4238-4cc9-b9ed-92407440070d',NULL,NULL),(16,'395af6b3-bf32-48c6-8570-679ebb48f435','9d352572-18f9-4486-8add-ca0144292e14',NULL,NULL); 
/*!40000 ALTER TABLE `farmers_products` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `final_products` -- DROP TABLE IF EXISTS 
`final_products`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`final_products` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `selling_price` double(13,4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `final_products_cooperative_id_foreign` (`cooperative_id`),
  KEY `final_products_category_id_foreign` (`category_id`),
  KEY `final_products_unit_id_foreign` (`unit_id`),
  CONSTRAINT `final_products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `final_products_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `final_products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `final_products` -- 
LOCK TABLES `final_products` WRITE; /*!40000 ALTER TABLE `final_products` DISABLE KEYS */; INSERT INTO `final_products` VALUES 
('201363a4-3ec0-4f48-89cb-1dbf0ba619ab','Cloth','2dd259d8-220a-4f85-b15b-365f1c63be43','5dae83b2-e858-4d55-922b-5295b8e9f81f','e899c22d-f213-4c19-b995-bdf2e3fb8944',1500.0000,'2022-01-06 
15:08:52','2022-01-06 15:08:52',NULL),('32ab16ff-a397-44df-adf9-372adb42a38b','Dried 
Fruit','7be640cf-b3c2-4172-8934-0a33aa6411da','5dae83b2-e858-4d55-922b-5295b8e9f81f','8fe2fb27-23d1-4e5b-9a06-63191f1a07be',850.0000,'2021-12-17 
12:38:57','2021-12-17 
12:38:57',NULL),('6ea99c4f-eae3-473d-bb02-588464b952c0','Yoghurt','78953dc4-c0c8-49b8-82b3-c756ead581ed','5dae83b2-e858-4d55-922b-5295b8e9f81f','573a8272-3e37-4fe5-9730-f6d906286bda',150.0000,'2021-12-13 
16:29:33','2021-12-13 16:29:33',NULL),('8ea618f8-9ec1-4db4-bc4d-b73442efe53b','Mixed 
fruit','7be640cf-b3c2-4172-8934-0a33aa6411da','5dae83b2-e858-4d55-922b-5295b8e9f81f','8fe2fb27-23d1-4e5b-9a06-63191f1a07be',150.0000,'2022-02-05 
15:02:36','2022-02-05 15:02:36',NULL),('a8825ecd-ceee-49ce-98c1-a93fa121e017','Animal 
Feed','78953dc4-c0c8-49b8-82b3-c756ead581ed','5dae83b2-e858-4d55-922b-5295b8e9f81f','8fe2fb27-23d1-4e5b-9a06-63191f1a07be',200.0000,'2021-12-13 
16:29:58','2021-12-13 16:29:58',NULL),('ace8e767-8926-4a80-9b16-5164b9dd3578','Packed 
Milk','131de6de-1708-43f5-98a0-6f6dbea42cea','5dae83b2-e858-4d55-922b-5295b8e9f81f','573a8272-3e37-4fe5-9730-f6d906286bda',55.0000,'2021-12-13 
18:07:21','2021-12-13 18:07:21',NULL),('ba1943f4-0cb8-401a-9336-24ed4346b8cc','Mango 
Juice','8aa37bac-42ef-44a4-a2f2-bf69316308e3','5dae83b2-e858-4d55-922b-5295b8e9f81f','8fe2fb27-23d1-4e5b-9a06-63191f1a07be',200.0000,'2021-12-17 
01:30:34','2021-12-17 01:30:34',NULL),('e57aea70-ca54-4dd7-bfdc-f6fd3aed93d4','Watermelon 
Juice','8aa37bac-42ef-44a4-a2f2-bf69316308e3','5dae83b2-e858-4d55-922b-5295b8e9f81f','573a8272-3e37-4fe5-9730-f6d906286bda',80.0000,'2022-02-12 
17:20:15','2022-02-12 17:20:15',NULL); /*!40000 ALTER TABLE `final_products` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table 
`invoice_payments` -- DROP TABLE IF EXISTS `invoice_payments`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET 
character_set_client = utf8 */; CREATE TABLE `invoice_payments` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invoice_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `transaction_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_platform` enum('mobile','cash','bank','crypto') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoice_payments_invoice_id_foreign` (`invoice_id`),
  CONSTRAINT `invoice_payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `invoice_payments` 
-- LOCK TABLES `invoice_payments` WRITE; /*!40000 ALTER TABLE `invoice_payments` DISABLE KEYS */; /*!40000 ALTER TABLE `invoice_payments` ENABLE KEYS 
*/; UNLOCK TABLES; -- -- Table structure for table `invoices` -- DROP TABLE IF EXISTS `invoices`; /*!40101 SET @saved_cs_client = 
@@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `invoices` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sale_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `invoice_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `invoices_sale_id_foreign` (`sale_id`),
  CONSTRAINT `invoices_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `invoices` -- LOCK TABLES `invoices` 
WRITE; /*!40000 ALTER TABLE `invoices` DISABLE KEYS */; /*!40000 ALTER TABLE `invoices` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table 
`job_positions` -- DROP TABLE IF EXISTS `job_positions`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client 
= utf8 */; CREATE TABLE `job_positions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `job_positions_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `job_positions_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table 
`job_positions` -- LOCK TABLES `job_positions` WRITE; /*!40000 ALTER TABLE `job_positions` DISABLE KEYS */; INSERT INTO `job_positions` VALUES 
('22c51f78-8800-44fe-ab2c-0be651b0f851','Quallity Assurance','Testing the quality','QA','Tests the quality of the produced 
items.','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 16:02:24','2022-01-06 
16:02:24',NULL),('a0b7cec8-8bbf-4c00-a196-23d6d77a2bd8','Communications Officer','Communications','Com01','Communicating with the famers and 
management','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:59:47','2022-02-12 
16:59:47',NULL),('e64d4581-184e-4fda-9fc7-48afa5605baf','Procurement Manager','Sourcing','PROC','Procurment 
Department','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:45:31','2021-11-20 
12:45:31',NULL),('ea39212a-80d0-459c-86f5-d93537210742','ACCOUNTANT','Accounts Management','ACC','Managing the organization fiancial 
activities','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:44:45','2021-11-20 12:44:45',NULL); /*!40000 ALTER TABLE `job_positions` ENABLE KEYS 
*/; UNLOCK TABLES; -- -- Table structure for table `loan_limits` -- DROP TABLE IF EXISTS `loan_limits`; /*!40101 SET @saved_cs_client = 
@@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `loan_limits` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `farmer_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `limit` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loan_limits_farmer_id_foreign` (`farmer_id`),
  CONSTRAINT `loan_limits_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `loan_limits` -- 
LOCK TABLES `loan_limits` WRITE; /*!40000 ALTER TABLE `loan_limits` DISABLE KEYS */; /*!40000 ALTER TABLE `loan_limits` ENABLE KEYS */; UNLOCK TABLES; 
-- -- Table structure for table `loan_payment_histories` -- DROP TABLE IF EXISTS `loan_payment_histories`; /*!40101 SET @saved_cs_client = 
@@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `loan_payment_histories` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `loan_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wallet_transaction_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loan_payment_histories_loan_id_foreign` (`loan_id`),
  KEY `loan_payment_histories_wallet_transaction_id_foreign` (`wallet_transaction_id`),
  CONSTRAINT `loan_payment_histories_loan_id_foreign` FOREIGN KEY (`loan_id`) REFERENCES `loans` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `loan_payment_histories_wallet_transaction_id_foreign` FOREIGN KEY (`wallet_transaction_id`) REFERENCES `wallet_transactions` (`id`) ON 
UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- 
Dumping data for table `loan_payment_histories` -- LOCK TABLES `loan_payment_histories` WRITE; /*!40000 ALTER TABLE `loan_payment_histories` DISABLE 
KEYS */; /*!40000 ALTER TABLE `loan_payment_histories` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `loans` -- DROP TABLE IF EXISTS 
`loans`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `loans` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `balance` double NOT NULL DEFAULT '0',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `farmer_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `due_date` date NOT NULL,
  `mode_of_payment` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `interest` double NOT NULL DEFAULT '1',
  `purpose` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `loans_farmer_id_foreign` (`farmer_id`),
  CONSTRAINT `loans_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `loans` -- LOCK TABLES `loans` 
WRITE; /*!40000 ALTER TABLE `loans` DISABLE KEYS */; /*!40000 ALTER TABLE `loans` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table 
`migrations` -- DROP TABLE IF EXISTS `migrations`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 
*/; CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = 
@saved_cs_client */; -- -- Dumping data for table `migrations` -- LOCK TABLES `migrations` WRITE; /*!40000 ALTER TABLE `migrations` DISABLE KEYS */; 
INSERT INTO `migrations` VALUES 
(1,'2021_06_19_125315_create_countries_table',1),(2,'2021_06_19_130025_create_cooperatives_table',1),(3,'2021_06_20_000000_create_users_table',1),(4,'2021_06_20_010038_create_permission_tables',1),(5,'2021_06_20_100000_create_password_resets_table',1),(6,'2021_06_26_220955_create_audit_trails_table',1),(7,'2021_07_10_101428_create_routes_table',1),(8,'2021_07_13_114307_create_units_table',1),(9,'2021_07_13_114326_create_categories_table',1),(10,'2021_07_14_111630_create_banks_table',1),(11,'2021_07_14_111637_create_bank_branches_table',1),(12,'2021_07_14_112831_create_products_table',1),(13,'2021_07_14_125215_create_farmers_table',1),(14,'2021_07_14_125823_create_farmers_products_table',1),(15,'2021_07_20_192913_create_breeds_table',1),(16,'2021_07_20_192927_create_cows_table',1),(17,'2021_07_22_064324_create_disease_categories_table',1),(18,'2021_07_22_064338_create_diseases_table',1),(19,'2021_10_11_080220_create_vet_services_table',2),(20,'2021_10_11_111048_create_vet_items_table',2),(21,'2021_10_12_171009_create_vets_table',2),(22,'2021_10_12_172726_create_vets_items_table',2),(23,'2021_11_04_130048_create_coop_branches_table',2),(24,'2021_11_04_130215_create_coop_branch_departments_table',2),(25,'2021_11_04_130302_create_coop_employees_table',2),(26,'2021_11_04_130329_create_employment_types_table',2),(27,'2021_11_04_130351_create_employee_leaves_table',2),(28,'2021_11_04_133437_create_job_positions_table',2),(29,'2021_11_04_133450_create_employee_positions_table',2),(30,'2021_11_04_133507_create_employee_bank_details_table',2),(31,'2021_11_04_133822_create_employee_files_table',2),(32,'2021_11_06_152140_create_employee_employment_types_table',2),(33,'2021_11_09_144420_create_recruitments_table',2),(34,'2021_11_09_145405_create_recruitment_applications_table',2),(35,'2021_11_13_171139_create_collections_table',2),(36,'2021_11_18_195857_create_final_products_table',2),(37,'2021_11_18_195923_create_raw_materials_table',2),(39,'2021_11_18_195959_create_productions_table',3),(40,'2021_11_21_153241_create_production_materials_table',4),(41,'2021_12_04_174008_create_vet_bookings_table',5),(42,'2021_12_08_190328_rename_paye_column',6),(43,'2021_12_08_192524_add_buying_price_to_products_table',6),(44,'2021_12_13_085332_add_column_number_to_collections_table',7),(45,'2021_12_16_112240_add_more_details_to_farmers_table',8),(46,'2021_12_28_085332_add_column_batch_no_to_collections_table',9),(47,'2021_12_30_093525_remove_raw_materials_columns',9),(48,'2021_12_30_094142_add_available_quantity_to_collection',9),(49,'2021_12_30_102950_create_customers_table',9),(50,'2021_12_30_104442_create_sales_table',9),(51,'2021_12_30_104510_create_invoices_table',9),(52,'2021_12_30_104615_create_invoice_payments_table',9),(53,'2021_12_30_123347_add_units_to_raw_materials',9),(54,'2021_12_30_125030_add_cooperative_to_raw_materials',9),(55,'2021_12_30_132906_remove_final_product_cols',9),(56,'2021_12_30_140002_remove_production_cols',9),(57,'2021_12_30_153724_add_available_quantity_to_productions',9),(58,'2022_01_04_100556_add_cooperative_id_to_customers_table',10),(59,'2022_01_08_121629_collections_available_quantity_data_type',11),(60,'2022_01_09_125015_drop_cols_from_sales',12),(61,'2022_01_09_125237_create_sale_items_table',12),(62,'2022_02_02_212713_create_cooperative_payment_configs_table',13),(63,'2022_02_02_213157_create_wallet_table',13),(64,'2022_02_02_213806_create_wallet_transactions_table',13),(65,'2022_02_02_214333_create_loan_limits_table',13),(66,'2022_02_02_214710_create_loans_table',13),(67,'2022_02_02_215255_create_loan_payment_histories_table',13),(68,'2022_02_19_094425_add_columns_to_trans',14); 
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `model_has_permissions` -- DROP TABLE IF EXISTS 
`model_has_permissions`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`model_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for 
table `model_has_permissions` -- LOCK TABLES `model_has_permissions` WRITE; /*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */; /*!40000 
ALTER TABLE `model_has_permissions` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `model_has_roles` -- DROP TABLE IF EXISTS 
`model_has_roles`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`model_has_roles` (
  `role_id` bigint(20) unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `model_has_roles` -- 
LOCK TABLES `model_has_roles` WRITE; /*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */; INSERT INTO `model_has_roles` VALUES 
(3,'App\\User','00462423-508f-4cc1-88bc-52d5b5e11586'),(7,'App\\User','10412d4d-4db7-4999-93c4-7e5b8f452582'),(2,'App\\User','2a05069e-4f8e-46a0-931a-a88e0b8d23b9'),(2,'App\\User','2f46da5f-6c3a-43db-8f33-ab8acc6c89bf'),(3,'App\\User','3366c996-2080-4cf2-a060-8ac85b2c8b80'),(3,'App\\User','37e9f0a5-aadd-441c-8a1c-02810e0f6387'),(4,'App\\User','3a1c5fd8-c546-4598-907f-cab1c4691673'),(3,'App\\User','3c71e95f-2785-4d86-9c02-f54b3d8e9ae9'),(7,'App\\User','4d327539-ef33-45c0-97ba-50b419577f9f'),(1,'App\\User','597dfe2f-35c0-4a32-86c7-434901fe7269'),(2,'App\\User','6131b2b3-7245-4363-b25d-d208721f8e12'),(4,'App\\User','79291b6c-7f9d-467f-be92-d2fafe6d2e18'),(4,'App\\User','8ad317be-f6ce-4d2d-9856-69abeb325cdf'),(4,'App\\User','9e59a2f8-24c8-4c41-b310-3bdfbc5864f6'),(2,'App\\User','a1998913-1bd5-46aa-8501-bd4e9564d3b3'),(2,'App\\User','ae0c23e3-985b-4f06-931a-94a813c67404'),(3,'App\\User','af7f8d72-023c-47d1-ba90-29a20f6a4231'),(2,'App\\User','b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1'),(3,'App\\User','d0c10ae9-ffd8-4ef5-b9ef-fabec7d0d1b4'),(3,'App\\User','d9d25896-6a97-4997-bea2-e6c2c322efae'),(3,'App\\User','e15afdc0-03f0-40c2-86a0-01dfd6b9feab'),(2,'App\\User','e9a2f744-8431-4efe-9b0b-c80deac79850'),(3,'App\\User','f300236f-6fe1-41b4-bca1-7eb260bf21b3'); 
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `password_resets` -- DROP TABLE IF EXISTS 
`password_resets`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`) ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = 
@saved_cs_client */; -- -- Dumping data for table `password_resets` -- LOCK TABLES `password_resets` WRITE; /*!40000 ALTER TABLE `password_resets` 
DISABLE KEYS */; INSERT INTO `password_resets` VALUES 
('franswhite254@gmail.com','$2y$10$bZMc85WmnmB1RvM6TGnP1u12zD6HqtVsN3DMU1YMntU0VHavn/w5.','2021-12-20 
18:07:08'),('wanguiwamutitu@gmail.com','$2y$10$liZpiZgLzJMVpveKrtHg.uWYKD/1EK56eZJ0odl0s8RXen4NK9bEm','2022-01-04 13:50:39'); /*!40000 ALTER TABLE 
`password_resets` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `permissions` -- DROP TABLE IF EXISTS `permissions`; /*!40101 SET 
@saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = 
@saved_cs_client */; -- -- Dumping data for table `permissions` -- LOCK TABLES `permissions` WRITE; /*!40000 ALTER TABLE `permissions` DISABLE KEYS 
*/; INSERT INTO `permissions` VALUES (1,'manage system','web','2021-07-22 12:37:58',NULL); /*!40000 ALTER TABLE `permissions` ENABLE KEYS */; UNLOCK 
TABLES; -- -- Table structure for table `production_materials` -- DROP TABLE IF EXISTS `production_materials`; /*!40101 SET @saved_cs_client = 
@@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `production_materials` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `raw_material_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `production_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` double(13,4) NOT NULL,
  `quantity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `production_materials_cooperative_id_foreign` (`cooperative_id`),
  KEY `production_materials_raw_material_id_foreign` (`raw_material_id`),
  KEY `production_materials_production_id_foreign` (`production_id`),
  KEY `production_materials_unit_id_foreign` (`unit_id`),
  CONSTRAINT `production_materials_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `production_materials_production_id_foreign` FOREIGN KEY (`production_id`) REFERENCES `productions` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `production_materials_raw_material_id_foreign` FOREIGN KEY (`raw_material_id`) REFERENCES `raw_materials` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `production_materials_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table 
`production_materials` -- LOCK TABLES `production_materials` WRITE; /*!40000 ALTER TABLE `production_materials` DISABLE KEYS */; INSERT INTO 
`production_materials` VALUES 
('73b3e98b-fd8e-4680-8788-b5db092c7c4e','445303e3-60f8-4303-abb2-8f977ac3955a','b3ee8c3e-7153-4a78-b0f6-0caca4281b1f','5dae83b2-e858-4d55-922b-5295b8e9f81f',20.0000,'1','8fe2fb27-23d1-4e5b-9a06-63191f1a07be','2021-12-17 
12:42:07','2022-01-06 15:39:21','2022-01-06 15:39:21'); /*!40000 ALTER TABLE `production_materials` ENABLE KEYS */; UNLOCK TABLES; -- -- Table 
structure for table `productions` -- DROP TABLE IF EXISTS `productions`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET 
character_set_client = utf8 */; CREATE TABLE `productions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `final_product_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_selling_price` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `available_quantity` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `productions_final_product_id_foreign` (`final_product_id`),
  KEY `productions_unit_id_foreign` (`unit_id`),
  CONSTRAINT `productions_final_product_id_foreign` FOREIGN KEY (`final_product_id`) REFERENCES `final_products` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `productions_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `productions` -- LOCK TABLES 
`productions` WRITE; /*!40000 ALTER TABLE `productions` DISABLE KEYS */; INSERT INTO `productions` VALUES 
('66a23ef4-44bc-4bc1-a3bf-193e116d7797','6ea99c4f-eae3-473d-bb02-588464b952c0','100','0b63595c-0685-4faf-9091-4a608e6015df','250','2021-12-16 
21:16:49','2022-02-05 14:57:13','2022-02-05 
14:57:13',NULL),('b3ee8c3e-7153-4a78-b0f6-0caca4281b1f','32ab16ff-a397-44df-adf9-372adb42a38b','1','85076d1d-a9f7-4bc0-8cbc-96fee9827154','1000','2021-12-17 
12:42:07','2022-01-06 15:39:21','2022-01-06 15:39:21',NULL); /*!40000 ALTER TABLE `productions` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure 
for table `products` -- DROP TABLE IF EXISTS `products`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client 
= utf8 */; CREATE TABLE `products` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'N/A',
  `sale_price` double NOT NULL,
  `vat` double NOT NULL DEFAULT '0',
  `serial_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `buying_price` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `products_cooperative_id_foreign` (`cooperative_id`),
  KEY `products_category_id_foreign` (`category_id`),
  KEY `products_unit_id_foreign` (`unit_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `products_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `products` -- LOCK 
TABLES `products` WRITE; /*!40000 ALTER TABLE `products` DISABLE KEYS */; INSERT INTO `products` VALUES ('1ca1979b-f2a2-4208-9e9a-60b4fec70f80','Dry 
Pineapples','5dae83b2-e858-4d55-922b-5295b8e9f81f','Pineapples',100,16,'4512020',NULL,'8aa37bac-42ef-44a4-a2f2-bf69316308e3','8fe2fb27-23d1-4e5b-9a06-63191f1a07be','2021-12-17 
12:34:02','2021-12-17 
12:34:02',NULL,25),('24173b51-713b-40d7-bb97-0c1e7238021a','Mango','5dae83b2-e858-4d55-922b-5295b8e9f81f','Mango',30,16,'SN100100',NULL,'131de6de-1708-43f5-98a0-6f6dbea42cea','573a8272-3e37-4fe5-9730-f6d906286bda','2021-12-17 
01:29:23','2021-12-17 
01:29:23',NULL,5),('4f1f0ace-936e-4a69-a517-5fdf81c6ddcd','Cotton','5dae83b2-e858-4d55-922b-5295b8e9f81f','Cotton',210,7,'SN1234Cotton',NULL,'2dd259d8-220a-4f85-b15b-365f1c63be43','573a8272-3e37-4fe5-9730-f6d906286bda','2021-12-27 
15:32:39','2021-12-27 
15:32:39',NULL,65),('6e8ea072-2c88-4be1-9bba-c4f2f36b8b71','Leather','5dae83b2-e858-4d55-922b-5295b8e9f81f','Leather',350.9,8,'SN1234',NULL,'ba46a958-2755-4575-a929-ae8fa1060ba8','8fe2fb27-23d1-4e5b-9a06-63191f1a07be','2021-12-20 
21:05:45','2021-12-20 
21:05:45',NULL,120.75),('7e3ad0c9-b573-4d2b-b7c4-259df964398d','MILK','5dae83b2-e858-4d55-922b-5295b8e9f81f','MILK',100,100,'M0001',NULL,'78953dc4-c0c8-49b8-82b3-c756ead581ed','573a8272-3e37-4fe5-9730-f6d906286bda','2021-07-24 
10:49:12','2021-07-24 
10:49:12',NULL,0),('9d352572-18f9-4486-8add-ca0144292e14','Watermelon','5dae83b2-e858-4d55-922b-5295b8e9f81f','WM',150,8,'SNWM',NULL,'8aa37bac-42ef-44a4-a2f2-bf69316308e3','8fe2fb27-23d1-4e5b-9a06-63191f1a07be','2022-02-12 
16:32:32','2022-02-12 16:32:32',NULL,100),('9e93b4ea-28ad-4c6d-9c58-b1cf21921e8e','Feeds','5dae83b2-e858-4d55-922b-5295b8e9f81f','Animal 
Feeds',50,50,'Feeds -001',NULL,'78953dc4-c0c8-49b8-82b3-c756ead581ed','8fe2fb27-23d1-4e5b-9a06-63191f1a07be','2021-07-25 10:21:33','2021-07-25 
10:21:33',NULL,0),('a2b4651f-4238-4cc9-b9ed-92407440070d','Miraa','5dae83b2-e858-4d55-922b-5295b8e9f81f','Miraa',76,10,'SN1234Miraa',NULL,'131de6de-1708-43f5-98a0-6f6dbea42cea','8fe2fb27-23d1-4e5b-9a06-63191f1a07be','2022-01-04 
13:30:49','2022-01-04 
13:30:49',NULL,45),('c04a4db3-0ce1-4b75-8df0-98373138fe80','COFFEE','5dae83b2-e858-4d55-922b-5295b8e9f81f','COFFEE',100,10,'C-OO1',NULL,'131de6de-1708-43f5-98a0-6f6dbea42cea','8fe2fb27-23d1-4e5b-9a06-63191f1a07be','2021-07-24 
10:49:44','2021-07-24 
10:49:44',NULL,0),('f62bb800-1182-4108-92dc-7d33da5e66df','Milk','4995bc45-7b46-473c-8658-76d78011ae0c','Mode',70,5,'SN456797',NULL,'d6faa137-9c23-4aa8-b387-08fe07921f5d','0b63595c-0685-4faf-9091-4a608e6015df','2021-07-23 
01:05:38','2021-07-23 01:05:38',NULL,0); /*!40000 ALTER TABLE `products` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table 
`raw_materials` -- DROP TABLE IF EXISTS `raw_materials`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client 
= utf8 */; CREATE TABLE `raw_materials` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimated_cost` double(13,4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `units` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `raw_materials_product_id_foreign` (`product_id`),
  KEY `raw_materials_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `raw_materials_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `raw_materials_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `raw_materials` -- 
LOCK TABLES `raw_materials` WRITE; /*!40000 ALTER TABLE `raw_materials` DISABLE KEYS */; INSERT INTO `raw_materials` VALUES 
('445303e3-60f8-4303-abb2-8f977ac3955a',NULL,'1ca1979b-f2a2-4208-9e9a-60b4fec70f80',20.0000,'2021-12-17 12:40:00','2021-12-17 
12:40:00',NULL,NULL,NULL),('5200d0e8-b161-4233-944c-a2759e79b2f8',NULL,'24173b51-713b-40d7-bb97-0c1e7238021a',10.0000,'2022-01-06 
15:41:44','2022-01-06 
15:41:44',NULL,'Kg','5dae83b2-e858-4d55-922b-5295b8e9f81f'),('78e4389a-a3e9-4812-a1bd-f9207fd180f4',NULL,'f62bb800-1182-4108-92dc-7d33da5e66df',45.5000,'2022-01-06 
15:41:00','2022-01-06 
15:41:00',NULL,'litre','5dae83b2-e858-4d55-922b-5295b8e9f81f'),('948dd09e-0574-4b13-bbba-d1c51d111a74',NULL,'9d352572-18f9-4486-8add-ca0144292e14',150.0000,'2022-02-12 
17:21:19','2022-02-12 
17:21:19',NULL,NULL,'5dae83b2-e858-4d55-922b-5295b8e9f81f'),('960074c1-d554-4575-87dd-77fbe62bee9e',NULL,'1ca1979b-f2a2-4208-9e9a-60b4fec70f80',30.0000,'2022-02-05 
15:00:59','2022-02-05 
15:00:59',NULL,'Kg','5dae83b2-e858-4d55-922b-5295b8e9f81f'),('bc1dc281-a87d-4a1e-9d35-87cb27435583',NULL,'4f1f0ace-936e-4a69-a517-5fdf81c6ddcd',210.0000,'2022-01-06 
15:13:01','2022-01-06 
15:13:01',NULL,'Kg','5dae83b2-e858-4d55-922b-5295b8e9f81f'),('c7a15d4e-74c7-4d45-93e0-5e741e88033d',NULL,'a2b4651f-4238-4cc9-b9ed-92407440070d',350.0000,'2022-01-11 
15:20:32','2022-01-11 15:20:32',NULL,NULL,'5dae83b2-e858-4d55-922b-5295b8e9f81f'); /*!40000 ALTER TABLE `raw_materials` ENABLE KEYS */; UNLOCK TABLES; 
-- -- Table structure for table `recruitment_applications` -- DROP TABLE IF EXISTS `recruitment_applications`; /*!40101 SET @saved_cs_client = 
@@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `recruitment_applications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `othernames` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_of_residence` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qualification` longtext COLLATE utf8mb4_unicode_ci,
  `top_skills` longtext COLLATE utf8mb4_unicode_ci,
  `resume` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_letter` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `recruitment_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recruitment_applications_recruitment_id_foreign` (`recruitment_id`),
  CONSTRAINT `recruitment_applications_recruitment_id_foreign` FOREIGN KEY (`recruitment_id`) REFERENCES `recruitments` (`id`) ON UPDATE CASCADE ) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for 
table `recruitment_applications` -- LOCK TABLES `recruitment_applications` WRITE; /*!40000 ALTER TABLE `recruitment_applications` DISABLE KEYS */; 
/*!40000 ALTER TABLE `recruitment_applications` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `recruitments` -- DROP TABLE IF EXISTS 
`recruitments`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `recruitments` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `desired_skills` longtext COLLATE utf8mb4_unicode_ci,
  `qualifications` longtext COLLATE utf8mb4_unicode_ci,
  `employment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `salary_range` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `end_date` datetime NOT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recruitments_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `recruitments_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table 
`recruitments` -- LOCK TABLES `recruitments` WRITE; /*!40000 ALTER TABLE `recruitments` DISABLE KEYS */; INSERT INTO `recruitments` VALUES 
('4cda9037-9e2c-408f-8a61-bd3f0136d6d7','ACCOUNTANT','Finance and Accounts Management','Holder of CPA and a Bachelors Degree','Bachelors 
Degree','Permanent','100000','Kenya','',0,'2022-02-09 00:00:00','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-11-20 12:54:56','2021-11-20 
12:54:56',NULL),('865a0b8d-9560-41a7-8fde-433a3ae25b40','Quallity Assurance','Can test quality of produced items','Quality assurance 
certification','Bachelors Degree','Outsourced','80000','KIAMBU','',0,'2022-07-15 00:00:00','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 
16:11:33','2022-01-06 16:11:33',NULL),('947a8aae-5f88-4b2e-a0af-b68f01a5c01f','Communications Officer','Communications','Communication 
skills','Bachelors degree','Permanent','Kes 35000','Kisii','',0,'2022-03-11 00:00:00','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 
17:08:47','2022-02-12 17:08:47',NULL); /*!40000 ALTER TABLE `recruitments` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table 
`role_has_permissions` -- DROP TABLE IF EXISTS `role_has_permissions`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET 
character_set_client = utf8 */; CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table 
`role_has_permissions` -- LOCK TABLES `role_has_permissions` WRITE; /*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */; /*!40000 ALTER TABLE 
`role_has_permissions` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `roles` -- DROP TABLE IF EXISTS `roles`; /*!40101 SET 
@saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) ) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = 
@saved_cs_client */; -- -- Dumping data for table `roles` -- LOCK TABLES `roles` WRITE; /*!40000 ALTER TABLE `roles` DISABLE KEYS */; INSERT INTO 
`roles` VALUES (1,'admin','web','2021-07-22 12:37:58',NULL),(2,'cooperative admin','web','2021-07-22 12:37:58',NULL),(3,'farmer','web','2021-07-22 
12:37:58',NULL),(4,'vet','web','2021-07-22 12:37:58',NULL),(5,'agent','web','2021-07-22 12:37:58',NULL),(6,'accountant','web','2021-07-22 
12:37:58',NULL),(7,'employee','web','2021-07-22 12:37:58',NULL); /*!40000 ALTER TABLE `roles` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for 
table `routes` -- DROP TABLE IF EXISTS `routes`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 
*/; CREATE TABLE `routes` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `routes_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `routes_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for 
table `routes` -- LOCK TABLES `routes` WRITE; /*!40000 ALTER TABLE `routes` DISABLE KEYS */; INSERT INTO `routes` VALUES 
('0ced6a08-c2e2-40da-bfab-064bc7e49799','ELDAMA RAVINE','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:43:17','2021-07-24 
10:43:17',NULL),('1fe0ef63-8903-44f5-969c-1572da384d82','Meru','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:43:08','2021-07-24 
10:43:08',NULL),('44769ba4-d939-4569-b5c7-c062266534ac','Kiambu','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:42:58','2021-07-24 
10:42:58',NULL),('55f9c07f-6a15-41b7-9b05-99532202e8ca','Mombasa','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 09:02:14','2021-12-17 
09:02:14',NULL),('6d0fea8c-c209-41a1-b424-9a1755fdf74a','Taita','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 09:02:04','2021-12-17 
09:02:04',NULL),('8bc6dda2-d0eb-47aa-b051-9366eb7fe5fd','Kisumu','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-27 15:27:53','2021-12-27 
15:27:53',NULL),('c355b1b5-e1c0-4607-a0b1-bc6266fec460','Nairobi','4995bc45-7b46-473c-8658-76d78011ae0c','2021-07-23 00:57:52','2021-07-23 
00:57:52',NULL),('d0a8c2a0-a7fd-43f5-a2fe-b3c6c1c65cd5','Migori','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 12:28:43','2021-12-17 
12:28:43',NULL),('dadfecf6-6ca1-43d5-9c48-73dd49b21c5f','Nyeri','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 20:54:36','2021-12-20 
20:54:36',NULL),('e0d0ef23-42f7-40c6-98de-66573a9b6cdb','Kisii','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:28:04','2022-02-12 
16:28:04',NULL); /*!40000 ALTER TABLE `routes` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `sale_items` -- DROP TABLE IF EXISTS 
`sale_items`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `sale_items` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `manufactured_product_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `collection_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sales_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `quantity` double NOT NULL DEFAULT '1',
  `discount` double NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_items_manufactured_product_id_foreign` (`manufactured_product_id`),
  KEY `sale_items_collection_id_foreign` (`collection_id`),
  KEY `sale_items_sales_id_foreign` (`sales_id`),
  CONSTRAINT `sale_items_collection_id_foreign` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `sale_items_manufactured_product_id_foreign` FOREIGN KEY (`manufactured_product_id`) REFERENCES `productions` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `sale_items_sales_id_foreign` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `sale_items` -- LOCK 
TABLES `sale_items` WRITE; /*!40000 ALTER TABLE `sale_items` DISABLE KEYS */; /*!40000 ALTER TABLE `sale_items` ENABLE KEYS */; UNLOCK TABLES; -- -- 
Table structure for table `sales` -- DROP TABLE IF EXISTS `sales`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET 
character_set_client = utf8 */; CREATE TABLE `sales` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `farmer_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sale_batch_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `type` enum('sale','quotation') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sale',
  PRIMARY KEY (`id`),
  KEY `sales_farmer_id_foreign` (`farmer_id`),
  KEY `sales_cooperative_id_foreign` (`cooperative_id`),
  KEY `sales_customer_id_foreign` (`customer_id`),
  KEY `sales_user_id_foreign` (`user_id`),
  CONSTRAINT `sales_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `sales_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `sales` -- LOCK TABLES `sales` 
WRITE; /*!40000 ALTER TABLE `sales` DISABLE KEYS */; INSERT INTO `sales` VALUES 
('0f6cfb4e-e921-4645-b835-f0dbae4cd5c6','2c5465ee-db91-46d9-a4d2-8dfc64370820','ae0c23e3-985b-4f06-931a-94a813c67404','5dae83b2-e858-4d55-922b-5295b8e9f81f',NULL,'202201oLlSyidKfTxH2weu','2022-01-11','2022-01-11 
15:22:38','2022-01-11 
15:22:38',NULL,'sale'),('1aa7c2b9-a14d-4663-b615-807956e8cb54',NULL,'ae0c23e3-985b-4f06-931a-94a813c67404','5dae83b2-e858-4d55-922b-5295b8e9f81f','887c46fd-d00f-4735-a40e-ee58fb913037','202202fWNWTwEG0Gi2yIrN','2022-02-10','2022-02-12 
17:25:36','2022-02-12 
17:25:36',NULL,'quotation'),('46059bc7-5f8d-496e-b08f-8e3e0e1a0053','ae87e19d-a5a1-4927-9cd3-720beecf19ce','ae0c23e3-985b-4f06-931a-94a813c67404','5dae83b2-e858-4d55-922b-5295b8e9f81f',NULL,'202202hTZK0U32JeOKXX58','2022-02-07','2022-02-07 
19:43:02','2022-02-07 
19:43:02',NULL,'sale'),('56690ed4-2c93-4dd3-b1e9-cd4f9d31ad7e','ae87e19d-a5a1-4927-9cd3-720beecf19ce','ae0c23e3-985b-4f06-931a-94a813c67404','5dae83b2-e858-4d55-922b-5295b8e9f81f',NULL,'202202p8V4SMZhPXelhbjs','2022-02-04','2022-02-04 
21:27:44','2022-02-04 
21:27:44',NULL,'quotation'),('89a600aa-a050-40e0-8d58-4462d2b7effb',NULL,'ae0c23e3-985b-4f06-931a-94a813c67404','5dae83b2-e858-4d55-922b-5295b8e9f81f','887c46fd-d00f-4735-a40e-ee58fb913037','202202IkpepnSwirJpiFLi','2022-02-02','2022-02-05 
14:23:20','2022-02-05 14:23:20',NULL,'sale'); /*!40000 ALTER TABLE `sales` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `units` -- 
DROP TABLE IF EXISTS `units`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`units` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `units_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `units_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for 
table `units` -- LOCK TABLES `units` WRITE; /*!40000 ALTER TABLE `units` DISABLE KEYS */; INSERT INTO `units` VALUES 
('0b63595c-0685-4faf-9091-4a608e6015df','Litres','4995bc45-7b46-473c-8658-76d78011ae0c','2021-07-23 01:04:09','2021-07-23 
01:04:09',NULL),('573a8272-3e37-4fe5-9730-f6d906286bda','LITRES','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:48:38','2021-07-24 
10:48:38',NULL),('7fc34530-2637-4846-a99e-b1600198bc11','Ounces','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 16:30:06','2022-02-12 
16:30:06',NULL),('85076d1d-a9f7-4bc0-8cbc-96fee9827154','Kgs','4995bc45-7b46-473c-8658-76d78011ae0c','2021-07-23 01:04:23','2021-07-23 
01:04:23',NULL),('8fe2fb27-23d1-4e5b-9a06-63191f1a07be','Kgs','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-07-24 10:48:28','2021-07-24 
10:48:28',NULL),('e899c22d-f213-4c19-b995-bdf2e3fb8944','Metre','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-01-06 15:08:05','2022-01-06 
15:08:05',NULL); /*!40000 ALTER TABLE `units` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `users` -- DROP TABLE IF EXISTS `users`; 
/*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `other_names` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `users_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for 
table `users` -- LOCK TABLES `users` WRITE; /*!40000 ALTER TABLE `users` DISABLE KEYS */; INSERT INTO `users` VALUES 
('00462423-508f-4cc1-88bc-52d5b5e11586','Michael','Muriuki','5dae83b2-e858-4d55-922b-5295b8e9f81f','MICHAEL','michael@gmail.com',NULL,'$2y$10$V8.dfM2C0ZVwkAO06We6I.HMDt0nvv/9xbsVf9z/UlNyb0G841XZa',NULL,'2021-07-24 
10:56:18','2021-07-24 
10:56:18'),('10412d4d-4db7-4999-93c4-7e5b8f452582','Robert','Wanjiku','5dae83b2-e858-4d55-922b-5295b8e9f81f','Robert','robert@gmail.com',NULL,'$2y$10$zsXBNYvsZMiwfttzWjwbs.nqTJdUyqpznRkU1/RMBqlhvFHyQNfoG',NULL,'2021-11-20 
12:57:39','2021-11-20 
12:57:39'),('283b2a32-1ca8-4b59-a623-58de078147ab','Joyce','Murume','5dae83b2-e858-4d55-922b-5295b8e9f81f','JoyceM','joycemurume@gmail.com',NULL,'$2y$10$lI5.FfGbRk5N/LByYKaS1OG8vCWp0JQpUhQu8pYtKz5IrQddR42Eu',NULL,'2022-01-04 
13:34:17','2022-01-04 
13:34:17'),('2a05069e-4f8e-46a0-931a-a88e0b8d23b9','Francis','Mwangi','40801158-8d2a-49f5-920c-8e9b3f94240c','Mwangi','franswhite254@gmail.com',NULL,'$2y$10$71Jne.kpmF9GsuwBTIDkmOrmoYiYmhyscdUIN8rk35eHb7Y6S.6Ty',NULL,'2021-12-20 
18:02:32','2021-12-20 
18:02:32'),('2f46da5f-6c3a-43db-8f33-ab8acc6c89bf','John','Kamau','68dc8065-6920-4138-8f9e-912d93f51ed7','J.Kamau','jkamau@maziwa.com',NULL,'$2y$10$oyKFZrrIuPEqONdsD/B.2.7qX8UNvVvn5J/zYp6SZ/4AGjNFeCAmu',NULL,'2021-12-20 
20:37:19','2021-12-20 
20:37:19'),('3366c996-2080-4cf2-a060-8ac85b2c8b80','James','Mwaniki','5dae83b2-e858-4d55-922b-5295b8e9f81f','jmwaniki','jmwaniki@gmail.com',NULL,'$2y$10$ni4P1FhU5AkwhHP9.27A9ulWfQh8qvNLdJpA6/xw/ZlODsT2kJ08m',NULL,'2021-12-13 
16:41:14','2021-12-13 
16:41:14'),('37e9f0a5-aadd-441c-8a1c-02810e0f6387','Susan','Malindi','5dae83b2-e858-4d55-922b-5295b8e9f81f','Susan','susan@gmail.com',NULL,'$2y$10$4JdJT74re85pZzyt.4vCKuS1aBdb.N.JC1oL19pq8hxNdRHuTKngC',NULL,'2021-12-17 
08:58:19','2021-12-17 
08:58:19'),('395af6b3-bf32-48c6-8570-679ebb48f435','Juliet','Gesago','5dae83b2-e858-4d55-922b-5295b8e9f81f','J.Gesago','jjulietgesago@farmers.com',NULL,'$2y$10$Ih2ZZcqQeWVClHArW5B63uEzTLdI64WFyU/YS3Rlhe2IGoZqd/Ojm',NULL,'2022-02-12 
16:36:12','2022-02-12 16:36:12'),('3a1c5fd8-c546-4598-907f-cab1c4691673','Dr 
Monica','Bett','5dae83b2-e858-4d55-922b-5295b8e9f81f','254726796059','gmwangi13@gmail.com',NULL,'$2y$10$xaNucVS25hfU3SEwZwn3NOGc.S0wkkFauoFfpP6yUydz1grq9RXPy',NULL,'2021-12-13 
17:48:45','2021-12-13 
17:48:45'),('3c71e95f-2785-4d86-9c02-f54b3d8e9ae9','Lucy','Wanjiru','5dae83b2-e858-4d55-922b-5295b8e9f81f','WANJIRU','wanjirulucy@gmail.com',NULL,'$2y$10$tWoKIdlEAdF6SlAhyswc7OsRkh8UHfif6nFn8EwV8QolI0Ghtxxny',NULL,'2021-07-24 
10:54:52','2021-07-24 
10:54:52'),('45a6aa4a-37a5-47a3-b831-c3cbda8cf1d7','Joyce','Murume','5dae83b2-e858-4d55-922b-5295b8e9f81f','JoyceMeru','joycemeru@gmail.com',NULL,'$2y$10$eA0o5Dr.9vNQW3rnsgps/.80s0wBNwrwbEBhWxWftSq8/ev3r9n56',NULL,'2022-01-04 
13:38:15','2022-01-04 
13:38:15'),('4d327539-ef33-45c0-97ba-50b419577f9f','Mary','Njoroge','5dae83b2-e858-4d55-922b-5295b8e9f81f','m.njoroge','marynjoroge@productions.com',NULL,'$2y$10$wEVj5ctJX0BhvmphsI7KV.ibaxtVM/YHNMSkkCe0NVRnCGCJjBBXK',NULL,'2022-01-06 
16:05:32','2022-01-06 
16:05:32'),('597dfe2f-35c0-4a32-86c7-434901fe7269','Admin','ERP','349228b3-afe3-497a-8001-2d236fb6b50b','admin','admin@erp.com',NULL,'$2y$10$Wo1/AH0DlyQ/s3w4M4kNXeP1zIvHZC0db5B029stVGkVYtMbMotXe',NULL,'2021-07-22 
12:40:09',NULL),('6131b2b3-7245-4363-b25d-d208721f8e12','Elvis','Mutende','de242fbd-8eb8-420e-9adc-394baa8c13a6','elvis','elvismutende@gmail.com',NULL,'$2y$10$Tjl9ID4duAyRfUyRDZKXROUqYfJeA.eGRFP/ZqtMxkPL2kBei2h8i','4EcDnS7YENPXIzqvK71XscxlcKRvonde5GOHCeDrILVkbkeGw9uUevo6dlzw','2021-12-13 
14:14:50','2022-02-04 15:40:28'),('79291b6c-7f9d-467f-be92-d2fafe6d2e18','Dr 
Andrew','Musyoka','5dae83b2-e858-4d55-922b-5295b8e9f81f','254720800800','musyoka@gmail.com',NULL,'$2y$10$Rip1KA0UNtNzuFS0dO3dE.Y.gCaksja4XQ6TEENgfSIRZFUmzHlKS',NULL,'2021-12-13 
16:49:52','2021-12-13 
16:49:52'),('8ad317be-f6ce-4d2d-9856-69abeb325cdf','Morgan','Kiptanui','5dae83b2-e858-4d55-922b-5295b8e9f81f','254712345678','morgankiptanui@vet.com',NULL,'$2y$10$x3rhUZPb9AL1M57v7mfVHe76TFS0QVpvfp/He8RG.sw6VwmZftvS6',NULL,'2022-02-12 
18:00:51','2022-02-12 
18:00:51'),('9e59a2f8-24c8-4c41-b310-3bdfbc5864f6','Caren','Carolyne','de242fbd-8eb8-420e-9adc-394baa8c13a6','254723678563','carencarolyn@gmail.com',NULL,'$2y$10$7IOE24uapeVFzS2d0raJ2udj9ETcUikDx5fbWf9ktuXqCzx7W0xFK',NULL,'2021-12-17 
06:51:17','2021-12-17 06:51:17'),('a1998913-1bd5-46aa-8501-bd4e9564d3b3','James','Dairy','f8be7d28-5260-445c-b30b-7e16733d8cd5','Dairy 
-James','dairy@gmail.com',NULL,'$2y$10$XVQhKbs5D3.U76m6KLk7mub7B/W/zwK1eCaJjmZqaFSQx2hnb/E8i',NULL,'2022-01-13 17:07:59','2022-01-13 
17:07:59'),('a484b8d4-243b-4a7c-ab1a-2340d23ef313','Maryann','Atieno','5dae83b2-e858-4d55-922b-5295b8e9f81f','Atieno_Mary','atienomary@gmail.com',NULL,'$2y$10$Rdsx7PDFEFCnBYBF0CFo..t/BM7LG9DbT8XObosSOvqyW2vKwIat.',NULL,'2021-12-27 
15:57:35','2021-12-27 
15:57:35'),('ae0c23e3-985b-4f06-931a-94a813c67404','John','Smith','5dae83b2-e858-4d55-922b-5295b8e9f81f','Smith','smith@gmail.com',NULL,'$2y$10$7J6jY3cSxKCG.TiE6EEMvurBWJrWsqbaD8Dv4QJLzxDXkgD5lZN.q',NULL,'2021-07-23 
11:23:31','2021-07-23 
11:23:31'),('af7f8d72-023c-47d1-ba90-29a20f6a4231','Mary','Doe','4995bc45-7b46-473c-8658-76d78011ae0c','mary','mary@gmail.com',NULL,'$2y$10$KgnO/nWEgb8qpxt8W9fQsOZqIOiyYdxAmJmBtwIf/zB7oRj3rNxw.',NULL,'2021-07-23 
01:13:23','2021-07-23 
01:13:23'),('b0ce24c6-01ec-4a02-9f6a-9d0c85893ea1','John','Doe','4995bc45-7b46-473c-8658-76d78011ae0c','john','john@gmail.com',NULL,'$2y$10$Tr3U/Y06BTnsrPHu4E2L4Oq88p8/ukLvQ2q308miAeEb5z8yFTAHe',NULL,'2021-07-23 
00:55:26','2021-07-23 
00:55:26'),('bb008cef-2149-4018-b069-9d8032b38809','Joyce','Murume','5dae83b2-e858-4d55-922b-5295b8e9f81f','JoyceMurume','joycemurumemeru@gmail.com',NULL,'$2y$10$a/5rKpzPnqeVBiXnnGuK7ekAKVnVa2ngy3fzwcZEfgxfzskPy4xzK',NULL,'2022-01-04 
13:37:25','2022-01-04 
13:37:25'),('d0c10ae9-ffd8-4ef5-b9ef-fabec7d0d1b4','Jane','Kamau','5dae83b2-e858-4d55-922b-5295b8e9f81f','Jane.K','janekamau@maziwa.com',NULL,'$2y$10$5Zh9fBB0.FJq2BerC/hWZ.wiQS2XiJsJjCf1bAZ5ycDQe3wMXPWJy',NULL,'2021-12-20 
21:00:42','2021-12-20 
21:00:42'),('d9d25896-6a97-4997-bea2-e6c2c322efae','Catherine','Wangui','5dae83b2-e858-4d55-922b-5295b8e9f81f','Catherine','wanguiwamutitu@gmail.com',NULL,'$2y$10$QDpn6O9QvatxjS4lXk3AhOxVKBt/jkjL2.2vW2nzyG9FmD1mKjfAi',NULL,'2021-12-17 
12:20:40','2021-12-17 
12:20:40'),('e15afdc0-03f0-40c2-86a0-01dfd6b9feab','Lucy','Muthoni','5dae83b2-e858-4d55-922b-5295b8e9f81f','LMuthoni','lucymuthoni@maziwa.com',NULL,'$2y$10$OiAAzjUegTagLmgAp47ORuBBZ0dUkKk9LRwWlbAWb5tBmwbMazGwq',NULL,'2021-12-20 
21:16:15','2021-12-20 
21:16:15'),('e9a2f744-8431-4efe-9b0b-c80deac79850','Daisy','Nyawira','5c348d8a-b850-49cb-9383-2a8cf723f933','Nyawira','nyawirad5@gmail.com',NULL,'$2y$10$/M2LcZ2EsHaRVZ5W4Ws2neHxbjF5caU8bitKeM1U9U6peqPIq7XnG',NULL,'2021-12-20 
17:35:31','2021-12-20 
17:35:31'),('ec126071-2854-455c-964c-e70f546b8a9b','Maryann','Atieno','5dae83b2-e858-4d55-922b-5295b8e9f81f','M.Atieno','maryatieno@gmail.com',NULL,'$2y$10$b2785neY4t5yE1UFSB5aduNQu.vfMtRExx7CN5exqWhxpSkeF43jm',NULL,'2021-12-27 
15:55:31','2021-12-27 
15:55:31'),('f300236f-6fe1-41b4-bca1-7eb260bf21b3','Francis','Mwangi','5dae83b2-e858-4d55-922b-5295b8e9f81f','Francis','fmwangi811@gmail.com',NULL,'$2y$10$vswnCOkwmadsvNMONg3kleM3waGEg2e65RPNXdplHLbWYnz6/YprW','u07FVTJSNSri3RSukhSn4UWW4UItSXXE7YyfAjjdvkZ4UvySSASdmjH7S2rk','2021-12-17 
12:27:34','2021-12-23 19:24:36'); /*!40000 ALTER TABLE `users` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `vet_bookings` -- DROP 
TABLE IF EXISTS `vet_bookings`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`vet_bookings` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_start` datetime NOT NULL,
  `event_end` datetime NOT NULL,
  `event_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `farmer_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vet_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vet_bookings_farmer_id_foreign` (`farmer_id`),
  KEY `vet_bookings_vet_id_foreign` (`vet_id`),
  KEY `vet_bookings_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `vet_bookings_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `vet_bookings_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `vet_bookings_vet_id_foreign` FOREIGN KEY (`vet_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table 
`vet_bookings` -- LOCK TABLES `vet_bookings` WRITE; /*!40000 ALTER TABLE `vet_bookings` DISABLE KEYS */; INSERT INTO `vet_bookings` VALUES 
('2734749b-24e3-47a3-983f-9d40148db61a','2021-12-13 14:00:00','2021-12-13 15:00:00','COW 
SICK','3366c996-2080-4cf2-a060-8ac85b2c8b80','79291b6c-7f9d-467f-be92-d2fafe6d2e18','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 
16:59:56','2021-12-13 16:59:56'),('30f52ee3-a57c-46d0-aeba-d5e5cb9e35eb','2022-03-01 15:21:00','2022-03-01 
16:21:00','Mastitis','d0c10ae9-ffd8-4ef5-b9ef-fabec7d0d1b4','3a1c5fd8-c546-4598-907f-cab1c4691673','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-03-01 
18:08:22','2022-03-01 18:08:22'),('5548eee1-820a-4bd0-8dc9-13fb038068c4','2022-02-09 14:00:00','2022-02-09 15:00:00','Full body examination for the 
cattle','e15afdc0-03f0-40c2-86a0-01dfd6b9feab','79291b6c-7f9d-467f-be92-d2fafe6d2e18','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 
14:43:57','2022-02-05 14:43:57'),('a7980583-2362-4290-a9b4-8f610072ad1c','2022-02-17 08:30:00','2022-02-17 
09:30:00','Checkup','37e9f0a5-aadd-441c-8a1c-02810e0f6387','3a1c5fd8-c546-4598-907f-cab1c4691673','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 
16:53:05','2022-02-12 16:53:05'),('ef89a412-8929-4268-a06e-dd3684e72e70','2021-12-23 06:16:00','2021-12-23 07:16:00','Spraying of 
Crops','37e9f0a5-aadd-441c-8a1c-02810e0f6387','3a1c5fd8-c546-4598-907f-cab1c4691673','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 
09:06:34','2021-12-17 09:06:34'); /*!40000 ALTER TABLE `vet_bookings` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `vet_items` -- 
DROP TABLE IF EXISTS `vet_items`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`vet_items` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` double NOT NULL DEFAULT '1',
  `bp` double NOT NULL DEFAULT '1',
  `sp` double NOT NULL DEFAULT '1',
  `unit_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vet_items_unit_id_foreign` (`unit_id`),
  KEY `vet_items_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `vet_items_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `vet_items_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ) ENGINE=InnoDB 
DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `vet_items` 
-- LOCK TABLES `vet_items` WRITE; /*!40000 ALTER TABLE `vet_items` DISABLE KEYS */; INSERT INTO `vet_items` VALUES 
('0f9ddc6b-2770-499b-8d82-38ac591c8c1e','Syringes',100,4500,5500,'8fe2fb27-23d1-4e5b-9a06-63191f1a07be','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 
21:27:12','2021-12-20 
21:27:12',NULL),('247851d2-8486-40b6-b1dd-60eb13e3ddd7','Fungicides',1,100,150,'573a8272-3e37-4fe5-9730-f6d906286bda','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 
09:05:37','2021-12-17 09:05:37',NULL),('4e17793a-4b17-49b0-8721-6989fc22c45a','Overall and 
Gumboots',3,3500,3500,'8fe2fb27-23d1-4e5b-9a06-63191f1a07be','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-05 14:46:44','2022-02-05 
14:46:44',NULL),('678d6cbe-5366-4126-8fde-556cabe82e18','SEMEN',1,100,200,'573a8272-3e37-4fe5-9730-f6d906286bda','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-13 
16:47:24','2021-12-13 
16:47:24',NULL),('68e51798-96d7-4086-8230-3e7e5d1ac04c','Vaccines',220,2500,4000,'573a8272-3e37-4fe5-9730-f6d906286bda','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 
16:48:12','2022-02-12 16:48:12',NULL); /*!40000 ALTER TABLE `vet_items` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `vet_services` 
-- DROP TABLE IF EXISTS `vet_services`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE 
TABLE `vet_services` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cooperative_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vet_services_cooperative_id_foreign` (`cooperative_id`),
  CONSTRAINT `vet_services_cooperative_id_foreign` FOREIGN KEY (`cooperative_id`) REFERENCES `cooperatives` (`id`) ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `vet_services` -- 
LOCK TABLES `vet_services` WRITE; /*!40000 ALTER TABLE `vet_services` DISABLE KEYS */; INSERT INTO `vet_services` VALUES 
('1a1f6f09-6080-4bd4-b93c-5702cac6416b','Crop Spraying','Spraying Crops','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-17 09:05:08','2021-12-17 
09:05:08',NULL),('50ed9320-6ea1-45e8-a883-f9aaff746fdb','Checkups','Regular Checkups','5dae83b2-e858-4d55-922b-5295b8e9f81f','2022-02-12 
16:46:49','2022-02-12 16:46:49',NULL),('7eb19aee-7d59-4df8-9e07-3ba7b91320de','AI SERVICE','Breeding Services - 
Semen','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-07 08:29:27','2021-12-07 
08:29:27',NULL),('bb4bbb6c-04db-433b-bb93-cefcac5f3741','Vaccination','All animal vaccinations','5dae83b2-e858-4d55-922b-5295b8e9f81f','2021-12-20 
21:25:37','2021-12-20 21:25:37',NULL); /*!40000 ALTER TABLE `vet_services` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `vets` -- 
DROP TABLE IF EXISTS `vets`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE 
`vets` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('M','F','X') COLLATE utf8mb4_unicode_ci NOT NULL,
  `profile_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `vets_user_id_foreign` (`user_id`),
  CONSTRAINT `vets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `vets` -- LOCK 
TABLES `vets` WRITE; /*!40000 ALTER TABLE `vets` DISABLE KEYS */; INSERT INTO `vets` VALUES 
('d8b1d684-f1f9-4bbc-abf5-00e51f86be9f','254712345678','1234567','M',NULL,'8ad317be-f6ce-4d2d-9856-69abeb325cdf','2022-02-12 18:00:51','2022-02-12 
18:00:51',NULL),('e49325f5-62d4-4018-bcae-ee426de17ccf','254723678563','12345678','X',NULL,'9e59a2f8-24c8-4c41-b310-3bdfbc5864f6','2021-12-17 
06:51:17','2021-12-17 06:51:17',NULL); /*!40000 ALTER TABLE `vets` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `vets_vets_items` -- 
DROP TABLE IF EXISTS `vets_vets_items`; /*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE 
TABLE `vets_vets_items` (
  `vet_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vet_item_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  KEY `vets_vets_items_vet_id_foreign` (`vet_id`),
  KEY `vets_vets_items_vet_item_id_foreign` (`vet_item_id`),
  CONSTRAINT `vets_vets_items_vet_id_foreign` FOREIGN KEY (`vet_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `vets_vets_items_vet_item_id_foreign` FOREIGN KEY (`vet_item_id`) REFERENCES `vet_items` (`id`) ON DELETE SET NULL ON UPDATE CASCADE ) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for 
table `vets_vets_items` -- LOCK TABLES `vets_vets_items` WRITE; /*!40000 ALTER TABLE `vets_vets_items` DISABLE KEYS */; /*!40000 ALTER TABLE 
`vets_vets_items` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `wallet_transactions` -- DROP TABLE IF EXISTS `wallet_transactions`; 
/*!40101 SET @saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `wallet_transactions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wallet_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` double NOT NULL DEFAULT '0',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `source` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `initiator_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `org_conv_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conv_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `wallet_transactions_wallet_id_foreign` (`wallet_id`),
  KEY `wallet_transactions_initiator_id_foreign` (`initiator_id`),
  CONSTRAINT `wallet_transactions_initiator_id_foreign` FOREIGN KEY (`initiator_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table 
`wallet_transactions` -- LOCK TABLES `wallet_transactions` WRITE; /*!40000 ALTER TABLE `wallet_transactions` DISABLE KEYS */; /*!40000 ALTER TABLE 
`wallet_transactions` ENABLE KEYS */; UNLOCK TABLES; -- -- Table structure for table `wallets` -- DROP TABLE IF EXISTS `wallets`; /*!40101 SET 
@saved_cs_client = @@character_set_client */; /*!40101 SET character_set_client = utf8 */; CREATE TABLE `wallets` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `available_balance` double NOT NULL DEFAULT '0',
  `current_balance` double NOT NULL DEFAULT '0',
  `farmer_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `wallets_farmer_id_foreign` (`farmer_id`),
  CONSTRAINT `wallets_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`id`) ON UPDATE CASCADE ) ENGINE=InnoDB DEFAULT 
CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci; /*!40101 SET character_set_client = utf8 */; -- -- Dumping data for table `wallets` -- LOCK 
TABLES `wallets` WRITE; /*!40000 ALTER TABLE `wallets` DISABLE KEYS */; /*!40000 ALTER TABLE `wallets` ENABLE KEYS */; UNLOCK TABLES; /*!40103 SET 
TIME_ZONE=@OLD_TIME_ZONE */; /*!40101 SET SQL_MODE=@OLD_SQL_MODE */; /*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */; /*!40014 SET 
UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */; /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */; /*!40101 SET 
CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */; /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */; /*!40111 SET 
SQL_NOTES=@OLD_SQL_NOTES */; -- Dump completed on 2022-03-08 2:29:56

