SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

INSERT INTO `usuarios` (`idUsuario`, `usuario`, `apellido`, `contrasena`, `correo`, `cedula`, `activo`)
VALUES (1, 'NaomiADM', 'Perea', '$2y$10$0wEO3gb.wR91bCcb/NiGTOVGQlj/8ILegszA16Ne.kQZSqGuhnAdC', 'naomiperea@gmail.com',
        '8-789-1456', 0),
       (2, 'Mulino', 'Martinez', '$2y$10$0wEO3gb.wR91bCcb/NiGTOVGQlj/8ILegszA16Ne.kQZSqGuhnAdC',
        'mulinoadm@gmail.com', '4-968-1582', 1);
INSERT INTO secciones (nombre, ubicacion)
VALUES ('Sucursal 1', 'Pasillo A, Estante 1'),
       ('Sucursal 2', 'Pasillo C, Estante 3'),
       ('Sucursal 3', 'Pasillo E, Estante 5'),
       ('Sucursal 4', 'Pasillo H, Estante 8');

INSERT INTO inventario (parte, marca, modelo, fecha, cantidad, costo, idSeccion, imagen, descripcion)
VALUES
    ('Parte A', 'Toyota', 'Corolla', 2023, 10, 150.00, 1, '../images/162100100.gif', 'Motor de arranque para Toyota Corolla, altamente duradero.'),
    ('Parte B', 'Toyota', 'Camry', 2023, 15, 200.50, 2, '../images/410103900.gif', 'Bomba de agua para Toyota Camry, eficiente y confiable.'),
    ('Parte C', 'Toyota', 'RAV4', 2023, 8, 250.75, 3, '../images/478100050.gif', 'Amortiguador trasero diseñado para Toyota RAV4.'),
    ('Parte D', 'Toyota', 'Hilux', 2023, 20, 300.40, 4, '../images/478103610.gif', 'Filtro de aire para Toyota Hilux, optimiza el rendimiento del motor.'),

    ('Parte E', 'Nissan', 'Altima', 2023, 12, 180.20, 1, '../images/B2_201710_078_A1002_A1002_003.gif', 'Radiador compatible con Nissan Altima, con excelente capacidad de enfriamiento.'),
    ('Parte F', 'Nissan', 'Sentra', 2023, 18, 160.30, 2, '../images/B2_201710_078_A1010_A1010_005.gif', 'Bujías para Nissan Sentra, mejora la ignición y el rendimiento.'),
    ('Parte G', 'Nissan', 'Pathfinder', 2023, 5, 275.90, 3, '../images/B2_201710_078_A1010_A1010_007.gif', 'Juego de frenos traseros de alta calidad para Nissan Pathfinder.'),
    ('Parte H', 'Nissan', 'Frontier', 2023, 7, 220.50, 4, '../images/B2_201710_078_A1101_A1101_004.gif', 'Eje de transmisión para Nissan Frontier, resistente y durable.'),

    ('Parte I', 'Volkswagen', 'Jetta', 2023, 25, 140.70, 1, '../images/MA0002I.gif', 'Filtro de aceite para Volkswagen Jetta, prolonga la vida útil del motor.'),
    ('Parte J', 'Volkswagen', 'Golf', 2023, 30, 170.80, 2, '../images/MA0009H.gif', 'Bomba de combustible eléctrica para Volkswagen Golf.'),
    ('Parte K', 'Volkswagen', 'Passat', 2023, 9, 210.60, 3, '../images/MA2151B.gif', 'Sistema de escape para Volkswagen Passat, reduce emisiones.'),
    ('Parte L', 'Volkswagen', 'Tiguan', 2023, 14, 260.90, 4, '../images/090717.gif', 'Amortiguador delantero compatible con Volkswagen Tiguan.');