<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Product Factory with 5-Layer Specs Model
 * 
 * Layers:
 * - facts: Raw specifications (brand, model, etc.)
 * - needs: What the component requires
 * - provides: What the component offers
 * - limits: Physical/electrical constraints
 * - meta: Performance scores and future considerations
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    // ===================== CPU DATA =====================
    protected array $cpuData = [
        // AMD Ryzen 9000 Series
        ['name' => 'AMD Ryzen 9 9950X', 'price' => 599, 'socket' => 'AM5', 'cores' => 16, 'threads' => 32, 'tdp' => 170, 'boost' => 5.7, 'tier' => 98],
        ['name' => 'AMD Ryzen 9 9900X', 'price' => 449, 'socket' => 'AM5', 'cores' => 12, 'threads' => 24, 'tdp' => 120, 'boost' => 5.6, 'tier' => 94],
        ['name' => 'AMD Ryzen 7 9700X', 'price' => 349, 'socket' => 'AM5', 'cores' => 8, 'threads' => 16, 'tdp' => 65, 'boost' => 5.5, 'tier' => 88],
        ['name' => 'AMD Ryzen 5 9600X', 'price' => 279, 'socket' => 'AM5', 'cores' => 6, 'threads' => 12, 'tdp' => 65, 'boost' => 5.4, 'tier' => 80],
        // AMD Ryzen 7000 Series
        ['name' => 'AMD Ryzen 9 7950X3D', 'price' => 699, 'socket' => 'AM5', 'cores' => 16, 'threads' => 32, 'tdp' => 120, 'boost' => 5.7, 'tier' => 97],
        ['name' => 'AMD Ryzen 9 7950X', 'price' => 549, 'socket' => 'AM5', 'cores' => 16, 'threads' => 32, 'tdp' => 170, 'boost' => 5.7, 'tier' => 95],
        ['name' => 'AMD Ryzen 9 7900X3D', 'price' => 499, 'socket' => 'AM5', 'cores' => 12, 'threads' => 24, 'tdp' => 120, 'boost' => 5.6, 'tier' => 93],
        ['name' => 'AMD Ryzen 9 7900X', 'price' => 399, 'socket' => 'AM5', 'cores' => 12, 'threads' => 24, 'tdp' => 170, 'boost' => 5.6, 'tier' => 91],
        ['name' => 'AMD Ryzen 7 7800X3D', 'price' => 449, 'socket' => 'AM5', 'cores' => 8, 'threads' => 16, 'tdp' => 120, 'boost' => 5.0, 'tier' => 92],
        ['name' => 'AMD Ryzen 7 7700X', 'price' => 299, 'socket' => 'AM5', 'cores' => 8, 'threads' => 16, 'tdp' => 105, 'boost' => 5.4, 'tier' => 85],
        ['name' => 'AMD Ryzen 5 7600X', 'price' => 229, 'socket' => 'AM5', 'cores' => 6, 'threads' => 12, 'tdp' => 105, 'boost' => 5.3, 'tier' => 78],
        ['name' => 'AMD Ryzen 5 7600', 'price' => 199, 'socket' => 'AM5', 'cores' => 6, 'threads' => 12, 'tdp' => 65, 'boost' => 5.1, 'tier' => 75],
        // Intel 14th Gen
        ['name' => 'Intel Core i9-14900K', 'price' => 589, 'socket' => 'LGA1700', 'cores' => 24, 'threads' => 32, 'tdp' => 253, 'boost' => 6.0, 'tier' => 96],
        ['name' => 'Intel Core i9-14900KF', 'price' => 549, 'socket' => 'LGA1700', 'cores' => 24, 'threads' => 32, 'tdp' => 253, 'boost' => 6.0, 'tier' => 96],
        ['name' => 'Intel Core i7-14700K', 'price' => 409, 'socket' => 'LGA1700', 'cores' => 20, 'threads' => 28, 'tdp' => 253, 'boost' => 5.6, 'tier' => 90],
        ['name' => 'Intel Core i7-14700KF', 'price' => 379, 'socket' => 'LGA1700', 'cores' => 20, 'threads' => 28, 'tdp' => 253, 'boost' => 5.6, 'tier' => 90],
        ['name' => 'Intel Core i5-14600K', 'price' => 319, 'socket' => 'LGA1700', 'cores' => 14, 'threads' => 20, 'tdp' => 181, 'boost' => 5.3, 'tier' => 82],
        ['name' => 'Intel Core i5-14600KF', 'price' => 294, 'socket' => 'LGA1700', 'cores' => 14, 'threads' => 20, 'tdp' => 181, 'boost' => 5.3, 'tier' => 82],
        // Intel 13th Gen
        ['name' => 'Intel Core i9-13900K', 'price' => 549, 'socket' => 'LGA1700', 'cores' => 24, 'threads' => 32, 'tdp' => 253, 'boost' => 5.8, 'tier' => 94],
        ['name' => 'Intel Core i7-13700K', 'price' => 379, 'socket' => 'LGA1700', 'cores' => 16, 'threads' => 24, 'tdp' => 253, 'boost' => 5.4, 'tier' => 88],
        ['name' => 'Intel Core i5-13600K', 'price' => 299, 'socket' => 'LGA1700', 'cores' => 14, 'threads' => 20, 'tdp' => 181, 'boost' => 5.1, 'tier' => 80],
        // AMD Ryzen 5000 Series (AM4)
        ['name' => 'AMD Ryzen 9 5950X', 'price' => 399, 'socket' => 'AM4', 'cores' => 16, 'threads' => 32, 'tdp' => 105, 'boost' => 4.9, 'tier' => 88],
        ['name' => 'AMD Ryzen 9 5900X', 'price' => 299, 'socket' => 'AM4', 'cores' => 12, 'threads' => 24, 'tdp' => 105, 'boost' => 4.8, 'tier' => 85],
        ['name' => 'AMD Ryzen 7 5800X3D', 'price' => 299, 'socket' => 'AM4', 'cores' => 8, 'threads' => 16, 'tdp' => 105, 'boost' => 4.5, 'tier' => 86],
        ['name' => 'AMD Ryzen 7 5800X', 'price' => 199, 'socket' => 'AM4', 'cores' => 8, 'threads' => 16, 'tdp' => 105, 'boost' => 4.7, 'tier' => 78],
        ['name' => 'AMD Ryzen 5 5600X', 'price' => 149, 'socket' => 'AM4', 'cores' => 6, 'threads' => 12, 'tdp' => 65, 'boost' => 4.6, 'tier' => 72],
        ['name' => 'AMD Ryzen 5 5600', 'price' => 119, 'socket' => 'AM4', 'cores' => 6, 'threads' => 12, 'tdp' => 65, 'boost' => 4.4, 'tier' => 70],
    ];

    // ===================== MOTHERBOARD DATA =====================
    protected array $motherboardData = [
        // AM5 Boards
        ['name' => 'ASUS ROG Crosshair X670E Hero', 'price' => 699, 'socket' => 'AM5', 'chipset' => 'X670E', 'form' => 'ATX', 'ram' => 'DDR5', 'max_speed' => 7200, 'vrm' => 200],
        ['name' => 'MSI MEG X670E ACE', 'price' => 649, 'socket' => 'AM5', 'chipset' => 'X670E', 'form' => 'E-ATX', 'ram' => 'DDR5', 'max_speed' => 7200, 'vrm' => 190],
        ['name' => 'Gigabyte X670E AORUS Master', 'price' => 499, 'socket' => 'AM5', 'chipset' => 'X670E', 'form' => 'ATX', 'ram' => 'DDR5', 'max_speed' => 6600, 'vrm' => 180],
        ['name' => 'ASUS TUF Gaming X670E-Plus', 'price' => 329, 'socket' => 'AM5', 'chipset' => 'X670E', 'form' => 'ATX', 'ram' => 'DDR5', 'max_speed' => 6400, 'vrm' => 160],
        ['name' => 'MSI MAG X670E TOMAHAWK', 'price' => 299, 'socket' => 'AM5', 'chipset' => 'X670E', 'form' => 'ATX', 'ram' => 'DDR5', 'max_speed' => 6400, 'vrm' => 150],
        ['name' => 'ASRock B650E Steel Legend', 'price' => 229, 'socket' => 'AM5', 'chipset' => 'B650E', 'form' => 'ATX', 'ram' => 'DDR5', 'max_speed' => 6200, 'vrm' => 130],
        ['name' => 'ASUS ROG Strix B650E-F', 'price' => 289, 'socket' => 'AM5', 'chipset' => 'B650E', 'form' => 'ATX', 'ram' => 'DDR5', 'max_speed' => 6400, 'vrm' => 145],
        ['name' => 'MSI MAG B650 TOMAHAWK', 'price' => 219, 'socket' => 'AM5', 'chipset' => 'B650', 'form' => 'ATX', 'ram' => 'DDR5', 'max_speed' => 6000, 'vrm' => 120],
        ['name' => 'Gigabyte B650M AORUS Elite AX', 'price' => 189, 'socket' => 'AM5', 'chipset' => 'B650', 'form' => 'Micro-ATX', 'ram' => 'DDR5', 'max_speed' => 5600, 'vrm' => 110],
        ['name' => 'ASRock B650M-HDV/M.2', 'price' => 119, 'socket' => 'AM5', 'chipset' => 'B650', 'form' => 'Micro-ATX', 'ram' => 'DDR5', 'max_speed' => 5200, 'vrm' => 90],
        // LGA1700 Boards
        ['name' => 'ASUS ROG Maximus Z790 Hero', 'price' => 629, 'socket' => 'LGA1700', 'chipset' => 'Z790', 'form' => 'ATX', 'ram' => 'DDR5', 'max_speed' => 7800, 'vrm' => 200],
        ['name' => 'MSI MEG Z790 ACE', 'price' => 599, 'socket' => 'LGA1700', 'chipset' => 'Z790', 'form' => 'E-ATX', 'ram' => 'DDR5', 'max_speed' => 7600, 'vrm' => 190],
        ['name' => 'Gigabyte Z790 AORUS Master', 'price' => 469, 'socket' => 'LGA1700', 'chipset' => 'Z790', 'form' => 'ATX', 'ram' => 'DDR5', 'max_speed' => 7200, 'vrm' => 180],
        ['name' => 'ASUS TUF Gaming Z790-Plus', 'price' => 299, 'socket' => 'LGA1700', 'chipset' => 'Z790', 'form' => 'ATX', 'ram' => 'DDR5', 'max_speed' => 6400, 'vrm' => 150],
        ['name' => 'MSI MAG Z790 TOMAHAWK', 'price' => 279, 'socket' => 'LGA1700', 'chipset' => 'Z790', 'form' => 'ATX', 'ram' => 'DDR5', 'max_speed' => 6400, 'vrm' => 145],
        ['name' => 'ASRock Z790 Pro RS', 'price' => 219, 'socket' => 'LGA1700', 'chipset' => 'Z790', 'form' => 'ATX', 'ram' => 'DDR5', 'max_speed' => 6000, 'vrm' => 130],
        ['name' => 'ASUS Prime B760M-A', 'price' => 139, 'socket' => 'LGA1700', 'chipset' => 'B760', 'form' => 'Micro-ATX', 'ram' => 'DDR5', 'max_speed' => 5600, 'vrm' => 100],
        ['name' => 'MSI PRO B760M-A', 'price' => 129, 'socket' => 'LGA1700', 'chipset' => 'B760', 'form' => 'Micro-ATX', 'ram' => 'DDR5', 'max_speed' => 5200, 'vrm' => 90],
        // AM4 Boards (DDR4)
        ['name' => 'ASUS ROG Crosshair VIII Hero', 'price' => 349, 'socket' => 'AM4', 'chipset' => 'X570', 'form' => 'ATX', 'ram' => 'DDR4', 'max_speed' => 4400, 'vrm' => 140],
        ['name' => 'MSI MAG B550 TOMAHAWK', 'price' => 179, 'socket' => 'AM4', 'chipset' => 'B550', 'form' => 'ATX', 'ram' => 'DDR4', 'max_speed' => 4400, 'vrm' => 120],
        ['name' => 'Gigabyte B550 AORUS Pro AC', 'price' => 189, 'socket' => 'AM4', 'chipset' => 'B550', 'form' => 'ATX', 'ram' => 'DDR4', 'max_speed' => 4400, 'vrm' => 115],
        ['name' => 'ASUS TUF Gaming B450-Plus II', 'price' => 109, 'socket' => 'AM4', 'chipset' => 'B450', 'form' => 'ATX', 'ram' => 'DDR4', 'max_speed' => 4400, 'vrm' => 95],
        ['name' => 'MSI B450 TOMAHAWK MAX II', 'price' => 99, 'socket' => 'AM4', 'chipset' => 'B450', 'form' => 'ATX', 'ram' => 'DDR4', 'max_speed' => 4133, 'vrm' => 90],
        ['name' => 'Gigabyte B450 AORUS M', 'price' => 89, 'socket' => 'AM4', 'chipset' => 'B450', 'form' => 'Micro-ATX', 'ram' => 'DDR4', 'max_speed' => 3600, 'vrm' => 80],
        ['name' => 'ASRock B450M Pro4', 'price' => 79, 'socket' => 'AM4', 'chipset' => 'B450', 'form' => 'Micro-ATX', 'ram' => 'DDR4', 'max_speed' => 3200, 'vrm' => 70],
        ['name' => 'MSI B450M PRO-VDH MAX', 'price' => 69, 'socket' => 'AM4', 'chipset' => 'B450', 'form' => 'Micro-ATX', 'ram' => 'DDR4', 'max_speed' => 3466, 'vrm' => 65],
    ];

    // ===================== GPU DATA =====================
    protected array $gpuData = [
        // NVIDIA RTX 40 Series
        ['name' => 'NVIDIA GeForce RTX 4090', 'price' => 1599, 'vram' => 24, 'tdp' => 450, 'length' => 336, 'tier' => 100],
        ['name' => 'NVIDIA GeForce RTX 4090 FE', 'price' => 1599, 'vram' => 24, 'tdp' => 450, 'length' => 304, 'tier' => 100],
        ['name' => 'ASUS ROG Strix RTX 4090 OC', 'price' => 1999, 'vram' => 24, 'tdp' => 480, 'length' => 358, 'tier' => 100],
        ['name' => 'MSI RTX 4090 SUPRIM X', 'price' => 1899, 'vram' => 24, 'tdp' => 480, 'length' => 340, 'tier' => 100],
        ['name' => 'NVIDIA GeForce RTX 4080 Super', 'price' => 999, 'vram' => 16, 'tdp' => 320, 'length' => 304, 'tier' => 92],
        ['name' => 'ASUS TUF RTX 4080 Super OC', 'price' => 1099, 'vram' => 16, 'tdp' => 320, 'length' => 305, 'tier' => 92],
        ['name' => 'NVIDIA GeForce RTX 4080', 'price' => 899, 'vram' => 16, 'tdp' => 320, 'length' => 304, 'tier' => 90],
        ['name' => 'MSI RTX 4080 GAMING X TRIO', 'price' => 1049, 'vram' => 16, 'tdp' => 340, 'length' => 337, 'tier' => 90],
        ['name' => 'NVIDIA GeForce RTX 4070 Ti Super', 'price' => 799, 'vram' => 16, 'tdp' => 285, 'length' => 267, 'tier' => 85],
        ['name' => 'NVIDIA GeForce RTX 4070 Super', 'price' => 599, 'vram' => 12, 'tdp' => 220, 'length' => 267, 'tier' => 78],
        ['name' => 'NVIDIA GeForce RTX 4070', 'price' => 499, 'vram' => 12, 'tdp' => 200, 'length' => 244, 'tier' => 72],
        ['name' => 'NVIDIA GeForce RTX 4060 Ti', 'price' => 399, 'vram' => 8, 'tdp' => 160, 'length' => 240, 'tier' => 65],
        ['name' => 'NVIDIA GeForce RTX 4060', 'price' => 299, 'vram' => 8, 'tdp' => 115, 'length' => 240, 'tier' => 55],
        // AMD RX 7000 Series
        ['name' => 'AMD Radeon RX 7900 XTX', 'price' => 899, 'vram' => 24, 'tdp' => 355, 'length' => 287, 'tier' => 88],
        ['name' => 'AMD Radeon RX 7900 XT', 'price' => 749, 'vram' => 20, 'tdp' => 315, 'length' => 276, 'tier' => 82],
        ['name' => 'AMD Radeon RX 7900 GRE', 'price' => 549, 'vram' => 16, 'tdp' => 260, 'length' => 267, 'tier' => 75],
        ['name' => 'AMD Radeon RX 7800 XT', 'price' => 449, 'vram' => 16, 'tdp' => 263, 'length' => 267, 'tier' => 70],
        ['name' => 'AMD Radeon RX 7700 XT', 'price' => 349, 'vram' => 12, 'tdp' => 245, 'length' => 260, 'tier' => 62],
        ['name' => 'AMD Radeon RX 7600', 'price' => 249, 'vram' => 8, 'tdp' => 165, 'length' => 240, 'tier' => 50],
    ];

    // ===================== RAM DATA =====================
    protected array $ramData = [
        ['name' => 'G.Skill Trident Z5 RGB DDR5-7200 32GB', 'price' => 189, 'type' => 'DDR5', 'capacity' => 32, 'speed' => 7200, 'modules' => 2, 'tier' => 95],
        ['name' => 'G.Skill Trident Z5 Neo DDR5-6400 32GB', 'price' => 149, 'type' => 'DDR5', 'capacity' => 32, 'speed' => 6400, 'modules' => 2, 'tier' => 88],
        ['name' => 'Corsair Dominator Titanium DDR5-6600 32GB', 'price' => 179, 'type' => 'DDR5', 'capacity' => 32, 'speed' => 6600, 'modules' => 2, 'tier' => 90],
        ['name' => 'Corsair Vengeance DDR5-6000 32GB', 'price' => 119, 'type' => 'DDR5', 'capacity' => 32, 'speed' => 6000, 'modules' => 2, 'tier' => 82],
        ['name' => 'Kingston Fury Beast DDR5-5600 32GB', 'price' => 99, 'type' => 'DDR5', 'capacity' => 32, 'speed' => 5600, 'modules' => 2, 'tier' => 75],
        ['name' => 'Crucial DDR5-4800 32GB', 'price' => 79, 'type' => 'DDR5', 'capacity' => 32, 'speed' => 4800, 'modules' => 2, 'tier' => 65],
        ['name' => 'G.Skill Trident Z5 RGB DDR5-6400 64GB', 'price' => 279, 'type' => 'DDR5', 'capacity' => 64, 'speed' => 6400, 'modules' => 2, 'tier' => 92],
        ['name' => 'Corsair Vengeance DDR5-5200 64GB', 'price' => 199, 'type' => 'DDR5', 'capacity' => 64, 'speed' => 5200, 'modules' => 2, 'tier' => 80],
        ['name' => 'Kingston Fury Beast DDR5-4800 64GB', 'price' => 159, 'type' => 'DDR5', 'capacity' => 64, 'speed' => 4800, 'modules' => 2, 'tier' => 72],
        ['name' => 'TeamGroup T-Force Delta DDR5-6000 32GB', 'price' => 109, 'type' => 'DDR5', 'capacity' => 32, 'speed' => 6000, 'modules' => 2, 'tier' => 78],
        // DDR4 RAM
        ['name' => 'G.Skill Trident Z RGB DDR4-3600 32GB', 'price' => 89, 'type' => 'DDR4', 'capacity' => 32, 'speed' => 3600, 'modules' => 2, 'tier' => 85],
        ['name' => 'Corsair Vengeance LPX DDR4-3200 32GB', 'price' => 69, 'type' => 'DDR4', 'capacity' => 32, 'speed' => 3200, 'modules' => 2, 'tier' => 78],
        ['name' => 'G.Skill Ripjaws V DDR4-3600 16GB', 'price' => 49, 'type' => 'DDR4', 'capacity' => 16, 'speed' => 3600, 'modules' => 2, 'tier' => 75],
        ['name' => 'Kingston Fury Beast DDR4-3200 16GB', 'price' => 45, 'type' => 'DDR4', 'capacity' => 16, 'speed' => 3200, 'modules' => 2, 'tier' => 70],
        ['name' => 'Corsair Vengeance RGB Pro DDR4-3600 32GB', 'price' => 99, 'type' => 'DDR4', 'capacity' => 32, 'speed' => 3600, 'modules' => 2, 'tier' => 82],
        ['name' => 'TeamGroup T-Force Vulcan Z DDR4-3200 16GB', 'price' => 39, 'type' => 'DDR4', 'capacity' => 16, 'speed' => 3200, 'modules' => 2, 'tier' => 65],
        ['name' => 'Crucial Ballistix DDR4-3600 32GB', 'price' => 79, 'type' => 'DDR4', 'capacity' => 32, 'speed' => 3600, 'modules' => 2, 'tier' => 80],
        ['name' => 'G.Skill Ripjaws V DDR4-3200 32GB', 'price' => 59, 'type' => 'DDR4', 'capacity' => 32, 'speed' => 3200, 'modules' => 2, 'tier' => 75],
        ['name' => 'Patriot Viper Steel DDR4-4400 16GB', 'price' => 89, 'type' => 'DDR4', 'capacity' => 16, 'speed' => 4400, 'modules' => 2, 'tier' => 88],
    ];

    // ===================== STORAGE DATA =====================
    protected array $storageData = [
        ['name' => 'Samsung 990 Pro 2TB NVMe', 'price' => 179, 'capacity' => 2000, 'interface' => 'NVMe Gen4', 'read' => 7450, 'tier' => 95],
        ['name' => 'Samsung 990 Pro 1TB NVMe', 'price' => 109, 'capacity' => 1000, 'interface' => 'NVMe Gen4', 'read' => 7450, 'tier' => 95],
        ['name' => 'WD Black SN850X 2TB NVMe', 'price' => 159, 'capacity' => 2000, 'interface' => 'NVMe Gen4', 'read' => 7300, 'tier' => 93],
        ['name' => 'WD Black SN850X 1TB NVMe', 'price' => 89, 'capacity' => 1000, 'interface' => 'NVMe Gen4', 'read' => 7300, 'tier' => 93],
        ['name' => 'Seagate FireCuda 530 2TB NVMe', 'price' => 169, 'capacity' => 2000, 'interface' => 'NVMe Gen4', 'read' => 7300, 'tier' => 92],
        ['name' => 'Crucial T700 2TB NVMe', 'price' => 249, 'capacity' => 2000, 'interface' => 'NVMe Gen5', 'read' => 12400, 'tier' => 98],
        ['name' => 'Crucial T700 1TB NVMe', 'price' => 159, 'capacity' => 1000, 'interface' => 'NVMe Gen5', 'read' => 11700, 'tier' => 97],
        ['name' => 'Samsung 980 Pro 1TB NVMe', 'price' => 79, 'capacity' => 1000, 'interface' => 'NVMe Gen4', 'read' => 7000, 'tier' => 88],
        ['name' => 'Kingston NV2 2TB NVMe', 'price' => 89, 'capacity' => 2000, 'interface' => 'NVMe Gen4', 'read' => 3500, 'tier' => 70],
        ['name' => 'Crucial P3 Plus 1TB NVMe', 'price' => 59, 'capacity' => 1000, 'interface' => 'NVMe Gen4', 'read' => 5000, 'tier' => 75],
    ];

    // ===================== PSU DATA =====================
    protected array $psuData = [
        ['name' => 'Corsair RM1000x 1000W 80+ Gold', 'price' => 189, 'wattage' => 1000, 'efficiency' => '80+ Gold', 'modular' => 'Full', 'tier' => 92],
        ['name' => 'Corsair HX1000i 1000W 80+ Platinum', 'price' => 269, 'wattage' => 1000, 'efficiency' => '80+ Platinum', 'modular' => 'Full', 'tier' => 95],
        ['name' => 'Corsair RM850x 850W 80+ Gold', 'price' => 149, 'wattage' => 850, 'efficiency' => '80+ Gold', 'modular' => 'Full', 'tier' => 90],
        ['name' => 'EVGA SuperNOVA 850 G7 850W', 'price' => 139, 'wattage' => 850, 'efficiency' => '80+ Gold', 'modular' => 'Full', 'tier' => 88],
        ['name' => 'Seasonic FOCUS GX-850 850W', 'price' => 149, 'wattage' => 850, 'efficiency' => '80+ Gold', 'modular' => 'Full', 'tier' => 90],
        ['name' => 'be quiet! Straight Power 12 750W', 'price' => 139, 'wattage' => 750, 'efficiency' => '80+ Platinum', 'modular' => 'Full', 'tier' => 88],
        ['name' => 'Corsair RM750x 750W 80+ Gold', 'price' => 119, 'wattage' => 750, 'efficiency' => '80+ Gold', 'modular' => 'Full', 'tier' => 85],
        ['name' => 'MSI MAG A750GL 750W 80+ Gold', 'price' => 99, 'wattage' => 750, 'efficiency' => '80+ Gold', 'modular' => 'Full', 'tier' => 82],
        ['name' => 'EVGA SuperNOVA 650 G6 650W', 'price' => 89, 'wattage' => 650, 'efficiency' => '80+ Gold', 'modular' => 'Full', 'tier' => 78],
        ['name' => 'Corsair CX650M 650W 80+ Bronze', 'price' => 69, 'wattage' => 650, 'efficiency' => '80+ Bronze', 'modular' => 'Semi', 'tier' => 70],
    ];

    // ===================== CASE DATA =====================
    protected array $caseData = [
        ['name' => 'NZXT H9 Elite', 'price' => 249, 'form' => 'ATX,Micro-ATX,Mini-ITX', 'gpu_length' => 435, 'cooler_height' => 185, 'radiator' => 360],
        ['name' => 'Lian Li O11 Dynamic EVO', 'price' => 169, 'form' => 'ATX,Micro-ATX,Mini-ITX', 'gpu_length' => 420, 'cooler_height' => 167, 'radiator' => 360],
        ['name' => 'Corsair 5000D Airflow', 'price' => 174, 'form' => 'ATX,Micro-ATX,Mini-ITX', 'gpu_length' => 400, 'cooler_height' => 170, 'radiator' => 360],
        ['name' => 'Fractal Design Torrent', 'price' => 199, 'form' => 'ATX,E-ATX,Micro-ATX', 'gpu_length' => 461, 'cooler_height' => 188, 'radiator' => 360],
        ['name' => 'NZXT H7 Flow', 'price' => 129, 'form' => 'ATX,Micro-ATX,Mini-ITX', 'gpu_length' => 400, 'cooler_height' => 185, 'radiator' => 360],
        ['name' => 'Phanteks Eclipse G360A', 'price' => 99, 'form' => 'ATX,Micro-ATX,Mini-ITX', 'gpu_length' => 380, 'cooler_height' => 160, 'radiator' => 360],
        ['name' => 'Corsair 4000D Airflow', 'price' => 104, 'form' => 'ATX,Micro-ATX,Mini-ITX', 'gpu_length' => 360, 'cooler_height' => 170, 'radiator' => 360],
        ['name' => 'Lian Li Lancool II Mesh', 'price' => 109, 'form' => 'ATX,Micro-ATX,Mini-ITX', 'gpu_length' => 384, 'cooler_height' => 176, 'radiator' => 360],
        ['name' => 'be quiet! Pure Base 500DX', 'price' => 109, 'form' => 'ATX,Micro-ATX,Mini-ITX', 'gpu_length' => 369, 'cooler_height' => 190, 'radiator' => 360],
        ['name' => 'Cooler Master NR200P Max', 'price' => 399, 'form' => 'Mini-ITX', 'gpu_length' => 330, 'cooler_height' => 82, 'radiator' => 280],
    ];

    // ===================== COOLING DATA =====================
    protected array $coolingData = [
        ['name' => 'NZXT Kraken Z73 360mm AIO', 'price' => 279, 'type' => 'Liquid AIO', 'tdp_rating' => 350, 'radiator' => 360, 'sockets' => 'AM5,LGA1700'],
        ['name' => 'Corsair iCUE H150i Elite 360mm', 'price' => 189, 'type' => 'Liquid AIO', 'tdp_rating' => 320, 'radiator' => 360, 'sockets' => 'AM5,LGA1700'],
        ['name' => 'ASUS ROG Ryujin III 360mm', 'price' => 349, 'type' => 'Liquid AIO', 'tdp_rating' => 350, 'radiator' => 360, 'sockets' => 'AM5,LGA1700'],
        ['name' => 'Arctic Liquid Freezer II 360', 'price' => 119, 'type' => 'Liquid AIO', 'tdp_rating' => 300, 'radiator' => 360, 'sockets' => 'AM5,LGA1700'],
        ['name' => 'NZXT Kraken X63 280mm AIO', 'price' => 159, 'type' => 'Liquid AIO', 'tdp_rating' => 280, 'radiator' => 280, 'sockets' => 'AM5,LGA1700'],
        ['name' => 'Corsair H100i Elite 240mm', 'price' => 149, 'type' => 'Liquid AIO', 'tdp_rating' => 250, 'radiator' => 240, 'sockets' => 'AM5,LGA1700'],
        ['name' => 'Noctua NH-D15', 'price' => 109, 'type' => 'Air Tower', 'tdp_rating' => 250, 'radiator' => 0, 'sockets' => 'AM5,LGA1700'],
        ['name' => 'Noctua NH-D15S', 'price' => 99, 'type' => 'Air Tower', 'tdp_rating' => 250, 'radiator' => 0, 'sockets' => 'AM5,LGA1700'],
        ['name' => 'be quiet! Dark Rock Pro 4', 'price' => 89, 'type' => 'Air Tower', 'tdp_rating' => 250, 'radiator' => 0, 'sockets' => 'AM5,LGA1700'],
        ['name' => 'Thermalright Peerless Assassin 120', 'price' => 35, 'type' => 'Air Tower', 'tdp_rating' => 220, 'radiator' => 0, 'sockets' => 'AM5,LGA1700'],
    ];

    // ===================== MOUSE DATA =====================
    protected array $mouseData = [
        ['name' => 'Logitech G Pro X Superlight 2', 'price' => 159, 'dpi' => 32000, 'weight' => 60, 'wireless' => true, 'brand' => 'Logitech'],
        ['name' => 'Razer DeathAdder V3 Pro', 'price' => 149, 'dpi' => 30000, 'weight' => 64, 'wireless' => true, 'brand' => 'Razer'],
        ['name' => 'Logitech G502 X Plus', 'price' => 159, 'dpi' => 25600, 'weight' => 106, 'wireless' => true, 'brand' => 'Logitech'],
        ['name' => 'Razer Viper V2 Pro', 'price' => 149, 'dpi' => 30000, 'weight' => 58, 'wireless' => true, 'brand' => 'Razer'],
        ['name' => 'SteelSeries Aerox 5 Wireless', 'price' => 139, 'dpi' => 18000, 'weight' => 74, 'wireless' => true, 'brand' => 'SteelSeries'],
        ['name' => 'Pulsar X2 Mini Wireless', 'price' => 109, 'dpi' => 26000, 'weight' => 52, 'wireless' => true, 'brand' => 'Pulsar'],
        ['name' => 'Finalmouse UltralightX', 'price' => 189, 'dpi' => 26000, 'weight' => 40, 'wireless' => true, 'brand' => 'Finalmouse'],
        ['name' => 'Logitech G Pro Wired', 'price' => 69, 'dpi' => 16000, 'weight' => 83, 'wireless' => false, 'brand' => 'Logitech'],
        ['name' => 'Razer DeathAdder Essential', 'price' => 29, 'dpi' => 6400, 'weight' => 96, 'wireless' => false, 'brand' => 'Razer'],
        ['name' => 'HyperX Pulsefire Haste', 'price' => 49, 'dpi' => 16000, 'weight' => 59, 'wireless' => false, 'brand' => 'HyperX'],
    ];

    // ===================== MOUSEPAD DATA =====================
    protected array $mousepadData = [
        ['name' => 'Artisan Hien FX XL', 'price' => 59, 'size' => 'XL', 'surface' => 'Cloth', 'brand' => 'Artisan'],
        ['name' => 'Logitech G840 XL', 'price' => 49, 'size' => 'XXL', 'surface' => 'Cloth', 'brand' => 'Logitech'],
        ['name' => 'SteelSeries QcK Heavy XXL', 'price' => 39, 'size' => 'XXL', 'surface' => 'Cloth', 'brand' => 'SteelSeries'],
        ['name' => 'Razer Gigantus V2 XXL', 'price' => 29, 'size' => 'XXL', 'surface' => 'Cloth', 'brand' => 'Razer'],
        ['name' => 'Corsair MM700 RGB', 'price' => 59, 'size' => 'XXL', 'surface' => 'Cloth', 'brand' => 'Corsair'],
        ['name' => 'Pulsar ParaSpeed V2 XL', 'price' => 35, 'size' => 'XL', 'surface' => 'Cloth', 'brand' => 'Pulsar'],
        ['name' => 'Razer Strider XXL', 'price' => 49, 'size' => 'XXL', 'surface' => 'Hybrid', 'brand' => 'Razer'],
        ['name' => 'Glorious 3XL Extended', 'price' => 49, 'size' => '3XL', 'surface' => 'Cloth', 'brand' => 'Glorious'],
    ];

    // ===================== HEADSET DATA =====================
    protected array $headsetData = [
        ['name' => 'SteelSeries Arctis Nova Pro Wireless', 'price' => 349, 'type' => 'Wireless', 'surround' => '7.1', 'driver' => 40, 'brand' => 'SteelSeries'],
        ['name' => 'Logitech G Pro X 2 Lightspeed', 'price' => 249, 'type' => 'Wireless', 'surround' => '7.1', 'driver' => 50, 'brand' => 'Logitech'],
        ['name' => 'Razer BlackShark V2 Pro', 'price' => 179, 'type' => 'Wireless', 'surround' => '7.1', 'driver' => 50, 'brand' => 'Razer'],
        ['name' => 'HyperX Cloud III Wireless', 'price' => 169, 'type' => 'Wireless', 'surround' => '7.1', 'driver' => 53, 'brand' => 'HyperX'],
        ['name' => 'Corsair Virtuoso RGB XT', 'price' => 269, 'type' => 'Wireless', 'surround' => '7.1', 'driver' => 50, 'brand' => 'Corsair'],
        ['name' => 'Audio-Technica ATH-M50xBT2', 'price' => 199, 'type' => 'Wireless', 'surround' => 'Stereo', 'driver' => 45, 'brand' => 'Audio-Technica'],
        ['name' => 'SteelSeries Arctis 7+', 'price' => 169, 'type' => 'Wireless', 'surround' => '7.1', 'driver' => 40, 'brand' => 'SteelSeries'],
        ['name' => 'HyperX Cloud II', 'price' => 99, 'type' => 'Wired', 'surround' => '7.1', 'driver' => 53, 'brand' => 'HyperX'],
        ['name' => 'Logitech G435', 'price' => 79, 'type' => 'Wireless', 'surround' => 'Stereo', 'driver' => 40, 'brand' => 'Logitech'],
        ['name' => 'Razer Kraken V3 X', 'price' => 69, 'type' => 'Wired', 'surround' => '7.1', 'driver' => 40, 'brand' => 'Razer'],
    ];

    // ===================== MICROPHONE DATA =====================
    protected array $microphoneData = [
        ['name' => 'Shure SM7B', 'price' => 399, 'type' => 'Dynamic XLR', 'pattern' => 'Cardioid', 'brand' => 'Shure'],
        ['name' => 'Elgato Wave:3', 'price' => 149, 'type' => 'USB Condenser', 'pattern' => 'Cardioid', 'brand' => 'Elgato'],
        ['name' => 'Blue Yeti X', 'price' => 169, 'type' => 'USB Condenser', 'pattern' => 'Multi', 'brand' => 'Blue'],
        ['name' => 'Rode NT-USB+', 'price' => 169, 'type' => 'USB Condenser', 'pattern' => 'Cardioid', 'brand' => 'Rode'],
        ['name' => 'HyperX QuadCast S', 'price' => 159, 'type' => 'USB Condenser', 'pattern' => 'Multi', 'brand' => 'HyperX'],
        ['name' => 'Audio-Technica AT2020USB+', 'price' => 129, 'type' => 'USB Condenser', 'pattern' => 'Cardioid', 'brand' => 'Audio-Technica'],
        ['name' => 'Razer Seiren V3 Chroma', 'price' => 129, 'type' => 'USB Condenser', 'pattern' => 'Cardioid', 'brand' => 'Razer'],
        ['name' => 'Fifine K669B', 'price' => 29, 'type' => 'USB Condenser', 'pattern' => 'Cardioid', 'brand' => 'Fifine'],
    ];

    // ===================== KEYBOARD DATA =====================
    protected array $keyboardData = [
        ['name' => 'Wooting 60HE+', 'price' => 189, 'type' => 'Hall Effect', 'layout' => '60%', 'wireless' => false, 'brand' => 'Wooting'],
        ['name' => 'Logitech G Pro X TKL', 'price' => 199, 'type' => 'Mechanical', 'layout' => 'TKL', 'wireless' => true, 'brand' => 'Logitech'],
        ['name' => 'Razer Huntsman V3 Pro TKL', 'price' => 229, 'type' => 'Optical', 'layout' => 'TKL', 'wireless' => false, 'brand' => 'Razer'],
        ['name' => 'Corsair K100 RGB', 'price' => 229, 'type' => 'Mechanical', 'layout' => 'Full', 'wireless' => false, 'brand' => 'Corsair'],
        ['name' => 'SteelSeries Apex Pro TKL', 'price' => 189, 'type' => 'Magnetic', 'layout' => 'TKL', 'wireless' => false, 'brand' => 'SteelSeries'],
        ['name' => 'Ducky One 3 SF', 'price' => 129, 'type' => 'Mechanical', 'layout' => '65%', 'wireless' => false, 'brand' => 'Ducky'],
        ['name' => 'Keychron Q1 Pro', 'price' => 199, 'type' => 'Mechanical', 'layout' => '75%', 'wireless' => true, 'brand' => 'Keychron'],
        ['name' => 'HyperX Alloy Origins 65', 'price' => 99, 'type' => 'Mechanical', 'layout' => '65%', 'wireless' => false, 'brand' => 'HyperX'],
        ['name' => 'Razer BlackWidow V4 75%', 'price' => 189, 'type' => 'Mechanical', 'layout' => '75%', 'wireless' => false, 'brand' => 'Razer'],
        ['name' => 'Logitech G915 TKL', 'price' => 229, 'type' => 'Mechanical', 'layout' => 'TKL', 'wireless' => true, 'brand' => 'Logitech'],
    ];

    // ===================== MONITOR DATA =====================
    protected array $monitorData = [
        ['name' => 'ASUS ROG Swift PG27AQDM 27" 240Hz OLED', 'price' => 999, 'size' => 27, 'resolution' => '2560x1440', 'refresh' => 240, 'panel' => 'OLED', 'brand' => 'ASUS'],
        ['name' => 'LG UltraGear 27GR95QE 27" 240Hz OLED', 'price' => 899, 'size' => 27, 'resolution' => '2560x1440', 'refresh' => 240, 'panel' => 'OLED', 'brand' => 'LG'],
        ['name' => 'Samsung Odyssey G9 49" 240Hz', 'price' => 1299, 'size' => 49, 'resolution' => '5120x1440', 'refresh' => 240, 'panel' => 'VA', 'brand' => 'Samsung'],
        ['name' => 'BenQ Zowie XL2546K 24.5" 240Hz', 'price' => 499, 'size' => 24.5, 'resolution' => '1920x1080', 'refresh' => 240, 'panel' => 'TN', 'brand' => 'BenQ'],
        ['name' => 'ASUS ROG Swift PG32UCDM 32" 4K OLED', 'price' => 1299, 'size' => 32, 'resolution' => '3840x2160', 'refresh' => 240, 'panel' => 'OLED', 'brand' => 'ASUS'],
        ['name' => 'LG 27GP850-B 27" 165Hz', 'price' => 399, 'size' => 27, 'resolution' => '2560x1440', 'refresh' => 165, 'panel' => 'IPS', 'brand' => 'LG'],
        ['name' => 'Dell S2722DGM 27" 165Hz', 'price' => 299, 'size' => 27, 'resolution' => '2560x1440', 'refresh' => 165, 'panel' => 'VA', 'brand' => 'Dell'],
        ['name' => 'Gigabyte M27Q X 27" 240Hz', 'price' => 429, 'size' => 27, 'resolution' => '2560x1440', 'refresh' => 240, 'panel' => 'IPS', 'brand' => 'Gigabyte'],
        ['name' => 'MSI MAG274QRF-QD 27" 165Hz', 'price' => 399, 'size' => 27, 'resolution' => '2560x1440', 'refresh' => 165, 'panel' => 'IPS', 'brand' => 'MSI'],
        ['name' => 'AOC 24G2 24" 144Hz', 'price' => 169, 'size' => 24, 'resolution' => '1920x1080', 'refresh' => 144, 'panel' => 'IPS', 'brand' => 'AOC'],
    ];

    // ===================== WEBCAM DATA =====================
    protected array $webcamData = [
        ['name' => 'Elgato Facecam Pro 4K', 'price' => 299, 'resolution' => '4K', 'fps' => 60, 'brand' => 'Elgato'],
        ['name' => 'Logitech Brio 4K Pro', 'price' => 199, 'resolution' => '4K', 'fps' => 30, 'brand' => 'Logitech'],
        ['name' => 'Razer Kiyo Pro Ultra', 'price' => 299, 'resolution' => '4K', 'fps' => 30, 'brand' => 'Razer'],
        ['name' => 'Sony ZV-E10 (Webcam Mode)', 'price' => 699, 'resolution' => '4K', 'fps' => 30, 'brand' => 'Sony'],
        ['name' => 'Logitech C922 Pro', 'price' => 99, 'resolution' => '1080p', 'fps' => 30, 'brand' => 'Logitech'],
        ['name' => 'Razer Kiyo', 'price' => 99, 'resolution' => '1080p', 'fps' => 30, 'brand' => 'Razer'],
        ['name' => 'Logitech StreamCam', 'price' => 169, 'resolution' => '1080p', 'fps' => 60, 'brand' => 'Logitech'],
        ['name' => 'Elgato Facecam', 'price' => 169, 'resolution' => '1080p', 'fps' => 60, 'brand' => 'Elgato'],
    ];

    // ===================== SPEAKERS DATA =====================
    protected array $speakersData = [
        ['name' => 'Audioengine A2+ Wireless', 'price' => 269, 'type' => '2.0', 'watts' => 60, 'brand' => 'Audioengine'],
        ['name' => 'Logitech G560 RGB', 'price' => 199, 'type' => '2.1', 'watts' => 240, 'brand' => 'Logitech'],
        ['name' => 'Creative Pebble Plus 2.1', 'price' => 39, 'type' => '2.1', 'watts' => 8, 'brand' => 'Creative'],
        ['name' => 'Razer Nommo V2 Pro', 'price' => 499, 'type' => '2.1', 'watts' => 65, 'brand' => 'Razer'],
        ['name' => 'Edifier R1280T', 'price' => 99, 'type' => '2.0', 'watts' => 42, 'brand' => 'Edifier'],
        ['name' => 'SteelSeries Arena 7', 'price' => 299, 'type' => '2.1', 'watts' => 100, 'brand' => 'SteelSeries'],
        ['name' => 'Logitech Z407', 'price' => 79, 'type' => '2.1', 'watts' => 80, 'brand' => 'Logitech'],
        ['name' => 'Klipsch ProMedia 2.1 THX', 'price' => 179, 'type' => '2.1', 'watts' => 200, 'brand' => 'Klipsch'],
    ];

    protected array $images = [
        'CPU' => ['https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=400', 'https://images.unsplash.com/photo-1555617981-dac3880eac6e?w=400'],
        'Motherboard' => ['https://images.unsplash.com/photo-1518770660439-4636190af475?w=400', 'https://images.unsplash.com/photo-1640872017887-e3f9d4b2d0c3?w=400'],
        'GPU' => ['https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=400', 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=400'],
        'RAM' => ['https://images.unsplash.com/photo-1562976540-1502c2145186?w=400', 'https://images.unsplash.com/photo-1563203369-26f2e4a5ccf7?w=400'],
        'Storage' => ['https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=400', 'https://images.unsplash.com/photo-1531492746076-161ca9bcad58?w=400'],
        'PSU' => ['https://images.unsplash.com/photo-1587202372583-49330a15584d?w=400', 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=400'],
        'Case' => ['https://images.unsplash.com/photo-1587202372616-b43abea06c2a?w=400', 'https://images.unsplash.com/photo-1600861194942-f883de0dfe96?w=400'],
        'Cooling' => ['https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=400', 'https://images.unsplash.com/photo-1555617981-dac3880eac6e?w=400'],
        'Mouse' => ['https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400', 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=400'],
        'Mousepad' => ['https://images.unsplash.com/photo-1589578228447-e1a4e481c6c8?w=400'],
        'Headset' => ['https://images.unsplash.com/photo-1599669454699-248893623440?w=400', 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=400'],
        'Microphone' => ['https://images.unsplash.com/photo-1590602847861-f357a9332bbc?w=400', 'https://images.unsplash.com/photo-1598550476439-6847785fcea6?w=400'],
        'Keyboard' => ['https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=400', 'https://images.unsplash.com/photo-1618384887929-16ec33fab9ef?w=400'],
        'Monitor' => ['https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=400', 'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?w=400'],
        'Webcam' => ['https://images.unsplash.com/photo-1587826080692-f439cd0b70da?w=400'],
        'Speakers' => ['https://images.unsplash.com/photo-1545454675-3531b543be5d?w=400', 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=400'],
    ];

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'slug' => fn (array $attributes) => \Illuminate\Support\Str::slug($attributes['name']) . '-' . \Illuminate\Support\Str::random(6),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 50, 2000),
            // Use a random image from the CPU category as default to avoid broken placeholders
            'image' => $this->faker->randomElement($this->images['CPU']),
            'category' => 'CPU',
            'stock' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
            'specs' => [],
        ];
    }

    // ===================== STATE METHODS =====================
    
    public function cpu(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->cpuData);
            return [
                'name' => $data['name'],
                // Convert USD to IQD (approx 1500 rate) and round to nearest 1000
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000, 
                'category' => 'CPU',
                'image' => $this->faker->randomElement($this->images['CPU']),
                'specs' => [
                    'facts' => [
                        'brand' => str_contains($data['name'], 'AMD') ? 'AMD' : 'Intel',
                        'model' => $data['name'],
                        'cores' => $data['cores'],
                        'threads' => $data['threads'],
                        'base_clock' => round($data['boost'] - 1.5, 1),
                        'boost_clock' => $data['boost'],
                        'tdp' => $data['tdp'],
                        'socket' => $data['socket'],
                    ],
                    'needs' => [
                        'socket' => $data['socket'],
                        'min_psu_wattage' => $data['tdp'] * 3,
                        'supported_memory_type' => $data['socket'] === 'AM4' ? 'DDR4' : 'DDR5',
                    ],
                    'provides' => [
                        'performance_tier' => $data['tier'],
                        'threads' => $data['threads'],
                    ],
                    'limits' => [
                        'max_memory_speed' => $data['socket'] === 'AM5' ? 6400 : ($data['socket'] === 'AM4' ? 3600 : 5600),
                        'supported_chipsets' => $data['socket'] === 'AM5' 
                            ? ['X670E', 'X670', 'B650E', 'B650'] 
                            : ($data['socket'] === 'AM4' 
                                ? ['X570', 'B550', 'B450', 'X470', 'B350']
                                : ['Z790', 'Z690', 'B760', 'B660']),
                    ],
                    'meta' => [
                        'generation' => str_contains($data['name'], 'AMD') 
                            ? ($data['socket'] === 'AM4' ? 'Ryzen 5000' : 'Ryzen 7000/9000') 
                            : 'Intel 13th/14th Gen',
                        'architecture' => str_contains($data['name'], 'AMD') 
                            ? ($data['socket'] === 'AM4' ? 'Zen 3' : 'Zen 4/5') 
                            : 'Raptor Lake',
                        'longevity_score' => $data['tier'] - 5,
                        'recommended_cooler_tdp' => $data['tdp'],
                    ],
                    // Legacy flat format for backward compatibility
                    'socket' => $data['socket'],
                    'tdp' => $data['tdp'],
                    'cores' => $data['cores'],
                ],
            ];
        });
    }

    public function motherboard(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->motherboardData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'Motherboard',
                'image' => $this->faker->randomElement($this->images['Motherboard']),
                'specs' => [
                    'facts' => [
                        'brand' => explode(' ', $data['name'])[0],
                        'model' => $data['name'],
                        'socket' => $data['socket'],
                        'memory_type' => $data['ram'],
                        'memory_slots' => 4,
                        'max_memory_speed' => $data['max_speed'],
                        'form_factor' => $data['form'],
                        'vrm_power_delivery' => $data['vrm'],
                        'pcie_version' => 5.0,
                    ],
                    'needs' => [
                        'cpu_socket' => $data['socket'],
                        'ram_type' => $data['ram'],
                    ],
                    'provides' => [
                        'supports_socket' => $data['socket'],
                        'max_memory_speed' => $data['max_speed'],
                        'pcie_lanes' => 24,
                        'vrm_power_delivery' => $data['vrm'],
                    ],
                    'limits' => [
                        'max_ram_capacity' => 128,
                        'supported_chipsets' => [$data['chipset']],
                        'max_gpu_length' => 330,
                    ],
                    'meta' => [
                        'generation' => $data['socket'] === 'AM5' ? 'Ryzen 7000' : 'Intel 12th-14th Gen',
                        'upgrade_path' => 'DDR5 platform',
                        'performance_tier' => $data['vrm'] > 150 ? 90 : ($data['vrm'] > 100 ? 75 : 60),
                    ],
                    // Legacy
                    'socket' => $data['socket'],
                    'chipset' => $data['chipset'],
                    'form_factor' => $data['form'],
                    'memory_type' => $data['ram'],
                    'max_memory_speed' => $data['max_speed'] . 'MHz',
                ],
            ];
        });
    }

    public function gpu(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->gpuData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'GPU',
                'image' => $this->faker->randomElement($this->images['GPU']),
                'specs' => [
                    'facts' => [
                        'brand' => str_contains($data['name'], 'AMD') ? 'AMD' : 'NVIDIA',
                        'model' => $data['name'],
                        'memory_gb' => $data['vram'],
                        'length_mm' => $data['length'],
                        'tdp' => $data['tdp'],
                        'pcie_version' => 4.0,
                        'slot_width' => $data['tdp'] > 300 ? 3 : 2,
                    ],
                    'needs' => [
                        'psu_min_wattage' => $data['tdp'] + 400,
                        'case_max_length' => $data['length'],
                        'cpu_performance_tier_min' => max(50, $data['tier'] - 30),
                    ],
                    'provides' => [
                        'performance_tier' => $data['tier'],
                        'vram' => $data['vram'],
                    ],
                    'limits' => [
                        'max_length' => $data['length'],
                        'slot_width' => $data['tdp'] > 300 ? 3 : 2,
                    ],
                    'meta' => [
                        'generation' => str_contains($data['name'], 'RTX 4') ? 'Ada Lovelace' : 'RDNA 3',
                        'architecture' => str_contains($data['name'], 'AMD') ? 'AMD' : 'NVIDIA',
                        'longevity_score' => min(95, $data['tier'] + 5),
                    ],
                    // Legacy
                    'tdp' => $data['tdp'],
                    'length' => $data['length'],
                    'vram' => $data['vram'] . 'GB',
                ],
            ];
        });
    }

    public function ram(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->ramData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'RAM',
                'image' => $this->faker->randomElement($this->images['RAM']),
                'specs' => [
                    'facts' => [
                        'brand' => explode(' ', $data['name'])[0],
                        'model' => $data['name'],
                        'type' => $data['type'],
                        'capacity_gb' => $data['capacity'],
                        'speed_mhz' => $data['speed'],
                        'modules' => $data['modules'],
                        'voltage' => 1.35,
                    ],
                    'needs' => [
                        'memory_type' => $data['type'],
                        'motherboard_max_speed' => $data['speed'],
                    ],
                    'provides' => [
                        'capacity_gb' => $data['capacity'],
                        'speed_mhz' => $data['speed'],
                    ],
                    'limits' => [
                        'max_speed' => $data['speed'],
                    ],
                    'meta' => [
                        'performance_tier' => $data['tier'],
                    ],
                    // Legacy
                    'type' => $data['type'],
                    'capacity' => $data['capacity'] . 'GB',
                    'speed' => $data['speed'] . 'MHz',
                ],
            ];
        });
    }

    public function storage(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->storageData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'Storage',
                'image' => $this->faker->randomElement($this->images['Storage']),
                'specs' => [
                    'facts' => [
                        'brand' => explode(' ', $data['name'])[0],
                        'model' => $data['name'],
                        'capacity_gb' => $data['capacity'],
                        'interface' => $data['interface'],
                        'read_speed' => $data['read'],
                        'write_speed' => $data['read'] - 500,
                    ],
                    'needs' => [
                        'm2_slot' => true,
                    ],
                    'provides' => [
                        'capacity_gb' => $data['capacity'],
                        'read_speed' => $data['read'],
                    ],
                    'limits' => [],
                    'meta' => [
                        'performance_tier' => $data['tier'],
                    ],
                    // Legacy
                    'interface' => $data['interface'],
                    'capacity' => $data['capacity'] >= 1000 ? ($data['capacity']/1000) . 'TB' : $data['capacity'] . 'GB',
                ],
            ];
        });
    }

    public function psu(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->psuData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'PSU',
                'image' => $this->faker->randomElement($this->images['PSU']),
                'specs' => [
                    'facts' => [
                        'brand' => explode(' ', $data['name'])[0],
                        'model' => $data['name'],
                        'wattage' => $data['wattage'],
                        'efficiency' => $data['efficiency'],
                        'modular' => $data['modular'],
                        'atx_version' => '3.0',
                    ],
                    'needs' => [
                        'system_total_power' => 0,
                    ],
                    'provides' => [
                        'wattage' => $data['wattage'],
                        'efficiency' => $data['efficiency'],
                        'atx_version' => '3.0',
                    ],
                    'limits' => [],
                    'meta' => [
                        'headroom_recommended_pct' => 20,
                        'performance_tier' => $data['tier'],
                    ],
                    // Legacy
                    'wattage' => $data['wattage'],
                    'efficiency' => $data['efficiency'],
                ],
            ];
        });
    }

    public function case(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->caseData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'Case',
                'image' => $this->faker->randomElement($this->images['Case']),
                'specs' => [
                    'facts' => [
                        'brand' => explode(' ', $data['name'])[0],
                        'model' => $data['name'],
                        'form_factor_support' => explode(',', $data['form']),
                        'max_gpu_length_mm' => $data['gpu_length'],
                        'max_cooler_height_mm' => $data['cooler_height'],
                        'radiator_support_mm' => $data['radiator'],
                        'airflow_rating' => 'High',
                    ],
                    'needs' => [],
                    'provides' => [
                        'motherboard_support' => explode(',', $data['form']),
                        'max_gpu_length' => $data['gpu_length'],
                        'max_cooler_height' => $data['cooler_height'],
                        'radiator_support' => $data['radiator'],
                    ],
                    'limits' => [],
                    'meta' => [
                        'upgrade_friendly' => true,
                    ],
                    // Legacy
                    'motherboard_support' => $data['form'],
                    'max_gpu_length' => $data['gpu_length'],
                    'radiator_support' => $data['radiator'],
                ],
            ];
        });
    }

    public function cooling(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->coolingData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'Cooling',
                'image' => $this->faker->randomElement($this->images['Cooling']),
                'specs' => [
                    'facts' => [
                        'brand' => explode(' ', $data['name'])[0],
                        'model' => $data['name'],
                        'type' => $data['type'],
                        'tdp_rating' => $data['tdp_rating'],
                        'radiator_size' => $data['radiator'],
                        'height_mm' => $data['radiator'] > 0 ? 55 : 165,
                    ],
                    'needs' => [
                        'cpu_tdp' => 0,
                        'case_max_height' => $data['radiator'] > 0 ? 55 : 165,
                    ],
                    'provides' => [
                        'tdp_rating' => $data['tdp_rating'],
                    ],
                    'limits' => [
                        'max_height_mm' => $data['radiator'] > 0 ? 55 : 165,
                    ],
                    'meta' => [
                        'noise_level' => 'Low',
                        'performance_tier' => min(95, (int)($data['tdp_rating'] / 3)),
                    ],
                    // Legacy
                    'type' => $data['type'],
                    'socket_support' => $data['sockets'],
                    'radiator_size' => $data['radiator'],
                    'tdp_rating' => $data['tdp_rating'],
                ],
            ];
        });
    }

    public function mouse(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->mouseData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'Mouse',
                'image' => $this->faker->randomElement($this->images['Mouse']),
                'specs' => [
                    'brand' => $data['brand'],
                    'dpi' => $data['dpi'],
                    'weight' => $data['weight'] . 'g',
                    'wireless' => $data['wireless'] ? 'Yes' : 'No',
                    'sensor' => 'Optical',
                ],
            ];
        });
    }

    public function mousepad(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->mousepadData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'Mousepad',
                'image' => $this->faker->randomElement($this->images['Mousepad']),
                'specs' => [
                    'brand' => $data['brand'],
                    'size' => $data['size'],
                    'surface' => $data['surface'],
                ],
            ];
        });
    }

    public function headset(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->headsetData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'Headset',
                'image' => $this->faker->randomElement($this->images['Headset']),
                'specs' => [
                    'brand' => $data['brand'],
                    'type' => $data['type'],
                    'surround' => $data['surround'],
                    'driver' => $data['driver'] . 'mm',
                ],
            ];
        });
    }

    public function microphone(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->microphoneData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'Microphone',
                'image' => $this->faker->randomElement($this->images['Microphone']),
                'specs' => [
                    'brand' => $data['brand'],
                    'type' => $data['type'],
                    'pattern' => $data['pattern'],
                ],
            ];
        });
    }

    public function keyboard(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->keyboardData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'Keyboard',
                'image' => $this->faker->randomElement($this->images['Keyboard']),
                'specs' => [
                    'brand' => $data['brand'],
                    'switch_type' => $data['type'],
                    'layout' => $data['layout'],
                    'wireless' => $data['wireless'] ? 'Yes' : 'No',
                ],
            ];
        });
    }

    public function monitor(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->monitorData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'Monitor',
                'image' => $this->faker->randomElement($this->images['Monitor']),
                'specs' => [
                    'brand' => $data['brand'],
                    'size' => $data['size'] . '"',
                    'resolution' => $data['resolution'],
                    'refresh_rate' => $data['refresh'] . 'Hz',
                    'panel' => $data['panel'],
                ],
            ];
        });
    }

    public function webcam(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->webcamData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'Webcam',
                'image' => $this->faker->randomElement($this->images['Webcam']),
                'specs' => [
                    'brand' => $data['brand'],
                    'resolution' => $data['resolution'],
                    'fps' => $data['fps'] . ' FPS',
                ],
            ];
        });
    }

    public function speakers(): static
    {
        return $this->state(function (array $attributes) {
            $data = $this->faker->randomElement($this->speakersData);
            return [
                'name' => $data['name'],
                'price' => ceil(($data['price'] * 1500) / 1000) * 1000,
                'category' => 'Speakers',
                'image' => $this->faker->randomElement($this->images['Speakers']),
                'specs' => [
                    'brand' => $data['brand'],
                    'type' => $data['type'],
                    'watts' => $data['watts'] . 'W',
                ],
            ];
        });
    }
}
