import Alpine from "alpinejs";
import intersect from '@alpinejs/intersect';
import collapse from '@alpinejs/collapse'
import focus from '@alpinejs/focus';
import ToastComponent from '../../vendor/usernotnull/tall-toasts/resources/js/tall-toasts';
import Uploader from "./components/uploader";
import Vapor from 'laravel-vapor';

// Alpine Components
Alpine.data('ToastComponent', ToastComponent);
Alpine.data('Uploader', Uploader);

// Plugins
Alpine.plugin(collapse)
Alpine.plugin(focus);
Alpine.plugin(intersect);

// Globals
window.Vapor = Vapor;
window.Alpine = Alpine;

// Launch!
Alpine.start();
