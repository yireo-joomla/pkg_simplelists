DROP TABLE IF EXISTS #__simplelists;
DROP TABLE IF EXISTS #__simplelists_items;
DROP TABLE IF EXISTS #__simplelists_categories;
DROP TABLE IF EXISTS #__simplelists_plugins;
DELETE FROM #__categories WHERE extension = "com_simplelists";
