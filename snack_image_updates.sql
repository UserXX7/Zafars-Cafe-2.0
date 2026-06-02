USE zafars_cafe;

UPDATE products SET image = 'snacks/doritos.jpg' WHERE product_id = 12;
UPDATE products SET image = 'snacks/cheetos.jpg' WHERE product_id = 13;
UPDATE products SET image = 'snacks/lays-original.jpg' WHERE product_id = 14;
UPDATE products SET image = 'snacks/lays-sourcream.jpg' WHERE product_id = 15;
UPDATE products SET image = 'snacks/oreo.jpg' WHERE product_id = 30;
UPDATE products SET image = 'snacks/kitkat.jpg' WHERE product_id = 31;

UPDATE products SET image = 'snacks/lays.jpg' WHERE product_id IN (178, 179, 180, 181);
UPDATE products SET image = 'snacks/doritos.jpg' WHERE product_id IN (182, 183, 184, 185);
UPDATE products SET image = 'snacks/cheetos.jpg' WHERE product_id IN (186, 187, 188);
UPDATE products SET image = 'snacks/ruffles.jpg' WHERE product_id IN (189, 190, 191);
UPDATE products SET image = 'snacks/pringles.jpg' WHERE product_id IN (192, 193, 194, 195);
UPDATE products SET image = 'snacks/takis.jpg' WHERE product_id IN (196, 197);
UPDATE products SET image = 'snacks/tostitos.jpg' WHERE product_id IN (198, 199);
UPDATE products SET image = 'snacks/fritos.jpg' WHERE product_id IN (200, 201);
UPDATE products SET image = 'snacks/sunchips.jpg' WHERE product_id IN (202, 203);

UPDATE products SET image = 'snacks/seasalt.jpg' WHERE product_id = 204;
UPDATE products SET image = 'snacks/jalapeno.jpg' WHERE product_id = 205;
UPDATE products SET image = 'snacks/bbq.jpg' WHERE product_id = 206;
UPDATE products SET image = 'snacks/saltvinegar.jpg' WHERE product_id = 207;
