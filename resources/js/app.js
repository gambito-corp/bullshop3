import './bootstrap';
import './Layout';
import Alpine from 'alpinejs';
import "flowbite";
import focus from '@alpinejs/focus';
window.Alpine = Alpine;

Alpine.plugin(focus);

Alpine.start();
