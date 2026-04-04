import './bootstrap';
import Alpine from 'alpinejs';
import mask from '@alpinejs/mask'; // 1. Import pluginnya

Alpine.plugin(mask); // 2. Daftarkan pluginnya

window.Alpine = Alpine;
Alpine.start();