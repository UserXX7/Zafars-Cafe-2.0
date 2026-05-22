USE zafars_cafe;

UPDATE products
SET product_name = 'Kettle Brand Sea Salt',
    description = 'Kettle Brand sea salt potato chips.',
    image = 'seasalt.jpg'
WHERE product_id = 204;

UPDATE products
SET product_name = 'Kettle Brand Jalapeno',
    description = 'Kettle Brand hot jalapeno potato chips.',
    image = 'jalapeno.jpg'
WHERE product_id = 205;

UPDATE products
SET product_name = 'Herrs Barbecue Potato Chips',
    description = 'Herrs barbecue flavored potato chips.',
    image = 'bbq.jpg'
WHERE product_id = 206;

UPDATE products
SET product_name = 'Herrs Salt & Vinegar Potato Chips',
    description = 'Herrs salt and vinegar flavored potato chips.',
    image = 'saltvinegar.jpg'
WHERE product_id = 207;
